<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Página Autenticación</title>
</head>

<body>

<div id="content">

<h2>Autenticación de Usuarios</h2>

<p style="color:blue"><b>Introduce tus datos por favor</b></p>

<?php
require ("BaseDatos.php");

//Asignamos por defecto los valores de email y password
$email="";
$password = "";
 
/*En función del valor POST que recibamos de $email, entrearemos en el if o en el else.
Si el email está vacío, mostramos un formulario para que el usuario lo rellene junto con el password, y estos datos se envían nuevamente
a esta página, con lo que ahora, con los datos rellenos, entramos en el else para verificar los datos que nos han proporcionado.*/
if (isset($_POST["Email"]))

    $email = $_POST["Email"];

    if (isset($_POST["Password"]))
        $password = $_POST["Password"];
        
    if ($email=="") {?>    
        <form action="Validacion.php" method="post">
            <label for="Email">Email</label><br>
            <input name="Email" type="text" value="" style="width:250" ><br>
            <label for="Password">Password</label><br>
            <input name="Password" type="password" style="width:250" ><br>
            <br>
            <input type="submit" value="Aceptar" style="margin-top: 5"></input>
        </form>  
    <?php 
    }
else {   
    //Iniciamos la sesión
    session_start(); 

    //Volvemos a usar la variable global para el contador de la sesión (la misma que en la página Index.php)
    $_SESSION['contador']= isset ($_SESSION['contador']) ? $_SESSION['contador']:0; 

    //Llamamos a la función de BaseDatos.php para comprobar la autenticación
    $usuarioValidado = checkUsuarioValidado($email, $password);

    //Si el usuario está validado se siguen los siguientes pasos:
    if ($usuarioValidado) {  

        //Llamar a la función para actualizar lastAccess
        actualizarAccesoUsuario($email);  

        //Establecer la sesion 
        $_SESSION["usuario"]= $email;    

        //Redirigir a index para que tenga acceso al menú de artículos y usuarios        
        header("Location: Index.php");
        exit;        
    } 
    //Si el usuario no está validado, se siguen los siguientes pasos:    
    else{  

        echo "<p>Error en la autentificación</p>";    
        echo "<br>";
        echo "<br>";

        //Aumentamos el contador para el bloqueo de sesión 
        $_SESSION['contador']=$_SESSION['contador']+1;    
        
        //Tras tres intentos fallidos, desactivar el enabled (user.enabled=0), llamando a la función bloquearUsuario()          
        if($_SESSION['contador']>=3){            
            bloquearUsuario($email);
            session_destroy();
        }
        ?>        
        
        <br>
   		<button> <a href="Validacion.php">Volver a intentarlo</a></button>
          
    <?php
    }
}
?>

</div>

</body>
</html>
