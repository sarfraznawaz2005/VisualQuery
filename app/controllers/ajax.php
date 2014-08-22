<?php

class Ajax
{
    /**
     * Gets fields/columns of specified table and generates dropdown options
     */
    public function gettablefields()
    {
        $table = $_POST['table'];

        if ($table) {
            // table columns
            $stmt = Flight::get('db')->query("DESCRIBE $table");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //pretty_print($columns);

            $fields = array();
            foreach ($columns as $values) {
                if (isset($values['Field'])) {
                    $fields[] = $values['Field'];
                }
            }
            //pretty_print($fields);

            echo getOptions($fields, true);
        }
    }

    /**
     * Gets fields/columns from specified tables and generates dropdown options
     */
    public function getselectfields()
    {
        $tablesJSON = $_POST['tables'];

        if ($tablesJSON) {
            $html = '';
            $tables = json_decode($tablesJSON, true);

            foreach ($tables as $table) {
                // table columns
                $stmt = Flight::get('db')->query("DESCRIBE $table");
                $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //pretty_print($columns);

                $fields = array();
                foreach ($columns as $values) {
                    if (isset($values['Field'])) {
                        $fields[] = $values['Field'];
                    }
                }
                //pretty_print($fields);

                $html .= '<optgroup label="' . $table . '">' . "\n";
                $html .= getOptions($fields, false, $table);
                $html .= '</optgroup>' . "\n";
            }

            echo $html;
        }
    }

    public function setDatabase()
    {
        $db = $_POST['db'];

        if ($db) {
            $_SESSION['db'] = $db;

            if ($_SESSION['db']) {
                echo 'ok';
            }
        }
    }
}