<?PHP
require("../conn.php");
require("../sql/check_teacher.php");

$teacher_id = "";
$semester_id = "";
$subject_id = "";
$term = "";


$student = $conn->query("SELECT * FROM students")

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
                            <div onclick="semester(1)" class="border p-2 text-center rounded bg-white mb-2">
                                <b>1st 25-26</b><br>
                                <small>1st Semester A.Y. 2025 - 2026 </small>
                            </div>
                            <div onclick="semester(2)" class="border p-2 text-center rounded bg-white mb-2">
                                <b>2st 25-26</b><br>
                                <small>2nd Semester A.Y. 2025 - 2026 </small>
                            </div>
                        </div>
                    </div>
                    <div class="col border bg-light p-2 text-center rounded" style="max-height: 270px;">
                        <h4>Select Subject</h4>
                        <div class="overflow-y-scroll" style="max-height: 80%;">
                            <div onclick="subject(1)" class="border p-2 text-center rounded bg-white mb-2">
                                <b>CS3A8ANALYSIS</b><br>
                                <small>Elementary Analysis</small>
                            </div>
                            <div onclick="semester(2)" class="border p-2 text-center rounded bg-white mb-2">
                                <b>CS3A8APPDEV</b><br>
                                <small>Application Development</small>
                            </div>
                            <div onclick="semester(3)" class="border p-2 text-center rounded bg-white mb-2">
                                <b>CS3A8AUTOLANG</b><br>
                                <small>Automata and Language</small>
                            </div>
                        </div>
                    </div>
                    <div class="col border bg-light p-2 text-center rounded" style="max-height: 270px;">
                        <h4>Select Period</h4>
                        <div class="overflow-y-scroll" style="max-height: 80%;">
                            <div onclick="period('midterm')" class="border p-2 text-center rounded bg-white mb-2">
                                <b>Midterm Grade</b><br>
                                <small>Prelim and Midterm</small>
                            </div>
                            <div onclick="period('fcg')" class="border p-2 text-center rounded bg-white mb-2">
                                <b>Final Course Grade</b><br>
                                <small>Semi-Finals And Finals </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex d-flex gap-3 m-4">
                    <div class="fs-4">
                        Grading Students From: <b id="from_sem">...</b> / <b id="">...</b> / <b>...</b>
                    </div>
                    <form action="" class="ms-auto">
                        <input type="hidden" name="teacher_id" id="teacher_id">
                        <input type="hidden" name="semester_id" id="semester_id">
                        <input type="hidden" name="period" id="period">
                        <button type="submit" class="btn bg-success text-light fw-bolder">LOAD STUDENTS</button>
                    </form>
                </div>
                <hr>
                <div class="m-4">
                    <?PHP  //if() :?>
                    <table class="table table-striped table-bordered table-hover mb-5">
                        <thead class="table-dark">
                            <tr><th colspan=4 class="text-center">FEMALE STUDENTS</th></tr>
                            <tr class="row-cols-4"><th class="col-4">Student#</th><th class="col-4">Name</th><th class="col-2">Midterm</th><th class="col-2">Final Course Grade</th></tr>
                        </thead>
                        <tbody id="student_list_table">
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="table-dark">
                            <tr><th colspan=4 class="text-center">MALE STUDENTS</th></tr>
                            <tr class="row-cols-4"><th class="col-4">Student#</th><th class="col-4">Name</th><th class="col-2">Midterm</th><th class="col-2">Final Course Grade</th></tr>
                        </thead>
                        <tbody id="student_list_table">
                            <tr>
                                <td>1</td>
                                <td>2</td>
                                <td>3</td>
                                <td>4</td>
                            </tr>
                        </tbody>
                    </table>
                    <?PHP  //endif :?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function semester(id){
            document.getElementById('semester_id').value = $id;
            document.getElementById('semester_id').value = $id;
        }
    </script>
</body>
</html>