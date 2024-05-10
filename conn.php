<?php
require_once('required/op_config.php');
if (isset($_SESSION['db_name'])) {
	$db_name = $_SESSION['db_name'];
}
$con = mysqli_connect($host_name, $db_user, $db_password, $db_name) or die(" Error No  1 : Unable to Connect, Check the Connection Parameter. " . mysqli_error($con));

/*---------OfferPlant Master Functions-------------*/

function encode($input)
{
	return strtr(base64_encode($input), '+/=', '._-');
}

function decode($input)
{
	$url = base64_decode(strtr($input, '._-', '+/='));
	//$parts = parse_url($url);
	parse_str($url, $query);
	return $query;
}

function xss_clean($data)
{
	// Fix &entity\n;
	$data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

	// Remove any attribute starting with "on" or xmlns
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

	// Remove javascript: and vbscript: protocols
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

	// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

	// Remove namespaced elements (we do not need them)
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

	do {
		// Remove really unwanted tags
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	} while ($old_data !== $data);

	// we are done...
	return $data;
}

function post_clean($arr_data)
{
	foreach ($arr_data as $data) {

		$key = array_search($data, $arr_data);
		if (is_array($data)) {
			post_clean($data);
		} else {
			$arr_data[$key] = xss_clean($data);
		}
	}
	return $arr_data;
}

function rnd_str($length_of_string)
{

	// String of all alphanumeric character 
	$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

	// Shufle the $str_result and returns substring 
	// of specified length 
	return strtoupper(substr(str_shuffle($str_result), 0, $length_of_string));
}

function verify_request()
{
	$ref = parse_url($_SERVER["HTTP_REFERER"]);
	$rh  = $ref['host'];
	$mh = $_SERVER['HTTP_HOST'];

	if ($rh <> $mh) {
		die("Invalid Access");
	}
}

function verify($user_type)
{
	$all_page = scandir('../bine');
	$actual_link = "http://" . $_SERVER['HTTP_HOST']; //$_SERVER['REQUEST_URI'];

	//die($actual_link);
	$current_page = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
	if ($user_type == 'Admin') {
		global $admin_role;
		$all_page = $admin_role;
	} else if ($user_type == 'Account') {
		global $account_role;
		$all_page = $account_role;
	} else if ($user_type == 'DBA') {
		global $dba_role;
		$all_page = $dba_role;
	} else {
		die("Don't Have Permission");
	}

	if (!array_search($current_page, $all_page)) {
		die("Don't have Permission");
	}
}
function add_column($table_name, $col_name, $data_type = 'varchar(255)', $default = null)
{
	global $con;
	$sql = "alter table $table_name add column $col_name $data_type $default";
	$res = mysqli_query($con, $sql) or die("Error in Adding Coumn" . mysqli_error($con));
}
function remove_column($table_name, $col_name)
{
	global $con;
	$sql = "alter table $table_name drop column $col_name ";
	$res = mysqli_query($con, $sql) or die("Error in Removing Column" . mysqli_error($con));
}


function insert_row($table_name)
{
	global $con;
	global $user_id;
	$result = get_multi_data($table_name, array('created_by' => $user_id, 'status' => 'AUTO'), ' order by id desc limit 1');
	if ($result['count'] < 1) {
		$result = insert_data($table_name, array('status' => 'AUTO'));
		$id = $result['id'];
	} else {
		$id = $result['data'][0]['id'];
	}
	return array('table' => $table_name, 'id' => $id);
}

