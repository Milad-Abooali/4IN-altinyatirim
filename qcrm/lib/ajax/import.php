<?php

/**
 * Import Functions Class
 */
include '../import/exlsx.php';

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

// Add expenses
function add() {
    $output = new stdClass();
    $output->e = false;
    if(!isset($_FILES['file'])) $output->e = "File expected";
    if(!$output->e){
        $time = date('Ymd_His');
        $file=$error=false;
        $name = $time."_". basename($_FILES["file"]["name"]);
        $target_file = "../import/files/".$name;
        $FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if ($_FILES["file"]["size"] > 500000000) $output->e = 'File Size';
        if ($FileType != "csv" && $FileType != "xlsx") $output->e = 'File Type must be ".csv" or ".xlsx"';
        if(!$output->e) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                $output->res = $name;

                // File 2 Array
                $data=$rows=array();
                $data = ( in_array($FileType,['csv','CSV']) ) ? _getCSV($target_file) : _getXLSX($target_file);
                $header = array_shift($data);
                foreach ($data as $k => $row) {
                    foreach($row as $c => $v) $rows[$k][$header[$c]] = $v;
                }
                if($rows) {
                    global $db;
                    $import = array(
                        'file_name' => $name,
                        'type'      => $FileType,
                        'note'      => $_POST['note'] ?? '',
                        'file_rows'      => count($rows),
                        'created_by'=> $_SESSION['id']
                    );
                    $output->res = $db->insert('import_logs', $import);
                }
            } else {
                $output->e = 'Sorry, there was an error uploading your file!';
            }
        }
    }
    echo json_encode($output);
}

