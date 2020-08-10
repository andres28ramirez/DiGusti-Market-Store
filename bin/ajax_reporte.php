<?php  
	require("connect.php");

	$codigo=$_POST["codigo"];
	$tarea=$_POST["tarea"];

	if ($tarea==1) {

		//$facturas=$bdd->query("SELECT * FROM orden INNER JOIN product_insert ON orden.Codigo=product_insert.Codigo and orden.C_Factura=$codigo")->fetchAll(PDO::FETCH_OBJ);

		$facturas=$bdd->query("SELECT * FROM ORDEN where c_factura=$codigo")->fetchAll(PDO::FETCH_OBJ);

		$header="<label class='text-muted'>N° de la Factura: (<span class='text-dark font-weight-bold'>" . $codigo ."</span>)</label>
			<div class='text-center'>
				<table class='table '>
	      			<thead class='thead-dark'>
	        			<tr>
	            			<th>Nombre de Producto</th>
	            			<th>Cantidad</th>
	            			<th>Precio Unitario</th>
	            			<th>Precio Total</th>
	        			</tr>
	      			</thead>
	      			<tbody>";

	    $body="";
	    $total=0;
	    foreach ($facturas as $factura) {
	    	$body= $body . "<tr>
							<td>" . $factura->Nombre . "</td>
							<td>" . $factura->Cantidad . "</td>
							<td style='color: red; font-weight: 450'>" . $factura->Precio . "<font size='2'> Bs.</font></td>
							<td style='color: red'><strong>" . $factura->Precio*$factura->Cantidad . "<font size='2'> Bs.</font></strong></td>
						</tr>";
			$total=$total+($factura->Precio*$factura->Cantidad);
	    }

		$footer="</tbody>
				</table>
				<div class='display-6 font-weight-bold' style='margin-top: -18px;'>
					TOTAL<br>
					<span class='text-danger'>" . $total . "<font size='2'> Bs</font></span>
				</div>
			</div>";

		$respuesta= $header . $body . $footer;

		echo $respuesta;
	}else{

		$fac=$codigo;
		$factura=$bdd->prepare("SELECT * FROM ENVIOS WHERE C_FACTURA=$fac");
		$factura->execute();
		$fact=$factura->fetch(PDO::FETCH_ASSOC);

		echo "<table class='table table-bordered'>
				<thead class='thead-light'>
					<th colspan='2'>
						<h5 class='d-inline'>Nro. de Factura:<span class='text-dark codigo' style='font-weight: 700'>" . $fac ."</span></h5>
					</th>
				</thead>
				<tbody>
						<tr>
							<td>
								<p><span class='font-weight-bold'>Tipo de Compra:</span><br class='d-md-none d-lg-block'>" . $fact["tipo"] . "</p>
								<div class=''>
									<p><span class='font-weight-bold'>Estatus:</span><br class='d-md-none d-lg-block'></p>
									<select class='custom-select' style='cursor: pointer;' name='estatus'>
										<option>Procesando Compra</option>
										<option>Empacando Productos</option>
										<option>En espera de su busqueda</option>
										<option>En camino a su destino</option>
										<option>Entregado</option>
									</select>
								</div>
							</td>
							<td>
								<p><span class='font-weight-bold'>Fecha del Movimiento:</span><br class='d-md-none d-lg-block'>" . $fact["fecha"] . "</p>
								<div>
									<p><span class='font-weight-bold'>Descripción:</span><br class='d-md-none d-sm-block'></p>
									<input type='text' name='descripcion' class='form-control'>
								</div>
								
							</td>
						</tr>
				</tbody>
			</table>";
	}
	/*<select class='custom-select' style='cursor: pointer;' name='descripcion'>
										<option>Seleccionando los Productos</option>
										<option>Empacando con cuidado sus Productos</option>
										<option>Listo para recoger su pedido</option>
										<option>Buscando la mejor ruta para su destino</option>
										<option>Productos recibido</option>
									</select>*/
?>