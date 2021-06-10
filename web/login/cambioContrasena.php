<?php 
	require_once "../config.php";
	require '../vendor/autoload.php';
	use Firebase\JWT\JWT;

	if (isset($_GET['value'])) {
		$jwt = $_GET['value'];
		$key = ")·!?123TremendaContraseña321¿¡.(";
	}
	else {
		header('Location: ../index.php');
		exit;
	}


	try{
		$decoded = JWT::decode($jwt, $key, array('HS256'));
		// print_r($decoded->id);
		$caducado=0;

	}catch(\Exception $e){
		// echo 'Caught exception: ',  $e->getMessage(), "\n";
		$caducado=1;
	}
	if (isset($_POST['pass']) && isset($_POST['passConf'])) {
		$newContra = $_POST['pass'];
		$newContraRep = $_POST['passConf'];
		if ($newContra!=$newContraRep) {
			$noigual=1;
		}
		else {
			$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ($db->connect_errno) {
			    echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
			}
			$sqlPsw = "CALL CambiarContrasena(?, ?, ?, @res)";
			$stmt = $db->prepare($sqlPsw);
			$stmt->bind_param("iss", $decoded->id, $newContra, $newContraRep);
			$stmt->execute();
			$res = mysqli_query($db,"SELECT @res as resultado");
			$res = mysqli_fetch_array($res);
			$rs = $res["resultado"];
			if ($rs==0) {
				$caducado=2;
			}
		}
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

	<link rel="shortcut icon" href="../iconwb.png">
	<link rel="stylesheet" type="text/css" href="login.css">
	<link rel="stylesheet" type="text/css" href="left.css">
	<link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>
	<div class="content" >
	<?php 
		if (isset($caducado) && $caducado==0) {
			echo "
		<div class='recuCuenta'>
			<form action='' method='post'>
				<h2><span>Recuperar contraseña</span></h2>
				<div class='user-box'>
					<input type='password' name='pass' required autocomplete='off'>
					<label>Nueva contraseña</label>
				</div>
				<div class='user-box'>
					<input type='password' name='passConf' required autocomplete='off'>
					<label>Repetir contraseña</label>
				</div>
				<input id='btnSend' class='btnEnviar' type='submit' value='Cambiar'>
				";
				if (isset($rs)) {echo $rs;}
				if (isset($noigual) && $noigual==1) {
					echo "<p class='perror3'>Las contraseñas no coinciden.</p>";
				}
				echo "
			</form>
		</div>
			";
		}
		else if (isset($caducado) && $caducado==1) {
			echo "
		<div class='baja'>
			<div class='loader'>
				<div class='circle-loader showmsg failed'>
				  <div class='status draw'></div>
				</div>
			</div>
			<div class='msg'>
				<p id='c_msg' class='ap'>
				Error: el tiempo máximo ha transcurrido y la token a caducado.
				</p>
			</div>
		</div>
			";
		}
		else if (isset($caducado) && $caducado==2) {
				echo "
		<div class='baja'>
			<div class='loader'>
				<div class='circle-loader showmsg success'>
				  <div class='status draw'></div>
				</div>
			</div>
			<div class='msg'>
				<p id='c_msg' class='ap'>
				¡Su contraseña ha sido modificada!
				Haga click <a href='./login.php'>aquí</a> para iniciar sesion.
				</p>
			</div>
		</div>
			";
		}

	 ?>

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