<?php require_once("required/function.php");	?>
<title> <?php echo $inst_name; ?></title>

<style>
	@import url('https://fonts.googleapis.com/css2?family=Roboto&display=swap');

	body {
		color: #000;
		margin: 10px;
		padding: 0px;
		font-family: 'Roboto', sans-serif;
	}

	td {
		font-size: 10px;
		padding: px;
		line-height: 14px;
		font-weight: 800;
		padding-left: 10px;
	}

	.idcard {
		width: 345px;
		border: solid 0px #ddd;
		height: 200px;
		background: url('assets/img/idcard_back.png') no-repeat;
		background-size: 345px 202px;
		margin: 4px 15px;
		float: left;
		page-break-before: always;
		position: relative;
	}

	.photo {
		position: absolute;
		width: 70px;
		height: 70px;
		top: 80px;
		right: 10px;
		z-index: 0;
		border: solid 0px #000;
		border-radius: 5px;
	}

	.adm {
		position: absolute;
		width: 70px;
		height: 15px;
		top: 150px;
		right: 10px;
		z-index: 0;
		border: solid 0px #000;
		border-radius: 2px;
		background: #fff;
		text-align: center;
		color: maroon;
		font-weight: 600;
		font-size: 10px;
		opacity: 0.75;
	}

	.session {
		position: absolute;
		width: 45px;
		height: 10px;
		margin: auto;
		top: 65px;
		right: 10px;
		z-index: 0;
		border: solid 0px #000;
		padding: 2px 6px;
		display: none;
	}

	.content {
		margin-left: 10px;
		width: 265px;
	}

	@media print {
		#printbtn {
			display: none;
		}

		.idcard {
			page-break-inside: avoid;
		}

		@page {
			size: portrait;
		}
	}
</style>
<button onclick="window.print()">Print </button>

<?php
extract($_POST);
$student_type = "'" . implode("','", $student_type) . "'";
$sql = "select * from student where student_type in($student_type) and student_class = '$student_class' and student_section ='$student_section'  and  status ='ACTIVE'";
$res = direct_sql($sql);
if ($res['count'] > 0) {

	foreach ($res['data'] as $student) {
		$student_id = $student['id'];
		$student_admission = $student['student_admission'];
?>
		<table border='1' class='idcard' rules='all'>
			<tr height='60px'>
				<td colspan='2' align='center'>
					<img src='images/logo.png' align='left' width='60px'>
					<span style='color:red;font-size:14px;margin:5px;line-height:20px;'><?php echo $full_name; ?></span>
					<br><span style='background:#ddd;color:#313140;padding:0px 5px;'> (Affiliated to CBSE, New Delhi upto 10+ 2) </span>
					<br><?php echo $inst_address1; ?>,<?php echo $inst_address2; ?>
					<br>Contact No.: <?php echo $inst_contact; ?>
				</td>
			</tr>
			<tr height='60px'>
				<td>
					<div class='session'> <?php echo $student['student_session']; ?> </div>

					<div class='content'>
						<span style='font-size:14px;line-height:20px;color:maroon;'><?php echo $student['student_name']; ?></span><br>
						Class : <?php echo $student['student_class']; ?> -<?php echo $student['student_section']; ?><br>
						Roll No. : <?php echo $student['student_roll']; ?> <br>
						Father's Name : <?php echo $student['student_father']; ?> <br>
						Address : <?php echo $student['student_address1']; ?> <br>
						Blood group : <?php echo $student['student_bloodgroup']; ?> <br>
						Contact No : <?php echo $student['student_mobile']; ?>
					</div>


					<center><img src='upload/<?php echo $student['student_photo']; ?>' alt='<?php echo  $student['student_photo']; ?>' class='photo' />

						<span class='adm'>
							<?php echo $student['student_admission']; ?><br>
							<img src='images/sign.jpg' height='20px'><br>
							Principal
						</span>

					</center>
				</td>
			</tr>
		</table>


<?php }
} ?>