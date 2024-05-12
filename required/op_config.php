<?php session_start();
$CONFIG['token'] = session_id();
date_default_timezone_set("Asia/Kolkata");
$CONFIG['current_date_time'] = date('Y-m-d H:i:s');
$CONFIG['today'] = date('Y-m-d'); // '2021-11-13'; 
error_reporting(1);
/*-------Some Basic Details (Global Variables) ---------*/

$CONFIG['full_name'] = "Abhinam India Nidhi limited";
$CONFIG['inst_name'] = "Abhinam India Nidhi limited";
$CONFIG['inst_managed_by'] = "";
$CONFIG['inst_address1'] = "F-1/3SF Pipal Wala Road, Mohan Garden Uttam Nagar, New Delhi - 110059";
$CONFIG['inst_address2'] = "";
$CONFIG['inst_contact'] = "";
$CONFIG['inst_email'] = "";
$CONFIG['inst_logo'] = "images/logo-1.png";
$CONFIG['white_logo'] = "images/logo-1.png";
$CONFIG['banner'] = "images/banner.jpg";
$CONFIG['inst_url'] = "http://localhost/bine-master";
$CONFIG['inst_type'] = "Bank";
// $CONFIG['sender_id'] = "STGHSB"; // AIRTEL STGHSB
// $CONFIG['noreply_email'] = "noreply@sghs.morg.in";
// $CONFIG['auth_key'] = ""; //STOP MESSAGE
// $CONFIG['auth_key'] = "Dr040moY50ivbsob9DynFw"; //SAKSHI SMS http://202.65.131.176/
/*---------Social Link ----------*/

// $CONFIG['facebook'] = 'https://facebook/';
// $CONFIG['twitter'] = 'https://twitter/';
// $CONFIG['linkedin'] = 'https://linkedin.com/';
// $CONFIG['youtube'] = 'https://youtube/';
// $CONFIG['pinterest'] = 'https://pinterest/';
// $CONFIG['instagram'] = 'https://instagram/';



$CONFIG['app_name'] = 'Bank Master';
$CONFIG['dev_company'] = "Freelance";
$CONFIG['dev_by'] = "Rahul Gupta";
$CONFIG['dev_url'] = "https://kodingkaro.com";
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
$CONFIG['db_user'] ='morg_user';
$CONFIG['db_password'] ='@User_2001';
$CONFIG['db_name'] ='morg_sghs'; // Default Database Name
$CONFIG['base_url'] ='https://sghs.morg.in/'; */

/* Test Configuration */
// $CONFIG['host_name'] ='localhost';
// $CONFIG['db_user'] ='morg_user';
// $CONFIG['db_password'] ='@User_2001';
// $CONFIG['db_name'] ='morg_bine';
// $CONFIG['base_url'] ='https://bine.morg.in/'; 

$CONFIG['branch_list'] = array('AINL-001');
$CONFIG['plan_code_list'] = array('FD', 'RD', 'DRD', 'MIS');
$CONFIG['mode_list'] = array('N/A', 'Daily', 'Single', 'Monthly', 'Yearly');
$CONFIG['mis_list'] = array('N/A', 'Monthly');
$CONFIG['relation_list'] = array('Brother', 'Daughter', 'Father', 'Husband', 'Mother', 'Sister', 'Son', 'Spouse');

$CONFIG['att_month_list'] = array('April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March');
$CONFIG['fee_month'] = array('November', 'December', 'January', 'February', 'March'); // Month Allowed to Collect Fee
$CONFIG['gender_list'] = array('MALE', 'FEMALE', 'OTHER');
$CONFIG['status_list'] = array('ACTIVE', 'BLOCK', 'INACTIVE');
$CONFIG['religion_list'] = array('', 'HINDU', 'MUSLIM', 'SHIKH', 'CHRISTIAN');
$CONFIG['caste_list'] = array('', 'General', 'SC', 'ST', 'OBC');
$CONFIG['bloodgroup_list'] = array('', 'A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-');
$CONFIG['user_type_list'] = array('Admin', 'Employee');
$CONFIG['current_session'] = '2021-2022';

$CONFIG['day_list'] = array('SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY');
/*-------End of Basic Details ---------*/


// $CONFIG['menu_list'] = array();
// $CONFIG['menu_list']['student'] = array('student_add', 'student_manage');
// $CONFIG['menu_list']['fee'] = array('collect_fee', 'collection_report', 'demand_print', 'fee_chart', 'class_wise_ledger');
// $CONFIG['menu_list']['transport'] = array('add_area', 'add_trip', 'area_wise_report');
// $CONFIG['menu_list']['exam'] = array('admit_card', 'exam_sheet', 'marks_entry', 'report_card', 'print_report_card', 'consolidated_marks');
// $CONFIG['menu_list']['website'] = array('notice_board', 'gallery', 'enquiry', 'holiday', 'online_admission', 'online_payment');
// $CONFIG['menu_list']['extra'] = array('send_sms', 'sms_report', 'identity_card', 'certificate_print');
// $CONFIG['menu_list']['settings'] = array('create_fee', 'set_fee', 'subject_settings', 'manage_user');



/* LIBRARY CONFIGRATION */
// $CONFIG['book_status'] = array('AVAILABLE', 'ISSUED', 'MISSING', 'REMOVED');
// $CONFIG['book_txn_status'] = array('ISSUED', 'RETURN', 'MISSED');
// $CONFIG['fine_per_day'] = 1;
// $CONFIG['max_book_allow'] = 3;
// $CONFIG['max_day_allow'] = 15;

/* Inventory Configuration */
$CONFIG['item_status'] = array('IN STOCK', 'OUT OF STOCK', 'REMOVED');
$CONFIG['payment_mode_list'] = array('CASH', 'BANK', 'UPI');
$CONFIG['gst_percent_list'] = array(0, 5, 12, 18, 28);


/* Account Configuration */

$CONFIG['account_head_list'] = array('', 'SALARY', 'INFRASTRUCTURE', 'ENERGY', 'RENT', 'TRANSPORT', 'HOSTEL', 'STATIONARY', 'LIBRARY', 'LABORATORY', 'ACTIVITY', 'DAILY EXPENSES', 'MISCELLANEOUS', 'PRINCIPAL', 'BANK DEPOSIT');

$CONFIG['txn_mode_list'] = array('CASH', 'BANK', 'UNPAID');

$CONFIG['allow_status'] = array('', 'Y', 'NO');
// $CONFIG['task_list'] = array('student' => 'Manage Student', 'enquiry' => 'Manage Enquiry', 'book_cat' => 'Manage Book Category');
// $CONFIG['attendence_status'] = array('' => 'Select', 'P' => 'PRESENT', 'A' => 'ABSENT', 'L' => 'LEAVE');

/* Vehicle Configuration */
// $CONFIG['vehicle_type_list'] = array('', 'BUS', 'VAN', 'MAGIC');

/* Lesson Plan Configuration */
// $CONFIG['timeslot_status_list'] = array('PENDING', 'ONGOING', 'COMPLETED');

/* Leave Configuration */
// $CONFIG['leave_type_list'] = array('Paid', 'Unpaid');
extract($CONFIG);
sort($account_head_list);
extract($CONFIG);
