<?php
// custom functions will go here

function uriSegment($index)
{
    $request = (array) Flight::request();
    $url = explode('/', $request['url']);
    return $url[--$index];
}

// dump flightphp request object
function dump_request($exit = true)
{
    $request = Flight::request();
    echo '<pre>';
    print_r($request);
    echo '</pre>';

    if ($exit) {
        exit();
    }
}

// make dropdown out of array
function makeDropDown(array $array)
{
    $options = '';
    foreach ($array as $arrayItem) {
        $options .= "<option value=\"$arrayItem[id]\">$arrayItem[name]</option>" . PHP_EOL;
    }

    return $options;
}

// redirect flight views with a message
function flashRedirect($page, $message)
{
    setFlashMessage($message);
    Flight::redirect($page);
    exit;
}

function setFlashMessage($message)
{
    $_SESSION['__flash_message__'] = $message;
}

function getFlashMessage()
{
    return $_SESSION['__flash_message__'];
}

function clearFlashMessage()
{
    unset($_SESSION['__flash_message__']);
    $flashMessage = $_SESSION['__flash_message__'];
    unset($_SESSION['variable'], $flashMessage);
}

/**
 * pretty_print
 *
 * Prints an array in easy to read format
 *
 * @param array $array
 * @param bool $exit
 * @return mixed
 */
function pretty_print(array $array, $exit = true, $return = false)
{
    if (! $array) {
        return false;
    }

    if ($return) {
        unset($array['printArray']);
        unset($array['vquery']);
        unset($array['cquery']);

        if (! $array['limitStart']) {
            unset($array['limitStart']);
        }

        if (! $array['limitNumRows']) {
            unset($array['limitNumRows']);
        }

        $html = '<pre>';
        $html .= print_r($array, true);
        $html .= '</pre>';

        return $html;
    } else {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }

    if ($exit) {
        exit;
    }
}

/**
 * dd
 *
 * var_dumps given data and dies
 *
 * @param $data
 * @return mixed
 */
function dd($data)
{
    if (! $data) {
        return false;
    }

    echo '<pre>';
    var_dump($data);
    echo '</pre>';

    exit;
}

/**
 * logConsole
 *
 * Logs messages/variables/data to browser console from within php
 *
 * @param $name
 * @param null $data
 * @param bool $jsEval
 * @return bool
 * @author Sarfraz
 */
function logConsole($name, $data = null, $jsEval = false)
{
    if (! $name) {
        return false;
    }

    $isevaled = false;
    $type = ($data || gettype($data)) ? 'Type: ' . gettype($data) : '';

    if ($jsEval && (is_array($data) || is_object($data))) {
        $data = 'eval(' . preg_replace('#[\s\r\n\t\0\x0B]+#', '', json_encode($data)) . ')';
        $isevaled = true;
    } else {
        $data = json_encode($data);
    }

    # sanitalize
    $data = $data ? $data : '';
    $search_array = array("#'#", '#""#', "#''#", "#\n#", "#\r\n#");
    $replace_array = array('"', '', '', '\\n', '\\n');
    $data = preg_replace($search_array, $replace_array, $data);
    $data = ltrim(rtrim($data, '"'), '"');
    $data = $isevaled ? $data : ($data[0] === "'") ? $data : "'" . $data . "'";

    $js = <<<JSCODE
\n<script>
     // fallback - to deal with IE (or browsers that don't have console)
     if (! window.console) console = {};
     console.log = console.log || function(name, data){};
     // end of fallback

     console.log('$name');
     console.log('------------------------------------------');
     console.log('$type');
     console.log($data);
     console.log('\\n');
</script>
JSCODE;

    echo $js;
}

/**
 * varLog
 *
 * Logs messages/variables/data to browser by creating custom console/floating window at bottom
 *
 * @param $name
 * @param $data
 * @author Sarfraz
 */
function varLog($name, $data)
{
    $type = ($data || gettype($data)) ? gettype($data) : '';

    $output = $data;
    if (is_array($data) || is_object($data)) {
        $output = '<table style="color:#fff; font-size:14px;" width="100%"><tr><td width="100" style="border:1px solid #ccc; border-bottom:0;"><strong>Propery</strong></td><td width="100" style="border:1px solid #ccc; border-bottom:0;"><strong>Value</strong></td></tr>';

        foreach ($data as $key => $value) {
            $key = preg_replace('~[\r\n]+~', '', $key);
            $value = preg_replace('~[\r\n]+~', '', $value);

            $output .= '<table style="color:#fff; font-size:13px;" width="100%"><tr><td width="100" style="border:1px solid #ccc; border-bottom:0;">' . $key . '</td><td width="100" style="border:1px solid #ccc; border-bottom:0;">' . $value . '</td></tr></table>';
        }
    }

    $js = <<< JSCODE
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        (function () {
            var varlog = document.createElement('div');
            var stylesObject = {
                "position": "fixed",
                "bottom": "5px",
                "right": "0",
                "left": "0",
                "min-height": "100px",
                "height": "auto",
                "width": "75%",
                "margin": "auto",
                "overflow": "auto",
                "background": "rgba(11,22,33, 0.7)",
                "box-shadow": "0 0 5px 5px gray",
                "color":"#fff",
                "padding": "5px",
                "font-family": "tahoma",
                "font-size": "12px",
                "border-radius": "10px",
                "border": "1px solid white"
            };

            // set styles
            for (var style in stylesObject) {
                if (stylesObject.hasOwnProperty(style)) {
                    varlog.style[style] = stylesObject[style];
                }
            }

            // set content
            varlog.innerHTML = '<strong style="font-size:16px;">$name (type: $type)</strong><hr style="margin:0;"><br>';
            varlog.innerHTML += '$output';

            // show now
            document.body.appendChild(varlog);

        }());
    });

    </script>
