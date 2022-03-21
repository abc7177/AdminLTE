<?php
    include("../docs/_includes/connection.php");
    if($_GET["eva_type"] == "daily"){
        $evaType = "daily";
        $page = "Daily Evaluation";
    } else if($_GET["eva_type"] == "semester") {
        $evaType = "semester";
        $page = "Semester Evaluation";
    } else {
        $evaType = "final";
        $page = "Final Evaluation";
    }
    

    if (!(isset($_SESSION["itac_user_id"]))) {
        echo "<script>window.location='login.php'</script>";
        exit;
    }

    // if(!in_array("Staff",$_SESSION["itac_user_permission_array"])){
    // 	echo "<script>alert('You have no permission to access this module.')</script>";
    // 	echo "<script>window.location='index.php'</script>";	
    // 	exit;
    // }

    if(isset($_POST["evaluation_id"])){	// delete evaluation
        if($evaType == "daily"){
            $q = "DELETE FROM evaluation WHERE evaluation_id = '".mysqli_real_escape_string($con,$_POST["evaluation_id"])."'";
            mysqli_query($con,$q);

            $q = "DELETE FROM evaluation_sub WHERE evaluation_id = '".mysqli_real_escape_string($con,$_POST["evaluation_id"])."'";
            mysqli_query($con,$q);
        } else if($evaType == "semester") {
            $q = "DELETE FROM evaluation_sem WHERE evaluation_id = '".mysqli_real_escape_string($con,$_POST["evaluation_id"])."'";
            mysqli_query($con,$q);

            $q = "DELETE FROM evaluation_sem_sub WHERE evaluation_id = '".mysqli_real_escape_string($con,$_POST["evaluation_id"])."'";
            mysqli_query($con,$q);
        } else {
            $q = "DELETE FROM evaluation_final WHERE evaluation_id = '".mysqli_real_escape_string($con,$_POST["evaluation_id"])."'";
            mysqli_query($con,$q);

            $q = "DELETE FROM evaluation_final_sub WHERE evaluation_id = '".mysqli_real_escape_string($con,$_POST["evaluation_id"])."'";
            mysqli_query($con,$q);
        }

        echo '<script>localStorage.setItem("Deleted",1)</script>';	// Successful deleted flag.
        echo "<script>window.location='evaluation.php?eva_type=".$evaType."'</script>";	
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ITAC | Evaluation</title>

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
							<h1><?php if($page == "Daily Evaluation"){ echo "Daily Evaluation"; } else if($evaType == "semester"){ echo "Semester Evaluation"; } else {echo "Final Evaluation";} ?></h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
                                <?php
                                if(isset($_GET["asTutor"])) { 
                                    echo '<li class="breadcrumb-item"><a href="../index.php?asTutor='.$_GET["asTutor"].'">Home</a></li>';
                                    echo '<li class="breadcrumb-item active">Evaluation</li>';
                                } else {
                                    echo '<li class="breadcrumb-item"><a href="../index.php">Home</a></li>';
                                    echo '<li class="breadcrumb-item active">Evaluation</li>';
                                }
                                ?>
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
                                    <h3 class="card-title">Datatable of  <?php if($page == "Daily Evaluation"){ echo "Daily Evaluation"; } else if($evaType == "semester"){ echo "Semester Evaluation"; } else {echo "Final Evaluation";}?></h3>
                                    <?php if($_SESSION["itac_admin"]  && !isset($_GET["asStudent"])  && !isset($_GET["asTutor"])) {?>
                                        <h3 class="card-title float-sm-right" ><button type="button" class="btn-sm btn-primary btn-block" id="addNewEvaluation" data-evatype='<?php echo $evaType; ?>'><i class="fa fa-folder-plus"></i>&nbsp;&nbsp;&nbsp;Add New Evaluation</button></h3>
                                    <?php } ?>
                                </div>
                                <div class="card-body">
                                    <table id="evaluationTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Evaluation Date</th>
                                                <th>Batch</th>
                                                <th>Course Info</th>
                                                <th>Group Name</th>
                                                <!-- <th>Evaluation Avg Score</th> -->
                                                <th style="text-align: center;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                             $showFilterSelectionDialog = 1;
                                             if($evaType == "daily"){
                                                $query = "SELECT evaluation.*, course.*, course_group.* ,course_module.course_module_code, course_module.course_module_name, batch.batch_code, batch.batch_name , GROUP_CONCAT(distinct(course_group.group_name)) as `groupName`   FROM evaluation
                                                        LEFT JOIN evaluation_sub ON evaluation_sub.evaluation_id = evaluation.evaluation_id
                                                        LEFT JOIN batch ON evaluation.batch_id = batch.batch_id 
                                                        LEFT JOIN course ON evaluation.course_id = course.course_id 
                                                        LEFT JOIN course_module ON evaluation.course_module_id = course_module.course_module_id 
                                                        LEFT JOIN course_group ON evaluation_sub.group_id = course_group.group_id ";
                                            } else if($evaType == "semester"){
                                                $query = "SELECT evaluation_sem.*, course.*, course_group.*, course_module.course_module_code, course_module.course_module_name, batch.batch_code, batch.batch_name, GROUP_CONCAT(distinct(course_group.group_name)) as `groupName`  FROM evaluation_sem 
                                                        LEFT JOIN evaluation_sem_sub ON evaluation_sem_sub.evaluation_id = evaluation_sem.evaluation_id
                                                        LEFT JOIN batch ON evaluation_sem.batch_id = batch.batch_id 
                                                        LEFT JOIN course ON evaluation_sem.course_id = course.course_id
                                                        LEFT JOIN course_module ON evaluation_sem.course_module_id = course_module.course_module_id 
                                                        LEFT JOIN course_group ON evaluation_sem_sub.group_id = course_group.group_id ";
                                                        
                                            } else {
                                                $query = "SELECT evaluation_final.*, course.*, course_group.*, course_module.course_module_code, course_module.course_module_name, batch.batch_code, batch.batch_name, GROUP_CONCAT(distinct(course_group.group_name)) as `groupName`  FROM evaluation_final 
                                                        LEFT JOIN evaluation_final_sub ON evaluation_final_sub.evaluation_id = evaluation_final.evaluation_id
                                                        LEFT JOIN batch ON evaluation_final.batch_id = batch.batch_id 
                                                        LEFT JOIN course ON evaluation_final.course_id = course.course_id
                                                        LEFT JOIN course_module ON evaluation_final.course_module_id = course_module.course_module_id 
                                                        LEFT JOIN course_group ON evaluation_final_sub.group_id = course_group.group_id ";
                                                        
                                            }

                                            if(isset($_GET["batchNo"]) || isset($_GET["courseCode"]) || isset($_GET["groupNo"])){
                                                $showFilterSelectionDialog = 0;
                                                $query .= "WHERE ";

                                                if(isset($_GET["batchNo"])){
                                                    $query .= "batch.batch_id ='".$_GET["batchNo"]."' ";
                                                }

                                                if(isset($_GET["courseCode"])){
                                                    if(isset($_GET["batchNo"]))
                                                        $query .="AND ";
                                                    $query .= "course.course_id ='".$_GET["courseCode"]."' ";
                                                }

                                                if(isset($_GET["groupNo"]) && $_GET["groupNo"] !=""){
                                                    if(isset($_GET["batchNo"]) || isset($_GET["courseCode"]))
                                                        $query .="AND ";
                                                    $query .= "course_group.group_id ='".$_GET["groupNo"]."' ";
                                                }
                                            }

                                            if($evaType == "daily"){
                                                $query .="GROUP BY evaluation_sub.evaluation_id ORDER BY evaluation_id DESC";
                                            } else if($evaType == "semester"){
                                                $query .="GROUP BY evaluation_sem_sub.evaluation_id ORDER BY evaluation_id DESC";
                                            } else if($evaType == "final"){
                                                $query .="GROUP BY evaluation_final_sub.evaluation_id ORDER BY evaluation_id DESC";
                                            }
                                            
                                            //echo $query;
                                            $result = mysqli_query($con,$query);
                                            $counter = 1;
                                            while($row = mysqli_fetch_assoc($result)){
                                                echo '<tr>';
                                                    $phpdate = strtotime( $row["evaluation_date"] );
                                                    $mysqldate = date( 'd-M-Y', $phpdate );
                                                    echo '<td>'.$counter.'</td>';
                                                    echo '<td>'.$mysqldate.'</td>';
                                                    echo '<td>'.$row["batch_code"].'<br>'.$row["batch_name"].'</td>';
                                                    echo '<td>Code: '.$row["course_code"].'<br>Name: '.$row["course_name"].'<br>';

                                                    if($row["evaluation_type"] == "Theory")
                                                        echo 'Type: <span style="color:#00e32a;">'.$row["evaluation_type"].'</span>';
                                                    else
                                                        echo 'Type: <span style="color:#0035e3;">'.$row["evaluation_type"].'</span>';
                                                    echo '<br>Module: '.$row["course_module_code"].'<br>Name: '.$row["course_module_name"].'</td>';
                                                    echo '<td>'.$row["groupName"].'</td>';

                                                    // if($evaType == "daily"){
                                                    //     $query1 = "SELECT FORMAT((
                                                    //         (SELECT SUM(eva_rate) FROM evaluation_sub WHERE evaluation_id = '".$row["evaluation_id"]."')
                                                    //         /
                                                    //         (SELECT COUNT(*) FROM evaluation_sub WHERE evaluation_id = '".$row["evaluation_id"]."') 
                                                    //     ), 2) AS evaluationRate";
                                                    // } else {
                                                    //     $query1 = "SELECT FORMAT((
                                                    //         (SELECT SUM(eva_rate) FROM evaluation_sem_sub WHERE evaluation_id = '".$row["evaluation_id"]."')
                                                    //         /
                                                    //         (SELECT COUNT(*) FROM evaluation_sem_sub WHERE evaluation_id = '".$row["evaluation_id"]."') 
                                                    //     ), 2) AS evaluationRate";
                                                    // }

                                                    // $result1 = mysqli_query($con,$query1);
                                                    // if($row1 = mysqli_fetch_assoc($result1)){
                                                    //     echo '<td>'.$row1["evaluationRate"].' / 5.00</td>';
                                                    // }
                                                    echo '<td style="text-align: center;">';
                                                        if(!isset($_GET["asTutor"])) 
                                                            echo '<button type="button" style="margin:1px 3px;" class="btn btn-md btn-danger deleteEvaluation" data-evaluation_id="'.$row["evaluation_id"].'"><i class="fa fa-trash-alt"></i></button>';
                                                        echo '<button type="button" style="margin:1px 3px;" class="btn btn-md btn-info editEvaluation" data-evaluation_id="'.$row["evaluation_id"].'" data-evatype="'.$evaType.'"><i class="fa fa-edit"></i></button>';
                                                    echo '</td>';
                                                echo '</tr>';
                                                $counter++;
                                            }
                                        ?>
                                        <input type="hidden" name="showFilterSelectionDialog" id="showFilterSelectionDialog" value="<?php echo $showFilterSelectionDialog; ?>">
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
                    <h4 class="modal-title">Delete evaluation?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form name="delete_form" method="POST" enctype="multipart/form-data" >
                    <input type="hidden" name="evaluation_id" id="evaluation_id">
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-danger" onclick="delete_evaluation()">Delete</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="filterModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Filter evaluation?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form name="delete_form" method="POST" enctype="multipart/form-data" >
                    <div class="form-group">
                        <div class="row" style="margin:5px;">
                            <div class="col-md-12">
                                <select class="form-control" name="evaluationBatchId" id="evaluationBatchId">
                                    <?php
                                        echo '<option value="" selected disabled>----- Batch Code -----</option>';
                                        $query = "SELECT * FROM batch WHERE batch_status = 'Open'";
                                        $result = mysqli_query($con, $query);
                                        while($row = mysqli_fetch_assoc($result)){
                                            echo '<option value="'.$row["batch_id"].'" ';
                                            echo '>'.$row["batch_code"].' - '.$row["batch_name"].'</option>';
                                        }
                                    ?>
                                </select>		
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row" style="margin:5px;">
                            <div class="col-md-12">
                                <select class="form-control" name="evaluationCourseId" id="evaluationCourseId">
                                    <?php
                                        echo '<option value="" selected disabled>----- Course Code -----</option>';
                                        $query = "SELECT * FROM course WHERE course_status = 'Open'";
                                        $result = mysqli_query($con, $query);
                                        while($row = mysqli_fetch_assoc($result)){
                                            echo '<option value="'.$row["course_id"].'" ';
                                            echo '>'.$row["course_code"].' - '.$row["course_name"].'</option>';
                                        }
                                    ?>
                                </select>			
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row" style="margin:5px;">
                            <div class="col-md-12">
                                <select name='evaluationCourseGroup' id='evaluationCourseGroup' class='form-control form-control-sm' disabled></select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-outline-info" onclick="filter_evaluation()">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                toastr.success('Evaluation has been deleted successfully.');
                localStorage.clear();
            }

            if(localStorage.getItem("Added")=="1"){
                toastr.success('Evaluation has been added successfully.');
                localStorage.clear();
            }

            if(localStorage.getItem("Updated")=="1"){
                toastr.success('Evaluation has been updated successfully.');
                localStorage.clear();
            }

            var showFilterSelectionDialog = $("#showFilterSelectionDialog").val();

            if(showFilterSelectionDialog == 1){	// No filter was selected then prompt filter selection modal.
                //$("#filterModal").modal();
            }
		});

        $(function () {
            $("#evaluationTable").DataTable({
            "responsive": false, 
            "lengthChange": false, 
            "autoWidth": false,
            "initComplete": function (settings, json) {  
                $("#evaluationTable").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");          
            },
            "buttons": [
                "copy", "csv", "excel", "pdf", "print", "colvis",
                {
                    text: '<i class="fa fa-filter" /> Filter</i>',
                    action: function ( e, dt, node, config ) {
                        $("#filterModal").modal();
                    }
                }
            ]
            }).buttons().container().appendTo('#evaluationTable_wrapper .col-md-6:eq(0)');

            $(document).on('change','#evaluationCourseId',function(){
				valueCurrent = $(this).val();
				if(valueCurrent != 0){
					getCourseGroupInfo($(this));
				} else {
					$(this).parent().parent().find("select[name='evaluationCourseGroup']").val("0");
					$(this).parent().parent().find("select[name='evaluationCourseGroup']").prop('disabled', true);
				}
			});

            $("#evaluationTable").on("click",".deleteEvaluation", function () {
					var evaluation_id = $(this).data('evaluation_id');
                    $("#evaluation_id").val( evaluation_id );
					$("#deleteModal").modal();
			});

            $("#evaluationTable").on("click",".editEvaluation", function () {
					var evaluation_id = $(this).data('evaluation_id');
                    var evaType = $(this).data("evatype");
                    var currentUrl = window.location.href;
                    var url = new URL(currentUrl);
                  
                    if(url.searchParams.has("asTutor") == true){
                        var asTutor = url.searchParams.get("asTutor");

                        if(evaType == "daily")
                            url = "../pages/evaluation_edit.php?asTutor="+asTutor+"&id="+evaluation_id+"&eva_type="+evaType;
                        else if(evaType == "semester")
                            url = "../pages/evaluation_sem_edit.php?asTutor="+asTutor+"&id="+evaluation_id+"&eva_type="+evaType;	
                        else 			
                            url = "../pages/evaluation_final_edit.php?asTutor="+asTutor+"&id="+evaluation_id+"&eva_type="+evaType;	
                    } else {

                        if(evaType == "daily")
                            url = "../pages/evaluation_edit.php?id="+evaluation_id+"&eva_type="+evaType;
                        else if(evaType == "semester")
                            url = "../pages/evaluation_sem_edit.php?id="+evaluation_id+"&eva_type="+evaType;	
                        else 			
                            url = "../pages/evaluation_final_edit.php?id="+evaluation_id+"&eva_type="+evaType;	
                    }	
					window.location.href = url;
			});

            $('[id="addNewEvaluation"]').click(function() {
                    var evaType = $(this).data("evatype");

                    if(evaType == "daily")
					    var url = "../pages/evaluation_add.php?eva_type="+evaType;
                    else if(evaType == "semester")
                        var url = "../pages/evaluation_sem_add.php?eva_type="+evaType;
                    else
                        var url = "../pages/evaluation_final_add.php?eva_type="+evaType;
					window.location.href = url;
			});
                
        });

        function delete_evaluation(){
			document.forms["delete_form"].submit();	
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

					var select = thisObj.parent().parent().parent().siblings().find("select[name='evaluationCourseGroup']");
					var options = select.prop('options');
					$('option', select).remove();
					select.prop('disabled', false);
					options[options.length] = new Option("----------", "");

					for (var i = 0, len = data.length; i < len; i++) {
						var id = data[i].id;
						var desc = data[i].name;
						options[options.length] = new Option(desc, id);
					}
				}
			});
		}

        function filter_evaluation(){ 
			var currentUrl = window.location.href;
			var url = new URL(currentUrl);
            var evaluationBatchId = "";
            var evaluationCourseId = "";
            var evaluationCourseGroup = "";

            if($("#evaluationBatchId").val() != undefined){
                evaluationBatchId = $("#evaluationBatchId").val();
                url.searchParams.set("batchNo", evaluationBatchId);
            }

            if($("#evaluationCourseId").val() != undefined){
                evaluationCourseId = $("#evaluationCourseId").val();
                url.searchParams.set("courseCode", evaluationCourseId);
            }

            if($("#evaluationCourseGroup").val() != undefined && $("#evaluationCourseGroup").val() != ""){
                evaluationCourseGroup = $("#evaluationCourseGroup").val();
                url.searchParams.set("groupNo", evaluationCourseGroup);
            }

            var newUrl = url.href; 
			window.location.href = newUrl; 
		}
    </script>
</body>

</html>