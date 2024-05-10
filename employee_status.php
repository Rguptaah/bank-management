<?php require_once('required/header.php'); ?>
<?php require_once('required/menu.php');
if (isset($_GET['status']) and $_GET['student_type'] == '') {
	$status = $_GET['status'];
	$res = get_all('student', '*', array('status' => $status));
} else if (isset($_GET['student_type']) and $_GET['status'] == '') {
	$student_type = $_GET['student_type'];
	$res = get_all('student', '*', array('student_type' => $student_type));
} else if (isset($_GET['status']) and $_GET['student_type'] != '') {
	$student_type = $_GET['student_type'];
	$status = $_GET['status'];
	$res = get_all('student', '*', array('status' => $status, 'student_type' => $student_type));
} else {
	$res = get_all('employee');
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1> Employee Report</h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="breadcrumb-item"><a href="#extra">Extra</a></li>
			<li class="breadcrumb-item active">Employee Details</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-12">
				<div class="box">
					<div class="box-header with-border">
						<form action='send_sms.php' method='post'>
							<h3 class="box-title">Employee Details
								<input type='hidden' name='mobiles' id='mobiles'>
								<input type='submit' class='btn btn-success btn-xs' value='Send SMS'>
							</h3>
						</form>
						<div class="box-tools pull-right">
							<form>
								<select name='status'>
									<?php dropdown($status_list, $status); ?>
								</select>
								<select name='employee_type'>
									<?php dropdown($employee_type_list, $employee_type); ?>
								</select>
								<button class='btn btn-orange'> Show </button>
							</form>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="table-responsive">
							<table id="example" class="table table-bordered table-hover display nowrap margin-top-10">
								<thead>
									<tr>


										<th>Employee Name</th>
										<th>Designation </th>
										<th>Qualification</th>
										<th>Professional</th>
										<th>Address</th>
										<th>Mobile</th>
										<th>Email</th>
										<th>Adhar No. </th>

									</tr>
								</thead>
								<tbody>
									<?php

									if ($res['count'] > 0) {
										foreach ($res['data'] as $row) {
											$id = $row['id'];
											$status = $row['status'];
											echo "<tr class='odd gradeX'>";


											echo "<td>" . $row['e_name'] . "</td>";
											echo "<td>" . $row['e_designation'] . "</td>";
											echo "<td>" . $row['e_qualification'] . "</td>";
											echo "<td>" . $row['e_professional'] . "</td>";
											echo "<td>" . $row['e_address'] . "</td>";

											echo "<td><span class='mobile' title='Click to send SMS'>" . $row['e_mobile'] . "</span></td>";
											echo "<td>" . $row['e_email'] . "</td>";
											echo "<td>" . $row['e_adhar'] . "</td>";

											echo "</tr>";
										}
									}
									?>

								</tbody>
							</table>
						</div>
					</div>

				</div>

			</div>
	</section>
</div>
<?php require_once('required/footer2.php'); ?>