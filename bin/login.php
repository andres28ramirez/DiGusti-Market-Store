<?php 

	try{

		$bdd=new PDO("mysql:host=localhost; dbname=digusti", "root", ""); //conexion a la base de datos

		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$sql="SELECT * FROM USUARIO WHERE CORREO= :correo AND PASS = :password";

		$consulta=$bdd->prepare($sql);

		$login=$_POST["usuario"];

		$pass=$_POST["password"];

		$consulta->bindValue(":correo",$login);

		$consulta->bindValue(":password",$pass);

		$consulta->execute();

		$comprobar=$consulta->rowCount();

		if($comprobar!=0){

			$tabla=$consulta->fetch(PDO::FETCH_ASSOC);

			$id=$tabla["ID"];

			$tipo=$tabla["Tipo_Usuario"];

			$sql2="SELECT * FROM DATO_USUARIO WHERE ID= :id";

			$consulta2=$bdd->prepare($sql2);

			$consulta2->execute(array(":id"=>$id));

			$tabla2=$consulta2->fetch(PDO::FETCH_ASSOC);

			$nombre=$tabla2["Nombre"];

			session_start();

			$_SESSION["usuario"]=$nombre;

			$_SESSION["id"]=$id;

			$_SESSION["tipo"]=$tipo;



			header("location:/CambiosDiGusti/index.php");
			


		}else{


			header("location:/CambiosDiGusti/index.php");

		}

	
		}catch(Exception $e){

			die('Error: ' . $e->GetMessage());

		}finally{

			$bdd=NULL;
		}

?>