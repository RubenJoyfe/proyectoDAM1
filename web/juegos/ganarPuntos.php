<?php 
require_once "../config.php";
session_start();
	if (isset($_SESSION['usrNick'])) {
		$usrNick = $_SESSION['usrNick'];

		if (isset($_SESSION['usrDinero'])) {
			$dineros=$_SESSION['usrDinero'];
		}
	}

	$data = json_decode(file_get_contents('php://input'), true);

	$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ($db->connect_errno) {
	    echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	}

	$dtsql = "CALL GanarDinero(?, ?, ?, @res)";
	$stmt = $db->prepare($dtsql);
	$stmt->bind_param("sis", $usrNick, $data['money'], $data['juego']);
	$stmt->execute();
	$db->query($dtsql);
	$res = mysqli_query($db, "SELECT @res as resultado");
	$res = mysqli_fetch_array($res);
	$rs = $res["resultado"];
	$dineros += $data['money'];
	$rsP=array(
		'cod_error' => $rs,
		'dinero' => $dineros
	);
	
	echo json_encode($rsP);

	$_SESSION['usrDinero'] = $dineros;

 ?>