function insert_data($table_name, $ArrayData)
{
	global $con;
	global $user_id;
	//echo"<pre>";
	//print_r($ArrayData);
	$ArrayData['created_by'] = $user_id;
	$columns = implode(", ", array_keys($ArrayData));
	$escaped_values = array_values($ArrayData);
	foreach ($escaped_values as $newvalue) {
		$newvalues[] = "'" . preg_replace('/[^A-Za-z_.@,+:0-9\-]/', ' ', $newvalue) . "'";
	}
	//$data = mysqli_escape_string ($escaped_values);
	$values  = implode(", ", $newvalues);

	$sql = "INSERT IGNORE INTO $table_name ($columns) VALUES ($values)";

	$res = mysqli_query($con, $sql) or die("Error in Inserting Data" . mysqli_error($con));
	$id = mysqli_insert_id($con);
	if (mysqli_affected_rows($con) > 0) {
		$result['id'] = $id;
		$result['status'] = 'success';
		$result['msg'] = " Data Inserted Successfully";
	} else {
		$result['id'] = 0;
		$result['status'] = 'fail';
		$result['msg'] = mysqli_error($con);
	}
	return $result;
}
function insert_html($table_name, $ArrayData)
{
	global $con;
	global $user_id;
	//echo"<pre>";
	//print_r($ArrayData);
	$ArrayData['created_by'] = $user_id;
	$columns = implode(", ", array_keys($ArrayData));
	$escaped_values = array_values($ArrayData);
	foreach ($escaped_values as $newvalue) {
		$newvalues[] = "'" . htmlspecialchars($newvalue) . "'";
	}
	//$data = mysqli_escape_string ($escaped_values);
	$values  = implode(", ", $newvalues);

	$sql = "INSERT IGNORE INTO $table_name ($columns) VALUES ($values)";

	$res = mysqli_query($con, $sql) or die("Error in Inserting Data" . mysqli_error($con));
	$id = mysqli_insert_id($con);
	if (mysqli_affected_rows($con) > 0) {
		$result['id'] = $id;
		$result['status'] = 'success';
		$result['msg'] = " Data Inserted Successfully";
	}
	return $result;
}
function update_data($table_name, $ArrayData, $id, $pkey = 'id')
{
	global $con;
	$cols = array();
	foreach ($ArrayData as $key => $value) {
		$newvalue = preg_replace('/[^A-Za-z._@,:+0-9\-]/', ' ', $value);
		$cols[] = "$key = '$newvalue'";
	}
	$sql = "UPDATE $table_name SET " . implode(', ', $cols) . " WHERE $pkey  ='" . $id . "'";
	$res = mysqli_query($con, $sql) or mysqli_error($con);
	$num = mysqli_affected_rows($con);
	if ($num > 0) {
		$result['status'] = 'success';
		$result['msg'] = $num . " Record Updated Successfully";
	} else {
		$result['status'] = 'Fail';
		$result['msg'] = $num . " No Update found";
	}
	return $result;
}

function create_list($table_name, $field,  $whereArr = null)
{
	global $con;
	$cols = array();
	foreach ($field as $key => $value) {
		$newvalue = preg_replace('/[^A-Za-z.@,:+0-9\-]/', ' ', $value);
		$cols[] = "$key = '$newvalue'";
	}

	foreach ($whereArr as $key => $value) {
		$newvalue = preg_replace('/[^A-Za-z.@,:+0-9\-]/', ' ', $value);
		$where[] = "$key = '$newvalue'";
	}

	$sql = "select * from " . $table_name . " WHERE " . implode('and ', $where);
	$res = mysqli_query($con, $sql) or die(" Error IN create List : " . mysqli_error($con));
	if (mysqli_num_rows($res) >= 1) {
		while ($row = mysqli_fetch_assoc($res)) {
			$list[] = $row[$field];
		}
	} else {
		return null;
	}
	return $list;
}

function update_multi_data($table_name, $ArrayData, $whereArr)
{
	global $con;
	$cols = array();
	foreach ($ArrayData as $key => $value) {
		$newvalue = preg_replace('/[^A-Za-z.@_,:+0-9\-]/', ' ', $value);
		$cols[] = "$key = '$newvalue'";
	}

	foreach ($whereArr as $key => $value) {
		$newvalue = preg_replace('/[^A-Za-z.@_,:+0-9\-]/', ' ', $value);
		$where[] = "$key = '$newvalue'";
	}

	$sql = "UPDATE $table_name SET " . implode(', ', $cols) . " WHERE " . implode('and ', $where);
	$res = mysqli_query($con, $sql) or mysqli_error($con);
	$num = mysqli_affected_rows($con);
	if ($num > 0) {
		$result['status'] = 'success';
		$result['msg'] = $num . " Multi Record Updated Successfully";
	} else {
		$result['status'] = 'Fail';
		$result['msg'] = $num . " No Update found";
	}
	return $result;
}

function delete_data($table_name, $id)
{
	global $con;
	$sql = "delete from $table_name WHERE id  ='" . $id . "'";
	$res = mysqli_query($con, $sql) or die("Error in Getting Data" . mysqli_error($con));
	$num = mysqli_affected_rows($con);
	if ($num >= 1) {
		$result['status'] = 'success';
		$result['msg'] = $num . " Record deleted successfully";
	} else {
		$result['status'] = 'failure';
		$result['msg'] = "Soory ! No Record found to delete";
	}
	return $result;
}

function get_all($table_name, $column_list = '*', $status = null, $pkey = 'id')
{
	global $con;
	//$column_list['id'] ='id';
	//$column_list['status'] ='status';
	if ($column_list <> '*') {
		$column_list = implode(',', $column_list);
	}

	if ($status) {
		$sql = "SELECT $column_list FROM $table_name where $pkey ='$status'";
	} else {
		$sql = "SELECT $column_list FROM $table_name";
	}
	$res = mysqli_query($con, $sql) or die("Error In Loding Data : " . mysqli_error($con));
	if (mysqli_num_rows($res) >= 1) {
		while ($row = mysqli_fetch_assoc($res)) {
			$data[] = $row;
		}
		return json_encode($data);
	} else {
		return "No Record Found";
	}
}

