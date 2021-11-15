<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/index.php" class="brand-link">
      <img src="<?php echo $_SESSION["itac_path_configuration"]; ?>/dist/img/logo.png" alt="ITAC Logo" class="brand-image img-rounded elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ITAC Beauty</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <?php
          if($_SESSION["itac_ad_image_path"] != ""){
            echo '<img src="'.$_SESSION["itac_path_configuration"].$_SESSION["itac_ad_image_path"].'" class="img-circle elevation-2" alt="User Image" style="width:35px; height: 35px;">';
          } else {
            echo ' <img src="'.$_SESSION["itac_path_configuration"].'../dist/img/avatar2.png" class="img-circle elevation-2" alt="User Image">';
          }
          ?>
         
        </div>
        <div class="info">
          <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/profile.php?id=<?php echo $_SESSION["itac_user_id"]?>" class="d-block"><?php echo $_SESSION["itac_username"]  ?></a>
        </div>
        <div class="info" style="text-align:right;">
          <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/includes/logout.php" class='btn btn-sm logoutBtn'><i class="fas fa-sign-out-alt fa-lg"></i></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-header">Admin</li>
          <li class="nav-item">
            <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/index.php" class="nav-link <?php if($page == "Dashboard"){ echo "active"; } ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/tutor.php" class="nav-link <?php if($page == "Tutor"){ echo "active"; } ?>">
              <i class="nav-icon far fas fa-chalkboard-teacher"></i>
              <p>
                Tutor
                <span class="badge badge-info right"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/student.php" class="nav-link <?php if($page == "Student"){ echo "active"; } ?>">
              <i class="nav-icon far fas fa-user-graduate"></i>
              <p>
                Student
                <span class="badge badge-info right"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/batch.php" class="nav-link <?php if($page == "Batch"){ echo "active"; } ?>">
              <i class="nav-icon far fas fa-book"></i>
              <p>
                Batch
                <span class="badge badge-info right"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/course.php" class="nav-link <?php if($page == "Course"){ echo "active"; } ?>">
              <i class="nav-icon far fas fa-book-open"></i>
              <p>
                Course
                <span class="badge badge-info right"></span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/attendance.php" class="nav-link <?php if($page == "Attendance"){ echo "active"; } ?>">
              <i class="nav-icon far fas fa-clipboard-check"></i>
              <p>
                Attendance
                <span class="badge badge-info right"></span>
              </p>
            </a>
          </li>
          <!-- ./Real navigation bar -->
          <li class="nav-header">Evaluations</li>
          <li class="nav-item  <?php if($page == "Daily Evaluation" || $page == "Semester Evaluation"){ echo "menu-open"; } ?>">
            <a href="#" class="nav-link <?php if($page == "Daily Evaluation" || $page == "Semester Evaluation"){ echo "active"; } ?>">
              <i class="nav-icon fas fa-percent"></i>
              <p>
                Evaluation
                <span class="badge badge-info right"></span>
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/evaluation.php?eva_type=daily" class="nav-link <?php if($page == "Daily Evaluation"){ echo "active"; } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daily Evaluation</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/evaluation.php?eva_type=semester" class="nav-link <?php if($page == "Semester Evaluation"){ echo "active"; } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Semester Evaluation</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
