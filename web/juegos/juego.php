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
  <?php 

		$db = new mysqli("localhost:3306", "Ruben", "1234", "h15af00");
		
		$stmt = $db->prepare("SELECT src FROM juego WHERE nombre = ?");
		$stmt->bind_param("s", $_GET['source']);
		$stmt->execute();
		$resultado = $stmt->get_result();

		$columna = $resultado->fetch_assoc();
		$src = $_GET['source'];
   ?>
</head>
<body>
	<div class="left shide2">
		<div class="toggle Tactive"></div>
	  <section class="sect shide">
	  	<div class="navegacion">
	  		<ul class="menu Tactive">
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
					<a href="../">
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
		<div style="width: 20%; height: 85%; background-color: lightgreen;"> </div>
		<iframe id="iframe"
			<?php  
		    	echo "title=".$src."";
			?>
		    width="60%"
		    height="85%"
		    <?php 

		    echo "src='" . $columna["src"] . "'>";
		    ?>
		</iframe>
		<div style="width: 20%; height: 85%; background-color: lightgreen;"> </div>
	</div>
	
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