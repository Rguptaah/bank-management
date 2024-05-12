<?php require_once ('function.php');
$_POST = post_clean($_POST);
$_GET = post_clean($_GET);
if (!isset($_SESSION['initiated'])) {
  echo "<script> window.location ='login' </script>";
} else {
  $user_id = $_SESSION['user_id'];
  $udata = get_data('users', $user_id)['data'];
  $user_name = $udata['username'];
  // $user_type = $udata['users_type'];
  session_regenerate_id();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="apple-mobile-web-app-status-bar" content="#ffbf36">
  <meta name="theme-color" content="#ffbf36">
  <link rel="manifest" href="manifest.json">
  <link rel="icon" href="images/favicon.ico">
  <title><?php echo $inst_name; ?> </title>

  <!-- Bootstrap 4.1.3-->
  <link rel="stylesheet" href="assets/vendor_components/bootstrap/css/bootstrap.css">

  <!-- Bootstrap-extend-->
  <link rel="stylesheet" href="css/bootstrap-extend.css">

  <!-- font awesome -->
  <link rel="stylesheet" href="assets/vendor_components/font-awesome/css/font-awesome.min.css">

  <!-- ionicons -->
  <link rel="stylesheet" href="assets/vendor_components/Ionicons/css/ionicons.min.css">

  <!-- theme style -->
  <link rel="stylesheet" href="css/master_style.css">

  <!-- Minimal-art Admin skins. choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="css/skins/_all-skins.css">
  <link rel="stylesheet" href="css/op.css">

  <!-- google font -->
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">


</head>

<body class="sidebar-mini skin-yellow">
  <div class="wrapper sidebar-mini skin-dask">

    <header class="main-header">
      <!-- Logo -->
      <a href="dashboard" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="images/minimal.png" alt=""></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><?php echo $inst_name; ?></span>
      </a>
      <!-- Header Navbar-->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

          <ul class="nav navbar-nav">

            <!-- Tasks-->
            <li class="dropdown tasks-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-flag"></i>
              </a>
              <ul class="dropdown-menu scale-up">
                <li class="header">Hot Link & ShortCut</li>
                <li>
                  <!-- inner menu: contains the actual data -->
                  <ul class="menu inner-content-div">

                    <li>
                      <!-- Task item -->
                      <a href="add_student">
                        <h3>
                          Dashboard
                          <small class="pull-right btn btn-dark">F1</small>
                        </h3>
                      </a>
                    </li>

                    <li>
                      <!-- Task item -->
                      <a href="manage_member">
                        <h3>
                          Manage Member
                          <small class="pull-right btn btn-dark">F2</small>
                        </h3>
                      </a>
                    </li>

                    <li>
                      <!-- Task item -->
                      <a href="deposit_report">
                        <h3>
                          Deposit Report
                          <small class="pull-right btn btn-dark">F3</small>
                        </h3>
                      </a>
                    </li>
                    <li>
                      <!-- Task item -->
                      <a href="deposit_fee">
                        <h3>
                          Deposits
                          <small class="pull-right btn btn-dark">F4</small>
                        </h3>
                      </a>
                    </li>
                    <!-- end task item -->
                    <li>
                      <!-- Task item -->
                      <a href="javascript:void(0)">
                        <h3>
                          Save & Update
                          <small class="pull-right btn btn-dark">Ctrl + S</small>
                        </h3>
                      </a>
                    </li>

                    <li>
                      <!-- Task item -->
                      <a href="deposit_fee">
                        <h3>
                          Search Member
                          <small class="pull-right btn btn-dark">Ctrl + F</small>
                        </h3>
                      </a>
                    </li>

                    <!-- end task item -->
                  </ul>
                </li>
              </ul>
            </li>


            <!-- Notifications -->
            <li class="dropdown notifications-menu">

              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell"></i>
              </a>
              <ul class="dropdown-menu scale-up">
                <!-- <li class="header">All Notification are shown here </li> -->
                <?php
                // $res = get_all("vehicle");
                // $count = 0;
                // if ($res['count'] > 0) {
                //   foreach ($res['data'] as $row) {
                //     $id = $row['id'];
                //     $vehicle_no = $row['vehicle_no'];
                //     $today_date = date("Y-m-d");
                //     $pollution_expiry = $row['pollution_expiry'];
                //     $insurance_expiry = $row['insurance_expiry'];
                //     $fitness_expiry = $row['fitness_expiry'];
                //     $road_tax_expiry = $row['road_tax_expiry'];
                //     $emi_start_date = $row['emi_start_date'];
                //     $emi_period = $row['emi_period'];
                // echo date_difference($today_date, $road_tax_expiry);
                ?>

                <li>
                  <!--inner menu: contains the actual data -->
                  <ul class="menu inner-content-div">
                    <?php
                    // if ((date_difference($today_date, $pollution_expiry)) <= 7 and (date_difference($today_date, $pollution_expiry)) >= 0) {
                    //$count++; ?>
                    <li>
                      <a href="#">
                        <i class="fa fa-bullhorn text-danger"></i> Vehicle No. <?php //echo $vehicle_no; ?> Renew
                        Pollution
                      </a>
                    </li>
                    <?php //}
                    // if ((date_difference($today_date, $insurance_expiry)) <= 7 and (date_difference($today_date, $insurance_expiry)) >= 0) {
                    //   $count++; ?>
                    <li>
                      <a href="#">
                        <i class="fa fa-bullhorn text-danger"></i> Vehicle No. <?php //echo $row['vehicle_no']; ?> Renew
                        Insurance
                      </a>
                    </li>
                    <?php //}
                    //if ((date_difference($today_date, $fitness_expiry)) <= 7 and (date_difference($today_date, $fitness_expiry)) >= 0) {
                    // $count++; ?>
                    <li>
                      <a href="#">
                        <i class="fa fa-bullhorn text-danger"></i> Vehicle No. <?php // echo $row['vehicle_no']; ?>
                        Renew
                        Fitness
                      </a>
                    </li>
                    <?php //}
                    // if ((date_difference($today_date, $road_tax_expiry)) <= 7 and (date_difference($today_date, $road_tax_expiry)) >= 0) {
                    //$count++; ?>
                    <li>
                      <a href="#">
                        <i class="fa fa-bullhorn text-danger"></i> Vehicle No. <?php //echo $row['vehicle_no'];  ?>
                        Renew Road Tax
                      </a>
                    </li>
                    <?php //}
                    //if ($count == 0) { ?>
                    <li class="text-center align-self-center pt-5">
                      <i class="fa fa-bell-slash-o fa-2x"></i>
                      <p>No Notification</p>
                    <li>
                      <?php // }
                      //   }
                      // }
                      ?>
                  </ul>
                </li>
                <!-- <li class="footer"><a href="#">View all</a></li> -->
              </ul>
            </li>

            <!-- User Account-->
            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="images/user2-160x160.jpg" class="user-image rounded-circle" alt="User Image">
              </a>
              <ul class="dropdown-menu scale-up">
                <!-- User image -->
                <li class="user-header">
                  <img src="images/user2-160x160.jpg" class="float-left rounded-circle" alt="User Image">

                  <p>
                    <?php echo $udata['name']; ?>
                    <small class="mb-5"><?php echo $udata['email']; ?></small>
                    <small class="mb-5"><?php echo $udata['phone']; ?></small>
                    <small class="mb-5"><?php //echo date('d M Y h:i A', strtotime($udata['last_login'])); ?></small>
                  </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">
                  <div class="row no-gutters">
                    <div class="col-12 text-left">
                      <a href="#"><i class="ion ion-person"></i> My Profile</a>
                    </div>
                    <div class="col-12 text-left">
                      <a href="#"><i class="ion ion-email-unread"></i> Inbox</a>
                    </div>
                    <div class="col-12 text-left">
                      <a href="#"><i class="ion ion-settings"></i> Setting</a>
                    </div>
                  </div>
                  <!-- /.row -->
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                  <div class="pull-left">
                    <a href="change_password" class="btn btn-block btn-primary"><i class="ion ion-locked"></i> Change
                      Password</a>
                  </div>
                  <div class="pull-right">
                    <a href="#" class="btn btn-block btn-danger" onclick='logout()'><i class="ion ion-power"></i> Log
                      Out</a>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Messages -->
          </ul>
        </div>
      </nav>
    </header>