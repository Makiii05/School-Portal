<?PHP
require('../conn.php');
require("../sql/check_admin.php");
?>
<!DOCTYPE html>
<html lang="en">
<?PHP 
require("../components/head.php");
?>
<div class='container w-50 mt-5'>
    <div>
        <form action='../sql/controller.php' method="POST">
        <h1 class="fw-bolder mb-5">EDIT DATA</h1>
        <?PHP
        if($_POST['from'] == 'students'){
            $result = $conn->query("SELECT * FROM students WHERE student_id = '$_POST[edit]'");
            $courses = $conn->query("SELECT * FROM courses");
            while($row=$result->fetch_assoc()){
                echo "
                <input class='form-control mb-3' type='hidden' name='studentEdit' value='$row[student_id]' required>
                <b>Student Number</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5 type='text' name='studno' value='$row[student_no]' required>
                <b>Name</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5 type='text' name='name' value='$row[name]' required>
                <b>Gender</b>
                <select class='form-select mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' name=gender>
                    <option value='M'>Male</option>
                    <option value='F'>Female</option>
                </select>";
                echo "
                <b>Courses</b>
                <select class='form-select mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' name=course_id required>";
                while($course=$courses->fetch_assoc()){
                    echo "<option value='$course[course_id]'>$course[name]</option>";
                }
                echo "</select>";
            }
        echo "<a href='student.php' type='button' class='form-control bg-dark btn fw-bolder text-light'>Cancel</a>";
        }else if($_POST['from'] == 'courses'){
            $result = $conn->query("SELECT * FROM courses WHERE course_id = '$_POST[edit]'");
            while($row=$result->fetch_assoc()){
                echo "
                <input class='form-control mb-3' type='hidden' name='coursesEdit' value='$row[course_id]' required>
                <b>Code</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5 type='text' name='code' value='$row[code]' required>
                <b>Name</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5 type='text' name='name' value='$row[name]' required>
                ";
            }
        echo "<a href='courses.php' type='button' class='form-control bg-dark btn fw-bolder text-light'>Cancel</a>";
        }
        ?>
        <input type="submit" name="edit" class="form-control fw-bolder bg-success text-light" value="Save Changes">
        </form>
    </div>
</div>