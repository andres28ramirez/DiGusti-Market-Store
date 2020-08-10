<?php
	require("bin/connect.php");
	session_start();
	if(($_SESSION["tipo"]==2)||(!isset($_SESSION["usuario"])))
    header("location:/CambiosDiGusti/inicio.php");
  $vacio=1;
  
?>

<!DOCTYPE html>
<html>
<head>
	<title>Digusti Market</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/digusti.css">
  <link rel="stylesheet" type="text/css" href="css/fontawesome-free-5.0.13/web-fonts-with-css/css/fontawesome-all.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="jquery.validate.min.js"></script>
  <script type="text/javascript" src="js/sweetalert.js"></script>
  <link rel="stylesheet" type="text/css" href="fuentes.css">
	<script>
    $(document).ready(function() {
      $("#manual1").click(function(event) { //PARA MOSTRAR EL MANUAL DE USUSARIO
        open("Guia.pdf", "Manual de Usuario", "height=100%","width=100%");
      });  

      $(".swal-button--confirm").addClass('bg-success');
      $(".swal-text").addClass('text-center');

      <?php //EVENTO PARA AGREGAR EL BACKGROUND DE LA OPCION SELECCIONADA DE LA BARRA
        if(isset($_POST["v_productos"])||(isset($_GET["pagina"]))||(isset($_POST["actualizar"]))||(isset($_GET["imagen"]))){
          echo "$('#1').addClass('bg-danger');";
        }
        else if((isset($_POST["v_usuarios"]))||(isset($_POST["convertir"]))||(isset($_POST["quitar"]))||(isset($_POST["borrar"]))){
          echo "$('#2').addClass('bg-danger');";
        }
        else if((isset($_POST["v_insertar"]))||(isset($_POST["b_insert"]))){
          echo "$('#3').addClass('bg-danger');";
        }
        else if((isset($_POST["v_compras"]))||(isset($_GET["mensaje"]))){
          echo "$('#4').addClass('bg-danger');";
        }
        else if((isset($_POST["v_envios"]))||(isset($_POST["a_estatus"]))){
          echo "$('#6').addClass('bg-danger');";
        }
        else if(isset($_POST["v_reporte"])){
          echo "$('#5').addClass('bg-danger');";
      }
      ?> 
      //SCRIPTS DE LA PAGINA YA NORMAL //

      $(".e_compra").click(function(event) {
        var factura = $(this).attr('name');
        var estado = $(this).attr('id');
        if(estado==1){
          swal({
            title: "Eliminar Registro de Compra",
            text: "¿Esta seguro de eliminar el registro?, la compra todavia es visible por el usuario.",
            icon: "warning",
            buttons: ["Cancelar","Aceptar"],
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              $.get("bin/deletecompra.php",{codigo:factura,mensaje:"ok"},function(e){
                location.href="admin.php?mensaje=1";
              });
            }
          });
        }
        else{
          swal({
            title: "Eliminar Registro de Compra",
            text: "¿Esta seguro de eliminar el registro de Compra?",
            icon: "warning",
            buttons: ["Cancelar","Aceptar"],
            dangerMode: true,
          })
          .then((willDelete) => {
            if (willDelete) {
              $.get("bin/deletecompra.php",{codigo:factura,mensaje:"ok"},function(e){
                location.href="admin.php?mensaje=1";
              });
            }
          });
        }
      });

      //EVENTO PARA COLOCAR LOS DATOS DEL PRODUCTO A ACTUALIZAR EN EL MODAL
      $(".id_p").click(function(event){
        var codigo=$(this).attr("id");
        var c_nombre=".n_p" + codigo;
        var c_precio=".p_p" + codigo;
        var nombre=$(c_nombre).attr("id");
        var precio=$(c_precio).attr("id");
        var categoria = $(".c_p"+codigo).text();
        $("#a_cat").val(categoria); 
        document.getElementById("a_n").value=nombre;
        document.getElementById("a_p").value=precio;
        document.getElementById("btn_actualizar").value=codigo;
      });

      //ACOMODAR LOS DATOS DENTRO DEL PREVIEW DEL PRODUCTO
      $("#nom").change(function(event) {
        $("#p_nombre").text($(this).val());
      });

      $("#pre").change(function(event) {
        $("#p_precio").text($(this).val());
      });

      $("#archivo").change(function(event) { //EVENTO PARA AJUSTAR LA IMAGEN EN EL PREVIEW
        var texto = $(this).val();
        var texto = texto.replace(/.*[\/\\]/, '');
        var TmpPath = URL.createObjectURL(event.target.files[0]);
        $("#t_archivo").text("Archivo: "+texto); //AJUSTAR EL LABEL DEL ARCHIVO CONFORME AL QUE SE SELECCIONE
        $('#p_imagen').attr('src', TmpPath);
      });

      //PARA EJECUTAR AJAX DE MOSTRAR LOS DETALLES DE UNA COMPRA Y DEL ENVIO
      $(".c_factura").click(function(event){
        var codigo=$(this).attr("id");
        $.ajax({
          data : {'codigo' : codigo,
                  'tarea' : 1},
          url : 'bin/ajax_reporte.php',
          type: 'post',
          beforeSend: function(){
            $("#respuesta_d").html("<label class='font-weight-light text-center'>Por favor espere...</label>");
          },
          success: function(response){
            $("#respuesta_d").html(response);
          }
        });
      });

      $(".estatus").click(function(event){
        var cod=$(this).attr("id");
        $.ajax({
          data : {'codigo' : cod,
                  'tarea' : 2},
          url : 'bin/ajax_reporte.php',
          type: 'post',
          beforeSend: function(){
            $("#respuesta_e").html("<label class='font-weight-light text-center'>Por favor espere...</label>");
          },
          success: function(response){
            $("#respuesta_e").html(response);
          }
        });
      });

      $("#a_estatus").click(function(event){//ENVIA EL CODIGO PARA ACTUAÑIZAR EL ESTATUS
        var codigo=$(".codigo").text();
        document.getElementById("codigo_f").value=codigo;
      });

      //SECCION DE VALIDACIONES DE LOS DISTINTOS FORMULARIOS QUE SE VAYAN A UTILIZAR
      $("#insert").validate({ //VALIDACIONES DEL FORMULARIO DE INSERTAR PRODUCTO

        rules:{ //REGLAS DE VALIDACION PARA CADA INPUT
          nom:{
            minlength: 3,
            required: true 
          },
          pre:{
            number:true,
            required: true,
            min: 0
          }
        },

        messages:{  //MENSAJES DE VALIDACION CONFORME A CADA VALIDACION ECHA
          nom:{
            required: "Porfavor Ingrese un Nombre de Producto...",
            minlength: "El Nombre debe tener al menos 3 Caracteres."
          },
          pre:{
            number:"Solo puede poseer digitos...",
            required: "Porfavor Ingrese un Precio al Producto...",
            min: "El Precio no puede tener un valor menor a 0.00"
          }
        },

        errorPlacement:function(error,element){ //Para reposicionar los elementos de error que son level
          error.insertAfter(element);
        }

      });

      $("#update").validate({ //VALIDACIONES DEL FORMULARIO DE ACTUALIZAR PRODUCTO

        rules:{ //REGLAS DE VALIDACION PARA CADA INPUT
          a_nombre:{
            minlength: 3,
            required: true
          },
          a_precio:{
            number:true,
            required: true,
            min: 0
          }
        },

        messages:{  //MENSAJES DE VALIDACION CONFORME A CADA VALIDACION ECHA
          a_nombre:{
            required: "Porfavor Ingrese un Nombre de Producto...",
            minlength: "El Nombre debe tener al menos 3 Caracteres."
          },
          a_precio:{
            number:"Solo puede poseer digitos...",
            required: "Porfavor Ingrese un Precio al Producto...",
            min: "El Precio no puede tener un valor menor a 0.00"
          }
        },

        errorPlacement:function(error,element){ //Para reposicionar los elementos de error que son level
          error.insertAfter(element);
        }

      });

      $("#u_envio").validate({ //VALIDACIONES DEL FORMULARIO DE ACTUALIZAR PRODUCTO

        rules:{ //REGLAS DE VALIDACION PARA CADA INPUT
          descripcion:{
            minlength: 5,
            required: true
          }
        },

        messages:{  //MENSAJES DE VALIDACION CONFORME A CADA VALIDACION ECHA
          descripcion:{
            required: "Porfavor Ingrese una Descripción.",
            minlength: "La Descripción debe tener al menos 5 Caracteres."
          }
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
      border: 2px solid black;
    }

    .opcion:hover{
      opacity: 0.5;
      cursor: pointer;
    }

    .eliminar{
      font-size: 1.5rem;
      font-weight: 700;
      color: #000;
      opacity: .7;
      cursor: pointer;
    }

    .eliminar:hover, .eliminar:focus {
      text-decoration: none;
      opacity: 1;
    }

    button.eliminar{
      padding: 0;
      background-color: transparent;
      border: 0;
      -webkit-appearance: none;
    }

    .filtro:hover, .c_factura:hover, .estado:hover{
      text-decoration: underline;
      opacity: 0.8;
      cursor: pointer;
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

	<div class="container-fluid" style="background: rgba(0,0,0,0.9)"> <!--ESTE DIV ES EL CONTENEDOR-->
    <div class="row">
      <!-- CONTENEDOR DE LAS OPCIONES -->
      <!--<div class="col-lg-2 p-0" style="background: rgba(0,0,0,0.5)">ESTO ES PA LA VERSION NORMAL-->
      <nav class="navbar-expand-lg col-lg-2 p-0 borde text-center" style="background: rgba(0,0,0,0.5)">
        <span class="text-white d-md-block d-lg-none font-weight-bold">Panel de Control</span>
        <button class="navbar-toggler my-1" type="button" data-toggle="collapse" data-target="#panel" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
          <span><i class="fas fa-angle-double-down text-white"></i></span>
        </button>
      <div class="collapse navbar-collapse" id="panel">
        <form action="admin.php" method="post">
          <ul class="nav flex-column text-center">
            <li class="nav-item">
              <button class="nav-link text-white opcion mt-1 mx-auto w-100 border-0" style="background: transparent" id="1" type="submit" name="v_productos">Ver Productos</button>
              <hr class="my-1" style="border-top: 1px solid black;">
            </li>
            <li class="nav-item">
              <button class="nav-link text-white opcion mx-auto w-100 border-0" style="background: transparent" id="2" type="submit" name="v_usuarios">Ver Usuarios</button>
              <hr class="my-1" style="border-top: 1px solid black;">
            </li>
            <li class="nav-item">
              <button class="nav-link text-white opcion mx-auto w-100 border-0" style="background: transparent" id="4" type="submit" name="v_compras">Ver Compras</button>
              <hr class="my-1" style="border-top: 1px solid black;">
            </li>
            <li class="nav-item">
              <button class="nav-link text-white opcion mx-auto w-100 border-0" style="background: transparent" id="6" type="submit" name="v_envios">Ver Envíos</button>
              <hr class="my-1" style="border-top: 1px solid black;">
            </li>
            <li class="nav-item">
              <button class="nav-link text-white opcion mx-auto w-100 border-0" style="background: transparent" id="5" type="submit" name="v_reporte">Ver Reporte de Modificaciones</button>
              <hr class="my-1" style="border-top: 1px solid black;">
            </li>
            <li class="nav-item">
              <button class="nav-link text-white opcion mx-auto w-100 border-0" style="background: transparent" id="3" type="submit" name="v_insertar">Registrar Productos</button>
              <hr class="my-1" style="border-top: 1px solid black;">
            </li>
            <li class="nav-item">
              <a class="nav-link text-white opcion" href="#">Estadísticas de Mercadeo</a><hr class="my-1" style="border-top: 1px solid black;">
            </li>
            <li class="nav-item mb-1">
              <a class="nav-link text-white opcion" href="#">Modificar Contenido</a>
            </li>
          </ul>
        </form>
      </div>
      </nav><!-- BORRAR ESTO CUALQUIER VAINA -->
      <!-- FIN CONTENEDOR DE LAS OPCIONES -->

      <!-- CONTENEDOR DE DESPLEGAR CADA CONTENIDO DE LAS OPCIONES -->
      <div class="col-lg-10" style="border-left: 2px solid black;background: #fff">
        <!-- ///////////////////////////////////////////////////////PANEL DE MOSTRAR LOS PRODUCTOS -->
        <?php if(isset($_POST["v_productos"])||(isset($_GET["pagina"]))||(isset($_POST["actualizar"]))||(isset($_GET["imagen"]))):?>
        <!-- SQL DE VER PORDUCTOS -->
        <?php 
          /*////////////////////////////////////// MENSAJE DE PRODUCTO ELIMINADO ///////////////////// */
          if(isset($_GET["imagen"])){
            echo "<script type='text/javascript'>
                swal({
                    title: 'Borrado Exitoso del Producto!',
                    text: 'Se ha eliminado el registro de la Base de Datos.',
                    icon: 'success',
                    button: 'Aceptar',
                  });</script>"; 
          }

          /*///////////////////////////////////// ACTUALIZACIÓN /////////////////////////////// */
          if(isset($_POST["actualizar"])){
            if($_FILES['a_imagen']['name']==null){
              $cod=$_POST["actualizar"];
              $nom=$_POST["a_nombre"];
              $cat=$_POST["a_categoria"];
              $pre=$_POST["a_precio"];
              $sql="UPDATE PRODUCTOS SET NOMBRE=:n_nom, CATEGORIA=:n_cat, PRECIO=:n_pre WHERE Codigo=:id";
              $up_bdd=$bdd->prepare($sql);
              $up_bdd->execute(array(":n_nom"=>$nom,":n_cat"=>$cat,":n_pre"=>$pre,":id"=>$cod));
              $actualizar=1;
            }else{
              $cod=$_POST["actualizar"];
              $nom=$_POST["a_nombre"];
              $cat=$_POST["a_categoria"];
              $pre=$_POST["a_precio"];
              $name_imagen=$_FILES['a_imagen']['name'];
              $size_imagen=$_FILES["a_imagen"]['size'];
              $type_imagen=$_FILES['a_imagen']['type'];
              if($type_imagen=="image/jpeg" || $type_imagen=="image/jpg" || $type_imagen=="image/png" || $type_imagen=="image/gif"){
                if($size_imagen<=4294967298){
                  $sql="SELECT IMAGEN FROM PRODUCTOS WHERE IMAGEN=:img";
                  $img="/CambiosDiGusti/Server_img/" . $name_imagen;
                  $query=$bdd->prepare($sql);
                  $query->execute(array(":img"=>$img));
                  if($query->rowCount()<1){
                    $sqls="SELECT * FROM PRODUCTOS WHERE CODIGO=:cod";
                    $query=$bdd->prepare($sqls);
                    $query->execute(array(":cod"=>$cod));
                    $borrar=$query->fetch(PDO::FETCH_ASSOC);
                    $b_img=$_SERVER["DOCUMENT_ROOT"] . $borrar["Imagen"];
                    unlink($b_img);
                    $destino=$_SERVER["DOCUMENT_ROOT"] . "/CambiosDiGusti/Server_img/";
                    move_uploaded_file($_FILES["a_imagen"]["tmp_name"],$destino . $name_imagen);
                    $img="/CambiosDiGusti/Server_img/" . $name_imagen;
                    $sqlu="UPDATE PRODUCTOS SET NOMBRE=:n_nom, CATEGORIA=:n_cat, PRECIO=:n_pre, IMAGEN=:img WHERE Codigo=:id";;
                    $up_bdd=$bdd->prepare($sqlu);
                    $up_bdd->execute(array(":n_nom"=>$nom,":n_cat"=>$cat,":n_pre"=>$pre, ":img"=>$img, ":id"=>$cod));
                    $actualizar=1;
                  }else{
                    $actualizar=2;
                  }
                }else{
                  $actualizar=3;
                }
              }else{
                $actualizar=4;  
              }
            }
          }

          /* ////////////////////////////////// PAGINACION /////////////////////////////// */
          $n_filas=5;
          if(isset($_GET["pagina"])){
            if($_GET["pagina"]==1){
              $n_pagina=1;
            }else{
              $n_pagina=$_GET["pagina"];
            }
          }else{
            $n_pagina=1;
          }
          $empezar=($n_pagina-1)*$n_filas;
          $bdd_filas=$bdd->query("SELECT * FROM PRODUCTOS ");
          $n_consulta=$bdd_filas->rowCount();
          $pgn_total=ceil($n_consulta/$n_filas);
          $bdd_filas->closeCursor();
          $registro=$bdd->query("SELECT * FROM PRODUCTOS LIMIT $empezar,$n_filas")->fetchAll(PDO::FETCH_OBJ);
        ?>
        <div class="col-xl-10 col-lg-11 col-md-12 mx-auto mt-lg-0 table-responsive" id="opc1">
          <h5 class="text-center font-weight-bold pt-3 pb-2 border-bottom">Productos del Almacen</h5>
          <table class="table table-striped text-center">
            <thead class="thead-success thead-dark text-white border border-dark">
              <tr>
                <th scope="col">Código</th>
                <th scope="col">Nombre</th>
                <th scope="col">Categoría</th>
                <th scope="col">Precio</th>
                <th scope="col">Opciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($registro as $producto): ?>
                <tr>
                  <th><?php echo $producto->Codigo?></th>
                  <td class="n_p<?php echo $producto->Codigo?>" id="<?php echo $producto->Nombre?>"><?php echo $producto->Nombre?></td>
                  <td class="c_p<?php echo $producto->Codigo?>"><?php echo $producto->Categoria?></td>
                  <td class="p_p<?php echo $producto->Codigo?>" id="<?php echo $producto->Precio?>"><?php echo $producto->Precio?></td>
                  <td>
                    <a href="bin/delete.php?Codigo=<?php echo $producto->Codigo; ?>">
                      <button class="btn btn-danger borde">Eliminar</button>
                    </a>
                    <button class="btn btn-secondary id_p borde ml-lg-1 mt-xl-0 mt-1" data-toggle="modal" data-target="#actualizar" id="<?php echo $producto->Codigo?>">Actualizar</button>
                  </td>
                </tr>
              <?php endforeach; ?>  
            </tbody>
          </table>
          <nav aria-label="...">
            <ul class="pagination justify-content-center">
              <?php if ($n_pagina==1): ?>
                <li class="page-item disabled"><a class="page-link text-dark" style="opacity: 0.6" href="#" tabindex="-1">Anterior</a></li>
              <?php else: ?>
                <li class="page-item"><a class="page-link text-dark" href="?pagina=<?php echo $n_pagina-1;?>" tabindex="-1">Anterior</a></li>
              <?php endif; ?>

              <?php for($i=1; $i<=$pgn_total; $i++): ?>
                <?php if ($n_pagina==$i): ?>
                  <li class="page-item"><a class="page-link text-dark font-weight-bold" href="?pagina=<?php echo $i;?>"><?php echo $i; ?></a></li>
                <?php else: ?>
                  <li class="page-item"><a class="page-link text-dark" href="?pagina=<?php echo $i;?>"><?php echo $i; ?></a></li>
                <?php endif; ?>
              <?php endfor; ?>

              <?php if ($n_pagina==$pgn_total): ?>
                <li class="page-item disabled"><a class="page-link text-dark" style="opacity: 0.6" href="#">Siguiente</a></li>
              <?php else: ?>
                <li class="page-item"><a class="page-link text-dark" href="?pagina=<?php echo $n_pagina+1;?>">Siguiente</a></li>
              <?php endif; ?>

            </ul>
          </nav>
        </div>
            <!-- MODAL  PARA LA ACTUALIZACION DE LOS DATOS DE UN PRODUCTO -->
            <div class="modal fade" id="actualizar" tabindex="-1" role="dialog" aria-labelledby="titulo" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title text-center" id="titulo">Actualizar Producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form method="post" action="admin.php" enctype="multipart/form-data" id="update">
                        <div class="modal-body">
                          <div class="form-group">
                            <label class="font-italic">Nombre:</label>
                            <input class="form-control" type="text" name="a_nombre" id="a_n" required="true">
                          </div>
                          <div class="form-group">
                            <label class="font-italic">Precio:</label>
                            <input class="form-control" type="text" name="a_precio" id="a_p" required="true">
                          </div>
                          <div class="form-group">
                            <label class="font-italic">Categoría:</label>
                        <select class="custom-select" name="a_categoria" required="true" id="a_cat">
                              <option value="Carne">Carne</option>
                              <option value="Panaderia">Panaderia</option>
                              <option value="Charcuetria">Charcuteria</option>
                              <option value="Verdura">Verdura</option>
                              <option value="Bodega">Bodega</option>
                              <option value="Fruta">Fruta</option>
                          </select>
                          </div>
                          <div class="form-group">
                            <label class="font-italic">Imagen:</label>
                            <label class="font-weight-light"> (No es necesario actualizarla)</label>
                            <input class="form-control" type="file" name="a_imagen">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <button type="submit" class="btn btn-danger" name="actualizar" id="btn_actualizar">Actualizar</button>
                        </div>
                      </form>   
                  </div>
                </div>
            </div>
            <!-- FIN MODAL PARA ACTUALIZAR LOS PRODUCTOS -->
        <?php $vacio=0; endif; ?>
        <!-- ///////////////////////////////////////////////////////FIN DEL PANEL DE VER LOS PRODUCTOS -->
        
        <!-- ///////////////////////////////////////////////////////PANEL DE LOS USUARIOS -->
        <?php if((isset($_POST["v_usuarios"]))||(isset($_POST["convertir"]))||(isset($_POST["quitar"]))||(isset($_POST["borrar"]))):?>
        <!-- SQL DE VER USUARIOS -->
        <?php 
          if (isset($_POST["convertir"])){
            if ($_SESSION["tipo"]==0) {
              $id=$_POST["id"];
              $bdd->query("UPDATE USUARIO SET Tipo_Usuario=1 WHERE id=$id");
              echo "<script type='text/javascript'>
              swal({
                  title: 'Modificación Exitosa!.',
                  text: 'Se ha realizado la Modificación de tipo de Usuario Exitosamente.',
                  icon: 'success',
                  button: 'Aceptar',
                });</script>";
            }else{
              echo "<script type='text/javascript'>
              swal({
                  title: 'Modificación Exitosa!.',
                  text: 'Se ha realizado la Modificación de tipo de Usuario Exitosamente.',
                  icon: 'success',
                  button: 'Aceptar',
                });</script>";
            }
          }


          if (isset($_POST["quitar"])){
            $id = $_POST["id"];
            if ($_SESSION["tipo"]==0) {
              $id=$_POST["id"];
              $bdd->query("UPDATE USUARIO SET Tipo_Usuario=2 WHERE id=$id");
              echo "<script type='text/javascript'>
              swal({
                  title: 'Modificación Exitosa!.',
                  text: 'Se ha realizado la Modificación de tipo de Usuario Exitosamente.',
                  icon: 'success',
                  button: 'Aceptar',
                });</script>";
            }else{
              echo "<script type='text/javascript'>
              swal({
                  title: 'Modificación Exitosa!.',
                  text: 'Se ha realizado la Modificación de tipo de Usuario Exitosamente.',
                  icon: 'success',
                  button: 'Aceptar',
                });</script>";
            }
          }

          if (isset($_POST["borrar"])){
            $ci = $_POST["cedula"];
            $fila=$bdd->prepare("DELETE FROM dato_usuario where Ci=$ci");
            $fila->execute();
            echo "<script type='text/javascript'>
              swal({
                  title: 'Eliminación Exitosa!.',
                  text: 'Se ha Borrado con exito los datos del Usuario en la Base de Datos.',
                  icon: 'success',
                  button: 'Aceptar',
                  });</script>";
          }

          $usuarios=$bdd->query("SELECT * FROM usuario INNER JOIN dato_usuario ON usuario.ID= dato_usuario.ID")->fetchAll(PDO::FETCH_OBJ);
        ?>
        <div class="col-xl-10 col-lg-11 col-md-12 mx-auto mt-lg-0 table-responsive" id="opc2">
          <h5 class="text-center font-weight-bold pt-3 pb-2 border-bottom">Usuarios Registrados</h5>
              <table class="table table-striped text-center table-scroll">
                <h6 class="">Dale click a Convertir o Quitar para suministrarle al usuario permisos de administrador.</h6>
                <thead class="thead-success thead-dark text-white border border-dark">
                    <th scope="col">Eliminar</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Apellido</th>
                    <th scope="col">Cedula</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Admin</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                      <th scope="row">
                        <form action="admin.php" method="post">
                          <button type="submit" class="eliminar" aria-label="Close" name="borrar">
                            <span aria-hidden="true">&times;</span>
                          </button>
                          <input type="hidden" name="cedula" value="<?php echo $usuario->Ci?>">
                        </form>
                      </th>
                      <td><?php echo $usuario->Nombre?></td>
                      <td><?php echo $usuario->Apellido?></td>
                      <th><?php echo $usuario->Ci?></th>
                      <td><?php echo $usuario->Correo?></td>
                      <td>
                        <form action="admin.php" method="post">
                        <?php if ($usuario->Tipo_Usuario!=2): ?>
                          <button class="btn btn-danger w-100 borde" type="submit" name="quitar">Quitar</button>
                        <?php else: ?>
                          <button class="btn btn-success w-100 borde" type="submit" name="convertir">Convertir</button></a>
                        <?php endif; ?>
                        <input type="hidden" name="id" value="<?php echo $usuario->ID?>">
                        </form>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
        </div>
        <?php $vacio=0; endif; ?>
        <!-- ///////////////////////////////////////////////////////FIN PANEL DE LOS USUARIOS -->

        <!-- ///////////////////////////////////////////////////////PANEL DE REGISTRAR PRODUCTOS -->
        <?php if((isset($_POST["v_insertar"]))||(isset($_POST["b_insert"]))):?>
        <!-- SQL PARA INSERTAR PRODUCTOS -->
        <?php 
          if(isset($_POST["b_insert"])){
            $nom=$_POST["nom"];
            $cat=$_POST["cat"];
            $pre=$_POST["pre"];
            $name_imagen=$_FILES['fot']['name'];
            $size_imagen=$_FILES["fot"]['size'];
            $type_imagen=$_FILES['fot']['type'];
            if($type_imagen=="image/jpeg" || $type_imagen=="image/jpg" || $type_imagen=="image/png" || $type_imagen=="image/gif"){
              if($size_imagen<=4294967298){
                $sql="SELECT IMAGEN FROM PRODUCTOS WHERE IMAGEN=:img";
                $img="/CambiosDiGusti/Server_img/" . $name_imagen;
                $query=$bdd->prepare($sql);
                $query->execute(array(":img"=>$img));
                if($query->rowCount()<1){
                  $destino=$_SERVER["DOCUMENT_ROOT"] . "/CambiosDiGusti/Server_img/";
                  move_uploaded_file($_FILES["fot"]["tmp_name"],$destino . $name_imagen);
                  $img="/CambiosDiGusti/Server_img/" . $name_imagen;
                  //INSERCION A LA TABLA NORMAL QUE MOSTRARA LOS PRODUCTOS EN LA INTERFAZ, CARRITO ETC...
                  $sql="INSERT INTO PRODUCTOS (NOMBRE, CATEGORIA, PRECIO, IMAGEN) VALUES (:nom,:cat,:pre,:img)";
                  $in_bdd=$bdd->prepare($sql);
                  $in_bdd->execute(array(":nom"=>$nom,":cat"=>$cat,":pre"=>$pre, ":img"=>$img));
                  /*//INSERCION A LA TABLA EXTRA PARA QUE SE MUESTREN SIEMPRE LAS FACTURAS
                  $sql2="INSERT INTO PRODUCT_INSERT (NOMBRE, CATEGORIA, PRECIO) VALUES (:nom,:cat,:pre)";
                  $in_bdd=$bdd->prepare($sql2);
                  $in_bdd->execute(array(":nom"=>$nom,":cat"=>$cat,":pre"=>$pre));*/
                  echo "<script type='text/javascript'>
                    swal({
                          title: 'Registro Exitoso!',
                          text: 'El artículo se ha aregado correctamente a la Base de Datos.',
                          icon: 'success',
                          button: 'Aceptar',
                        });</script>";
                }else{
                  echo "<script type='text/javascript'>
                    swal({
                          title: 'Error de Inserción de Imagen!.',
                          text: 'Ya existe un archivo con el mismo nombre de imagen.',
                          icon: 'error',
                          button: 'Aceptar',
                        });</script>";
                  }
              }else{
                echo "<script type='text/javascript'>
                  swal({
                        title: 'Error de Inserción de Imagen!.',
                        text: 'La imagen es muy grande para la base de datos',
                        icon: 'error',
                        button: 'Aceptar',
                      });</script>";
                }
            }else{
              echo "<script type='text/javascript'>
                swal({
                    title: 'Error de Inserción de Imagen!.',
                    text: 'El formato de Archivo no es el Correcto.',
                    icon: 'error',
                    button: 'Aceptar',
                    });</script>";
              }
            }
        ?>
        <div class="row justify-content-around"  id="opc3">
          <div class="col-lg-5 col-md-6 col-sm-8 mt-md-0 mt-2">
            <form action="admin.php" method="post" id="insert" class="col-11 mx-auto rounded mt-1" enctype="multipart/form-data" style="border-left: 2px groove rgba(0,0,0,0.5); border-right: 2px groove rgba(0,0,0,0.5);">
              <h5 class="text-center font-weight-bold pt-3">Agregar Producto</h5><hr class="my-1 border-top border-dark">
              <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nom" id="nom" class="form-control">
              </div>
              <div class="form-group">
                <label>Categoría:</label>
                <select class="custom-select" style="cursor: pointer;" name="cat">
                      <option>Carne</option>
                      <option>Panaderia</option>
                      <option>Charcuteria</option>
                      <option>Verdura</option>
                      <option>Bodega</option>
                      <option>Fruta</option>
                  </select>
              </div>
              <div class="form-group">
                <label>Precio:</label>
                <input type="text" name="pre" id="pre" class="form-control">
              </div>
              <div class="form-group">
                <label>Imagen:</label>
                  <input type="file" name="fot" data-toggle="tooltip" data-placement="top" title="Solo se aceptan imagenes" accept="image/*" style="color: transparent;" id="archivo">
                  <label class="mt-1" id="t_archivo">Archivo no seleccionado.</label>
              </div>
              <div class="form-group text-center mt-1">
                <button type="submit" class="btn btn-danger borde w-50" name="b_insert">Agregar</button>
              </div>  
            </form>
          </div>

          <div class="col-lg-6 col-md-6 col-sm-8">
            <h5 class="text-center font-weight-bold pt-3">Vista del Producto</h5><hr class="my-1 border-top border-dark">
            <div class="card col-xl-8 mx-auto mt-md-5 mt-2 mb-md-0 mb-2">
              <img class="card-img-top" id="p_imagen" src="Server_img/cebolla.jpg" alt="Card image cap" height="180">
              <div class="card-body">
                <h5 class="card-title d-inline" id="p_nombre">Cebolla</h5>
                <p class="card-text text-center text-danger font-weight-bold" style="font-size: 2.2rem; margin-top: -8px">
                  <span id="p_precio">800000</span>
                  <span class="font-weight-normal" style="font-size: 1.5rem">Bs<span>
                  <font size="2" class="text-dark"> x Kg</font>
                </p>
                <div class="row" style="margin-top: -12px">
                  <input id="#" align="left" class=" col-2 form-control form-control-sm" type='number' name='cantidad' value='1' min='1'><font size='2' class="m-auto">.und</font>
                  <a href="#" class="btn bg-success btn-primary col-8 font-weight-bold border border-dark">Agregar al Carrito</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php $vacio=0; endif; ?>
        <!-- ///////////////////////////////////////////////////////FIN PANEL DE REGISTRAR PRODUCTOS -->

        <!-- ///////////////////////////////////////////////////////PANEL DE VER COMPRAS REALIZADAS -->
        <?php if((isset($_POST["v_compras"]))||(isset($_GET["mensaje"]))):?>
        <!-- SQL PARA VER COMPRAS -->
        <?php 
          $sql="SELECT * FROM FACTURA INNER JOIN DATO_USUARIO ON FACTURA.ID=DATO_USUARIO.ID ORDER BY factura.Id DESC";
          if (isset($_GET["mensaje"])) {
            echo "<script type='text/javascript'>
              swal({
                  title: 'Registro de Compra eliminado Exitosamente!',
                  icon: 'success',
                  button: 'Aceptar',
                });</script>";
          }
        ?>
        <div class="table-responsive col-xl-10 col-lg-11 col-md-12 mx-auto" id="opc4">
          <h5 class="text-center font-weight-bold pt-3 pb-2 border-bottom">Compras Realizadas</h5>
          <h6 class="">Presione sobre el código de la Factura para ver el detallado de la compra.</h6>
          <table class="table table-striped text-center table-scroll" style="height: 500px">
            <thead class="thead-success thead-dark border border-dark">
              <tr>
                <th scope="col">Eliminar</th>
                <th scope="col">Usuario (CI)</th>
                <th scope="col">Código de Factura</th>
                <th scope="col">Fecha de la Compra</th>
                <th scope="col">Método de Retiro</th>
                <th scope="col">Método de Pago</th>
                <th scope="col">Monto de la Factura</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($bdd->query($sql) as $factura): ?>
              <tr>
                <th scope="row"> 
                  <button id="<?php echo $factura['ver']; ?>" type="button" class="eliminar e_compra" aria-label="Close" name="<?php echo $factura['C_Factura']; ?>">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </th>
                <td class="font-weight-bold"><?php echo $factura["Ci"]; ?></td>
                <td>
                    <span class="text-primary c_factura" id="<?php echo $factura['C_Factura']; ?>" data-toggle="modal" data-target="#modaldr"><?php echo $factura["C_Factura"]; ?></span>
                </td>
                <td><?php echo $factura["Fecha"]; ?></td>
                <td><?php echo $factura["Cancelacion"]; ?></td>
                <td><?php echo $factura["Modalidad"]; ?></td>
                <td style="color: red"><strong><?php echo $factura["Monto"]; ?><font size="3"> Bs</font></strong></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!--//////////////////////////////////////////// MODAL DE DETALLE //////////////////////////////////////////////-->
        <div class="modal fade" id="modaldr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content" style="height: auto;">
                  <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Compra</h5>
                    <button type="button" class="close r-cerrar" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <span id="respuesta_d"></span>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  </div>
              </div>
            </div>
        </div>
        <?php $vacio=0; endif; ?>
        <!-- ///////////////////////////////////////////////////////FIN PANEL DE VER COMPRAS REALIZADAS -->

        <!-- ///////////////////////////////////////////////////////PANEL DE VER ENVIOS -->
        <?php if((isset($_POST["v_envios"]))||(isset($_POST["a_estatus"]))):?>
        <!-- SQL PARA VER ENVIOS -->
        <?php
          if(isset($_POST["a_estatus"])){
            $fact=$_POST["codigo"];
            $estatus=$_POST["estatus"];
            $descripcion=$_POST["descripcion"];
            /*$sql_a="UPDATE ENVIOS SET ESTADO=:estatus, MOVIMIENTO=:descri WHERE C_Factura=:factu";
            $actualizar=$bdd->prepare($sql_a);
            $actualizar->execute(array(":estatus"=>$estatus, ":descri"=>$descripcion, ":factu"=>$fact));*/
            $bdd->query("CALL u_envios('$estatus','$descripcion',$fact)");
            echo "<script type='text/javascript'>
                  swal({
                      title: 'Cambio de Estatus Exitoso',
                      text: 'Se ha cambiado el Estatus del Pedido.',
                      icon: 'success',
                      button: 'Aceptar',
                    });</script>";
            /*if ($actualizar->rowCount()>0){
              echo "<script type='text/javascript'>
                  swal({
                      title: 'Cambio de Estatus Exitoso',
                      text: 'Se ha cambiado el Estatus del Pedido.',
                      icon: 'success',
                      button: 'Aceptar',
                    });</script>";
            }else{
              echo "<script type='text/javascript'>
                swal({
                    title: 'Error de Cambio de Estatus',
                    text: 'No ha realizado ningun cambio o hay algun error.',
                    icon: 'error',
                    button: 'Aceptar',
                  });</script>";
            }*/
          }
          $factura=$bdd->prepare("SELECT * FROM ENVIOS");
          $factura->execute();
          $fact=$factura->fetchAll(PDO::FETCH_OBJ);
        ?>
        <div class="table-responsive col-xl-10 col-lg-11 col-md-12 mx-auto" id="opc6">
          <h5 class="text-center font-weight-bold pt-3 pb-2 border-bottom">Envios por Entregar</h5>
          <h6 class="">Presione sobre el código de la Factura para revisar la información del envio.</h6>
          <table class="table table-striped text-center table-scroll" style="height: 500px">
            <thead class="thead-success thead-dark border border-dark">
              <tr>
                <th scope="col">Código de Factura</th>
                <th scope="col">Estatus Actual</th>
                <th scope="col">Descripción</th>
                <th scope="col">Tipo de Compra</th>
                <th scope="col">Fecha del Movimiento</th>
                <th scope="col">Opción</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($fact as $regis): ?>
              <?php if($regis->estado!="Entregado") :?>
              <tr>
                <td>
                    <span class="text-primary c_factura" id="<?php echo $regis->C_Factura; ?>" data-toggle="modal" data-target="#modaldr"><?php echo $regis->C_Factura ?></span>
                </td>
                <td><?php echo $regis->estado ?></td>
                <td><?php echo $regis->movimiento ?></td>
                <td class="font-weight-bold"><?php echo $regis->tipo ?></td>
                <td><?php echo $regis->fecha ?></td>
                <td><button class="btn btn-secondary borde ml-lg-1 mt-xl-0 mt-1 estatus" id="<?php echo $regis->C_Factura ?>" data-toggle="modal" data-target="#modale">Modificar</button></td>
              </tr>
              <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <!--//////////////////////////////////////////// MODAL DE DETALLE //////////////////////////////////////////////-->
        <div class="modal fade" id="modaldr" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content" style="height: auto;">
                  <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Compra</h5>
                    <button type="button" class="close r-cerrar" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <span id="respuesta_d"></span>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  </div>
              </div>
            </div>
        </div>
        <!--//////////////////////////////////////////// MODAL DE ESTATUS //////////////////////////////////////////////-->
          <div class="modal fade" id="modale" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content" style="height: auto;">
                <div class="modal-header">
                  <h5 class="modal-title">Estatus de la Compra</h5>
                    <button type="button" class="close r-cerrar" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                  <form method="post" action="admin.php" id="u_envio">
                    <span id="respuesta_e"></span>
                    <div class="modal-footer">
                      <input type="submit" name="a_estatus" id="a_estatus" class="btn btn-success" value="Editar">
                      <input type="hidden" name="codigo" id="codigo_f" value="">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php $vacio=0; endif; ?>
        <!-- ////////////////////////////////////////////////////////FIN PANEL DE VER ENVIOS -->

        <!-- ///////////////////////////////////////////////////////PANEL DE VER REPORTE DE MODIFICACIONES -->
        <?php if(isset($_POST["v_reporte"])):?>
        <!-- SQL DE VER REPORTES -->
        <?php
          $fila=$bdd->query("SELECT * FROM reg_productos order by fecha asc")->fetchAll(PDO::FETCH_OBJ);
        ?>
        <div class="col-12 mx-auto mt-lg-0 table-responsive" id="opc5">
          <h5 class="text-center font-weight-bold pt-3 pb-2 border-bottom">Reporte de Modificaciones Efectuadas</h5>
          <table class="table table-striped text-center table-scroll" style="height: 500px">
            <h6 class="">Información de Productos que hayan sido Borrados, Actualizados o Ingresados.</h6>
            <thead class="thead-success text-white thead-dark border border-dark">
              <tr>
                  <th>Codígo</th>
                  <th>Nombre Antiguo</th>
                  <th>Categoria Antigua</th>
                  <th>Precio Antiguo</th>
                  <th>Nombre Nuevo</th>
                  <th>Categoria Nueva</th>
                  <th>Precio Nuevo</th>
                  <th>Accion</th>
                  <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
                <?php foreach ($fila as $registro): ?>
                  <tr>
                     <th><?php echo $registro->Codigo?></th>
                     <td><?php echo $registro->Nombre_v?></td>
                     <td><?php echo $registro->Categoria_v?></td>
                     <td style="color: #007bff"><strong><?php echo $registro->Precio_v?><font size="3"> Bs</font></strong></td>
                     <td><?php echo $registro->Nombre_n?></td>
                     <td><?php echo $registro->Categoria_n?></td>
                     <td style="color: #007bff"><strong><?php echo $registro->Precio_n?><font size="3"> Bs</font></strong></td>
                     <td><?php echo $registro->Accion?></td>
                     <td><?php echo $registro->Fecha?></td>
                  </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php $vacio=0; endif; ?>
        <!-- ///////////////////////////////////////////////////////FIN PANEL DE VER REPORTE DE MODIFICACIONES -->

        <!-- ///////////////////////////////////////////////////////PANEL DE CUANDO NO SE CARGA NADA -->
        <?php if($vacio==1) :?>
        <div class="col-lg-10 col-md-12 mx-auto p-5">
          <div class="row text-center">
            <div class='col-12 mt-5'>
              <p class='display-5 text-dark mt-3'><span class='display-4 text-primary text-center'>Sección de Administrador</span>
                <br>Presiona sobre las opciones para realizar distintas tareas.</p>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <!-- ///////////////////////////////////////////////////////FIN PANEL DE CUANDO NO SE CARGA NADA -->
      </div>
      <!-- FINAL DEL CONTENEDOR DE DESPLEGAR CADA CONTENIDO DE LAS OPCIONES -->
    </div>  
  </div> <!-- FIN CONTENEDOR DEL CONTENIDO PRINCIPAL -->
	<!-- /////////////////////////////////// FIN DE CONTENIDO //////////////////////////////// -->
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>

<!-- SECCION PARA IMPRIMIR LOS ALERTA -->
<?php 

/* ///////////////////////////////////// ALERTA ACTUALIZAR /////////////////////// */
  if(isset($_POST["actualizar"])){
    if ($actualizar==1) {
      echo "<script type='text/javascript'>
        swal({
            title: 'Actualizacion Exitosa',
            text: 'Se ha actualizado el producto correctamente.',
            icon: 'success',
            button: 'Aceptar',
          });</script>";
    }

    if ($actualizar==2) {
      echo "<script type='text/javascript'>
        swal({
            title: 'Error de Actualizacion',
            text: 'Se ha encontrado una imagen con el mismo nombre en el servidor.',
            icon: 'error',
            button: 'Aceptar',
          });</script>";
    }

    if ($actualizar==3) {
      echo "<script type='text/javascript'>
        swal({
            title: 'Error de Actualizacion',
            text: 'La imagen es muy grande para el servidor',
            icon: 'error',
            button: 'Aceptar',
          });</script>";
    }

    if ($actualizar==4) {
      echo "<script type='text/javascript'>
        swal({
            title: 'Error de Actualizacion',
            text: 'Solo se permiten subir imagenes al servidor.',
            icon: 'error',
            button: 'Aceptar',
          });</script>";
    }
  }

  /*///////////////////////////////// ALERTAS PARA MOSTRAR CUANDO SE TRANSFORME A ADMIN ///////////////*/
  
?>