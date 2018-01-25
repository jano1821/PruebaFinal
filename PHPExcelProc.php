<?php
class PHPExcelProc{
  public function efectuarExportacion($archivo){
  require_once 'Classes/PHPExcel.php';
  $objPHPExcel = new PHPExcel();

$linea;
$tamanioTotal=0;
$tamanioEvaluado=0;
$nombre;
$revision;

$file = fopen('./archivos/'.$archivo, "r");

//Informacion del excel
$objPHPExcel->getProperties()
            ->setCreator("ingenieroweb.com.co")
            ->setLastModifiedBy("ingenieroweb.com.co")
            ->setTitle("Exportar excel")
            ->setSubject("Ejemplo 1")
            ->setDescription("Documento generado con PHPExcel")
            ->setKeywords("ingenieroweb.com.co  con  phpexcel")
            ->setCategory("ciudades");

$i=1;
$contador=0;
$marcador="";
while(!feof($file)) {
  $linea = fgets($file);
  if(substr($linea,0,3)=="---" || substr($linea,0,3)=="+++"){
    $linea = substr($linea,3);
    $tamanioTotal = strlen($linea);

    for($e=1;$e<=$tamanioTotal;$e++){
      $contador++;
      if(substr($linea,($e),1)=="/"){
        break;
      }
    }

    $revision = trim(substr($linea,($contador-1),$contador));

    if($revision=="a/" && $marcador==""){
      $marcador="Modificado";
    }
    if ($revision!="a/" && $marcador==""){
      $marcador="Nuevo";
    }

    if ($revision=="b/"){
      $linea = trim(substr($linea,3,$tamanioTotal-$contador));

      $contador=0;

      $tamanioEvaluado = strlen($linea);
      for($e=1;$e<=$tamanioEvaluado;$e++){
        if(substr($linea,($e*-1),1)=="/"){
          break;
        }else{
          $contador++;
        }
      }
      $nombre = substr($linea,($contador*-1),$contador);

      $contador=0;

      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i, $linea);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, $nombre);
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, substr($linea,0,($tamanioEvaluado-strlen($nombre))));
      $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $marcador);
      $marcador="";
      $i++;
    }
    $contador=0;
  }
}

fclose($file);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="ejemplo1.xlsx"');
header('Cache-Control: max-age=0');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');
exit;
  }
};
?>