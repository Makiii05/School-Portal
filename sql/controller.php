<?PHP
include("../conn.php");

// create
if(isset($_POST["add"])){
    if(isset($_POST["studno"])){
        $studno = $_POST["studno"];
        $name = $_POST["name"];
        $gender = $_POST["gender"];
        $course = $_POST["course_id"];

        // check if existing studno
        $checkIfExisting = $conn->query("SELECT * FROM students WHERE student_no = $studno");
        if(mysqli_num_rows($checkIfExisting) >= 1){
            header("location:../admin/student.php?error=2");
            exit;
        }

        $sql = "INSERT INTO students (student_no, name, gender, course_id) VALUES ('$studno','$name','$gender','$course')";
        $result = $conn->query($sql);
        $password = hash("MD5", "123");
        $sql = "INSERT INTO users (user, pass, role) VALUES ('$studno','$password','Student')";
        $result = $conn->query($sql);
        header("location:../admin/student.php?success=1");
    }else if(isset($_POST["code"])){
        $code = $_POST["code"];
        $name = $_POST["name"];

        // check if existing studno
        $checkIfExisting = $conn->query("SELECT * FROM courses WHERE code = '$code'");
        if(mysqli_num_rows($checkIfExisting) >= 1){
            header("location:../admin/courses.php?error=2");
            exit;
        }

        $sql = "INSERT INTO courses (code, name) VALUES ('$code','$name')";
        $result = $conn->query($sql);
        header("location:../admin/courses.php?success=1");

    }else if(isset($_POST["semester_code"])){
        $code = $_POST['semester_code'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $type = $_POST['type'];
        
        // check if existing studno
        $checkIfExisting = $conn->query("SELECT * FROM semesters WHERE code = '$code'");
        if(mysqli_num_rows($checkIfExisting) >= 1){
            header("location:../admin/semester.php?error=2");
            exit;
        }

        $sql = "INSERT INTO semesters (code, start_date, end_date, type) VALUES ('$code','$start_date', '$end_date', '$type')";
        $result = $conn->query($sql);
        header("location:../admin/semester.php?success=1");
    }else if(isset($_POST["or_number"])){
        $or_number = $_POST["or_number"];
        $date = $_POST["date"];
        $studno = $_POST["student_no"];
        $semesterId = $_POST["semester_id"];
        $cash = $_POST["cash"] ?? "0.00";
        $gcash = $_POST["gcash"] ?? "0.00";
        $refNo = $_POST["reference_no"] ?? "Null";

        // atleast one payment method
        if(empty($cash) && empty($gcash)){
            header("location:../admin/collection.php?error=6");
            exit;
        }

        // only take one payment method
        if(!empty($cash) && !empty($gcash)){
            header("location:../admin/collection.php?error=7");
            exit;
        }

        // if gcash, reference no is required
        if(!empty($gcash) && empty($refNo)){
            header("location:../admin/collection.php?error=8");
            exit;
        }

        $checkIfExisting = $conn->query("SELECT * FROM collections WHERE or_number = '$or_number'");
        if(mysqli_num_rows($checkIfExisting) >= 1){
            //update
            $sql = "UPDATE collections SET or_date = '$date', semester_id = '$semesterId', cash = '$cash', gcash = '$gcash', gcash_refno = '$refNo' 
                    WHERE or_number = '$or_number'";
            $result = $conn->query($sql);
            header("location:../admin/collection.php?success=2");
            exit;
        }else{
            $studId = $conn->query("SELECT student_id FROM students WHERE student_no = '$studno'")->fetch_assoc()["student_id"];
            //insert
            $sql = "INSERT INTO collections (or_number, or_date, student_id, semester_id, cash, gcash, gcash_refno) 
                    VALUES ('$or_number', '$date', '$studId', '$semesterId', '$cash', '$gcash', '$refNo')";
            $result = $conn->query($sql);
        }

        header("location:../admin/collection.php?success=1");
    }else if(isset($_POST["teacher_code"])){
        $code = $_POST["teacher_code"];
        $name = $_POST["name"];
        $gender = $_POST["gender"];

        // check if existing studno
        $checkIfExisting = $conn->query("SELECT * FROM teachers WHERE teacher_code = $code");
        if(mysqli_num_rows($checkIfExisting) >= 1){
            header("location:../admin/teacher.php?error=2");
            exit;
        }

        $sql = "INSERT INTO teachers (teacher_code, name, gender) VALUES ('$code','$name','$gender')";
        $result = $conn->query($sql);
        $password = hash("MD5", "123");
        $sql = "INSERT INTO users (user, pass, role) VALUES ('$code','$password','Teacher')";
        $result = $conn->query($sql);
        header("location:../admin/teacher.php?success=1");
    }else if(isset($_POST["subject_code"])){
        $code = $_POST["subject_code"];
        $des = $_POST["description"];
        $day = $_POST["day"];
        $time = "$_POST[start_time]-$_POST[end_time]";
        $room_id = $_POST["room_id"];
        $teacher_id = $_POST["teacher_id"];
        $price = $_POST["price_unit"];
        $unit = $_POST["unit"];

        // check if existing studno
        $checkIfExisting = $conn->query("SELECT * FROM subjects WHERE code = $code");
        if(mysqli_num_rows($checkIfExisting) >= 1){
            header("location:../admin/subject.php?error=2");
            exit;
        }

        $sql = "INSERT INTO subjects (code, des, days, time, room_id, teacher_id, price_unit, unit) VALUES ('$code','$des','$day','$time','$room_id','$teacher_id','$price','$unit')";
        $result = $conn->query($sql);
        header("location:../admin/subject.php?success=1");
    }
}

// edit
if(isset($_POST["edit"])){
    if(isset($_POST["studentEdit"])){
        $id = $_POST["studentEdit"];
        $studno = $_POST["studno"];
        $oldstudno = $_POST["oldstudno"];
        $name = $_POST["name"];
        $gender = $_POST["gender"];
        $course = $_POST["course_id"];
        $conn->query("UPDATE users SET user = '$studno' WHERE user = '$oldstudno' AND role = 'Student'");
        $sql = "UPDATE students SET student_no = '$studno', name = '$name', gender = '$gender', course_id = '$course' WHERE student_id = '$id'";
        $result = $conn->query($sql);
        header("location:../admin/student.php?success=2");
    }else if (isset($_POST["coursesEdit"])){
        $id = $_POST["coursesEdit"];
        $code = $_POST["code"];
        $name = $_POST["name"];
        $sql = "UPDATE courses SET code = '$code', name = '$name' WHERE course_id = '$id'";
        $result = $conn->query($sql);
        header("location:../admin/courses.php?success=2");
    }else if (isset($_POST["semesterEdit"])){
        $id = $_POST["semesterEdit"];
        $code = $_POST["semester_code"];
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];
        $type = $_POST["type"];
        $sql = "UPDATE semesters SET code = '$code', start_date = '$start_date', end_date= '$end_date', type = '$type' WHERE semester_id = '$id'";
        $result = $conn->query($sql);
        header("location:../admin/semester.php?success=2");
    }else if (isset($_POST["teacherEdit"])){
        $id = $_POST["teacherEdit"];
        $old_code = $_POST["old_teacher_code"];
        $code = $_POST["teacher_code"];
        $name = $_POST["name"];
        $gender = $_POST["gender"];
        $conn->query("UPDATE users SET user = '$code' WHERE user = '$old_code' AND role = 'Teacher'");
        $sql = "UPDATE teachers SET teacher_code = '$code', name = '$name', gender= '$gender' WHERE id = '$id'";
        $result = $conn->query($sql);
        header("location:../admin/teacher.php?success=2");
    }else if (isset($_POST["subjectEdit"])){
        $id = $_POST["subjectEdit"];
        $code = $_POST["code"];
        $des = $_POST["des"];
        $days = $_POST["days"];
        $time = $_POST["time"];
        $room = $_POST["room_id"];
        $teacher = $_POST["teacher_id"];
        $price = $_POST["price_unit"];
        $unit = $_POST["unit"];
        $sql = "UPDATE subjects SET code='$code', des='$des', days='$days', time='$time', room_id='$room', teacher_id='$teacher', price_unit='$price', unit='$unit'  WHERE subject_id = '$id'";
        $result = $conn->query($sql);
        header("location:../admin/subject.php?success=2");
    }
}

