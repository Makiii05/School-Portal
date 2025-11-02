<?php
session_start();
require('fpdf.php');
require('../conn.php');
ob_end_clean();
ob_start();

$pdf = new FPDF("P", "mm", array(215,279.95));
$pdf->AddPage();

$student_id = (int)$_POST['student_id'];
$semester_id = (int)($_POST['semester_id'] ?? 1);

// Get student info
$student_info = $conn->query("
    SELECT s.student_id AS id, s.student_no AS number, s.name, c.name AS course, c.code
    FROM students s
    JOIN courses c ON c.course_id = s.course_id
    WHERE s.student_id = $student_id
");

// Get subjects and grades
$subjects = $conn->query("
    SELECT sub.code, sub.des, sub.unit, ss.subject_id, ss.midterm, ss.fcg
    FROM student_subjects ss
    JOIN subjects sub ON ss.subject_id = sub.subject_id
    WHERE ss.student_id = $student_id
    AND ss.semester_id = $semester_id
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
$pdf->Cell(10, 8, "#", 1, 0, 'C');
$pdf->Cell(40, 8, "Course Code", 1, 0, 'C');
$pdf->Cell(75, 8, "Course Description", 1, 0, 'C');
$pdf->Cell(15, 8, "Units", 1, 0, 'C');
$pdf->Cell(20, 8, "Midterm", 1, 0, 'C');
$pdf->Cell(35, 8, "Final Grade", 1, 1, 'C');

// Initialize totals
$total_units = 0;
$total_points = 0;
$sub_no = 1;

// Table Body
$pdf->SetFont('Arial', '', 10);
while ($row = $subjects->fetch_assoc()) {
    $midterm = $row['midterm'] ?? "N/A";
    $fcg = $row['fcg'] ?? "N/A";

    // Accumulate totals only if numeric grade exists
    if (is_numeric($fcg)) {
        $total_points += $row['fcg'] * $row['unit'];
    }

    $pdf->Cell(10, 8, $sub_no, 1, 0, 'C');
    $pdf->Cell(40, 8, $row['code'], 1, 0, 'L');
    $pdf->Cell(75, 8, $row['des'], 1, 0, 'L');
    $pdf->Cell(15, 8, $row['unit'], 1, 0, 'C');
    $pdf->Cell(20, 8, $midterm, 1, 0, 'C');
    $pdf->Cell(35, 8, $fcg, 1, 1, 'C');

    $total_units += $row['unit'];
    $sub_no++;
}

// GWA computation
$gwa = ($total_units > 0) ? number_format($total_points / $total_units, 2) : "N/A";

// Summary section
$pdf->Cell(195, 15, "", 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(195, 8, "Total Units: $total_units", 0, 1, 'C');
$pdf->Cell(195, 8, "General Weighted Average (GWA): $gwa", 0, 1, 'C');

$pdf->Output();
ob_end_flush();
?>
