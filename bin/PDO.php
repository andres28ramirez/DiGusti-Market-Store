
<?php  

/////////////////////////////////////////////////////// BUSQUEDA DE PRODUCTOS //////////////////////////////////////////////////////////

	function busqueda($buscar,$num){
		$filas = 12; /*AQUI PUEDO AJUSTAR A CUANTAS PAGINAS QUIERO QUE TENGA LA PAGINACION*/
		if(isset($_GET["pagina"])){
			$pagina = $_GET["pagina"];
		}
		else{
			$pagina = 1;
		}

		$empezar = ($pagina-1)*$filas;/*PARA LA PAGINACION*/
		try{

			$bdd=new PDO("mysql:host=localhost; dbname=digusti", "root", ""); //conexion a la base de datos

			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			if($num==1){/*BUSCADOR INGRESADO POR EL USUARIO*/
				$sql="SELECT * FROM PRODUCTOS WHERE NOMBRE LIKE ? ORDER BY NOMBRE LIMIT $empezar,$filas"; /*CONSULTA PAGINADA*/

				$sql2="SELECT * FROM PRODUCTOS WHERE NOMBRE LIKE ? ORDER BY NOMBRE"; /*CONSULTA NORMAL*/

				$bdd->exec("SET CHARACTER SET utf8");

				$query=$bdd->prepare($sql);

				$query->execute(array("%$buscar%"));

				$total=$bdd->prepare($sql2); $total->execute(array("%$buscar%")); /*PARA RECOJER NUMERO DE PRODUCTOS*/
			}
			
			else{/*CATEGORIA NORMAL DE LA BARRA DE BUSQUEDA*/
				//$sql="SELECT * FROM PRODUCTOS WHERE CATEGORIA= :pro ORDER BY NOMBRE LIMIT $empezar,$filas"; /*CONSULTA PAGINADA*/

				//$sql2="SELECT * FROM PRODUCTOS WHERE CATEGORIA= :pro ORDER BY NOMBRE"; /*CONSULTA NORMAL*/
				/*
				$bdd->exec("SET CHARACTER SET utf8");

				$query=$bdd->prepare($sql);

				$query->execute(array(":pro"=>$buscar));

				$total=$bdd->prepare($sql2); $total->execute(array(":pro"=>$buscar));*/ /*PARA RECOJER NUMERO DE PRODUCTOS*/

				$sql="SELECT * FROM $buscar ORDER BY NOMBRE LIMIT $empezar,$filas"; /*CONSULTA PAGINADA*/

				$sql2="SELECT * FROM $buscar ORDER BY NOMBRE"; /*CONSULTA NORMAL*/

				$bdd->exec("SET CHARACTER SET utf8");

				$query=$bdd->query($sql);

				$total=$bdd->query($sql2); /*PARA RECOJER NUMERO DE PRODUCTOS*/
			}
		
			$contador=0;
			$cantidad = $total->rowCount();
			/*SECCION PARA ACOMODAR LA INFORMACION DE LA PAGINACIONA*/
			$empezar++; /*para imprimir el numerito de la paginacion producto inicial*/
			$empezarf = $empezar+$filas-1; 
			if(!($empezarf<$cantidad)){
				$empezarf=$cantidad;
			}
			$tpag = ceil($cantidad/$filas); /*CANTIDAD DE PAGINAS TOTALES QUE DEBERIA TENER LA PAGINACION*/

			/*IMPRESION DE LA CANTIDAD DE PRODUCTOS*/ 
			/* ECHO NORMAL POR SI LA PAGINACION SALE CHIMBA
				echo "
			    	  <div class='col-9'>
			    	    <h6 class='text-muted mb-lg-3 mb-2'>Cantidad de Productos: <strong class='text-dark'>(".$cantidad.")</strong></h6>
			    	  </div>";*/
			
			/*IMPRESION POR SI HAY PAGINACION*/
				echo "	
					<div class='row justify-content-between w-80 pag'>
				      <div class='col-xl-4 col-lg-4 col-md-5 col-sm-12'>
				        <h6 class='text-muted mb-lg-3 mb-2'>Cantidad de Productos: <strong class='text-dark'>(".$cantidad.")</strong></h6>
				      </div>
				      <div class='col-xl-4 col-lg-3 col-md-4 col-sm-12 text-right'>
				      	<h6 class='text-muted mb-lg-3 mb-2'>".$empezar."-".$empezarf." / Página";
				for($i=1; $i<=$tpag; $i++){
				  if($pagina==$i){
				  	echo "<a class='text-dark' href='productos.php?producto=".$buscar."&pagina=".$i."'><strong> ".$i."</strong></a>";
				  }
				  else{
				  	echo "<a class='text-muted' href='productos.php?producto=".$buscar."&pagina=".$i."'> ".$i."</a>";
					}
				}
				echo "  </h6>
				      </div>
				    </div>";
				    
			/*IMPRESION DE LA TABLA DEVUELTA POR LA CONSULTA DE LOS PRODUCTOS*/

			while($tabla=$query->fetch(PDO::FETCH_ASSOC)){
				global $contador;
				$contador++;
					echo "  <div class='card col-xl-3 col-lg-4 col-md-5 col-sm-6 col-10 mb-2 mx-md-2 mx-0'>
							  <img class='card-img-top' src='".$tabla["Imagen"]."' alt='Card image cap' height='180'>
							  <div class='card-body'>
							    <h5 class='card-title d-inline'>".$tabla["Nombre"]."</h5>";

					echo"	    <p class='card-text text-center text-danger font-weight-bold' style='font-size: 2.2rem; margin-top: -8px'>".$tabla["Precio"]."
							      <span class='font-weight-normal' style='font-size: 1.5rem'>Bs<span>";

					if($tabla["Categoria"]!="Bodega"){
							    echo "<font size='2' class='text-dark'> x Kg</font>";}
					else{
								echo "<font size='2' class='text-dark'> x Und</font>";}
							      
					echo"	    </p>
							    <div class='row' style='margin-top: -12px'>
							      <input id='".$tabla["Codigo"]."' align='left' class='col-2 form-control form-control-sm cantidad' type='number' name='cantidad' value='1' min='1'><font size='2' class='m-auto'>.und</font>
							      <a id='".$tabla["Codigo"]."' class='text-white btn bg-success btn-primary col-8 font-weight-bold borde car'>Agregar al Carrito</a>
							    </div>
							  </div>
							</div>";
			}

			if($contador==0){
				echo "
				  <script>$('.pag').hide();</script>	
			      <div class='col-md-8 col-12'>
			        <h5 class='text-muted mb-lg-4 mb-2'>Resultado para: <strong class='text-dark'>".$buscar."</strong></h5>
			        <p class='display-6 text-dark mb-3'><span class='display-5 text-primary'>LO SENTIMOS,</span><br>NO SE HAN ENCONTRADO RESULTADOS COINCIDENTES</p>
			        <h5 class='text-muted'>¡Prueba a realizar otra búsqueda!</h5>
			      </div>";
			}

		$query->closeCursor();

		}catch(Exception $e){

			die('Error: ' . $e->GetMessage());

		}finally{

			$bdd=NULL;
		}
	}

//////////////////////////////////////////////////// PAGINAS DE PRODUCTO ////////////////////////////////////////////////////
?>