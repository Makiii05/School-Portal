<?PHP
require("../conn.php");
require("../sql/check_teacher.php");

$semester_id = $_POST['semester_id'] ?? "";
$subject_id = $_POST['subject_id'] ?? "";
$period = $_POST['period'] ?? "";

$from_sem = !empty($semester_id) ? $conn->query("SELECT code FROM semesters WHERE semester_id = '$semester_id'")->fetch_assoc()["code"] : '...';
$from_sub = !empty($subject_id) ? $conn->query("SELECT code FROM subjects WHERE subject_id = '$subject_id'")->fetch_assoc()["code"] : '...';
$from_per = !empty($period) ? ($period == "fcg" ? 'Final Course Grade' : 'Midterm Grade') : '...';

$teacher_id = $conn->query("SELECT * FROM teachers WHERE teacher_code = '$_SESSION[user_code]'")->fetch_assoc()["id"];
$semesters = $conn->query("SELECT DISTINCT sem.code as code, sem.semester_id as semester_id  FROM semesters sem JOIN `student_subjects` ss ON ss.semester_id = sem.semester_id JOIN subjects sub ON sub.subject_id = ss.subject_id WHERE sub.teacher_id = '$teacher_id'");
$subjects = $conn->query("SELECT * FROM subjects WHERE teacher_id = '$teacher_id'");

$gradeOptions = [
    '1.00', '1.25', '1.50', '1.75', 
    '2.00', '2.25', '2.50', '2.75', 
    '3.00', '3.25', '3.50', '3.70', 
    '4.00', '5.00'
];
$f_students = null;
$m_students = null;

if (isset($_POST['submit_grades']) && !empty($period)) {
    
    $column = ($period == 'midterm') ? 'midterm' : 'fcg';
    
    foreach ($_POST['grades'] as $grade_id => $grade_value) {
        $value = $grade_value;

        $valToStore = ($value == 'Null') ? "NULL" : "'" . $conn->real_escape_string($value) . "'";

        $sql = "UPDATE student_subjects SET $column = $valToStore WHERE id = '$grade_id'";
        $conn->query($sql);
    }
}


if (!empty($semester_id) && !empty($subject_id)) {
    
    $base_query = "SELECT 
        s.student_id AS id,
        s.student_no AS studno,
        s.name AS name,
        s.gender AS gender,
        ss.id AS grade_id, 
        ss.midterm, 
        ss.fcg
    FROM students s
    JOIN student_subjects ss ON s.student_id = ss.student_id
    WHERE ss.semester_id = '$semester_id' 
    AND ss.subject_id = '$subject_id'
    AND s.gender = ";
    
    $f_students = $conn->query($base_query . "'F' ORDER BY s.name ASC");
    
    $m_students = $conn->query($base_query . "'M' ORDER BY s.name ASC");
}

