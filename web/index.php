<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>prueba olas</title>
  
  <script src="https://kit.fontawesome.com/0ec605ed6f.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">

  <link rel="stylesheet" type="text/css" href="rubiclogo.css">
  <link rel="stylesheet" type="text/css" href="left.css">
  <link rel="stylesheet" type="text/css" href="styles.css">

</head>
<body>
	<div class="left">
		<div class="toggle"></div>
	  <section class="sect">
	  	<div class="navegacion">
	  		<ul class="menu">
	  			<li>
					<a href="#">
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
					<a href="#">
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
				<li>
					<a href=".\login\login.html">
						<span class="icon"><i class="fas fa-user"></i></span>
						<span class="titulo">Account</span>
					</a>
				</li>
				<li>
					<a href="#">
						<span class="icon"><i class="fas fa-sign-out-alt"></i></span>
						<span class="titulo">Cerrar sesión</span>
					</a>
				</li>
			</ul>
	  	</div>
	  	
	    <div class="ola ola1"></div>
	    <div class="ola ola2"></div>
	    <div class="ola ola3"></div>
	    <div class="ola ola4"></div>
	  </section>
	</div>
	
	<div class="content" >
<!-- 			<h1>JUEGOS</h1>
			<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p> -->

			<?php 
				// creación de la conexión a la base de datos con mysql_connect()
				$conexion = mysqli_connect( "localhost", "Ruben", 1234 ) or die ("No se ha podido conectar al servidor de Base de datos");
				// Selección del a base de datos a utilizar
				$db = mysqli_select_db( $conexion, "h15af00" ) or die ( "Upps! No se ha podido conectar a la base de datos" );
				// establecer y realizar consulta. guardamos en variable.
				$consulta = "SELECT * FROM juego";
				$resultado = mysqli_query( $conexion, $consulta);

				$resultado->num_rows;


				while ($columna = mysqli_fetch_array($resultado)) {
					$source = $columna['nombre'];
					$redireccion = "./juegos/juego.php?source=".$source."";
					echo " <a href='" . $redireccion . "'>
								<div class='juego'>
									<p>" 
									. $columna["nombre"] . 
									"</p>
								</div>
							</a>";
				}
			 ?>
			<div class="juego"> <h2>Proximamente...</h2></div>
	</div>
	<div class="imagen"></div>
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