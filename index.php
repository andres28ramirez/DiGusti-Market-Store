<?php
	require("bin/connect.php");
	session_start();
	if(!isset($_SESSION["usuario"]))
		header("location:/CambiosDiGusti/inicio.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Digusti Market</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/digusti.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
  <link rel="stylesheet" type="text/css" href="fuentes.css">
	<script>
    $(document).ready(function() {
      $("#manual1").click(function(event) { //para mostrar el manual de usuario en otra pestaña
        open("Guia.pdf", "Manual de Usuario", "height=100%","width=100%");
      });

      $(".som").each(function() {

        $(this).hover(function() {
          $(this).css('opacity', '0.8');
        }, function() {
          $(this).css('opacity', '1');
        });

        $(this).click(function(event) {
          var pro = $(this).attr('id');
          location.href = "productos.php?producto="+pro+"&forma=1";
        });
      });
    });
  </script>
	<style type="text/css">
		.banner{
			background: url('Alimentos/Pasillo.jpg');
			background-size: cover;
			background-position: center;
		}

    .som{
      cursor: pointer;
    }
	</style>
</head>
<body>
	<div class="container-fluid banner">
		<div class="container">
			<div class="col-lg-6">
				<a href="index.php"><img src="Alimentos/logo1.png" class="img-fluid my-lg-4 my-md-3 my-sm-2 my-1"></a>
			</div>
		</div>			
	</div>

	<div class="sticky-top">
		<nav class="navbar navbar-dark  navbar-expand-lg bg-rojo">
      <span class="text-white ml-1 d-sm-block d-lg-none">Bienvenid@ <?php echo $_SESSION["usuario"];?>, a Digusti Market</span>
			<button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
    			<span class="navbar-toggler-icon"></span>
  			</button>
  			<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
  				<div class="dropdown">
  					<a href="#" class="dropdown-toggle text-secondary" id="usuario" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="Alimentos/usu.png" style="width: 30px; height: 25;" class="mr-1 p-1 border-2 border-secondary rounded"></a>
  					<div class="dropdown-menu" aria-labelledby="usuario">
  						<a class="dropdown-item" href="car.php"><img src="Alimentos/carrito.png" style=" width: 25px; height: 25;" class="mr-1">Carrito</a>
          				<a class="dropdown-item" href="compras.php"><img src="Alimentos/ucom.png" style=" width: 25px; height: 25;" class="mr-1">Ultimas Compras</a>
          				<a class="dropdown-item" href="configuracion.php"><img src="Alimentos/engranaje.png" style=" width: 25px; height: 25;" class="mr-1">Configuración</a>
                  <?php
                      if ($_SESSION["tipo"]!=2) {
                        echo "<a class='dropdown-item' href='admin.php'><img src='Alimentos/admin.png' class='mr-1' style='width: 25px; height: 25;'>Admin</a>";
                      }
                  ?>
          				<a class="dropdown-item" href="bin/csion.php"><img src="Alimentos/off.png" class="mr-1" style=" width: 25px; height: 25;">Cerrar Sesión</a>
  					</div>
  				</div>
  				<span class="text-white ml-1 d-none d-lg-block">Bienvenid@ <?php echo $_SESSION["usuario"];?>, a Digusti Market Store</span>
    			<div class="navbar-nav ml-auto">
      				<a class="nav-item nav-link active" href="#">Inicio</a>
      				<li class="nav-item dropdown">
      					<a class="nav-link dropdown-toggle" href="#" id="categoria" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categorías</a>
      					<div class="dropdown-menu" aria-labelledby="categoria">
      						<a class="dropdown-item" href="productos.php?producto=Carne&forma=1">Carnes</a>
          					<a class="dropdown-item" href="productos.php?producto=Panaderia&forma=1">Panadería</a>
          					<a class="dropdown-item" href="productos.php?producto=Charcuteria&forma=1">Charcutería</a>
          					<a class="dropdown-item" href="productos.php?producto=Verdura&forma=1">Verduras</a>
          					<a class="dropdown-item" href="productos.php?producto=Bodega&forma=1">Bodegon</a>
          					<a class="dropdown-item" href="productos.php?producto=Fruta&forma=1">Frutas</a>
        				</div>
      				</li>
      				<a class="nav-item nav-link" href="#Nosotros">Nosotros</a>
              <a class="nav-item nav-link" href="#contacto">Contacto</a>
      				<a class="nav-item nav-link" id="manual1" style="cursor: pointer;">Ayuda</a>
      				<form class="form-inline ml-lg-1 ml-0" action="productos.php" method="get">
      					<input type="text" name="producto" placeholder="Buscar..." class="form-control mr-1 mb-xl-0 mb-lg-2 mb-md-0 mb-2">
      					<input type="submit" class="btn btn-secondary ml-xl-1 ml-lg-0 ml-md-1" value="Buscar">
      				</form>
    			</div>
    		</div>
		</nav>	
	</div>

	<!-- //////////////////////////////////// INICIO DE CONTENIDO ///////////////////////////// -->

	<div class="container-fluid my-2"> <!--ESTE DIV ES EL CONTENEDOR-->

    <div class="row justify-content-center"> <!--CONTENEDOR DE LA PARTE DEL CARROUSEL Y LO DE AL LADO col-lg-5-->
		<div id="carouselExampleIndicators" class="carousel slide col-lg-7 col-md-8 col-sm-10 mb-2" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img class="d-block w-100"  style="border-radius: 10px; height: 60vh;" src="Imagenes/domicilio.jpg" alt="First slide">
          <div class="carousel-caption d-md-block d-sm-block py-3" style="background: rgba(0,0,0,0.5);">
            <h6>No tendras que salir de tu hogar te enviamos los productos a domicilio.</h6>
          </div>
        </div>
        <div class="carousel-item">
          <img class="d-block w-100"  style="border-radius: 10px;height: 60vh;" src="Imagenes/carrito.jpg" alt="Second slide">
            <div class="carousel-caption d-md-block d-sm-block py-3" style="background: rgba(0,0,0,0.5);">
    		      <h6>Agrega tus productos al carrito de compras con solo un click.</h6>
  	        </div>
        </div>
        <div class="carousel-item">
          <img class="d-block w-100"  style="border-radius: 10px; height: 60vh;" src="Imagenes/compra_c.png" alt="Third slide">
          <div class="carousel-caption d-md-block d-sm-block py-3" style="background: rgba(0,0,0,0.5);">
    		    <h6>Ahora puedes cancelar tus productos con tarjeta de crédito.</h6>
  	      </div>
        </div>
        <div class="carousel-item">
          <img class="d-block w-100"  style="border-radius: 10px; height: 60vh;" src="Imagenes/receta4_c.png" alt="Fourth slide">
          <div class="carousel-caption d-md-block d-sm-block py-3" style="background: rgba(0,0,0,0.5);">
    		    <h6>Prueba nuestras variadas recetas.</h6>
  	      </div>
        </div>
        <div class="carousel-item">
          <img class="d-block w-100"  style="border-radius: 10px; height: 60vh;" src="Imagenes/productos.jpg" alt="Fifth slide">
          <div class="carousel-caption d-md-block d-sm-block py-3" style="background: rgba(0,0,0,0.5);">
    		    <h6>Aprovecha los productos importados traidos para ti.</h6>
  	      </div>
        </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>

      <!--<div style="height: 50vh; background-image: url(Imagenes/Info.jpg); background-size: contain; background-repeat: no-repeat; background-position: center;" class="col-lg-5 col-md-8 col-sm-10 ">
       LA IMAGEN SERA EDITADA CON LA INFO QUE DEBERIA LLEVAR 
      </div>-->
    </div><!--FIN CONTENEDOR DE LA PARTE DEL CARROUSEL Y LO DE AL LADO -->

    <div class="px-lg-2 px-0 w-80 mx-auto mt-5"> <!-- CONTENEDOR CON LOS CIRCULOS TIPOS CARROUSEL DE LOS PRODUCTOS -->
    <div class="row justify-content-center text-center mb-lg-2">  
      <div class="col-lg-4 col-md-4 col-6 m-auto"> <!-- EL PRIMERO -->
        <img id="Bodega" src="Alimentos/cerveza.jpg" width="200" class="img-fluid som" style="border-radius: 50%; height: 180px">
        <p style="font-family: lobster;">Cervezas de marcas Mundiales al mejor precio</p>
      </div>
      <div class="col-lg-4 col-md-4 col-6 m-auto"> <!-- EL SEGUNDO -->
        <img id="Fruta" src="Alimentos/frut.jpg" width="200" class="img-fluid som" style="border-radius: 50%; height: 180px">
        <p style="font-family: lobster;">Las frutas mas ricas y frescas</p>
      </div>
      <div class="col-lg-4 col-md-4 col-6 m-auto"> <!-- EL TERCERO -->
        <img id="Panaderia" src="Alimentos/pasteleria.jpg" width="200" class="img-fluid som" style="border-radius: 50%; height: 180px">
        <p style="font-family: lobster;">Productos panaderos recien sacados del Horno</p>
      </div>
    </div>
    <div class="row justify-content-center text-center">
      <div class="col-lg-4 col-md-4 col-6 m-auto"> <!-- EL CUARTO -->
        <img id="Carne" src="Alimentos/cortes.jpg" width="200" class="img-fluid som" style="border-radius: 50%; height: 180px">
        <p style="font-family: lobster;">Los Mejores Cortes de Carne solo aquí</p>
      </div>
      <div class="col-lg-4 col-md-4 col-6 m-auto"> <!-- EL QUINTO -->
        <img id="Verdura" src="Alimentos/verduras.png" width="200" class="img-fluid som" style="border-radius: 50%; height: 180px">
        <p style="font-family: lobster;">Combina tus comidas con nuestras Verduras</p>
      </div>
      <div class="col-lg-4 col-md-4 col-6 m-auto"> <!-- EL SEXTO -->
        <img id="Charcuteria" src="Alimentos/char.jpg" width="200" class="img-fluid som" style="border-radius: 50%; height: 180px">
        <p style="font-family: lobster;">Charcutería al puro estilo Italiano</p>
      </div>
    </div>
    </div> <!-- FIN CONTENEDOR DE LOS CIRCULOS -->

    <div class="row text-center text-white mb-2" id="Nosotros"><!-- FRANJA CON EL SLOGAN -->
      <div class="w-100" style="background-image: url('Alimentos/pasto2.png'); background-size: contain; height: 12vh"></div>
      <h5 class="w-100 bg-rojo py-2">Un Placer Todos los Días, Ven, Compra y Disfruta!!!</h5>
    </div>

    <div class="row justify-content-center text-center mb-4"> <!-- CONTENEDOR QUE VA LUEGO DEL SLOGAN -->
      <h3 class="col-12">Nosotros</h3>
      <div class="col-md-5 col-12 m-auto text-justify">
        <h4 align="left" class="font-weight-bold">Una marca orgullosa de ser Venezolana</h4>
        <p>DiGusti Market es la cadena de retail y delivery más grande de Venezuela con capital 100% nacional. Somos reconocidos como una compañía lider en la comercialización de productos de consumo masivo de óptima calidad, a traves de toda la Isla de Margarita</p>
        <p>Además de comercializar un amplio portafolio de productos marcas privadas y extranjeras, DiGusti Market cuenta con marcas propias disponibles en las categorías de supermercado, como Bodegon, Carnes Blancas y Rojas, Charcuteria, Panaderia, Frutas y Verduras.</p>
      </div>
      <div class="col-md-6 col-12">
        <img src="Alimentos/abasto4.jpg" class="img-fluid">
      </div>
    </div> <!-- FIN CONTENEDOR LUEGO DEL SLOGAN -->
	
  </div> <!-- FIN CONTENEDOR DEL CONTENIDO PRINCIPAL -->
	<!-- /////////////////////////////////// FIN DE CONTENIDO //////////////////////////////// -->
	
  <!-- ///////////////////////////////////// INFORMACION DE LA PAGINA DENTRO DEL HOME //////////////////////////// -->
  <section class="container-fluid mb-2">
    <div class="row text-center text-white justify-content-center" style="background: rgba(110,103,102,1);"> <!-- REDES SOCIALES -->
      <h4 class="mt-3 col-lg-12">Visita Nuestras Redes Sociales</h4>
          <div class="col-lg-2 col-md-4 col-6 py-3 mb-3">
            <img src="Alimentos/facebook.png" style="width: 100px; height: 100px;"> <br>
            <a href="#" class="text-white font-italic">DiGusti Market Store</a>
          </div>
          <div class="col-lg-2 col-md-4 col-6 py-3 mb-3">
            <img src="Alimentos/instagram.png" style="width: 100px; height: 100px;"> <br>
            <a href="#" class="text-white font-italic">@DiGustiMarket</a>
          </div>
          <div class="col-lg-2 col-md-4 col-6 py-3 mb-3">
            <img src="Alimentos/twitter.png" style="width: 100px; height: 100px;"> <br>
            <a href="#" class="text-white font-italic">@DiGusti_Porlamar</a>
          </div>
          <div class="col-lg-2 col-md-4 col-6 py-3 mb-3">
            <img src="Alimentos/youtube.png" style="width: 100px; height: 100px;"> <br>
            <a href="#" class="text-white font-italic">Canal: Recetas DiGusti</a>
          </div>
          <div class="col-lg-2 col-md-4 col-6 py-3 mb-3">
            <img src="Alimentos/Pinterest.png" style="width: 100px; height: 100px;"> <br>
            <a href="#" class="text-white font-italic">pinterest.com/DiGusti</a>
          </div>
    </div>

    <div class="row justify-content-center" id="contacto"> <!-- CONTACTO -->
      <h3 class="mt-3 col-lg-12 text-center mb-3">Contacto</h3>
      <div class="col-lg-6 col-12 w-100 m-auto" style="height: 400">
        <p align="center">
                Porlamar / Av.Bolivar / Centro Comercial CCM <br>
                Local #80-12 Pasillo 2.
        </p>
        <ul class="list-unstyled">
          <li class="media row justify-content-md-end justify-content-center">
            <div class="text-center">
              <img class="m-auto align-self-center" src="Imagenes/horarios.png" height="64" width="64" alt="Generic placeholder image">
            </div>
            <div class="media-body col-md-6 col-8">
              <h5 class="mt-0 mb-1 text-center">Horario de Atención</h5>
                <p>
                  Mañana: 8:00<font size="2">am</font> a 12:00<font size="2">pm</font>
                </p>
                <p>
                  Tarde: 2:00<font size="2">pm</font> a 7:30<font size="2">pm</font>
                </p>
            </div>
          </li>
          <li class="media mb-4 mt-2 row justify-content-md-end justify-content-center">
            <div class="text-center">
              <img class="m-auto align-self-center" src="Imagenes/phone.png" height="64" width="64" alt="Generic placeholder image">
            </div>
            <div class="media-body col-md-6 col-8">
              <h5 class="mt-0 mb-1 text-center">Números de Contacto</h5>
              <p>Teléfono fijo: +58 295 262-4012</p>
              <p>Número celular: +58 412-7942183</p>
            </div>
          </li>
        </ul>
      </div>
      <div class="col-lg-6 col-12 text-md-left text-center">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3916.785538475444!2d-63.821752763309284!3d10.97955399387309!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8c318efc56f3d9e5%3A0x483bda58145d007a!2sCentro+Comercial+CCM%2C+Calle+Los+Uveros%2C+Porlamar+6301%2C+Nueva+Esparta!5e0!3m2!1ses!2sve!4v1527891771770" class="w-85" height="400" frameborder="0" style="border:0" allowfullscreen></iframe>
      </div>
    </div>
  </section>
  <!-- ///////////////////////////////////// FIN DE LA INFORMACION DE LA PAGINA ///////////////////////////////////// -->
  
    <!-- ///////////////////////////////////////////////// FOOTER ////////////////////////////////////////////// -->
  <div class="text-center bg-rojo text-white pt-2 px-2 h-50" >
      <h6> DiGusti Market Store siempre a la Vanguardia con los nuevos productos, al mejor precio y con la excelencia de nuestros servicios
      <img src="Alimentos/logo2b.png" class="img-fluid d-none d-lg-inline" width="150">
      </h6>
      <img src="Alimentos/logo2b.png" class="img-fluid d-lg-none d-md-inline" width="150">
  </div>
  <div class="text-center" style="background: rgba(110,103,102,1);border-top: 2px solid black">
    <font class="text-white font-weight-bold" size="2">Desarrollado por Andres Ramirez y Miguel Gil</font>
  </div>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>
