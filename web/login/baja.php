<?php 
require_once "../config.php";
session_start();
	if (isset($_SESSION['usrNick'])) {
			$usrNick=$_SESSION['usrNick'];
		}
		else {
			header("Location: ..\index.php");
			exit;
		}
		if (isset($_POST['confirmar'])) {
			$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ($db->connect_errno) {
			    echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
			}
			$bajasql = "call BajaUsuario(?,@res);";
			$stmt = $db->prepare($bajasql);
			$stmt->bind_param("s", $usrNick);
			$stmt->execute();
			$res = mysqli_query($db,"SELECT @res as resultado");
			$res = mysqli_fetch_array($res);
			$rs = $res["resultado"];
			$_SESSION['codbaja']=$rs;
		}
		else {
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
	<script src="baja.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="login.css">
	<link rel="stylesheet" type="text/css" href="left.css">
	<link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>
	<div class="content" >
		<div class="baja">
			<div class="loader">
				<div class="circle-loader">
				  <div class="status draw"></div>
				</div>
			</div>
			<div class="msg showmsg">
				<p id="c_msg"></p>
			</div>
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
	<div id="borrar"><?php include 'comprobarBaja.php';?></div> <!-- borra el código de error de la página web --> 
</html>