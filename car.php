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
  <script type="text/javascript" src="jquery.validate.min.js"></script>
  <script type="text/javascript" src="js/sweetalert.js"></script>
  <link rel="stylesheet" type="text/css" href="fuentes.css">
	<script>
    $(document).ready(function() {
      $("#manual1").click(function(event) { //para mostrar el manual de usuario en otra pestaña
        open("Guia.pdf", "Manual de Usuario", "height=100%","width=100%");
      });  
      
      //ASPECTO AL CLICKEAR EL FORMULARIO
      $(".caja-1").focus(function(event) {
        $(this).css('background', 'rgba(255,218,42,0.2)');  //CAMBIARLE EL COLOR
      });

      $(".caja-1").blur(function(event) {
        $(this).css('background', 'none');
      });

      $(".block").hover(function() {
        $(this).css('background', 'rgba(48,48,47,0.7)'); 
      }, function() {
        $(this).css('background', 'rgba(33,127,20,1)');
      });
      //FIN DE ASPECTO AL CLICKEAR EL FORMULARIO

      //SECCION DE ACOMODAR DETALLES DEL ASPECTO DE TABLA DE CARRITO Y BOTONES EXTERNOS//
      var valor = $("#precio2").text();

      $("#precio1").text(valor);

      $("#0").click(function(event){
        swal({
          title: "Vaciar Carrito",
          text: "¿Esta seguro de eliminar todos los productos del carrito?",
          icon: "warning",
          buttons: ["Cancelar","Aceptar"],
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $.get("bin/deletecarrito.php",{codigo:$(this).attr("id")},function(e){
            location.href="car.php?men=1";
            });
          }
        });
      });

      $(".eliminar").click(function(event){
        swal({
          title: "Eliminar Producto",
          text: "¿Esta seguro de eliminar el producto seleccionado del carrito?",
          icon: "warning",
          buttons: ["Cancelar","Aceptar"],
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $.get("bin/deletecarrito.php",{codigo:$(this).attr("id")},function(e){
            location.reload(true);//location.href="car.php?men=2";
            });
          }
        });
      });
      //FIN DE SECCION DE TABLA CARRITO Y BOTONES EXTERNOS

      // VALIDACION DEL FORMULARIO //
      $("#register").validate({ //VALIDACIONES DEL FORMULARIO SI TODO SE CUMPLE SE LANZARIA EL EVENTO SUBMIT

        rules:{ //REGLAS DE VALIDACION PARA CADA INPUT
          tipo_pago:{
            required:true
          },
          tarjeta:{
            number:true,
            required:true,
            minlength: 10,
            maxlength: 16
          },
          tipo_tarjeta:{
            required:true
          },
          retiro:{
            required:true
          },
          comentarios:{
            required:true,
            minlength:30
          },
          banco:{
            required:true
          },
          referencia:{
            digits:true,
            required:true
          },
          fecha:{
            required:true
          }
        },

        messages:{  //MENSAJES DE VALIDACION CONFORME A CADA VALIDACION ECHA
          tipo_pago:{
            required:"Ingresa la forma de cancelar los productos.",
          },
          tarjeta:{
            number:"Eg: 0000 0000 0000 0000 / Escribirlo todo pegado.",
            required:"Ingrese un número de tarjeta.",
            minlength:"El número de la tarjeta debe ser mayor a 10 dígitos.",
            maxlength:"El número no debe exceder de 12 dígitos."
          },
          tipo_tarjeta:{
            required:"Seleccione una opción."
          },
          retiro:{
            required:"Seleccione una opción de retiro."
          },
          comentarios:{
            required:"Porfavor Ingrese una dirección de envio.",
            minlength:"La dirección debe contener al menos 30 caracteres."
          },
          banco:{
            required:"Ingrese el Banco emisor."
          },
          referencia:{
            required:"Ingrese la Referencía de la Transferencia.",
            digits:"La Referencía solo puede poseer dígitos."
          },
          fecha:{
            required:"Ingrese la Fecha en que se realizo el Pago."
          }
        },

        errorPlacement:function(error,element){ //Para reposicionar los elementos de error que son level
          
          if(element.is(":radio")){
            error.appendTo(element.parent());
          }
          else
            error.insertAfter(element);
        }

      });
      //FIN DE VALIDACION DE FORMULARIO //

      //VALIDACION DE LA HABILITACION DE ZONAS DEL FORMULARIO//
      $("#direction").hide(); //OCULTO EL TEXTAREA DE ARRANQUE
      $("#credito").hide();
      $("#transferencia").hide();

      $("#retiro").change(function(event) { //SECCION PARA VALIDAR LA MUESTRA DEL TEXTAREA
        if($(this).val()=="Delivery"){
          $("#direction").slideDown(500);          
        }  
        else{
          $("#direction").slideUp(1000);
        }
      });

      $("#tipo_pago").change(function(event) {
        if($(this).val()=="Crédito"){
          $("#transferencia").slideUp(1000);
          $("#credito").slideDown(500);
        }
        else if($(this).val()=="Transferencia"){
          $("#credito").slideUp(1000);
          $("#transferencia").slideDown(500);
        }
        else{
          $("#transferencia").slideUp(1000);
          $("#credito").slideUp(1000);          
        }
      });
      /*$("#tipo_pago").change(function(event) { //HABILITAR O DESAHIBILITAR CAMPOS DEL FORMULARIO
        if ($(this).val()=="Crédito"){ //habilito los campos de la tarjeta de credito
          $("#tipo_tarjeta").removeAttr('disabled');
          $("#tarjeta").removeAttr('disabled');
          $("#tipo_tarjeta").css('background', '#fff');
          $("#tarjeta").css('background', '#fff');
        }
        else if($(this).val()=="Efectivo"){ //desahibilito los controles para el pago con credito
          $("#tipo_tarjeta").attr('disabled','true');
          $("#tipo_tarjeta").val("");
          $("#tipo_tarjeta").css('background', '#e9ecef');
      
          $("#tarjeta").css('background', '#e9ecef');
          $("#tarjeta").attr('disabled','true');
          $("#tarjeta").val("");
          $("label.error").hide();
          $("input.error").css('border', '1px solid #ced4da');
          $("select.error").css('border', '1px solid #ced4da');
        }
      });*/

      //ANIMACIONES Y MODIFICACIONES PARA EL CAMBIO DE LA CANTIDAD DE PRODUCTOS DEL CARRITO
      $(".balto").click(function(event) {
        var id = $(this).attr('id');//COJO ID UNICO POR BOTON!
        var suma=0; //valor para ajustar la cantidad
        if($(this).val()=="+")  //TODO CONFORME AL VALUE DEL BOTON
          suma = 1;
        else
          suma = -1;
        $(".unidades").each(function(index, el) {
          var cod = $(this).attr('id');
            if(id==cod){  //CUANDO ENCUENTRE QUE EL ID ES EL MISMO PARA LOS BOTONES Y LA ETIQUETA DE LA CANTIDAD
              var cantidad = $(this).text(); cantidad = parseInt(cantidad);
              if(cantidad==1){
                if(suma!=-1){
                  cantidad += suma;
                  $.get("bin/actualizarcarrito.php",{codigo:cod,cantidad:cantidad},function(e){
                    location.reload(true);
                  });
                }
              }
              else{
                cantidad += suma;
                $.get("bin/actualizarcarrito.php",{codigo:cod,cantidad:cantidad},function(e){
                    location.reload(true);
                  });
              }
            }
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

    .borde{
      border: 1px solid rgba(0,0,0,0.5);
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

    th{
      font-size: 1.1rem;
    }

    .balto{
      padding: 0;
      font-weight: bold;
      background: rgba(0,0,0,0.3);
    }

    .balto:hover{
     background: rgba(0,0,0,0.5);
     border-color: black; 
    }

    .unidades{
      margin-top: 1px;
      margin-bottom: -1px;
      border: 0;
      background: transparent;
    }

    .ani:hover{
      text-decoration: underline;
      cursor: pointer;
      font-weight: 450;
    }

    label.error{  /*MANIPULO EL CSS DEL LABEL QUE SE ESCRIBE CUANDO HAY UN ERROR*/
      color: red;
      margin-left: 2%;
      display: inline;
      font-style: italic;
    }

    input.error{  /*MODIFICA LOS INPUT QUE HAYAN TENEDIO ALGUN ERROR*/
      border: 1px solid red;
      /*background: rgba(230,200,180,0.5);*/
    }

    select.error{  /*MODIFICA LOS SELECT QUE HAYAN TENEDIO ALGUN ERROR*/
      border: 1px solid red;
      /*background: rgba(230,200,180,0.5);*/
    }

    textarea.error{  /*MODIFICA LOS TEXTAREA QUE HAYAN TENEDIO ALGUN ERROR*/
      border: 1px solid red;
      /*background: rgba(230,200,180,0.5);*/
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

	<div class="container-fluid my-3"> <!--ESTE DIV ES EL CONTENEDOR-->

    <?php 
      $sql="SELECT * FROM PRODUCTOS INNER JOIN CARRITO ON PRODUCTOS.CODIGO=CARRITO.CODIGO where ID=:id";

      $query=$bdd->prepare($sql);

      $query->execute(array(":id"=>$_SESSION["id"]));

      $contador = $query->rowCount();

      $monto = 0;
    ?>

    <?php if($contador==0):?>
    <!-- MENSAJE DE QUE NO HAY NADA EN EL CARRITO-->
    <div class="row justify-content-center mt-4 mb-4">
      <div class='col-md-8 col-12'>
        <p class='display-6 text-dark my-4'><span class='display-5 text-primary'>CARRITO VACIO,</span>
          <br>NO SE ENCUENTRAN PRODUCTOS REGISTRADOS.</p>
        <h5 class='text-muted'>¡Prueba anexar Productos al Carrito con solo un <a href="productos.php?producto=">click</a>!</h5>
      </div>
    </div>
    <!--FIN DE MENSAJE QUE NO HAY NADA EN EL CARRITO -->
    <?php endif;?>

    <?php if($contador!=0):?> <!-- IF DE QUE HAY PRODUCTOS EN EL CARRITO -->
    <!-- CONTENEDOR EL CUAL IMPRIME LA INFORMACIÓN EN LA PARTE SUPERIOR DE LA TABLA-->
    <div class="col-xl-8 col-lg-9 col-md-10 col-12 mx-auto mt-xl-4 mt-3  mb-lg-2 mb-2">
      <div class="row justify-content-between ">
        <div class="col-xl-6 col-lg-5 col-12">
          <h6 class='text-muted'>Producto pedidos (<strong class='text-dark'><?php echo $contador;?></strong>) - Monto Total: <strong id="precio1" class='text-dark'>...</strong></h6>
        </div>

        <?php if($contador>3):?> <!-- SI ES NECESARIO EL DESPLAZARSE AL FINAL Y EL MONTO TOTAL-->
        <div class="col-xl-6 col-lg-5 col-12 text-lg-right">
          <h6>Para Vaciar o Pagar carrito:<a href="#final"> Ir al final</a></h6><!-- SE IMPRIME SI HAY MAS DE 4 PRODUCTOS -->
        </div>
        <?php endif;?>
      </div>
    </div>
    <!-- FIN CONTENEDOR EL CUAL IMPRIME LA INFORMACIÓN EN LA PARTE SUPERIOR DE LA TABLA-->

  <!-- TABLA DONDE SE IMPRIMIRAN LOS ARTICULOS DEL CARRITO -->
  <div class="table-responsive col-xl-8 col-lg-9 col-md-10 col-12 mx-auto mt-xl-2 mt-1">
    <table class="table text-center">
      <thead class="thead-light">
        <tr>
          <th scope="col" >Eliminar</th>
          <th scope="col" >Producto</th>
          <th scope="col" >Cantidad</th>
          <th scope="col" >Precio Unitario</th>
          <th scope="col" >Precio Total</th>
        </tr>
      </thead>
      <?php while($tabla=$query->fetch(PDO::FETCH_ASSOC)):?> <!-- WHILE PARA IMPRIMIR LOS PRODUCTOS -->
      <tbody>
        <tr>
          <th scope="row">
              <button id="<?php echo $tabla['Codigo']?>" type="button" class="eliminar" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </th>
          <td><img src="<?php echo $tabla["Imagen"];?>" height="70"><br><?php echo $tabla["Nombre"]; ?></td>
          <td>
            <input type="button" class="btn btn-primary borde col-8 balto" id="<?php echo $tabla['Codigo']?>" value="+">
            <button type="button" class="col-8 unidades" id="<?php echo $tabla['Codigo']?>"><?php echo $tabla["Cantidad"]; ?></button>
            <input type="button" class="btn btn-primary borde col-8 balto" id="<?php echo $tabla['Codigo']?>" value="-">
          </td>
          <td style="color: red; font-weight: 450"><?php echo $tabla["Precio"]; ?><font size="3"> Bs</font></td>
          <td style="color: red"><strong><?php echo $tabla["Precio"]*$tabla["Cantidad"]; ?><font size="3"> Bs</font></strong></td>
        </tr>
      </tbody>
    <?php global $monto; $monto+=$tabla["Precio"]*$tabla["Cantidad"]; endwhile; ?> <!-- FIN DEL WHILE PARA IMPRIMIR LOS PRODUCTOS -->
    </table>
  </div>
  <!-- FIN TABLA DONDE SE IMPRIMIRAN LOS ARTICULOS DEL CARRITO -->

  <!-- BOTONES PARA PAGAR O VACIAR CARRITO COMO TAMBIEN VISUALIZAR EL MONTO FINAL-->
    <div class="col-xl-8 col-lg-9 col-md-10 col-12 mx-auto" id="final">
      <div class="row justify-content-between m-auto">
        <div>
          <h5 id="0" class="text-danger col-12 ani">Vaciar carrito</h6>
        </div>
        <div class="col-lg-6 col-12 text-lg-right text-md-center">
          <h5 class="text-muted d-inline">Total a pagar: <span id="precio2" class="text-dark"><?php echo $monto;?> Bs</span></h5>
          <button class="btn btn-primary bg-success borde font-weight-bold py-1 px-3 ml-md-1 ml-0" data-toggle="modal" data-target="#pagar">Pagar</button>
        </div>
      </div>
    </div>
  <!-- FIN BOTONES PARA PAGAR O VACIAR CARRITO -->
  <?php endif;?> <!-- FIN DEL IF DE QUE HAY PRODUCTOS EN EL CARRITO -->

  </div> <!-- FIN CONTENEDOR DEL CONTENIDO PRINCIPAL -->
	<!-- /////////////////////////////////// FIN DE CONTENIDO //////////////////////////////// -->
	
  <!-- MODAL PARA EL PAGO DE LOS PRODUCTOS -->
    <div class="modal fade" id="pagar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="height: auto;">
            <div class="modal-header">
              <h5 class="modal-title">Proceso de Pago del Carrito</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form class="h-50" action="bin/compra.php" method="post" id="register">
              <div class="form-group">
                <label>Método de Pago</label>
                <select class="form-control caja-1" id="tipo_pago" name="tipo_pago">
                    <option value="">Seleccione un método de Pago</option>
                    <option value="Crédito">Crédito</option>
                    <option value="Transferencia">Transferencia</option>
                </select>
              </div>
              <div id="transferencia">
                <div class="form-group">
                  <label>Cuenta a Transferir: </label>
                  <font size="3" class="text-muted">Banesco Nro: 01160442190207628556</font>
                </div>
                <div class="form-group">
                  <label>Fecha del Pago</label>
                  <input name="fecha" type="date" class="form-control caja-1" id="fecha" aria-describedby="emailHelp" placeholder="Fecha de Transferencía">
                </div>
                <div class="form-group">
                  <label>Referencia de Transferencia</label>
                  <input name="referencia" type="text" class="form-control caja-1" id="referencia" aria-describedby="emailHelp" placeholder="Número de Referencía">
                </div>
                <div class="form-group">
                  <label>Banco emisor de la Transferencia</label>
                  <select class="form-control caja-1" id="banco" name="banco">
                        <option value="">Seleccione un Banco</option>
                        <option value="Banesco">Banesco</option>
                        <option value="Mercantil">Mercantil</option>
                        <option value="BOD">BOD (Banco Occidental de Descuento)</option>
                        <option value="Bancaribe">Bancaribe</option>
                        <option value="BNC">BNC (Banco Nacional de Crédtio)</option>
                        <option value="Venezuela">Venezuela</option>
                        <option value="Bicentenario">Bicentenario</option>
                        <option value="Provincial">BBVA Provincial</option>
                        <option value="Otro">Otro Banco...</option>
                  </select>
                </div>
              </div>
              <div id="credito">
                <div class="form-group">
                  <label>Número de Tarjeta</label>
                  <input name="tarjeta" type="text" class="form-control caja-1" id="tarjeta" aria-describedby="emailHelp" placeholder="Número">
                </div>
                <div class="form-group">
                  <label>Tipo de Tarjeta</label>
                  <select class="form-control caja-1" id="tipo_tarjeta" name="tipo_tarjeta">
                        <option value="">Seleccione un tipo de tarjeta</option>
                        <option value="Visa">Visa</option>
                        <option value="MasterCard">MasterCard</option>
                        <option value="Amex">Amex</option>
                        <option value="American Express">American Express</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label>Forma de retiro de la compra</label>
                  <select class="form-control caja-1" id="retiro" name="retiro">
                      <option value="">Seleccione una forma de retiro</option>
                      <option value="Retiro Presencial">Retiro Presencial</option>
                      <option value="Delivery">Delivery</option>
                  </select>
              </div>
              <div class="form-group" id="direction">
                <label>Dirección de Envio:</label><br>
                <font size="3" class="text-muted">Proporcione una dirección correcta recuerde que ahi se realizara el envio de su carrito.</font>
                <div class="input-group">
                  <textarea name="comentarios" rows="3" cols="100" id="comentarios"></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button name="comprar" type="submit" class="btn btn-primary bg-danger borde" value="<?php echo $monto?>">Aceptar</button>
                <p class="btn btn-primary bg-danger borde mt-3" data-dismiss="modal">Cancelar</p>
              </div>
          </form>
            </div>
        </div>
      </div>
  </div>

  <!-- FIN MODAL PARA EL PAGO DE LOS PRODUCTOS-->
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

  
  
  if (isset($_GET["mensaje"])){
    echo "
          <script>swal({
          title: 'Ha ocurrido un error al pagar!',
          icon: 'error',
          button: 'Aceptar',
        });</script>";
  }

  if(isset($_GET["men"])){
    $valor = $_GET["men"];
    if($contador==0){$valor=1;}

    switch ($valor) {
      case '1':{
        echo "
          <script>swal({
          title: 'Carrito Vaciado correctamente!',
          icon: 'success',
          button: 'Aceptar',
        });</script>";
        break;
      }
      case '2':{
        echo "
          <script>swal({
          title: 'Producto eliminado correctamente!',
          icon: 'success',
          button: 'Aceptar',
        });</script>";
        break;
      }
    }
  }
?>
