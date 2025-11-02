<?PHP
session_start();
require('fpdf.php');
require('../conn.php');
ob_end_clean();
ob_start();

$pdf = new FPDF("P", "mm", array(215,279.95));
$pdf->AddPage();

$sql = "SELECT st.student_id AS id, st.student_no AS studno ,st.name AS name, st.gender AS gender, cr.name AS course_name FROM students st JOIN courses cr ON st.course_id = cr.course_id";
$result = $conn->query($sql);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(180,8, "Student List", 1, 1, 'C');
$pdf->Cell(30,8, "Student #", 1, 0, 'C');
$pdf->Cell(40,8, "Name", 1, 0, 'C');
$pdf->Cell(30,8, "Gender", 1, 0, 'C');
$pdf->Cell(80,8, "Course", 1, 1, 'C');
$pdf->SetFont('Arial', '', 12);
while ($row=$result->fetch_assoc()) {
    $pdf->Cell(30,8, "$row[studno]", 1, 0, '');
    $pdf->Cell(40,8, "$row[name]", 1, 0, '');
    $pdf->Cell(30,8, "$row[gender]", 1, 0, '');
    $pdf->Cell(80,8, "$row[course_name]", 1, 1, '');
}

$pdf->Output();
ob_end_flush();
?>