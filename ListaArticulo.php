<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="style.css">
	<title>LISTA ARTICULOS</title>
</head>

<body>

<div id="content">

<h2>LISTA DE ARTICULOS</h2>

<!-- Enlace Crear Artículo  -->
<form action="FormArticulo.php" method="post">
	<input name="action" type="hidden" value="create">
	<input id= "diff" type="submit" value="Crear Artículo" style="margin-left: 800px;">
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

$orden="ProductID";
if(isset($_GET["orden"])){
	$orden = $_GET["orden"];
}
echo "<p>Has elegido ordenar por: ".$orden ."</p><br>";	
echo "<br>";
	

//Mostramos una lista de artículos (a través de una función en BaseDatos.php)	
$resultArticulos = getArticulos($orden, $pag);
	
//Si el resultado de la función es correcto, lo mostramos por pantalla.
	if($resultArticulos){?>
	    <table border="1">
			<tr>	<!-- Línea de la cabecera, le daremos un titulo a cada columna, que a su vez serán enlaces a la misma página
					para poder ordenar las variables en función de este título y cambiar de página -->
				<td><a href="ListaArticulo.php?orden=ProductID&pag=<?php echo $pag?>">ID</a></td>
				<td><a href="ListaArticulo.php?orden=Categoria&pag=<?php echo $pag?>">Categoría</a></td>
				<td><a href="ListaArticulo.php?orden=Nombre&pag=<?php echo $pag?>">Nombre</a></td>
				<td><a href="ListaArticulo.php?orden=Cost&pag=<?php echo $pag?>">Coste</a></td>
				<td><a href="ListaArticulo.php?orden=Price&pag=<?php echo $pag?>">Precio</a></td>
				<td></td>
				<td></td>	    
			</tr>
			
			<?php 
			while($row = $resultArticulos->fetch_assoc()){?>
				 <tr> <!--Rellenamos cada fila de la tabla con el resultado obtenido en la query.
						Creamos dos columnas con los enlaces de Modificar y Eliminar para cada registro.
						Estos enlaces tienen un valor que usaremos en FormArticulo.php para cada acción.-->
					<td><?php echo $row["ProductID"]?></td>
					<td><?php echo $row["Categoria"]?></td>
					<td><?php echo $row["Nombre"]?></td>
					<td><?php echo number_format($row["Cost"],0)." €"?></td>
					<td><?php echo number_format($row["Price"],0)." €"?></td>
					<td>
						<form action="FormArticulo.php" method="post">
							<input name="id" type="hidden" value="<?php echo $row["ProductID"]?>">
							<input name="action" type="hidden" value="modify">
							<input type="submit" value="Modificar">	 	       		
						</form>
					</td>
					<td>
						<form action="FormArticulo.php" method="post">
							<input name="id" type="hidden" value="<?php echo $row["ProductID"]?>">
							<input name="action" type="hidden" value="delete">
							<input type="submit" value="Eliminar">	       		
						</form>
					</td>
				</tr>
			<?php 
			}?>	    
	    </table>
	<?php 
	}
	

//Paginación
?>
<div id="pag"><?php
	$numPaginas=ceil(getNumArticulos()/10);
	echo "<br>"."Número de páginas: ".$numPaginas;
	?>	
	<br>
	<table >
		<tr>
			<td> 
			<?php 
			for($i=1;$i<=$numPaginas;$i++){?>
				<a  href="ListaArticulo.php?pag=<?php echo $i?>&orden=<?php echo $orden?>"> <?php echo $i?></a>
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