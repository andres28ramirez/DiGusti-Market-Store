<?php 

	session_start();

	if((!isset($_SESSION["tipo"]))or($_SESSION["tipo"]>1)){


		header("location:/CambiosDiGusti/index.php");

	}

	include("connect.php");

	$codigo=$_GET["Codigo"];

	$query = $bdd->query("SELECT * FROM PRODUCTOS WHERE CODIGO=$codigo");
	$borrar=$query->fetch(PDO::FETCH_ASSOC);
	$destino = $_SERVER["DOCUMENT_ROOT"].$borrar["Imagen"];
	unlink($destino); //PARA BORRAR LA IMAGEN EN EL SERVIDOR

	$bdd->query("DELETE FROM PRODUCTOS WHERE CODIGO=$codigo");

	header("location:/CambiosDiGusti/admin.php?imagen=1");

?>