<?php
	session_start();
	if (isset($_SESSION['codbaja'])) {
		$rs=$_SESSION['codbaja'];
		//Creo un objeto
		$rsBaja=array(
			'cod_error' => $rs
		);
		echo json_encode($rsBaja);
	}
	else {
		header("Location: ..\index.php");
		exit;
	}
 ?>