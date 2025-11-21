<?PHP
session_start();
if ($_SESSION['user_role'] != "Teacher") {
    header("Location:auth/signin.php");
}

