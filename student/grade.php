<?php
require("../conn.php");
require("../sql/check_student.php");

$student_no = $_SESSION['user_name'];
$semester_id = isset($_POST['semester_id']) ? (int)$_POST['semester_id'] : 1;

// ✅ Update grade if form is submitted
if (!empty($_POST['midterm']) || !empty($_POST['fcg'])) {
    $midterm = $_POST['midterm'] !== '' ? (float)$_POST['midterm'] : 'NULL';
    $fcg = $_POST['fcg'] !== '' ? (float)$_POST['fcg'] : 'NULL';
    $subject_id = (int)$_POST['subject_id'];
    $student_id = (int)$_POST['student_id'];

    $set = [];

    if ($_POST['midterm'] !== '') {
        $set[] = "midterm = $midterm";
    }
    if ($_POST['fcg'] !== '') {
        $set[] = "fcg = $fcg";
    }

    if (!empty($set)) {
        $set_query = implode(', ', $set);
        $conn->query("
            UPDATE student_subjects
            SET $set_query
            WHERE student_id = $student_id
            AND semester_id = $semester_id
            AND subject_id = $subject_id
        ");
    }
}

// ✅ Get student info
$student_info = $conn->query("
    SELECT s.student_id AS id, s.name, c.name AS course, c.code
    FROM students s
    JOIN courses c ON c.course_id = s.course_id
    WHERE s.student_no = '$student_no'
")->fetch_assoc();

$student_id = $student_info['id'];

// ✅ Get subjects (fixed missing AND)
$subjects = $conn->query("
    SELECT sub.code, sub.des, sub.unit, ss.subject_id, ss.midterm, ss.fcg
    FROM student_subjects ss
    JOIN students s ON ss.student_id = s.student_id
    JOIN subjects sub ON ss.subject_id = sub.subject_id
    WHERE s.student_no = '$student_no'
    AND ss.semester_id = $semester_id
");
?>
<!DOCTYPE html>
<html lang="en">
<?php require("../components/head.php"); ?>
<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php require("../components/sidebar.php"); ?>
            <div class="col py-3">
                <div class="d-flex w-100">
                    <h3>View Grades</h3>
                </div>
                <hr>

                <div class="m-4">
                    
                    <div class="d-flex gap-3 my-2"><b>Name:</b> <p><?= htmlspecialchars($student_info['name']) ?></p></div>
                    <div class="d-flex gap-3 my-2"><b>Course:</b> <p><?= htmlspecialchars($student_info['course']) ?></p></div>

                    <div class="d-flex gap-3 my-2">
                        <b>Semester:</b>
                        <form action="grade.php" method="POST" id="semester_form">
                            <input type="hidden" name="createGrade">
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

                    <table class="table table-striped table-hover overflow-scroll mt-2">
                        <thead class="table-dark">
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Units</th>
                                <th>Midterm Grade</th>
                                <th>Final Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $subjects->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['code']) ?></td>
                                    <td><?= htmlspecialchars($row['des']) ?></td>
                                    <td><?= htmlspecialchars($row['unit']) ?></td>
                                    <td><?= $row['midterm'] ?? 'No Grade' ?></td>
                                    <td><?= $row['fcg'] ?? 'No Grade' ?></td>
                                </tr>
                            <?php endwhile; ?>
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
