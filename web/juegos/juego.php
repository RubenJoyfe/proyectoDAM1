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


  <script type="text/javascript" src="shop.js"></script>
  <?php echo "<script type='text/javascript' src='".$_GET['source']."/unlock.js'></script>"; ?>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://kit.fontawesome.com/0ec605ed6f.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">

  <link rel="shortcut icon" href="../iconwb.png">
  <link rel="stylesheet" type="text/css" href="rubiclogo.css">
  <link rel="stylesheet" type="text/css" href="left.css">
  <link rel="stylesheet" type="text/css" href="styles.css">
  <link rel="stylesheet" type="text/css" href="dark.css">

  <?php 

		$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		
		$stmt = $db->prepare("SELECT src FROM juego WHERE nombre = ?");
		$stmt->bind_param("s", $_GET['source']);
		$stmt->execute();
		$resultado = $stmt->get_result();

		$columna = $resultado->fetch_assoc();
		$src = $_GET['source'];
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
		<div class="gmhead">
			<div></div>
			<button id="gmdesplegable"><i class="fas fa-store"></i></button>
			<div id="gmdesbloqueables" class="gmoculto">
				<p class="gmshop">Tienda</p>
				<?php 
					$stmt = $db->prepare("SELECT id_desbloqueo AS id, desbloqueo.nombre AS desbloqueable, desbloqueo.coste FROM juego JOIN desbloqueo ON juego.id_juego = desbloqueo.fk_juego WHERE juego.nombre LIKE ?");
					$stmt->bind_param("s", $_GET['source']);
					$stmt->execute();
					$desbl = $stmt->get_result();
					$numrws = mysqli_num_rows($desbl);
					for ($i=0; $i < $numrws; $i++) {
						$rs = $desbl->fetch_assoc();
						/*VER SI TIENE DESBLOQUEADO ALGUN DESBLOQUEABLE*/
							$stmt = $db->prepare("SELECT * FROM usuario_desbloqueo JOIN usuario ON usuario.id_usuario = usuario_desbloqueo.fk_usuario
								WHERE usuario.nick = ? AND usuario_desbloqueo.fk_desbloqueo = ?;");
							$stmt->bind_param("si", $usrNick, $rs['id']);
							$stmt->execute();
							$lotiene = $stmt->get_result();
							$lotiene = mysqli_num_rows($lotiene);
						/*FIN VER SI TIENE DESBLOQUEADO ALGUN DESBLOQUEABLE*/
						echo "<div class='foto' id='".$_GET['source']."' value='". $rs['id'] ."'>";
						
						echo "	<div class='gmbloqueado'>
									<span>
										<i class='fas fa-coins'></i><p class='precio'>";
										if ($lotiene!=0) {
											echo "0";
										}
										else {
											echo $rs['coste'];
										}
										
										echo "</p></span>
								</div>";

						echo "</div>";
					}
				 ?>
			</div>
		</div>
	<div class="gmcontent">
		<?php 
			require $src.'/index.php';
		?>
	</div>
	<div class="gmimagen<?php if(isset($_SESSION['usrTema']) && $usrTema==1){echo " gmdark";} ?>"></div>
<!-- 	<div class="gmblackbg">
		<div class="gmbuyAlert">
			<h2>Comprar desbloqueable</h2>
			<img class="gmbuyImg">
			<p>Precio: 1200</p>
			<input id="gmcancelar" type="button" name="cancelar" value="Cancelar">
			<input id="gmconfirmar" type="submit" name="confirmar" value="Confirmar">
		</div>
	</div> -->
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
</script>
</html>