?>
<!DOCTYPE html>
<html lang="en">
<?PHP 
require("../components/head.php");
?>
<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?PHP
            require("../components/sidebar.php")
            ?>
            <div class="col py-3">
                
                <div class="d-flex w-100">
                <h3>Add Grade</h3>
                </div>
                <hr>
                
                <div class="d-flex gap-3 m-4 row">
                    
                    <div class="col border bg-light p-2 text-center rounded" style="max-height: 270px;">
                        <h4>Select Semester</h4>
                        <div class="overflow-y-scroll" style="max-height: 80%;">
                            <?PHP if(mysqli_num_rows($semesters) == 0): ?>
                                <small class="text-secondary">Teacher's has no assigned semester.</small>
                            <?PHP else: ?>                                
                                <?PHP while ($semester = $semesters->fetch_assoc()): ?>
                                    <?PHP 
                                        $isActive = (!empty($semester_id) && $semester_id == $semester['semester_id']) ? 'bg-primary text-white' : 'bg-white';
                                    ?>
                                    <div onclick="selectSemester(<?PHP echo $semester['semester_id']; ?>, '<?PHP echo $semester['code']; ?>', this)" 
                                        class="border p-2 text-center rounded mb-2 sem-item cursor-pointer <?PHP echo $isActive; ?>" style="cursor:pointer;">
                                        <b><?PHP echo $semester['code']; ?></b><br>
                                        <small><?PHP echo $semester['code']; ?></small>
                                    </div>
                                <?PHP endwhile; ?>
                            <?PHP endif; ?>

                        </div>
                    </div>

                    <div class="col border bg-light p-2 text-center rounded" style="max-height: 270px;">
                        <h4>Select Subject</h4>
                        <div class="overflow-y-scroll" style="max-height: 80%;">
                            <?PHP if(mysqli_num_rows($semesters) == 0): ?>
                                <small class="text-secondary">Teacher's has no assigned subject.</small>
                            <?PHP else: ?>                                
                                <?PHP while ($subject = $subjects->fetch_assoc()): ?>
                                    <?PHP 
                                        $isActive = (!empty($subject_id) && $subject_id == $subject['subject_id']) ? 'bg-primary text-white' : 'bg-white';
                                    ?>
                                    <div onclick="selectSubject(<?PHP echo $subject['subject_id']; ?>, '<?PHP echo $subject['code']; ?>', this)" 
                                        class="border p-2 text-center rounded mb-2 sub-item cursor-pointer <?PHP echo $isActive; ?>" style="cursor:pointer;">
                                        <b><?PHP echo $subject['code']; ?></b><br>
                                        <small><?PHP echo $subject['des']; ?></small>
                                    </div>
                                <?PHP endwhile; ?>
                            <?PHP endif; ?>

                        </div>
                    </div>

                    <div class="col border bg-light p-2 text-center rounded" style="max-height: 270px;">
                        <h4>Select Period</h4>
                        <div class="overflow-y-scroll" style="max-height: 80%;">
                            <?PHP 
                                $p_mid = ($period == 'midterm') ? 'bg-primary text-white' : 'bg-white';
                                $p_fcg = ($period == 'fcg') ? 'bg-primary text-white' : 'bg-white';
                            ?>
                            <div onclick="selectPeriod('midterm', 'Midterm Grade', this)" class="border p-2 text-center rounded mb-2 period-item cursor-pointer <?PHP echo $p_mid; ?>" style="cursor:pointer;">
                                <b>Midterm Grade</b><br>
                                <small>Prelim and Midterm</small>
                            </div>
                            <div onclick="selectPeriod('fcg', 'Final Course Grade', this)" class="border p-2 text-center rounded mb-2 period-item cursor-pointer <?PHP echo $p_fcg; ?>" style="cursor:pointer;">
                                <b>Final Course Grade</b><br>
                                <small>Semi-Finals And Finals </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex d-flex gap-3 m-4 align-items-center">
                    <div class="fs-4">
                        Grading Students From: <b id="from_sem"><?= $from_sem ?></b> / <b id="from_sub"><?= $from_sub ?></b> / <b id="from_period"><?= $from_per ?></b>
                    </div>
                    
                    <form action="grade.php" method="POST" class="ms-auto">
                        <input type="hidden" name="semester_id" id="semester_id" value="<?= $semester_id ?>">
                        <input type="hidden" name="subject_id" id="subject_id" value="<?= $subject_id ?>">
                        <input type="hidden" name="period" id="period" value="<?= $period ?>">
                        <button type="submit" name="load_student" id="load_students" class="btn bg-success text-light fw-bolder" disabled>
                            <i class="bi bi-people-fill"></i> LOAD STUDENTS
                        </button>
                    </form>
                </div>
                <hr>

                <div class="m-4">
                    <?php if ($f_students !== null && $f_students->num_rows > 0 || $m_students !== null && $m_students->num_rows > 0): ?>
                    
                    <form action="grade.php" method="POST">
                        <input type="hidden" name="semester_id" value="<?= $semester_id ?>">
                        <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                        <input type="hidden" name="period" value="<?= $period ?>">
                        
                        <table class="table table-striped table-bordered table-hover mb-5 shadow-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th colspan=2>FEMALE STUDENTS</th>
                                    <th class="text-end"><button type="button" class="btn btn-success d-flex ms-auto" onclick="pasteFemale()">Paste Female</button><small id="female_error" class="text-danger"></small></th>
                                </tr>
                                <tr class="row-cols-3">
                                    <th class="col-2">Student#</th>
                                    <th class="col-7">Name</th>
                                    <th class="col-3"><?= $from_per ?></th>
                                </tr>
                            </thead>
                            <tbody id="paste_f_body">
                                <?php while ($student = $f_students->fetch_assoc()): 
                                    $grade_field = ($period == "fcg") ? 'fcg' : 'midterm';
                                    $current_grade = $student[$grade_field];
                                ?>
                                    <tr>
                                        <td><?= $student['studno'] ?></td>
                                        <td><?= $student['name'] ?></td>
                                        <td>
                                            <select name="grades[<?= $student['grade_id'] ?>]" class="female form-select form-select-sm">
                                                <option value="Null" <?= (empty($current_grade) || $current_grade == 'Null') ? 'selected' : '' ?>>Select Grade (Null)</option>
                                                <?php 
                                                foreach ($gradeOptions as $grade):
                                                    $selected = ($current_grade == $grade) ? 'selected' : '';
                                                ?>
                                                    <option value="<?= $grade ?>" <?= $selected ?>><?= $grade ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        
                        <table class="table table-striped table-bordered table-hover shadow-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th colspan=2>MALE STUDENTS</th>
                                    <th class="text-end"><button type="button" class="btn btn-success d-flex ms-auto" onclick="pasteMale()">Paste Male</button><small id="male_error" class="text-danger"></small></th>
                                </tr>
                                <tr class="row-cols-3">
                                    <th class="col-2">Student#</th>
                                    <th class="col-7">Name</th>
                                    <th class="col-3"><?= $from_per ?></th>
                                </tr>
                            </thead>
                            <tbody id="paste_m_body">
                                <?php while ($student = $m_students->fetch_assoc()): 
                                    $grade_field = ($period == "fcg") ? 'fcg' : 'midterm';
                                    $current_grade = $student[$grade_field];
                                ?>
                                    <tr>
                                        <td><?= $student['studno'] ?></td>
                                        <td><?= $student['name'] ?></td>
                                        <td>
                                            <select name="grades[<?= $student['grade_id'] ?>]" class="male form-select form-select-sm">
                                                <option value="Null" <?= (empty($current_grade) || $current_grade == 'Null') ? 'selected' : '' ?>>Select Grade (Null)</option>
                                                <?php 
                                                foreach ($gradeOptions as $grade):
                                                    $selected = ($current_grade == $grade) ? 'selected' : '';
                                                ?>
                                                    <option value="<?= $grade ?>" <?= $selected ?>><?= $grade ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        
                        <div class="d-flex justify-content-end mb-4">
                            <button type="submit" name="submit_grades" class="btn btn-success fw-bolder">
                                <i class="bi bi-save"></i> SUBMIT ALL GRADES
                            </button>
                        </div>
                    </form>

                    <?php elseif (!empty($semester_id) && !empty($subject_id)): ?>
                        <div class="alert alert-info text-center">
                            No students enrolled in this Subject/Semester combination.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-secondary text-center">
                            Please select a Semester, Subject, and Grade Period to load students.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let semester = document.getElementById('semester_id');
        let subject = document.getElementById('subject_id');
        let periodInp = document.getElementById('period');

        document.addEventListener('DOMContentLoaded', toggleLoadButton);

        async function pasteGrades(gender) {
            const selects = document.querySelectorAll(`.${gender}`);
            const rows = selects.length;
            const text = await navigator.clipboard.readText();
            const result = []
            const error = document.getElementById(`${gender}_error`)

            grades = text.replace(/\r\n/g, "").trim().split(" ")
            
            for (let grade of grades) {
                console.log(grade.length)
                if (grade.length != 4) {
                    error.innerText = "Atleast one field has invalid value."
                    return;
                }
                result.push(grade)
            }

            if (result.length != rows) {
                error.innerText = "Number of row does not match the list."
                return;
            }
            
            for (let i = 0; i < selects.length; i++) {
                selects[i].value = result[i]
            }
            error.innerText = ""
        }

        function pasteFemale() {
            pasteGrades("female");
        }

        function pasteMale() {
            pasteGrades("male");
        }


        function selectSemester(id, name, element) {
            semester.value = id;
            document.getElementById('from_sem').innerText = name;
            
            document.querySelectorAll('.sem-item').forEach(el => {
                el.classList.remove('bg-primary', 'text-white');
                el.classList.add('bg-white');
            });
            element.classList.remove('bg-white');
            element.classList.add('bg-primary', 'text-white');
            
            toggleLoadButton();
        }

        function selectSubject(id, name, element) {
            subject.value = id;
            document.getElementById('from_sub').innerText = name;
            
            document.querySelectorAll('.sub-item').forEach(el => {
                el.classList.remove('bg-primary', 'text-white');
                el.classList.add('bg-white');
            });
            element.classList.remove('bg-white');
            element.classList.add('bg-primary', 'text-white');
            
            toggleLoadButton();
        }

        function selectPeriod(period, name, element) {
            periodInp.value = period;
            document.getElementById('from_period').innerText = name;
            
            document.querySelectorAll('.period-item').forEach(el => {
                el.classList.remove('bg-primary', 'text-white');
                el.classList.add('bg-white');
            });
            element.classList.remove('bg-white');
            element.classList.add('bg-primary', 'text-white');
            
            toggleLoadButton();
        }

        function toggleLoadButton() {
            const loadButton = document.getElementById('load_students');
            if (semester.value !== "" && subject.value !== "" && periodInp.value !== "") {
                loadButton.disabled = false;
            } else {
                loadButton.disabled = true;
            }
        }
    </script>
</body>
</html>