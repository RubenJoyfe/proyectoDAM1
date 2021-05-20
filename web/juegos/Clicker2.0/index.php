<!DOCTYPE html>
<html>
<head>
	<title>
		Clicker
	</title>
	<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/Clicker.css">
		<script type="text/javascript" src="js/Clicker.js"></script>
</head>
<body>
	<div class="juego">
		<h1>
			Clica, POR TUTATIS!!!
		</h1>
		<img src="img/por-tutatis.png">
		<br>
		<button type="button" onclick="myFun()">( O.O)</button>
		<button class="resset" type="button" onclick="reset()">( -.-)</button>
		<h1 id="counter">0</h1>
	</div>
	<div class="tienda">
		<button type="button" id="mult_button" onclick="multiplicador()">10 Dineros</button>
		<h2 class="mult_text" id="mult_counter">X1</h2>
	</div>
	
</div>
</body>
</html>