<?php 

require_once 'init.php';

if (!empty($_POST['chatName'])){
	try {
		$sql = "INSERT INTO `chats`(`chat_name`) VALUES( :chat_name)";
		pdoInsert($sql, ["chat_name" => $_POST['chatName']]);

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
	$sql = "SELECT * FROM chats";
	$chats = pdoFetchAll($sql);
	echo json_encode($chats);
}
