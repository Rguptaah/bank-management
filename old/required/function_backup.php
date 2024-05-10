<?php
require_once('op_config.php');
if (isset($_SESSION['db_name'])) {
	$db_name = $_SESSION['db_name'];
}
$con = mysqli_connect($host_name, $db_user, $db_password, $db_name) or die("Unable to Connect, Check the Connection Parameter. " . mysqli_error($con));

/*---------OfferPlant Master Functions-------------*/

function encode($input)
{
	return strtr(base64_encode($input), '+/=', '._-');
}

function decode($input)
{
	$url = base64_decode(strtr($input, '._-', '+/='));
	$parts = parse_url($url);
	parse_str($url, $query);
	return $query;
	//return $url;
}

function rnd_str($length_of_string)
{

	// String of all alphanumeric character 
	$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

	// Shufle the $str_result and returns substring 
	// of specified length 
	return substr(str_shuffle($str_result), 0, $length_of_string);
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
	if (is_array($arr_data)) {
		foreach ($arr_data as $data) {

			$key = array_search($data, $arr_data);
			if (is_array($data)) {
				post_clean($data);
			} else {
				$arr_data[$key] = xss_clean($data);
			}
		}
	} else {
		xss_clean($arr_data);
	}
	return $arr_data;
}

function verify_request()
{
	$ref = parse_url($_SERVER["HTTP_REFERER"]);
	$rh  = $ref['host'];
	$mh = $_SERVER['HTTP_HOST'];

	if ($rh <> $mh) {
		return false;
	} else {
		return true;
	}
}

function verify($user_type)
{
	$actual_link = "http://" . $_SERVER['HTTP_HOST']; //$_SERVER['REQUEST_URI'];
	//die($actual_link);
	$current_page = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
	if ($user_type == 'ADMIN') {
		global $admin_role;
		$all_page = $admin_role;
	} else if ($user_type == 'CLIENT') {
		global $client_role;
		$all_page = $client_role;
	} else {
		die("Invalid User ! Don't Have Permission");
	}

	if (!array_search($current_page, $all_page)) {
		die("Don't have Permission");
	}
}

function add_column($table_name, $col_name, $data_type = 'varchar(255)', $default = null)
{
	global $con;
	$sql1 = "SHOW COLUMNS FROM $table_name LIKE '$col_name'";
	$res1 = direct_sql($sql1);
	//print_r($res1);
	if ($res1['count'] == 0) {
		$sql = "alter table $table_name add column $col_name $data_type $default";
		$res = mysqli_query($con, $sql) or die("Error in Adding Column" . mysqli_error($con));
	}
}
function remove_column($table_name, $col_name)
{
	global $con;
	$res1 = direct_sql("SHOW COLUMNS FROM $table_name LIKE '$col_name'");
	if ($res1['count'] > 0) {
		$sql = "alter table $table_name drop column $col_name ";
		$res = mysqli_query($con, $sql) or die("Error in Removing Column" . mysqli_error($con));
	}
}

function insert_row($table_name)
{
	global $con;
	global $user_id;
	global $current_datetime;
	$result = get_multi_data($table_name, array('created_by' => $user_id, 'status' => 'AUTO'), ' order by id desc limit 1');
	if ($result['count'] < 1) {
		$result2 = insert_data($table_name, array('status' => 'AUTO', 'created_at' => $current_datetime, 'created_by' => $user_id));
		$id = $result2['id'];
	} else {
		$id = $result['data'][0]['id'];
	}
	return array('table' => $table_name, 'id' => $id);
}
function insert_data($table_name, $ArrayData)
{
	global $con;
	global $user_id;
	global $current_datetime;
	//echo"<pre>";
	//print_r($ArrayData);
	$ArrayData['created_by'] = $user_id;
	$ArrayData['created_at'] = $current_datetime;
	$columns = implode(", ", array_keys($ArrayData));
	$escaped_values = array_values($ArrayData);
	foreach ($escaped_values as $newvalue) {
		$newvalues[] = "'" . post_clean($newvalue) . "'";
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
		$result['status'] = 'error';
		$result['msg'] = mysqli_error($con);
	}
	return $result;
}