// delete
if(isset($_POST["delete"])){
    $table = $_POST["from"];
    $id = $_POST["delete"];
    $where = "";
    $header = "";
    if ($table == "students"){
        $where = "student_id";
        $header = "location:../admin/student.php?success=3";
    }else if($table == "courses"){
        $where = "course_id";
        $header = "location:../admin/course.php?success=3";
    }else if($table == "semesters"){
        $where = "semester_id";
        $header = "location:../admin/semester.php?success=3";
    }else if($table == "teachers"){
        $where = "id";
        $header = "location:../admin/teacher.php?success=3";
    }else if($table == "subjects"){
        $where = "subject_id";
        $header = "location:../admin/subject.php?success=3";
    }
    //check if student, if enroll
    if($table == "students"){
        $checkIfExisting = $conn->query("SELECT * FROM student_subjects WHERE student_id = $id");
        if(mysqli_num_rows($checkIfExisting) >= 1){
            header("location:../admin/student.php?error=1");
            exit;
        }
    }

    //check if course, if use in students
    if($table == "courses"){
        $checkIfExisting = $conn->query("SELECT * FROM students WHERE course_id = $id");
        if(mysqli_num_rows($checkIfExisting) >= 1){
            header("location:../admin/courses.php?error=1");
            exit;
        }
    }

    //check if semester, if use in student
    if($table == "semesters"){
        $checkIfExisting = $conn->query("SELECT * FROM student_subjects WHERE semester_id = $id");
        if(mysqli_num_rows($checkIfExisting) >= 1){
            header("location:../admin/semester.php?error=1");
            exit;
        }
    }

    //check if teacher, if use in subject
    if($table == "teachers"){
        $checkIfExisting = $conn->query("SELECT * FROM subjects WHERE teacher_id = $id");
        if(mysqli_num_rows($checkIfExisting) >= 1){
            header("location:../admin/teacher.php?error=1");
            exit;
        }
    }

    //check if subject, if use in student_subject
    if($table == "subjects"){
        $checkIfExisting = $conn->query("SELECT * FROM student_subjects WHERE subject_id = $id");
        if(mysqli_num_rows($checkIfExisting) >= 1){
            header("location:../admin/subject.php?error=1");
            exit;
        }
    }
    $sql = "DELETE FROM $table WHERE $where = $id";
    $result = $conn->query($sql);
    $conn->query("DELETE FROM users WHERE user = '$_POST[studno]' AND role = 'Student'");
    header($header);
}
