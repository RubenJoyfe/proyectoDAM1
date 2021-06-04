<?php 
	session_start();
	if (isset($_SESSION['codbaja'])) {
		session_destroy();
		header("Location: index.php");
		exit;
	}
	if (isset($_SESSION['usrNick'])) {
		$usrNick = $_SESSION['usrNick'];

		if (isset($_SESSION['usrDinero'])) {  //coger dinero si existe (está declarado)
			$dineros=$_SESSION['usrDinero'];
		}
	}
	else {
		header('Location: ../login/login.php');
		exit;
	}
	$db = new mysqli("localhost:3306", "root", "", "h15af00");
	if ($db->connect_errno) {
	    echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	}
	$dtsql = "SELECT oscuro FROM ajustes JOIN usuario ON ajustes.fk_usuario = usuario.id_usuario WHERE usuario.nick LIKE '".$usrNick."'";
	$tema = $db->query($dtsql);
	$tema = $tema->fetch_assoc();
	$_SESSION['usrTema'] = $tema['oscuro'];
	$usrTema = $_SESSION['usrTema'];
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
  <link rel="stylesheet" type="text/css" href="rubiclogo.css">
  <link rel="stylesheet" type="text/css" href="left.css">
  <link rel="stylesheet" type="text/css" href="styles.css">
  <link rel="stylesheet" type="text/css" href="dark.css">

