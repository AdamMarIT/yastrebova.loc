<?php

require_once 'init.php';

$path = "";

if (array_key_exists("path", $_GET)) {
	$path = $_GET['path'];
}

switch ($path) {
    case "room":
        require_once('topic.php');
        break;
    case "":
        require_once('home.php');
        break;
    case "home":
        require_once ('home.php');
        break;
    case "pdf":
        require_once('pdf.php');
        break;
    default:
        require_once('404.php');
}

?>