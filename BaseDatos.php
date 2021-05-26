<?php

//Definimos constantes para crear la conexión
const HOST="localhost";
const USER="root";
const PWD= "";
const DATABASE ="pac3_daw";

/*Función para crear la conexión. Usaremos esta función en todas las demás funciones, de forma que no tengamos que
crear la conexión para cada una de ellas cada vez que queramos hacer una consulta.*/
function createConexion(){

    $conn= mysqli_connect(HOST, USER, PWD, DATABASE);
    
    if(!$conn){
        echo "Error de conexión: ".mysqli_error($conn);
    }
        
    return $conn;
}


//ARTICULOS

/*Función para ver la lista de artículos.
Le paso como parámetros las variables de orden y pag que recoja con get en la página ListaArticulo.php*/
function getArticulos($orden, $pag) {

    //Creo una conexión con la función anterior
    $conn=createConexion();
    
    //Asignamos valores por defecto a orden y página
    if($orden==""){
        $orden="ProductID";
    }    
    if($pag==0){
        $pag=1;
    }
        
    //Creo la query
    $min_id=($pag-1)*10;
    $query = "SELECT p.ProductID, p.Name as Nombre, Price, c.Name as Categoria, 
    p.Cost          
    FROM product p
    INNER JOIN 
    category c 
    ON p.CategoryID=c.CategoryID 
    WHERE  ProductID >= ".$min_id."         
    ORDER BY " .$orden ."
    LIMIT 10";
    
    $result = mysqli_query($conn, $query);
    
    //Tratamiento de errores
    if(!$result){
        echo "Error query: " . mysqli_error($conn);
    }    
    
    //Cierro la conexión
    $conn->close();
    
    //Devuelvo la query
    return $result;
}


/*Función para ver el número total de artículos
Usamos esta función para la paginación*/
function getNumArticulos(){

    $conn=createConexion();
    
    $query="SELECT COUNT(ProductID) total FROM product";

    $result=mysqli_query($conn, $query);
    
    if(!$result){
        echo "Error query: ".mysqli_error($conn);
    }
    
    $conn->close();
    
    return $result->fetch_assoc()["total"];
}


/*Función para ver todos los campos de un artículo concreto
Esta función se usa cuando se selecciona la opción modificar en ListaArticulo.php, queremos que se muestren todos los 
valores actuales del artículo antes de modificarlos*/
function getArticulo($id){

    $conn=createConexion();
    
    $query = "SELECT p.ProductID, p.Name as Nombre, p.Price , c.Name as Categoria,
    p.Cost 
    FROM product p
    INNER JOIN
    category c
    ON p.CategoryID=c.CategoryID
    WHERE  ProductID = ".$id; 
    
    $result=mysqli_query($conn, $query);
    
    if($result){
        $row=$result->fetch_assoc();
    }
    else{
        echo "Error query: ".mysqli_error($conn);
    }
    
    $conn->close();
    
    return $row;    
}


/*Función para modificar un artículo en la bd
Una vez el usuario introduce cambios en los campos del artículo, se envía en forma de post para actualizarlos en la bd*/
function modificarArticuloBD($ProductID, $categoryQuery, $Nombre, $Coste, $Precio){
    
    $conn=createConexion();
    
    /*Relacionamos el valor de categoría con el de su id, de forma que se guarden los cambios con los mismo nombres con
    los que se muestran al usuario tanto en la lista principal como en el apartado de modificar*/
    switch ($categoryQuery){
        case "PANTALÓN":
            $Categoria=1;
            break;
        case "CAMISA":
            $Categoria=2;
            break;
        case "JERSEY":
            $Categoria=3;
            break;
        case "CHAQUETA":
            $Categoria=4;
            break;
    }
    
    $query="UPDATE product p
    INNER JOIN category c
    ON p.CategoryID=c.CategoryID
    SET p.Name='".$Nombre."',
     p.CategoryID='".$Categoria."',
     p.ProductID='".$ProductID."',
     p.Cost=".$Coste.",
     p.Price=".$Precio." 
     WHERE p.ProductID=".$ProductID; 
      
    $result=mysqli_query($conn, $query);
    
    if($result){
        echo "<p>Producto modificado</p>";?><br>
        <br>
        <a href="ListaArticulo.php">Volver a lista de artículos</a><br>
    <?php         
    }
    else{
        echo "Error query: " . mysqli_error($conn);
    }
        
    $conn->close();    
}


