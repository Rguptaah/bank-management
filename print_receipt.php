<?php require_once ('required/header.php'); ?>
<?php require_once ('required/menu.php'); ?>
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1> Receipt </h1>
		<ol class="breadcrumb">
			<li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
			<li class="breadcrumb-item active">Receipt</li>
		</ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Basic Forms -->
		<div class="box box-default">
			<div class="box-header with-border">
				<h3 class="box-title"> Receipt Details </h3>

				<!-- <div class="box-tools pull-right">
					<form action='report_card.php' action='get'>
						Filter By
						<select name="student_class">
							<?php //dropdown($class_list, $student_class) ?>
						</select>
						<select name="student_section" onchange='submit()' accesskey='l'>
							<?php //dropdown($section_list, $student_section) ?>
						</select>
					</form>
				</div> -->
			</div>
			<div class="box-body">

				<div class="table-responsive">
					<form action='receipt_print.php' method='post' id="receipt_print">
						<table id="example" class="table table-bordered table-striped">
							<thead>
								<tr>

									<th>#</th>
									<th>Student Name</th>
									<th>Father's Name</th>
									<th>Date of Birth</th>
									<th>Reg No. </th>
									<th>Email</th>
									<th>Mobile</th>

								</tr>
							</thead>
							<tbody>

								<?php
								//$sql ="select * from student where student_photo <> 'no_image.jpg'";
								if (isset($_REQUEST['reg_no'])) {
									$reg_no = trim($_REQUEST['reg_no']);
									$email = trim($_REQUEST['email']);
									$sql = "select * from member where reg_no = '$reg_no' and email = '$email' and status <>'BLOCK'";
								} else {
									$sql = "select * from member where status <>'BLOCK'";
								}

								$res = mysqli_query($con, $sql) or die("Error in selecting Member" . mysqli_error($con));

								while ($row = mysqli_fetch_array($res)) {
									$mem_id = $row['id'];
									$status = $row['status'];
									echo "<tr class='odd gradeX'>";
									echo "<td>" . "</td>";
									echo "<td>" . $row['name'] . "</td>";
									echo "<td>" . $row['father_name'] . "</td>";
									if (date('d-M-y', strtotime($row['dob'])) <> '01-Jan-70') {
										echo "<td>" . date('d-M-y', strtotime($row['dob'])) . "</td>";
									} else {
										echo "<td></td>";
									}
									echo "<td>" . trim($row['reg_no']) . "</td>";
									echo "<td>" . trim($row['email']) . "</td>";
									echo "<td>" . $row['phone'] . "</td>";
								}
								?>
								</tr>
							</tbody>
							<!--<a href='print_id.php?student_id=$stu_id' title='Print I Card' >-->
							<tfoot>
								<tr>
									<td colspan='13'>
										<center>
											<input type='submit' class='btn btn-danger btn-xs' value='Print Receipt'>
										</center>
									</td>
								</tr>
							</tfoot>
						</table>

					</form>
				</div>


			</div>

		</div>
		<!-- end page-wrapper -->

</div>

<!-- end wrapper -->

<?php require_once ('required/footer2.php'); ?>
<script language="JavaScript">
	function selectAll(source) {
		checkboxes = document.getElementsByName('sel_id[]');
		for (var i in checkboxes)
			checkboxes[i].checked = source.checked;
	}
</script>

</body>

</html>