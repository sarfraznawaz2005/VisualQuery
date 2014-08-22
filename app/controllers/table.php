<?php

set_time_limit(0);

class Table
{
    private static $icon = 'fa fa-table';

    public function index()
    {
        $_SESSION['tableData'] = array();

        // Checks whether or not user is logged in
        self::checkLogin();

        // enable query profiling
        Flight::get('db')->query('SET profiling = 1;');

        // get specified table data as array
        $records = ORM::for_table(Flight::get('lastSegment'))->find_array();
        //pretty_print($records);

        // find out time above query was ran for
        $exec_time_result = Flight::get('db')->query(
           'SELECT query_id, SUM(duration) FROM information_schema.profiling GROUP BY query_id ORDER BY query_id DESC LIMIT 1;'
        );

        $exec_time_row = $exec_time_result->fetchAll(PDO::FETCH_NUM);

        // table columns
        $stmt = Flight::get('db')->query("DESCRIBE " . Flight::get('lastSegment'));
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //pretty_print($columns);

        $fields = array();
        foreach ($columns as $values) {
            if (isset($values['Field'])) {
                $fields[] = $values['Field'];
            }
        }
        //pretty_print($fields);

        // store table fields/columns + data rows in session for exporting later
        $_SESSION['tableData'] = array_merge($fields, $records);


        $fieldTypes = array();

        /*
        foreach ($columns as $values) {
            if (isset($values['Field'])) {
                $fieldTypes['type'][] = $values['Type'];
                $fieldTypes['primary'][] = $values['Key'];
            }
        }
        //pretty_print($fieldTypes, false);
        */

        $records = Presenter::listTableData($records, $fieldTypes);

        Flight::render(
           'table',
           array(
              'title' => Flight::get('lastSegment'),
              'icon' => self::$icon,
              'table_data' => $records,
              'fields' => getOptions($fields),
              'query' => SqlFormatter::format(ORM::get_last_query(ORM::DEFAULT_CONNECTION)),
              'timetaken' => $exec_time_row[0][1]
           )
        );
    }

    /**
     * Runs Custom or Visual SQL query
     */
    public function runquery()
    {
        $printArray = '';
        $query = $_POST['cquery'];

        if (isset($_POST['printArray'])) {
            $printArray = pretty_print($_POST, false, true);
        }

        // table columns
        $stmt = Flight::get('db')->query("DESCRIBE " . Flight::get('lastSegment'));
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //pretty_print($columns);

        $fields = array();
        foreach ($columns as $values) {
            if (isset($values['Field'])) {
                $fields[] = $values['Field'];
            }
        }
        //pretty_print($fields);

        // for custom query
        if ($query) {
            self::runQueryWithView($query, $fields, $printArray);

        } // for visual query
        else {

            $query = 'SELECT ';

            // find out which fields to SELECT
            if (array_key_exists('fields', $_POST) && count($_POST['fields'])) {
                $total = count($_POST['fields']);

                $duplicateNameFields = array();
                $counter = 0;

                foreach ($_POST['fields'] as $value) {
                    $counter ++;

                    if ($value) {
                        $duplicateNameFields[] = $value;

                        // apply AS keyword for same field names from different tables
                        if (in_array($value, $duplicateNameFields)) {
                            $fieldArray = explode('.', $value);
                            $tableName = $fieldArray[0];
                            $fieldName = $fieldArray[1];
                            $value = $value . ' AS ' . $tableName . '_' . $fieldName;
                        }

                        if ($total === $counter) {
                            $query .= $value;
                        } else {
                            $query .= $value . ', ';
                        }
                    }
                }
            }

            $query .= ' FROM `' . Flight::get('lastSegment') . '`';

            // find out which tables to JOIN
            if (array_key_exists('jointype', $_POST) && count($_POST['jointype'])) {
                $counter = 0;
                foreach ($_POST['jointype'] as $key => $value) {
                    $counter ++;

                    if (! $value) {
                        continue;
                    }

                    $primaryTable = Flight::get('lastSegment');
                    $query .= ' ' . $value . ' ';

                    // build ON table join clause
                    if ($_POST['jointable'][$key]) {
                        $query .= $_POST['jointable'][$key];
                        $query .= ' ON ' . $primaryTable . '.`' . $_POST['joinfieldp'][$key] . '` = ' . $_POST['jointable'][$key] . '.`' . $_POST['joinfield'][$key] . '`';
                    }
                }
            }

            // find out which fields/conditions to put in in WHERE clause
            if (array_key_exists('fname', $_POST) && count($_POST['fname'])) {
                $total = count($_POST['fname']);
                $query .= ' WHERE ';

                $counter = 0;
                foreach ($_POST['fname'] as $key => $value) {
                    $counter ++;

                    if ($_POST['fvalue'][$key]) {
                        if ($total === $counter) {
                            $query .= $value . $_POST['fvalue'][$key];
                        } else {
                            $query .= $value . $_POST['fvalue'][$key] . ' ' . $_POST['ftype'][$key + 1] . ' ';
                        }
                    }

                }
            }

            // find out GROUP BY fields
            if (array_key_exists('groupfields', $_POST) && count($_POST['groupfields'])) {
                $query .= ' GROUP BY ';
                $query .= implode(', ', $_POST['groupfields']);
            }

            // find out ORDER BY fields
            if (array_key_exists('orderfields', $_POST) && count($_POST['orderfields'])) {
                $query .= ' ORDER BY ';
                $query .= implode(', ', $_POST['orderfields']);

                if (array_key_exists('chkDescending', $_POST) && $_POST['chkDescending']) {
                    $query .= ' DESC ';
                }
            }

            // find out LIMIT clause details
            if (array_key_exists('limitStart', $_POST) && $_POST['limitStart']) {
                $query .= ' LIMIT ' . $_POST['limitStart'];

                if (array_key_exists('limitNumRows', $_POST) && $_POST['limitNumRows']) {
                    $query .= ', ' . $_POST['limitNumRows'];
                }
            }

            $query = self::fixQuery($query);

            // run query and render view
            self::runQueryWithView($query, $fields, $printArray);
        }

    }

