<?PHP
require("../conn.php");
require("../sql/check_admin.php");


$sql = "SELECT st.student_id AS id, st.student_no AS studno ,st.name AS name, st.gender AS gender, cr.name AS course_name FROM students st JOIN courses cr ON st.course_id = cr.course_id";

if(isset($_POST['order'])) {
    $sql = "SELECT st.student_id AS id, st.student_no AS studno ,st.name AS name, st.gender AS gender, cr.name AS course_name FROM students st JOIN courses cr ON st.course_id = cr.course_id ORDER BY $_POST[order]";
}

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
                <h3>Student List</h3>
                <form action="add.php" class="ms-auto" method="POST">
                    <input type="submit" class="form-control px-4 bg-dark text-light" name="createStudent" value="Create New Data">
                </form>
                <form action="student.php" class="mx-2" id="order_by_form" method="POST">
                    <select name="order" id="order_by" class="form-select">
                        <option value="" disable>-- Order By --</option>
                        <option value="gender">Gender</option>
                        <option value="name">Name</option>
                        <option value="student_no">Student Number</option>
                    </select>
                </form>
                </div>

                <hr>
                <div class="m-4">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr><th scope="col">Student#</th><th scope="col">Name</th><th scope="col">Gender</th><th scope="col">Course</th><th scope="col">Action</th></tr>
                        </thead>
                        <tbody>
                            <?PHP
                            while($row=$result->fetch_assoc()){
                                $gender = ($row["gender"] == "F") ? "Female" : "Male";
                                echo "<tr>";
                                echo "<td>$row[studno]</td>";
                                echo "<td>$row[name]</td>";
                                echo "<td>$gender</td>";
                                echo "<td>$row[course_name]</td>";
                                echo "<td class='d-flex gap-3'>
                                    <form action='../sql/controller.php' method='POST'>
                                        <input type='hidden' name='studno' value='$row[studno]'>
                                        <input type='hidden' name='delete' value='$row[id]'>
                                        <input type='hidden' name='from' value='students'>
                                        <button class='btn border-danger' type='submit' onclick='confirm(`Are you sure to delete this data?`)'><i class='bi bi-trash3 p-1 text-danger'></i></button>
                                    </form>
                                    <form action='edit.php' method='POST'>
                                        <input type='hidden' name='edit' value='$row[id]'>
                                        <input type='hidden' name='from' value='students'>
                                        <button type='submit' class='btn border-dark'><i class='bi bi-pencil p-1 text-dark'></i></button>
                                    </form>
                                </td>";
                                echo "</tr>";
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
</script>
</html>