<?php

require_once('fpdf/fpdf.php');

$userName = $_POST["name"];
$chatId = $_POST["chat"];

try {
	$sql = "INSERT INTO `users`(`name`, `chat_id`) VALUES(:name, :chat_id)";
	pdoInsert($sql, [
		"name" => $userName, 
		"chat_id" => $chatId
	]);
}
catch(PDOException $e)
{
	echo $e->getMessage();
}

$sql = "SELECT chat_name FROM chats WHERE id = :chat_id";
$chatName = pdoFetch($sql, ["chat_id" => $chatId]);

$sql = "SELECT id FROM users WHERE chat_id = :chat_id AND name = :name";
$userId = pdoFetch($sql, [
	"chat_id" => $chatId, 
	"name" => $userName
]);
	
$sql = "SELECT * FROM users WHERE chat_id = :chat_id";
$users = pdoFetchAll($sql, ["chat_id" => $chatId]);

$nowInChat = count($users);
$timestamp = strtotime("now") - 60*60;
$dateMinusHour = date('y-m-d H:i:s', $timestamp);

$sql = "SELECT id, message FROM messages WHERE chat_id = :chat_id AND create_at >= :hour";
$messages = pdoFetchAll($sql, [
	"chat_id" => $chatId, 
	"hour" => $dateMinusHour
]);
	
if (empty($messages)) {
	$sql = "SELECT id, message FROM messages WHERE chat_id = :chat_id AND id = (SELECT MAX(id) FROM messages WHERE chat_id = :chat_id)";
	$messages = pdoFetchAll($sql, ["chat_id" => $chatId]);

	if (empty($messages)) {
		$messages[0] = array("id" => '1', "message" => 'Welcome to our chat');
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Simple chat without registration</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	</head>
	<body>
		<div class="row">
			<div class="container">
				<h1><?php echo $chatName["chat_name"]; ?></h1>
			</div>
		</div>
		<div class="row">
			<div class="container">
				<div class="row chatField">
					<div class="col-md-3">
						<div>
							<p><b> Now in chat </b></p>
							<p><?php echo $nowInChat; ?></p>
						</div>
						<div>
							<p><b> People in chat </b></p>
						</div>
						<div id="userList">
							<?php foreach ($users as $user) { ?>
								<?php if ($user["name"] == $userName) { ?>
									<p class="active" data-user="<?php echo $user['id']; ?>"><?php echo $user["name"]; ?></p>
								<?php } else { ?>
									<p class="user active" data-user="<?php echo $user['id']; ?>"><?php echo $user["name"]; ?></p>
								<?php }	?>
							<?php } ?>
						</div>
					</div>
					<div class="col-md-9" id="messageField">
						<div>
							<form action="pdf" method="POST">
								<input type="hidden" name="chatId" id="chatId" value="<?php echo $chatId;?>">
								<input type="hidden" name="chatName" id="chatName" value="<?php echo $chatName['chat_name'];?>">
								<button class="btn btn-outline-secondary" type="submit" id="pdf">Download history</button>
							</form>
						</div>
						<?php foreach ($messages as $message) { ?>
						<p class="message" data-id="<?php echo $message['id']; ?>"><?php echo $message["message"]; ?></p>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<button type="button" class="btn btn-dark" onclick="logout(<?php echo $userId['id'];?>)">leave chat</button>
					</div>
					<div class="col-md-9">
						<div class="input-group mb-3">
							<input type="hidden" name="userName" id="userName" value="<?php echo $userName; ?>">
							<input type="text" class="form-control" placeholder="Write something" aria-label="Write something" aria-describedby="button-addon2" id="formMessage" onkeydown="clickEnter()">
							<div class="input-group-append">
								<button class="btn btn-outline-secondary" type="button" id="send" onclick="sendMessage(<?php echo $userId['id'];?>,<?php echo $chatId;?>)">Send</button>
								<button class="btn btn-outline-secondary" type="button" id="cancel" onclick="clearMessage()">Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</div>

		<script type="text/javascript">
			$(document).ready(function () { 
				let userId = '<?php echo $userId['id'];?>'
				let chatId = '<?php echo $chatId;?>'
				

				setInterval(function() { 
          getMessages(chatId)
          getUsers(chatId)
        }, 3000);

			  $('#userList').on('click', 'p.user', function () { 
			  	let whom = $(this).html();
			  	$('#formMessage').val(`${whom} : `);
			  	
			  });
			});

			function clickEnter() {
		  	if (event.keyCode == 13) {
		  		document.getElementById('send').click()
		  	}
		  }

			function sendMessage(userId, chatId) {
		  	$.post({
				  url: '/message.php',
				  data: {
				  				message : $('#userName').val() + ' > ' + $('#formMessage').val(),
				  				userId : userId,
				  				chatId : chatId
								},
				  success: function(response){
				  		getMessages(chatId);
				  		$('#formMessage').val('')
				  },
				});
		  }

		  function getMessages(chatId) {
		  	if ($("p.message").last().attr('data-id')) {
		  		$.post({
					  url: '/message.php',
					  data: {
					  				messageId : $("p.message").last().attr('data-id'),
					  				chatId : chatId
									},
						success: function(response){
						 		let messages = JSON.parse(response);

						 		for (key in messages) {
							 		$('#messageField').append(`<p class="message" data-id="${messages[key]['id']}">${messages[key]['message']}</p>`)
							 	}
					  },
					});
		  	}
		  }

		  function getUsers(chatId) {
		  	if ($("p.active").last().attr('data-user')) {
		  		
		  		$.post({
					  url: '/user.php',
					  data: {
					  				user : $("p.active").last().attr('data-user'),
					  				chatId : chatId
									},
						success: function(response){
								if (response) {
									let users = JSON.parse(response);
						 		
						 			for (key in users) {
								 		$('#userList').append(`<p class="user active" data-user="${users[key]['id']}">${users[key]['name']}</p>`)
								 	}
						 		}
					  },
					});
		  	}
		  }

		  function clearMessage() {
		  	$('#formMessage').val('')
		  }

		  function logout(userId) {
		  	$.post({
				  url: '/user.php',
				  data: {
				   				userId : userId
								},
				  success: function(response){
				  	window.location.href = "/";
				  },
				});
		  }
		</script>
	</body>
</html>

<style type="text/css">
	.row { margin-right: 0;
	}

	h1 {
		text-align: center;
		margin-top: 30px;
	}
	.chatField {
		min-height: 80vh;
	}

</style>