<!DOCTYPE html>
<html lang="en">
<?PHP 
    require("conn.php");
    require("components/head.php");
    session_start();
    if(!$_SESSION['user_name']){
        header("Location:student/auth/signin.php");
    }
?>
<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include("sql/messages.php"); ?>
            <button class="btn btn-outline-light bg-dark h-25 p-0 position-absolute top-50 translate-middle-y" style="width:2%;" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                <i class="bi bi-arrow-bar-right"></i>
            </button>
            <!-- Offcanvas Sidebar -->
            <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarLabel" style="width:350px;">
                <div class="offcanvas-header border-bottom">
                    <h5 class="offcanvas-title fw-bold" id="sidebarLabel">
                        <?= "$_SESSION[user_role] Portal" ?>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body d-flex flex-column justify-content-between">
                    <?php if ($_SESSION['user_role'] == 'Administrator'): ?>
                        <ul class="nav nav-pills flex-column mb-auto" id="menu">
                            <li class="nav-item">
                                <a href="index.php" class="nav-link text-white">
                                    <i class="fs-5 bi-house"></i>
                                    <span class="ms-2">Home</span>
                                </a>
                            </li>
                            <li>
                                <a href="#submenu1" data-bs-toggle="collapse" class="nav-link text-white">
                                    <i class="fs-5 bi-speedometer2"></i>
                                    <span class="ms-2">List</span>
                                </a>
                                <ul class="collapse show nav flex-column ms-3" id="submenu1" data-bs-parent="#menu">
                                    <li>
                                        <a href="admin/student.php" class="nav-link text-white">
                                            <i class="bi bi-people"></i>
                                            <span class="ms-2">Students</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="admin/courses.php" class="nav-link text-white">
                                            <i class="bi bi-journal-bookmark"></i>
                                            <span class="ms-2">Courses</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="admin/teacher.php" class="nav-link text-white">
                                            <i class="bi bi-people"></i>
                                            <span class="ms-2">Teachers</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="admin/subject.php" class="nav-link text-white">
                                            <i class="bi bi-hdd-stack"></i>
                                            <span class="ms-2">Subjects</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="admin/enroll.php" class="nav-link text-white">
                                    <i class="bi bi-person-plus-fill fs-5"></i>
                                    <span class="ms-2">Enroll Student</span>
                                </a>
                            </li>
                            <li>
                                <a href="admin/grade.php" class="nav-link text-white">
                                    <i class="bi bi-file-earmark-plus-fill fs-5"></i>
                                    <span class="ms-2">Insert Grade</span>
                                </a>
                            </li>
                            <li>
                                <a href="admin/semester.php" class="nav-link text-white">
                                    <i class="bi bi-calendar-plus-fill fs-5"></i>
                                    <span class="ms-2">Insert Semester</span>
                                </a>
                            </li>
                            <li>
                                <a href="admin/collection.php" class="nav-link text-white">
                                    <i class="bi bi-bank2 fs-5"></i>
                                    <span class="ms-2">Insert Collection</span>
                                </a>
                            </li>
                            <li>
                                <a href="print/p_stud.php" target="_blank" class="nav-link text-white">
                                    <i class="fs-5 bi-table"></i>
                                    <span class="ms-2">Report</span>
                                </a>
                            </li>
                        </ul>
                    <?php elseif ($_SESSION['user_role'] == 'Student'): ?>
                        <ul class="nav nav-pills flex-column mb-auto" id="menu">
                            <li class="nav-item">
                                <a href="index.php" class="nav-link text-white">
                                    <i class="fs-5 bi bi-house-door-fill"></i>
                                    <span class="ms-2">Home</span>
                                </a>
                            </li>
                            <li>
                                <a href="student/grade.php" class="nav-link text-white">
                                    <i class="fs-5 bi bi-journal-text"></i>
                                    <span class="ms-2">View Grade</span>
                                </a>
                            </li>
                            <li>
                                <a href="student/ledger.php" class="nav-link text-white">
                                    <i class="fs-5 bi bi-journal-text"></i>
                                    <span class="ms-2">View Balance</span>
                                </a>
                            </li>
                            <li>
                                <a href="student/change_password.php" class="nav-link text-white">
                                    <i class="fs-5 bi bi-key-fill"></i>
                                    <span class="ms-2">Change Password</span>
                                </a>
                            </li>
                        </ul>
                    <?php elseif ($_SESSION['user_role'] == 'Teacher'): ?>
                        <ul class="nav nav-pills flex-column mb-auto" id="menu">
                            <li class="nav-item">
                                <a href="index.php" class="nav-link text-white">
                                    <i class="fs-5 bi bi-house-door-fill"></i>
                                    <span class="ms-2">Home</span>
                                </a>
                            </li>
                            <li>
                                <a href="teacher/grade.php" class="nav-link text-white">
                                    <i class="fs-5 bi bi-journal-text"></i>
                                    <span class="ms-2">Insert Grade</span>
                                </a>
                            </li>
                            <li>
                                <a href="teacher/change_password.php" class="nav-link text-white">
                                    <i class="fs-5 bi bi-key-fill"></i>
                                    <span class="ms-2">Change Password</span>
                                </a>
                            </li>
                        </ul>
                    <?php endif; ?>

                    <hr class="text-white">
                    <?php
                    $logout_link = ($_SESSION['user_role'] == "Administrator") ? "admin/auth/signin.php" 
                        : ($_SESSION['user_role'] == 'Student' ? "student/auth/signin.php" : "teacher/auth/signin.php" );
                    ?>
                    <a href="<?= $logout_link ?>" class="btn btn-danger w-100 mt-2">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                        <span class="ms-2">Logout</span>
                    </a>
                </div>
            </div>

            <div class="col py-3">
                <?PHP
                if($_SESSION['user_role'] == "Administrator"){
                    $username = $_SESSION['user_name'];
                }else if ($_SESSION['user_role'] == "Student"){
                    $sql = $conn->query("SELECT name FROM students WHERE student_no = '$_SESSION[user_name]'");
                    while($row=$sql->fetch_assoc()){
                        $username = $row['name'];
                    }
                }else if ($_SESSION['user_role'] = "Teacher"){
                    $username = $_SESSION['user_name'];
                }
                
                ?>
                <h3>Welcome to <?= $_SESSION['user_role'] ?> Portal, <?= $username ?></h3>
                <p class="lead">
                    In this student portal program, you can manipulate the information within the school. Create, Edit, and Delete information.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
