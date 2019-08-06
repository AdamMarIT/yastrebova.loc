<?php 
require_once 'init.php';

if (!empty($_POST['userId'])){
	try {
		$mysql = "DELETE FROM `users` WHERE id = :id";
		pdoInsert($pdo, $mysql, ["id" => $_POST['userId']]);
	}
	catch(PDOException $e)
	{
	   echo $e->getMessage();
	}
} else {
	$mysql = "SELECT * FROM users WHERE chat_id = :chat_id AND id > :id";
	$users = pdoFetchAll($pdo, $mysql, [
		"chat_id" => $_POST['chatId'], 
		"id" => $_POST['user']
	]);
	echo json_encode($users);
}
