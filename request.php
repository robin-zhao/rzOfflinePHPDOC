<?php
/*
 * Fetch result for ajax call
 *
 * @author Robin ZHAO (boborabit@gmail.com)
 * @version 1.0
 * @date 2013-02-05
 *
 */

if(!isset($_GET['key']) || empty($_GET['key'])){
    return;
}

$return = array();

switch($_GET['type']){
    case 2://search by file name
        $key = escapeshellarg($_GET['key']);
        exec("ls html/ | grep $key", $return);
        break;
    case 1://search by function name
    default:
        $key = $_GET['key'];
        $config = parse_ini_file('config.ini');
        foreach($config as $k => $v){
            if(strpos($k,$key) !== false){
                $return[] = $v;
            }
        }
        usort($return, function($a, $b) use ($key) {//show nearest result first
            if(levenshtein($key, $a) > levenshtein($key, $b)){
                return 1;
            }else{
                return -1;
            }
        });
        break;
}

echo json_encode($return);