function direct_sql($sql, $isCount = true)
{
	global $con;
	$res = mysqli_query($con, $sql) or die("Error In Loding Data : " . mysqli_error($con));
	if ($isCount == true) {
		$result['count'] = mysqli_num_rows($res);
	}
	if (mysqli_num_rows($res) >= 1) {
		while ($row = mysqli_fetch_assoc($res)) {
			$result[] = $row;
		}
		return json_encode($result);
	} else {
		return "No Record Found";
	}
}
function get_data($table_name, $id, $field_name = null, $pkey = 'id')
{
	global $con;
	$sql = "SELECT * FROM $table_name where $pkey ='$id' ";
	$res = mysqli_query($con, $sql) or die(" Student Information Error : " . mysqli_error($con));
	if (mysqli_num_rows($res) >= 1) {
		$row = mysqli_fetch_assoc($res);
		extract($row);
		if ($field_name) {
			return $row[$field_name];
		} else {
			return json_encode($row);
		}
	} else {
		return " No Record Found";
	}
}

function get_array($table_name, $id, $field_name = null)
{
	global $con;
	$sql = "SELECT * FROM $table_name where id ='$id' ";
	$res = mysqli_query($con, $sql) or die(" Data Information Error : " . mysqli_error($con));
	if (mysqli_num_rows($res) >= 1) {
		$row = mysqli_fetch_assoc($res);
		extract($row);
		if ($field_name) {
			return $row[$field_name];
		} else {
			return $row;
		}
	} else {
		return " No Record Found";
	}
}
function get_multi_data($table_name, $whereArr, $order = null)
{
	global $con;

	foreach ($whereArr as $key => $value) {
		$newvalue = preg_replace('/[^A-Za-z.@_,:+0-9\-]/', ' ', $value);
		$where[] = "$key = '$newvalue'";
	}

	$sql = "select * from " . $table_name . " WHERE " . implode('and ', $where) . $order;
	$res = mysqli_query($con, $sql) or mysqli_error($con);
	$num = mysqli_num_rows($res);
	if ($num > 0) {
		while ($row = mysqli_fetch_assoc($res)) {
			$data[] = $row;
		}
		$result['status'] = 'success';
		$result['count'] = $num;
		$result['data'] = $data;
	} else {
		$result['status'] = 'error';
		$result['count'] = 0;
		$result['data'] = null;
	}
	return $result;
}
function remove_space($str)
{
	$str = trim($str);
	return strtolower(preg_replace("/[^a-zA-Z0-9]+/", "_", $str));
}

function add_space($str)
{
	$str = trim($str);
	return ucwords(str_replace('_', ' ', $str));
}

function dropdown($array_list, $selected = null)
{
	foreach ($array_list as $list) {
?>
		<option value='<?php echo $list; ?>' <?php if ($list == $selected) echo "selected"; ?>><?php echo $list; ?></option>
	<?php
	}
}
function dropdown_with_key($array_list, $selected = null)
{
	foreach ($array_list as $list) {
		$key = array_search($list, $array_list);
	?>
		<option value='<?php echo $key; ?>' <?php if ($list == $selected) echo "selected"; ?>><?php echo $list; ?></option>
	<?php
	}
}
function countr($table, $col_name)
{
	global $con;
	$query = "select count($col_name) from $table";

	$res = mysqli_query($con, $query) or die(" User Information Error : " . mysqli_error($con));
	$row = mysqli_fetch_assoc($res);
	return mysqli_num_rows($res);
}
function bulksmsold($no, $msg, $count, $ContentType = 'english')
{
	global $con;
	global $sender_id;
	global $auth_key;
	$no = urlencode($no);
	$msg = substr(urlencode($msg), 0, 1000);

	/*--------------------SEND SMS ---------------------*/
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	echo	$url = "http://msg.morg.in/rest/services/send_sms/sendGroupSms?AUTH_KEY=$auth_key&message=$msg&senderId=$sender_id&routeId=1&mobileNos=$no&smsContentType=$ContentType";

	curl_setopt($ch, CURLOPT_URL, $url);
	$res = curl_exec($ch);

	if ($res) {
		echo ("<div class='alert alert-success alert-dismissable'>
				 <button type='button' class='close' data-dismiss='aler' aria-hidden='true'>&times;</button>
				 <i class='fa fa-warning fa-2x'></i>&nbsp;&nbsp; Thanks ! $count SMS send Sucessfully. </div>");
	}
}

function bulksms($mobile_list, $sms, $count = 1,  $smstype = 'english')
{
	global $auth_key;
	global $sender_id;

	$data  = array('smsContent' => $sms, 'groupId' => '', 'routeId' => 1, 'mobileNumbers' => $mobile_list, 'senderId' => $sender_id, 'signature' => '', 'smsContentType' => $smstype);
	$text_sms  = json_encode($data);
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "http://msg.morg.in/rest/services/send_sms/sendGroupSms?AUTH_KEY=$auth_key",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $text_sms,
		CURLOPT_HTTPHEADER => array(
			"Cache-Control: no-cache",
			"Content-Type: application/json"
		),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		echo "cURL Error #:" . $err;
	} else {
		echo $count . " SMS send Successfully";
	}
}
function send_sms($number, $sms)
{
	if (preg_match('/^[6-9]{1}[0-9]{9}+$/', $number) == 1) {
		$no = '91' . urlencode($number);
		$msg = substr(urlencode($sms), 0, 340);
		global $sender_id;
		global $auth_key;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$url = "http://msg.morg.in/rest/services/send_sms/sendGroupSms?AUTH_KEY=$auth_key&message=$msg&senderId=$sender_id&routeId=1&mobileNos=$no&smsContentType=english";
		curl_setopt($ch, CURLOPT_URL, $url);
		$res = curl_exec($ch);
		curl_close($ch);
	}
	return $res;
}

