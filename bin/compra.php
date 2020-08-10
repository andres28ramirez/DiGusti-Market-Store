<?php 

	include("connect.php");

	session_start();

	if(!isset($_POST["comprar"])){

		header("location:/CambiosDiGusti/index.php");

	}else{
		try{
			$bdd->beginTransaction();
			$id=$_SESSION["id"];
			$modalidad=$_POST["tipo_pago"];
			$cancelacion=$_POST["retiro"];
			$monto=$_POST["comprar"];
			$fecha=date("d-m-Y");

			$sql1="INSERT INTO FACTURA (ID, MODALIDAD, MONTO, CANCELACION, FECHA) VALUES (:id, :modalidad, :monto, :cancelacion, :fecha)";

			$sql2="SELECT C_FACTURA FROM FACTURA ORDER BY C_FACTURA DESC LIMIT 1";

			$query=$bdd->prepare($sql1);

			$query->execute(array(":id"=>$id,":modalidad"=>$modalidad,":monto"=>$monto,":cancelacion"=>$cancelacion,":fecha"=>$fecha));

			$query=$bdd->prepare($sql2);

			$query->execute();

			$factura=$query->fetch(PDO::FETCH_ASSOC);

			$c_factura=$factura["C_FACTURA"];

			$sql3="SELECT * FROM CARRITO INNER JOIN PRODUCTOS ON CARRITO.codigo = PRODUCTOS.codigo WHERE ID=$id";

			foreach ($bdd->query($sql3) as $carrito) {

			  $codigo=$carrito["Codigo"];
			  $cantidad=$carrito["Cantidad"];
			  $nombre=$carrito["Nombre"];
			  $precio=$carrito["Precio"];
			  $sql4="INSERT INTO ORDEN (C_FACTURA, CODIGO, CANTIDAD, NOMBRE, PRECIO) VALUES (:c_factura, :codigo, :cantidad, :nombre, :precio)";
			  $query=$bdd->prepare($sql4);
			  $query->execute(array(":c_factura"=>$c_factura,":codigo"=>$codigo,":cantidad"=>$cantidad,":nombre"=>$nombre,":precio"=>$precio));

			}

			$sql5="DELETE FROM CARRITO WHERE ID='$id'";

			$bdd->query($sql5);
			$bdd->commit();
			header("location:/CambiosDiGusti/compras.php?mensaje=1");
		}catch(\PDOException $e){
			$bdd->rollBack();
			header("location:/CambiosDiGusti/car.php?mensaje=1");
		}
	}

?>