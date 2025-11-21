<?PHP
require("../conn.php");
require("../sql/check_admin.php");

$sql = "SELECT * FROM semesters";

if(isset($_POST['order'])) {
    $sql = "SELECT * FROM semesters ORDER BY $_POST[order]";
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
                <h3>Semester List</h3>
                <form action="add.php" class="ms-auto" method="POST">
                    <input type="submit" class="form-control px-4 bg-dark text-light" name="createSemester" value="Create New Data">
                </form>
                <form action="semester.php" class="mx-2" id="order_by_form" method="POST">
                    <select name="order" id="order_by" class="form-select">
                        <option value="" disable>-- Order By --</option>
                        <option value="semester_id">Id</option>
                        <option value="start_date">Start Date</option>
                        <option value="end_date">End Date</option>
                        <option value="type">Type</option>
                    </select>
                </form>
                </div>

                <hr>
                <div class="m-4">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr><th scope="col">Semester ID</th><th scope="col">Code</th><th scope="col">Start Date</th><th scope="col">End Date</th><th scope="col">Type</th><th scope="col">Action</th></tr>
                        </thead>
                        <tbody>
                            <?PHP
                            while($row=$result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>$row[semester_id]</td>";
                                echo "<td>$row[code]</td>";
                                echo "<td>$row[start_date]</td>";
                                echo "<td>$row[end_date]</td>";
                                echo "<td>$row[type]</td>";
                                echo "<td class='d-flex gap-3 h-100'>
                                    <form action='../sql/controller.php' method='POST'>
                                        <input type='hidden' name='delete' value='$row[semester_id]'>
                                        <input type='hidden' name='from' value='semesters'>
                                        <button class='btn border-danger' type='submit' onclick='confirm(`Are you sure to delete this data?`)'><i class='bi bi-trash3 p-1 text-danger'></i></button>
                                    </form>
                                    <form action='edit.php' method='POST'>
                                        <input type='hidden' name='edit' value='$row[semester_id]'>
                                        <input type='hidden' name='from' value='semesters'>
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