function uploadimg($file_name, $target_dir = 'upload/', $imgkey = 'rand')
{
	if ($imgkey == 'rand') {
		$imgkey = rand(10000, 99999);
	}
	// $target_dir = "upload/";
	$target_file = $imgkey . "_" . basename($_FILES[$file_name]["name"]);
	$target_file = strtolower(preg_replace("/[^a-zA-Z0-9.]+/", "", $target_file));
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
	// Check if image file is a actual image or fake image

	$check = getimagesize($_FILES[$file_name]["tmp_name"]);
	if ($check !== false) {
		echo "File is an image - " . $check["mime"] . ".";
		$uploadOk = 1;
	} else {
		echo "File is not an image.";
		$uploadOk = 0;
	}

	// Check if file already exists
	if (file_exists($target_file)) {
		unlink($target_file);
		//echo "Sorry, file already exists.";
		$uploadOk = 1;
	}
	// Check file size
	if ($_FILES[$file_name]["size"] > 5000000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "pdf") {
		echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES[$file_name]["tmp_name"], $target_dir . $target_file)) {
			//echo "The file ". basename( $_FILES[$file_name]["name"]). " has been uploaded.";
			return $target_file;
		} else {
			echo "<script> alert('Sorry, there was an error uploading your file.'')</script>";
		}
	}
}


function rowcount($table, $field_name = 'id')
{
	global $con;
	$query = "SELECT distinct($field_name) from $table";
	$res = mysqli_query($con, $query) or die(" Count Error : " . mysqli_error($con));
	$count  = mysqli_num_rows($res);
	return $count;
}



function mymail($to, $subject, $msg)
{
	global $noreply_email;
	global $inst_name;
	$from = $noreply_email;

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Create email headers
	$headers .= 'From: ' . $from . "\r\n" .
		'Reply-To: ' . $from . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	// Compose a simple HTML email message
	$message = '<html><body>';
	$message .= '<table width="600px" border="1" Cellpadding="0" cellspacing="0" align="center" height="400" bgcolor="azure">';
	$message .= '<tr height="25px" bgcolor="orange"><td align="center"><h2>' . $subject . '</h2></td></tr>';
	$message .= '<tr><td><div style="color:dodgerblue;font-size:16px;font-family:arial;text-align:center;">' . $msg . '</div></td></tr>';
	$message .= '<tr><td><div style="color:dodgerblue;font-size:16px;font-family:arial;text-align:center;">' . $inst_name . '</div></td></tr>';
	$message .= '</table></body></html>';

	// Sending email
	if (mail($to, $subject, $message, $headers)) {
		return 'success';
	} else {
		return 'Failed';
	}
}

function dropdown_list($tablename, $value, $list, $selected = null)
{
	global $con;
	$i = 0;
	$query = "select distinct $list, $value from $tablename order by $list";
	$res = mysqli_query($con, $query) or die(" Creating Drop down Error : " . mysqli_error($con));
	while ($row = mysqli_fetch_array($res)) {
		$key = $row[$value];
		$show = $row[$list];
		//echo $selected;
	?>

		<option value='<?php echo $key; ?>' <?php if ($key == $selected) echo "selected"; ?>><?php echo $show; ?></option>
<?php
	}
}


function updatetable($table1, $table2, $col1, $col2)
{
}
/*-------------END of OfferPlant Master Function ------------*/

$all_data = [];