    private static function runQueryWithView($query, $fields, $printArray)
    {
        $_SESSION['tableData'] = array();

        $exec_time_row = array();
        $records = '';

        try {
            // turn on query profiling
            Flight::get('db')->query('SET profiling = 1;');

            $stmt = Flight::get('db')->query($query);

            // find out time above query was ran for
            $exec_time_result = Flight::get('db')->query(
               'SELECT query_id, SUM(duration) FROM information_schema.profiling GROUP BY query_id ORDER BY query_id DESC LIMIT 1;'
            );

            $exec_time_row = $exec_time_result->fetchAll(PDO::FETCH_NUM);

            // run query and fetch array
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // store table fields/columns + data rows in session for exporting later
            $_SESSION['tableData'] = array_merge($fields, $data);

            $records = Presenter::listTableData($data);

        } catch (PDOException $e) {
            setFlashMessage('Error: ' . $e->getMessage());
        }

        Flight::render(
           'table',
           array(
              'title' => Flight::get('lastSegment'),
              'icon' => self::$icon,
              'table_data' => $records,
              'fields' => getOptions($fields),
              'query' => SqlFormatter::format($query),
              'printArray' => $printArray,
              'timetaken' => $exec_time_row[0][1]
           )
        );
    }

    /**
     * Fix invalid queries
     *
     * @param $query
     * @return mixed|string
     */
    private static function fixQuery($query)
    {
        $query = str_replace('SELECT FROM', 'SELECT * FROM', $query);
        $query = str_replace('SELECT  FROM', 'SELECT * FROM', $query);

        $query = rtrim($query, ' OR ');
        $query = rtrim($query, ' OR');
        $query = rtrim($query, ' AND ');
        $query = rtrim($query, ' AND');
        $query = rtrim($query, ' WHERE');
        $query = rtrim($query, ' WHERE ');
        $query = rtrim($query, ',,');
        $query = rtrim($query, ',');
        $query = rtrim($query, ', ');
        $query = rtrim($query, ',  ');
        $query = rtrim($query, ' INNER');
        $query = rtrim($query, ' INNER JOI');
        $query = rtrim($query, ' INNER JOIN');
        $query = rtrim($query, ' LEFT');
        $query = rtrim($query, ' LEFT JOIN');
        $query = rtrim($query, ' RIGH');
        $query = rtrim($query, ' RIGHT JOIN');
        $query = rtrim($query, ' FU');
        $query = rtrim($query, ' FULL');
        $query = rtrim($query, ' FULL JOIN');

        return $query;
    }

    private static function checkLogin()
    {
        if (! isset($_SESSION['logged'])) {
            Flight::redirect('./login');
        }
    }
}