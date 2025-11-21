<?PHP
session_start();
if ($_SESSION['user_role'] != "Student") {
    header("Location:auth/signin.php");
}