function userid($username)
{
	global $con;
	$query = "select * from user where user_name ='$username'";
	$res = mysqli_query($con, $query) or die(" User Information Error : " . mysqli_error($con));
	$row = mysqli_fetch_assoc($res);
	extract($row);
	//print_r($row);
	return $row['id'];
}
function studentid($admission_no)
{
	global $con;
	$query = "select * from student where student_admission='$admission_no' and status='ACTIVE'";
	$res = mysqli_query($con, $query) or die(" User Information Error : " . mysqli_error($con));
	$row = mysqli_fetch_assoc($res);
	if (mysqli_num_rows($res) > 0) {
		extract($row);
		//print_r($row);
		return $row['id'];
	} else {
		die("No Record Found");
	}
}
function studentcount($class_name, $section_name)
{
	global $con;
	$query = "SELECT count(id) FROM `student` where student_class ='$class_name' and student_section ='$section_name' and status <>'BLOCK'";
	$res = mysqli_query($con, $query) or die(" Error in student_count : " . mysqli_error($con));
	$row = mysqli_fetch_assoc($res);
	extract($row);
	//print_r($row);
	return $row['count(id)'];
}

function get_fee($student_id, $fee_name)
{
	global $con;
	$student_class = trim(get_data('student', $student_id, 'student_class'));
	$query = "select * from fee_details where student_class='$student_class'";
	$res = mysqli_query($con, $query) or die(" User Information Error : " . mysqli_error($con));
	$row = mysqli_fetch_assoc($res);
	extract($row);
	//print_r($row);
	return $row[$fee_name];
}

function tp_count($area_id)
{
	global $con;
	$query = "select * from student where area_id='$area_id'";
	$res = mysqli_query($con, $query) or die(" User Information Error : " . mysqli_error($con));
	$row = mysqli_fetch_assoc($res);
	$count = mysqli_num_rows($res);

	return $count;
}


function collection($fromdate, $todate)
{
	global $con;
	$total = 0;
	$count = 0;
	$query = "select * from receipt where paid_date between '$fromdate' and '$todate' and status<>'CANCEL' order by id desc";

	$res = mysqli_query($con, $query) or die(" Default Error : " . mysqli_error($con));
	while ($row = mysqli_fetch_array($res)) {
		$total = $total + $row['paid_amount'];
		$count = $count + 1;
	}
	$collection['total'] = $total;
	$collection['count'] = $count;
	return $collection;
}




function classwisereport($month_id)
{
	global $con;
	$data[] = array('Month Name', 'Collection');
	$query = "SELECT student_class, sum(paid_amount)as total FROM receipt,student where receipt.student_id = student.student_id and month(paid_date) ='$month_id' group by student_class";

	$res = mysqli_query($con, $query) or die(" Class Wise report Error : " . mysqli_error($con));
	while ($row = mysqli_fetch_array($res)) {
		$data[] = array($row['student_class'], floatval($row['total']));
	}

	return json_encode($data);
}
function createmonth($month_id)
{
	global $con;
	$tdate = date('Y-' . $month_id . '-d');
	$table_name = strtolower(date('F_Y', strtotime($tdate)));
	$lastday = date('t', strtotime($tdate));
	$sql = "create table if not EXISTS $table_name (att_id INT AUTO_INCREMENT, id INT NOT NULL, student_admission int not null, created_by datetime not null, primary key (att_id), UNIQUE (id))";
	$res = mysqli_query($con, $sql) or mysqli_error($con);
	if ($res) {
		for ($i = 1; $i <= $lastday; $i++) {
			$day = 'd_' . $i;
			$sql2 = "alter table $table_name ADD COLUMN $day varchar(3)";
			mysqli_query($con, $sql2) or mysqli_error($con);
		}

		$sql2 = "insert into $table_name (id, student_admission) select id, student_admission from student where status ='ACTIVE'";
		$res = mysqli_query($con, $sql2) or mysqli_error($con);
	}
}
function duesmonthcount($student_id, $month_list)
{
	$ct = 0;
	$list = 'Prev Dues';
	foreach ($month_list as $month) {

		if (get_data('student_fee', $student_id, $month) == null) {
			$ct++;
			$list = $list . ", " . $month;
		}
	}
	$res['count'] = $ct;
	$res['list'] = $list;
	return $res;
}
function lastpaidmonth($student_id)
{
	global $month_list;
	$last = "";
	foreach ($month_list as $month) {

		if (get_data('student_fee', $student_id, $month) <> null) {
			$last = $month;
		}
	}
	return $last;
}

