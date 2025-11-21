<?PHP
require("../../conn.php");
session_start();
session_destroy();
if(isset($_POST['signin'])){
    $username = $_POST['username'];
    $password = hash("MD5", $_POST['password']);

    $stmt = $conn->prepare("SELECT id, user, role FROM users WHERE user = ? AND pass = ? AND role = 'Administrator'");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // fetch any columns you selected
        $stmt->bind_result($id, $user, $role);
        $stmt->fetch();

        // successful login
        session_start();
        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $user;
        $_SESSION['user_role'] = $role;
        header("location:../../index.php");
        $stmt->close();
    } else {
        $stmt->close();
        header("location:signin.php?error");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?PHP 
require("../../components/head.php");
?>
<body>
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="card border-dark shadow-sm" style="width: 420px;">
            <div class="card-body p-4">
                <h4 class="card-title text-center mb-4">Sign In For Admin</h4>
                <form action="signin.php" method="POST" autocomplete="off">
                    <input type="hidden" id="from" name="from" value="admin" class="form-control border-dark" required>
                    <div class="mb-3">
                        <label for="username" class="form-label">Admin Username</label>
                        <input type="text" id="username" name="username" class="form-control border-dark" value="<?= isset($_GET['u']) ? htmlspecialchars($_GET['u']) : '' ?>" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control border-dark" required>
                    </div>

                    <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger py-2" role="alert">
                        Password or Username does not exist
                    </div>
                    <?php endif; ?>
                    <hr>

                    <button type="submit" class="btn btn-dark w-100" name="signin">Sign In</button>
                </form>

            </div>
        </div>
    </div>
</body>
</html>