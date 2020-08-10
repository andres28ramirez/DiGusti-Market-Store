<?php 

	require("connect.php");

	session_start();

	if((!isset($_SESSION["tipo"]))or($_SESSION["tipo"]!=1)){//or(!isset($_GET["cod"]))){
		header("location:/CambiosDiGusti/index.php");
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title>DiGusti Market Store</title>
	<meta charset="utf-8">
	<meta name="Supermercado" content="Supermercado DiGusti Market Store">
	<link rel="stylesheet" type="text/css" href="/DiGustiMarketStore/Estructura.css">
	<link rel="stylesheet" type="text/css" href="/DiGustiMarketStore/home.css">
	<link rel="stylesheet" type="text/css" href="/DiGustiMarketStore/fuentes.css">
	<link rel="stylesheet" type="text/css" href="/DiGustiMarketStore/compras.css">
	<script type="text/javascript" src="/DiGustiMarketStore/jquery-3.3.1.min.js"></script>
	<style type="text/css">
		a{
			text-decoration: none;
			color: #fff;
		}
	</style>
	<script type="text/javascript">
		$(document).ready(function() {

			$("#login").hide();	//OCULTAR EL BLOQUE DEL LOGIN
			$(".ver").hide();	//OCULTAR EL VER PRODUCTOS

			$("#login").removeClass('esconder');
			$("#bienvenute").removeClass('esconder');

			//EVENTO PARA CLICKIAR EL LOGO Y CARGAR EL HOME
			$("#logo").click(function(event) {
				location.href = "index.php";
			});
			//FIN EVENTO DEL CLICK DEL LOGO
			
			$("#BarraI p").hover(function() {	//ANIMACION DE LOS BOTONES SUPERIORES
				$(this).animate({borderWidht: "5px", fontSize: "23px"}, 500);
			}, function() {
				$(this).animate({borderWidht: "2px", fontSize: "15px"}, 500);
			});									//FIN BOTONES SUPERIOR

			$("#sesion").click(function(event) {	//ANIMACION DE ABRIR EL BOTON DE LOGIN
				$("#login").fadeIn(500);
			});

			$("#cerrar").click(function(event) {	//ANIMACION DE CERRAR EL LOGIN
				$("#login").fadeOut(500);
			});

			//AUTO SEÑALIZAR EL BOTON INVITADO
			$("#invitado").css("background","rgba(48,48,47,1)"); 		//('text-shadow','black 2px 2px 10px');

			//ANIMACION DE LOS BOTONES DE LA BARRA DE NAVEGACION
			$(".opcion").hover(function() {
				$(this).animate({fontSize: "25px"}, 350);
			}, function() {
				$(this).animate({fontSize: "20px"}, 350);
			});

			//SECCION DEL MENU DEL PERFIL DEL USUARIO
				$(".logusuario").hide();
				$("#log").click(function(){
					$(".logusuario").slideToggle(500);
				});

				$(".opcusuario").hover(function() {
					$(this).css('text-decoration', 'underline');
				}, function() {
					$(this).css('text-decoration', 'none');
				});
			//ESCRIBIR EL CLICK DE CADA OPCION DE USUARIO//

			//ESCRIBIR EL CLICK DE CERRAR SESION QUE A LA VEZ ACOMODARA LOS BOTONES DE INICIAR SESIN Y TODO ESO
			$("#csesion").click(function(){
				$("#bienvenute").fadeOut(200);
				$(".logusuario").hide(); 
				$("#BarraI").fadeIn(200);
			});
			//FIN SECCION DEL MENU DEL PERFIL

			$("#manual").click(function(event) {	//para mostrar el manual de usuario en otra pestaña
				open("/DiGustiMarketStore/Guia.pdf", "Manual de Usuario", "height=100%","width=100%");
			});

			//--------------------------------------SECCION EXCLUSIVA DEL CONTENIDO DEL UPDATE-----------------------------------------------//

		});
	</script>
</head>
			<!-- FALTA EL LINKIADO DE LAS DIFERENTES PAGINAS Y YA-->
<body>

	<!-- ///////////////////////////////////////////////// BOTONES Y LOGIN ///////////////////////////////////////////////////////////// -->
<?php

		if(isset($_SESSION["usuario"])){

			//<!-- BOTONES PARTE SUPERIOR -->		

			echo ("<div id='bienvenute' class='esconder'> 
			<div id='usuario'>
				<p>Bienvenido " . $_SESSION["usuario"] . ", a DiGusti Market Store</p>
				<img id='log' src='/DiGustiMarketStore/Alimentos/usu.png'>
			</div>"); // ***********************QUITAR CLASES ESCONDER*****************

		}else{

			echo ("<div id='BarraI'>                         
					<p id='sesion'>Iniciar Sesion</p>
					<p id='registro'><a href='/DiGustiMarketStore/registro.php'>Registrarse</a></p>
					<p id='invitado'>Invitado</p>
				</div>");

		}

	?>

<?php


	if(isset($_SESSION["usuario"])){

	echo ("<div class='logusuario'>
			<div class='o1'>
				<p class='opcusuario'><a href='/DiGustiMarketStore/configuracion.php'>Configuración</a></p>
			</div>
			<div class='o2'>
				<p class='opcusuario'><a href='/DiGustiMarketStore/carrito.php'>Carrito</a></p>
			</div>
			<div class='o3'>
				<p class='opcusuario'><a href='/DiGustiMarketStore/compras.php'>Ultimas Compras</a></p>
			</div>");


				if($_SESSION["tipo"]==1){

					echo ("<div>
							<p class='opcusuario'><a href='/DiGustiMarketStore/admin.php'>Admin</a></p>
						</div>");

				}

echo ("
			<div class='o4'>
				<p class='opcusuario' id='csesion'><a href='csion.php'>Cerrar Sesion</a></p>
			</div>
	</div> 
	</div>");

	}

?>

	<div id="login" class="esconder">	<!-- CONTENIDO DEL LOGIN -->
		<form method="post" action="<?php $_SERVER['PHP_SELF']?>">
			<input class="log" type="email" name="usuario" placeholder="Ingresar Correo..." required>
			<input class="log" type="password" name="password" placeholder="Contraseña..." required><br>
			<input class="log boton" type="submit" name="botonlog" value="Enviar">
			<input id="cerrar" class="log boton" type="button" name="cerrar" value="Cerrar">
		</form>
	</div>

	<!-- ///////////////////////////////////////////////// FIN BOTONES Y LOGIN ///////////////////////////////////////////////////////////// -->

	<!-- ///////////////////////////////////////////////// SECTOR SUPERIOR ///////////////////////////////////////////////////////////// -->
	<div id="imagenP">
		<form class="formulario" action="/DiGustiMarketStore/buscador.php"> <!-- BARRA DE BUSQUEDA-->
			<input class="texto busqueda" type="text" name="busqueda" placeholder="Barra de Búsqueda..." method="get">
			<input class="boton busqueda" type="button" name="submit" value="Buscar">
		</form>

		<div id="navegacion">	<!-- BARRA DE NAVEGACION-->
			<img id="logo" src="/DiGustiMarketStore/Alimentos/logo1.png">
			<!-- AQUI ESTABA LA BARRA DE NAVEGACION ANTES -->
		</div>
	</div>

	<nav id="barra">
		<ul>
			<li class="opcion"><a href="/DiGustiMarketStore/index.php">Home</a></li>
			<li class="opcion"><a href="/DiGustiMarketStore/producto.php?producto=Carne">Carnes</a></li>
			<li class="opcion"><a href="/DiGustiMarketStore/producto.php?producto=Panaderia">Panadería</a></li>
			<li class="opcion"><a href="/DiGustiMarketStore/producto.php?producto=Charcuteria">Charcutería</a></li>
			<li class="opcion"><a href="/DiGustiMarketStore/producto.php?producto=Verdura">Verduras</a></li>
			<li class="opcion"><a href="/DiGustiMarketStore/producto.php?producto=Bodega">Bodega</a></li>
			<li class="opcion"><a href="/DiGustiMarketStore/producto.php?producto=Fruta">Frutas</a></li>
			<li class="opcion"><a href="/DiGustiMarketStore/ayuda.php">Ayuda</a></li>
		</ul>
	</nav>	
	<!-- ///////////////////////////////////////////////// FIN SECTOR SUPERIOR ///////////////////////////////////////////////////////////// -->

	<!-- ///////////////////////////////////////////////// CONTENIDO ///////////////////////////////////////////////////////////// -->
	<?php 


		if(!isset($_POST["b_up"])){

			$cod=$_GET["cod"];
			$nom=$_GET["nom"];
			$cat=$_GET["cat"];
			$pre=$_GET["pre"];

		}elseif($_FILES['fot']['name']==null){
			$cod=$_POST["cod"];
			$nom=$_POST["nom"];
			$cat=$_POST["cat"];
			$pre=$_POST["pre"];

			$sql="UPDATE PRODUCTOS SET NOMBRE=:n_nom, CATEGORIA=:n_cat, PRECIO=:n_pre WHERE Codigo=:id";

			$up_bdd=$bdd->prepare($sql);

			$up_bdd->execute(array(":n_nom"=>$nom,":n_cat"=>$cat,":n_pre"=>$pre,":id"=>$cod));

			$_SESSION["actualizar"]=1;

			echo "<script type='text/javascript'>location.href='/DiGustiMarketStore/admin.php'</script>";
			
		}else{

			$cod=$_POST["cod"];
			$nom=$_POST["nom"];
			$cat=$_POST["cat"];
			$pre=$_POST["pre"];
			$name_imagen=$_FILES['fot']['name'];
			$size_imagen=$_FILES["fot"]['size'];
			$type_imagen=$_FILES['fot']['type'];

			if($type_imagen=="image/jpeg" || $type_imagen=="image/jpg" || $type_imagen=="image/png" || $type_imagen=="image/gif"){

					if($size_imagen<=4294967298){

						$sql="SELECT IMAGEN FROM PRODUCTOS WHERE IMAGEN=:img";

						$img="/DiGustiMarketStore/Server_img/" . $name_imagen;

						$query = $bdd->prepare($sql);

						$query->execute(array(":img"=>$img));

						if($query->rowCount()<1){

							$sqls="SELECT * FROM PRODUCTOS WHERE CODIGO=:cod";

							$query=$bdd->prepare($sqls);

							$query->execute(array(":cod"=>$cod));

							$borrar=$query->fetch(PDO::FETCH_ASSOC);

							$b_img=$_SERVER["DOCUMENT_ROOT"] . $borrar["Imagen"];

							unlink($b_img);

							$destino=$_SERVER["DOCUMENT_ROOT"] . "/DiGustiMarketStore/Server_img/";

							move_uploaded_file($_FILES["fot"]["tmp_name"],$destino . $name_imagen);

							$img="/DiGustiMarketStore/Server_img/" . $name_imagen;

							$sqlu="UPDATE PRODUCTOS SET NOMBRE=:n_nom, CATEGORIA=:n_cat, PRECIO=:n_pre, IMAGEN=:img WHERE Codigo=:id";;

							$up_bdd=$bdd->prepare($sqlu);

							$up_bdd->execute(array(":n_nom"=>$nom,":n_cat"=>$cat,":n_pre"=>$pre, ":img"=>$img, ":id"=>$cod));

							$_SESSION["actualizar"]=1;

							echo "<script type='text/javascript'>location.href='/CambiosDiGusti/admin.php'</script>";

						}else{

							echo "<script type='text/javascript'>alert('Ya existe la Imagen en el Servidor, porfavor Ingrese otra Imagen')</script>";
						}
					}else{

						echo "<script type='text/javascript'>alert('Archivo muy grande para la base de datos')</script>";

					}

				}else{


					echo "<script type='text/javascript'>alert('El tipo de archivo no es el correcto')</script>";	

				}
		}

	?>



	<form method="post" action="update.php" enctype="multipart/form-data">
		<div id="contenido">
			<div class="factura">
			<p><strong>Actualizar</strong></p>
			<table>
				<tr>
					<th>Nombre</th>
					<th>Categoria</th>
					<th>Precio</th>
					<th>Imagen</th>
				</tr>
				<tr>
					<input type="hidden" name="cod" value="<?php echo $cod;?>">
					<td><input type="tex" name="nom" value="<?php echo $nom;?>" required></td>
					<td>
			 			<!--<datalist id=categoria>
			 				<option value="Panaderia"></option>
			 				<option value="Carne"></option>
			 				<option value="Charcuteria"></option>
			 				<option value="Fruta"></option>
			 				<option value="Bodega"></option>
			 				<option value="Verdura"></option>
			 			</datalist>
			 			<input list="categoria" type="tex" name="cat" value="<?php echo $cat;?>" required>-->
			 			<select id="categoria" name="cat">
			 				<option value="Panaderia">Panaderia</option>
			 				<option value="Carne">Carne</option>
			 				<option value="Charcuteria">Charcuteria</option>
			 				<option value="Fruta">Fruta</option>
			 				<option value="Bodega">Bodega</option>
			 				<option value="Verdura">Verdura</option>
			 			</select>
			 		</td>
					<td><input type="tex" name="pre" value="<?php echo $pre;?>" required></td>
					<td><input type="file" name="fot"></td>
				</tr>
				<tr>
					<td colspan="4"><input type="submit" name="b_up" value="Actualizar" required></td>
				</tr>
			</table>
			<p>Puede dejar la imagen sin actualizar si lo desea</p>
		</div>
	</form>
	</div>
	
	<!-- ///////////////////////////////////////////////// FIN DE CONTENIDO ///////////////////////////////////////////////////////////// -->
	
	<!-- ///////////////////////////////////////////////// SECTOR INFERIOR ///////////////////////////////////////////////////////////// -->
	<div class="slogan">Un Placer Todos los Días, Ven, Compra y Disfruta!!!</div>
	<div id="informacion">
		<div class="juramental">	<!-- 3 BLOQUES DE INFORMACION -->
			<section class="bloque"> 
				<div class="jur">
					<span class="cambio">Sobre Nosotros</span><br><br>
					Nos encontramos ubicados en la Isla de Margarita, Ciudad de Porlamar, Av.Bolivar, diagonal al centro comercial La Vela y Venetur, local número 80-12. <br>
					Número Contacto: 0295-2624415 <br>
					Teléfono Movil: 0412-7942183 <br>
					Correo Electrónico: digusti@gmail.com <br>
				</div>
			</section>
			<section class="bloque">
				<div class="jur">
					<span class="cambio">Redes Sociales</span><br><br>
					<section class="f1">Dale like en Facebook</section>
					<section class="f2">Miranos por Instagram</section>
					<section class="f3">Siguenos en Twitter</section>
					<section class="f4">Subscribete en Youtube</section>
				</div>
			</section>
			<section class="bloque">
				<div class="jur">
					<br>DiGusti Market Store siempre a la Vanguardia con los nuevos productos, al mejor precio y con la excelencia de nuestros servicios <br> 
					<!-- IMAGEN DEL LOGO PERO MUCHO MAS PEQUEÑA -->
					<img src="/DiGustiMarketStore/Alimentos/logo2.png" width="250" height="80">
					<!-- SECCION MANUAL DE USUARIO 
					<div class="manual">
						Aprende a Manipular la Página con el 
						<label id="manual" style="text-decoration: underline; color: red; cursor: pointer;">Manual de Usuario</label>
					</div> -->
				</div>
			</section>
		</div>
		<footer>	<!-- AUTORES -->
				Derechos Reservados: Ing. Ramirez Andres e Ing. Gil Miguel
		</footer>
	</div>
	<!-- ///////////////////////////////////////////////// FIN SECTOR INFERIOR ///////////////////////////////////////////////////////////// -->
</body>
<?php
	echo "<script>$('#categoria > option[value=".$cat."]').attr('selected','true');</script>";
?>
</html>
