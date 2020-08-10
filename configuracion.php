<?php
	require("bin/connect.php");
	session_start();
	if(!isset($_SESSION["usuario"]))
		header("location:/CambiosDiGusti/inicio.php");

	$success = 0; //PARA MOSTRAR LOS SWAL A LO ULTIMO DE MODIFICACIONES EXITOSAS O NO
	$id = $_SESSION['id'];
	if (isset($_POST["b_correo"])) { //ACTUALIZAR CORREO
		if(password_verify($_POST["contra"],$_SESSION["pass"])) {
        $correo = $_POST["correo1"];
    		//CONFIRMAR QUE NO ACTUALIZE A UN CORREO YA EXISTENTE
    		$sql="SELECT * FROM USUARIO WHERE CORREO= :correo";
    		$verificar=$bdd->prepare($sql);
    		$verificar->execute(array(":correo"=>$correo));
    		if($verificar->rowCount()>0){ //SI HAY PUES LANZO EL ERROR
    			$success = 7;
    		}
    		else{ //SI NO HAY LANZO LA ACTUALIZACIÓN
    			$success = 1;
    			$bdd->query("CALL actualizar('Correo','$correo',$id)");
    		}
    }
    else
      $success = 8;
	}
	if (isset($_POST["b_pass"])) { //ACTUALIZAR CONTRASEÑA
		if(password_verify($_POST["contra"],$_SESSION["pass"])){
        $success = 2;
    		$pass = password_hash($_POST["pass1"], PASSWORD_DEFAULT);
        $_SESSION["pass"] = $pass;
    		$bdd->query("CALL actualizar('Pass','$pass',$id)");
    }
    else{
      $success = 8;
    }
	}
	if (isset($_POST["b_nombre"])) { //ACTUALIZAR NOMBRE
		$success = 3;
		$nombre = $_POST["nombre"];
		$_SESSION["usuario"] = $nombre;
		$bdd->query("CALL actualizar('Nombre','$nombre',$id)");
	}
	if (isset($_POST["b_apellido"])) { //ACTUALIZAR APELLIDO
		$success = 4;
		$apellido = $_POST["apellido"];
		$bdd->query("CALL actualizar('Apellido','$apellido',$id)");
	}
	if (isset($_POST["b_ciudad"])) { //ACTUALIZAR CIUDAD
		$success = 5;
		$ciudad = $_POST["ciudad"];
		$bdd->query("CALL actualizar('Ciudad','$ciudad',$id)");
	}
	if (isset($_POST["b_direccion"])) { //ACTUALIZAR DIRECCIÓN
		$success = 6;
		$direccion = $_POST["direccion"];
		$bdd->query("CALL actualizar('Direccion','$direccion',$id)");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Digusti Market</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/digusti.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="jquery.validate.min.js"></script>
  <script type="text/javascript" src="js/sweetalert.js"></script>
  <link rel="stylesheet" type="text/css" href="fuentes.css">
	<script>
    $(document).ready(function() {
      $("#manual1").click(function(event) { //para mostrar el manual de usuario en otra pestaña
        open("Guia.pdf", "Manual de Usuario", "height=100%","width=100%");
      });  

      $(".swal-button--confirm").addClass('bg-success');

      //VALIDACIONES DE CADA FORMULARIO PARA REALIZAR LA ACTUALIZACION
      //FORMULARI DE EMAIL
      $("#f_correo").validate({
        rules:{ //REGLAS DE VALIDACION PARA CADA INPUT
          correo1:{
            email:true,
            required: true
          },
          correo2:{
          	equalTo:"#correo1",
            email:true,
            required: true
          }
        },
        //////////////////////////////////////////////////////////////////////
        messages:{  //MENSAJES DE VALIDACION CONFORME A CADA VALIDACION ECHA
          correo1:{
            email:"Ingrese un formato correcto (eg: persona@gmail.com).",
            required:"Ingrese un Correo Electrónico."
          },
         correo2:{
            email:"Ingrese un formato correcto (eg: persona@gmail.com).",
            required:"Ingrese un Correo Electrónico.",
            equalTo:"El Correo no concuerda con el principal."
          }   
        },
        errorPlacement:function(error,element){ //Para reposicionar los elementos de error que son level
          error.insertAfter(element);
        }
      });

      //FORMULARIO DE NUEVA CONTRASEÑA
      $("#f_pass").validate({
        rules:{ //REGLAS DE VALIDACION PARA CADA INPUT
          pass1:{
            minlength:6,
            required: true
          },
          pass2:{
          	equalTo:"#pass1",
            minlength:6,
            required: true
          }
        },
        //////////////////////////////////////////////////////////////////////
        messages:{  //MENSAJES DE VALIDACION CONFORME A CADA VALIDACION ECHA
          pass1:{
            minlength:"La Contraseñana debe tener al menos 6 caracteres.",
            required:"Ingrese su Nueva Contraseña."
          },
          pass2:{
            minlength:"La Contraseñana debe tener al menos 6 caracteres.",
            required:"Ingresa su Confirmación de Contraseña.",
            equalTo:"La Contraseña no coincide con la principal."
          }   
        },
        errorPlacement:function(error,element){ //Para reposicionar los elementos de error que son level
          error.insertAfter(element);
        }
      });

      //FORMULARIO DE NUEVO NOMBRE
      $("#f_nombre").validate({
        rules:{ //REGLAS DE VALIDACION PARA CADA INPUT
          nombre:{
            required: true,
            minlength: 3
          }
        },
        //////////////////////////////////////////////////////////////////////
        messages:{  //MENSAJES DE VALIDACION CONFORME A CADA VALIDACION ECHA
          nombre:{
          		required:"Ingrese su Nuevo Nombre.",
          		minlength:"Minimo 3 Caracteres de Nombre."
          },
        },
        errorPlacement:function(error,element){ //Para reposicionar los elementos de error que son level
          error.insertAfter(element);
        }
      });

      //FORMULARIO DE NUEVO APELLIDO
      $("#f_apellido").validate({
        rules:{ //REGLAS DE VALIDACION PARA CADA INPUT
          apellido:{
            required: true,
            minlength: 3
          }
        },
        //////////////////////////////////////////////////////////////////////
        messages:{  //MENSAJES DE VALIDACION CONFORME A CADA VALIDACION ECHA
          apellido:{
          		required:"Ingrese su Nuevo Apellido.",
          		minlength:"Minimo 3 Caracteres de Apellido."
          },
        },
        errorPlacement:function(error,element){ //Para reposicionar los elementos de error que son level
          error.insertAfter(element);
        }
      });

      //FORMULARIO DE NUEVA DIRECCION
      $("#f_direccion").validate({
        rules:{ //REGLAS DE VALIDACION PARA CADA INPUT
          direccion:{
            required: true,
            minlength: 10
          }
        },
        //////////////////////////////////////////////////////////////////////
        messages:{  //MENSAJES DE VALIDACION CONFORME A CADA VALIDACION ECHA
          direccion:{
				required:"Ingrese su Dirección de Vivienda.",
				minlength:"Minimo 10 Caracteres de Dirección."
			},
        },
        errorPlacement:function(error,element){ //Para reposicionar los elementos de error que son level
          error.insertAfter(element);
        }
      });
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

    	#banconf{
    		background-image: url("Imagenes/conf_bann.jpg");
    		background-size: cover;
			background-position: center;
			height: 12vh;
    	}

    	/*MENSAJE DE ERROR EN EL FORMULARIO*/
	    label.error{  /*MANIPULO EL CSS DEL LABEL QUE SE ESCRIBE CUANDO HAY UN ERROR*/
	      color: red;
	      margin-left: 2%;
	      display: inline;
	      font-style: italic;
	    }

	    input.error{  /*MODIFICA LOS INPUT QUE HAYAN TENEDIO ALGUN ERROR*/
	      border: 1px solid red;
	      background: rgba(230,200,180,0.5);
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
      <span class="text-white ml-1 d-md-block d-lg-none">Bienvenid@ <?php echo $_SESSION["usuario"];?>, a Digusti Market</span>
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

	<?php 
		//SQL PARA TOMAR LA INFORMACION ACTUAL DEL USUARIO Y MOSTRARLA EN LOS PLACEHOLDER DE ALGUNOS INPUT Y ETC..
		$sql1="SELECT * FROM USUARIO WHERE ID=:id";

		$sql2="SELECT * FROM DATO_USUARIO WHERE ID=:id";

		$consulta1=$bdd->prepare($sql1);

		$consulta2=$bdd->prepare($sql2);

		$consulta1->execute(array(":id"=>$_SESSION["id"]));

		$consulta2->execute(array(":id"=>$_SESSION["id"]));

		$registro1=$consulta1->fetch(PDO::FETCH_ASSOC);

		$registro2=$consulta2->fetch(PDO::FETCH_ASSOC);

	?>
  <div class="container-fluid my-3"> <!--ESTE DIV ES EL CONTENEDOR-->
    <div class="mt-2 mx-auto col-lg-8 col-md-10 col-12 d-none d-lg-block" id="banconf">
    	<h2 class="text-primary ">Mi Cuenta</h2>
    	<span class="text-muted font-weight-bold">Visualiza o Modifica tus datos!</span>
    </div>
    <!-- CUANDO ESTE MAS CHICO SE VA LA IMAGEN DEL BACKGROUND -->
    <div class="mt-2 mx-auto col-12 d-lg-none d-md-block text-center">
    	<h2 class="text-primary ">Mi Cuenta</h2>
    	<span class="text-muted font-weight-bold">Visualiza o Modifica tus datos!</span>
    </div>

    <div class="row mt-4">
    	<!-- DATOS DE ACCESO O LOGIN -->
    	<div class="col-lg-6 col-md-10 col-sm-10 col-11 mx-auto">
    		<h5>Datos de Acceso</h5>
    		<div class="border px-4 py-3 text-dark row">
    			<div class="col-10">
    				<span class="font-weight-bold">Email: </span><span class="font-italic"><?php echo $registro1["Correo"]; ?></span>
    			</div>
    			<div class="col-2 text-right">
    				<a href="" data-toggle="modal" data-target="#M-email">Editar</a>
    			</div>
    			<!-- MODAL PARA CONFIGURAR EL EMAIL -->
    			<div class="modal" id="M-email" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-dialog-centered" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title">Editar Correo Electrónico:</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
				        <form class="h-50" action="configuracion.php" method="post" id="f_correo">
						  <div class="form-group">
			    			<label>Correo Electrónico</label>
    						<input name="correo1" type="text" class="form-control caja-1" id="correo1" aria-describedby="emailHelp" placeholder="<?php echo $registro1["Correo"]; ?>">
			  			</div>
			  			<div class="form-group">
			    			<label>Confirmar Correo Electrónico</label>
    						<input name="correo2" type="text" class="form-control caja-1" id="correo2" aria-describedby="emailHelp" placeholder="Confirmar Correo...">
			  			</div>
              <div class="form-group">
                <label>Contraseña Actual para Confirmar Cambios.</label>
                <input name="contra" type="text" class="form-control caja-1" id="contra1" aria-describedby="emailHelp" placeholder="Contraseña Actual..." required title="Ingrese Contraseña para Confirmar los Cambios.">
              </div>
				        <div class="modal-footer">
				        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				        	<button type="submit" class="btn btn-primary"  name="b_correo">Modificar</button>
				        </div>
				  		</form>
				      </div>
				    </div>
				  </div>
				</div>
				<!-- FIN MODAL DE ACTUALIZACION -->
    		</div>
    		<div class="border px-4 py-3 text-dark mt-2 row">
    			<div class="col-10">
    				<span class="font-weight-bold">Contraseña: </span><span class="font-italic">********</span>
    			</div>
    			<div class="col-2 text-right">
    				<a href=""  data-toggle="modal" data-target="#M-pass">Editar</a>
    			</div>
    			<!-- MODAL PARA CONFIGURAR LA CONTRASEÑA -->
    			<div class="modal" id="M-pass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-dialog-centered" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title">Editar Contraseña de Acceso:</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
				        <form class="h-50" action="configuracion.php" method="post" id="f_pass">
						  <div class="form-group">
			    			<label>Contraseña</label>
    						<input name="pass1" type="password" class="form-control caja-1" id="pass1" aria-describedby="emailHelp" placeholder="Contraseña...">
			  			</div>
			  			<div class="form-group">
			    			<label>Confirmar Contraseña</label>
    						<input name="pass2" type="password" class="form-control caja-1" id="pass2" aria-describedby="emailHelp" placeholder="Confirmar Contraseña...">
			  			</div>
              <div class="form-group">
                <label>Contraseña Actual para Confirmar Cambios.</label>
                <input name="contra" type="password" class="form-control caja-1" id="contra2" aria-describedby="emailHelp" placeholder="Contraseña Actual..." required title="Ingrese Contraseña para Confirmar los Cambios.">
              </div>
				        <div class="modal-footer">
				        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				        	<button type="submit" class="btn btn-primary" name="b_pass">Modificar</button>
				        </div>
				  		</form>
				      </div>
				    </div>
				  </div>
				</div>
				<!-- FIN MODAL DE ACTUALIZACIÓN -->
    		</div>
    	</div>

    <?php if($_SESSION["tipo"]!=2): ?>
    	<div class="w-100"></div> <!--FORZAR LA SEPARACION-->
    	<!-- DATOS DEL USUSARIO -->
    	<div class="col-lg-6 col-md-10 col-sm-10 col-11 mx-auto mt-5">
    		<h5>Datos Personales</h5>
    		<div class="border px-4 py-3 text-dark row">
    			<div class="col-10">
    				<span class="font-weight-bold">Nombre: </span><span class="font-italic"><?php echo $registro2["Nombre"]; ?></span>
    			</div>
    			<div class="col-2 text-right">
    				<a href="" data-toggle="modal" data-target="#M-nombre">Editar</a>
    			</div>
    			<!-- MODAL PARA CONFIGURAR EL NOMBRE -->
    			<div class="modal" id="M-nombre" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-dialog-centered" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title">Editar Nombre Personal:</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
				        <form class="h-50" action="configuracion.php" method="post" id="f_nombre">
						<div class="form-group">
			    			<label>Nuevo Nombre</label>
    						<input name="nombre" type="text" class="form-control caja-1" id="nombre" aria-describedby="emailHelp" placeholder="<?php echo $registro2["Nombre"]; ?>">
			  			</div>
				        <div class="modal-footer">
				        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				        	<button type="submit" class="btn btn-primary" name="b_nombre">Modificar</button>
				        </div>
				  		</form>
				      </div>
				    </div>
				  </div>
				</div>
				<!-- FIN MODAL DE ACTUALIZACIÓN -->
    		</div>
    		<div class="border px-4 py-3 text-dark mt-2 row">
    			<div class="col-10">
    				<span class="font-weight-bold">Apellido: </span><span class="font-italic"><?php echo $registro2["Apellido"]; ?></span>
    			</div>
    			<div class="col-2 text-right">
    				<a href="" data-toggle="modal" data-target="#M-apellido">Editar</a>
    			</div>
    			<!-- MODAL PARA CONFIGURAR EL APELLIDO -->
    			<div class="modal" id="M-apellido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-dialog-centered" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title">Editar Apellido Personal:</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
				        <form class="h-50" action="configuracion.php" method="post" id="f_apellido">
						<div class="form-group">
			    			<label>Nuevo Apellido</label>
    						<input name="apellido" type="text" class="form-control caja-1" id="apellido" aria-describedby="emailHelp" placeholder="<?php echo $registro2["Apellido"]; ?>">
			  			</div>
				        <div class="modal-footer">
				        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				        	<button type="submit" class="btn btn-primary" name="b_apellido">Modificar</button>
				        </div>
				  		</form>
				      </div>
				    </div>
				  </div>
				</div>
				<!-- FIN MODAL DE ACTUALIZACIÓN -->
    		</div>
    	</div>
    <?php endif; ?>
    	<div class="w-100"></div> <!--FORZAR LA SEPARACION-->
    	<!-- DATOS DE DIRECCION DE HOGAR -->
    	<div class="col-lg-6 col-md-10 col-sm-10 col-11 mx-auto mt-5 mb-3">
    		<h5>Dirección de Hogar</h5>
    		<div class="border px-4 py-3 text-dark row">
    			<div class="col-10">
    				<span class="font-weight-bold">Ciudad: </span><span class="font-italic"><?php echo $registro2["Ciudad"]; ?></span>
    			</div>
    			<div class="col-2 text-right">
    				<a href="" data-toggle="modal" data-target="#M-ciudad">Editar</a>
    			</div>
    			<!-- MODAL PARA CONFIGURAR LA CIUDAD -->
    			<div class="modal" id="M-ciudad" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-dialog-centered" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title">Editar Ciudad de Hogar:</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
				        <form class="h-50" action="configuracion.php" method="post" id="f_ciudad">
						<div class="form-group">
			    			<label>Nueva Ciudad</label>
    						<select class="form-control caja-1" id="ciudad" name="ciudad">
      							<option>Porlamar</option>
      							<option>Juan Griego</option>
      							<option>Punta de Piedra</option>
      							<option>Pampatar</option>
      							<option>La Asunción</option>
      							<option>San Juan</option>
      							<option>Santa Ana</option>
      							<option>Boca del Río</option>
      							<option>Villa Rosa</option>
      							<option>El Valle</option>
    						</select>
			  			</div>
				        <div class="modal-footer">
				        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				        	<button type="submit" class="btn btn-primary" name="b_ciudad">Modificar</button>
				        </div>
				  		</form>
				      </div>
				    </div>
				  </div>
				</div>
				<!-- FIN MODAL DE ACTUALIZACIÓN -->
    		</div>
    		<div class="border px-4 py-3 text-dark mt-2 row">
    			<div class="col-10">
    				<span class="font-weight-bold">Dirección: </span><span class="font-italic"><?php echo $registro2["Direccion"]; ?></span>
    			</div>
    			<div class="col-2 text-right">
    				<a href="" data-toggle="modal" data-target="#M-direccion">Editar</a>
    			</div>
    			<!-- MODAL PARA CONFIGURAR LA DIRECCIÓN -->
    			<div class="modal" id="M-direccion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-dialog-centered" role="document">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h5 class="modal-title">Editar Dirección de Hogar:</h5>
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
				          <span aria-hidden="true">&times;</span>
				        </button>
				      </div>
				      <div class="modal-body">
				        <form class="h-50" action="configuracion.php" method="post" id="f_direccion">
						<div class="form-group">
			    			<label>Nueva Dirección</label>
    						<input name="direccion" type="text" class="form-control caja-1" id="direccion" aria-describedby="emailHelp" placeholder="<?php echo $registro2["Direccion"]; ?>">
			  			</div>
				        <div class="modal-footer">
				        	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
				        	<button type="submit" class="btn btn-primary" name="b_direccion">Modificar</button>
				        </div>
				  		</form>
				      </div>
				    </div>
				  </div>
				</div>
				<!-- FIN MODAL DE ACTUALIZACIÓN -->
    		</div>
    	</div>
    </div>
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

<?php
	if (($success!=0)AND($success<7)){
		$mensaje ="";
		if($success == 1){
			$mensaje = "Correo";
		}
		else if ($success == 2) {
			$mensaje = "Contraseña";
		}
		else if ($success == 3) {
			$mensaje = "Nombre";
		}
		else if ($success == 4) {
			$mensaje = "Apellido";	
		}
		else if ($success == 5) {
			$mensaje = "Ciudad de Hogar";
		}
		else if ($success == 6) {
			$mensaje = "Dirección de Hogar";
		}
		echo "<script type='text/javascript'>
				swal({
	        	  title: 'Configuración de ".$mensaje.", se realizo Exitosamente!',
	        	  icon: 'success',
	        	  button: 'Aceptar',
	        	});</script>";
	}
	else{
		if ($success == 7) {
			echo "<script type='text/javascript'>
				swal({
	        	  title: 'Error de Configuración de Correo.',
	        	  text: 'El Correo ya se encuentra afiliado a un Usuario.',
	        	  icon: 'error',
	        	  button: 'Aceptar',
	        	});</script>";
		}
    else if($success == 8){
      echo "<script type='text/javascript'>
        swal({
              title: 'Error de Configuración de Dato de Acceso.',
              text: 'La contraseña no concuerda con la de Inicio de Sesión.',
              icon: 'error',
              button: 'Aceptar',
            });</script>"; 
    }
	}
?>
