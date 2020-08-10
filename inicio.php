<?php //INICIO DE SESION SI PRESIONA EL BOTON DE INGRESAR

	include("bin/connect.php");
	session_start();

	if(isset($_SESSION["usuario"])){
		header("location:/CambiosDiGusti/index.php");
	}

	global $error;

	//SI SE LE DA AL BOTON DE INICIAR SESIÓN
	if(isset($_POST["botonlog"])){

		$sql="SELECT * FROM USUARIO INNER JOIN DATO_USUARIO ON USUARIO.ID=DATO_USUARIO.ID WHERE CORREO=:correo";

		$consulta=$bdd->prepare($sql);

		$login=$_POST["usuario"];

		$pass=$_POST["password"];

		$consulta->bindValue(":correo",$login);

		$consulta->execute();

		$comprobar=$consulta->rowCount();

		if($comprobar!=0){

			$tabla=$consulta->fetch(PDO::FETCH_ASSOC);

			if(password_verify($pass, $tabla["Pass"])){

				$_SESSION["usuario"]=$tabla["Nombre"];

				$_SESSION["id"]=$tabla["ID"];

				$_SESSION["tipo"]=$tabla["Tipo_Usuario"];

				$_SESSION["pass"]=$tabla["Pass"]; //GRABAR EL PASSWORD PARA DE TAL FORMA CONFIRMAR ALGUNAS CONFIGURACIONES

				header("location:/CambiosDiGusti/index.php");
			}else{
				$error = 1;
			}
		}else{

			/*echo "<script type='text/javascript'>alert('El usuario o la contraseña esta incorrecto')</script>";*/
			$error = 1;
		}
	}

	//SI SE LE DA AL BOTON DE REGISTRAR
	if(isset($_POST["registrar"])){
		
		$nombre=$_POST["nombre"];
		$email=$_POST["email"];
		$apellido=$_POST["apellido"];
		$direccion=$_POST["dir1"];
		$ciudad=$_POST["dir2"];
		$pass=$_POST["contraseña"];
		$ci=$_POST["cedula"];

		$q_verificar1="SELECT * FROM USUARIO WHERE CORREO= :correo";

		$q_verificar2="SELECT * FROM DATO_USUARIO WHERE CI= :ci";

		$verificar1=$bdd->prepare($q_verificar1);

		$verificar2=$bdd->prepare($q_verificar2);

		$verificar1->execute(array(":correo"=>$email));

		$verificar2->execute(array(":ci"=>$ci));

		if($verificar1->rowCount()>0){
			$error = 2;
		}else{
			if($verificar2->rowCount()>0){
				$error = 3;
			}else{

				$pass=password_hash($pass, PASSWORD_DEFAULT);

				$q_insertar1="INSERT INTO USUARIO (CORREO, PASS) VALUES (:correo, :pass)";

				$q_insertar2="INSERT INTO DATO_USUARIO (NOMBRE, APELLIDO, CI, DIRECCION, CIUDAD) VALUES (:nombre, :apellido, :ci, :direccion, :ciudad)";

				$usuario=$bdd->prepare($q_insertar1);

				$datos=$bdd->prepare($q_insertar2);

				$usuario->execute(array(":correo"=>$email, ":pass"=>$pass));

				$datos->execute(array(":nombre"=>$nombre, ":apellido"=>$apellido, ":ci"=>$ci, ":direccion"=>$direccion, ":ciudad"=>$ciudad));
				$error=4;
			}
		}
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>DiGusti Market Store</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
	<script type="text/javascript" src="jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="jquery.validate.min.js"></script>
 	<script type="text/javascript" src="js/sweetalert.js"></script>
	<style type="text/css">
		
		.inicial{
			height: 100%;
			background-color: rgba(0,0,0,0.6); 
		}

		.borde{
			border: 2px solid black;
		}

		body{
			margin: auto;
			background: url(Alimentos/abasto3.jpg);
			background-size: cover;
			background-attachment: fixed;
			background-position: center;
		}

		/*MENSAJE DE ERROR EN EL FORMULARIO*/
		label.error{	/*MANIPULO EL CSS DEL LABEL QUE SE ESCRIBE CUANDO HAY UN ERROR*/
			color: red;
			margin-left: 2%;
			display: inline;
			font-style: italic;
		}

		input.error{	/*MODIFICA LOS INPUT QUE HAYAN TENEDIO ALGUN ERROR*/
			border: 1px solid red;
			background: rgba(230,200,180,0.5);
		}

	</style>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".swal-button--confirm").addClass('bg-success');

			$("#titulo").hide();
			$("#titulo").fadeIn(3000);
			$("#titulo2").hide();
			$("#titulo2").fadeIn(4000);
			$("#registrar").hide();
			$("#ingresar").hide();
			$("#registrar").removeClass('d-none');
			$("#ingresar").removeClass('d-none');

			$("#iniciar").click(function(event) {	
				$("#ingresar").fadeIn(500);
			});

			$("#registro").click(function(event) {	
				$("#registrar").fadeIn(500);
				$("#ingresar").fadeOut(500);
				$(".ocultar").fadeOut(500);
				$(".inicial").css('height', '0');
			});

			$("#i-cerrar").click(function(event) {	
				$("#ingresar").fadeOut(500);
			});

			$(".r-cerrar").click(function(event) {
				$(".ocultar").fadeIn(500);
				$(".inicial").css('height', '100%');
			});

			//VENTANA MODAL ANIMACIÓN
			$('#myModal').on('shown.bs.modal', function () {
  				$('#myInput').trigger('focus')
			});

			//VALIDACIONES PARA EL ENVIO DEL REGISTRO 
			
			$("#register").validate({	//VALIDACIONES DEL FORMULARIO SI TODO SE CUMPLE SE LANZARIA EL EVENTO SUBMIT

				rules:{	//REGLAS DE VALIDACION PARA CADA INPUT
					nombre:{
						required:true
					},
					apellido: "required",
					email:{
						email:true,
						required:true
					},
					cedula:{
						number:true,
						required:true,
						minlength: 7
					},
					dir1:{
						required: true,
						minlength:10
					},
					dir2:{
						required: true,
					},
					contraseña:{
						required:true,
						minlength:6
					},
					confirmar:{
						equalTo:"#contraseña",
						required:true,
						minlength:6
					}
				},

				messages:{	//MENSAJES DE VALIDACION CONFORME A CADA VALIDACION ECHA
					nombre:{
						required:"Ingrese su nombre",
					},
					apellido:"Ingrese su Apellido",
					email:{
						email:"Ingrese formato correcto (eg: persona@gmail.com)",
						required:"Ingrese su email correspondiente"
					},
					cedula:{
						number:"Ingrese solamente valores númericos",
						required:"Ingrese su Cedula de Identidad",
						minlength: "La cedula debe tener al menos 7 cifras"
					},
					dir1:{
						required:"Ingrese su dirección de Vivienda",
						minlength:"Minimo 10 caracteres de dirección"
					},
					dir2:{
						required:"Ingresa tu dirección de Vivienda",
					},
					contraseña:{
						required:"Ingrese su contraseña",
						minlength:"La contraseñana debe tener al menos 6 caracteres"
					},
					confirmar:{
						required:"Ingresa su confirmación de contraseña",
						equalTo:"La contraseña no coincide con la principal",
						minlength:"La contraseña debe tener al menos 6 caracteres"
					}		
				},

				errorPlacement:function(error,element){ //Para reposicionar los elementos de error que son level
					error.insertAfter(element);
				}

			});
			
			$(".caja-1").focus(function(event) {
				$(this).css('background', 'rgba(255,218,42,0.2)');	//CAMBIARLE EL COLOR
			});

			$(".caja-1").blur(function(event) {
				$(this).css('background', 'none');
			});

			$(".block").hover(function() {
				$(this).css('background', 'rgba(48,48,47,0.7)'); 
			}, function() {
				$(this).css('background', 'rgba(33,127,20,1)');
			});

		});
	</script>
