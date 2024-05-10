<?php session_start();
$CONFIG['token'] = session_id();
date_default_timezone_set("Asia/Kolkata");
$CONFIG['current_date_time'] = date('Y-m-d H:i:s');
$CONFIG['today'] = date('Y-m-d'); // '2021-11-13'; 
error_reporting(E_ERROR | E_PARSE);
/*-------Some Basic Details (Global Variables) ---------*/

$CONFIG['full_name'] = "Banking Facilities";
$CONFIG['inst_name'] = "Banking";
$CONFIG['inst_managed_by'] = "";
$CONFIG['inst_address1'] = "Noida India";
$CONFIG['inst_address2'] = "Noida UP India";
$CONFIG['inst_contact'] = "8581843939";
$CONFIG['inst_email'] = "developer.freelance1511@gmail.com";
$CONFIG['inst_logo'] = "images/logo.png";
$CONFIG['white_logo'] = "images/logo.png";
$CONFIG['banner'] = "images/banner.jpg";
$CONFIG['inst_url'] = "localhost/bine-master";
$CONFIG['inst_type'] = "Bank";
// $CONFIG['sender_id'] = "STGHSB"; // AIRTEL STGHSB
// $CONFIG['noreply_email'] = "noreply@sghs.morg.in";
// $CONFIG['auth_key'] = ""; //STOP MESSAGE
// $CONFIG['auth_key'] = "Dr040moY50ivbsob9DynFw"; //SAKSHI SMS http://202.65.131.176/
/*---------Social Link ----------*/

// $CONFIG['facebook'] = 'https://facebook/OfferPlant';
// $CONFIG['twitter'] = 'https://twitter/OfferPlant';
// $CONFIG['linkedin'] = 'https://linkedin/company/OfferPlant';
// $CONFIG['youtube'] = 'https://youtube/OfferPlant';
// $CONFIG['pinterest'] = 'https://pinterest/OfferPlant';
// $CONFIG['instagram'] = 'https://instagram/OfferPlant';



$CONFIG['app_name'] = 'Bine Master';
$CONFIG['dev_company'] = "Freelance";
$CONFIG['dev_by'] = "Rahul Gupta";
$CONFIG['dev_url'] = "";
$CONFIG['dev_email'] = "developer.freelance1511@gmail.com";
$CONFIG['dev_contact'] = "8581843939";
// $CONFIG['api_key'] = '13ea2963ad0e2903bdce009324decadc';
// $CONFIG['default_sms'] = 'send_sms';


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

