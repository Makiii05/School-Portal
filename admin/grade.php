<?PHP
require("../conn.php");
require("../sql/check_admin.php");

$sql = "SELECT st.student_id AS id, st.student_no AS studno ,st.name AS name, st.gender AS gender, cr.name AS course_name FROM students st JOIN courses cr ON st.course_id = cr.course_id";
$course = $_POST['course_filter'] ?? "";
$semester = $_POST['semester'] ?? 1 ;
if(isset($_POST['course_filter'])) {
    $sql = "SELECT st.student_id AS id, st.student_no AS studno ,st.name AS name, st.gender AS gender, cr.name AS course_name FROM students st JOIN courses cr ON st.course_id = cr.course_id WHERE cr.name = '$_POST[course_filter]'";
}
$student_list = [];
$result = $conn->query($sql);
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
                <div class="d-flex gap-3">
                    <b>Course:</b>
                    <form action="grade.php" id="order_by_form" method="POST">
                        <select name="course_filter" id="order_by" class="form-select">
                            <?PHP
                            $courses = $conn->query("SELECT * FROM courses");
                            while($row=$courses->fetch_assoc()){
                                echo "<option value='$row[name]' ";
                                echo ($course == $row['name']) ? 'selected' : '';
                                echo ">$row[name]</option>";
                            }
                            ?>
                        </select>
                    </form>
                    <label class="form-label fw-bold">Search By:</label>
                    <div class="d-flex gap-3">
                        <select id="search_by" class="form-select border-dark">
                            <option value="studno">Student #</option>
                            <option value="name">Student Name</option>
                            <option value="course_name">Course Name</option>
                        </select>
                        <input type="text" id="search_by_input" class="form-control border-dark" placeholder="Enter Student Information" autofocus>
                    </div>
                </div>
                <div class="m-4">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr><th scope="col">Student#</th><th scope="col">Name</th><th scope="col">Gender</th><th scope="col">Course</th><th scope="col">Action</th></tr>
                        </thead>
                        <tbody id="student_list_table">
                            <?PHP
                            while($row=$result->fetch_assoc()){
                                $gender = ($row["gender"] == "F") ? "Female" : "Male";
                                echo "<tr>";
                                echo "<td>$row[studno]</td>";
                                echo "<td>$row[name]</td>";
                                echo "<td>$gender</td>";
                                echo "<td>$row[course_name]</td>";
                                echo "<td>
                                    <form action='add.php' method='POST'>
                                        <input type='hidden' name='student_id' value='$row[id]'>
                                        <button class='btn border-success' name='createGrade' type='submit'><i class='bi bi-file-earmark-fill p-1 text-success'></i></button>
                                    </form>
                                </td>";
                                echo "</tr>";
                                $student_list[] = $row;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    let orderBy = document.getElementById("order_by")
    let orderByForm = document.getElementById("order_by_form")
    orderBy.onchange = function () {
        order_by_form.submit()
    }

    const studentList = <?= json_encode($student_list) ?>;
    const table = document.getElementById("student_list_table");
    const searchBy = document.getElementById("search_by");
    const searchByInput = document.getElementById("search_by_input");
    const semForm = document.getElementById("semester_form");
    const semSelect = document.getElementById("semester_select");

    searchBy.onchange = search_student;
    searchByInput.oninput = search_student;

    function search_student() {
        const filterBy = searchBy.value;        
        const keyword = searchByInput.value.toLowerCase();

        table.innerHTML = "";

        const results = studentList.filter(stud => 
            stud[filterBy].toLowerCase().includes(keyword)
        );

        if (results.length === 0) {
            table.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No matching students</td></tr>';
            return;
        }
        console.log(studentList)
        results.forEach(stud => {
            const gender = stud.gender == "M" ? "Male" : "Female";
            const row = `
                <tr>
                    <td>${stud.studno}</td>
                    <td>${stud.name}</td>
                    <td>${gender}</td>
                    <td>${stud.course_name}</td>
                    <td>
                        <form action='add.php' method='POST'>
                            <input type='hidden' name='student_id' value='${stud.id}'>
                            <button class='btn border-success' name='createGrade' type='submit'><i class='bi bi-file-earmark-fill p-1 text-success'></i></button>
                        </form>
                    </td>
                </tr>`;
            table.insertAdjacentHTML("beforeend", row);
        });
    }
</script>
</html>