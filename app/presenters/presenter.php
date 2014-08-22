<?php

class Presenter
{
    public static function listTables(array $array)
    {
        $html = '';

        $base = Flight::get('base');

        $counter = 0;
        foreach ($array as $arrayitem) {
            $counter ++;

            $html .= <<< HTML
            <li><a href="$base/table/$arrayitem">$arrayitem</a></li>
HTML;
        }

        return $html;
    }

    public static function listTableData(array $array, $fieldTypes = array())
    {
        //$fieldTypes = convertFieldTypesEditable($fieldTypes);

        $html = '<table class="table table-striped table-bordered table-hover">' . "\n";
        $html .= '<thead>' . "\n";

        // build headings
        foreach ($array[0] as $head => $value) {
            $html .= "<th>$head</th>" . "\n";
        }

        $html .= '</thead>' . "\n";

        // build body
        $html .= '<tbody>' . "\n";

        //pretty_print($array);

        foreach ($array as $subArray) {
            $html .= '<tr>' . "\n";

            foreach ($subArray as $value) {
                $html .= '<td style="white-space: nowrap !important;">' . $value . '</td>' . "\n";
            }

            $html .= '</tr>' . "\n";
        }

        $html .= '</tbody>' . "\n";
        $html .= '</table>' . "\n";

        return $html;
    }
}