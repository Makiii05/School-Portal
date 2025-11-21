<?php
require("../conn.php");
require("../sql/check_student.php");

$student_no = $_SESSION['user_name'];
$semester_id = isset($_POST['semester_id']) ? (int)$_POST['semester_id'] : 1;

// ✅ Get student info
$student_info = $conn->query("
    SELECT s.student_id AS id, s.student_no AS student_no, s.name, c.name AS course, c.code
    FROM students s
    JOIN courses c ON c.course_id = s.course_id
    WHERE s.student_no = '$student_no'
")->fetch_assoc();

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
    WHERE s.student_no = $student_no
    AND ss.semester_id=$semester_id
    ");

?>
<!DOCTYPE html>
<html lang="en">
<?php require("../components/head.php"); ?>
<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php require("../components/sidebar.php"); ?>
            <div class="col py-3 d-flex flex-column ">
                <div class="d-flex w-100">
                    <h3>View Balance</h3>
                </div>
                <hr>

                <div class="m-4 w-75 align-self-center">
                    
                    <div class="d-flex gap-3 my-2"><b>Student#:</b> <p><?= htmlspecialchars($student_info['student_no']) ?></p></div>
                    <div class="d-flex gap-3 my-2"><b>Name:</b> <p><?= htmlspecialchars($student_info['name']) ?></p></div>
                    <div class="d-flex gap-3 my-2"><b>Course:</b> <p><?= htmlspecialchars($student_info['course']) ?></p></div>

                    <div class="d-flex gap-3 my-2">
                        <b>Semester:</b>
                        <form action="ledger.php" method="POST" id="semester_form">
                            <input type="hidden" name="student_id" value="<?= $student_id ?>">
                            <select autofocus name="semester_id" class="form-select" id="semester_select" onchange="this.form.submit()">
                                <?php
                                $semesters = $conn->query("
                                    SELECT sem.semester_id, sem.code
                                    FROM semesters sem
                                    RIGHT JOIN student_subjects ss ON ss.semester_id = sem.semester_id
                                    JOIN students s ON ss.student_id = s.student_id
                                    WHERE s.student_no = '$student_no'
                                    GROUP BY sem.code
                                ");
                                while ($sem = $semesters->fetch_assoc()):
                                ?>
                                    <option value="<?= $sem['semester_id'] ?>" <?= $semester_id == $sem['semester_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($sem['code']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </form>
                    </div>
                    <?php
                    $total_fee = 0;
                    $subjects_data = []; // store subject info for table
                    while ($row = $subjects->fetch_assoc()) {
                        $price_unit = $row['unit'] * $row['price'];
                        $total_fee += $price_unit;
                        $subjects_data[] = [
                            'description' => "Tuition Fee for {$row['unit']} unit(s)",
                            'amount' => "P" . number_format($price_unit, 2),
                            'payment' => "" // we'll fill later from collection
                        ];
                    }

                    // Get total payments
                    $total_payment = 0;
                    $payment_rows = [];
                    while ($row = $collection->fetch_assoc()) {
                        if ($row["cash"] > 0) {
                            $payment = $row["cash"];
                            $desc = "Tuition Fee Payment (Cash)";
                        } else {
                            $payment = $row["gcash"];
                            $desc = "Tuition Fee Payment (G-cash)";
                        }
                        $total_payment += $payment;
                        $payment_rows[] = ['description' => $desc, 'amount' => "", 'payment' => "P" . number_format($payment, 2)];
                    }

                    $remaining_fee = $total_fee - $total_payment;
                    ?>

                    <table class="table table-striped table-hover mt-2">
                        <thead class="table-dark">
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Tuition fee -->
                            <tr>
                                <td>Total Tuition Fee</td>
                                <td>P<?= number_format($total_fee, 2) ?></td>
                                <td></td>
                            </tr>

                            <!-- Payments -->
                            <?php foreach ($payment_rows as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['description']) ?></td>
                                <td><?= $p['amount'] ?></td>
                                <td><?= $p['payment'] ?></td>
                            </tr>
                            <?php endforeach; ?>

                            <!-- Remaining Balance -->
                            <tr class="fw-bold">
                                <td>Remaining Balance</td>
                                <td></td>
                                <td>P<?= number_format($remaining_fee, 2) ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
const semForm = document.getElementById("semester_form");
const semSelect = document.getElementById("semester_select");
semSelect.onchange = function() {
    semForm.submit();
};
</script>
