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
		if (isset($_SESSION['usrTema'])) {
			$usrTema=$_SESSION['usrTema'];
		}
		else {

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

  <link rel="shortcut icon" href="iconwb.png">
  <link rel="stylesheet" type="text/css" href="rubiclogo.css">
  <link rel="stylesheet" type="text/css" href="left.css">
  <link rel="stylesheet" type="text/css" href="styles.css">
  <link rel="stylesheet" type="text/css" href="dark.css">
  <script type="text/javascript" src="js.js"></script>

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
					<a href="index.php">
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
								<a href='.\login\login.php'>
									<span class='icon'><i class='fas fa-user'></i></span>
									<span class='titulo'>Account</span>
								</a>
							</li>
							";
				 	} 
				 ?>
				
				<li>
					<a href=".\login\logout.php">
						<span class="icon"><i class="fas fa-sign-out-alt"></i></span>
						<span class="titulo">Cerrar sesión</span>
					</a>
				</li>
				<?php
					// if (isset($_SESSION['usrNick'])) {
				 	//	echo "
			 		// <li class='cuenta'>
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
	<div class="top-menu <?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo "dartkbgtop";} ?>">
		<ul>
		<?php
			if (isset($_SESSION['usrNick'])) {
		 		echo "
		 		
		 			<li class='cuenta'>
						<a href='.\cuenta\cuenta.php'"; if(isset($_SESSION['usrTema']) && $usrTema==1){echo "class='darkUsr'";}echo "> 
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
	<div class="content" >
			<?php 
				// creación de la conexión a la base de datos con mysql_connect()
				$conexion = mysqli_connect( "localhost", "Ruben", 1234 ) or die ("No se ha podido conectar al servidor de Base de datos");
				// Selección del a base de datos a utilizar
				$db = mysqli_select_db( $conexion, "h15af00" ) or die ( "Upps! No se ha podido conectar a la base de datos" );
				// establecer y realizar consulta. guardamos en variable.
				if (isset($_GET['search'])) {
					$cad = $_GET['search']."%";
					$db = new mysqli("localhost:3306", "root", "", "h15af00");
					$consulta = "SELECT * FROM juego WHERE nombre LIKE ? ;";
					$stmt = $db->prepare($consulta);
					$stmt->bind_param("s", $cad);
					$stmt->execute();
					$resultado = $stmt->get_result();
				}
				else {
					$consulta = "SELECT * FROM juego LIMIT 50";
					$resultado = mysqli_query( $conexion, $consulta);
				}

				$resultado->num_rows;
				while ($columna = mysqli_fetch_array($resultado)) {
					$source = $columna['nombre'];
					$visualizar= "
					'background-image:url(./juegos/" . $source . "/img/portada.png);
					background-size: contain;
					'";
					$gif= "
						color='red'
						
					";
					$redireccion = "./juegos/juego.php?source=".$source."";
					echo " <a href='" . $redireccion . "'>
								<div data-juego='$source' class='juego";if(isset($_SESSION['usrTema']) && $usrTema==1){echo " darkbg1";}echo "'>
									<p>" 
									. $columna["nombre"] . 
									"</p>
								</div>
							</a>";
				}
			 ?>
			<div class="lastjuego<?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo " darkbg1";} ?>"> <h2>Proximamente...</h2></div>
	</div>
	<div class="imagen<?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo " dark";} ?>"></div>
</body>
</html>