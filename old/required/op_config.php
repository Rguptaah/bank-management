<?php session_start();
$CONFIG['token'] = session_id();
date_default_timezone_set("Asia/Kolkata");
$CONFIG['current_date_time'] = date('Y-m-d H:i:s');
$CONFIG['today'] = date('Y-m-d'); // '2021-11-13'; 
error_reporting(E_ERROR | E_PARSE);
/*-------Some Basic Details (Global Variables) ---------*/

$CONFIG['full_name'] = "Banking Facilities";
$CONFIG['inst_name'] = "Banking";
$CONFIG['inst_managed_by'] = "Koding Karo";
$CONFIG['inst_address1'] = "Noida India";
$CONFIG['inst_address2'] = "Noida UP India";
$CONFIG['inst_contact'] = "8581843939";
$CONFIG['inst_email'] = "developer.freelance1511@gmail.com";
$CONFIG['inst_logo'] = "images/logo.png";
$CONFIG['white_logo'] = "images/logo.png";
$CONFIG['banner'] = "images/banner.jpg";
$CONFIG['inst_url'] = "localhost/bine-master";
$CONFIG['inst_type'] = "Bank";


$CONFIG['app_name'] = 'Bank Master';
$CONFIG['dev_company'] = "Freelance";
$CONFIG['dev_by'] = "Rahul Gupta";
$CONFIG['dev_url'] = "https://rahulkrgupta.netlify.app";
$CONFIG['dev_email'] = "developer.freelance1511@gmail.com";
$CONFIG['dev_contact'] = "8581843939";


// LocalHost Configuration
$CONFIG['host_name'] = 'localhost';
$CONFIG['db_user'] = 'root';
$CONFIG['db_password'] = '';
$CONFIG['db_name'] = 'morg_bine'; // Default Database Name
$CONFIG['base_url'] = 'http://localhost/bine-master';

/* Live Configuration
$CONFIG['host_name'] ='localhost';
$CONFIG['db_user'] ='';
$CONFIG['db_password'] ='';
$CONFIG['db_name'] =''; // Default Database Name
$CONFIG['base_url'] =''; */

$CONFIG['month_list'] = array('Admission_month', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March');
$CONFIG['att_month_list'] = array('April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March');
$CONFIG['fee_month'] = array('November', 'December', 'January', 'February', 'March'); // Month Allowed to Collect Fee

$CONFIG['gender_list'] = array('MALE', 'FEMALE', 'OTHER');
$CONFIG['status_list'] = array('ACTIVE', 'BLOCK', 'INACTIVE');
$CONFIG['religion_list'] = array('', 'HINDU', 'MUSLIM', 'SHIKH', 'CHRISTIAN');
$CONFIG['caste_list'] = array('', 'General', 'SC', 'ST', 'OBC');
$CONFIG['bloodgroup_list'] = array('', 'A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-');
$CONFIG['user_type_list'] = array('Account', 'Class Teacher', 'DBA', 'Parent', 'Driver');
$CONFIG['mode_list'] = array('Monthly', 'Annual', 'OneTime');

$CONFIG['day_list'] = array('SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY');
/*-------End of Basic Details ---------*/


$CONFIG['session_list'] = array('' => 'Select Session', 'morg_sghs' => '2021-2022');


$CONFIG['menu_list'] = array();
$CONFIG['menu_list']['settings'] = array('create_fee', 'set_fee', 'manage_user');

/* Inventory Configuration */
$CONFIG['payment_mode_list'] = array('CASH', 'BANK', 'UPI');
$CONFIG['gst_percent_list'] = array(0, 5, 12, 18, 28);


/* Account Configuration */

$CONFIG['txn_mode_list'] = array('CASH', 'BANK', 'UNPAID');

$CONFIG['allow_status'] = array('', 'Y', 'NO');

extract($CONFIG);
sort($account_head_list);
extract($CONFIG);
