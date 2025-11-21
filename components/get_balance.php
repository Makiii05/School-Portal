<?php
require_once "../conn.php"; // adjust path if needed

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$student_no = $data['student_no'] ?? null;
$semester_id = $data['semester_id'] ?? null;

if (!$student_no || !$semester_id) {
    echo json_encode(["success" => false, "message" => "Missing parameters"]);
    exit;
}


$collection = $conn->query("SELECT *
    FROM collections c
    JOIN students stud ON c.student_id = stud.student_id
    JOIN semesters sem ON c.semester_id = sem.semester_id
    WHERE stud.student_no = $student_no
    AND c.semester_id = $semester_id
");

$subjects = $conn->query("SELECT
    sub.unit AS unit,
    sub.price_unit AS price
    FROM student_subjects ss
    JOIN semesters sem ON ss.semester_id=sem.semester_id
    JOIN subjects sub ON ss.subject_id=sub.subject_id
    JOIN students s ON ss.student_id=s.student_id
    JOIN teachers t ON t.id = sub.teacher_id
    JOIN rooms r ON r.id = sub.room_id
    WHERE s.student_no = $student_no
    AND ss.semester_id=$semester_id
    ");


// tuition fee
$total_fee = 0;
while($row=$subjects->fetch_assoc()){
$price_unit = $row['unit'] * $row['price'];
$total_fee += $price_unit;
}

// payment fee
$total_payment = 0;
while($row=$collection->fetch_assoc()){
if($row["cash"] > 0){
$payment = $row["cash"];
}else{
$payment = $row["gcash"];
}
$total_payment += $payment;
}
$remaining_fee = $total_fee - $total_payment;

echo json_encode([
    "success" => true,
    "balance" => $remaining_fee
]);
?>