//Función para borrar un artículo de la base de datos
function borrarArticuloBD(){

    $conn=createConexion();
    
    $query="DELETE FROM product WHERE ProductId=" . $_POST["id"];
    
    $result=mysqli_query($conn, $query);
    
    if($result){
        echo "<p>Artículo eliminado</p>";?><br>
        <br>
        <a href="ListaArticulo.php">Volver a Lista de Artículos</a><br>
        <?php 
    }
    else{
        echo "Error query: ".mysqli_error($conn);
    }
    
    $conn->close();  

}


//Función para crear un artículo en la bd
function crearArticuloBD($categoryQuery, $Nombre, $Coste, $Precio){

    $conn=createConexion();    
   
    /*Relacionamos el valor de categoría con el de su id, de forma que se guarden los cambios con los mismo nombres con
    los que se muestran al usuario tanto en la lista principal como en el apartado de modificar*/
    switch ($categoryQuery){
        case "PANTALÓN":
            $Categoria=1;
            break;
        case "CAMISA":
            $Categoria=2;
            break;
        case "JERSEY":
            $Categoria=3;
            break;
        case "CHAQUETA":
            $Categoria=4;
            break;
    }
    
    $query="INSERT INTO product
    (Name, Price, Cost, CategoryID)  
     VALUES ('".$Nombre."', ".$Precio. ", ".$Coste.", ".$Categoria.");";
    
    $result=mysqli_query($conn, $query);
    
    if($result){
        echo "<p>Artículo creado</>";?><br>
        <a href="ListaArticulo.php">Volver a lista de artículos</a><br>
        
    <?php
    }
    else{
        echo "Error query: ".mysqli_error($conn);
    }
    
    $conn->close();  

}



//USUARIOS

//Función para ver la lista de Usuarios
function getUsuarios($orden,$pag){
    
    $conn=createConexion();

    //Asignamos valores por defecto a orden y página
    if($orden==""){
        $orden="UserID";
    }    
    if($pag==0){
        $pag=1;
    }   
    
    /*Creamos la query
    A la variable LastAccess le pedimos un formato específico*/
    $min_id=($pag-1)*10;
    $dateFormat= '%d/%m/%y';
    $query="SELECT UserID, FullName as Nombre, Email, DATE_FORMAT(LastAccess,'".$dateFormat."') , Enabled 
    FROM user 
    WHERE  UserID >= ".$min_id."
    ORDER BY " .$orden."
    LIMIT 10";    
    
    $result=mysqli_query($conn, $query);
    
    if(!$result){
        echo "Error query: " . mysqli_error($conn);
    }
        
    $conn->close();
    
    return $result;

}


/*Función para ver todos los campos de un usuario concreto
Usamos esta función para modificar un usuario. Se muestran todos los valores actuales que tiene dicho usuario antes de ser modificado*/
function getUsuario($id){

    $conn=createConexion();
    
    //Creamos la query con el formato de la fecha específico
    $dateFormat= '%d/%m/%y';
    $query="SELECT UserID, FullName as Nombre, Email, DATE_FORMAT(LastAccess,'".$dateFormat."') , Enabled 
    FROM user WHERE UserID=".$id;
    
    $result=mysqli_query($conn, $query);
    
    if($result){
        $row=$result->fetch_assoc();
    }
    else{
        echo "Error query: ".mysqli_error($conn);
    }
    
    $conn->close();
    
    return $row;
}


/*Función para ver el número total de usuarios que tengo
Usamos esta función en la paginación*/
function getNumUsuarios(){

    $conn=createConexion();
    
    $query="SELECT COUNT(UserID) total FROM user";
    $result=mysqli_query($conn, $query);
    
    if(!$result){
        echo "Error query: ".mysqli_error($conn);
    }
    
    $conn->close();
    
    return $result->fetch_assoc()["total"];

}


/*Función para modificar un usuario en la bd
Una vez recibimos con post los valores introducidos por el usuario, procedemos a actualizarlos en la bd.
Hemos decidido que solo se pueden modificar los campos de Nombre y Email (aunque se muestren el resto de campos para que el usuario los pueda visualizar) */
function modificarUsuarioBD($id, $Nombre, $Email){
    
    $conn=createConexion();
    
    /*Creamos la query, solo le permitimos cambiar el nombre y correo, ya que el id se genera en autoincrement.
     *El último acceso (lastAccess) lo decidiremos más tarde, una vez el usuario se haya autenticado*/
     $query="UPDATE user
     SET FullName='".$Nombre."',
     Email='".$Email."'
     WHERE UserID=".$id;
        
    $result=mysqli_query($conn, $query);
        
    if($result){
        echo "<p>Usuario modificado</p>";?><br>
        <br>
        <a href="ListaUsuario.php">Volver a lista de usuarios</a><br>
        <?php         
    }
    else{
        echo "Error query: " . mysqli_error($conn);
    }
           
    $conn->close();     
}