$CONFIG['month_list'] = array('Admission_month', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March');
$CONFIG['att_month_list'] = array('April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March');
$CONFIG['fee_month'] = array('November', 'December', 'January', 'February', 'March'); // Month Allowed to Collect Fee

$CONFIG['class_list'] = array('', 'NUR', 'LKG', 'UKG', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X');
$CONFIG['section_list'] = array('', 'A', 'B');
$CONFIG['gender_list'] = array('MALE', 'FEMALE', 'OTHER');
$CONFIG['status_list'] = array('ACTIVE', 'BLOCK', 'INACTIVE');
$CONFIG['vehicle_status_list'] = array('ASSIGNED', 'NOT ASSIGNED');
$CONFIG['religion_list'] = array('', 'HINDU', 'MUSLIM', 'SHIKH', 'CHRISTIAN');
$CONFIG['caste_list'] = array('', 'General', 'SC', 'ST', 'OBC');
$CONFIG['student_type_list'] = array('', 'GENERAL', 'TRANSPORT', 'HOSTELER');
$CONFIG['bloodgroup_list'] = array('', 'A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-');
$CONFIG['admission_list'] = array('', 'NEW', 'OLD');
$CONFIG['finance_list'] = array('NORMAL', 'BPL', 'FREE', 'WARD');
$CONFIG['e_type_list'] = array('', 'PGT', 'TGT', 'PRT', 'Teaching', 'Non Teaching', 'Administration', 'Counselor', 'Librarian');
$CONFIG['department_list'] = array('', 'Administration', 'IT', 'Language', 'Science', 'Maths', 'SST', 'Non-Teaching');
$CONFIG['qualification_list'] = array('', 'No Formal Education', 'Primary Education', 'Secondary Education or High School', 'Vocational Qualification', 'Bachelor\'s degree', 'Master\'s degree', 'Doctorate or higher');
$CONFIG['occupation_list'] = array('', 'Unemployed', 'Private Job', 'Govt. Job', 'Self Employed', 'Professional', 'Farmer', 'Student', 'House Wife');
$CONFIG['income_list'] = array('', 'No Income', 'Less Than 1,00,000', '1,00,001 to 3,00,000', '3,00,001 to 10,00,000', '10,00,001 to 25,00,000', '25,00,001 or above');
$CONFIG['account_list'] = array('Tuition', 'Hostel', 'Transport', 'Director Cash', 'School A/c', 'Staff Salary', 'Office Expense', 'Miscellenous');
$CONFIG['user_type_list'] = array('Account', 'Class Teacher', 'DBA', 'Parent', 'Driver');
$CONFIG['subject_list'] = array('Hindi', 'English', 'Mathematics', 'EVS', 'SST', 'Science', 'Infomation_Technology', 'General_Knowledge', 'Sanskrit_Urdu');
$CONFIG['co_scholastic_list'] = array('Work Education ', 'Health & Physical Education', 'Discipline ');
$CONFIG['co_grade_list'] = array('', 'A', 'B', 'C');
$CONFIG['exam_list'] = array('', 'term1', 'term2');
$CONFIG['current_session'] = '2021-2022';
$CONFIG['adm_suffix'] = '/2021-22';
$CONFIG['fee_mode_list'] = array('Monthly', 'Annual', 'OneTime');
$CONFIG['fee_nature_list'] = array('CLASS' => 'Based On Class', 'STUDENT' => 'Based On Student', 'FIXED' => 'Fixed For All');
$CONFIG['subject_category_list'] = array('', 'Scholastic', 'Non Scholastic');
$CONFIG['smc_list'] = array('PDF', 'YOUTUBE', 'GOOGLE MEET', 'RICH TEXT', 'OTHER');

$CONFIG['day_list'] = array('SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY');
/*-------End of Basic Details ---------*/


$CONFIG['session_list'] = array('' => 'Select Session', 'morg_sghs' => '2021-2022');


$CONFIG['menu_list'] = array();
$CONFIG['menu_list']['student'] = array('student_add', 'student_manage');
$CONFIG['menu_list']['fee'] = array('collect_fee', 'collection_report', 'demand_print', 'fee_chart', 'class_wise_ledger');
$CONFIG['menu_list']['transport'] = array('add_area', 'add_trip', 'area_wise_report');
$CONFIG['menu_list']['exam'] = array('admit_card', 'exam_sheet', 'marks_entry', 'report_card', 'print_report_card', 'consolidated_marks');
$CONFIG['menu_list']['website'] = array('notice_board', 'gallery', 'enquiry', 'holiday', 'online_admission', 'online_payment');
$CONFIG['menu_list']['extra'] = array('send_sms', 'sms_report', 'identity_card', 'certificate_print');
$CONFIG['menu_list']['settings'] = array('create_fee', 'set_fee', 'subject_settings', 'manage_user');



/* LIBRARY CONFIGRATION */
$CONFIG['book_status'] = array('AVAILABLE', 'ISSUED', 'MISSING', 'REMOVED');
$CONFIG['book_txn_status'] = array('ISSUED', 'RETURN', 'MISSED');
$CONFIG['fine_per_day'] = 1;
$CONFIG['max_book_allow'] = 3;
$CONFIG['max_day_allow'] = 15;

/* Inventory Configuration */
$CONFIG['item_status'] = array('IN STOCK', 'OUT OF STOCK', 'REMOVED');
$CONFIG['payment_mode_list'] = array('CASH', 'BANK', 'UPI');
$CONFIG['gst_percent_list'] = array(0, 5, 12, 18, 28);


/* Account Configuration */

$CONFIG['account_head_list'] = array('', 'SALARY', 'INFRASTRUCTURE', 'ENERGY', 'RENT', 'TRANSPORT', 'HOSTEL', 'STATIONARY', 'LIBRARY', 'LABORATORY', 'ACTIVITY', 'DAILY EXPENSES', 'MISCELLANEOUS', 'PRINCIPAL', 'BANK DEPOSIT');

$CONFIG['txn_mode_list'] = array('CASH', 'BANK', 'UNPAID');

$CONFIG['allow_status'] = array('', 'Y', 'NO');
$CONFIG['task_list'] = array('student' => 'Manage Student', 'enquiry' => 'Manage Enquiry', 'book_cat' => 'Manage Book Category');
$CONFIG['attendence_status'] = array('' => 'Select', 'P' => 'PRESENT', 'A' => 'ABSENT', 'L' => 'LEAVE');

/* Vehicle Configuration */
$CONFIG['vehicle_type_list'] = array('', 'BUS', 'VAN', 'MAGIC');

/* Lesson Plan Configuration */
$CONFIG['timeslot_status_list'] = array('PENDING', 'ONGOING', 'COMPLETED');

/* Leave Configuration */
$CONFIG['leave_type_list'] = array('Paid', 'Unpaid');
extract($CONFIG);
sort($account_head_list);
extract($CONFIG);
