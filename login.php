<?php require_once('required/function.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="images/favicon.ico">

  <title>Bank Facilities</title>

  <!-- Bootstrap 4.1.3-->
  <link rel="stylesheet" href="assets/vendor_components/bootstrap/css/bootstrap.css">

  <!-- Bootstrap-extend-->
  <link rel="stylesheet" href="css/bootstrap-extend.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/vendor_components/font-awesome/css/font-awesome.min.css">

  <!-- Ionicons -->
  <link rel="stylesheet" href="assets/vendor_components/Ionicons/css/ionicons.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="css/master_style.css">

  <!-- Minimal-art Admin Skins. Choose a skin from the css/skins
	   folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="css/skins/_all-skins.css">
  <link rel="stylesheet" href="css/op.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

  <!-- google font -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">

</head>

<body class="hold-transition login-page">
  <div class="login-box">

    <!-- /.login-logo -->
    <div class="login-box-body" style='padding-bottom:20px'>
      <div class="login-logo">
        <!--<a href="" class='text-custom'><b><?php echo $full_name; ?></b></a>-->
        <!-- <img src='images/icon-192x192.png' style='border-radius:50%;height:130px;'> -->
      </div>
      <p class="login-box-msg">Sign in to start your session</p>

      <form id='login_frm'>
        <div class="form-group has-feedback">
          <input type="text" class="form-control" placeholder="Username" name='username' required>
          <span class="ion ion-email form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" class="form-control" placeholder="Password" name='password' required>
          <span class="ion ion-locked form-control-feedback"></span>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="fog-pwd">
              <a href="javascript:void(0)" id='forget_password'><i class="ion ion-locked"></i> Forgot password ?</a><br>
            </div>
          </div>
      </form>
      <!-- /.col -->
      <div class="col-12 text-center">
        <span class="btn btn-orange btn-block btn-flat margin-top-10 " id='login_btn'>SIGN IN</span>
      </div>
      <!-- /.col -->
    </div>


  </div>
  <!-- /.login-box-body -->
  </div>
  <!-- /.login-box -->
  <!-- jQuery 3 -->
  <script src="assets/vendor_components/jquery-3.3.1/jquery-3.3.1.js"></script>

  <script src="assets/vendor_components/popper/dist/popper.min.js"></script>
  <!-- Bootstrap 4.1.3--	-->
  <script src="assets/vendor_components/bootstrap/js/bootstrap.min.js"></script>
  <script src="js/jquery.validate.min.js"></script>
  <script src="js/bootbox.all.js"></script>
  <script src="js/notify.min.js"></script>
  <script src="js/op.js"></script>
  <script>
    $(document).ready(function() {
      $(window).keydown(function(event) {
        if (event.keyCode == 13) {
          event.preventDefault();
          $("#login_btn").trigger('click');
        }
      });
    });
  </script>
</body>

</html>