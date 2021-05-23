<?php
	// include "checkAuth"; //comprueba la sesion
	session_start();
	if (isset($_SESSION['usrNick'])) {
		$usrNick = $_SESSION['usrNick'];

		if (isset($_SESSION['usrDinero'])) {  //coger dinero si existe (está declarado)
			$dineros=$_SESSION['usrDinero'];
		}
		if (isset($_SESSION['usrTema'])) {
			$tema = $_SESSION['usrTema']; 
		}
	}
	else {
		header('Location: ../index.php?redireccion=1');
		exit;
	}
	$data = json_decode(file_get_contents('php://input'), true);
	
	$db = new mysqli("localhost:3306", "root", "", "h15af00");
	if ($db->connect_errno) {
	    echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	}

	$dtsql = "UPDATE ajustes JOIN usuario ON ajustes.fk_usuario = usuario.id_usuario SET oscuro= ? WHERE usuario.nick LIKE ? ";
	$stmt = $db->prepare($dtsql);
	$stmt->bind_param("is", $data['value'], $usrNick);
	$stmt->execute();
	$db->query($dtsql);
	
	$_SESSION['usrTema']=$data['value'];
	$_SESSION['baja']=$data['baja'];




		//$myJson = json_encode($tema);
		//$data = json_encode(file_get_contents('php://input'),true);


 ?>