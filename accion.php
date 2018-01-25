<?php
if (isset($_POST['boton'])) {
	$dir_subida = './archivos/';
	$fichero_subido = $dir_subida . basename($_FILES['imagen']['name']);

	if (move_uploaded_file($_FILES['imagen']['tmp_name'], $fichero_subido)) {
	    include('PHPExcelProc.php');
		$phpExcelProc = new PHPExcelProc;
		$phpExcelProc -> efectuarExportacion($_FILES['imagen']['name']);
	} else {
	    echo "Error al Procesar Archivos\n";
	}



}
?>