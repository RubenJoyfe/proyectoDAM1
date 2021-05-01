<?php 
	session_start();
	if (isset($_SESSION['usrNick'])) {
		header('Location: ../index.php');
		exit;
	}
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>prueba olas</title>
  
  <script src="https://kit.fontawesome.com/0ec605ed6f.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="login.css">
  <link rel="stylesheet" type="text/css" href="left.css">
  <link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>
	<?php 
		$rs=0;
		$db = new mysqli("localhost:3306", "root", "", "h15af00");
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
					echo "string " . $rs;
				}
				else {
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
		else if (isset($_POST["usuario"]) && isset($_POST["pass"])) { //no es registro
			$usrNick =  $_POST['usuario'];
			$pswd = $_POST['pass'];
			$sqlLogin = "SELECT * FROM usuario WHERE nick LIKE ? AND contrasena LIKE ?";
			$stmt = $db->prepare($sqlLogin);
			$stmt->bind_param("ss", $usrNick, $pswd);
			$stmt->execute();
			$resultado = $stmt->get_result();

			if ($resultado->num_rows!=1) {
				header('Location: login.php?relogin=2');
				exit;
			}
			else {
				$_SESSION['usrNick'] = $usrNick;
				header('Location: ../index.php?redireccion=1');
				exit;
			}
		}

	 ?>
		<div class="content" >
			<div class="LoginZone">
				<form action="" method="post">
					<h2><span>Inicio de sesión</span></h2>
					<div class="user-box">
						<input type="text" name="usuario" required>
						<label>Usuario</label>
					</div>
					<div class="user-box">
						<input type="password" name="pass" required>
						<label>Contraseña</label>
					</div>
					<input class="btnEnviar" type="submit" value="Iniciar sesión">
				</form>
			</div>
			<div class="ra"></div>
			<div class="RegisterZone">
				<form action="" method="post">
					<h2><span>Registrarse</span></h2>
					<div class="user-box">
						<input type="text" name="correo" required autocomplete="off">
						<label>Correo</label>
						<?php 
						if($rs==-3){echo "<p class='perror'>El correo no puede estar en blanco</p>";}
						if($rs==-5){echo "<p class='perror'>El correo introducido está en uso</p>";}
						?>
						
					</div>
					<div class="user-box">
						<input type="text" name="usuario" required autocomplete="off">
						<label>Usuario</label>
						<?php
						if($rs==-1){echo "<p class='perror'>El nombre de usuario no puede estar en blanco</p>";}
						if($rs==-4){echo "<p class='perror'>El nombre de usuario introducido está en uso</p>";}
						?>
					</div>
					<div class="user-box">
						<input type="password" name="pass" required>
						<label>Contraseña</label>
						<?php if($rs==-2){
							echo "<p class='perror'>La contraseña no puede estar en blanco</p>";}
						?>
					</div>
					<input class="btnEnviar" type="submit" value="Registrarse">
				</form>
			</div>

		</div>
		<section class="sect">  	
			<div class="ola ola1"></div>
			<div class="ola ola2"></div>
			<div class="ola ola3"></div>
			<div class="ola ola4"></div>
		</section>
	
</body>

</html>