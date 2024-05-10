<?php 

require('../librerias/fpdf/fpdf.php');

include_once '../configuraciones/bd.php';
$conexionBD=BD::crearInstancia();


function agregarTexto($pdf,$texto,$x,$y,$align='L',$fuente,$size=10,$r=0,$g=0,$b=0){

$pdf->SetFont($fuente,"",$size);
$pdf->SetXY($x,$y);
$pdf->SetTextColor($r,$g,$b);
$pdf->Cell(0,10,$texto,0,0,$align);
// $pdf->Text($x,$y,$texto);

}

function agregarImagen($pdf,$imagen,$x,$y){
    $pdf->Image($imagen,$x,$y,0);


}




$idcurso=isset($_GET['idcurso'])?$_GET['idcurso']:'';
$idalumno=isset($_GET['idalumno'])?$_GET['idalumno']:'';

$sql="SELECT alumnos.nombre, alumnos.apellidos,cursos.nombre_curso
FROM alumnos, cursos WHERE alumnos.id=:idalumno AND cursos.id=:idcurso";
$consulta=$conexionBD->prepare($sql);
$consulta->bindParam(':idalumno',$idalumno);
$consulta->bindParam(':idcurso',$idcurso);
$consulta->execute();
$alumno=$consulta->fetch(PDO::FETCH_ASSOC);


$pdf=new FPDF("L","mm",array(254,194));
$pdf->AddPage();
$pdf->setFont("Arial","B",16);
agregarImagen($pdf,"../src/certificado_.jpg",0,0);
agregarTexto ($pdf,ucwords(utf8_decode($alumno['nombre']." ".$alumno['apellidos'])),60,70,'C',"Helvetica",30,0,255,255);
agregarTexto ($pdf,$alumno['nombre_curso'],-250,100,'C',"Helvetica",20,0,255,255);
agregarTexto ($pdf,date("d/m/Y"),-300,155,'C',"Helvetica",11,0,255,255); 

$pdf->Output();

?>