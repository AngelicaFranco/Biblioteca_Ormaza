<?php
    session_start();

    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {

    } else {
    header ("Location: ../db/control.php");

    exit;
    }

    $now = time();

    if($now > $_SESSION['expire']) {
    session_destroy();

    header ("Location: ../index.php");
    exit;
    }
?>
<?php
	
	require_once('../fpdf/fpdf.php');
	include ("../db/conexion.php");

	class PDF extends FPDF
	{
		
		function AcceptPageBreak()
		{

			$this->AddPage();
			$this->SetFillColor(232,232,232);
			$this->SetFont('Arial','B',12);
			$this->SetX(10);
			$this->Cell(10,6,'#',1,0,'C',1);
			$this->SetX(20);
			$this->Cell(30,6,'DOCUMENTO',1,0,'C',1);
			$this->SetX(50);
			$this->Cell(40,6,'NOMBRES',1,0,'C',1);
			$this->SetX(90);
			$this->Cell(50,6,'APELLIDOS',1,0,'C',1);
			$this->SetX(140);
			$this->Cell(50,6,'TELEFONO',1,0,'C',1);
			$this->SetX(190);
			$this->Cell(50,6,'DIRECCION',1,0,'C',1);
			$this->Ln();

		}

		// Se heredan todas las funciones para hacer el Encabezado de la página
		function Header()
		{
			// Logo
			$this->Image('../assets/img/logo.jpg',10,8,28);
			// Arial bold 15
			$this->SetFont('Arial','B',20);
			// Movernos a la derecha (espacio)
			$this->Cell(70);
			// Título (PROPIEDAD DESPUÉS DEL TITULO ES CONTORNO, SALTO DE LÍNEA, ALINEACIÓN)
			$this->Cell(130,20,'INFORME DE ESTUDIANTES',0,0,'C');
			// Salto de línea
			$this->Ln(30);
		}
		
		// Se crea la función FOOTER "Pie de página"
		function Footer()
		{
			// Posición: a 1,5 cm del final
			$this->SetY(-15);
			// Arial italic 8
			$this->SetFont('Arial','I',8);
			// Número de página
			$this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
			
			//Posición en y
			$this->SetY(-20);
			//Fuente
			$this->SetFont('Arial','I',8);
			//Fecha
			date_default_timezone_set('America/Bogota');
			$this->Cell(0,10,date('d/m/Y g:i:s a'),0,0,'C');

			//$this->Cell(0,10,time(),0,0,'C');
		}
	}

	$mostrar = 
	"
		SELECT * FROM estudiante;
	";
	
	$res = $conexion->query($mostrar);
	
	//Limpiar (eliminar) y deshabilitar los búferes de salida.
	ob_end_clean();
	$pdf = new PDF('L','mm','A4');
	$pdf->AliasNbPages();
	$pdf->Addpage();
	$pdf->SetAuthor('Sistema de Biblioteca');
	$pdf->SetCreator('Institución Educativa Jesús María Ormaza');
	$pdf->SetTitle('Informe de Estudiantes v1.0');

	$pdf->SetFillColor(84,89,247);
	$pdf->SetFont('Arial','B',12);
	$pdf->SetX(10);
	$pdf->Cell(10,6,'#',1,0,'C',1);
	$pdf->SetX(20);
	$pdf->Cell(30,6,'DOCUMENTO',1,0,'C',1);
	$pdf->SetX(50);
	$pdf->Cell(40,6,'NOMBRES',1,0,'C',1);
	$pdf->SetX(90);
	$pdf->Cell(50,6,'APELLIDOS',1,0,'C',1);
	$pdf->SetX(140);
	$pdf->Cell(40,6,'TELEFONO',1,0,'C',1);
	$pdf->SetX(180);
	$pdf->Cell(90,6,'DIRECCION',1,0,'C',1);
	$pdf->Ln();	

	while ($fila = $res->fetch_assoc())
	{
		$pdf->SetFont('Arial','',12);
		$pdf->SetX(10);
		$pdf->Cell(10,6,$fila['id'],1,0,'C');
		$pdf->SetX(20);
		$pdf->Cell(30,6,$fila['doc_identidad_estudiante'],1,0,'C');
		$pdf->SetX(50);
		$pdf->Cell(40,6,utf8_decode($fila['nombre_estudiante']),1,0,'C');
		$pdf->SetX(90);
		$pdf->Cell(50,6,utf8_decode($fila['apellido_estudiante']),1,0,'C');
		$pdf->SetX(140);
		$pdf->Cell(40,6,$fila['telefono_estudiante'],1,0,'C');
		$pdf->SetX(180);
		$pdf->Cell(90,6,utf8_decode($fila['direccion_estudiante']),1,1,'C');
	}
	
	/*for($i=1;$i<=100;$i++)
    $pdf->Cell(0,10,'Imprimiendo linea número '.$i,0,1);*/
	$pdf->Output();
?>