JSCODE;

    echo $js;
}

function dateFormat($date, $addTime = false)
{
    if (strtotime($date)) {

        if ($addTime) {
            return date('F d, Y h:i', strtotime($date));
        }

        return date('F d, Y', strtotime($date));
    }
    return '';
}

function getTimeDate()
{
    return date('Y-m-d h:i:s');
}

function getMysqlDate($date)
{
    if (! $date) {
        return false;
    }

    return date('Y-m-d', strtotime($date));
}

function getMysqlDateTime($datetime)
{
    return date('Y-m-d h:i:s', strtotime($datetime));
}

function arrayFlatten(array $array)
{
    $flat = array(); // initialize return array
    $stack = array_values($array); // initialize stack
    while ($stack) // process stack until done
    {
        $value = array_shift($stack);
        if (is_array($value)) // a value to further process
        {
            $stack = array_merge(array_values($value), $stack);
        } else // a value to take
        {
            $flat[] = $value;
        }
    }
    return $flat;
}

function fullURL()
{
    $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
    $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
    $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
    $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER["SERVER_PORT"]);
    return $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
}

function toggleEncryption($text, $key = '')
{
    // return text unaltered if the key is blank
    if ($key == '') {
        $key = '!@a7z%^&*|+';
    }

    // remove the spaces in the key
    $key = str_replace(' ', '', $key);
    if (strlen($key) < 8) {
        exit('key error');
    }
    // set key length to be no more than 32 characters
    $key_len = strlen($key);
    if ($key_len > 32) {
        $key_len = 32;
    }

    $k = array(); // key array
    // fill key array with the bitwise AND of the ith key character and 0x1F
    for ($i = 0; $i < $key_len; ++$i) {
        $k[$i] = ord($key{$i}) & 0x1F;
    }

    // perform encryption/decryption
    for ($i = 0; $i < strlen($text); ++$i) {
        $e = ord($text{$i});
        // if the bitwise AND of this character and 0xE0 is non-zero
        // set this character to the bitwise XOR of itself
        // and the ith key element, wrapping around key length
        // else leave this character alone
        if ($e & 0xE0) {
            $text{$i} = chr($e ^ $k[$i % $key_len]);
        }
    }
    return $text;
}

function shortURL($url)
{
    $url = urlencode($url);
    $ch = curl_init(
       'http://api.bitly.com/v3/shorten?login=sarfraznawaz2005&apiKey=R_182a565c28e4eafd0e23aa113464e73e&longUrl=' . $url
    );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $resultArray = json_decode($result, true);
    $shortURL = $resultArray['data']['url'];
    return $shortURL ? $shortURL : $url;
}

function getOptions($array, $prependEmptyOption = false, $tableNameAppend = '', $database = '')
{
    $html = '';

    if ($prependEmptyOption) {
        $html = '<option value="">--select--</option>' . "\n";
    }

    foreach ($array as $arrayItem) {
        $selected = $arrayItem === $database ? 'selected' : '';

        if ($tableNameAppend) {
            $html .= '<option ' . $selected . ' value="' . $tableNameAppend . '.' . $arrayItem . '">' . $tableNameAppend . '.' . $arrayItem . '</option>' . "\n";
        } else {
            $html .= '<option ' . $selected . ' value="' . $arrayItem . '">' . $arrayItem . '</option>' . "\n";
        }
    }

    return $html;
}

function convertFieldTypesEditable(array $array)
{
    $returnArray = array();
    //pretty_print($array);

    foreach ($array as $key => $arrayItem) {
        if ($key === 'type') {
            foreach ($arrayItem as $type) {

                $prefix = substr($type, 0, 3);

                switch ($prefix) {
                    case 'int':
                    case 'big':
                    case 'sma':
                    case 'tin':
                    case 'flo':
                    case 'dou':
                    case 'var':
                    case 'cha':
                        $returnArray[$key][] = 'text';
                        break;

                    case 'text':
                        $returnArray[$key][] = 'textarea';
                        break;

                    case 'dat':
                    case 'tim':
                        $returnArray[$key][] = 'date';
                        break;

                    case 'enu':
                        $returnArray[$key][] = 'select';
                        break;

                    default:
                        $returnArray[$key][] = 'text';
                }
            }
        } else {
            $returnArray[$key] = $arrayItem;
        }
    }

    //pretty_print($returnArray);
    return $returnArray;
}

function timeoutLogout($minutes)
{
    // auto-logout after inactivity
    $timeout = $minutes * 60;
    $lastActivityTime = $_SESSION['LAST_ACTIVITY'];

    if ($lastActivityTime && (time() - $lastActivityTime > $timeout)) {
        unset($_SESSION['db']);
        unset($_SESSION['logged']);
        unset($_SESSION);
        @session_destroy();
        session_regenerate_id();

        Flight::redirect('./login');
    }

    // update last activity time stamp with each request
    $_SESSION['LAST_ACTIVITY'] = time();
}
