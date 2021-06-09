<?php 

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

	$data = json_decode(file_get_contents('php://input'), true);

	$db = new mysqli("localhost:3306", "root", "", "h15af00");
	if ($db->connect_errno) {
	    echo "Falló la conexión con MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
	}

	$dtsql = "CALL DesbloquearDesbloqueable(?, ?, ?, @res)";
	$stmt = $db->prepare($dtsql);
	$stmt->bind_param("isi", $data['id_des'], $usrNick, $data['dinero']);
	$stmt->execute();
	$db->query($dtsql);
	$res = mysqli_query($db, "SELECT @res as resultado");
	$res = mysqli_fetch_array($res);
	$rs = $res["resultado"];
	$rsCompra=array(
		'cod_error' => $rs
	);
	echo json_encode($rsCompra);


	$_SESSION['usrDinero'] = $data['dinero'];
 ?>