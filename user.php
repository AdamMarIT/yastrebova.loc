<?php 

require_once 'init.php';

if (!empty($_POST['userId'])){
	try {
		$sql = "DELETE FROM `users` WHERE id = :id";
		pdoInsert($sql, ["id" => $_POST['userId']]);

		echo json_encode([
			'success' => true
		]);
	}
	catch(PDOException $e)
	{
		echo json_encode([
			'success' => false,
			'error' => $e->getMessage()
	    ]);
	}
} else {
	$sql = "SELECT * FROM users WHERE chat_id = :chat_id AND id > :id";
	$users = pdoFetchAll($sql, [
		"chat_id" => $_POST['chatId'], 
		"id" => $_POST['user']
	]);
	echo json_encode($users);
}