//Función para borrar usuario de la bd
function borrarUsuarioBD($id){
 
    $conn=createConexion();
    
    $query="DELETE FROM user WHERE UserId=" .$id;
    
    $result=mysqli_query($conn, $query);
    
    if($result){
        echo "<p>Usuario eliminado</p>";?><br>
        <br>
        <a href="ListaUsuario.php">Volver a Lista de Usuarios</a><br>
        <?php 
    }
    else{
        echo "Error query: ".mysqli_error($conn);
    }
    
    $conn->close();    
        
}

/*Función para crear usuario
A los usuarios nuevos, por defecto les asignamos el valor de lastAccess con la fecha actual (la fecha del día que se registran)
y los habilitamos por defecto, es decir, les damos enabled=1*/
function crearUsuarioBD($Nombre, $Email, $Password){

    $conn=createConexion();
    
    $query="INSERT INTO user
    (FullName, Email, Password, Enabled, LastAccess)
    VALUES ('".$Nombre."', '".$Email."', '".$Password."',1,CURDATE());";
    
    $result=mysqli_query($conn, $query);
    
    if($result){
        echo "<p>Usuario creado</p>";?><br>
        <a href="ListaUsuario.php">Volver a lista de usuarios</a><br>
        
    <?php
    }
    else{
        echo "Error query: ".mysqli_error($conn);
    }
    
    $conn->close(); 

}



//VALIDACION USUARIOS

/*Función para validar usuarios. 
Devuelve true si el usuario se autentica correctamente. Le pasamos como parámetro el email y la contraseña (Si esta última existiera)
Por defecto la función devuelve false, a menos que se cumplan los requisitos fijados una vez tenemos la query realizada
Si no se encuentra ningún resultado, se indica que no se encuentra el resultado.
Si se encuentra el resultado, se pasa a los siguientes filtros:
Si Enabled==0, el usuario no tiene acceso
Si Enabled no es cero y el password coicide, entonces se permite el acceso. SOLO en este caso se devuelve true en la función*/
function checkUsuarioValidado($email, $password) {

    $conn = createConexion();

    $query = "SELECT Password, Enabled FROM user ";
    $query .= "WHERE Email ='" . $email . "' AND Password ='".$password."';";
    
    $result = mysqli_query($conn, $query);

    if ($result) {
        if ($row = $result->fetch_assoc()) {
            if ($row["Enabled"]==0) {        
                echo "<p>Este usuario no puede acceder, no está habilitado</p><br>";
                return false;
            }
            else if ($row["Password"]==$password) {
                return true;
            }          
        }
        else {
            echo "<p>No existe el usuario introducido!</p><br>";
            return false;
        }        
    }
    else {
        echo "Error query:" . mysqli_error($conn);
    }

    $conn->close();

    return false;
}


/*Función para actualizar campo user.lastAccess solo si el usuario está autenticado
Esta función la usamos una vez el usuario se ha autenticado, de forma que la fecha queda actualizada a la fecha actual*/
function actualizarAccesoUsuario($Email){

    $conn=createConexion();    
    
    $query="UPDATE user
    SET LastAccess=CURDATE()
    WHERE Email='".$Email."';";
        
    $result=mysqli_query($conn, $query);
    
    if($result){
        echo "<p>Fecha de último acceso actualizada!</p>";
    }
    else{
        mysqli_error($conn);
    }  

    $conn->close();  

}


/*Función para bloquear al usuario tras tres intentos de acceso fallidos
Desde el fichero de Validacion.php, si el contador llega a 3, se llama a esta función que bloquea el usuario en función del email que recibe*/
function bloquearUsuario($Email){

    $conn=createConexion(); 

    //Condición para el superUsuario, este no puede ser bloqueado
    if($Email=='jack@blue.com'){

        echo "<p>Este usuario no se puede bloquear</p>";

    }
    //Condición para el resto de usuarios
    else{

        $query="UPDATE user
        SET Enabled=0
        WHERE Email='".$Email."';";

        $result=mysqli_query($conn, $query);

        if($result){
            echo "<p>Usuario bloqueado!</p>";
        }
        else{
            mysqli_error($conn);
        }
    }     
    
    $conn->close();    

}


/*Función para activar la autenticación de usuarios
Si desde el Index.php, el usuario selecciona la opción de activar la autenticación, actualizamos la tabla setup*/
function activarAutenticacion($codigo){

    $conn=createConexion();

    if($codigo==0){
        $query="UPDATE setup
        SET Autenticación=0;";
        $result=mysqli_query($conn, $query);
        return false;
    }else{
        $query="UPDATE setup
        SET Autenticación=1;";
        $result=mysqli_query($conn, $query);
        return true;
    }   

    $conn->close();

}
?>




