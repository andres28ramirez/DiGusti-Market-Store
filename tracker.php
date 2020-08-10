<?php 

	require("bin/connect.php");

	session_start();
	if((!isset($_SESSION["usuario"]))||(!isset($_GET["factura"])))
		header("location:/CambiosDiGusti/inicio.php");
	$fac=$_GET["factura"];
	$factura=$bdd->prepare("SELECT * FROM ENVIOS WHERE C_FACTURA=$fac");
	$factura->execute();
	$fact=$factura->fetch(PDO::FETCH_ASSOC);

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
			$("#cerrar").click(function(event) {
				self.close();
			});
		});
	</script>
	<style type="text/css">
		.borde{
			border: 1px solid rgba(0,0,0,0.2);
		}
	</style>
</head>
<body>
	<div class="container-fluid my-3">
		<div class="table-responsive col-lg-6 col-md-8 col-12 mx-auto">
			<h5 class="text-dark" style="font-weight: 700">Estado de la Compra:</h5>
			<table class="table borde table-bordered">
				<thead class="thead-light">
					<th colspan="2">
						<h5 class="d-inline">Nro. de Factura:<span class="text-dark" style="font-weight: 700"> <?php echo $fac ?></span></h5>
					</th>
				</thead>
				<tbody>
					<tr>
						<td>
							<p><span class="font-weight-bold">Tipo de Compra:</span><br class="d-md-none d-sm-block"> <?php echo $fact["tipo"]; ?></p>
							<p><span class="font-weight-bold">Estatus:</span><br class="d-md-none d-sm-block"> <?php echo $fact["estado"] ?></p>
						</td>
						<td>
							<p><span class="font-weight-bold">Fecha del Movimiento:</span><br class="d-md-none d-sm-block"> <?php echo $fact["fecha"] ?>.</p>
							<p><span class="font-weight-bold">Descripción:</span><br class="d-md-none d-sm-block"> <?php echo $fact["movimiento"] ?></p>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="text-center">
				<button class="btn btn-primary bg-success borde font-weight-bold" id="cerrar">Cerrar</button>
			</div>
		</div>
	</div>
</body>
</html>