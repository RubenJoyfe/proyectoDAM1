<?php 
	require_once "../config.php";
	session_start();
	if (isset($_SESSION['usrNick'])) {
		$usrNick = $_SESSION['usrNick'];

		if (isset($_SESSION['usrDinero'])) {  //coger dinero si existe (está declarado)
			$dineros=$_SESSION['usrDinero'];
		}
		if (isset($_SESSION['usrTema'])) {
			$usrTema=$_SESSION['usrTema'];
		}
		else {
			$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
			if ($db->connect_errno) {
			    echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
			}
			$dtsql = "SELECT oscuro FROM ajustes JOIN usuario ON ajustes.fk_usuario = usuario.id_usuario WHERE usuario.nick LIKE '".$usrNick."'";
			$tema = $db->query($dtsql);
			$tema = $tema->fetch_assoc();
			$_SESSION['usrTema'] = $tema['oscuro'];
		}
	}
	else {
		header("Location: ../index.php");
		exit;
	}
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PuzzleGames</title>
  
	<script src="alertify/alertify.min.js"></script>
	<!-- include the style -->
	<link rel="stylesheet" href="alertify/css/alertify.min.css" />
	<!-- include a theme -->
	<link rel="stylesheet" href="alertify/css/themes/default.min.css" />

	<link rel="shortcut icon" href="../iconwb.png">
	<link rel="stylesheet" type="text/css" href="rubiclogo.css">
	<link rel="stylesheet" type="text/css" href="left.css">
	<link rel="stylesheet" type="text/css" href="dark.css">
	<link rel="stylesheet" type="text/css" href="styles.css">
 	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">
	<!--  -->
  	<script src="https://kit.fontawesome.com/0ec605ed6f.js" crossorigin="anonymous"></script>
  	<script type="text/javascript" src="js.js"></script>
 

  
  <?php 
		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		$stmt = $db->prepare("SELECT juego.* FROM usuario_juego_favorito
							JOIN usuario ON usuario.id_usuario = usuario_juego_favorito.fk_usuario
							JOIN juego ON usuario_juego_favorito.fk_juego = juego.id_juego
							WHERE usuario.nick = ? ORDER BY usuario_juego_favorito.fk_juego ASC;");

		$stmt->bind_param("s", $_SESSION['usrNick']);
		$stmt->execute();
		$resultado = $stmt->get_result();
		
   ?>
</head>
<body <?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo "class='darkbg'";} ?>>
<div class="left">
		<div class="toggle <?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo "toogleDark";} ?>"></div>
	  <section class="sect shide <?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo "leftDark";} ?>">
	  	<div class="navegacion">
	  		<ul class="menu">
	  			<li>
					<a href="index.php">
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
					<a href="../index.php">
						<span class="icon"><i class="fas fa-gamepad"></i></span>
						<span class="titulo">Games</span>
					</a>
				</li>
				<li>
					<a href="">
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
					if (isset($_SESSION['usrNick'])) {
				 		echo "
							<li>
								<a href='..\login\logout.php'>
									<span class='icon'><i class='fas fa-sign-out-alt'></i></span>
									<span class='titulo'>Cerrar sesión</span>
								</a>
							</li>
							";
				 	} 
				 ?>
			</ul>
	  	</div>
	  	
	    <div class="ola ola1"></div>
	    <div class="ola ola2"></div>
	    <div class="ola ola3"></div>
	    <div class="ola ola4"></div>
	  </section>
	</div>
	<div class="top-menu <?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo "dartkbgtop";} ?>">
		<ul>
		<?php
			if (isset($_SESSION['usrNick'])) {
		 		echo "
		 		
		 			<li class='cuenta'>
						<a href='..\cuenta\cuenta.php'"; if(isset($_SESSION['usrTema']) && $usrTema==1){echo "class='darkUsr'";}echo "> 
							<span class='iconC'><i class='fas fa-user-circle'></i></span>
							<span class='nombreUsr'>$usrNick</span>
							<span class='dineros'><i class='fas fa-coins'></i>";if (isset($_SESSION['usrDinero'])){echo $dineros;}echo"</span>
						</a>
					</li>
				
					";
		 	} 
		 ?>
		<form id="formSearch" method="GET" action="index.php" >
			<div class="flexbox">
				<div class="search">
					<div id="divbq" <?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo "class='darkSearch'";} ?>>
						<input id="busqueda" name="search" type="text" placeholder="Buscar . . ." required <?php if (isset($_GET['search'])) {echo "value='".$_GET['search']."'";} if( isset($_SESSION['usrTema']) && $usrTema==1){echo "class='darkSearch'";} ?>>
					</div>
				</div>
			</div>
		</form>
		 </ul>
		<div class="content-menu"></div>
	</div>
	<div class="content">
		<?php 
		while ($columna = mysqli_fetch_array($resultado)) {
			$source = $columna['nombre'];
			$visualizar= "
			'background-image:url(../juegos/" . $source . "/img/portada.png);
			background-size: contain;
			'";
			$gif= "
				color='red'
				
			";
			$redireccion = "../juegos/juego.php?source=".$source."";
			echo " <a href='" . $redireccion . "'>
						<div data-juego='$source' class='juego";if(isset($_SESSION['usrTema']) && $usrTema==1){echo " darkbg1";}echo "'>
							<input class='star' type='checkbox' title='bookmark page' checked value='".$columna["nombre"]."'>
							<div class='nade'>
								<div "; 
								if(isset($_SESSION['usrTema']) && $usrTema==1){echo "class='darkDesc'";}
								echo">
									<h3>" 
									. $columna["nombre"] . 
									"</h3>
									<p>".
										$columna["descripcion"]
									."</p>
								</div>
							</div>
						</div>
					</a>";

		}
		 ?>
		 <div class="lastjuego<?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo " darkbg1";} ?>"> <h2>No hay más favoritos</h2></div>
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
</html>