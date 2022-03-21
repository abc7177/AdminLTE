<?php
	include("../docs/_includes/connection.php"); 
	$page = "login";

  if(isset($_POST["username"])){
    $userName = $_POST["username"];
    $hashedPassword = password_hash(mysqli_real_escape_string($con,$_POST["password"]), PASSWORD_DEFAULT);

    $query = "SELECT account.*, account_level.account_level_class, account_detail.ad_image_path, state.state_code FROM account 
        LEFT JOIN account_level ON account.account_level_id = account_level.account_level_id 
        LEFT JOIN account_detail ON account.account_id = account_detail.account_id 
        LEFT JOIN state ON account.account_state = state.state_id
        WHERE account_username = '".$userName."'";
    
    //echo $query;
    $result = mysqli_query($con,$query);
    if($row = mysqli_fetch_assoc($result)){
      if(password_verify(mysqli_real_escape_string($con,$_POST["password"]), $row["account_password"])){
        if($row["account_status"] == "Inactive"){
          echo "<script>alert('The account has been suspended. Kindly contact your admin for further enquiries.')</script>";
          echo "<script>window.location='login.php'</script>";	
        }
        else{

          if(isset($_POST["rememberMe"])) { 
            setcookie ("member_username",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60));
            setcookie ("member_password",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60));
          } else { 
            if(isset($_COOKIE["member_username"])) {
              setcookie ("member_username","");
              setcookie ("member_password","");
            }
          }

          $_SESSION["itac_user_id"] = $row["account_id"];	
          //$_SESSION["itac_path_configuration"] = "/demo/howard/itac";
          $_SESSION["itac_path_configuration"] = "/xantec/itac";
          //$_SESSION["itac_path_configuration"] = "";
          $_SESSION["itac_ad_image_path"] = $row["ad_image_path"];
          $_SESSION["itac_account_level"] = $row["account_level_class"];
          $_SESSION["itac_username"] = $row["account_username"];
          $_SESSION["itac_account_name"] = $row["account_name"];
          $_SESSION["itac_user_permission_array"] = explode(",",$row["account_permission"]);	
          if($_SESSION["itac_account_level"] <= 1){
            $_SESSION["itac_admin"] = "1";	// admin account, to enable admin only functions
          } else {
            $_SESSION["itac_admin"] = "0";
          }
          echo "<script>window.location='../index.php'</script>";	
        }
      } else {
        echo '<script>localStorage.setItem("LoginFailed",1)</script>';	// Login Failed flag.
        echo "<script>window.location='login.php'</script>";	
      }
        exit;
    } else {
        echo '<script>localStorage.setItem("LoginFailed",1)</script>';	// Login Failed flag.
        echo "<script>window.location='login.php'</script>";	
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ITAC | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
  <!-- Favicon -->
  <link rel="shortcut icon" href="../dist/img/logo-200x200.png"/>
  <!-- Toastr -->
  <link rel="stylesheet" href="../plugins/toastr/toastr.min.css">
</head>
<body class="hold-transition login-page">
  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../dist/img/logo-200x200.png" alt="AdminLTELogo" height="100" width="100">
  </div>
  <div class="login-box">
    <div class="login-logo">
      <a href="../index.php"> <img src="../dist/img/logo-200x200.png" alt="ITAC Logo" class="" style="opacity: .8"></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form method="post">
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" value="<?php if(isset($_COOKIE["member_username"])) { echo $_COOKIE["member_username"]; } ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" value="<?php if(isset($_COOKIE["member_password"])) { echo $_COOKIE["member_password"]; } ?>">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" name="rememberMe" id="rememberMe" value="1" <?php if(isset($_COOKIE["member_username"])) { echo "checked"; } ?>>
                <label for="rememberMe">
                  Remember Me
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <!-- <p class="mb-1">
          <a href="examples/404.html">I forgot my password</a>
        </p> -->
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../dist/js/adminlte.min.js"></script>
  <!-- Toastr -->
  <script src="../plugins/toastr/toastr.min.js"></script>
  <script>
    $(document).ready(function(){

        if(localStorage.getItem("LoginFailed")=="1"){
            toastr.error('Login Failed. The username or password is incorrect.');
            localStorage.clear();
        }

        if(localStorage.getItem("LogoutSuccess")=="1"){
            toastr.success('Logout Successful. Your session has been cleared.');
            localStorage.clear();
        }
    });
  </script>
</body>
</html>
