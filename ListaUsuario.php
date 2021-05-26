<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
	<title>LISTA USUARIO</title>
</head>

<body>

<div id="content">

<h2>LISTA DE USUARIOS</h2>

<!-- Enlace Crear Usuario  -->
<br>
<form action="FormUsuario.php" method="post">
	<input name="action" type="hidden" value="create">
	<input id="diff" type="submit" value="Crear Usuario" style="margin-left: 800px;">
</form>	


<?php
// Cargamos el fichero de la bd, que usaremos para llamar a las funciones de este archivo
require ("BaseDatos.php");

//Asignamos valores  a la variable orden y pag 	
$pag=1;
if(isset($_GET["pag"])){
	$pag = $_GET["pag"];
}
echo "<p>Estás en la página: ".$pag ."</p><br>";	

$orden="UserID";
if(isset($_GET["orden"])){
	$orden = $_GET["orden"];
}
echo "<p>Has elegido ordenar por: ".$orden ."</p><br>";	
echo "<br>";

	
//Mostramos una lista de usuarios (a través de una función en BaseDatos.php)
$resultUsuarios =getUsuarios($orden,$pag);

//Si el resultado de la función es correcto, lo mostramos por pantalla.
	if($resultUsuarios){?>	
		
		<table border="1">
			<tr>	<!-- Línea de la cabecera, le daremos un titulo a cada columna, que a su vez serán enlaces a la misma página
					para poder ordenar las variables en función de este título y cambiar de página -->
				<td><a href="ListaUsuario.php?pag=<?php echo $pag?>&orden=UserID">ID</a></td>
				<td><a href="ListaUsuario.php?pag=<?php echo $pag?>&orden=Nombre">Nombre</a></td>
				<td><a href="ListaUsuario.php?pag=<?php echo $pag?>&orden=Email">Email</a></td>
				<td><a href="ListaUsuario.php?pag=<?php echo $pag?>&orden=LastAccess">Último acceso</a></td>
				<td><a href="ListaUsuario.php?pag=<?php echo $pag?>&orden=Enabled">Enabled</a></td>
				<td></td>
				<td></td>	    
			</tr>
			
			<?php 
			$dateFormat= '%d/%m/%y';	    
			while($row = $resultUsuarios->fetch_assoc()){?>				  					    
				<tr><!--Rellenamos cada fila de la tabla con el resultado obtenido en la query.
						Creamos dos columnas con los enlaces de Modificar y Eliminar para cada registro.
						Estos enlaces tienen un valor que usaremos en FormUsuario.php para cada acción.-->	 

				<!-- Definimos la condición de estilo para el usuario superadmin -->
				<?php if ($row["UserID"]==3){
						echo '<td style=background-color:pink>'.$row["UserID"];
						echo '<td style=background-color:pink>'.$row["Nombre"];
						echo '<td style=background-color:pink>'.$row["Email"];
						echo '<td style=background-color:pink>'.$row["DATE_FORMAT(LastAccess,'".$dateFormat."')"];
						echo '<td style=background-color:pink>'.$row["Enabled"];
				}
				else{?>
				<!-- Condición para el resto de usuarios -->
					<td><?php echo $row["UserID"]?></td>	        	
					<td><?php echo $row["Nombre"]?></td>
					<td><?php echo $row["Email"]?></td>
					<td><?php echo $row["DATE_FORMAT(LastAccess,'".$dateFormat."')"]?></td>
					<td><?php echo $row["Enabled"]?></td>	        	
				<?php 
				}?>						
					<td>
						<form action="FormUsuario.php" method="post">
							<input name="id" type="hidden" value="<?php echo $row["UserID"]?>">
							<input name="action" type="hidden" value="modify">
							<input type="submit" value="Modificar">	        		
						</form>
					</td>
					<td>
						<form action="FormUsuario.php" method="post">
							<input name="id" type="hidden" value="<?php echo $row["UserID"]?>">
							<input name="action" type="hidden" value="delete">
							<input type="submit" value="Eliminar">	        		
						</form>
					</td>				
				</tr>
			<?php 
			}?>	    
		</table>
	<?php 
	}?>	
	
	
<!-- Paginación. En caso de que se crearan más usuarios, solo queremos que se muestren 10 por página -->
<div id="pag">
	<?php	
	$numPaginas=ceil(getNumUsuarios()/10);
	echo "<br>". "Número de páginas: ".$numPaginas;
	?>
	<br>
	<table>
		<tr>
			<td> 
			<?php 
			for($i=1;$i<=$numPaginas;$i++){?>
				<a id="pag" href="ListaUsuario.php?pag=<?php echo $i?>&orden=<?php echo $orden?>"> <?php echo $i?></a>
				&nbsp;
			<?php }
			?>
			</td>
		</tr>
	</table>
</div>	
	
<!-- Enlace Para volver a página principal -->
<br>
<form action="Index.php" method="post">
	<input id="diff2" type="submit" value="Volver a página inicial">
</form>	

</div>

</body>
</html>