function duesviaadm($adm_no)
{
	global $con;
	global $month_list;

	$c_month = strtolower(date('F'));
	foreach ($month_list as $month) {
		if ($c_month == $month) {
			$cur_list[] = $month;
			break;
		} else {
			$cur_list[] = $month;
		}
	}
	$sql = "select id, student_name,student_admission from student where student_admission='$adm_no' and status ='ACTIVE'";
	$res = mysqli_query($con, $sql);
	if (mysqli_num_rows($res) == 0) {
		return false;
	} else {
		while ($row = mysqli_fetch_array($res)) {
			$total = 0;
			$student_id = $row['id'];
			$dmonth = duesmonthcount($row['id'], $cur_list);
			$ct = $dmonth['count'];

			//print_r($cur_list);
			if (get_data('student_fee', $student_id, 'student_dues') > 0) {
				$dues['prev'] = get_data('student_fee', $student_id, 'student_dues');
			} else {
				$dues['prev'] = 0;
			}
			if (get_fee($student_id, 'tuition_fee') > 0) {
				$dues['tution'] = get_fee($student_id, 'tuition_fee') * $ct;
			} else {
				$dues['tution'] = 0;
			}
			if (get_data('student', $student_id, 'student_type') == 'TRANSPORT') {
				$area_id = get_data('student', $student_id, 'area_id');
				$dues['transport'] = get_data('transport_area', $area_id, 'area_fee') * $ct;
			} else {
				$dues['transport'] = 0;
			}
			if (get_data('student', $student_id, 'student_type') == 'HOSTEL') {
				$dues['hostel'] = $total + get_fee($student_id, 'hostel_fee') * $ct;
			} else {
				$dues['hostel'] = 0;
			}
			foreach ($dues as $d) {
				$total = $total + $d;
			}
			$data['name'] = get_data('student', $student_id, 'student_name');
			$data['admission_no'] = get_data('student', $student_id, 'student_admission');
			$data['details'] = $dues;
			$data['month'] = $dmonth['list'];
			$data['total'] = $total;
			$data['lastpaidmonth'] = lastpaidmonth($student_id);
			//print_r($data); // Total Dues Details 
			return $data;
		}
	}
}
function getdues($mob_no)
{
	global $con;
	global $all_data;
	global $month_list;

	$c_month = strtolower(date('F'));
	foreach ($month_list as $month) {
		if ($c_month == $month) {
			$cur_list[] = $month;
			break;
		} else {
			$cur_list[] = $month;
		}
	}
	$sql = "select id, student_name,student_admission from student where student_mobile='$mob_no' and status ='ACTIVE'";
	$res = mysqli_query($con, $sql);
	if (mysqli_num_rows($res) == 0) {
		return false;
	} else {
		while ($row = mysqli_fetch_array($res)) {
			$total = 0;
			$student_id = $row['id'];
			$dmonth = duesmonthcount($row['id'], $cur_list);
			$ct = $dmonth['count'];

			//print_r($cur_list);
			if (get_data('student_fee', $student_id, 'student_dues') > 0) {
				$dues['prev'] = get_data('student_fee', $student_id, 'student_dues');
			} else {
				$dues['prev'] = 0;
			}
			if (get_fee($student_id, 'tuition_fee') > 0) {
				$dues['tution'] = get_fee($student_id, 'tuition_fee') * $ct;
			} else {
				$dues['tution'] = 0;
			}
			if (get_data('student', $student_id, 'student_type') == 'TRANSPORT') {
				$area_id = get_data('student', $student_id, 'area_id');
				$dues['transport'] = get_data('transport_area', $area_id, 'area_fee') * $ct;
			} else {
				$dues['transport'] = 0;
			}
			if (get_data('student', $student_id, 'student_type') == 'HOSTEL') {
				$dues['hostel'] = $total + get_fee($student_id, 'hostel_fee') * $ct;
			} else {
				$dues['hostel'] = 0;
			}
			foreach ($dues as $d) {
				$total = $total + $d;
			}
			$data['name'] = get_data('student', $student_id, 'student_name');
			$data['admission_no'] = get_data('student', $student_id, 'student_admission');
			$data['details'] = $dues;
			$data['month'] = $dmonth['list'];
			$data['total'] = $total;
			$data['lastpaidmonth'] = lastpaidmonth($student_id);
			//echo "<pre>";
			//print_r($data); // Total Dues Details 
			$all_data[] = $data;
		}
	}
}

