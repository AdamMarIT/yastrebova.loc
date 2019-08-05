<?php
	require_once 'init.php';

	$smtp = $pdo->prepare("SELECT * FROM chats");
	$smtp->execute();
	$chats = $smtp->fetchAll();

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
				<h1>the list of topics</h1>
				<ol>
					<?php foreach ($chats as $chat) { ?>
					<li class="room" data-name="<?php echo base64_encode($chat["id"]); ?>"><?php echo $chat["chat_name"]; ?></li>
				<?php } ?>
				</ol>
			</div>
			<form>
				<input type="hidden" name="chat" id="chat" value="">
				<input type="hidden" name="name" id="name" value="">
			</form>
		</div>
	

<script type="text/javascript">
	$(document).ready(function () { 

	  $('.room').click(function () { 
	  	$( "#login" ).remove();
	  	$( `<div class="input-group mb-3" id="login"><input type="text" placeholder="Insert your name" id="loginName" required><div class="input-group-append"><button class="btn btn-outline-secondary" type="button" onclick="logIn()">Login</button></div></div>` ).insertAfter($(this))
	  		document.getElementById("chat").value = $(this).attr('data-name')
	  	
	  });

	});

	function logIn() {
  	$.post({
		  url: '/login.php',
		  data: {name : document.getElementById("loginName").value,
						 chat: document.getElementById("chat").value
						},
		  success: function(response){
		    document.getElementById("loginName").value = ""
		    if (!response) {
		    	document.location.replace("/topic.php");
		    } else {
		    	consol.log("enter another name")
		    }
		  },
		});
  }
</script>

	</body>
</html>