</head>
<body>
	<div class="container-fluid inicial">
		<div class="row justify-content-between"> <!-- bloque superior logo y redes sociales-->
			<div class="col-md-4  col-sm-6 mt-3">
				<img class="rounded float-left img-fluid" alt="Responsive image" src="Alimentos/logo1.png">	
			</div>
			<div class="col-md-4 col-sm-6 mt-3 text-center">
				<a href="#"><img width="40" height="40" class="rounded" src="Alimentos/facebook.png"></a>
				<a href="#"><img width="40" height="40" class="rounded" src="Alimentos/youtube.png"></a>
				<a href="#"><img width="40" height="40" class="rounded" src="Alimentos/twitter.png"></a>
				<a href="#"><img width="40" height="40" class="rounded" src="Alimentos/instagram.png"></a>
				<a href="#"><img width="40" height="40" class="rounded" src="Alimentos/Pinterest.png"></a>	
			</div>
		</div><br> <!-- fin de bloque superior -->

		<div class="container text-white text-center mt-4 ocultar"> <!-- Bloque de texto -->
			<p id="titulo" class="display-6 mb-4">Conoce nuestros productos</p>
			<p id="titulo2" class="display-5 text-success font-weight-bold">Entra y disfruta de DiGusti Market</p>
		</div> <!-- fin de bloque de texto -->

		<div id="botones" class="text-center mt-4 m-auto ocultar container"><!-- bloque de botones -->
			<br>
			<div class="col-md-4 col-sm-6 text-center m-auto">
				<button type="button" class="btn btn-primary btn-lg btn-block bg-danger borde" id="iniciar">Iniciar Sesión</button>
			</div>
			<div class="col-md-4 col-sm-6 text-center m-auto">
				<button type="button" class="btn btn-secondary btn-lg btn-block  my-4 borde" id="registro" data-toggle="modal" data-target="#registrar">Registrarse</button>
			</div>
		</div> <!-- fin de bloque de botones -->

		<footer class="container text-white text-center footer ocultar" id="footer"> <!-- footer -->
			<div class="row align-middle" style="margin-top: 5rem;height: 14.6vh;">
    			<div class="col-md-4 col-sm-8 h5 font-italic m-auto">
      				DiGusti Market Store 2018.
			    </div>
			    <div class="col-md-4 col-sm-8 font-italic m-auto">
			        Margarita - Porlamar <br> Av. Bolivar -  Local #80-12. 
			    </div>
 			    <div class="col-md-4 col-sm-8 font-italic m-auto">
 			    	DiGusti Market Store, con la excelencia y los productos mas frescos del Estado. 
			    </div>
 			</div>
		</footer> <!-- fin del footer -->
	</div>

	<!-- //////////////////////// MODAL DE INICIO DE SECION ////////////////////// -->
	<div class="container position-absolute rounded ml-3 borde p-2 d-none text-white col-sm-8" id="ingresar" style="top: 25%; background-color: #4F2428; width: 18rem">
		<form class="mt-3" method="post" action="<?php $_SERVER['PHP_SELF']?>">
  			<div class="form-group">
    			<label for="exampleInputEmail1">Correo</label>
    			<input name="usuario" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Ingrese correo">
  			</div>
  			<div class="form-group">
    			<label for="exampleInputPassword1">Contraseña</label>
    			<input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder=" Ingrese contraseña">
    			<a href="#" class="text-white">¿Olvido su contraseña?</a>
  			</div>
  			<button name="botonlog" type="submit" class="btn btn-primary bg-danger borde">Ingresar</button>
  			<p class="btn btn-primary bg-danger borde mt-3" id="i-cerrar" style="display: inline;">Cerrar</p>
		</form>
	</div>

	<!-- //////////////////////// MODAL DE REGISTRO ////////////////////// -->

	<div class="modal fade r-cerrar" id="registrar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  		<div class="modal-dialog" role="document">
    		<div class="modal-content" style="height: auto;">
      			<div class="modal-header">
        			<h5 class="modal-title">Formulario de Registro</h5>
        			<button type="button" class="close r-cerrar" data-dismiss="modal" aria-label="Close">
          			<span aria-hidden="true">&times;</span>
        			</button>
      			</div>
      			<div class="modal-body">
        			<form class="h-50" action="inicio.php" method="post" id="register">
						<div class="form-group">
			    			<label>Nombre</label>
    						<input name="nombre" type="text" class="form-control caja-1" id="nombre" aria-describedby="emailHelp" placeholder="Nombre">
			  			</div>
			  			<div class="form-group">
    						<label>Apellido</label>
			    			<input name="apellido" type="text" class="form-control caja-1" id="apellido" aria-describedby="emailHelp" placeholder="Apellido">
  						</div>
  						<div class="form-group">
    						<label>Correo Electrónico</label>
    						<input name="email" type="email" class="form-control caja-1" id="email" aria-describedby="emailHelp" placeholder="Correo Electrónico">
  						</div>
  						<div class="form-group">
  							<label>Cedula</label>
  							<div class="input-group">
      							<select class="form-control" id="tcedula">
      								<option>V-</option>
      								<option>E-</option>
    							</select>
      							<input id="cedula" name="cedula" type="text" class="form-control w-55 caja-1" placeholder="Cedula" aria-label="Username" aria-describedby="basic-addon1">
		    				</div>
  						</div>
  						<div class="form-group">
    						<label>Ciudad</label>
    						<select class="form-control caja-1" id="dir2" name="dir2">
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
  						<div class="form-group">
    						<label>Dirección</label>
    							<input name="dir1" type="text" class="form-control caja-1" id="dir1" aria-describedby="emailHelp" placeholder="Dirección...">
			  			</div>
  						<div class="form-group">
    						<label>Contraseña</label>
    						<input name="contraseña" type="password" class="form-control caja-1" id="contraseña" aria-describedby="emailHelp" placeholder="Contraseña">
  						</div>
  						<div class="form-group"> 
			    			<input name="confirmar" type="password" class="form-control caja-1" id="confirmar" aria-describedby="emailHelp" placeholder="Confimar">
  						</div>
  						<div class="modal-footer">
      						<button name="registrar" type="submit" class="btn btn-primary bg-danger borde">Registrar</button>
  							<p class="btn btn-primary bg-danger borde mt-3 r-cerrar"data-dismiss="modal">Cerrar</p>
      					</div>
					</form>
      			</div>
    		</div>
  		</div>
	</div>

