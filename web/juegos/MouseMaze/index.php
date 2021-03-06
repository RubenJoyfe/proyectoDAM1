<?php

  if(isset($_GET['full'])){
    $path = '';
  }
  else{
    $path = 'mousemaze/';
  }

?>
<!DOCTYPE html>
<html>
<head>
	<title>PuzzleGames</title>
	<link rel="stylesheet" type="text/css" href="<?php echo $path; ?>styles.css">
</head>
<body>
<section class="wrapper" oncontextmenu="return false">

  <section class="mobile">
    <span class="line title">The maze</span>
    <span class="line">I'm very sorry but this game is for non-touch screen only</span>
  </section>

  <section class="game">
    <input type="checkbox" class="step" id="maze--1" />
    <section class="maze maze--1">

      <section class="path path--1"></section>

      <section class="path path--finish"></section>

      <section class="path path--end"></section>

      <section class="finish">
        <span class="line">Good job</span>
        <span class="line">Le'ts see you on the <label for="maze--1">next level</label></span>
      </section>

      <section class="gameover">
        <span class="line">To finish this game, the mouse cursor should stay on the path</span>
        <span class="line">You lose!</span>
      </section>
    </section>

    <input type="checkbox" class="step" id="maze--2" />
    <section class="maze maze--2">

      <section class="path path--1"></section>

      <section class="path path--2"></section>

      <section class="path path--3"></section>

      <section class="path path--4"></section>

      <section class="path path--5"></section>

      <section class="path path--finish"></section>

      <section class="path path--end"></section>

      <section class="finish">
        <span class="line">Good job</span>
        <span class="line">Le'ts see you on the <label for="maze--2">next level</label></span>
      </section>

      <section class="gameover">
        <span class="line">To finish this game, the mouse cursor should stay on the path</span>
               <span class="line">You lose!</span>
      </section>
    </section>

    <input type="checkbox" class="step" id="maze--3" />
    <section class="maze maze--3">

      <section class="path path--1"></section>

      <section class="path path--2"></section>

      <section class="path path--3"></section>

      <section class="path path--4"></section>

      <section class="path path--5"></section>

      <section class="path path--6"></section>

      <section class="path path--7"></section>

      <section class="path path--finish"></section>

      <section class="path path--end"></section>

      <section class="finish">
        <span class="line">Good job</span>
        <span class="line">Le'ts see you on the <label for="maze--3">next level</label></span>
      </section>

      <section class="gameover">
        <span class="line">To finish this game, the mouse cursor should stay on the path</span>
        <span class="line">You lose!</span>
      </section>
    </section>

    <input type="checkbox" class="step" id="maze--4" />
    <section class="maze maze--4">

      <section class="path path--1"></section>

      <section class="path path--2"></section>

      <section class="path path--3"></section>

      <section class="path path--4"></section>

      <section class="path path--5"></section>

      <section class="path path--finish"></section>

      <section class="path path--end"></section>

      <section class="finish">
        <span class="line">Good job</span>
        <span class="line">Le'ts see you on the <label for="maze--4">next level</label></span>
      </section>

      <section class="gameover">
        <span class="line">To finish this game, the mouse cursor should stay on the path</span>
               <span class="line">You lose!</span>
      </section>
    </section>

    <input type="checkbox" class="step" id="maze--5" />
    <section class="maze maze--5">

      <section class="path path--1"></section>

      <section class="path path--2"></section>

      <section class="path path--3"></section>

      <section class="path path--4"></section>

      <section class="path path--5"></section>

      <section class="path path--finish"></section>

      <section class="finish">
        <span class="line">Good job</span>
        <span class="line">You made it till the end!</span>
        <span class="line">You won... ¬¬</span>
      </section>

      <section class="gameover">
        <span class="line">To finish this game, the mouse cursor should stay on the path</span>
               <span class="line">You lose!</span>
      </section>
    </section>
  </section>
</section>
</body>
	<script type="text/javascript" src="js.js"></script>
</html>