<?php 
require_once 'init.php';

if (!empty($_POST['chatName'])){
	try {
		$mysql = "INSERT INTO `chats`(`chat_name`) VALUES( :chat_name)";
		pdoInsert($pdo, $mysql, ["chat_name" => $_POST['chatName']]);
	}
	catch(PDOException $e)
	{
	   echo $e->getMessage();
	}
} else {
	$mysql = "SELECT * FROM chats";
	$chats = pdoFetchAll($pdo, $mysql);
	echo json_encode($chats);
}
