<?php
require('fpdf/tfpdf.php'); // Usar tFPDF en lugar de FPDF
require('php/config.php'); // Incluir la conexión PDO

// Crear una nueva instancia de tFPDF
$pdf = new tFPDF();
$pdf->AddPage();

// Agregar fuentes con soporte Unicode y emojis
$pdf->AddFont('DejaVu','','DejaVuSans.ttf',true);
$pdf->AddFont('DejaVu','B','DejaVuSans-Bold.ttf',true);



// Título del PDF
$pdf->SetFont('Arial','B',14);
$pdf->Cell(190, 10, 'Registro de Actividades', 0, 1, 'C');
$pdf->Ln(5);



// Encabezados de la tabla
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 8, 'ID', 1, 0, 'C');
$pdf->Cell(30, 8, 'Usuario', 1, 0, 'C');
$pdf->Cell(30, 8, 'Curso', 1, 0, 'C');
$pdf->Cell(40, 8, utf8_decode('Acción'), 1, 0, 'C');
$pdf->Cell(50, 8, utf8_decode('Descripción'), 1, 0, 'C');
$pdf->Cell(30, 8, 'Fecha y Hora', 1, 1, 'C');

// Consulta con JOIN
$sql = "SELECT r.id, u.nombre AS usuario, c.nombre AS curso, r.accion, r.descripcion, r.fecha_hora
        FROM registro_actividades r
        JOIN usuarios u ON r.usuario_id = u.id
        JOIN cursos c ON r.curso_id = c.id
        ORDER BY r.fecha_hora DESC, r.id DESC";  
$stmt = $pdo->prepare($sql);
$stmt->execute();
$resultados = $stmt->fetchAll();



// Agregar los registros a la tabla
$pdf->SetFont('DejaVu', '', 8);

foreach ($resultados as $fila) {
    $usuario = mb_convert_encoding($fila['usuario'], 'UTF-8', 'auto');
    $accion = mb_convert_encoding($fila['accion'], 'UTF-8', 'auto');
    $descripcion = mb_convert_encoding($fila['descripcion'], 'UTF-8', 'auto');

    $pdf->Cell(10, 8, $fila['id'], 1, 0, 'C');
    $pdf->Cell(30, 8, $usuario, 1, 0, 'C');
    $pdf->Cell(30, 8, $fila['curso'], 1, 0, 'C');
    $pdf->Cell(40, 8, $accion, 1, 0, 'C');
    $pdf->Cell(50, 8, $descripcion, 1, 0, 'C');
    $pdf->Cell(30, 8, date('d-m-Y H:i', strtotime($fila['fecha_hora'])), 1, 1, 'C');
}

date_default_timezone_set('America/Bogota'); // Configurar la zona horaria correcta
$nombre_pdf = 'Registro_Actividades_' . date('Ymd_His') . '.pdf';
$pdf->Output('I', $nombre_pdf); // 'I' Mostrar el PDF en el navegador sin forzar la descarga
?>