function update_data($table_name, $ArrayData, $id, $pkey = 'id')
{
	global $con;
	global $user_id;
	global $current_datetime;
	$ArrayData['created_by'] = $user_id;
	$ArrayData['created_at'] = $current_datetime;
	$cols = array();
	foreach ($ArrayData as $key => $value) {
		if ($value == NULL) {
			unset($ArrayData[$key]);
		} else {
			$newvalue = post_clean($value);
			$cols[] = "$key = '$newvalue'";
		}
	}
	$sql = "UPDATE $table_name SET " . implode(', ', $cols) . " WHERE $pkey  ='" . $id . "'";
	$res = mysqli_query($con, $sql) or mysqli_error($con);
	$num = mysqli_affected_rows($con);
	if ($num > 0) {
		$result['id'] = $id;
		$result['status'] = 'success';
		$result['msg'] = $num . " Record Updated Successfully";
	} else {
		$result['id'] = $id;
		$result['status'] = 'error';
		$result['msg'] = "Sorry ! No Update found " . mysqli_error($con);
	}
	return $result;
}

function update_multi_data($table_name, $ArrayData, $whereArr)
{
	global $con;
	$cols = array();
	foreach ($ArrayData as $key => $value) {
		$newvalue = post_clean($value);
		$cols[] = "$key = '$newvalue'";
	}

	foreach ($whereArr as $key => $value) {
		$newvalue = post_clean($value);
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
		$result['msg'] = " No Update found";
	}
	return $result;
}

function delete_data($table_name, $id, $pkey = 'id')
{
	global $con;
	$sql = "delete from $table_name WHERE $pkey  ='" . $id . "'";
	$res = mysqli_query($con, $sql) or die("Error in Deleting Data" . mysqli_error($con));
	$num = mysqli_affected_rows($con);
	if ($num >= 1) {
		$result['id'] = $id;
		$result['status'] = 'success';
		$result['msg'] = $num . " Record deleted successfully";
	} else {
		$result['id'] = $id;
		$result['status'] = 'error';
		$result['msg'] = "Sorry ! No record found to delete";
	}
	return $result;
}

function delete_multi_data($table_name, $whereArr)
{
	global $con;
	foreach ($whereArr as $key => $value) {
		$newvalue = preg_replace('/[^A-Za-z.@,:+0-9\-]/', ' ', $value);
		$where[] = "$key = '$newvalue'";
	}
	$sql = "delete from" . $table_name . " WHERE " . implode('and ', $where);
	$res = mysqli_query($con, $sql) or die("Error in Getting Data" . mysqli_error($con));
	$num = mysqli_affected_rows($con);
	if ($num >= 1) {
		$result['id'] = $id;
		$result['status'] = 'success';
		$result['msg'] = $num . " Record deleted successfully";
	} else {
		$result['id'] = $id;
		$result['status'] = 'error';
		$result['msg'] = "Soory ! No Record found to delete";
	}
	return $result;
}
function direct_sql($sql, $type = 'get')
{
	global $con;
	$result = null;
	$res = mysqli_query($con, $sql) or die("Error In Loading Data : " . mysqli_error($con));
	if ($type == 'set') {
		$ct = mysqli_affected_rows($con);
	} else {

		$ct = mysqli_num_rows($res);
		if ($ct >= 1) {
			while ($row = mysqli_fetch_assoc($res)) {
				$data[] = $row;
			}
			$result['count'] = $ct;
			$result['status'] = 'success';
			$result['data'] = $data;
		} else {
			$result['count'] = 0;
			$result['status'] = 'error';
			$result['data'] = null;
		}
	}
	return $result;
}

