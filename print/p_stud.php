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

$pdf->SetFont('Arial', 'B', 8);
//date printed
date_default_timezone_set("Asia/Manila");
$pdf->Cell(50, 5, "Printed: " . date("m/d/y h:i A"), 0, 1, 'L');
//logo
$pdf->Image("../images/logo.jpg", 97.5,5,20,);
//header - title
$pdf->SetFont('Times', 'B', 18);
$pdf->Cell(195, 13, "", 0, 1, 'C');
$pdf->Cell(195, 6, "LIPA CITY COLLEGES", 0, 1, 'C');
//header - desc
$pdf->SetFont('Times', '', 10);
$pdf->Cell(195, 6, "Tel No:(043) 756-1943: (043) 756-3768: (028) 520-6201 www.lipacitycolleges.edu.ph", 0, 1, 'C');

//body - title
$pdf->SetFont('Times', '', 14);
$pdf->Cell(195, 8, "OFFICIAL STUDENT LIST", 1, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30,8, "Student #", 1, 0, 'C');
$pdf->Cell(40,8, "Name", 1, 0, 'C');
$pdf->Cell(30,8, "Gender", 1, 0, 'C');
$pdf->Cell(95,8, "Course", 1, 1, 'C');
$pdf->SetFont('Arial', '', 12);
while ($row=$result->fetch_assoc()) {
    $pdf->Cell(30,8, "$row[studno]", 1, 0, '');
    $pdf->Cell(40,8, "$row[name]", 1, 0, '');
    $pdf->Cell(30,8, "$row[gender]", 1, 0, '');
    $pdf->Cell(95,8, "$row[course_name]", 1, 1, '');
}

$pdf->Output();
ob_end_flush();
?>