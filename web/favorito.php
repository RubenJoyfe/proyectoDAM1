<?php 
require_once "config.php";
session_start();
	if (isset($_SESSION['usrNick'])) {
		$usrNick = $_SESSION['usrNick'];
	}

	$data = json_decode(file_get_contents('php://input'), true);

	$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ($db->connect_errno) {
	    echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	}

	$dtsql = "CALL AgregarFavorito(?, ?, ?, @res)";
	$stmt = $db->prepare($dtsql);
	$stmt->bind_param("ssi", $data['nombreJuego'], $usrNick, $data['favorito']);
	$stmt->execute();
	$db->query($dtsql);
	$res = mysqli_query($db, "SELECT @res as resultado");
	$res = mysqli_fetch_array($res);
	$rs = $res["resultado"];
	$rsCompra=array(
		'cod_error' => $rs
	);
	echo json_encode($rsCompra);

 ?>