function finaldues($student_id)
{
	global $con;
	global $all_data;
	global $month_list;
	$total = 0;
	$c_month = 'March';
	//$c_month =strtolower(date('F'));
	foreach ($month_list as $month) {
		if ($c_month == $month) {
			$cur_list[] = $month;
			break;
		} else {
			$cur_list[] = $month;
		}
	}

	$dmonth = duesmonthcount($student_id, $cur_list);
	$ct = $dmonth['count'];

	//print_r($cur_list);
	if (get_data('student_fee', $student_id, 'student_dues') > 0) {
		$dues['prev'] = get_data('student_fee', $student_id, 'student_dues');
	} else {
		$dues['prev'] = 0;
	}
	if (get_fee($student_id, 'tuition_fee') > 0) {
		$dues['tution'] = get_fee($student_id, 'tuition_fee') * $ct;
	} else {
		$dues['tution'] = 0;
	}
	if (get_data('student', $student_id, 'student_type') == 'TRANSPORT') {
		$area_id = get_data('student', $student_id, 'area_id');
		$dues['transport'] = get_data('transport_area', $area_id, 'area_fee') * $ct;
	} else {
		$dues['transport'] = 0;
	}
	if (get_data('student', $student_id, 'student_type') == 'HOSTEL') {
		$dues['hostel'] = $total + get_fee($student_id, 'hostel_fee') * $ct;
	} else {
		$dues['hostel'] = 0;
	}
	foreach ($dues as $d) {
		$total = $total + $d;
	}
	$data['name'] = get_data('student', $student_id, 'student_name');
	$data['admission_no'] = get_data('student', $student_id, 'student_admission');
	$data['details'] = $dues;
	$data['month'] = $dmonth['list'];
	$data['total'] = $total;
	$data['lastpaidmonth'] = lastpaidmonth($student_id);
	//echo "<pre>";
	//print_r($data); // Total Dues Details 
	return $data;
}
function duesonapp($mobile)
{
	global $all_data;
	$all_data = $text = null;
	getdues($mobile);
	return $all_data;
}

function duessms($mobile)
{
	global $all_data;
	$all_data = $text = null;
	getdues($mobile);
	if ($all_data != null) {
		foreach ($all_data as $sms) {
			$text = $text . "" . $sms['name'] . "--" . $sms['total'] . "\n";
		}
		return $text;
	} else {
		return 'No Data Found';
	}
}
/*--------------------------MOBILE APP FUNCTION ---------------------------*/


function verify_student($mobile)
{
	global $con;
	global $inst_name;
	global $sender_id;
	$query = "SELECT student_name, student_admission from student where status='ACTIVE' and student_mobile ='$mobile' ";

	$res = mysqli_query($con, $query) or die(" Verify Student Mobile No. Error : " . mysqli_error($con));
	if (mysqli_num_rows($res) >= 1) {
		$data['status'] = 'success';
		$data['otp'] = rand(100000, 999999);
		$msg = "Thanks for interest in " . $inst_name . " Your App Login OTP is " . $data['otp'];
		$msg = urlencode($msg);
		// $data['sms']= bulksms($mobile,$sms);
		/*--------Send SMS --------*/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$url = "http://msg.morg.in/rest/services/send_sms/sendGroupSms?AUTH_KEY=cd1323469f4970988b9bff98ff49cb79&message=$msg&senderId=$sender_id&routeId=1&mobileNos=$mobile&smsContentType=english";

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_exec($ch);
		curl_close($ch);
		/*--------Send SMS End --------*/
	} else {
		$data['status'] = 'fail';
		$data['otp'] = 0;
	}
	return json_encode($data);
}

function verify_staff($mobile)
{
	global $con;
	global $inst_name;
	global $sender_id;
	$query = "SELECT * from staff_details where status<>'BLOCK' and e_mobile ='$mobile' ";

	$res = mysqli_query($con, $query) or die(" Verify Staff Mobile No. Error : " . mysqli_error($con));
	if (mysqli_num_rows($res) >= 1) {
		$row = mysqli_fetch_array($res);

		$data['status'] = 'success';
		$data['otp'] = rand(100000, 999999);
		$msg = "Hello " . $row['e_name'] . " Thanks for using " . $inst_name . " App.  Your Login OTP is " . $data['otp'];
		$msg = urlencode($msg);
		// $data['sms']= bulksms($mobile,$sms);
		/*--------Send SMS --------*/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$url = "http://msg.morg.in/rest/services/send_sms/sendGroupSms?AUTH_KEY=cd1323469f4970988b9bff98ff49cb79&message=$msg&senderId=$sender_id&routeId=1&mobileNos=$mobile&smsContentType=english";

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_exec($ch);
		curl_close($ch);
		/*--------Send SMS End --------*/
	} else {
		$data['status'] = 'fail';
		$data['otp'] = 0;
	}

	return json_encode($data);
}

/*+++++++++++++++++++Exam System  ++++++++++++++++++++++++++*/
function get_marks($student_admission, $exam_name, $subject)
{
	global $con;
	$sql = "select * from exam where student_admission  ='$student_admission' and exam_name like '$exam_name' ";
	$res = mysqli_query($con, $sql) or die(" Marks Information Error : " . mysqli_error($con));
	if (mysqli_num_rows($res) == 1) {
		$row = mysqli_fetch_assoc($res);
		$data['mo']	 = $row[$subject . '_mo'];
		$data['se']	 = $row[$subject . '_se'];
		$data['nb']	 = $row[$subject . '_nb'];
	} else {
		$data['mo']	 = 0;
		$data['se']	 = 0;
		$data['nb']	 = 0;
	}
	return $data;
}

