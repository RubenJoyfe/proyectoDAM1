<?php 
	require_once "../config.php";
	session_start();
	if (!isset($_GET['source'])) {
		header("Location: ../index.php");
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
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PuzzleGames</title>
  
	<link rel="shortcut icon" href="../iconwb.png">
	<link rel="stylesheet" type="text/css" href="rubiclogo.css">
	<link rel="stylesheet" type="text/css" href="left.css">
	<link rel="stylesheet" type="text/css" href="dark.css">
	<link rel="stylesheet" type="text/css" href="styles.css">
 	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">
	<!-- Tablas -->
	<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="dataTables.jqueryui.min.css">


  	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  	<!-- Tablas -->
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.25/js/dataTables.jqueryui.min.js"></script>
	<!--  -->
  	<script src="https://kit.fontawesome.com/0ec605ed6f.js" crossorigin="anonymous"></script>
 

  
  <?php 
		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		$stmt = $db->prepare("SELECT usuario.nick, juego.nombre, puntuacion.puntos, puntuacion.fecha
			FROM puntuacion JOIN juego ON puntuacion.fk_juego = juego.id_juego 
							JOIN usuario ON puntuacion.fk_usuario = usuario.id_usuario
			WHERE juego.nombre = ? ORDER BY puntuacion.puntos DESC");

		$stmt->bind_param("s", $_GET['source']);
		$stmt->execute();
		$resultado = $stmt->get_result();
		// $columna = $resultado->fetch_assoc();
   ?>
</head>
<body <?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo "class='darkbg'";} ?>>
	<div class="left shide2">
		<div class="toggle Tactive <?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo "toogleDark";} ?>"></div>
	  <section class="sect shide <?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo "leftDark";} ?>">
	  	<div class="gmnavegacion">
	  		<ul class="gmmenu Tactive">
	  			<li>
					<a href="../">
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
					<a href="#">
						<span class="icon"><i class="fas fa-star"></i></span>
						<span class="titulo">Favorites</span>
					</a>
				</li>
				<li>
					<a href="#">
						<span class="icon"><i class="fas fa-list-ol"></i></span>
						<span class="titulo">Puntuaciones</span>
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
		<div class="gmcontent-menu"></div>
	</div>
	<div class="gmcontent">
	<table id="miTabla" class="display dataTable">
	    <thead>
	        <tr>
	        	<th>Posicion</th>
	            <th>Nombre</th>
	            <th>Juego</th>
	            <th>Puntuacion</th>
	            <th>Fecha</th>
	        </tr>
	    </thead>
	    <tbody>
			<?php
			$cont = 1;
			while ($columna = $resultado->fetch_assoc()) {
				echo "<tr>";
					echo "<td>". $cont ."</td>";
					echo "<td>". $columna['nick'] ."</td>";
					echo "<td>
						<a href='../juegos/juego.php?source=".$columna['nombre']."'>
								". $columna['nombre'] ."
						</a>
					</td>";
					echo "<td>". $columna['puntos'] ."</td>";
					echo "<td>". $columna['fecha'] ."</td>";
				echo "</tr>";
				$cont++;
			}
			 ?>
	    </tbody>
	</table>
	</div>

	<div class="gmimagen<?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo " gmdark";} ?>"></div>
</body>

<script type="text/javascript">
	const left = document.querySelector('.left');
	const section = document.querySelector('.sect');
	const ul = document.querySelector('.gmmenu');
	const content = document.querySelector('.gmcontent');
	document.querySelector('.toggle').onclick = function(){
		this.classList.toggle('Tactive');
		ul.classList.toggle('Tactive');
		content.classList.toggle('Tactive');
		left.classList.toggle('shide2');
		section.classList.toggle('shide');
	}

	$(document).ready( function () {
	    $('#miTabla').DataTable();
	} );
</script>
</html>