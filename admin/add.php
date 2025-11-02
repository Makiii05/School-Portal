<?PHP
require('../conn.php');
require("../sql/check_admin.php");
require("../sql/messages.php");
?>
<!DOCTYPE html>
<html lang="en">
<?PHP 
require("../components/head.php");
?>
<div class='container w-75 mt-5'>
    <div>
        <form action='../sql/controller.php' method="POST">
        <?PHP if(isset($_POST['createStudent'])){ ?>
        <h1 class="fw-bolder mb-5">CREATE NEW DATA</h1>

                <b>Student Number</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' type='text' name='studno' placeholder='Enter Student Number' required>
                <b>Student Name</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' type='text' name='name' placeholder='Enter Student Name' required>
                <b>Gender</b>
                <select class='form-select mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' name='gender'>
                    <option value='M'>Male</option>
                    <option value='F'>Female</option>
                </select>
                
                <b>Courses</b>
                <select class='form-select mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' name='course_id' required>
                <?PHP 
                $courses = $conn->query("SELECT * FROM courses");
                while($course=$courses->fetch_assoc()){
                    echo "<option value='$course[course_id]'>$course[name]</option>";
                }
                echo "</select>";
                ?>
            <input type="submit" name="add" class="form-control fw-bolder bg-success text-light" value="Save Changes">
            <a href='student.php' type='button' class='form-control bg-dark btn fw-bolder text-light'>Cancel</a> 
        <?PHP }else if(isset($_POST['createCourse'])){ ?>
        <h1 class="fw-bolder mb-5">CREATE NEW DATA</h1>
            <b>Course Code</b>
            <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' type='text' name='code' value='' placeholder="Enter Course Code" required>
            <b>Course Name</b>
            <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' type='text' name='name' value='' placeholder="Enter Course Name" required>
            <input type="submit" name="add" class="form-control fw-bolder bg-success text-light" value="Save Changes">
            <a href='courses.php' type='button' class='form-control bg-dark btn fw-bolder text-light'>Cancel</a>    
        <?PHP }?>
        </form>
        <?php if (isset($_POST['createGrade'])): ?>
            <?php
            $student_id = (int)$_POST['student_id'];
            $semester_id = (int)($_POST['semester_id'] ?? 1);

            if (!empty($_POST['midterm']) || !empty($_POST['fcg'])) {
                $midterm = $_POST['midterm'] ?? 'NULL';
                $fcg = $_POST['fcg'] ?? 'NULL';
                $subject_id = (int)$_POST['subject_id'];
                

                $set = "SET midterm=$midterm, fcg=$fcg";

                if (empty($_POST['fcg'])) {
                    $set = "SET midterm=$midterm";
                }
                if (empty($_POST['midterm'])) {
                    $set = "SET fcg=$fcg";
                }

                $conn->query("UPDATE student_subjects 
                    $set
                    WHERE student_id=$student_id 
                    AND semester_id=$semester_id 
                    AND subject_id=$subject_id");
            }

            $student_info = $conn->query("
                SELECT s.student_id AS id, s.name, c.name AS course, c.code
                FROM students s
                JOIN courses c ON c.course_id = s.course_id
                WHERE s.student_id = $student_id
            ")->fetch_assoc();
            $subjects = $conn->query("
                SELECT sub.code, sub.des, sub.unit, ss.subject_id, ss.midterm, ss.fcg
                FROM student_subjects ss
                JOIN subjects sub ON ss.subject_id = sub.subject_id
                WHERE ss.student_id = $student_id
                AND ss.semester_id = $semester_id
            ");
            ?>

            <h1 class="fw-bolder mb-5">ADD STUDENT GRADE</h1>

            <div class="d-flex gap-3 my-2"><b>Name:</b> <p><?= $student_info['name'] ?></p></div>
            <div class="d-flex gap-3 my-2"><b>Course:</b> <p><?= $student_info['course'] ?></p></div>

            <div class="d-flex gap-3 my-2">
                <b>Semester:</b>
                <form action="add.php" method="POST" id="semester_form">
                    <input type="hidden" name="createGrade">
                    <input type="hidden" name="student_id" value="<?= $student_id ?>">
                    <select name="semester_id" class="form-select" id="semester_select" onchange="this.form.submit()">
                        <?php
                        $semesters = $conn->query("SELECT * FROM semesters");
                        while ($sem = $semesters->fetch_assoc()):
                        ?>
                            <option value="<?= $sem['semester_id'] ?>" <?= $semester_id == $sem['semester_id'] ? 'selected' : '' ?>>
                                <?= $sem['code'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>
            </div>

            <table class="table table-striped table-hover overflow-scroll mt-2">
                <thead class="table-dark">
                    <th>Subject Code</th><th>Subject Name</th><th>Units</th><th>Midterm Grade</th><th>Final Grade</th><th>Action</th>
                </thead>
                <tbody>
                    <?php while ($row = $subjects->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['code'] ?></td>
                            <td><?= $row['des'] ?></td>
                            <td><?= $row['unit'] ?></td>
                            <td><?= $row['midterm'] ?? 'No Grade' ?></td>
                            <td><?= $row['fcg'] ?? 'No Grade' ?></td>
                            <td>
                                <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modal<?= $row['subject_id'] ?>">
                                    Input Grade
                                </button>
                            </td>
                        </tr>

                        <?php require('../components/input_grade_modal.php'); ?>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <form action="../print/p_grade.php" method="POST" target="_blank" class="w-25 d-flex">
                <input type="hidden" name="student_id" value="<?= $student_id ?>">
                <input type="hidden" name="semester_id" value="<?= $semester_id ?>">
                <input type="submit" class="form-control fw-bolder bg-dark text-light" value="Print">
                <a href="grade.php" class="form-control bg-dark btn fw-bolder text-light">Cancel</a>
            </form>
        <?php endif; ?>

    </div>
</div>

<script>
    const semForm = document.getElementById("semester_form");
    const semSelect = document.getElementById("semester_select");
    semSelect.onchange = function () {
        semForm.submit();
    }
</script>