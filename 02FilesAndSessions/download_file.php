<?php

session_start();
if (isset($_GET['file']) && isset($_SESSION['loggedIn'])) {
    $file_name = str_replace(array('/', '\\', '?', ':', '*', '<', '>', '|', '"'), '', rawurldecode($_GET['file']));
    $file_url = dirname(__FILE__) . '/personal_folders/' . $_SESSION['username'] . '/' . $file_name;
    if (file_exists($file_url)) {
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
        readfile($file_url);
    } else {
        //header("Location: list.php");
    }
} else {
    //header("Location: list.php");
}
?>