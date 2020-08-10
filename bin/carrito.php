<?php
	
	require("connect.php");
	session_start();

	if((!isset($_SESSION["tipo"]))or(isset($_SESSION["tipo"]))){


		header("location:/CambiosDiGusti/index.php");

	}

	$codigo = $_GET["codigo"];
	$cantidad = $_GET["cantidad"];
	
	//PARA VERIFICAR QUE NO SE HAYA INGRESADO ALGUN PRODUCTO DEL MISMO CODIGO ANTERIORMENTE
	$sql2 = "SELECT * FROM CARRITO WHERE ID = :id AND CODIGO = :codigo";
	$query2=$bdd->prepare($sql2);
	$query2->execute(array(":id"=>$_SESSION["id"],":codigo"=>$codigo));
	$total = $query2->rowCount();

	if($total==0){
		$sql="INSERT INTO CARRITO (ID, CODIGO, CANTIDAD) VALUES (:id, :codigo, :cantidad)";
	}
	else{
		$sql="UPDATE CARRITO SET CANTIDAD=CANTIDAD+:cantidad WHERE ID = :id AND CODIGO = :codigo";
	}
	//EJECUTAMOS LA SENTENCIA QUE HAYAMOS ELEGIDO Y YA
	$query=$bdd->prepare($sql)->execute(array(":id"=>$_SESSION["id"],":codigo"=>$codigo,":cantidad"=>$cantidad));

	echo "Producto insertado correctamente Carrito";

?>