function get_all($table_name, $column_list = '*', $whereArr = null, $orderby = 'id')
{
	global $con;
	if ($column_list <> '*') {
		$column_list = implode(',', $column_list);
	}

	if ($whereArr <> null) {
		foreach ($whereArr as $key => $value) {
			$key = trim($key);
			$newvalue = preg_replace('/[^A-Za-z.@,:+0-9\-]/', ' ', $value);
			$where[] = "$key = '$newvalue'";
		}
		$sql = "SELECT $column_list FROM $table_name where " . implode('and ', $where);
	} else {
		$sql = "SELECT $column_list FROM $table_name where status <> 'AUTO'  order by $orderby ";
	}
	//echo  $sql;
	$res = mysqli_query($con, $sql) or die("Error In Loading Data : " . mysqli_error($con));
	$ct = mysqli_num_rows($res);
	if ($ct >= 1) {
		while ($row = mysqli_fetch_assoc($res)) {
			$data[] = $row;
		}
		$result['count'] = $ct;
		$result['status'] = 'success';
		$result['data'] = $data;
	} else {
		$result['count'] = 0;
		$result['status'] = 'error';
		$result['data'] = null;
	}
	return $result;
}

function get_data($table_name, $id, $field_name = null, $pkey = 'id')
{
	global $con;
	$result['count'] = 0;
	$result['status'] = 'error';
	$sql = "SELECT * FROM $table_name where $pkey ='$id' ";
	$res = mysqli_query($con, $sql) or die(" Data Information Error : " . mysqli_error($con));
	$ct = mysqli_num_rows($res);
	$result['count'] = $ct;
	if ($ct >= 1) {
		$row = mysqli_fetch_assoc($res);
		extract($row);
		if ($field_name) {
			$result['status'] = 'success';
			$result['data'] = $row[$field_name];
		} else {
			$result['status'] = 'success';
			$result['data'] = $row;
		}
	} else {
		$result['count'] = 0;
		$result['status'] = 'success';
		$result['data'] = null;
	}
	return $result;
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
function dropdown_list($tablename, $value, $list, $selected = null, $list2 = null)
{
	global $con;
	$show2 = null;
	$i = 0;
	$query = "select * from $tablename  where status <> 'AUTO' order by $list";
	$res = mysqli_query($con, $query) or die(" Creating Drop down Error : " . mysqli_error($con));
	while ($row = mysqli_fetch_array($res)) {
		$key = $row[$value];
		$show = $row[$list];


		if ($list2 <> null) {
			$show2 = $row[$list2];
			$show2 = "[ " . $show2 . "]";
		}

	?>
		<option value='<?php echo $key; ?>' <?php if ($key == $selected) echo "selected"; ?>><?php echo $show . " " . $show2; ?></option>
	<?php
	}
}


function dropdown_with_key($array_list, $selected = null)
{
	foreach ($array_list as $list) {
		$key = array_search($list, $array_list);
	?>
		<option value='<?php echo $key; ?>' <?php if ($key == $selected) echo "selected"; ?>><?php echo $list; ?></option>
	<?php
	}
}

function dropdown_where($table_name, $id, $list, $whereArr, $selected = null)
{
	global $con;

	foreach ($whereArr as $key => $value) {
		$newvalue = post_clean($value);
		$where[] = "$key = '$newvalue'";
	}

	$sql = "select * from " .  $table_name . " WHERE " . implode('and ', $where);
	$res = mysqli_query($con, $sql) or mysqli_error($con);
	while ($row = mysqli_fetch_array($res)) {
		$id_inner = $row[$id];
		$show = $row[$list];
	?>
		<option value='<?php echo $id_inner; ?>' <?php if ($id_inner == $selected) echo "selected"; ?>><?php echo $show; ?></option>
	<?php
	}
}

function dropdownmultiple($array_list, $selectedArr = null)
{
	foreach ($array_list as $list) {
		//$key=-1;
		$key = array_search($list, $selectedArr);
	?>
		<option value='<?php echo $list; ?>' <?php if ($key != '') echo "selected"; ?>><?php echo $list . "-" . $key; ?></option>
	<?php
	}
}

function check_list($name, $array_list, $selected = null, $height = '160px')
{
	$selected = explode(',', $selected);
	echo "<div style='overflow-y:auto;height:$height'>";
	?>
	<span class='btn btn-sm btn-info float-right' onclick="selectall('<?php echo $name; ?>')"><i class='fa fa-check'></i></span>
	<hr>
	<?php
	foreach (array_filter($array_list) as $list) {
		$checked = null;
		$x = array_search(trim($list), array_map('trim', $selected));

		if ($x >= -1) {
			$checked = 'checked';
		}
	?>
		<div class="checkbox">
			<input type="checkbox" value="<?php echo $list; ?>" id="Checkbox_<?php echo $list; ?>" <?php echo $checked; ?> name='<?php echo $name . '[]'; ?>'>
			<label for="Checkbox_<?php echo $list; ?>"><?php echo $list ?></label>
		</div>
<?php
	}
	echo "</div>";
}

function create_list($table_name, $field,  $whereArr = null)
{
	global $con;
	$cols = array();


	if ($whereArr != null) {
		foreach ($whereArr as $key => $value) {
			$newvalue = preg_replace('/[^A-Za-z.@,:+0-9\-]/', ' ', $value);
			$where[] = "$key = '$newvalue'";
		}
		$sql = "select distinct($field) from " . $table_name . " WHERE " . implode('and ', $where);
	} else {
		$sql = "select distinct($field) from " . $table_name;
	}

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


function get_bal_msg()
{
	global $auth_key_msg;
	$api_url = 'http://mysms.msgclub.net/rest/services/send_sms/getClientRouteBalance?AUTH_KEY=' . $auth_key_msg;
	//  Initiate curl
	$ch = curl_init();
	// Disable SSL verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// Set the url
	curl_setopt($ch, CURLOPT_URL, $api_url);
	// Execute
	$result = curl_exec($ch);
	// Closing
	curl_close($ch);
	$data  = json_decode($result, true);
	return $data[0]['routeBalance'];
}


function get_bal_sms()
{
	global $auth_key_sms;
	$api_url = 'http://sms.morg.in/api/balance.php?&type=4&authkey=' . $auth_key_sms;
	//  Initiate curl
	$ch = curl_init();
	// Disable SSL verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// Set the url
	curl_setopt($ch, CURLOPT_URL, $api_url);
	// Execute
	$result = curl_exec($ch);
	// Closing
	curl_close($ch);
	$data  = json_decode($result, true);
	return $data;
}



function send_sms($mobile, $sms, $template_id)
{
	$template = get_data('sms_template', $template_id)['data'];
	$sender_id = $template['sender_id'];
	$template_id = $template['template_id'];
	$res = null;
	//if(preg_match('/^[6-9]{1}[0-9]{9}+$/', $number) ==1)
	//	{
	$no = urlencode($mobile);
	$msg = substr(urlencode($sms), 0, 340);
	global $auth_key;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$url = "http://202.65.131.176/api/mt/send_sms?APIKey=$auth_key&senderid=$sender_id&channel=trans&DCS=0&flashsms=0&number=$mobile&text=$msg&route=4&DLTTemplateId=$template_id&PEID=1201159513052830502";
	//$url ="http://sms.morg.in/api/sendhttp.php?authkey=$auth_key_sms&mobiles=$no&message=$msg&sender=$sender_id&route=4&country=91";
	curl_setopt($ch, CURLOPT_URL, $url);
	$res = curl_exec($ch);
	curl_close($ch);
	//	}
	return $res;
}


function uploadimg($file_name, $imgkey = 'rand', $target_dir = "upload")
{
	if ($imgkey == 'rand') {
		$imgkey = rand(10000, 99999);
	}
	$target_file = $imgkey . "_" . basename($_FILES[$file_name]["name"]);
	$target_file = strtolower(preg_replace("/[^a-zA-Z0-9.]+/", "", $target_file));
	$uploadOk = 1;

	$res['id'] = 0;
	$res['status'] = 'error';
	$res['msg'] = '';
	$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
	// Check if image file is a actual image or fake image

	$check = getimagesize($_FILES[$file_name]["tmp_name"]);
	if ($check !== false) {
		$res['msg'] = "File is an image - " . $check["mime"] . ".";
		$uploadOk = 1;
	} else {
		$res['msg'] = "File is not an image.";
		$uploadOk = 0;
	}

	// Check if file already exists
	if (file_exists($target_file)) {
		unlink($target_file);
		$res['msg'] = "Sorry, file already exists.";
		$uploadOk = 1;
	}
	// Check file size
	if ($_FILES[$file_name]["size"] > 500000) {
		$res['msg'] = "Sorry, your file is too large.";
		$uploadOk = 0;
	}
	// Allow certain file formats
	if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "pdf") {
		$res['msg'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$msg = "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES[$file_name]["tmp_name"], $target_dir . "/" . $target_file)) {
			$res['msg'] = "The file " . basename($_FILES[$file_name]["name"]) . " has been uploaded.";
			$res['id'] = $target_file;
			$res['status'] = 'success';
		} else {
			$res['msg'] = "Sorry, there was an error uploading your file.";
		}
	}
	return $res;
}


function rowcount($table, $field_name = 'id') // unique count
{
	global $con;
	$query = "SELECT distinct($field_name) from $table";
	$res = mysqli_query($con, $query) or die(" Count Error : " . mysqli_error($con));
	$count  = mysqli_num_rows($res);
	return $count;
}


function rtf_mail($to, $subject, $msg) // single email 
// rtf_mail('xyz@abc.com', 'Email Subject', 'Email Body' ) // example 
{
	global $inst_logo;
	global $inst_name;
	global $inst_email;
	global $noreply_email;
	global $inst_url;
	global $inst_address1;
	global $inst_address2;

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Create email headers
	$headers .= 'From: ' . $noreply_email . "\r\n" .
		'Reply-To: ' . $inst_email . "\r\n" .
		'X-Mailer: PHP/' . phpversion();

	// Compose a simple HTML email message
	$message = '<html><body>';
	$message .= "<table width='1000px' cellpadding='20px' cellspacing='0px' border='1' rules='all'>";
	$message .= "<tr><td colspan='5' aling='center' valign='middle'><img src='http://'" . $inst_url . $inst_logo . "' alt='Logo here'/><h3>$inst_name </h3></td></tr>";
	$message .= "<tr><td colspan='5' aling='center'><p>" . $inst_address1 . $inst_address2 . "<br> " . $inst_email . "|" . $inst_url . "</p></td></tr>";
	$message .= "<tr><td colspan='5' aling='center' valign='top' height='350px'><p> $msg </p></td></tr>";
	$message .= "<tr><td colspan='5' bgcolor='skyblue' align='left'>Regards, <br> $inst_name , <br> $inst_email <br> $inst_url </p></td></tr>";
	$message .= '</table>';
	$message .= '</body></html>';

	// Sending email
	if (mail($to, $subject, $message, $headers)) {
		return 'Your mail has been sent successfully.';
	} else {
		return 'Unable to send email. Please try again.';
	}
}

function api_call($api_url)
{
	//  Initiate curl
	$ch = curl_init();
	// Disable SSL verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// Set the url
	curl_setopt($ch, CURLOPT_URL, $api_url);
	// Execute
	$result = curl_exec($ch);
	// Closing
	curl_close($ch);
	return $result;
}

function qrcode($data)
{

	$PNG_TEMP_DIR = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'qrcode' . DIRECTORY_SEPARATOR;

	//html PNG location prefix
	$PNG_WEB_DIR = 'qrcode/';

	require_once "assets/lib/qrlib.php";
	//ofcourse we need rights to create temp dir
	if (!file_exists($PNG_TEMP_DIR)) mkdir($PNG_TEMP_DIR);


	$filename = $PNG_TEMP_DIR . 'test.png';
	$errorCorrectionLevel = 'H';
	$matrixPointSize = 4;

	if (isset($data)) {

		//it's very important!
		if (trim($data) == '')
			die('data cannot be empty! <a href="?">back</a>');

		// user data
		$filename = $PNG_TEMP_DIR . 'OFFERPLANT' . md5($data . '|' . $errorCorrectionLevel . '|' . $matrixPointSize) . '.png';
		QRcode::png($data, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
	} else {

		//default data
		echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';
		QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);
	}
	//display generated file
	//echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" />';  
	return $PNG_WEB_DIR . basename($filename);
}


function rnd_str2($url)
{
	$x  = preg_match_all("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $url, $matches);
	$id  = $matches[0][0];
	return $id;
}

// Whatsapp API
function wa_send($number, $sms, $ctype = 'text')
{
	if (preg_match('/^[6-9]{1}[0-9]{9}+$/', $number) == 1) {
		global $wakey;
		$no = urlencode($number);
		$msg = substr(urlencode($sms), 0, 2000);
		global $sender_id;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$url = "https://www.whatsappapi.in/api?token=$wakey&action=$ctype&from=$sender_id&country=91&to=$no&uid=5d7622f36b80b&$ctype=$msg";
		curl_setopt($ch, CURLOPT_URL, $url);
		$res = curl_exec($ch);
		curl_close($ch);
	}
	//return $res;
}

function csv_import($table, $pkey = 'id') // Import CSV FILE to Table
{
	// Allowed mime types
	$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

	// Validate whether selected file is a CSV file
	if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {
		$change = $new = 0;
		if (is_uploaded_file($_FILES['file']['tmp_name'])) {

			// Open uploaded CSV file with read-only mode
			$csvFile = fopen($_FILES['file']['tmp_name'], 'r');
			$col_list = array_map('trim', fgetcsv($csvFile));
			//print_r($col_list);
			while (($line = fgetcsv($csvFile)) !== FALSE) {
				$all_data = array_combine($col_list, $line);
				//$search[$pkey] =trim($all_data[$pkey]);
				//$search_result = get_all($table,'*', $search, $pkey);
				$search_result = get_data($table, $all_data[$pkey], null, $pkey);
				echo "<pre>";
				print_r($search_result);
				if ($search_result['count'] < 1) {
					$res1 = insert_data($table, $all_data);
					if ($res1['id'] != 0) {
						$new++;
					}
				} else {
					//echo $all_data[$pkey];
					$res1 = update_data($table, $all_data, $all_data[$pkey], $pkey);
					if ($res1['status'] == 'success') {
						$change++;
					}
				}
				print_r($res1);
				$res = array('status' => 'success', 'change' => $change, 'new' => $new, 'msg' => 'Data has been imported successfully.');
			}
		}
	} else {
		$res = array('status' => 'error', 'change' => $change, 'new' => $new, 'msg' => 'Please upload a valid CSV file.');
	}
	return  $res;
}

function csv_export($table_name, $status = 'ACTIVE')
{
	global $con;
	global $db_name;

	$filename = $table_name . ".csv";
	$fp = fopen('php://output', 'w');

	$query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$db_name' AND TABLE_NAME='$table_name'";
	$result = mysqli_query($con, $query);
	while ($row = mysqli_fetch_row($result)) {
		$header[] = $row[0];
	}

	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename=' . $filename);
	fputcsv($fp, $header);

	$query = "SELECT * FROM $table_name where status ='$status' ";
	$result = mysqli_query($con, $query);
	while ($row = mysqli_fetch_row($result)) {
		//print_r($row);
		fputcsv($fp, $row);
	}
	exit;
}
/* =============== END of OfferPlant Master Function ===============*/

function studentcount($class, $section = null)
{
	if ($section == null) {
		$res = get_all('student', '*', array('status' => 'ACTIVE', 'student_class' => $class));
	} else {
		$res = get_all('student', '*', array('status' => 'ACTIVE', 'student_class' => $class, 'student_section' => $section));
	}
	return $res['count'];
}


function get_fee($student_id, $fee_id)
{
	global $con;
	$fee = 0.00;
	$student = get_data('student', $student_id, null)['data'];
	$fee = get_data('fee_head', $fee_id)['data'];
	$col_name = remove_space($fee['fee_name']);

	if ($col_name == 'transport_fee') {
		if ($student['student_type'] == 'TRANSPORT') {
			$fee = get_data('transport_area', $student['area_id'], 'area_fee')['data'];
		} else {
			$fee = 0;
		}
	} else if ($col_name == 'previous_dues') {
		$fee = 0;
	} else if ($col_name == 'hostel_fee') {
		if ($student['student_type'] == 'HOSTELER') {
			$fee = get_data('fee_details', $student['student_class'], $col_name, 'student_class')['data'];
		} else {
			$fee = 0;
		}
	} else if ($fee['fee_type'] == 'STUDENT') {
		$fee = get_data('student_fee', $student_id, $col_name, 'student_id')['data'];
	} else {
		$fee = get_data('fee_details', $student['student_class'], $col_name, 'student_class')['data'];
	}
	return $fee;
}

function monthly_fee($student_id, $month_name)
{
	global $con;
	//$fee['total']=0;
	$student = get_data('student', $student_id, null)['data'];
	extract($student);
	$sql = "select * from fee_head where find_in_set ('$finance_type', finance_type) and find_in_set( '$admission_type',admission_type) and find_in_set('$month_name', fee_month) and find_in_set('$student_class', student_class) and find_in_set('$student_type', student_type) and status='ACTIVE'";
	//$sql ="select * from fee_head where finance_type ='NORMAL' and find_in_set( '$admission_type',admission_type) and find_in_set('$month_name', fee_month) and find_in_set('$student_type', student_type) and status='ACTIVE'";
	$res = direct_sql($sql);

	if ($res['count'] > 0) {
		$total = 0;
		foreach ($res['data'] as $row) {
			$fee['id'] = $row['id'];
			$fee['name'] = $row['fee_name'];
			$fee['col_name'] = $col_name = remove_space($row['fee_name']);
			$fee['amount'] = $fee_amount = get_fee($student_id, $row['id']);
			$total = $total + $fee['amount'];
			$all_fee['fee'][$col_name] = $fee_amount;
		}
		$all_fee['fee']['total'] = $total;
		$all_fee['student_id'] = $student_id;
		$all_fee['admission'] = $student['student_admission'];
		$all_fee['month'] = $month_name;
	}
	return $all_fee;
}


function create_check($name, $value = null, $class = 'fee-month', $checked = null)
{
	$id = remove_space($name);
	$check = "<div class='checkbox'><input type='checkbox' name ='$id' id='$id' value='$value' class='$class' $checked ><label for='$id'>$name</label></div>";
	echo $check;
}


function array_add($all)
{
	$total = array();
	$key_list = array();
	foreach ($all as $tmp) {
		$key_list = array_merge($key_list, array_keys($tmp));
	}

	$col_list = array_unique($key_list);

	foreach ($col_list as $col_name) {
		$total[$col_name] = array_sum(array_column($all, $col_name));
	}
	return $total;
}

function subject_list($class_name)
{
	$scls = xss_clean($class_name);
	//$list =array();
	$sql = "select * from subject where find_in_set('$scls',student_class) and status <>'AUTO' ";
	$sub = direct_sql($sql);
	foreach ($sub['data'] as $sub) {
		$list[] = $sub['id'];
	}
	return $list;
}
function nmonth_fee($sid, $marr)
{
	//$marr = explode(',',$mlist);
	$all = array();
	foreach ($marr as $month) {
		if (get_data('student_fee', $sid, remove_space($month), 'student_id')['data'] == null) {
			$all[$month] = monthly_fee($sid, $month)['fee'];
		}
	}
	return array_add($all);
}

function get_marks($student_admission, $exam_name, $subject)
{
	global $con;
	$sql = "select * from exam where student_admission  ='$student_admission' and exam_name like '$exam_name' ";
	$res = direct_sql($sql);
	if ($res['count'] == 1) {
		$row = $res['data'][0];
		$data['pt']	 = $row[$subject . '_pt'];
		$data['mo']	 = $row[$subject . '_mo'];
		$data['se']	 = $row[$subject . '_se'];
		$data['nb']	 = $row[$subject . '_nb'];
		$data['total'] = $data['nb'] + $data['se'] + $data['mo'] + $data['pt'];
	} else {
		$data['pt']	 = 0;
		$data['mo']	 = 0;
		$data['se']	 = 0;
		$data['nb']	 = 0;
		$data['total'] = 0;
	}
	return $data;
}

function marksimport($table, $pkey = 'id', $remove = null) // Import CSV FILE to Table
{
	// Allowed mime types
	$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

	// Validate whether selected file is a CSV file
	if (!empty($_FILES['file']['name']) && in_array($_FILES['file']['type'], $csvMimes)) {
		$change = $new = 0;
		if (is_uploaded_file($_FILES['file']['tmp_name'])) {

			// Open uploaded CSV file with read-only mode
			$csvFile = fopen($_FILES['file']['tmp_name'], 'r');
			$col_list = array_map('remove_space', fgetcsv($csvFile));

			while (($line = fgetcsv($csvFile)) !== FALSE) {
				$all_data = array_combine($col_list, $line);
				//$search[$pkey] =trim($all_data[$pkey]);
				//$search_result = get_all($table,'*', $search, $pkey);
				foreach (explode(',', $remove) as $rcol) {
					unset($all_data[$rcol]);
				}
				print_r($all_data);
				$search_result = get_data($table, $all_data[$pkey], null, $pkey);
				echo "<pre>";
				print_r($search_result);
				if ($search_result['count'] < 1) {
					$res = insert_data($table, $all_data);
					if ($res['id'] != 0) {
						$new++;
					}
				} else {
					//echo $all_data[$pkey];
					$res1 = update_data($table, $all_data, $all_data[$pkey], $pkey);
					if ($res1['status'] == 'success') {
						$change++;
					}
				}
				$res = array('status' => 'success', 'change' => $change, 'new' => $new, 'msg' => 'Data has been imported successfully.');
			}
		}
	} else {
		$res = array('status' => 'error', 'change' => $change, 'new' => $new, 'msg' => 'Please upload a valid CSV file.');
	}
	return  $res;
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

function duesmonthcount($student_id)
{
	$ct = 0;
	$list[] = 'Prev Dues';
	$student = get_data('student', $student_id)['data'];
	global $month_list;

	if ($student['student_class'] == 'I' || $student['student_class'] == 'II' || $student['student_class'] == 'III' || $student['student_class'] == 'IV' || $student['student_class'] == 'V') {
		$month_list = array('March');
	}

	if ($student['student_class'] == 'VI' || $student['student_class'] == 'VII' || $student['student_class'] == 'VIII') {
		$month_list = array('February', 'March');
	}
	if ($student['student_class'] == 'XI') {
		$month_list = array('July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March');
	}
	// else{

	// }
	foreach ($month_list as $month) {
		$x = get_data('student_fee', $student_id, remove_space($month), 'student_id')['data'];
		if ($x == '' || $x == null) {
			$ct++;
			$list[] = $month;
			//$list =$list.", ".$month;
		}
	}
	$res['count'] = $ct;
	$res['list'] = $list;
	return $res;
}


function finaldues($student_id)
{
	$total = get_data('student_fee', $student_id, 'current_dues', 'student_id')['data'];
	$marr = duesmonthcount($student_id)['list'];

	foreach ($marr as $mnth) {
		$res = monthly_fee($student_id, remove_space($mnth));
		//print_r($res);
		$total = $total + $res['fee']['total'];
	}

	return $total;
}

//echo finaldues(20);

?>