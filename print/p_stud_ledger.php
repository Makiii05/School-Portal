<?php
session_start();
require('fpdf.php');
require('../conn.php');
ob_end_clean();
ob_start();

$pdf = new FPDF("P", "mm", array(215,279.95));
$pdf->AddPage();

$student_no = $_POST['student_no'];
$semester_id = (int)($_POST['semester_id'] ?? 1);

// Get student info
$student_info = $conn->query("SELECT s.student_id AS id, s.student_no AS number, s.name, c.name AS course, c.code
    FROM students s
    JOIN courses c ON c.course_id = s.course_id
    WHERE s.student_no = $student_no
");

// Payment Query
$collection = $conn->query("SELECT *
    FROM collections c
    JOIN students stud ON c.student_id = stud.student_id
    JOIN semesters sem ON c.semester_id = sem.semester_id
    WHERE stud.student_no = $student_no
    AND c.semester_id = $semester_id
");

// Subjects Query
$subjects = $conn->query("SELECT
    sub.unit AS unit,
    sub.price_unit AS price
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
date_default_timezone_set("Asia/Manila");
$pdf->Cell(50, 5, "Printed: " . date("m/d/y h:i A"), 0, 1, 'L');

// Logo
$pdf->Image("../images/logo.jpg", 97.5,5,20,);

// Header
$pdf->SetFont('Times', 'B', 18);
$pdf->Cell(195, 13, "", 0, 1, 'C');
$pdf->Cell(195, 6, "LIPA CITY COLLEGES", 0, 1, 'C');

$pdf->SetFont('Times', '', 10);
$pdf->Cell(195, 6, "Tel No:(043) 756-1943 | www.lipacitycolleges.edu.ph", 0, 1, 'C');

// Title
$pdf->SetFont('Times', '', 14);
$pdf->Cell(195, 8, "OFFICIAL GRADE FORM", 1, 1, 'C');

// Student Info
$pdf->SetFont('Arial', '', 10);
while($row = $student_info->fetch_assoc()){
    $pdf->Cell(10, 10, "", 0, 0, 'L');
    $pdf->Cell(60, 10, "Student #:  $row[number]", 0, 0, 'L');
    $pdf->Cell(60, 10, "Name:  $row[name]", 0, 0, 'L');

    $semesters = $conn->query("SELECT * FROM semesters WHERE semester_id=$semester_id");
    while($semester = $semesters->fetch_assoc()){
        $pdf->Cell(60, 10, "Semester:  $semester[code]", 0, 1, 'L');
    }

    $pdf->Cell(10, 10, "", 0, 0, 'L');
    $pdf->Cell(180, 10, "Course:  $row[course]", 0, 1, 'L');
}

// Table Header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(115, 8, "Description", 1, 0, 'C');
$pdf->Cell(40, 8, "Amount", 1, 0, 'C');
$pdf->Cell(40, 8, "Payment", 1, 1, 'C');

// tuition fee
$total_fee = 0;
while($row=$subjects->fetch_assoc()){
$price_unit = $row['unit'] * $row['price'];
$total_fee += $price_unit;
}
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(115, 8, "Total Tuition Fee", 1, 0, 'L');
$pdf->Cell(40, 8, "P$total_fee", 1, 0, 'C');
$pdf->Cell(40, 8, "", 1, 1, 'C');

// payment fee
$total_payment = 0;
while($row=$collection->fetch_assoc()){
if($row["cash"] > 0){
$pdf->Cell(115, 8, "Tuition Fee Payment (Cash)", 1, 0, 'L');
$payment = $row["cash"];
}else{
$pdf->Cell(115, 8, "Tuition Fee Payment (G-cash)", 1, 0, 'L');
$payment = $row["gcash"];
}
$pdf->Cell(40, 8, "", 1, 0, 'C');
$pdf->Cell(40, 8, "P$payment", 1, 1, 'C');
$total_payment += $payment;
}

$remaining_fee = $total_fee - $total_payment;
// remaining fee
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(115, 8, "Remaining Balance", 1, 0, 'L');
$pdf->Cell(40, 8, "", 1, 0, 'C');
$pdf->Cell(40, 8, "P$remaining_fee", 1, 1, 'C');


$pdf->Output();
ob_end_flush();
?>
