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
  <link rel="stylesheet" type="text/css" href="css/fontawesome-free-5.0.13/web-fonts-with-css/css/fontawesome-all.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="js/sweetalert.js"></script>
  <script type="text/javascript" src="jquery.validate.min.js"></script>
  <link rel="stylesheet" type="text/css" href="fuentes.css">
	<script>
    $(document).ready(function() {
      $("#manual1").click(function(event) { //para mostrar el manual de usuario en otra pestaña
        open("Guia.pdf", "Manual de Usuario", "height=100%","width=100%");
      });  

      $(".swal-button--confirm").addClass('bg-success');

      $(".c_factura").click(function(event) {
        var factura=$(this).attr("id");
        open("factura.php?factura="+factura+"","Factura de la compra","height=100%","width=100%");
      });

      $(".estado").click(function(event) {
        var ancho = screen.width;
        ancho/=3.5;
        var fac = $(this).attr("id");
        open("tracker.php?factura="+fac+"","Estado de Envio.","height=350,width=500,left="+ancho+",top=200");return false;
      });

      $("#monto2").hide();

      $("#forma").change(function(event) {
        $("#filtrar").removeAttr('disabled');
        $("#errorm").text('');
        $("label.error").text('');

        if($(this).val()=="0"){
            $("#monto1").attr('disabled', 'true');
            $("#monto1").val("");
            $("#monto2").attr('disabled', 'true');
        }
        else{
            $("#monto1").removeAttr('disabled');
            $("#monto2").removeAttr('disabled');
        }

        if($(this).val()=="entre"){
            $("#monto1").val("");
            $("#monto2").removeAttr('disabled');
            $("#monto2").slideDown(500);
            $("#monto1").attr('placeholder', 'Monto Menor');          
        }  
        else{
            $("#monto2").attr('disabled', 'true');
            $("#monto2").slideUp(250);
            $("#monto2").val("");
            $("#monto1").attr('placeholder', 'Monto');
        }
      });

      $("#register").validate({ //VALIDACIONES DEL FORMULARIO SI TODO SE CUMPLE SE LANZARIA EL EVENTO SUBMIT

        rules:{ //REGLAS DE VALIDACION PARA CADA INPUT
          monto1:{
            number:true,
            required: true,
            min: 0
          },
          monto2:{
            number:true,
            required: true,
            min: 0
          }
        },

        messages:{  //MENSAJES DE VALIDACION CONFORME A CADA VALIDACION ECHA
          monto1:{
            number:"Ingrese solamente valores númericos",
            required:"Ingrese un Monto de Busqueda",
            min: "Porfavor Ingrese solo montos superiores a 0 Bs"
          },
          monto2:{
            number:"Ingrese solamente valores númericos",
            required:"Ingrese un Monto de Busqueda",
            min: "Porfavor Ingrese solo montos superiores a 0 Bs"
          }   
        },

        errorPlacement:function(error,element){ //Para reposicionar los elementos de error que son level
          error.insertAfter(element);
        }

      });

      //VALIDAR QUE MONTO 2 NO SEA MENOR QUE MONTO 1
      $(".mont").change(function(event) {
        var num1 = $("#monto1").val();
        num1 = num1.replace(".",""); num1 = num1.replace(",","."); num1 = parseFloat(num1);
        var num2 = $("#monto2").val();
        num2 = num2.replace(".",""); num2 = num2.replace(",","."); num2 = parseFloat(num2);
        if(num1>num2){
          $("#errorm").text('Ingrese un Monto Mayor que sea superior que el Monto Menor');
          $("#monto2").addClass('error');
          $("#filtrar").attr('disabled', 'true');
        }
        else{
          $("#monto2").removeClass('error');
          $("#filtrar").removeAttr('disabled');
          $("#errorm").text(''); 
        }
      });

      //ELIMINAR FACTURA
      $(".eliminar").click(function(event){
        swal({
          title: "Eliminar Registro de Compra",
          text: "¿Esta seguro de eliminar el registro seleccionado de las compras?",
          icon: "warning",
          buttons: ["Cancelar","Aceptar"],
          dangerMode: true,
        })
        .then((willDelete) => {
          if (willDelete) {
            $.get("bin/deletecompra.php",{codigo:$(this).attr("id")},function(e){
            location.href="compras.php?mensaje=2";
            });
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

	<div class="container-fluid my-3"> <!--ESTE DIV ES EL CONTENEDOR-->

    <?php 
    $mensaje ="";
    $id=$_SESSION["id"];

    if(!isset($_POST["filtrar"]))
      $sql="SELECT * FROM FACTURA WHERE ID=$id ORDER BY C_Factura DESC";
    else{//APLICANDO FILTROS DE BUSQUEDA
      $mensaje = "según el filtro ";
      $forma = $_POST["forma"];
      if($forma!="0"){
        $monto1 = $_POST["monto1"];
        if($forma=="entre")
          $monto2 = $_POST["monto2"];
      }
      $pago = $_POST["pago"];
      $retiro = $_POST["retiro"];

      //VALIDAR LA FORMA DE FILTRO A EFECTUAR
      if(($pago=="0")AND($forma=="0")AND($retiro=="0"))//TODO VACIO
        $sql="SELECT * FROM FACTURA WHERE ID=$id ORDER BY C_Factura DESC";

      else if(($pago!="0")AND($forma=="0")AND($retiro!="0"))//SOLO POR METODO DE PAGO Y DE RETIRO
        $sql="SELECT * FROM FACTURA WHERE ID=$id AND Cancelacion='$retiro' AND Modalidad='$pago' ORDER BY C_Factura DESC";

      else if(($pago!="0")AND($forma=="0")AND($retiro=="0"))//SOLO POR METODO DE PAGO
        $sql="SELECT * FROM FACTURA WHERE ID=$id AND Modalidad='$pago' ORDER BY C_Factura DESC";
      
      else if(($pago=="0")AND($forma=="0")AND($retiro!="0"))//SOLO POR METODO DE RETIRO
        $sql="SELECT * FROM FACTURA WHERE ID=$id AND Cancelacion='$retiro' ORDER BY C_Factura DESC";
      
      else if(($pago=="0")AND($forma!="0")AND($retiro=="0")){//SOLO POR MONTOS
        if($forma=="entre")
          $sql="SELECT * FROM FACTURA WHERE ID=$id AND Monto BETWEEN $monto1 AND $monto2 ORDER BY C_Factura DESC";
        else if($forma=="mayor")
          $sql="SELECT * FROM FACTURA WHERE ID=$id AND Monto>=$monto1 ORDER BY C_Factura DESC";
        else
          $sql="SELECT * FROM FACTURA WHERE ID=$id AND Monto<=$monto1 ORDER BY C_Factura DESC";
      }

      else if(($pago!="0")AND($forma!="0")AND($retiro=="0")){//SOLO POR MONTOS Y DE METODO DE PAGO
        if($forma=="entre")
          $sql="SELECT * FROM FACTURA WHERE ID=$id AND Modalidad='$pago' AND Monto BETWEEN $monto1 AND $monto2 ORDER BY C_Factura DESC";
        else if($forma=="mayor")
          $sql="SELECT * FROM FACTURA WHERE ID=$id AND Modalidad='$pago' AND Monto>=$monto1 ORDER BY C_Factura DESC";
        else
          $sql="SELECT * FROM FACTURA WHERE ID=$id AND Modalidad='$pago' AND Monto<=$monto1 ORDER BY C_Factura DESC";
      }

      else if(($pago=="0")AND($forma!="0")AND($retiro!="0")){//SOLO POR MONTOS Y DE METODO DE RETIRO
        if($forma=="entre")
          $sql="SELECT * FROM FACTURA WHERE ID=$id AND Cancelacion='$retiro' AND Monto BETWEEN $monto1 AND $monto2 ORDER BY C_Factura DESC";
        else if($forma=="mayor")
          $sql="SELECT * FROM FACTURA WHERE ID=$id AND Cancelacion='$retiro' AND Monto>=$monto1 ORDER BY C_Factura DESC";
        else
          $sql="SELECT * FROM FACTURA WHERE ID=$id AND Cancelacion='$retiro' AND Monto<=$monto1 ORDER BY C_Factura DESC";
      }

      else if(($pago!="0")AND($forma!="0")AND($retiro!="0")){//BUSQUEDA USANDO TODOS LOS FILTROS
        if($forma=="entre")
          $sql="SELECT * FROM FACTURA WHERE ID=$id AND Cancelacion='$retiro' AND Modalidad='$pago' AND Monto BETWEEN $monto1 AND $monto2 ORDER BY C_Factura DESC";
        else if($forma=="mayor")
          $sql="SELECT * FROM FACTURA WHERE ID=$id AND Cancelacion='$retiro' AND Modalidad='$pago' AND Monto>=$monto1 ORDER BY C_Factura DESC";
        else
          $sql="SELECT * FROM FACTURA WHERE ID=$id AND Cancelacion='$retiro' AND Modalidad='$pago' AND Monto<=$monto1 ORDER BY C_Factura DESC";
      }

      else //ALGUN ERROR LLEGA AQUI
      $sql="SELECT * FROM FACTURA WHERE ID=$id ORDER BY C_Factura DESC";
    }
    
    $total = $bdd->query($sql)->rowCount();
    ?>

    <?php if($total==0):?>
    <!-- MENSAJE DE QUE NO HAY NADA EN EL CARRITO -->
      <?php if(!isset($_POST["filtrar"])):?>
      <div class="row justify-content-center mt-4 mb-4">
        <div class='col-md-8 col-12'>
          <p class='display-6 text-dark my-4'><span class='display-5 text-primary'>REGISTRO DE COMPRAS VACIO,</span>
            <br>NO SE ENCUENTRAN COMPRAS REALIZADAS O ALMACENADAS.</p>
          <h5 class='text-muted'>¡Sigue Disfrutando de DiGuti Market Store, ir al <a href="index.php">inicio</a>!</h5>
        </div>
      </div>
      <?php else: ?>
      <div class="row justify-content-center mt-4 mb-4">
        <div class='col-md-8 col-12'>
          <p class='display-6 text-dark my-4'><span class='display-5 text-primary'>REGISTRO DE COMPRAS VACIO,</span>
            <br>No se encuentran compras realizadas segun el filtro efectuado.</p>
          <h5 class='text-muted'>¡Trata con una nueva busqueda!, Retornar a la Sección de <a href="compras.php">Compras</a>.</h5>
        </div>
      </div>
      <?php endif; ?>
    <!--  FIN DE MENSAJE QUE NO HAY NADA EN EL CARRITO -->
    
    <?php else :?>
    <!-- CONTENEDOR EL CUAL IMPRIME LA INFORMACIÓN EN LA PARTE SUPERIOR DE LA TABLA-->
    <div class="col-xl-9 col-lg-9 col-md-10 col-12 mx-auto mt-xl-2 mt-2 mb-lg-1 mb-1">
      <div class="row justify-content-center">
        <h5 class='text-primary'>Para verificar las compras al detalle presione click sobre el código o estado de la factura.</h5>
      </div>
    </div>
    <div class="col-xl-9 col-lg-9 col-md-10 col-12 mx-auto mt-xl-1 mt-1 mb-lg-2 mb-2">
      <div class="row justify-content-between">
        <div class="col-xl-6 col-lg-5 col-12 align-bottom">
          <h6 class='text-muted'>Número de Compras registradas <?php echo $mensaje ?>(<strong class='text-dark'><?php echo $total ?></strong>)</h6>
        </div>
        <?php if(isset($_POST["filtrar"])):?>
        <div class="col-xl-7 col-lg-5 col-12 align-left">
          <h6 class='text-muted'>Vaciar Filtro:<a href="compras.php"> Click aqui!</a></h6>
        </div>
        <?php endif; ?>
        <div class="col-xl-5 col-lg-5 col-12 text-lg-right">
          <h6>Aplicar Filtros:<span style="font-size: 1.5rem;">
            <i class="fas fa-sort-amount-up text-success ml-1 filtro" data-toggle="modal" data-target=".bd-example-modal-lg"></i>
          </span></h6>
          <!-- PARA APLICAR FILTROS CON UN MODAL -->
        </div>
      </div>
    </div>
    <!-- FIN CONTENEDOR DE LA INFORMACIÓN -->

    <!-- MODAL PARA APLICAR LOS FILTROS DE BUSQUEDA -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Filtros de Busqueda</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form class="h-50" action="compras.php" method="post" id="register">
              <div class="form-group col-6 float-left">
                <label>Método de Pago:</label>
                <select class="form-control" id="pago" name="pago">
                  <option value="0">Seleccione el Método de Pago</option>
                  <option value="Efectivo">Efectivo</option>
                  <option value="Crédito">Crédito</option>
                </select>
              </div>
              <div class="form-group col-6 float-left">
                <label>Método de Retiro:</label>
                <select class="form-control" id="retiro" name="retiro">
                  <option value="0">Seleccione el Método de Retiro</option>
                  <option value="Delivery">Delivery</option>
                  <option value="Retiro Presencial">Retiro Presencial</option>
                </select>
              </div>
              <div class="form-group col-12 float-left">
                <label>Por Monto:</label>
                <div class="input-group">
                    <select class="form-control" id="forma" name="forma">
                      <option value="0">Seleccione una Busqueda por Monto</option>
                      <option value="mayor">Montos mayores a...</option>
                      <option value="menor">Montos menores a...</option>
                      <option value="entre">Montos entre:</option>
                    </select>
                    <input id="monto1" name="monto1" type="text" class="form-control mont" placeholder="Monto" aria-label="Username" aria-describedby="basic-addon1" disabled>
                    <input id="monto2" name="monto2" type="text" class="form-control mont" placeholder="Monto Mayor" aria-label="Username" aria-describedby="basic-addon1" disabled>
                </div>
              </div>
              <div class="modal-footer">
                <label class="error mr-3" id="errorm"></label>
                <button type="button" class="btn btn-secondary bg-danger borde" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary bg-danger borde" name="filtrar" id="filtrar">Filtrar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- FIN DEL MODAL DE LOS FILTROS -->

    <!-- TABLA DONDE SE MUESTRAN LAS COMPRAS REALIZADAS -->
    <div class="table-responsive col-xl-9 col-lg-9 col-md-10 col-12 mx-auto mt-xl-2 mt-1">
    <table class="table table-striped text-center table-bordered">
      <thead class="thead-success thead-dark">
        <tr>
          <th scope="col">Eliminar</th>
          <th scope="col">Código de Factura</th>
          <th scope="col">Fecha de la Compra</th>
          <th scope="col">Método de Retiro</th>
          <th scope="col">Método de Pago</th>
          <th scope="col">Monto de la Factura</th>
          <th scope="col">Estado de la Compra</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($bdd->query($sql) as $factura): ?> <!-- Impresion de las facturas -->
          <?php if($factura["ver"]==1):?>
        <tr>
          <th scope="row">
            <button id="<?php echo $factura['C_Factura']; ?>" type="button" class="eliminar" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </th>
          <td>
              <span class="text-primary c_factura" id="<?php echo $factura['C_Factura']; ?>" ><?php echo $factura["C_Factura"]; ?></span>
          </td>
          <td><?php echo $factura["Fecha"]; ?></td>
          <td><?php echo $factura["Cancelacion"]; ?></td>
          <td><?php echo $factura["Modalidad"]; ?></td>
          <td style="color: red"><strong><?php echo $factura["Monto"]; ?><font size="3"> Bs</font></strong></td>
          <td>
            <span class="text-primary estado" id="<?php echo $factura['C_Factura']; ?>" >Información</span>
          </td> <!-- DEBE IMPRIMIR ALGO SOBRE COMO VA EL ENVIO -->
        </tr>
          <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  <?php endif; ?>
    <!-- FIN DE LA TABLA DE LAS COMPRAS REALIZADAS -->
   
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
  //Mensaje de compra correcta
  if(isset($_GET["mensaje"])){
    if($_GET["mensaje"]==1){
        echo "
          <script>swal({
          title: 'Carrito Pagado Correctamente!',
          text: 'Verifique su compra realizada en la sección ultimas compras',
          icon: 'success',
          button: 'Aceptar',
        });</script>";
    }
    else if ($_GET["mensaje"]==2){
        echo "
          <script>swal({
          title: 'Registro de compra Eliminado Correctamente!',
          icon: 'success',
          button: 'Aceptar',
        });</script>"; 
    }
  }
?>