</head>
<body>
	<div class="left">
		<div class="toggle <?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo "toogleDark";} ?>"></div>
	  <section class="sect shide <?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo "leftDark";} ?>">
	  	<div class="navegacion">
	  		<ul class="menu">
	  			<li>
					<a href="../index.php">
						<span class="icon">
							<div id="rubic_wrapper">
								<div id="rubic">
									<div id="face1"></div>
									<div id="face2"></div>
									<div id="face3"></div>
									<div id="face4"></div>
									<div id="face5"></div>
									<div id="face6"></div>
								</div>
							</div>
						</span>
						<span class="titulo">PuzzleGames</span>
					</a>
				</li>
				<li>
					<a href="../index.php">
						<span class="icon"><i class="fas fa-home"></i></span>
						<span class="titulo">Home</span>
					</a>
				</li>
				<li>
					<a href="#">
						<span class="icon"><i class="fas fa-gamepad"></i></span>
						<span class="titulo">Games</span>
					</a>
				</li>
				<li>
					<a href="#">
						<span class="icon"><i class="fas fa-star"></i></span>
						<span class="titulo">Favorites</span>
					</a>
				</li>
				<?php
					if (!isset($_SESSION['usrNick'])) {
				 		echo "
							<li>
								<a href='..\login\login.php'>
									<span class='icon'><i class='fas fa-user'></i></span>
									<span class='titulo'>Account</span>
								</a>
							</li>
							";
				 	} 
				 ?>
				
				<li>
					<a href="..\login\logout.php">
						<span class="icon"><i class="fas fa-sign-out-alt"></i></span>
						<span class="titulo">Cerrar sesión</span>
					</a>
				</li>
				<?php
					// if (isset($_SESSION['usrNick'])) {
				 // 		echo "
			 	// 		<li class='cuenta'>
					// 		<a href='.\cuenta\cuenta.php'>
					// 			<span class='iconC'><i class='fas fa-user-circle'></i></span>
					// 			<span class='nombreUsr'>$usrNick</span>
					// 			<span class='dineros'><i class='fas fa-coins'></i>";if (isset($_SESSION['usrDinero'])){echo $dineros;}echo"</span>
					// 		</a>
					// 	</li>
					// 		";
				 // 	} 
				 ?>

			</ul>
	  	</div>
	  	
	    <div class="ola ola1"></div>
	    <div class="ola ola2"></div>
	    <div class="ola ola3"></div>
	    <div class="ola ola4"></div>
	  </section>
	</div>
	<div class="top-menu">

		<ul>
		<?php
			if (isset($_SESSION['usrNick'])) {
		 		echo "
		 		
		 			<li class='cuenta'>
						<a href='cuenta.php'"; if(isset($_SESSION['usrTema']) && $usrTema==1){echo "class='darkUsr'";}echo "> 
							<span class='iconC'><i class='fas fa-user-circle'></i></span>
							<span class='nombreUsr'>$usrNick</span>
							<span class='dineros'><i class='fas fa-coins'></i>";if (isset($_SESSION['usrDinero'])){echo $dineros;}echo"</span>
						</a>
					</li>
				
					";
		 	} 
		 ?>
		<form id="formSearch" method="GET" action="..\index.php" >
			<div class="flexbox">
				<div class="search">
					<div id="divbq">
						<input id="busqueda" name="search" type="text" placeholder="Buscar . . ." required>
					</div>
				</div>
			</div>
		</form>
		 </ul>
		<div class="content-menu"></div>
	</div>
	<div class="content" >
	<h2 style="font-weight: 500; font-size: 30px">Configuración de cuenta</h2>
	<br><br>
		<div class="conf">
			<hr>
			<div class="info">Modo oscuro</div>
			<div class="activable">
				<label class="switch">
				  <input type="checkbox" id="darksw" <?php if ($tema['oscuro']==1) {
				  	echo "checked";
				  } ?>>
				  <span class="slider round" ></span>
				  <!-- https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_switch -->
				</label>
			</div>
			<hr>
		</div>
		<div class="conf">
			<div class="info">Idioma
			</div>
			<hr>
		</div>
		<div class="conf">
			<div class="contPerf">
				<div class="fotoPerfil">
					<div id="edit"><i class="far fa-edit"></i></div>
				</div>
				<?php
					$db = new mysqli("localhost:3306", "root", "", "h15af00");
					$consulta = "SELECT * FROM usuario WHERE nick LIKE ? ;";
					$stmt = $db->prepare($consulta);
					$stmt->bind_param("s", $usrNick);
					$stmt->execute();
					$resultado = $stmt->get_result();
					$dt = mysqli_fetch_array($resultado);

				//Zona submit Guardar perfil
					if (isset($_POST['usuario'])) {
						$sqlGuardar = "CALL GuardarUsuario(?, ?, ?, ?, ?, ?, ?, ?, @res)";
						$stmt = $db->prepare($sqlGuardar);
						$stmt->bind_param("ssssssss", $usrNick, $_POST['usuario'], $_POST['pw'], $_POST['newpw'],$_POST['nombre'],$_POST['ape1'],$_POST['ape2'],$_POST['correo']);
						$stmt->execute();
						$res = mysqli_query($db,"SELECT @res as resultado");
						$res = mysqli_fetch_array($res);
						$rs = $res["resultado"];
						/*
						TRATAR ERRORES DE $rs
						*/
						header('Location: ./cuenta.php');
						exit;
					}
				// FIN Zona submit Guardar perfil
				 ?>
				<div class="infoP" style="padding-top: 0;">
					<form id="perfil" method="POST" action="cuenta.php">
						<input class="btnGuardar" type="submit" name="guardar" value="Guardar">
						<p>Usuario</p>
						<input type="text" name="usuario" value="<?php echo $usrNick;?>"placeholder="Nombre">
						<p>Correo</p>
						<input type="text" name="correo" value="<?php echo $dt['correo'];?>" placeholder="Correo">
						<p>Nombre</p>
						<input type="text" name="nombre" value="<?php echo $dt['nombre'];?>"placeholder="Nombre">
						<p>Apellidos</p>
						<input type="text" name="ape1" value="<?php echo $dt['apellido_1'];?>"
						placeholder="Primer apellido">
						<input type="text" name="ape2" value="<?php echo $dt['apellido_2'];?>" placeholder="Segundo apellido">
						<p>Contraseña</p>
						<input type="password" name="pw" value="<?php ?>" placeholder="Contraseña">
						<input type="password" name="newpw" value="<?php ?>" placeholder="Nueva contraseña">
					</form>
				</div>
			</div>
			<hr>
		</div>
		<div class="conf">
			<div id="txtDel" class="info">Suspender cuenta</div>
			<div class="dangerButton">
				<input id="btnBaja" type="button" name="delete" value="Dar de baja">
			</div>
			<hr>
		</div>
		
			
	</div>
	<div id="bgalerta">
		<div class="alerta">
			<form id="eliminar" method="POST" action="..\login\baja.php">
				<div class="advertencia">
					<i class="fas fa-exclamation-triangle"></i>
					<span>Ten cuidado, piensalo bien . . .</span>
				</div>
				<div class="msg">
					<span>
						¿Está seguro de que desea dar de baja su cuenta?
						<br>
						<span id="revert">
							(Este cambio puede revertirse en el futuro por un módico precio)
						</span>
						<div class="img">
							<img src="missyou.jpg">	
						</div>
						
					</span>
				</div>
				<input id="cancelar" type="button" name="cancelar" value="Cancelar">
				<input id="confirmar" type="submit" name="confirmar" value="Confirmar">
			</form>
		</div>
	</div>
	<div class="imagen<?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo " dark";} ?>"></div>
</body>
<script type="text/javascript">
	const left = document.querySelector('.left');
	const section = document.querySelector('.sect');
	const ul = document.querySelector('.menu');
	const content = document.querySelector('.content');
	document.querySelector('.toggle').onclick = function(){
		this.classList.toggle('Tactive');
		ul.classList.toggle('Tactive');
		content.classList.toggle('Tactive');
		left.classList.toggle('shide2');
		section.classList.toggle('shide');
	}
</script>
<script type="text/javascript" src="confx.js"></script>
</html>