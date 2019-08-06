<?php
require_once 'init.php';
	$userName = $_POST["name"];
	$chatId = base64_decode($_POST["chat"]);

	try {
		$mysql = "INSERT INTO `users`(`name`, `chat_id`) VALUES(:name, :chat_id)";
		$params = ["name" => $userName, "chat_id" => $chatId];
		pdoInsert($pdo, $mysql, $params);
	}
	catch(PDOException $e)
	{
	   echo $e->getMessage();
	}

	$mysql = "SELECT chat_name FROM chats WHERE id = :chat_id";
	$params = ["chat_id" => $chatId];
	$chatName = pdoFetch($pdo, $mysql, $params);

	$mysql = "SELECT id FROM users WHERE chat_id = :chat_id AND name = :name";
	$params = ["chat_id" => $chatId, "name" => $userName];
	$userId = pdoFetch($pdo, $mysql, $params);
	
	$mysql = "SELECT * FROM users WHERE chat_id = :chat_id";
	$params = ["chat_id" => $chatId];
	$users = pdoFetchAll($pdo, $mysql, $params);

	$nowInChat = count($users);
	$timestamp = strtotime("now") - 60*60;
	$dateMinusHour = date('y-m-d H:i:s', $timestamp);

	$mysql = "SELECT id, message FROM messages WHERE chat_id = :chat_id AND create_at >= :hour";
	$params = ["chat_id" => $chatId, "hour" => $dateMinusHour];
	$messages = pdoFetchAll($pdo, $mysql, $params);
	
	if (empty($messages)) {
		$mysql = "SELECT id, message FROM messages WHERE chat_id = :chat_id AND id = (SELECT MAX(id) FROM messages WHERE chat_id = :chat_id)";
		$params = ["chat_id" => $chatId];
		$messages = pdoFetchAll($pdo, $mysql, $params);

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
						<?php foreach ($users as $user) { ?>
							<?php
							if ($user["name"] == $userName) { 
							    ?>
								<p><?php echo $user["name"]; ?></p>
								<?php 
							} else { 
							  ?>
							  <p class="user"><?php echo $user["name"]; ?></p>
							  <?php 
							}
							?>
						<?php } ?>
					</div>
				</div>
				<div class="col-md-9" id="messageField">
					<?php foreach ($messages as $message) { ?>
					<p class="message" data-id="<?php echo $message['id']; ?>"><?php echo $message["message"]; ?></p>
					<?php } ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3">
					<button type="button" class="btn btn-dark">leave chat</button>
				</div>
				<div class="col-md-9">
					<form>
						<div class="input-group mb-3">
							<input type="hidden" name="userName" id="userName" value="<?php echo $userName; ?>">
							<input type="text" class="form-control" placeholder="Write something" aria-label="Write something" aria-describedby="button-addon2" id="formMessage" onkeydown="clickEnter()">
							<div class="input-group-append">
								<button class="btn btn-outline-secondary" type="button" id="send" onclick="sendMessage(<?php echo $userId['id'];?>,<?php echo $chatId;?>)">Send</button>
								<button class="btn btn-outline-secondary" type="button" id="cancel" onclick="clearMessage()">Cancel</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function () { 
				let userId = '<?php echo $userId['id'];?>'
				let chatId = '<?php echo $chatId;?>'

				setTimeout(function() { 
          getMessages(chatId)
        }, 3000);

			  $('.user').click(function () { 
			  	let whom = $(this).html();
			  	console.log($(this))
			  	$('#formMessage').val(`${whom} : `);
			  	
			  });
			});

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

		  function clickEnter() {
		  	if (event.keyCode == 13) {
		  		document.getElementById('send').click()
		  	}
		  }

		  function clearMessage() {
		  	$('#formMessage').val('')
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