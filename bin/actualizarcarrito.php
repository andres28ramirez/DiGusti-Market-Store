<?php 

	include("connect.php");

	session_start();

	if((!isset($_SESSION["tipo"]))or(isset($_SESSION["tipo"]))){


		header("location:/CambiosDiGusti/index.php");

	}

	$codigo = $_GET["codigo"];

	$cantidad = $_GET["cantidad"];

	$id = $_SESSION['id'];

	$bdd->query("UPDATE CARRITO SET CANTIDAD='$cantidad' WHERE ID='$id' AND CODIGO = '$codigo'");

	echo "loca"

?>