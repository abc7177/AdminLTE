<?php
    include("../docs/_includes/connection.php");
    $page = "Tutor";

    if (!(isset($_SESSION["itac_user_id"]))) {
        echo "<script>window.location='login.php'</script>";
        exit;
    }

    // if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
    // 	echo "<script>alert('You have no permission to access this module.')</script>";
    // 	echo "<script>window.location='index.php'</script>";	
    // 	exit;
    // }

    if(isset($_POST["account_id"])){	// delete tutor
        $q = "DELETE FROM account WHERE account_id = '".mysqli_real_escape_string($con,$_POST["account_id"])."'";
        mysqli_query($con,$q);

        echo '<script>localStorage.setItem("Deleted",1)</script>';	// Successful deleted flag.
        echo "<script>window.location='tutor.php'</script>";	
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ITAC | Tutor</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="../dist/css/adminlte.min.css">
	<!-- Favicon -->
	<link rel="shortcut icon" href="../dist/img/logo-200x200.png" />
    <!-- DataTables -->
    <link rel="stylesheet" href="../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
</head>

<body class="hold-transition sidebar-mini">
	<!-- Site wrapper -->
	<div class="wrapper">
		<!-- Top Navbar Container -->
        <?php include("includes/navbar.php"); ?>

		<!-- Main Sidebar Container -->
		<?php include('includes/sidebar.php'); ?>
		<!-- /.Main Sidebar Container -->

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="../dist/img/logo-200x200.png" alt="AdminLTELogo" height="100" width="100">
        </div>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1>Tutor</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="../index.php">Home</a></li>
								<li class="breadcrumb-item active">Tutor</li>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</section>

			 <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Datatable of Tutor</h3>
                                    <h3 class="card-title float-sm-right" ><button type="button" class="btn-sm btn-primary btn-block" id="addNewTutor" ><i class="fa fa-user-plus"></i>&nbsp;&nbsp;&nbsp;Add New Tutor</button></h3>
                                </div>
                                <div class="card-body">
                                    <table id="tutorTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Tutor Name</th>
                                                <th>Tutor Username</th>
                                                <th>Enroll Date</th>
                                                <th>Status</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM account WHERE account_type='TUTOR'";
                                            $result = mysqli_query($con,$query);
                                            $counter = 1;
                                            while($row = mysqli_fetch_assoc($result)){
                                                echo '<tr>';
                                                    echo '<td>'.$counter.'</td>';
                                                    echo '<td>'.$row["account_name"].'</td>';
                                                    echo '<td>'.$row["account_username"].'</td>';
                                                    echo '<td>'.$row["account_enroll_date"].'</td>';
                                                    
                                                if($row["account_status"] == 'Inactive')
                                                    echo '<td class="text-red">';
                                                else
                                                    echo '<td class="text-green">';
                                                        echo $row["account_status"];
                                                    echo '</td>';                                                   
                                                    echo '<td style="text-align: center;">';
                                                        echo '<button type="button" id="deleteTutor" style="margin:1px 3px;" class="btn btn-md btn-danger" data-account_id="'.$row["account_id"].'"><i class="fa fa-trash-alt"></i></button>';
                                                        echo '<button type="button" id="editTutor" style="margin:1px 3px;" class="btn btn-md btn-info" data-account_id="'.$row["account_id"].'"><i class="fa fa-edit"></i></button>';
                                                    echo '</td>';
                                                echo '</tr>';
                                                $counter++;
                                            }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.Main content -->
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

    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">Delete tutor?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form name="delete_form" method="POST" enctype="multipart/form-data" >
                    <input type="hidden" name="account_id" id="account_id">
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-danger" onclick="delete_tutor()">Delete</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->

	<!-- jQuery -->
	<script src="../plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../plugins/jszip/jszip.min.js"></script>
    <script src="../plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
	<!-- AdminLTE App -->
	<script src="../dist/js/adminlte.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="../dist/js/demo.js"></script>
    <!-- Toastr -->
    <script src="../plugins/toastr/toastr.min.js"></script>
    <script>
        $(document).ready(function(){

            if(localStorage.getItem("Deleted")=="1"){
                toastr.success('Tutor has been deleted successfully.');
                localStorage.clear();
            }

            if(localStorage.getItem("Added")=="1"){
                toastr.success('Tutor has been added successfully.');
                localStorage.clear();
            }
		});
        $(function () {
            $("#tutorTable").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#tutorTable_wrapper .col-md-6:eq(0)');

            $('[id="deleteTutor"]').click(function() {
					var account_id = $(this).data('account_id');
                    $("#account_id").val( account_id );
					$("#deleteModal").modal();
			});

            $('[id="editTutor"]').click(function() {
					var account_id = $(this).data('account_id');
					var url = "../pages/tutor_edit.php?id="+account_id;
					window.location.href = url;
			});

            $('[id="addNewTutor"]').click(function() {
					var url = "../pages/tutor_add.php";
					window.location.href = url;
			});
            
        });

        function delete_tutor(){
                //alert("Yo");
				document.forms["delete_form"].submit();	
		}
    </script>
</body>

</html>