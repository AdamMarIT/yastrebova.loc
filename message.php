<?php 
require_once 'init.php';
	
	if (!empty($_POST['message'])){
		try {
			$smtp = $pdo->prepare("INSERT INTO `messages`(`message`,`user_id`, `chat_id`) VALUES(:message, :id, :chat_id)");
			$smtp->execute(["message" => $_POST['message'], "id" => $_POST['userId'], "chat_id" => $_POST['chatId']]);
		}
		catch(PDOException $e)
		{
		   echo $e->getMessage();
		}
	} else {
		$mysql = "SELECT id, message FROM messages WHERE chat_id = :chat_id AND id > :id";
		$params = ["chat_id" => $_POST['chatId'], "id" => $_POST['messageId']];
		$messages = pdoFetchAll($pdo, $mysql, $params);
		echo json_encode($messages);
	}

	
