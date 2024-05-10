<?php
require_once ('required/header.php'); ?>
<?php require_once ('required/menu.php');
// die();
if ($user_type == 'Account') {
	echo "<script> window.location ='acc_dash'  </script>";
}

?>
<style>
	.tbl tr:last-child {
		background: #ffbf36;
	}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Dashboard
			<!-- <small><a href='acc_dash'>(Go to Account)</a></small> -->
		</h1>
		<ol class="breadcrumb">
			<!--<li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="breadcrumb-item active">Dashboard</li> -->
			<!-- <form action='required/master_process.php?task=change_session' method='post' role="form">

				<select name='session_year' class='form-control float-left' onchange='submit()'>
					<?php //dropdown_with_key($session_list, $db_name); ?>
				</select>
			</form> -->
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xl-3 col-md-6 col-6">
				<!-- small box -->
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3><?php //echo get_all('student', '*', array('status' => 'ACTIVE'))['count']; ?></h3>

						<p>Members</p>
					</div>
					<div class="icon">
						<i class="fa fa-male"></i>
					</div>
					<a href="add_member" class="small-box-footer">New Registration <i class="fa fa-arrow-right"></i></a>
				</div>
			</div>

			<div class="col-xl-3 col-md-6 col-6">
				<!-- small box -->
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3><?php //echo get_all('employee', '*', array('status' => 'ACTIVE'))['count']; ?></h3>

						<p>Total Fixed Deposits </p>
					</div>
					<div class="icon">
						<i class="fa fa-currency"></i>
					</div>
					<a href="add_member" class="small-box-footer">See Lists <i class="fa fa-arrow-right"></i></a>
				</div>
			</div>

			<div class="col-xl-3 col-md-6 col-6">
				<!-- small box -->
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3><?php //echo get_all('transport_area', '*', array('status' => 'ACTIVE'))['count']; ?></h3>

						<p>Total Recurring Deposits</p>
					</div>
					<div class="icon">
						<i class="fa fa-amount"></i>
					</div>
					<a href="add_member" class="small-box-footer">See Lists <i class="fa fa-arrow-right"></i></a>
				</div>
			</div>

			<div class="col-xl-3 col-md-6 col-6">
				<!-- small box -->
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3><?php //echo get_all('book_list', '*', array('status' => 'ACTIVE'))['count']; ?></h3>

						<p>Total Deposits </p>
					</div>
					<div class="icon">
						<i class="fa fa-table"></i>
					</div>
					<a href="add_member" class="small-box-footer">All Deposits <i class="fa fa-arrow-right"></i></a>
				</div>
			</div>
		</div>
		<!-- /.row -->

		<div class="row">
			<div class="col-xl-3 col-lg-6 col-12">
				<!-- Widget: user widget style 1 -->
				<div class="box box-widget widget-user-2">
					<!-- Add the bg color to the header using any of the bg-* classes -->
					<div class="widget-user-header bg-yellow p-2">
						<!--<div class="widget-user-image">-->
						<!--<img class="rounded-circle" src="../../images/user7-128x128.jpg" alt="User Avatar">-->
						<!--</div>-->
						<!-- /.widget-user-image -->
						<!--<h5 class="widget-user-username"></h5>-->
						<h3 class='text-center'>Statistics </h3>
					</div>
					<div class="box-footer no-padding">
						<ul class="nav d-block nav-stacked">
							<li class="nav-item"><a href="manage_member" class="nav-link">Male Members <span
										class="pull-right badge bg-blue"><?php echo get_all('members', '*', array('gender' => 'Male'))['count']; ?></span></a>
							</li>
							<li class="nav-item"><a href="manage_member" class="nav-link">Female Members <span
										class="pull-right badge bg-green"><?php echo get_all('members', '*', array('gender' => 'Female'))['count']; ?></span></a>
							</li>
							<li class="nav-item"><a href="manage_member" class="nav-link">Total FDs <span
										class="pull-right badge bg-yellow"><?php //echo get_all('student', '*', array('student_sex' => 'MALE'))['count']; ?></span></a>
							</li>
							<li class="nav-item"><a href="manage_member" class="nav-link">Total RDs <span
										class="pull-right badge bg-red"><?php //echo get_all('student', '*', array('student_sex' => 'FEMALE'))['count']; ?></span></a>
							</li>
							<li class="nav-item"><a href="manage_member" class="nav-link">Inactive Members <span
										class="pull-right badge bg-black"><?php echo get_all('members', '*', array('status' => 'INACTIVE'))['count']; ?></span></a>
							</li>
						</ul>
					</div>
				</div>
				<!-- /.widget-user -->
			</div>

			<div class="col-xl-9 connectedSortable">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Quick Access</h3>
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class='row'>
							<div class='col-md-6 text-center p-2'>
								<a href='add_member' title='New Registration'>
									<img src='icon/student.png' height='50px' />
									<br> New Registration
								</a>
							</div>

							<div class='col-md-6 text-center p-2'>
								<a href='collect_fee' title='New Deposit'>
									<img src='icon/printer.png' height='50px' />
									<br> New Deposit
								</a>
							</div>
							<!-- <div class='col-md-2 text-center p-2'>
								<a href='generate_demand' title='Demand Slip'>
									<img src='icon/calculation.png' height='50px' />
									<br> Create Demand
								</a>
							</div>
							<div class='col-md-2 text-center p-2'>
								<a href='send_sms' title='Send SMS'>
									<img src='icon/email.png' height='50px' />
									<br> Send SMS
								</a>
							</div>
							<div class='col-md-2 text-center p-2'>
								<a href='collect_fee' title='Student Ledger'>
									<img src='icon/girl.png' height='50px' />
									<br> Student Ledger</a>
							</div>
							<div class='col-md-2 text-center p-2'>
								<a href='collection_report' title='Collection Report'>
									<img src='icon/calc.png' height='50px' />
									<br> Collection Report</a>
							</div>

							<div class='col-md-2 text-center p-2'>
								<a href='create_certificate' title=' Create Certificate'>
									<img src='icon/test.png' height='50px' />
									<br> Create Certificate</a>
							</div>
							<div class='col-md-2 text-center p-2'>
								<a href='add_area' title=' Transport Area '>
									<img src='icon/school-bus.png' height='50px' />
									<br> Transport Area </a>
							</div>
							<div class='col-md-2 text-center p-2'>
								<a href='manage_account' title='Collection Report'>
									<img src='icon/ereader.png' height='50px' />
									<br> Expense Report</a>
							</div>

							<div class='col-md-2 text-center p-2'>
								<a href='issue_book' title=' Issue A Book'>
									<img src='icon/book.png' height='50px' />
									<br> Issue A Book </a>
							</div>
							<div class='col-md-2 text-center p-2'>
								<a href='book_return' title=' Book Return'>
									<img src='icon/book_1.png' height='50px' />
									<br> Book Return</a>
							</div>
							<div class='col-md-2 text-center p-2'>
								<a href='date_wise_report' title='Date with Report'>
									<img src='icon/writing.png' height='50px' />
									<br> Day Book </a>
							</div> -->

						</div>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->


		<div class="row">

			<div class="col-xl-12 connectedSortable">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title">Member Analysis</h3>

						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<table id="example1" class="table table-bordered table-striped">
							<tr class='bg-secondary text-light'>
								<th> FD / RD</th>
								<?php
								// foreach (array_filter($class_list) as $class) {
								// 	echo "<th>" . $class . " </th>";
								// }
								?>
							</tr>
							<?php
							// foreach (array_filter($section_list) as $section) {
							// 	echo "<tr><th>" . $section . "</th>";
							
							// 	foreach (array_filter($class_list) as $class) {
							// 		$link = encode('student_class=' . $class . '&student_section=' . $section);
							// 		echo "<th><a href='manage_student?link=$link'>" . studentcount($class, $section) . "</a></th>";
							// 	}
							// 	echo "</tr>";
							// }
							?>
							<tr>
								<th> Total </th>
								<?php
								// foreach (array_filter($class_list) as $class) {
								// 	echo "<th><a href='manage_student?student_class=$class&student_section='>" . studentcount($class) . "</a></th>";
								// }
								?>
							</tr>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->

		<div class="row">
			<div class="col-xl-6 connectedSortable">
				<!-- PRODUCT LIST -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Collection of Month</h3>

						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div id="payment_chart" style="width: 100%; height: 250px;"></div>
					</div>
					<!-- /.box-body -->

				</div>
			</div>
			<div class="col-xl-6 connectedSortable">
				<!-- PRODUCT LIST -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Registration of Month</h3>

						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div id="admission_chart" style="width: 100%; height: 250px;"></div>
					</div>
					<!-- /.box-body -->

				</div>
			</div>

		</div>
		<div class="row">

			<div class="col-xl-4 connectedSortable">
				<!-- PRODUCT LIST -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Last 3 Deposits </h3>

						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<ul class="products-list product-list-in-box">
							<?php //$respay = get_multi_data('receipt', array('paid_date' => $today), 'order by id desc limit 3');
							
							// if ($respay['count'] > 0) {
							// 	foreach ($respay['data'] as $pd) {
							?>
							<li class="item">
								<div class="product-img">
									<?php
									// $rid = $pd['id'];
									// $img =  get_data('student', $pd['student_id'], 'student_photo')['data']; ?>
									<!-- <img src="upload/<?php //echo $img; ?>" alt="Student Image"> -->
								</div>
								<div class="product-info">
									<a href="receipt.php?receipt_id=<?php //echo  $rid; ?>" target='_blank'
										class="product-title">
										<?php //echo get_data('student', $pd['student_id'], 'student_name')['data']; ?>
										<span class="label bg-yellow pull-right"><?php //echo $pd['paid_amount']; ?>
										</span></a>
									<span class="product-description">
										<?php //echo $pd['paid_month']; ?>
									</span>
									<span class="product-description">
										At <?php //echo date('h:i A', strtotime($pd['created_at'])); ?>
									</span>
								</div>
							</li>
							<?php //}
							//} ?>
						</ul>
					</div>
					<!-- /.box-body -->
					<div class="box-footer text-center">
						<a href="collection_report" class="uppercase">View All Deposits</a>
					</div>
					<!-- /.box-footer -->
				</div>
			</div>

			<div class="col-xl-4 connectedSortable">
				<!-- PRODUCT LIST -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Today Deposits</h3>

						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<ul class="products-list product-list-in-box">
							<table class='tbl table'>
								<?php
								// $payment = all_exp($today, $today);
								// foreach ($payment as $hd => $e_amt) {
								?>
								<tr>
									<td><?php //echo add_space($hd); ?> </td>
									<td style="text-align:right;"> <?php //echo $e_amt; ?></td>
								</tr>
								<?php //} ?>
							</table>
						</ul>
					</div>
					<!-- /.box-body -->
					<div class="box-footer text-center">
						<a href="manage_account" class="uppercase">View All Deposits</a>
					</div>
					<!-- /.box-footer -->
				</div>
			</div>

			<div class="col-xl-4 connectedSortable">
				<!-- PRODUCT LIST -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Today Collection</h3>

						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i
									class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<ul2 class="products-list product-list-in-box">
							<table class='tbl table'>
								<?php
								// $income = all_income($today, $today);
								// foreach ($income as $ed => $i_amt) {
								// 	if ($i_amt <> '') {
								?>
								<tr>
									<td><?php //echo add_space($ed); ?> </td>
									<td style="text-align:right;"> <?php //echo $i_amt; ?></td>
								</tr>
								<?php // }
								//} ?>
							</table>
							</ul>
					</div>
					<!-- /.box-body -->
					<div class="box-footer text-center">
						<a href="collection_report" class="uppercase">View All Collection</a>
					</div>
					<!-- /.box-footer -->
				</div>
			</div>

		</div>
		<!-- /.row -->



	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php require_once ('required/footer.php'); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
	window.addEventListener('load', () => {
		registerSW();
	});

	// Register the Service Worker
	async function registerSW() {
		if ('serviceWorker' in navigator) {
			try {
				await navigator
					.serviceWorker
					.register('sw.js');
			} catch (e) {
				console.log('SW registration failed');
			}
		}
	}
</script>

<script type="text/javascript">
	google.charts.load('current', {
		'packages': ['corechart']
	});
	google.charts.setOnLoadCallback(paymentChart);
	google.charts.setOnLoadCallback(admissionChart);

	function paymentChart() {
		var data = google.visualization.arrayToDataTable(<?php echo monthly_collection_graph(); ?>);

		var options = {
			title: 'Total Vs Paid',
			chartArea: {
				left: 30,
				top: 20,
				width: "80%",
				height: "80%"
			}
		};

		var chart = new google.visualization.LineChart(document.getElementById('payment_chart'));

		chart.draw(data, options);
	}

	function admissionChart() {
		var data = google.visualization.arrayToDataTable(<?php echo new_admission_graph(); ?>);

		var options = {
			title: 'Class Vs New Admission',
			//chartArea:{left:30,top:20,width:"70%",height:"80%"}
		};

		var chart = new google.visualization.PieChart(document.getElementById('admission_chart'));

		chart.draw(data, options);
	}
</script>