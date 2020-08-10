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
  <script type="text/javascript" src="js/sweetalert.js"></script>
  <link rel="stylesheet" type="text/css" href="fuentes.css">
	<script>
    $(document).ready(function() {
      $("#manual1").click(function(event) { //para mostrar el manual de usuario en otra pestaña
        open("Guia.pdf", "Manual de Usuario", "height=100%","width=100%");
      });  

      $(".swal-button--confirm").addClass('bg-success');

    });
  </script>
	<style type="text/css">
		.banner{
			background: url('Alimentos/Pasillo.jpg');
			background-size: cover;
			background-position: center;
		}

    .borde{
      border: 1px solid rgba(0,0,0,0.5);
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
  <!-- SE HIZO UN PEQUEÑO CAMBIO, LO HABLARE CON MIGUEL A VER SI LE PARECE -->
	<div class="sticky-top">
		<nav class="navbar navbar-dark  navbar-expand-lg bg-rojo">
      <span class="text-white ml-1 d-md-block d-lg-none">Bienvenid@ <?php echo $_SESSION["usuario"];?>, a Digusti Market</span>
			<button class="navbar-toggler ml-auto mx-sm-0 mx-auto mt-sm-0 mt-1" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
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
                  <a class="dropdown-item d-xl-none d-lg-block d-none" id="manual1" style="cursor: pointer;"><img src="Alimentos/inter.png" class="mr-1" style=" width: 25px; height: 25;">Ayuda</a>
          				<a class="dropdown-item" href="bin/csion.php"><img src="Alimentos/off.png" class="mr-1" style=" width: 25px; height: 25;">Cerrar Sesión</a>
  					</div>
  				</div>
  				<span class="text-white ml-1 d-none d-lg-block">Bienvenid@ <?php echo $_SESSION["usuario"];?>, a Digusti Market Store</span>
    			<div class="navbar-nav ml-auto">
      				<a class="nav-item nav-link" href="index.php">Inicio</a>
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
      				<a class="nav-item nav-link" href="index.php#Nosotros">Nosotros</a>
              <a class="nav-item nav-link" href="index.php#contacto">Contacto</a>
              <a class="nav-item nav-link d-xl-block d-lg-none d-md-block" id="manual1" style="cursor: pointer;">Ayuda</a>
      				<form class="form-inline ml-lg-1 ml-0" action="productos.php" method="get">
      					<input type="text" name="producto" placeholder="Buscar..." class="form-control mr-1 mb-sm-0 mb-2">
      					<input type="submit" class="btn btn-secondary ml-xl-1 ml-lg-0 ml-md-1" value="Buscar">
      				</form>
    			</div>
    		</div>
		</nav>	
	</div>

	<!-- //////////////////////////////////// INICIO DE CONTENIDO ///////////////////////////// -->

	<div class="container-fluid my-3"> <!--ESTE DIV ES EL CONTENEDOR-->
    

  </div> <!-- FIN CONTENEDOR DEL CONTENIDO PRINCIPAL -->
	<!-- /////////////////////////////////// FIN DE CONTENIDO //////////////////////////////// -->
	
  <div class="text-center text-white mt-4" style="margin-bottom: -10px"><!-- FRANJA CON EL SLOGAN -->
    <div class="w-100 col-12" style="background-image: url('Alimentos/pasto2.png'); background-size: contain; height: 12vh"></div>
      <h5 class="w-100 py-2 col-12" style="background: rgba(0,0,0,0.7);">Un Placer Todos los Días, Ven, Compra y Disfruta!!!</h5>
  </div>
  
    <!-- ///////////////////////////////////////////////// FOOTER ////////////////////////////////////////////// -->
  <div class="text-center bg-rojo text-light pt-2 px-2 h-50" >
      <h6> DiGusti Market Store siempre a la Vanguardia con los nuevos productos, al mejor precio y con la excelencia de nuestros servicios
      <img src="Alimentos/logo2b.png" class="img-fluid d-none d-lg-inline" width="150">
      </h6>
      <img src="Alimentos/logo2b.png" class="img-fluid d-lg-none d-md-inline" width="150">
  </div>
  <div class="text-center" style="background: rgba(0,0,0,0.7);border-top: 2px solid black">
    <font class="text-white font-weight-bold" size="2">Desarrollado por Andres Ramirez y Miguel Gil</font>
  </div>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>