function get_co_scholastic($student_admission, $exam_name, $field)
{
	global $con;
	$data = null;
	$sql = "select * from co_scholastic where student_admission  ='$student_admission' and exam_name ='$exam_name' ";
	$res = mysqli_query($con, $sql) or die("Co Scholastic Information Error : " . mysqli_error($con));
	if (mysqli_num_rows($res) >= 1) {
		$row = mysqli_fetch_assoc($res);
		$data = $row[$field];
	}
	return $data;
}


function get_percentile($student_admission, $exam_name, $subject)
{
	global $con;
	$sql = "select * from exam where student_admission  ='$student_admission' and exam_name ='$exam_name' ";
	$res = mysqli_query($con, $sql) or die(" Marks Information Error : " . mysqli_error($con));
	if (mysqli_num_rows($res) == 1) {
		$row = mysqli_fetch_assoc($res);
		$mo	 = $row[$subject . '_mo'];
		$se	 = $row[$subject . '_se'];
		$nb	 = $row[$subject . '_nb'];
	} else {
		$mo	 = 0;
		$se  = 0;
		$nb	 = 0;
	}
	$total = $mo + $se + $nb;
	return $total / 10;
}

function grade($per)
{
	if ($per <= 100 && $per >= 91) {
		$grade = 'A1';
	} elseif ($per < 91 && $per >= 81) {
		$grade = 'A2';
	} elseif ($per < 81 && $per >= 71) {
		$grade = 'B1';
	} elseif ($per < 71 && $per >= 61) {
		$grade = 'B2';
	} elseif ($per < 61 && $per >= 51) {
		$grade = 'C1';
	} elseif ($per < 51 && $per >= 41) {
		$grade = 'C2';
	} elseif ($per < 41 && $per >= 33) {
		$grade = 'D';
	} else {
		$grade = 'E';
	}
	//A1(91-100), A2(81-90), B1(71-80), B2(61-70), C1(51-60), C2(41-50), D(33-40), E(0-32)
	return $grade;
}

function total_marks($student_id)
{
	global $con;
	$admission = get_data('student', $student_id, 'student_admission');
	$st_class = get_data('student', $student_id, 'student_class');
	$gtotal = 0;
	$sub_list = $st_class . '_subject';
	$extra_list = $st_class . '_extra';
	global $$sub_list;
	global $$extra_list;
	$subject_list = array_diff($$sub_list, $$extra_list);

	foreach (array_filter($subject_list) as $subject) {
		$garr = array();
		$sub = remove_space($subject);
		$marks  = get_marks($admission, 'Half Yearly', $sub);
		$pt1 = get_percentile($admission, 'PMT1', $sub);
		$total = $pt1 + $marks['nb'] + $marks['se'] + $marks['mo'];
		$gtotal = $gtotal + $total;
	}
	$result['total'] = $gtotal;
	$result['per'] = number_format($gtotal / count($subject_list), 2);
	$result['grade'] = grade($result['per']);
	return $result;
}

function exam_wise_marks($student_id, $exam_name)
{
	global $con;
	$admission = get_data('student', $student_id, 'student_admission');
	$st_class = get_data('student', $student_id, 'student_class');
	$gtotal = 0;
	$sub_list = $st_class . '_subject';
	$extra_list = $st_class . '_extra';
	global $$sub_list;
	global $$extra_list;
	$subject_list = array_diff($$sub_list, $$extra_list);

	foreach (array_filter($subject_list) as $subject) {
		$garr = array();
		$sub = remove_space($subject);
		$marks  = get_marks($admission, $exam_name, $sub);
		if ($exam_name == 'Half Yearly') {
			$pt1 = get_percentile($admission, 'PMT1', $sub);
			$total = $pt1 + $marks['nb'] + $marks['se'] + $marks['mo'];
		} else if ($exam_name == 'Annual') {
			$pt2 = get_percentile($admission, 'PMT2', $sub);
			$total = $pt2 + $marks['nb'] + $marks['se'] + $marks['mo'];
		} else {
			$total = $marks['nb'] + $marks['se'] + $marks['mo'];
		}
		$gtotal = $gtotal + $total;
	}
	$result['total'] = $gtotal;
	$result['per'] = number_format($gtotal / count($subject_list), 2);
	$result['grade'] = grade($result['per']);
	return $result;
}

function holiday_att($today)
{
	if (!isset($today)) {
		$today = date('Y-m-d');
	}
	$col_name = 'd_' . date('j', strtotime($today));
	$mvalue = remove_space(date('M_Y', strtotime($today)));
	$holiday = create_list('holiday', 'holiday_date', array('status' => 'PUBLIC'));
	if (array_search($today, $holiday)) {
		$data = array($col_name => 'H');
		$where = array('att_month' => $mvalue);
		update_multi_data('student_att', $data, $where);
	}
}

?>