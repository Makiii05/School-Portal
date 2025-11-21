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
                <input class='form-control mb-3' type='hidden' name='oldstudno' value='$row[student_no]' required>
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
        }else if($_POST['from'] == 'semesters'){
            $result = $conn->query("SELECT * FROM semesters WHERE semester_id = '$_POST[edit]'");
            while($row=$result->fetch_assoc()){
                echo "
                <input class='form-control mb-3' type='hidden' name='semesterEdit' value='$row[semester_id]' required>
                <b>Semester Code</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' type='text' name='semester_code' value='$row[code]' placeholder='Enter Semester Code' required>
                <b>Start Date</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' type='date' name='start_date' value='$row[start_date]' required>
                <b>End Date</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' type='date' name='end_date' value='$row[end_date]' required>
                <b>Type</b>
                <select class='form-select mx-3 border-0 rounded-0 border-dark text-dark border-bottom mb-5' name='type' required>
                <option value='Summer'>Summer</option>
                <option value='Regular'>Regular</option>
                </select>
                ";
            }
            echo "<a href='semester.php' type='button' class='form-control bg-dark btn fw-bolder text-light'>Cancel</a>";
        }else if($_POST['from'] == 'teachers'){
            $result = $conn->query("SELECT * FROM teachers WHERE id = '$_POST[edit]'");
            while($row=$result->fetch_assoc()){
                echo "
                <input class='form-control mb-3' type='hidden' name='teacherEdit' value='$row[id]' required>
                <input class='form-control mb-3' type='hidden' name='old_teacher_code' value='$row[teacher_code]' required>
                <b>Teacher Code</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5 type='text' name='teacher_code' value='$row[teacher_code]' required>
                <b>Name</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5 type='text' name='name' value='$row[name]' required>
                <b>Gender</b>
                <select class='form-select mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' name=gender>
                    <option value='M'"; echo ($row['gender'] == "M") ? ' selected ': ''; echo">Male</option>
                    <option value='F'"; echo ($row['gender'] == "F") ? ' selected ': ''; echo">Female</option>
                </select>";
            }
            echo "<a href='teacher.php' type='button' class='form-control bg-dark btn fw-bolder text-light'>Cancel</a>";
        }else if($_POST['from'] == 'subjects'){
            $result = $conn->query("SELECT * FROM subjects WHERE subject_id = '$_POST[edit]'");
            $rooms = $conn->query("SELECT * FROM rooms");
            $teachers = $conn->query("SELECT * FROM teachers");
            while($row=$result->fetch_assoc()){
                echo "
                <input class='form-control mb-3' type='hidden' name='subjectEdit' value='$row[subject_id]' required>
                <b>Code</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5 type='text' name='code' value='$row[code]' required>
                <b>Descriptiom</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5 type='text' name='des' value='$row[des]' required>
                <b>Day</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5 type='text' name='days' value='$row[days]' required>
                <b>Time</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5 type='text' name='time' value='$row[time]' required>";
                echo "<b>Room</b>
                <select class='form-select mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' name=room_id required>";
                while($room=$rooms->fetch_assoc()){
                    echo "<option value='$room[id]'>$room[name]</option>";
                }
                echo "</select>";
                echo "<b>Teacher</b>
                <select class='form-select mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5' name=teacher_id required>";
                while($teacher=$teachers->fetch_assoc()){
                    echo "<option value='$teacher[id]'>$teacher[teacher_code] - $teacher[name]</option>";
                }
                echo "</select>";
                echo "
                <b>Price per Unit</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5 type='number' name='price_unit' value='$row[price_unit]' required>
                <b>Unit</b>
                <input class='form-control mx-3 border-0 rounded-0 border-dark text-secondary border-bottom mb-5 type='number' name='unit' value='$row[unit]' required>
                ";
            }
            echo "<a href='subject.php' type='button' class='form-control bg-dark btn fw-bolder text-light'>Cancel</a>";
        }
        ?>
        <input type="submit" name="edit" class="form-control fw-bolder bg-success text-light" value="Save Changes">
        </form>
    </div>
</div>