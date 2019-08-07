<?php

$sql = "SELECT * FROM chats";
$chats = pdoFetchAll($sql);

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
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<h1>the list of topics</h1>
						<ol id="chatList">
							<?php foreach ($chats as $chat) { ?>
							<li class="room" data-name="<?php echo $chat['id']; ?>"><?php echo $chat["chat_name"]; ?></li>
							<?php } ?>
						</ol>
						<div class="input-group mb-3">
							<input type="text" class="form-control" aria-describedby="button-addon2" name="chatName" id="chatName" onkeydown="clickEnter()">
							<div class="input-group-append">
								<button class="btn btn-secondary" type="button" id="add" onclick="addChat()">Add new room</button>
							</div>
						</div>
					</div>
					<form id="loginForm" action="room" method="POST">
						<input type="hidden" name="chat" id="chat" value="">
						<input type="hidden" name="name" id="name" value="">
					</form>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$(document).ready(function () { 

			  $('#chatList').on("click", "li.room", function () { 
			  	$( "#login" ).remove();
			  	$( `<div class="input-group mb-3" id="login"><input type="text" placeholder="Insert your name" id="loginName" required><div class="input-group-append"><button class="btn btn-outline-secondary" type="button" onclick="logIn()">Login</button></div></div>` ).insertAfter($(this))
			  		document.getElementById("chat").value = $(this).attr('data-name')
			  });
			});

			function logIn() {
		  	document.getElementById("name").value = document.getElementById("loginName").value;
		  	document.getElementById("loginForm").submit();
		  }

		  function clickEnter() {
				if (event.keyCode == 13) {
					document.getElementById('add').click()
				}
			}

		  function addChat() {
		  	$.post({
					url: '/chat.php',
					data: {
									chatName : document.getElementById("chatName").value
								},
					success: function(response){
						getCats()
						$('#chatName').val('')
					},
				});
		  }

			function getCats() {
					$.post({
						url: '/chat.php',
						success: function(response){
							let element = document.getElementById("chatList");
							while (element.firstChild) {
							  element.removeChild(element.firstChild);
							}
							let chats = JSON.parse(response);

							for (key in chats) {
								$('#chatList').append(`<li class="room" data-name="${chats[key]['id']}">${chats[key]['chat_name']}</li>`)
							}
						},
					});
			}
		</script>
	</body>
</html>