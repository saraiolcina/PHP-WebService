<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
	<title>FORMULARIO ARTICULO</title>
</head>

<body>

<div id="content">

<h2>MODIFICAR/ELIMINAR/CREAR ARTICULOS</h2>


<?php
require ("BaseDatos.php");

//Controlamos que los valores de acción estén bien definidos 
if(!isset($_POST["action"])){
    echo "Ha ocurrido un error";?> <br>
    <a href="ListaArticulo.php">Volver a lista de Artículos</a>
    <?php exit();
}

//En función de la acción seleccionada, se llamará a unas funciones u otras.    
switch ($_POST["action"]){
	case "modify":
		modificarArticulo();
		break;
	case "delete":
		borrarArticulo();
		break;
	case "modifyBD":
		modificarArticuloBD($_POST["ProductID"], $_POST["Categoria"],$_POST["Nombre"],
			$_POST["Cost"],$_POST["Price"]);
		break;
	case "deleteBD":
		borrarArticuloBD();
		break;
	case "create":
		crearArticulo();
		break;
	case "createBD":
		crearArticuloBD($_POST["Categoria"],$_POST["Nombre"],
		$_POST["Cost"],$_POST["Price"]);
		break;
}
    
    
//Función de modificar artículo en formulario
function modificarArticulo(){
    //obtenemos todos los campos del articulo seleccionado y los mostramos por defecto
    $articulo = getArticulo($_POST["id"]); 
    
    //Sacamos en esta página los valores sacados en la función anterior?>
    	<h3>Artículo a modificar</h3> 

		<form action= "FormArticulo.php" method="post">
    		<input type="hidden" name="action" value="modifyBD">
    		<input type="hidden" name="id" value="<?php echo $articulo["ProductID"]?>">
    		
    		<label for="ProductID">ID</label>
    		<input type="text" name="ProductID" style="margin-left: 55px;" value="<?php echo $articulo["ProductID"]?>"><br>
    		
    		<label for="Categoria">Categoria</label>
    		<input type="text" name="Categoria" style="margin-left: 10px;" value="<?php echo $articulo["Categoria"]?>"><br>
    		
    		<label for="Nombre">Nombre</label>
    		<input type="text" name="Nombre" style="margin-left: 20px;" value="<?php echo $articulo["Nombre"]?>"><br>
    		
    		<label for="Cost">Coste</label>
    		<input type="text" name="Cost" style="margin-left: 35px;" value="<?php echo number_format($articulo["Cost"])?>"><br>
    		
    		<label for="Price">Precio</label>
    		<input type="text" name="Price" style="margin-left: 32px;" value="<?php echo number_format($articulo["Price"])?>"><br>
			
    		<br>
    		<!-- Botón aceptar -->
    		<input id="diff" type="submit" value = "Guardar">
			<br>
			<br>
    	</form>    	
    	
    	<!-- Para el botón de cancelar, creamos otro formulario que redirija a la página del listado de artículos-->
		<form action="ListaArticulo.php" method="post">
			<input id="diff" type="submit" value="Cancelar">
		</form>
<?php 
}


//Función para borrar artículo
function borrarArticulo(){?>

	<h3>¿Deseas eliminar el Artículo?</h3>
	<br>

 	<form action="FormArticulo.php" method="post">
 		<input type="hidden" name="action" value="deleteBD">
 		<input type="hidden" name="id" value="<?php echo $_POST["id"]?>">
 		<input id="diff" type="submit" value="Confirmar"> 	
 	</form>
 	<br>
 	<form action="ListaArticulo.php" method="post">
 		<input id="diff" type="submit" value="Cancelar">
 	</form>
<?php 
}


//Función para crear artículo
function crearArticulo(){?>

	<h3>Crear artículo</h3>
	
	<form action= "FormArticulo.php" method="post">
		<input type="hidden" name="action" value="createBD">
		
		<label for="Categoria">Categoria</label>
		<input type="text" name="Categoria" style="margin-left: 10px;" value=""><br>
		
		<label for="Nombre">Nombre</label>
		<input type="text" name="Nombre" style="margin-left: 20px;"  value=""><br>
		
		<label for="Cost">Coste</label>
		<input type="text" name="Cost" style="margin-left: 35px;" value=""><br>
		
		<label for="Price">Precio</label>
		<input type="text" name="Price" style="margin-left: 32px;" value=""><br>
		
		<br>
		<br>
		<!-- Botón aceptar -->
		<input id="diff" type="submit" value = "Guardar">
		<br>
		<br>
	</form>    	
	
	<!-- Para el botón de cancelar, creamos otro formulario que redirija a la página del listado de artículos-->
	<form action="ListaArticulo.php" method="post">
		<input id="diff" type="submit" value="Cancelar">
	</form>	

	<br>
	<br>
	<!--Para que el articulo se cree correctamente es necesario que se incluya el nombre de la categoría de forma correcta-->
	<p style="color:blue">Es importante que el nombre de la categoría sea una de las siguientes opciones:<br>
	CAMISA, JERSEY, CHAQUETA, PANTALÓN</p>

<?php 
}
?>  

</div>

</body>
</html>