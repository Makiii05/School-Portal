<?PHP
include("../sql/messages.php");
?>
<div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
  <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
  <?PHP if($_SESSION['user_role'] == 'Administrator'): ?>
    <!-- Header -->
    <a href="index.php" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <span class="fs-5 fw-bold d-none d-sm-inline">Admin Portal</span>
    </a>

    <!-- Navigation -->
    <ul class="nav nav-pills flex-column mb-auto w-100" id="menu">
      <li class="nav-item">
        <a href="../index.php" class="nav-link text-white">
          <i class="fs-5 bi-house"></i>
          <span class="ms-2 d-none d-sm-inline">Home</span>
        </a>
      </li>
      <li>
        <a href="#submenu1" data-bs-toggle="collapse" class="nav-link text-white">
          <i class="fs-5 bi-speedometer2"></i>
          <span class="ms-2 d-none d-sm-inline">List</span>
        </a>
        <ul class="collapse show nav flex-column ms-4" id="submenu1" data-bs-parent="#menu">
          <li>
            <a href="student.php" class="nav-link text-white">
              <i class="bi bi-people"></i> 
              <span class="ms-2 d-none d-sm-inline">Students</span>
            </a>
          </li>
          <li>
            <a href="courses.php" class="nav-link text-white">
              <i class="bi bi-journal-bookmark"></i> 
              <span class="ms-2 d-none d-sm-inline">Courses</span>
            </a>
          </li>
        </ul>
      </li>
      <li>
        <a href="enroll.php" class="nav-link text-white">
          <i class="bi bi-person-plus-fill fs-5"></i>
          <span class="ms-2 d-none d-sm-inline">Enroll Student</span>
        </a>
      </li>
      <li>
        <a href="grade.php" class="nav-link text-white">
          <i class="bi bi-file-earmark-plus-fill"></i>
          <span class="ms-2 d-none d-sm-inline">Insert Grade</span>
        </a>
      </li>
      <li>
        <a href="../print/p_stud.php" target="_blank" class="nav-link text-white">
          <i class="fs-5 bi-table"></i>
          <span class="ms-2 d-none d-sm-inline">Report</span>
        </a>
      </li>
    </ul>
  <?PHP else: ?>
    
    <!-- Header -->
    <a href="index.php" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <span class="fs-5 fw-bold d-none d-sm-inline">Admin Portal</span>
    </a>
    <ul class="nav nav-pills flex-column mb-auto w-100" id="menu">
      <li class="nav-item">
        <a href="../index.php" class="nav-link text-white">
          <i class="fs-5 bi bi-house-door-fill"></i>
          <span class="ms-2 d-none d-sm-inline">Home</span>
        </a>
      </li>
      <li>
        <a href="grade.php" class="nav-link text-white">
          <i class="fs-5 bi bi-journal-text"></i>
          <span class="ms-2 d-none d-sm-inline">View Grade</span>
        </a>
      </li>
      <li>
        <a href="change_password.php" class="nav-link text-white">
          <i class="fs-5 bi bi-key-fill"></i>
          <span class="ms-2 d-none d-sm-inline">Change Password</span>
        </a>
      </li>
    </ul>


  <?PHP endif; ?>
    <!-- Divider -->
    <hr class="text-white w-100">

    <!-- Logout Button -->
    <div class="pb-3 w-100">
      <a href="../auth/signin.php" class="btn btn-danger w-100">
        <i class="bi bi-box-arrow-right"></i>
        <span class="ms-2 d-none d-sm-inline">Logout</span>
      </a>
    </div>

  </div>
</div>
