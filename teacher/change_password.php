<?php
require("../conn.php");
require("../sql/check_teacher.php");
if (isset($_POST['change_password'])) {
    $old_pass_raw = $_POST['old_pass'] ?? '';
    $new_pass_raw = $_POST['new_pass'] ?? '';
    $c_new_pass_raw = $_POST['c_new_pass'] ?? '';

    $old_pass = hash("MD5", $old_pass_raw);
    $new_pass = hash("MD5", $new_pass_raw);
    $c_new_pass = hash("MD5", $c_new_pass_raw);

    $message = "";
    $message_color = "";
    $message_state = "";

    $stmt = $conn->prepare("SELECT pass FROM users WHERE user = ? AND role = ?");
    $role = "Teacher";
    $stmt->bind_param("ss", $_SESSION['user_code'], $role);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $message = "User not found.";
        $message_color = "border-danger bg-danger-subtle";
        $message_state = "Error";
    } else {
        $stmt->bind_result($pass);
        $stmt->fetch();

        if ($old_pass !== $pass) {
            $message = "Incorrect old password.";
            $message_color = "border-danger bg-danger-subtle";
            $message_state = "Error";
        } elseif ($new_pass_raw !== $c_new_pass_raw) {
            $message = "Confirm password does not match the new password.";
            $message_color = "border-danger bg-danger-subtle";
            $message_state = "Error";
        } else {
            $update = $conn->prepare("UPDATE users SET pass = ? WHERE user = ? AND role = ?");
            $update->bind_param("sss", $new_pass, $_SESSION['user_code'], $role);
            $update->execute();

            $message = "Successfully changed password.";
            $message_color = "border-success bg-success-subtle";
            $message_state = "Success";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<?php require("../components/head.php"); ?>
<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php require("../components/sidebar.php"); ?>
            <div class="col py-3">
                <div class="d-flex w-100">
                    <h3>Change Password</h3>
                </div>
                <hr>
                <form action="change_password.php" method="POST" class="container w-100 d-flex flex-column gap-3">
                    <div class="mx-2">
                        <b>Old Password</b>
                        <input autofocus class="form-control" type="password" name="old_pass" required>
                    </div>
                    <div class="mx-2">
                        <b>New Password</b>
                        <input class="form-control" type="password" name="new_pass" required>
                    </div>
                    <div class="mx-2">
                        <b>Confirm New Password</b>
                        <input class="form-control" type="password" name="c_new_pass" required>
                    </div>
                    <?PHP if(!empty($message)): ?>
                    <div class="mx-2 border <?= $message_color ?> p-3 text-center rounded">
                        <b><?= $message_state ?>: </b>
                        <?= $message ?>
                    </div>
                    <?PHP endif; ?>
                    <hr>
                    <input class="form-control bg-dark text-light fw-bold" type="submit" name="change_password" value="Change Password">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
