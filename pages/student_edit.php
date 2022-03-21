<?php
    include("../docs/_includes/connection.php");
    $page = "Student";

    if (!(isset($_SESSION["itac_user_id"]))) {
        echo "<script>window.location='login.php'</script>";
        exit;
    }

    // if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
    // 	echo "<script>alert('You have no permission to access this module.')</script>";
    // 	echo "<script>window.location='index.php'</script>";	
    // 	exit;
    // }

    $query0 = "SELECT account.*, batch.batch_code, batch.batch_id FROM account 
			LEFT JOIN batch_sub ON batch_sub.account_id = account.account_id 
			LEFT JOIN batch ON batch_sub.batch_id = batch.batch_id 
			WHERE account.account_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
    $result0 = mysqli_query($con,$query0);
	//echo $query0;

    if(!($row0 = mysqli_fetch_assoc($result0))){
        echo "<script>alert('student was not found.')</script>";
        echo "<script>window.location= 'student.php'</script>";
        exit;
    }

    if (isset($_POST["studentName"])) {
            $student_permission = "";
            if (isset($_POST["permission"])) {
                foreach ($_POST["permission"] as $selected_permission) {
                    $student_permission .= $selected_permission . ",";
                }
                if ($student_permission != "") {
                    $student_permission = substr($staff_permission, 0, -1);
                }
            }

            if(isset($_POST["studentStatus"])){
                $studentStatus = 'Active';
            } else {
                $studentStatus = 'Inactive';
            }

			if(isset($_POST["studentFinancing"])){
				$studentFinancing = 'Loan';
			} else {
				$studentFinancing = 'Cash';
			}
	
			if(isset($_POST["studentHostel"])){
				$studentHostel = 'Yes';
			} else {
				$studentHostel = 'No';
			}

            if($_POST["account_password"] != ""){
                $hashedPassword = password_hash(mysqli_real_escape_string($con,$_POST["account_password"]), PASSWORD_DEFAULT);
                $passwordQuery = "account_password = '" . $hashedPassword . "',";
            } else {
                $hashedPassword = "";
                $passwordQuery = "";
            }

            
            $query = "UPDATE account SET 
                        account_name = '" . mysqli_real_escape_string($con, $_POST["studentName"]) . "', 
                        account_ic = '" . mysqli_real_escape_string($con, $_POST["studentICNumber"]) . "', 
                        account_dob = '" . mysqli_real_escape_string($con, $_POST["studentDOB"]) . "', 
                        account_email = '" . mysqli_real_escape_string($con, $_POST["studentEmail"]) . "', 
                        account_phone_no = '" . mysqli_real_escape_string($con, $_POST["studentPhoneNumber"]) . "', 
                        account_e_name = '" . mysqli_real_escape_string($con, $_POST["studentEmergencyName"]) . "', 
						account_e_email = '" . mysqli_real_escape_string($con, $_POST["studentEmergencyEmail"]) . "', 
                        account_e_phone_no = '" . mysqli_real_escape_string($con, $_POST["studentEmergencyPhone"]) . "', 
						account_e_name_2 = '" . mysqli_real_escape_string($con, $_POST["studentEmergencyName2"]) . "', 
						account_e_email_2 = '" . mysqli_real_escape_string($con, $_POST["studentEmergencyEmail2"]) . "', 
						account_e_phone_no_2 = '" . mysqli_real_escape_string($con, $_POST["studentEmergencyPhone2"]) . "', 
                        account_address_line1 = '" . mysqli_real_escape_string($con, $_POST["studentAddressLine1"]) . "', 
                        account_address_line2 = '" . mysqli_real_escape_string($con, $_POST["studentAddressLine2"]) . "', 
                        account_postcode = '" . mysqli_real_escape_string($con, $_POST["studentPostcode"]) . "', ";

            if(isset($_POST["studentState"]))	
				$query .= "account_state = '" . mysqli_real_escape_string($con, $_POST["studentState"]) . "', ";
			
			if(isset($_POST["studentCountry"]))		
				$query .= "account_country = '" . mysqli_real_escape_string($con, $_POST["studentCountry"]) . "', ";
            	$query .= "account_enroll_date = '" . mysqli_real_escape_string($con, $_POST["studentEnrollDate"]) . "', 
                        account_status = '" . $studentStatus . "', 
						account_hostel = '" . $studentHostel . "', 
						account_financing = '" . $studentFinancing . "', 
                        account_remark = '" . mysqli_real_escape_string($con, $_POST["studentDescription"]) . "', 
                        $passwordQuery
                        account_level_id = '3' 
                       	WHERE account_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
            $result = mysqli_query($con, $query);

			$query = "SELECT * FROM batch_sub WHERE batch_id = '". mysqli_real_escape_string($con, $row0["batch_id"]) ."' 
					AND account_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
			$result = mysqli_query($con, $query);
			// echo $query;

			if(!($row = mysqli_fetch_assoc($result))){ // No batch registered previously
				$query = "INSERT INTO batch_sub SET 
					batch_id = '" . mysqli_real_escape_string($con, $_POST["studentBatchNumber"]) . "', 				
					account_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
			} else {	// Batch registered previously
				$query = "UPDATE batch_sub SET 
						batch_id = '" . mysqli_real_escape_string($con, $_POST["studentBatchNumber"]) . "' 
						WHERE batch_sub_id = '". mysqli_real_escape_string($con, $row["batch_sub_id"]) ."'";
			}
			// echo $query;
			$result = mysqli_query($con, $query);

            echo '<script>localStorage.setItem("Updated",1)</script>';	// Successful updated flag.
            echo "<script>window.location='student_edit.php?id=".mysqli_real_escape_string($con,$_GET["id"])."'</script>";	
            exit;
    }
    
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ITAC | Student Information</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="../dist/css/adminlte.min.css">
	<!-- Favicon -->
	<link rel="shortcut icon" href="../dist/img/logo-200x200.png" />
    <!-- Toastr -->
    <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
	<!-- Customize style -->
	<link rel="stylesheet" href="../dist/css/customize.css">
