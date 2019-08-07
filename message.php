<?php 

require_once 'init.php';
	
if (!empty($_POST['message'])){
	try {
		$sql = "INSERT INTO `messages`(`message`,`user_id`, `chat_id`) VALUES(:message, :id, :chat_id)";
		pdoInsert($sql, [
			"message" => $_POST['message'], 
			"id" => $_POST['userId'], 
			"chat_id" => $_POST['chatId']
		]);

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
	$sql = "SELECT id, message FROM messages WHERE chat_id = :chat_id AND id > :id";
	$params = [
		"chat_id" => $_POST['chatId'], 
		"id" => $_POST['messageId']
	];
	$messages = pdoFetchAll($sql, $params);
	echo json_encode($messages);
}
