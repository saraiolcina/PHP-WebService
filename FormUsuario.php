<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
	<title>LISTA USUARIO</title>
</head>

<body>

<div id="content">

<h2>MODIFICAR/BORRAR/CREAR USUARIOS</h2>

<?php
require ("BaseDatos.php");

//Controlamos que los valores de acción estén bien definidos 

if(!isset($_POST["action"])){
    echo "Ha ocurrido un error";?> <br>
    <a href="ListaUsuario.php">Volver a lista de Usuarios</a>
    <?php exit();
}

//En función de la acción seleccionada, se llamará a unas funciones u otras.
switch ($_POST["action"]){
	case "modify":
		modificarUsuario();            
		break;
	case "modifyBD":
		modificarUsuarioBD($_POST["id"],$_POST["Nombre"],$_POST["Email"]);
		break;
	case "delete":
		borrarUsuario();            
		break;
	case "deleteBD":
		borrarUsuarioBD($_POST["id"]);
		break;
	case "create":
		crearUsuario();
		break;
	case "createBD":
		crearUsuarioBD($_POST["Nombre"],$_POST["Email"],$_POST["Password"]);
		break;        
}
    

//Función para modificar un usuario
    function modificarUsuario(){

        //Obtenemos todos los campos del usuario solicitado
        $usuario=getUsuario($_POST["id"]);
        
        //Condición para el usuario superAdmin. Si se elige modificar este usuario, no se permite y se muestra el mensaje.
        if($_POST["id"]==='3'){?>
			<h3>Este usuario no se puede modificar</h3> 
			<br>
			<a href="ListaUsuario.php">Volver a lista de usuarios</a><br>        
        <?php 
        }
        else{
        ?>
        <!--  Sacamos en esta página los valores sacados en la función anterior -->
        <h3>Usuario a modificar</h3> 
        
    	<form action= "FormUsuario.php" method="post">
    		<input type="hidden" name="action" value="modifyBD">
    		<input type="hidden" name="id" value="<?php echo $usuario["UserID"]?>">
    		
    		<label for="UserID">ID</label>
    		<input type="text" name="UserID" style="margin-left: 90px;" value="<?php echo $usuario["UserID"]?>"><br>
    		
    		<label for="Nombre">Nombre</label>
    		<input type="text" name="Nombre" style="margin-left: 55px;" value="<?php echo $usuario["Nombre"]?>"><br>
    		
    		<label for="Email">Email</label>
    		<input type="text" name="Email" style="margin-left: 70px;" value="<?php echo $usuario["Email"]?>"><br>
    		
    		<?php 
    		 $dateFormat= '%d/%m/%y';
    		 ?>
    		<label for="LastAccess">Último Acceso</label>
    		<input type="text" name="LastAccess" style="margin-left: 12px;" value="<?php echo $usuario["DATE_FORMAT(LastAccess,'".$dateFormat."')"]?>"><br>
    		
    		<label for="Enabled">Enabled</label>
    		<input type="text" name="Enabled" style="margin-left: 55px;" value="<?php echo $usuario["Enabled"]?>"><br>
    		
    		<br>
    		<!-- Botón aceptar -->
    		<input id="diff" type="submit" value = "Guardar">
    	</form>    	
    	
    	<!-- Para el botón de cancelar, creamos otro formulario que redirija a la página de listado de usuarios -->
		<br>
		<form action="ListaUsuario.php" method="post">
			<input id="diff" type="submit" value="Cancelar">
		</form>    		
        
    	<?php 
    	}
    		
    } 
    
    
//Función para borrar usuario
    function borrarUsuario(){

		//Condición para el usuario superAdmin. Si se elige borrar este usuario, no se permite y se muestra el mensaje. 	
		if($_POST["id"]==='3'){?>
			<h3>Este usuario no se puede eliminar</h3>        
			<br>
			<br>
			<a href="ListaUsuario.php">Volver a lista de usuarios</a><br>		
		<?php 
		}
		else {
		//Condición para el resto de usuarios
		?>
			<h3>¿Desea eliminar el Usuario?</h3>
			<br>
			<form action="FormUsuario.php" method="post">
				<input type="hidden" name="action" value="deleteBD">
				<input type="hidden" name="id" value="<?php echo $_POST["id"]?>">
				<input id="diff" type="submit" value="Confirmar"> 	
			</form>
			<br>
			<form action="ListaUsuario.php" method="post">
				<input id="diff" type="submit" value="Cancelar">
			</form>
		<?php 
     	}        
	}


//Función para crear Usuario
function crearUsuario(){?>

	<h3>Crear usuario</h3>
	
	<form action= "FormUsuario.php" method="post">
		<input type="hidden" name="action" value="createBD">
    
        <!-- Solo permitimos crear nombre, email y contraseña, ya que desde la función del fichero BaseDatos.php, el id se genera en 
		autoincrement, por defecto asignamos todos los usuarios nuevos con enabled y lastAccess se actualiza a la fecha del momento que se crea-->
    	<label for="Nombre">Nombre</label>
		<input type="text" name="Nombre" style="margin-left: 55px;" value=""><br>
		
		<label for="Email">Email</label>
		<input type="text" name="Email" style="margin-left: 70px;" value=""><br>
		
		<label for="Password">Contraseña</label>
		<input type="password" name="Password" style="margin-left: 36px;"value=""><br>	
		
		<br>
		<br>
		<!-- Botón aceptar -->
		<input id="diff" type="submit" value = "Guardar">
	</form>    	
	
	<!-- Para el botón de cancelar, creamos otro formulario que redirija a la página de Lista de usuarios -->
	<form action="ListaUsuario.php" method="post">
		<input id="diff" type="submit" value="Cancelar">
	</form>	
	
<?php 
}
?> 

</div>
	
</body>
</html>
