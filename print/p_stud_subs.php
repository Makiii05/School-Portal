<?PHP
session_start();
require('fpdf.php');
require('../conn.php');
ob_end_clean();
ob_start();

$pdf = new FPDF("P", "mm", array(215,279.95));
$pdf->AddPage();

$student_info = $conn->query("SELECT 
    s.student_id AS id,
    s.student_no AS number,
    s.name AS name,
    c.name AS course,
    c.code AS code
    FROM students s
    JOIN courses c ON c.course_id=s.course_id
    WHERE student_no=$_POST[student_no]
    ");
$subjects = $conn->query("SELECT
    sub.code AS code,
    sub.des AS des,
    sub.days AS day,
    sub.time AS time,
    r.name AS room,
    t.name AS teacher,
    sub.unit AS unit,
    sub.price_unit AS price,
    ss.semester_id AS semester
    FROM student_subjects ss
    JOIN semesters sem ON ss.semester_id=sem.semester_id
    JOIN subjects sub ON ss.subject_id=sub.subject_id
    JOIN students s ON ss.student_id=s.student_id
    JOIN teachers t ON t.id = sub.teacher_id
    JOIN rooms r ON r.id = sub.room_id
    WHERE s.student_no = $_POST[student_no]
    AND ss.semester_id=$_POST[semester_id]
    ");

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
$pdf->Cell(195, 8, "OFFICIAL ENROLLMENT AND ASSESSMENT FORM", 1, 1, 'C');
//body - desc
$pdf->SetFont('Arial', '', 10);
while($row=$student_info->fetch_assoc()){
    $pdf->Cell(10, 10, "", 0, 0, 'L');
    $pdf->Cell(60, 10, "Student #:  $row[number]", 0, 0, 'L');
    $pdf->Cell(60, 10, "Name:  $row[name]", 0, 0, 'L');
    $semesters = $conn->query("SELECT * FROM semesters WHERE semester_id=$_POST[semester_id]");
    while($semester=$semesters->fetch_assoc()){
    $pdf->Cell(60, 10, "Semester:  $semester[code]", 0, 1, 'L');
    }
    $pdf->Cell(10, 10, "", 0, 0, 'L');
    $pdf->Cell(180, 10, "Course:  $row[course]", 0, 1, 'L');
}
$pdf->SetFont('Arial', '', 9);
$sub_no = 1;
$total_unit = 0;
$total_price = 0;
$pdf->Cell(10, 8, "No.", 1,0, 'C');
$pdf->Cell(30, 8, "Code", 1,0, 'C');
$pdf->Cell(50, 8, "Description", 1,0, 'C');
$pdf->Cell(15, 8, "Days", 1,0, 'C');
$pdf->Cell(25, 8, "Time", 1,0, 'C');
$pdf->Cell(20, 8, "Room", 1,0, 'C');
$pdf->Cell(15, 8, "Unit Price", 1,0, 'C');
$pdf->Cell(15, 8, "Unit", 1,0, 'C');
$pdf->Cell(15, 8, "Total", 1,1, 'C');
while($row=$subjects->fetch_assoc()){
$pdf->Cell(10, 8, "$sub_no", 1,0, 'R');
$pdf->Cell(30, 8, "$row[code]", 1,0, 'L');
$pdf->Cell(50, 8, "$row[des]", 1,0, 'L');
$pdf->Cell(15, 8, "$row[day]", 1,0, 'C');
$pdf->Cell(25, 8, "$row[time]", 1,0, 'C');
$pdf->Cell(20, 8, "$row[room]", 1,0, 'C');
$pdf->Cell(15, 8, "$row[price]", 1,0, 'C');
$pdf->Cell(15, 8, "$row[unit]", 1,0, 'C');
$price_unit = $row['unit'] * $row['price'];
$pdf->Cell(15, 8, "P$price_unit", 1,1, 'C');
$total_unit += $row['unit'];
$total_price += $row['price'];
$sub_no++;
}
$pdf->Cell(165, 8, "", 0,0, 'C');
$pdf->Cell(15, 8, "$total_unit", 0,0, 'C');
$pdf->Cell(15, 8, "P$total_price", 0,1, 'C');

$pdf->Output();
ob_end_flush();
?>