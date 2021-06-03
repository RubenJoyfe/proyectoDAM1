<!DOCTYPE html>
<html>
<head>
	<title>15Game</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- FONT AWESOME -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">
	<script src="https://kit.fontawesome.com/3d5343bfd9.js" crossorigin="anonymous"></script>
	<!-- ELSE -->
	<link rel="stylesheet" type="text/css" href="styles.css">
	<script type="text/javascript" src="js.js"></script>
</head>
<body>
	<div class="daddy">
		<div id="content">
			<div id="cont-game">
				<div class="top menu">
					<div>
						<img src="img/15game-logo.png">
					</div>
					<div class="selectSize">
						<button type="button" id="menos"><i class="fas fa-chevron-left"></i></button>
						<input type="text" id="option" value="4" placeholder="size">
						<button type="button" id="mas"><i class="fas fa-chevron-right"></i></button>
					</div>
					<div id="timer">
						<div>
						    <span id="hour">00</span>:
						    <span id="minute">00</span><br>
						    <span id="second">00</span>:
						    <span id="millisecond">000</span>
						</div>
					</div>
				</div>
				<div id="game">
				</div>
				<div class="bottom menu">
						<button type="button" id="rand">Desordenar</button>
						<input type="range" min="0" max="100" value="50" class="slider">
						<button type="button" id="solucion">Ordenar</button>
				</div>
			</div>
			<div id="desbloqueable">
				<div class="selectImg">
					<div class="foto"></div>
					<div class="foto"></div>
					<div class="foto"></div>
					<div class="foto"></div>
					<div class="foto"></div>
					<div class="foto"></div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>