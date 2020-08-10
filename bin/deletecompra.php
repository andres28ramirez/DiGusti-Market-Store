<?php 

	include("connect.php");

	session_start();

	if((!isset($_SESSION["tipo"]))or(isset($_SESSION["tipo"]))){


		header("location:/CambiosDiGusti/index.php");

	}

	$codigo=$_GET["codigo"];

	if (isset($_GET["mensaje"])) {
		$bdd->query("DELETE FROM FACTURA WHERE C_Factura='$codigo'");
	}
	else{
		$bdd->query("UPDATE FACTURA SET ver = 2 WHERE C_Factura='$codigo'");
	}
	echo "loca"

?>