</body>
</html>

<?php
	/*MENSAJES DE ERROR POR DUPLICADO O DATOS ERRONEOS*/

	if(($error==2)OR($error==3)){
		echo "<script type='text/javascript'>
			$('#registrar').modal('show');
			$('#nombre').val('".$nombre."');
			$('#apellido').val('".$apellido."');
			$('#email').val('".$email."');
			$('#cedula').val('".$ci."');
			$('#dir2').val('".$ciudad."');
			$('#dir1').val('".$direccion."');
		</script>";
	}
	if($error==1){
		echo "<script type='text/javascript'>
			swal({
        	  title: 'Error de Inicio de Sesión',
        	  text: 'Usuario o Contraseña incorrecta',
        	  icon: 'error',
        	  button: 'Aceptar',
        	});</script>";	
	}
	else if($error==2){
		echo "<script type='text/javascript'>
			swal({
        	  title: 'Error de Registro de Usuario',
        	  text: 'El Correo ya se encuentra afiliado a un Usuario',
        	  icon: 'error',
        	  button: 'Aceptar',
        	});</script>";
		echo "<script type='text/javascript'>
			$('#email').val('');
			$('#email').attr('placeholder','Ingrese un nuevo correo');
			$('#email').css('border','1px solid red');
		</script>";
	}
	else if($error==3){
		echo "<script type='text/javascript'>
			swal({
        	  title: 'Error de Registro de Usuario',
        	  text: 'La Cedula ya se encuentra afiliada a un Usuario',
        	  icon: 'error',
        	  button: 'Aceptar',
        	});</script>";
		echo "<script type='text/javascript'>
			$('#cedula').val('');
			$('#cedula').attr('placeholder','Ingrese una nueva cedula');
			$('#cedula').css('border','1px solid red');
		</script>";
	}
	else if($error==4){
		echo "<script type='text/javascript'>
			swal({
        	  title: 'Registro Exitoso',
        	  text: 'Inicie Sesión para visitar el DiGusti Market Store.',
        	  icon: 'success',
        	  button: 'Aceptar',
        	});</script>";
	}
?>
