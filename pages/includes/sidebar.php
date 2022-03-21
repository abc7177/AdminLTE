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
          if(isset($_GET["asStudent"])){ 
            if($_SESSION["itac_ad_image_path2"] != ""){
              echo '<img src="'.$_SESSION["itac_path_configuration2"].$_SESSION["itac_ad_image_path2"].'" class="img-circle elevation-2" alt="User Image" style="width:35px; height: 35px;">';
            } else {
              echo ' <img src="'.$_SESSION["itac_path_configuration2"].'/dist/img/avatar2.png" class="img-circle elevation-2" alt="User Image">';
            }
          } else if(isset($_GET["asTutor"])){ 
            if($_SESSION["itac_ad_image_path3"] != ""){
              echo '<img src="'.$_SESSION["itac_path_configuration3"].$_SESSION["itac_ad_image_path3"].'" class="img-circle elevation-2" alt="User Image" style="width:35px; height: 35px;">';
            } else {
              echo ' <img src="'.$_SESSION["itac_path_configuration3"].'/dist/img/avatar2.png" class="img-circle elevation-2" alt="User Image">';
            }
          } else {
            if($_SESSION["itac_ad_image_path"] != ""){
              echo '<img src="'.$_SESSION["itac_path_configuration"].$_SESSION["itac_ad_image_path"].'" class="img-circle elevation-2" alt="User Image" style="width:35px; height: 35px;">';
            } else {
              echo ' <img src="'.$_SESSION["itac_path_configuration"].'/dist/img/avatar2.png" class="img-circle elevation-2" alt="User Image">';
            }
          }
          ?>
         
        </div>
        <div class="info">
          <?php
          if(isset($_GET["asStudent"])){ 
          ?>
            <a href="<?php echo $_SESSION["itac_path_configuration2"]; ?>/pages/profile.php?asStudent=<?php echo $_GET["asStudent"]?>&id=<?php echo $_GET["asStudent"]?>" class="d-block"><?php echo $_SESSION["itac_account_name2"]  ?></a>
          <?php
          } else if (isset($_GET["asTutor"])){
          ?>
            <a href="<?php echo $_SESSION["itac_path_configuration3"]; ?>/pages/profile.php?asTutor=<?php echo $_GET["asTutor"]?>&id=<?php echo $_GET["asTutor"]?>" class="d-block"><?php echo $_SESSION["itac_account_name3"]  ?></a>
            <?php
          } else {
          ?>
            <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/profile.php?id=<?php echo $_SESSION["itac_user_id"]?>" class="d-block"><?php echo $_SESSION["itac_account_name"]  ?></a>
          <?php
          }
          ?>
        </div>
        <div class="info" style="text-align:right;">
          <?php
          if(!isset($_GET["asStudent"]) && !isset($_GET["asTutor"])){ 
          ?>
            <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/includes/logout.php" class='btn btn-sm logoutBtn'><i class="fas fa-sign-out-alt fa-lg"></i></a>
          <?php
          }
          ?>
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
            <?php
            if(isset($_GET["asStudent"])){
            ?>
              <a href="<?php echo $_SESSION["itac_path_configuration2"]; ?>/index.php?asStudent=<?php echo $_GET["asStudent"];?>" class="nav-link <?php if($page == "Dashboard"){ echo "active"; } ?>">
            <?php
            } else if(isset($_GET["asTutor"])){
            ?>
              <a href="<?php echo $_SESSION["itac_path_configuration3"]; ?>/index.php?asTutor=<?php echo $_GET["asTutor"];?>" class="nav-link <?php if($page == "Dashboard"){ echo "active"; } ?>">
            <?php
            } else {
            ?>
            <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/index.php" class="nav-link <?php if($page == "Dashboard"){ echo "active"; } ?>">
            <?php
            } 
            ?>
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <?php if($_SESSION["itac_account_level"] < 1  && !isset($_GET["asStudent"]) && !isset($_GET["asTutor"])) { // Admin only?> 
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
          <?php } // Admin only?>
          <li class="nav-item">
            <?php
            if(isset($_GET["asStudent"])){
            ?>
              <a href="<?php echo $_SESSION["itac_path_configuration2"]; ?>/pages/course.php?asStudent=<?php echo $_GET["asStudent"];?>" class="nav-link <?php if($page == "Course"){ echo "active"; } ?>">
            <?php
            } else if(isset($_GET["asTutor"])){
            ?>
              <a href="<?php echo $_SESSION["itac_path_configuration3"]; ?>/pages/course.php?asTutor=<?php echo $_GET["asTutor"];?>" class="nav-link <?php if($page == "Course"){ echo "active"; } ?>">
            <?php
            } else {
            ?>
              <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/course.php" class="nav-link <?php if($page == "Course"){ echo "active"; } ?>">
            <?php
            } 
            ?>
              <i class="nav-icon far fas fa-book-open"></i>
              <p>
                Course
                <span class="badge badge-info right"></span>
              </p>
            </a>
          </li>
          
          <li class="nav-item">
            <?php
            if(isset($_GET["asStudent"])){
            ?>
              <a href="<?php echo $_SESSION["itac_path_configuration2"]; ?>/pages/attendance.php?asStudent=<?php echo $_GET["asStudent"];?>" class="nav-link <?php if($page == "Attendance"){ echo "active"; } ?>">
            <?php
            } else if(isset($_GET["asTutor"])){
            ?>
              <a href="<?php echo $_SESSION["itac_path_configuration3"]; ?>/pages/attendance.php?asTutor=<?php echo $_GET["asTutor"];?>" class="nav-link <?php if($page == "Attendance"){ echo "active"; } ?>">
            <?php
            } else {
            ?>
              <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/attendance.php" class="nav-link <?php if($page == "Attendance"){ echo "active"; } ?>">
            <?php
            } 
            ?>
              <i class="nav-icon far fas fa-clipboard-check"></i>
              <p>
                Attendance
                <span class="badge badge-info right"></span>
              </p>
            </a>
          </li>
          <?php if($_SESSION["itac_account_level"] < 2 && !isset($_GET["asStudent"])) { // Tutor and Admin only?> 
          <li class="nav-item">
            <?php
              if(!isset($_GET["asTutor"])){
            ?>
                <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/scoring.php" class="nav-link <?php if($page == "Scoring"){ echo "active"; } ?>">
            <?php
              } else {
            ?>
                <a href="<?php echo $_SESSION["itac_path_configuration3"]; ?>/pages/scoring.php?asTutor=<?php echo $_GET["asTutor"];?>" class="nav-link <?php if($page == "Scoring"){ echo "active"; } ?>">
            <?php
              }
            ?>
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Scoring
                <span class="badge badge-info right"></span>
              </p>
            </a>
          </li>
          <?php } // Tutor and Admin only?>
          <?php if($_SESSION["itac_account_level"] >= 2 || isset($_GET["asStudent"])) { // Student only?> 
          <li class="nav-item">
            <?php
            if(isset($_GET["asStudent"])){
            ?>
              <a href="<?php echo $_SESSION["itac_path_configuration2"]; ?>/pages/scoring2.php?asStudent=<?php echo $_GET["asStudent"];?>" class="nav-link <?php if($page == "Scoring2"){ echo "active"; } ?>">
            <?php
            } else {
            ?>
              <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/scoring2.php" class="nav-link <?php if($page == "Scoring2"){ echo "active"; } ?>">
            <?php
            } 
            ?>
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Final Scoring
                <span class="badge badge-info right"></span>
              </p>
            </a>
          </li>
          <?php } // Student only?>
          <?php if($_SESSION["itac_account_level"] < 2 && !isset($_GET["asStudent"])) { // Tutor and Admin only?> 
          <li class="nav-header">Evaluations</li>
          <li class="nav-item  <?php if($page == "Daily Evaluation" || $page == "Semester Evaluation" || $page == "Final Evaluation"){ echo "menu-open"; } ?>">
            <a href="#" class="nav-link <?php if($page == "Daily Evaluation" || $page == "Semester Evaluation" || $page == "Final Evaluation"){ echo "active"; } ?>">
              <i class="nav-icon fas fa-percent"></i>
              <p>
                Evaluation
                <span class="badge badge-info right"></span>
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <?php if(!isset($_GET["asTutor"])) { ?> 
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
                <li class="nav-item">
                  <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/evaluation.php?eva_type=final" class="nav-link <?php if($page == "Final Evaluation"){ echo "active"; } ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Final Evaluation</p>
                  </a>
                </li>
              </ul>
            <?php } else { ?>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/evaluation.php?asTutor=<?php echo $_GET["asTutor"];?>&eva_type=daily" class="nav-link <?php if($page == "Daily Evaluation"){ echo "active"; } ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Daily Evaluation</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/evaluation.php?asTutor=<?php echo $_GET["asTutor"];?>&eva_type=semester" class="nav-link <?php if($page == "Semester Evaluation"){ echo "active"; } ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Semester Evaluation</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?php echo $_SESSION["itac_path_configuration"]; ?>/pages/evaluation.php?asTutor=<?php echo $_GET["asTutor"];?>&eva_type=final" class="nav-link <?php if($page == "Final Evaluation"){ echo "active"; } ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Final Evaluation</p>
                  </a>
                </li>
              </ul>
            <?php } ?>
          </li>
          <?php } // Tutor and Admin only?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
