<?php
    set_time_limit(0);

    require_once "../config.php";
    require_once 'exlsx.php';

    global $db;


    /**
     * Read CSV
     * @param $file
     * @return array
     */
    function _getCSV ($file) {
        return array_map('str_getcsv', file($file));
    }

    /**
     * Read XLSX
     * @param $file
     * @return array|bool
     */
    function _getXLSX ($file) {
        $xlsx = @(new SimpleXLSX($file));
        return $xlsx->rows();
    }

function pushQueue()
{
    global $db;
    $where = 'status="In The Queue"';
    $imports = $db->selectRow('import_logs', $where);
    if($imports){
        $db->updateId('import_logs',$imports['id'], ['status'=>'In Progress']);

        $target_file = './files/'.$imports['file_name'];
        $rows = array();
        $data = ( in_array($imports['type'],['csv','CSV']) ) ? _getCSV($target_file) : _getXLSX($target_file);
        $header = array_shift($data);
        foreach ($data as $k => $row) {
            foreach($row as $c => $v) $rows[$k][$header[$c]] = $v;
        }
        if($rows) {
            foreach ($rows as $row){
                $row['import_id'] = $imports['id'];

                usleep(500);
                // phone
                if(strlen($row['phone'])<5){
                    // Add Error
                    $db->increase('import_logs', 'error', 'id='.$imports['id'] );
                    continue;
                }

                // Check for duplicate phone
                $phone_10 = $db->escape(substr($row['phone'],-10));
                $where = " RIGHT(phone,10)='$phone_10'";
                $exist_phone = $db->exist('user_extra', $where);
                if($exist_phone) {
                    // Add Phone D
                    $db->increase('import_logs', 'phone_d', 'id='.$imports['id'] );
                    continue;
                }

                // Email
                if (filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                    $row['email'] = filter_var($row['email'], FILTER_SANITIZE_EMAIL);
                } else {
                    $row['email'] = $row['phone']."@temp.com";
                }

                // Check for duplicate email
                global $db;
                $where = "email='".$row['email']."' AND unit IN (".$row['unit'].")";
                $exist = $db->exist('users', $where);
                if($exist) {
                    // Add Email D
                    $db->increase('import_logs', 'email_d', 'id='.$imports['id'] );
                    continue;
                }

                // Name
                $row['fname'] = clearString($row['fname']);
                $row['lname'] = clearString($row['lname']);

                // Country
                $row['country'] = clearString($row['country']);

                // Unit
                $row['unit'] = $db->selectId('units', $row['unit_id'], 'name')['name'];

                GF::p($row);
                echo '<hr>';
            }
            $db->updateId('import_logs',$imports['id'], ['status'=>'Done']);
        }
    } else {
        echo "no import";
    }

}

$where = 'status="In Progress"';
$in_progress = $db->selectRow('import_logs', $where);
if($in_progress) {
    echo "In Progress:". $in_progress['id'];
} else {
    pushQueue();
}

function clearString($string)
{
    $string = filter_var($string, FILTER_SANITIZE_STRING);
    $string = preg_replace('/[^\p{L}\p{N}\s]/u', '', $string);

    $turkish = array("ı", "ğ", "ü", "ş", "ö", "ç", "Ğ", "İ", "Ş", "Ö", "Ü", "Ç");
    $english = array("i", "g", "u", "s", "o", "c", "G", "I", "S", "O", "U", "C");
    $string = str_replace($turkish, $english, $string);

    return htmlspecialchars($string, ENT_NOQUOTES, "UTF-8");
}

function clearEmail($string){
    return filter_var($string, FILTER_SANITIZE_EMAIL);
}