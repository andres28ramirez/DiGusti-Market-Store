<?php 
	include("connect.php");

	session_start();
	
	$id = $_SESSION["id"];

	$sql = "DELETE FROM CARRITO WHERE id=$id";

	$bdd->query($sql);

	session_destroy();

	header("location:/CambiosDiGusti/inicio.php");


?>