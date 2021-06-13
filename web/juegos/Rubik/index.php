<?php

  if(isset($_GET['full'])){
    $path = '';
  }
  else{
    $path = 'Rubik/';
  }

?>
<!DOCTYPE html>
<html>
<head>
	<title>PuzzleGames</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $path; ?>styles.css">
	<script type="text/javascript" src="<?php echo $path; ?>js.js"></script>
</head>
<body>
		<div class="app">
		  <div class="menu expanded">
		    <header class="menu-header">
		      <span class="title">Game Settings</span>
		      <button class="menu-btn">
		        <span class="bar"></span>
		        <span class="bar"></span>
		        <span class="bar"></span>
		      </button>
		    </header>

		    <div class="menu-body">
		      <table class="menu-table">
		        <tbody>
		          <tr data-target="dimensions">
		            <td><span class="label">Dimensions</span></td>
		            <td>
		              <span class="fill-bar">
		                <span class="fill"></span>
		              </span>
		            </td>
		            <td>
		              <span class="input"></span>
		            </td>
		          </tr>
		          <tr data-target="randomize-factor">
		            <td><span class="label">Randomize Factor</span></td>
		            <td>
		              <span class="fill-bar">
		                <span class="fill"></span>
		              </span>
		            </td>
		            <td><span class="input"></span></td>
		          </tr>
		          <tr data-target="rotation-ticks">
		            <td><span class="label">Rotation Ticks</span></td>
		            <td>
		              <span class="fill-bar">
		                <span class="fill"></span>
		              </span>
		            </td>
		            <td><span class="input"></span></td>
		          </tr>
		          <tr>
		            <td colspan="3">
		              <div class="row right">
		                <button data-target="solve" class="btn">Solve</button>
		                <button data-target="newgame" class="btn">New Game</button>
		              </div>
		            </td>
		          </tr>
		        </tbody>
		      </table>
		    </div>
		  </div>

		  <div class="cover">
		    <div class="panel">
		      <div class="title">Enhorabuena! Has resuelto el cubo!</div>
		      <p>Dineros obtenidos: 1</p>
		    </div>
		  </div>
		</div>

	
</body>
</html>