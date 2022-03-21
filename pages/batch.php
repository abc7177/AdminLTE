<?php
    include("../docs/_includes/connection.php");
    $page = "Batch";

    if (!(isset($_SESSION["itac_user_id"]))) {
        echo "<script>window.location='login.php'</script>";
        exit;
    }

    // if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
    // 	echo "<script>alert('You have no permission to access this module.')</script>";
    // 	echo "<script>window.location='index.php'</script>";	
    // 	exit;
    // }

    if(isset($_POST["batch_id"])){	// delete Batch
        $q = "DELETE FROM batch WHERE batch_id = '".mysqli_real_escape_string($con,$_POST["batch_id"])."'";
        mysqli_query($con,$q);

        //echo $q;

        $q = "DELETE FROM batch_module WHERE batch_id = '".mysqli_real_escape_string($con,$_POST["batch_id"])."'";
        mysqli_query($con,$q);

        //echo $q;

        echo '<script>localStorage.setItem("Deleted",1)</script>';	// Successful deleted flag.
        echo "<script>window.location='batch.php'</script>";	
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ITAC | Batch</title>

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
							<h1>Batch</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="../index.php">Home</a></li>
								<li class="breadcrumb-item active">Batch</li>
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
                                    <h3 class="card-title">Datatable of Batch</h3>
                                    <h3 class="card-title float-sm-right" ><button type="button" class="btn-sm btn-primary btn-block" id="addNewBatch" ><i class="fa fa-folder-plus"></i>&nbsp;&nbsp;&nbsp;Add New Batch</button></h3>
                                </div>
                                <div class="card-body">
                                    <table id="BatchTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Batch Code</th>
                                                <th>Batch Name</th>
                                                <th>Total Students</th>
                                                <th>Status</th>
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT batch.*, count(batch_sub_id) as total_student FROM batch LEFT JOIN batch_sub ON batch.batch_id = batch_sub.batch_id GROUP BY batch_id";
                                            $result = mysqli_query($con,$query);
                                            $counter = 1;
                                            while($row = mysqli_fetch_assoc($result)){
                                                echo '<tr>';
                                                    echo '<td>'.$counter.'</td>';
                                                    echo '<td>'.$row["batch_code"].'</td>';
                                                    echo '<td>'.$row["batch_name"].'</td>';
                                                    echo '<td>'.$row["total_student"].'</td>';

                                                if($row["batch_status"] == 'Closed')
                                                    echo '<td class="text-red">';
                                                else
                                                    echo '<td class="text-green">';
                                                        echo $row["batch_status"];
                                                    echo '</td>';
                                                    echo '<td style="text-align: center;">';
                                                        echo '<button type="button" style="margin:1px 3px;" class="btn btn-md btn-danger deleteBatch" data-batch_id="'.$row["batch_id"].'"><i class="fa fa-trash-alt"></i></button>';
                                                        echo '<button type="button" style="margin:1px 3px;" class="btn btn-md btn-info editBatch" data-batch_id="'.$row["batch_id"].'"><i class="fa fa-edit"></i></button>';
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
                    <h4 class="modal-title">Delete Batch?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form name="delete_form" method="POST" enctype="multipart/form-data" >
                    <input type="hidden" name="batch_id" id="batch_id">
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-danger" onclick="delete_Batch()">Delete</button>
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
                toastr.success('Batch has been deleted successfully.');
                localStorage.clear();
            }

            if(localStorage.getItem("Added")=="1"){
                toastr.success('Batch has been added successfully.');
                localStorage.clear();
            }
		});

        $(function () {
            $("#BatchTable").DataTable({
            "responsive": false, 
            "lengthChange": false, 
            "autoWidth": false,
            "initComplete": function (settings, json) {  
                $("#BatchTable").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");            
            },
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#BatchTable_wrapper .col-md-6:eq(0)');

            $("#BatchTable").on("click",".deleteBatch", function () {
					var batch_id = $(this).data('batch_id');
                    $("#batch_id").val( batch_id );
					$("#deleteModal").modal();
			});

            $("#BatchTable").on("click",".editBatch", function () {
					var batch_id = $(this).data('batch_id');
					var url = "../pages/batch_edit.php?id="+batch_id;
					window.location.href = url;
			});

            $('[id="addNewBatch"]').click(function() {
					var url = "../pages/batch_add.php";
					window.location.href = url;
			});
                
        });

        function delete_Batch(){
				document.forms["delete_form"].submit();	
		}
    </script>
</body>

</html>