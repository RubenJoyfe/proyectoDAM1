<?php 
session_start();
	if (isset($_SESSION['usrNick'])) {
			header("Location: ..\index.php");
			exit;
		}
 ?>
<!DOCTYPE html>
<html>
<head>
 	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PuzzleGames</title>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://kit.fontawesome.com/0ec605ed6f.js" crossorigin="anonymous"></script>
	<script src="script.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">

	<link rel="shortcut icon" href="../iconwb.png">
	<link rel="stylesheet" type="text/css" href="login.css">
	<link rel="stylesheet" type="text/css" href="left.css">
	<link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>
	<div class="content" >
		<div class="recuCuenta">
			<form action="sendMail.php" method="post">
				<h2><span>Recuperar contraseña</span></h2>
				<div class="user-box">
					<input type="text" name="correo" id="emailAddress" required autocomplete="off">
					<label>Correo</label>
					<div class='perror4'><p>El correo introducido es incorrecto</p></div>
				</div>
				<div class="text">
					Ingrese el correo de su cuenta para poder cambiar su contraseña.<br><br>
					Le mandaremos un correo con las instrucciones que debe seguir.
				</div>
				<input id="btnSend" class="btnEnviar" type="submit" value="Recuperar">
			</form>
		</div>

	</div>
	<section class="sect">  	
		<div class="ola ola1"></div>
		<div class="ola ola2"></div>
		<div class="ola ola3"></div>
		<div class="ola ola4"></div>
	</section>
	<section class="example2">
		<ul class="cuadrados">
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
		</ul>
	</section>
</body>
</html>