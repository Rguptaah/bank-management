<?php
require_once ('function.php');
$_POST = post_clean($_POST);
$_GET = post_clean($_GET);
if (isset($_GET['task'])) {
	$task = xss_clean($_GET['task']);
	$users_id = $_SESSION['users_id'];
	switch ($task) {
		case "verify_login": // Delete Any Data From Table 
			extract($_POST);
			$res = get_all('users', '*', array('username' => $username, 'password' => md5($password)));
			if ($res['count'] == 1) {
				if (!isset($_SESSION['initiated'])) {
					session_regenerate_id();
					$_SESSION['initiated'] = TRUE;
					$_SESSION['bank_token'] = md5(uniqid(rand(), TRUE));
					$_SESSION['token_time'] = time();
					$_SESSION['users_agent'] = 'bank_' . $_SERVER['HTTP_users_AGENT'];
				}
				$_SESSION['users_id'] = $users_id = $res['data'][0]['id'];
				$_SESSION['usersname'] = $res['data'][0]['usersname'];
				$_SESSION['users_type'] = $res['data'][0]['users_type'];
				update_data('users', array('token' => $token, 'status' => 'ACTIVE'), $users_id);
				$res['url'] = 'dashboard';
				session_regenerate_id();
			} else {
				$res['url'] = 'login';
			}
			echo json_encode($res);
			break;

		case "change_password": // Change Password of Logged in users
			$current_pass = md5($_POST['current_password']);
			$new_password = md5($_POST['new_password']);
			$where = array('id' => $users_id, 'password' => $current_pass);
			$res = update_multi_data('users', array('password' => $new_password), $where);
			echo json_encode($res);
			break;

		case "master_delete": // Delete Any Data From Table 
			extract($_POST);
			if ($_SESSION['users_type'] == 'Admin') {
				$searchdata = get_data($table, $id);
				if ($searchdata['count'] > 0) {
					$res = delete_data($table, $id, $pkey);
				}
			} else {
				$res = array('msg' => "Don't  have permission", 'status' => 'error');
			}
			echo json_encode($res);
			break;

		case "master_block": // BLOCK Any Data From Table 
			extract($_POST);
			//print_r($_POST);
			$bdata = array('status' => 'BLOCK');
			$res = update_data($table, $bdata, $id, $pkey);
			echo json_encode($res);
			break;

		case "block_users": // BLOCK Any Data From Table 
			extract($_POST);
			//print_r($_POST);
			$bdata = array('status' => $data_status);
			$res2 = update_data('center_details', $bdata, $id, 'center_code');
			$res = update_data('users', $bdata, $id, 'usersname');
			$res['msg'] = 'users and Center ' . $data_status . ' Successfully';
			$res['url'] = 'show_users';
			echo json_encode($res);
			break;


		case "logout":
			$rtype = 'direct';
			extract($_POST);
			if ($_SESSION['bine_token'] != '') {
				$users_id = $_SESSION['users_id'];
				$users_type = $_SESSION['users_type'];
				$result = update_data('users', array('token' => '', 'status' => 'LOGOUT'), $users_id);
				echo json_encode($result);
				if ($result['status'] == 'success') {
					unset($_SESSION['usersname']);
					unset($_SESSION['users_type']);
					unset($_SESSION['users_id']);
					unset($_SESSION['bine_token']);
					session_destroy();
					$url = 'login';
				} else {
					$url = 'dashboard';
				}
			} else {
				$url = 'login';
			}
			if ($rtype == 'AJAX') {
				$result['url'] = $url;
				json_encode($result);
			} else {
				$result['url'] = 'login';
				json_encode($result);
			}
			break;

		case "forget_password":
			$usersname = $_POST['usersname'];
			$sql = "select * from users where usersname ='$usersname' and status not in ('AUTO','DELETED')";
			$res = direct_sql($sql);
			//print_r($res);
			if ($res['count'] > 0) {
				$id = $res['data'][0]['id'];
				$users_type = $res['data'][0]['users_type'];
				$email = $res['data'][0]['users_email'];
				$mobile = $res['data'][0]['users_mobile'];
				$name = $res['data'][0]['full_name'];

				$np = rnd_str(6);
				$up = array('password' => md5($np));
				$res = update_data($users_type, $up, $id, 'id');
				$sms = "Dear " . $name . " Your new password is " . $np . " kindly change after login " . $inst_name;
				rtf_mail($email, "Password Recover of $inst_name ", $sms);
				//bulk_sms($mobile,$sms);
				$data['id'] = $id;
				$data['status'] = 'success';
				$data['msg'] = "New Password Successfully Send to $email";
			} else {
				$data['id'] = 0;
				$data['status'] = 'error';
				$data['msg'] = 'No any users exist with this ID. Try Again';
			}
			echo json_encode($data);
			break;

		case "get_dist":
			$state_code = $_GET['state_code'];
			$res = get_all('district', '*', array('state_code' => $state_code), 'name');
			foreach ($res['data'] as $data) {
				$id = $data['id'];
				echo "<option value='" . $data['code'] . "'>" . $data['name'] . "</option>";
			}
			break;


		case "upload":
			$result = upload_img('uploadimg', 'rand', 'upload');
			echo json_encode($result);
			break;
		case "uploadaadhar":
			$result = upload_img('uploadaadhar', 'rand', 'upload');
			echo json_encode($result);
			break;
		/*============= STANDARD TASK END ===============*/

		case "update_member":
			extract($_POST);
			$res = update_data('members', $_POST, $_POST['id']);
			$mem_new = array('id' => $_POST['id'], 'reg_no' => $_POST['reg_no']);
			$res['url'] = 'manage_member';
			echo json_encode($res);
			break;

		case "cancel_receipt":
			extract($_POST);
			//print_r($_POST);
			$rid = $_POST['receipt_id'];
			$cancel_remarks = $_POST['cancel_remarks'];
			$cancel_at = date('Y-m-d h:i:s');
			$i = 1;
			$student_id = get_data('receipt', $rid, 'student_id')['data'];
			$paid_month = get_data('receipt', $rid, 'paid_month')['data'];
			$new_dues = get_data('receipt', $rid, 'previous_dues')['data'];
			$month_list = explode(",", $paid_month);
			foreach ($month_list as $colname) {
				$sql2 = "update student_fee set $colname = null, current_dues ='$new_dues' where student_id ='$student_id'";
				$res = mysqli_query($con, $sql2) or die("Update Student Month Error : " . mysqli_error($con));
				$i = $i + 1;
			}
			if ($i > 1) {
				$cancel_data = array('status' => 'CANCEL', 'cancel_by' => $users_id, 'cancel_at' => $cancel_at, 'cancel_remarks' => $cancel_remarks);
				$res2 = update_data('receipt', $cancel_data, $rid);
				if ($res2['status'] == 'success') {
					$res2['msg'] = "Receipt No. $rid Cancelled Successfully";
				}
				echo json_encode($res2);
			}
			break;


		case "bulk_import":
			extract($_POST);
			echo "<pre>";
			$res = csv_import($table, $pkey);
			print_r($res);
			//echo "<script> window.location='bulk_import.php?res=".json_encode($res)."' </script>";
			break;

		case "bulk_export":
			if ($_SESSION['users_type'] == 'Admin') {
				$status = $_GET['status'];
				csv_export($_REQUEST['table']);
			}
			break;

		case "update_users":
			$_POST['usersname'] = remove_space($_POST['usersname']);
			$_POST['password'] = md5($_POST['password']);
			$res = update_data('users', $_POST, $_POST['id']);
			$res['url'] = 'add_users';
			echo json_encode($res);
			break;

		case "save_photo":
			$baseFromJavascript = $_POST['profile_pic']; //your data in base64 'data:image/png....';
			$base_to_php = explode(',', $baseFromJavascript);
			$data = base64_decode($base_to_php[1]);
			$file_name = date('ymdhis') . "_" . rnd_str(5) . ".png";
			$filepath = "required/upload/no_image.jpg "; //.$file_name; // or image.jpg
			file_put_contents($filepath, $data);
			rename($filepath, 'required/upload/' . $file_name);
			$res['msg'] = "The file " . $file_name . " has been uploaded.";
			$res['id'] = $file_name;
			$res['status'] = 'success';
			echo json_encode($res);
			break;

		default:
			echo "<script> alert('Invalid Action'); window.location ='" . $_SERVER['HTTP_REFERER'] . "' </script>";
	}
}
