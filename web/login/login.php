<?php 
	require_once "../config.php";
	session_start();
	if (isset($_SESSION['usrNick'])) {
		header('Location: ../index.php');
		exit;
	}
	$again=0;
	if (isset($_GET['relogin'])) {
		if ($_GET['relogin']==1) {
			// echo "Usuario y/o contraseña incorrectos";
			$again=1;
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
	<?php 
		$rs=0;
		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ($db->connect_errno) {
		    echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
		}

		if (isset($_POST["correo"])) { //es registro
			if (isset($_POST["usuario"]) && isset($_POST["pass"])){
				$usrMail = $_POST["correo"];
				$usrNick =  $_POST['usuario'];
				$pswd = $_POST['pass'];
				if ($usrMail=="" || $usrNick=="" || $pswd=="") {
					if ($usrMail=="") {$rs=-3;}
					else if ($usrNick=="") {$rs=-1;}
					else if ($pswd=="") {$rs=-2;}
				}
				else {
					$pswd = md5($pswd);
					$sqlRegister = "CALL InsertarUsuario(?, ?, null, null, null, ?, @res)";
					$stmt = $db->prepare($sqlRegister);
					$stmt->bind_param("sss", $usrNick, $pswd, $usrMail);
					$stmt->execute();
					$res = mysqli_query($db,"SELECT @res as resultado");
					$res = mysqli_fetch_array($res);
					$rs = $res["resultado"];
				}
				// echo "a: ".$rs;
					// -1 si _nick es NULL
					// -2 si _contraseña es NULL
					// -3 si _correo es NULL
					// -4 si _nick ya está en uso
					// -5 si correo ya está en uso

			}
		}
		else if (isset($_POST["usuario"]) && isset($_POST["pass"])) { //es inicio de sesion
			$usrNick =  $_POST['usuario'];
			$pswd = $_POST['pass'];

			if ($usrNick=="" || $pswd=="") {
					if ($usrNick=="") {$rs=-6;}
					else if ($pswd=="") {$rs=-9;}
			}
			else {
				$pswd = md5($pswd);
				$sqlLogin = "SELECT * FROM usuario WHERE nick LIKE ? AND contrasena LIKE ? AND baja IS NULL";
				$stmt = $db->prepare($sqlLogin);
				$stmt->bind_param("ss", $usrNick, $pswd);
				$stmt->execute();
				$resultado = $stmt->get_result();

				if ($resultado->num_rows!=1) {
					header('Location: login.php?relogin=1');
					exit;
				}
				else {
					$disql = "SELECT dinero FROM usuario WHERE nick LIKE '".$usrNick."'";
					$dineros = $db->query($disql);
					$dineros = $dineros->fetch_assoc();
					$_SESSION['usrDinero'] = $dineros['dinero'];
					$_SESSION['usrNick'] = $usrNick;
					//coge el tema que tengas (oscuro-no oscuro)
						$dtsql = "SELECT oscuro FROM ajustes JOIN usuario ON ajustes.fk_usuario = usuario.id_usuario WHERE usuario.nick LIKE '".$usrNick."'";
						$tema = $db->query($dtsql);
						$tema = $tema->fetch_assoc();
					$_SESSION['usrTema'] = $tema['oscuro'];
					//redirige
					header('Location: ../index.php');
					exit;
				}
			}
		}

	 ?>
	<div class="content" >
		<div class="LoginZone">
			<form action="login.php" method="post">
				<h2><span>Inicio de sesión</span></h2>
				<div class="user-box">
					<input type="text" name="usuario" required>
					<label>Usuario</label>
					
					<?php
					if($rs==-6){echo "<i class='fas fa-times-circle perror2'></i>
					<div class='perror'><p>El usuario no puede estar en blanco</p></div>";}
					?>
				</div>
				<div class="user-box">
					<input type="password" name="pass" required>
					<label>Contraseña</label>
					<?php if($rs==-9){
						echo "<i class='fas fa-times-circle perror2'></i>
						<div class='perror'><p>La contraseña no puede estar en blanco</p></div>";}
					?>
				</div>
				<?php 
				if ($again==1) {
					echo "<p class='perror3'>Usuario y/o contraseña incorrectos</p>";
				}
				?>
				<input class="btnEnviar" type="submit" value="Iniciar sesión">
			</form>
			<div class="recuContra">
				<hr>
				<p>¿Olvidó su contraseña? Pulse <a href="recuperarCuenta.php">aquí</a>.</p>
			</div>
		</div>
		<div class="ra"></div>
		<div class="RegisterZone">
			<form action="login.php" method="post">
				<h2><span>Registrarse</span></h2>
				<div class="user-box">
					<input type="text" name="correo" id="emailAddress" required autocomplete="off">
					<label>Correo</label>
					<div class='perror4'><p>El correo introducido es incorrecto</p></div>
					<?php 
					if($rs==-3){echo "<div class='perror'><p>El correo no puede estar en blanco</p></div>";}
					if($rs==-5){echo "<div class='perror'><p>El correo introducido está en uso</p></div>";}
					?>
					
				</div>
				<div class="user-box">
					<input type="text" name="usuario" required autocomplete="off">
					<label>Usuario</label>
					<?php
					if($rs==-1){echo "<i class='fas fa-times-circle perror2'></i>
					<div class='perror'><p>El nombre de usuario no puede estar en blanco</p></div>";}
					if($rs==-4){echo "<i class='fas fa-times-circle perror2'></i>
					<div class='perror'><p>El nombre de usuario introducido está en uso</p></div>";}
					?>
				</div>
				<div class="user-box">
					<input type="password" name="pass" required>
					<label>Contraseña</label>
					<?php if($rs==-2){
						echo "<i class='fas fa-times-circle perror2'></i>
					<div class='perror'><p>El usuario no puede estar en blanco</p></div>";}
					?>
				</div>
				<input id="btnSend" class="btnEnviar" type="submit" value="Registrarse">
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
  <script src="script.js"></script>
</html>