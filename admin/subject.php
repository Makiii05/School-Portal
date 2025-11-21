<?PHP
require("../conn.php");
require("../sql/check_admin.php");

$sql = "SELECT
        sub.subject_id AS id,
        sub.code AS code,
        sub.des AS des,
        sub.days AS day,
        sub.time AS time,
        r.name AS room,
        t.name AS teacher,
        sub.unit AS unit
        FROM subjects sub
        JOIN teachers t ON t.id = sub.teacher_id
        JOIN rooms r ON r.id = sub.room_id
        ";
if(isset($_POST['order'])) {
    $sql = "SELECT
            sub.subject_id AS id,
            sub.code AS code,
            sub.des AS des,
            sub.days AS day,
            sub.time AS time,
            r.name AS room,
            t.name AS teacher,
            sub.unit AS unit
            FROM subjects sub
            JOIN teachers t ON t.id = sub.teacher_id
            JOIN rooms r ON r.id = sub.room_id
            ORDER BY $_POST[order]";
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
                <h3>Subject List</h3>
                <form action="add.php" class="ms-auto" method="POST">
                    <input type="submit" class="form-control px-4 bg-dark text-light" name="createSubject" value="Create New Data">
                </form>
                <form action="subject.php" class="mx-2" id="order_by_form" method="POST">
                    <select name="order" id="order_by" class="form-select">
                        <option value="" disable>-- Order By --</option>
                        <option value="code">Code</option>
                        <option value="des">Description</option>
                        <option value="days">Day</option>
                        <option value="time">Time</option>
                        <option value="room_id">Room</option>
                        <option value="teacher_id">Teacher</option>
                        <option value="unit">Unit</option>
                    </select>
                </form>
                </div>

                <hr>
                <div class="m-4">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                                <th>Teacher</th>
                                <th>Unit</th>
                                <th>Action</th>
                        </thead>
                        <tbody>
                            <?PHP
                            while($row=$result->fetch_assoc()){
                                echo "<tr>";
                                echo "<td>$row[code]</td>";
                                echo "<td>$row[des]</td>";
                                echo "<td>$row[day]</td>";
                                echo "<td>$row[time]</td>";
                                echo "<td>$row[room]</td>";
                                echo "<td>$row[teacher]</td>";
                                echo "<td>$row[unit]</td>";
                                echo "<td class='d-flex gap-3 h-100'>
                                    <form action='../sql/controller.php' method='POST'>
                                        <input type='hidden' name='delete' value='$row[id]'>
                                        <input type='hidden' name='from' value='subjects'>
                                        <button class='btn border-danger' type='submit' onclick='confirm(`Are you sure to delete this data?`)'><i class='bi bi-trash3 p-1 text-danger'></i></button>
                                    </form>
                                    <form action='edit.php' method='POST'>
                                        <input type='hidden' name='edit' value='$row[id]'>
                                        <input type='hidden' name='from' value='subjects'>
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