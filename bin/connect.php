<?php

	try{

		$bdd=new PDO("mysql:host=localhost; dbname=digusti", "root", ""); //conexion a la base de datos

		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$bdd->exec("SET CHARACTER SET utf8");

	}catch(Exeption $e){

		die('Error:' . $e->getMessage());

		echo "Linea del error: " . $e->getLine();

	}

?>