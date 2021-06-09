<?php

	if(isset($_GET['full'])){
		$path = '';
	}
	else{
		$path = 'sudoku/';
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>PuzzleGames</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $path; ?>styles.css">
</head>
<body>
	<div class="container"></div>
	<div id="controls">
		<button type="button" class="btn primary" data-action="newGame">New Game</button>
		<button type="button" class="btn primary" data-action="solve">Solve</button>
		<button type="button" class="btn primary" data-action="validate">Validate</button>
	</div>
</body>
	<script type="text/javascript" src="<?php echo $path; ?>js.js"></script>
</html>