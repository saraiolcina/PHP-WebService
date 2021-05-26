<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
	<title>WestStore</title>
</script>
</head>

<body>

<div id="content">

<h1>BINVENIDOS A WestStore</h1>

<?php
require("BaseDatos.php");

session_start();

$check="";

/*
Si la sesión existe o si la validación está desactivada, mostramos el contenido.
Por defecto dejamos la opción de validación desactivada. Se muestran los listados de Usuarios y Articulos.
Si se inicia sesión y el usuario es correcto, se le da la bienvenida y la opción de cerrar Sesión con un botón.
*/
if(isset($_SESSION["usuario"])||$check="disable"){  
      
  mostrarContenido();

  if(isset($_SESSION["usuario"])){
  
    echo "<p>Bienvenido ".$_SESSION["usuario"]."</p>"; 
    ?>

    <form method="post">
    <input type="submit" name="buttonCloseSession" value="Cerrar Sesión">
    <?php 
    if(isset($_POST["buttonCloseSession"])){
    session_destroy();
    header("Location: Index.php");
    } 
    ?>
    </form>
    <?php  
  }

}

//Mostramos los botones para activar/desactivar la autenticación.
mostrarInicio();

//Leemos el resultado de la opción seleccionada por el usuario.
if(isset($_POST["check"])){
  $check=$_POST["check"];  

  //Si el usuario elige la opción de activar la autenticación, validamos.
  if($_POST["check"]=='enable'){  

    //Primero cambiamos setup.Autenticacion=1
    activarAutenticacion(1);
      
    //Si la sesión no existe, mostrar formulario de autenticación en la página Validacion.php. 
    if (!isset($_SESSION["usuario"])) {      
      header("Location: Validacion.php");
      exit;      
      ?>    
      <button> <a href="Index.php">Volver a intentarlo</a></button>    
      <?php    
    }
    //Contador para el bloqueo de sesión. 
    $_SESSION['contador']= isset ($_SESSION['contador']) ? $_SESSION['contador']:0;
    session_destroy();
  }

  //Si elige no validar, se muestra el mensaje por pantalla.
  else if($_POST["check"]=='disable'){
    echo "<p>No es necesario autenticación</p>";
  }

}


//Función que muestra los botones del contenido.
function mostrarContenido(){ ?>

  <div id="contentcontainer" >
    <br>
    <br>
    <button> <a href="ListaArticulo.php">Ver Artículos</a></button>
    <br>
    <br>
    <button> <a href="ListaUsuario.php">Ver Usuarios</a></button>
    <br>
    <br>
  </div> 

<?php
}


//Función que muestra los botones para activar/desactivar la autenticación.
function mostrarInicio(){?>

  <div id="enablecontainer">
    <form method="post" action="Index.php">
      <input type="radio" name="check" value="enable">
      <label for="check">Activar autenticación</label>
      <br>
      <input type="radio" name="check" value="disable">
      <label for="check">Desactivar autenticación</label>
      <br>
      <br>
      <input type="submit" value = "Aceptar">
    </form>
  </div>
<?php
}
?>  

</div>

</body>
</html>
