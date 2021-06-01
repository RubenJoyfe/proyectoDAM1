<?php 

if (isset($_POST['correo'])) {
	$correo = $_POST['correo'];
}
else {
	header('Location: ../index.php');
	exit;
}
	$db = new mysqli("localhost:3306", "root", "", "h15af00");
	if ($db->connect_errno) {
		echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	}
	$correosql = "SELECT nick, count(correo) AS mail FROM usuario WHERE correo LIKE ?;";
	$stmt = $db->prepare($correosql);
	$stmt->bind_param("s", $correo);
	$stmt->execute();
	$resultado = $stmt->get_result();
	$resultado = mysqli_fetch_array($resultado);
	$rs = $resultado['mail'];
	if ($rs==1) {

		$nombre = $resultado['nick'];
		$enlace = "http://localhost/1damA/web/zz_p/login/recuperarCuenta.php";

		$from = "puzzlegamesemp@hotmail.com";
		$to = $correo;
		$subject = "Recuperacion de cuenta";
		$message = "Hola " . $nombre . ", si usted no ha solicitado un reinicio de contraseña simplemente ignore este correo o elimínelo.\n En caso de haberlo solicitado haga click en el siguiente enlace: " . $enlace;
		$headers = "From: " . $from;
		if (mail($to,$subject,$message, $headers)){
			$res=1;
		}
		else {
			$res=0;
		}
	}
	else {
		$res=-1;
	}

?>

<!DOCTYPE html>
<html>
<head>
 	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>PuzzleGames</title>
	<script src="https://kit.fontawesome.com/0ec605ed6f.js" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="login.css">
	<link rel="stylesheet" type="text/css" href="left.css">
	<link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>
	<div class="content" >
		<div class="baja">
			<div class="loader">
				<div class="circle-loader showmsg <?php if($res==1){echo "success";}else{echo "failed";} ?>">
				  <div class="status draw"></div>
				</div>
			</div>
			<div class="msg">
				<p id="c_msg" class="ap">
					<?php 
						if($res==1){echo "Correo enviado exitosamente";}
						else if($res==-1){echo "El correo introducido no está registrado.";} 
						else {echo "No se ha podido enviar el correo de recuperacion, pruebe más tarde.";}
					?></p>
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
</html>