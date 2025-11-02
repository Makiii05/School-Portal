<?PHP
require("../conn.php");
require("../sql/check_admin.php");

$sql = "SELECT cr.course_id AS id, cr.code as code, cr.name AS name, COUNT(st.course_id) as count_student FROM courses cr LEFT JOIN students st ON cr.course_id = st.course_id GROUP BY cr.course_id ORDER BY cr.course_id ASC  ";

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
                <h3>Course List</h3>
                <form action="add.php" class="ms-auto" method="POST">
                    <input type="submit" class="form-control px-4 bg-dark text-light" name="createCourse" value="Create New Data">
                </form>
                </div>

                <hr>
                <div class="m-4">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr><th scope="col">Course#</th><th scope="col">Code</th><th scope="col">Name</th><th scope="col">Student Enrolled</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?PHP
                            while($row=$result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>$row[id]</td>";
                                echo "<td>$row[code]</td>";
                                echo "<td>$row[name]</td>";
                                echo "<td>$row[count_student]</td>";
                                echo "<td class='d-flex gap-3'>
                                    <form action='../sql/controller.php' method='POST'>
                                        <input type='hidden' name='delete' value='$row[id]'>
                                        <input type='hidden' name='from' value='courses'>
                                        <button class='btn border-danger' type='submit' onclick='confirm(`Are you sure to delete this data?`)'><i class='bi bi-trash3 p-1 text-danger'></i></button>
                                    </form>
                                    <form action='edit.php' method='POST'>
                                        <input type='hidden' name='edit' value='$row[id]'>
                                        <input type='hidden' name='from' value='courses'>
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
</html>