</head>

<body class="hold-transition sidebar-mini">
	<!-- Site wrapper -->
	<div class="wrapper">
		<!-- Top Navbar Container -->
		<?php include("includes/navbar.php"); ?>

		<!-- Main Sidebar Container -->
		<?php include('includes/sidebar.php'); ?>
		<!-- /.Main Sidebar Container -->

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1>Student Information</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../index.php">Home</a></li>
								<li class="breadcrumb-item"><a href="student.php">Student</a></li>
								<li class="breadcrumb-item active">Student Registration</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			<section class="content">
				<form name="add_student" id="add_student" class="col-lg-12" method="POST">
					<div class="row">
						<div class="col-md-5">
							<div class="card card-primary collapsed-card">
								<div class="card-header">
									<h3 class="card-title">General</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
											<i class="fas fa-plus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="form-group">
										<label for="inputName">Name</label>
										<input type="hidden" name="studentId" id="studentId" class="form-control" value="<?php echo htmlspecialchars($row0["account_id"]) ?>">
										<input type="text" name="studentName" id="studentName" class="form-control"  value="<?php echo htmlspecialchars($row0["account_name"]) ?>">
									</div>
									<div class="form-group">
										<label for="inputName">I/C Number</label>
										<input type="text" name="studentICNumber" id="studentICNumber" class="form-control" value="<?php echo htmlspecialchars($row0["account_ic"]) ?>">
									</div>
									<div class="form-group">
										<label for="inputName">Date Of Birth</label>
										<div class="input-group">
											<?php
											$datetime = '';
											if($row0["account_dob"] != "0000-00-00"){
												$datetime = date('Y-m-d', strtotime($row0["account_dob"]));
											}
											?>
											<input type="date" name="studentDOB" id="studentDOB" class="form-control" value="<?php echo $datetime ?>">
											<div class="input-group-prepend">
												<!-- <span class="input-group-text"><i class="far fa-calendar-alt"></i></span> -->
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="inputName">Email</label>
										<input type="text" name="studentEmail" id="studentEmail" class="form-control" value="<?php echo htmlspecialchars($row0["account_email"]) ?>">
									</div>
									<div class="form-group">
										<label for="inputName">Phone Number</label>
										<input type="text" name="studentPhoneNumber" id="studentPhoneNumber" class="form-control" value="<?php echo htmlspecialchars($row0["account_phone_no"]) ?>">
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<label for="inputName">Parent / Emergency Contact 1</label>
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentEmergencyName" id="studentEmergencyName" class="form-control" placeholder="Name" value="<?php echo htmlspecialchars($row0["account_e_name"]) ?>">
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentEmergencyEmail" id="studentEmergencyEmail" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($row0["account_e_email"]) ?>">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
												<input type="text" name="studentEmergencyPhone" id="studentEmergencyPhone" class="form-control" placeholder="Phone" value="<?php echo htmlspecialchars($row0["account_e_phone_no"]) ?>">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<label for="inputName">Parent / Emergency Contact 2</label>
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentEmergencyName2" id="studentEmergencyName2" class="form-control" placeholder="Name" value="<?php echo htmlspecialchars($row0["account_e_name_2"]) ?>">
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentEmergencyEmail2" id="studentEmergencyEmail2" class="form-control" placeholder="Email" value="<?php echo htmlspecialchars($row0["account_e_email_2"]) ?>">													
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentEmergencyPhone2" id="studentEmergencyPhone2" class="form-control" placeholder="Phone" value="<?php echo htmlspecialchars($row0["account_e_phone_no_2"]) ?>">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="inputName">Address</label>
										<input type="text" name="studentAddressLine1" id="studentAddressLine1" class="form-control" placeholder="Line 1" value="<?php echo htmlspecialchars($row0["account_address_line1"]) ?>">
									</div>
									<div class="form-group">
										<input type="text" name="studentAddressLine2" id="studentAddressLine2" class="form-control" placeholder="Line 2" value="<?php echo htmlspecialchars($row0["account_address_line2"]) ?>">
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
													<input type="text" name="studentPostcode" id="studentPostcode" class="form-control" placeholder="Postcode" value="<?php echo htmlspecialchars($row0["account_postcode"]) ?>">
												</div>
											</div>
											<div class="form-group col-lg-6">
												<div class="input-group">
                                                    <select class="form-control" name="studentState" id="studentState">
                                                        <?php
                                                            echo '<option value="" selected disabled>----- State -----</option>';
                                                            $query = "select * from state";
                                                            $result = mysqli_query($con, $query);
                                                            while($row = mysqli_fetch_assoc($result)){
                                                                echo '<option value="'.$row["state_id"].'" ';
                                                                if($row0["account_state"] == $row["state_id"])
																echo 'selected ';
                                                                echo '>'.$row["state_name"].'</option>';
                                                            }
                                                        ?>
                                                    </select>													
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="form-group col-lg-6">
												<div class="input-group">
                                                    <select class="form-control" name="studentCountry" id="studentCountry">
                                                    <?php
                                                        echo '<option value="" selected disabled>----- Country -----</option>';
                                                        $query = "select * from country";
                                                        $result = mysqli_query($con, $query);
                                                        while($row = mysqli_fetch_assoc($result)){
                                                            echo '<option value="'.$row["country_id"].'" ';
                                                            if($row0["account_country"] == $row["country_id"])
																echo 'selected ';
                                                            echo '>'.$row["country_name"].'</option>';
                                                        }
                                                    ?>
                                                    </select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<label for="inputName">Date Of Enrollment</label>
												<div class="input-group">
													<input type="date" name="studentEnrollDate" id="studentEnrollDate" class="form-control" value="<?php echo date('Y-m-d', strtotime($row0["account_enroll_date"])) ?>">
													<div class="input-group-prepend">
														<!-- <span class="input-group-text"><i class="far fa-calendar-alt"></i></span> -->
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<label for="inputName">Batch Number</label>
												<div class="input-group">
													<!-- <input type="text" name="studentBatchNumber" id="studentBatchNumber" class="form-control" readonly value="<?php echo $row0["batch_code"]; ?>">													 -->
											
                                                    <select class="form-control" name="studentBatchNumber" id="studentBatchNumber">
                                                    <?php
                                                        echo '<option value="" selected>----- Batch -----</option>';
                                                        $query = "select * from batch";
                                                        $result = mysqli_query($con, $query);
                                                        while($row = mysqli_fetch_assoc($result)){
                                                            echo '<option value="'.$row["batch_id"].'" ';
                                                            if($row0["batch_id"] == $row["batch_id"])
																echo 'selected ';
                                                            echo '>'.$row["batch_code"].'</option>';
                                                        }
                                                    ?>
                                                    </select>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<label for="studentHostel">Hostel &nbsp;</label>
												<div class="input-group">
													<input type="checkbox" name="studentHostel" id="studentHostel" 
													<?php 
                                                        if($row0["account_hostel"] == "Yes")
                                                            echo "checked"; 
                                                    ?> 
													data-bootstrap-switch data-off-text="No" data-off-color="danger" data-on-text="Yes" data-on-color="success" data-size="medium">
												</div>
											</div>
											<div class="form-group col-lg-6">
												<label for="studentFinancing">Financing &nbsp;</label>
												<div class="input-group">
													<input type="checkbox" name="studentFinancing" id="studentFinancing" 
													<?php 
                                                        if($row0["account_financing"] == "Loan")
                                                            echo "checked"; 
                                                    ?> 
													data-bootstrap-switch data-off-text="Cash" data-off-color="yellow" data-on-text="Loan" data-on-color="blue" data-size="medium">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px;">
										<div class="row">
											<div class="form-group col-lg-6">
												<label for="studentStatus">Status &nbsp;</label>
												<div class="input-group">
													<input type="checkbox" name="studentStatus" id="studentStatus" 
                                                    <?php 
                                                        if($row0["account_status"] == "Active")
                                                            echo "checked"; 
                                                    ?> 
                                                    data-bootstrap-switch data-off-text="Inactive" data-off-color="danger" data-on-text="Active" data-on-color="success" data-size="medium">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="studentDescription">Description / Remarks</label>
										<textarea name="studentDescription" id="studentDescription" class="form-control" rows="4" ></textarea>
									</div>
								</div>
								<!-- /.card-body -->
							</div>
							<!-- /.card -->
						</div>
						<div class="col-md-7">
							<div class="card card-info  collapsed-card">
								<div class="card-header">
									<h3 class="card-title">Login Information</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
											<i class="fas fa-plus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="form-group">
										<label for="studentUsername">Username</label>
										<div class="input-group">
											<input type="text" name="studentUsername" id="studentUsername" class="form-control" value="<?php echo htmlspecialchars($row0["account_username"]) ?>" disabled>
											<div class="input-group-append">
												<div class="input-group-text">
													<span class="fas fa-user"></span>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label for="password">Password</label>
										<div class="input-group">
											<input type="password" name="account_password" id="account_password" class="form-control">
											<div class="input-group-append">
												<div class="input-group-text">
													<span class="fas fa-lock"></span>
												</div>
											</div>	
										</div>
										
									</div>
									<div class="form-group">
										<label for="passwordRetype">Retype Password</label>
										<div class="input-group">
											<input type="password" name="studentPasswordRetype" id="studentPasswordRetype" class="form-control">
											<div class="input-group-append">
												<div class="input-group-text">
													<span class="fas fa-lock"></span>
												</div>
											</div>
										</div>
									</div>
									<!-- /.card-body -->
								</div>
								<!-- /.card -->
								
							</div>							
						</div>
						<div class="col-md-12">
						<div class="card card-secondary">
								<div class="card-header">
									<h3 class="card-title">Course Information</h3>

									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									 <!-- /.row -->
									<div class="row">
										
										<div class="col-12">
										<div class="card">
										<div class="card-header bg-success">
											Enrolled Course
										</div>
										<!-- ./card-header -->
										<div class="card-body p-0">
											<div style='overflow:auto; width:100%;position:relative;'>
												<table id="courseTable" class="table table-borderless table-hover">
												<tbody>
												<tr>
													<td colspan='6'><button type="button" id="addCourse" class="btn btn-sm" style="background-color:transparent" ><i class="fas fa-plus"></i> Add New Course</button></td>
												</tr>
												<?php
													$query1 = "SELECT * FROM student_course 
																LEFT JOIN course
																ON student_course.course_id = course.course_id 
																LEFT JOIN course_group 
																ON student_course.course_group_id = course_group.group_id 
																WHERE student_course.account_id = '".mysqli_real_escape_string($con,$_GET["id"])."'";
													$result1 = mysqli_query($con, $query1);

													if(mysqli_num_rows($result1) != 0){
														echo "<tr class='' id='enrolledCourseHeader'><td><div style='width:150px;'>Code</div></td><td><div style='width:400px;'>Name</div></td><td><div style='width:150px;'>Group</div></td><td><div style='width:150px;text-align: center;'>Status</div></td><td ><div style='width:100px;text-align: center;'>Action</td></tr>";
													}
													while($row1 = mysqli_fetch_assoc($result1)){
														echo "<tr><td>".
														"<select class='form-control form-control-sm courseCode' style='appearance: none' name='courseCode' >";
														echo '"<option value=\'0\' selected>----------</option>"+';		
														$query = "select course_id, course_code from course";
														$result = mysqli_query($con, $query);
														while($row = mysqli_fetch_assoc($result)){
															echo	'"<option value=\''.$row["course_id"].'\'';
															if($row1["course_id"] == $row["course_id"])
																echo "selected";
															echo '>'.$row["course_code"].'</option>"+';
														}			
														echo "</select></td>".
														"<td><input type='hidden' name='scId' class='form-control form-control-sm' value='".$row1["sc_id"]."'>".
														"<input type='text' name='courseName' class='form-control form-control-sm'  value='".$row1["course_name"]."' readonly></td>".
														"<td>".
														"<select name='courseGroup' class='form-control form-control-sm courseGroup' style='appearance: none'>".
														"<option value=\'0\' selected>----------</option>";
														$query = "select group_id, group_name from course_group";
														$result = mysqli_query($con, $query);
														while($row = mysqli_fetch_assoc($result)){
															echo	'"<option value=\''.$row["group_id"].'\'';
															if($row1["group_id"] == $row["group_id"])
																echo "selected";
															echo '>'.$row["group_name"].'</option>"+';
														}		
														echo "</select></td>".												
				
														"<td style='text-align: center;'>".
														"<input type='text' name='courseStatus' class='form-control form-control-sm'  value='".$row1["course_status"]."' readonly></td>".
														"<td style='text-align: center;background-color: transparent;'><button type='button' class='btn btn-sm courseDelBtn red-icon'>".
														"<i class='fa fa-trash-alt'></i></button><button class='btn btn-sm courseSaveBtn green-icon'><i class='fas fa-check'></i></button>".
														"</td></tr>";
													}
												?>
													
												</tbody>
												</table>
											</div>
										</div>
										<!-- /.card-body -->
										
										</div>
										<div class="form-group">
											<div class="row">
												<div class="form-group col-lg-6">
													<div class="input-group">
														<button type="button" class="btn btn-danger btn-block" onclick="window.location='student.php'"><i class="fa fa-arrow-left"></i> BACK</button>
													</div>
												</div>
												<div class="form-group col-lg-6">
													<div class="input-group">
														<button type="submit" id="submitstudentForm" class="btn btn-primary btn-block">UPDATE <i class="fa fa-arrow-right"></i></button>
													</div>
												</div>
											</div>
										</div>
										<!-- /.card -->
									</div>
									</div>
									<!-- /.row -->
									<!-- /.card-body -->
								</div>
								<!-- /.card -->
							</div>
						</div>
					</div>
				</form>
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

		<!-- Footer Container -->
		<?php include('includes/footer.php'); ?>
		<!-- /.Footer Container -->

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
		</aside>
		<!-- /.control-sidebar -->
	</div>
	<!-- ./wrapper -->

	<!-- jQuery -->
	<script src="../plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="../dist/js/adminlte.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="../dist/js/demo.js"></script>
	<!-- InputMask -->
	<script src="../plugins/moment/moment.min.js"></script>
	<script src="../plugins/inputmask/jquery.inputmask.min.js"></script>
	<!-- Bootstrap Switch -->
	<script src="../plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
	<!-- jquery-validation -->
	<script src="../plugins/jquery-validation/jquery.validate.min.js"></script>
	<script src="../plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- Toastr -->
    <script src="../plugins/toastr/toastr.min.js"></script>
	<script>

		function getCourseInfo(thisObj){	
			jQuery.ajax({
				type: "POST",
				url: "../pages/includes/get_course.php",
				data: {id: thisObj.val()},
				datatype: "json",
				success: function(data, textStatus, xhr) {
					console.log(xhr.responseText);
					data = JSON.parse(xhr.responseText);

					for (var i = 0, len = data.length; i < len; i++) {
						thisObj.parent().parent().find("input[name='courseName']").val(data[i].name);
						thisObj.parent().parent().find("input[name='courseStartDate']").val(data[i].startDate);
						thisObj.parent().parent().find("input[name='courseStatus']").val(data[i].status);
					}
				}
			});
		}

		function getCourseGroupInfo(thisObj){	
			jQuery.ajax({
				type: "POST",
				url: "../pages/includes/get_course_group.php",
				data: {id: thisObj.val()},
				datatype: "json",
				success: function(data, textStatus, xhr) {
					console.log(xhr.responseText);
					data = JSON.parse(xhr.responseText);

					var select = thisObj.parent().parent().find("select[name='courseGroup']");
					var options = select.prop('options');
					$('option', select).remove();
					select.prop('disabled', false);
					options[options.length] = new Option("----------", 0);

					for (var i = 0, len = data.length; i < len; i++) {
						var id = data[i].id;
						var desc = data[i].name;
						options[options.length] = new Option(desc, id);
					}
				}
			});
		}
        
		$(function() {

			if(localStorage.getItem("Updated")=="1"){
                toastr.success('Student information has been updated successfully.');
                localStorage.clear();
            }

			if(localStorage.getItem("Added")=="1"){
                toastr.success('Student has been added successfully.');
                localStorage.clear();
            }
			
			$(document).on('change','.courseCode',function(){
				valueCurrent = $(this).val();

				if(valueCurrent != 0){
					getCourseInfo($(this));
					getCourseGroupInfo($(this));
				} else {
					$(this).parent().parent().find("input[name='courseName']").val("");
					$(this).parent().parent().find("input[name='courseStartDate']").val("");
					$(this).parent().parent().find("input[name='courseStatus']").val("");
					$(this).parent().parent().find("select[name='courseGroup']").val("0");
					$(this).parent().parent().find("select[name='courseGroup']").prop('disabled', true);
				}
			});
			
			$('#datemask').inputmask('dd/mm/yyyy', {
				'placeholder': 'dd/mm/yyyy'
			})

			$('[data-mask]').inputmask()

			$("input[data-bootstrap-switch]").each(function() {
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			})

			$("#addCourse").click(function(){
				var tbl = $("#courseTable");
				var tblHeader = "";

				if($("#enrolledCourseHeader").length == 0){
					var tblHeader = "<tr class='' id='enrolledCourseHeader'><td>Code</td><td>Name</td><td>Group</td><td>Start Date</td><td style='text-align: center;width: 100px;'>Status</td><td style='text-align: center;width: 100px;'>Action</td></tr>";
				}

				var appendRow = tblHeader+
				"<tr><td>"+
				"<select class='form-control form-control-sm courseCode' name='courseCode' style='appearance: none'>"+
				<?php
					$query = "select course_id, course_code from course where course_status = 'Open'";
					$result = mysqli_query($con, $query);
					while($row = mysqli_fetch_assoc($result)){
						echo '"<option value=\''.$row["course_id"].'\'';
						echo '>'.$row["course_code"].'</option>"+';
					}
					echo '"<option value=\'0\' selected>----------</option>"+';
				?>							
				"</select></td>"+
				"<td><input type='hidden' name='scId' class='form-control form-control-sm'>"+
				"<input type='text' name='courseName' class='form-control form-control-sm' readonly></td>"+
				"<td>"+
				"<select name='courseGroup' class='form-control form-control-sm courseGroup' style='appearance: none' disabled></select></td>"+
				"<td style='text-align: center;width: 100px;'>"+
				"<input type='text' name='courseStatus' class='form-control form-control-sm' readonly></td>"+
				"<td style='text-align: center;background-color: transparent;'><button class='btn btn-sm courseDelBtn red-icon'>"+
				"<i class='fa fa-trash-alt'></i></button><button class='btn btn-sm courseSaveBtn green-icon'><i class='fas fa-check'></i></button>"+
				"</td></tr>"

				$(appendRow).appendTo(tbl);
			});

			$(document.body).delegate(".courseSaveBtn", "click", function(){
				var tbl = $("#courseTable");
				var courseCode = $(this).closest("tr").find("select[name='courseCode']").val();
				var courseGroup = $(this).closest("tr").find("select[name='courseGroup']").val();
				var courseStartDate = $(this).closest("tr").find("input[name='courseStartDate']").val();
				var courseName = $(this).closest("tr").find("input[name='courseName']").val();
				var courseStatus = $(this).closest("tr").find("input[name='courseStatus']").val();
				var scId = $(this).closest("tr").find("input[name='scId']").val();
				var studentId = $("#studentId").val();

				if(scId != ""){	
					var url = "../pages/includes/update_student_course.php";
				} else {
					$(this).closest("tr").remove();
					var url = "../pages/includes/add_student_course.php";
				}

				jQuery.ajax({
					type: "POST",
					url: url,
					data: {
						studentId: studentId,
						scId: scId,
						courseCode: courseCode,
						courseGroup: courseGroup
					}
				}).done(function(response){

					if(scId == ""){	
						
						var tbl = $("#courseTable");
						var tblHeader = "";

						if($("#enrolledCourseHeader").length == 0){
							var tblHeader = "<tr class='bg-olive' id='enrolledCourseHeader'><td>Code</td><td>Name</td><td>Group</td><td>Start Date</td><td style='text-align: center;width: 100px;'>Status</td><td style='text-align: center;width: 100px;'>Action</td></tr>";
						}

						var appendRow = tblHeader+
						"<tr><td>"+
						"<select class='form-control form-control-sm courseCode' name='courseCode' style='appearance: none' >"+
						<?php
							$courseCode = "";
							$query = "select course_id, course_code from course";
							$result = mysqli_query($con, $query);

							if(isset($_SESSION["courseCode"]))
								$courseCode = $_SESSION["courseCode"];

							while($row = mysqli_fetch_assoc($result)){
								echo '"<option value=\''.$row["course_id"].'\'';
								if($courseCode == $row["course_id"])
									echo 'selected ';
								echo '>'.$row["course_code"].'</option>"+';
							}
							echo '"<option value=\'0\'>----------</option>"+';
						?>							
						"</select></td>"+
						"<td><input type='hidden' name='scId' class='form-control form-control-sm' value='"+response+"'>"+
						"<input type='text' name='courseName' class='form-control form-control-sm' readonly value='"+courseName+"'></td>"+
						"<td>"+
						"<select name='courseGroup' class='form-control form-control-sm courseGroup' style='appearance: none'>"+
						<?php
							$groupCode = "";
							$query = "select group_id, group_name from course_group";
							$result = mysqli_query($con, $query);

							if(isset($_SESSION['groupCode']))
								$groupCode = $_SESSION['groupCode'];

							while($row = mysqli_fetch_assoc($result)){
								echo '"<option value=\''.$row["group_id"].'\'';
								if($groupCode == $row["group_id"])
									echo 'selected ';
								echo '>'.$row["group_name"].'</option>"+';
							}	
							echo '"<option value=\'0\'>----------</option>"+';	
							unset($_SESSION['groupCode']);
						?>
						"</select></td>"+
						"<td style='text-align: center;width: 100px;'>"+
						"<input type='text' name='courseStatus' class='form-control form-control-sm' readonly value='"+courseStatus+"'></td>"+
						"<td style='text-align: center;background-color: transparent;'><button class='btn btn-sm courseDelBtn red-icon'>"+
						"<i class='fa fa-trash-alt'></i></button><button class='btn btn-sm courseSaveBtn green-icon'><i class='fas fa-check'></i></button>"+
						"</td></tr>"

						$(appendRow).appendTo(tbl);
					}
					
					console.log(response);
				});
			});
				
			$(document.body).delegate(".courseDelBtn", "click", function(){
				var scId = $(this).closest("tr").find("input[name='scId']").val();
				$(this).closest("tr").remove();

				if(scId != undefined){
					jQuery.ajax({
						type: "POST",
						url: "../pages/includes/delete_student_course.php",
						data: {
							scId: scId
						}
					}).done(function(response){
						console.log(response);
					});
						
				}
			}); 

            jQuery.validator.addMethod("samePassword", function(value, element) {
                return this.optional(element) || $("#account_password").val() == $("#studentPasswordRetype").val();
            });

			$('#add_student').validate({
				rules: {
					studentName: {
						required: true
					},
					studentEmail: {
						required: true,
						email: true
					},
					studentEnrollDate: {
						required: true
					},
					studentUsername: {
						required: true
					},
					account_password: {
					},
					studentPasswordRetype: {
                        samePassword: true
					},
					studentBatchNumber: {
						required: true
					}
				},
				messages: {
					studentName: {
						required: "Please enter a student name"
					},
					studentEmail: {
						required: "Please enter an email address",
						email: "Please enter a vaild email address"
					},
					studentEnrollDate: {
						required: "Please enter the enroll date"
					},
					studentUsername: {
						required: "Please enter the username"
					},
					account_password: {
					},
					studentPasswordRetype: {
                        samePassword: "Retype Password must be same as the Password."
					},
					studentBatchNumber: {
						required: "Please select a batch number"
					}
				},
				errorElement: 'span',
				errorPlacement: function (error, element) {
				error.addClass('invalid-feedback');
				element.closest('.form-group').append(error);
				},
				highlight: function (element, errorClass, validClass) {
				$(element).addClass('is-invalid');
				},
				unhighlight: function (element, errorClass, validClass) {
				$(element).removeClass('is-invalid');
				}
			});
		});
	</script>
</body>

</html>