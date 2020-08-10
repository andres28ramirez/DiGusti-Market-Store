<?php 

	require("bin/connect.php");

	session_start();
						///CAMBIAR ESTA GUEVONADA A QUE SEA CON POST
	if(!isset($_GET["factura"])){
		header("location:location:/CambiosDiGusti/index.php");
	}
	if(!isset($_SESSION["usuario"]))
		header("location:/CambiosDiGusti/inicio.php");

	$factura=$_GET["factura"];

	$id=$_SESSION["id"];

	$sql_p="SELECT * FROM DATO_USUARIO WHERE ID=:id";

	$query_p=$bdd->prepare($sql_p);

	$query_p->execute(array(":id"=>$id));

	$persona=$query_p->fetch(PDO::FETCH_ASSOC);

	//$sql="SELECT * FROM ORDEN INNER JOIN PRODUCTOS ON PRODUCTOS.CODIGO=orden.CODIGO where c_factura=$factura";
	//$sql="SELECT * FROM ORDEN INNER JOIN PRODUCT_INSERT ON PRODUCT_INSERT.CODIGO=orden.CODIGO where c_factura=$factura";
	$sql="SELECT * FROM ORDEN where c_factura=$factura";

	$sql2="SELECT * FROM FACTURA WHERE C_FACTURA=:factura";

	$query_f=$bdd->prepare($sql2);

	$query_f->execute(array(":factura"=>$factura));

	$fact=$query_f->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
	<title>DiGusti Market</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/digusti.css">
  	<link rel="stylesheet" type="text/css" href="css/fontawesome-free-5.0.13/web-fonts-with-css/css/fontawesome-all.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
  	<script type="text/javascript" src="js/sweetalert.js"></script>
  	<link rel="stylesheet" type="text/css" href="fuentes.css">
	<script type="text/javascript">
		$(document).ready(function($) {
			$(".print").click(function(event) {
				$(".print").hide();
				window.print();
				self.close();
			});
		});
	</script>
</head>
<body>
	<div class="my-2 mx-auto text-center">
		<button type="button" class="btn btn-success print font-weight-bold">Descargar</button> 
		<i class="fas fa-download print" style="font-size: 2rem;"></i> 
	</div>
	<div class="factura col-xl-4 col-lg-6 col-md-7 col-sm-10 col-12 mx-auto text-center border-2">
		<img src="Alimentos/logo1.png" class="img-fluid">
		<div class="informacion">
			<font>Dirección: Porlamar / Av.Bolivar / Centro Comercial CCM</font><br>
			<font>Local #80-12 Pasillo #2.</font><br>
			<font>Teléfonos: 0295-2624012 / 04127942183</font><br>
			<font>Comprador: <?php echo $persona["Nombre"] . " " . $persona["Apellido"];?></font><br>
			<font>Cedula de Identidad: <?php echo $persona["Ci"];?></font><br>
		</div>
		<div class="mt-1">
			<table class="table">
				<tr>
					<th class="text-left">Productos</th>
					<th>Montos</th>
				</tr>
			<?php foreach($bdd->query($sql) as $producto):?>
				<tr>
					<td class="text-left"><?php echo $producto["Nombre"];?> / Cantidad (<?php echo $producto["Cantidad"];?>)</td>
					<td class="text-center"><?php echo $producto["Precio"]*$producto["Cantidad"];?><font size="2"> Bs</font></td>
				</tr>
			<?php endforeach; ?>
			</table>
		</div>
		<div class="display-6 font-weight-bold" style="margin-top: -18px;">
			TOTAL<br>
			<span class="text-danger"><?php echo $fact["Monto"];?><font size="2"> Bs</font></span>
		</div>
		<footer>Fecha: <?php echo $fact["Fecha"];?> - Código de la Factura:<?php echo $factura;?></footer>
	</div>
</body>
</html>
