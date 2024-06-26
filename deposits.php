<?php require_once ('required/header.php'); ?>
<?php require_once ('required/menu.php'); ?>
<?php
if (isset($_GET['link']) and $_GET['link'] != '') {
    $data = decode($_GET['link']);
    $id = $data['id'];
} else {
    $receipt = insert_row('receipt_data');
    $receipt_id = $receipt['id'];
}

if ($id != '') {
    $res = get_data('receipt_data', $id);
    if ($res['count'] > 0 and $res['status'] == 'success') {
        extract($res['data']);
    }
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Deposit Sheet Entry</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="#fee">Deposit</a></li>
            <li class="breadcrumb-item active">Deposit Entry</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Basic Forms -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Deposit Entry</h3>
            </div>
            <!-- /.box-header -->
            <?php echo $receipt_id; ?>
            <div class="box-body">
                <form action='' method='post'>
                    <div class='row'>
                        <div class="col-lg-2 col-offset-lg-2"></div>
                        <div class="col-lg-2 col-offset-lg-2">
                            <div class="form-group">
                                <label>Search Via</label>
                                <select class="form-control" name='search_by' required>
                                    <option value='email'>Email</option>
                                    <option value='reg_no'>Reg No.</option>
                                    <option value='id'>ID </option>
                                    <option value='phone'>Mobile No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">

                                <div class="form-group has-success">
                                    <label class="control-label" for="inputSuccess">Enter value</label>
                                    <input type="text" class='form-control' name='search_text' required autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="control-label">&nbsp; Alt +S to Search </label>
                                <input type="submit" class='btn btn-success btn-md' value='Search Member' name='search'
                                    accesskey='s'>
                            </div>
                        </div>
                    </div>
                </form>

                <div class='row'>
                    <div class="col-lg-12">
                        <hr>
                        <!-- Advanced Tables -->
                        <?php
                        if (isset($_REQUEST['search_text'])) {
                            $sql = "select * from members where status <>'BLOCK' and ";
                            $search_by = xss_clean(trim($_REQUEST['search_by']));
                            $search_text = xss_clean(trim($_REQUEST['search_text']));

                            if ($search_by == 'email' or $search_by == 'id' or $search_by == 'reg_no' or $search_by == 'phone') {
                                $sql .= " $search_by = '$search_text'";
                            }
                        }
                        if (!empty($sql)) {
                            $member_data = direct_sql($sql, 'get');

                            if ($member_data['status'] == 'success') {
                                $data_member = $member_data['data'];
                            }
                        }

                        ?>
                        <form id="update_policy_frm" action="update_policy">
                            <div class="row">
                                <div class="col-md-6">
                                    <h2>Applicant Details</h2>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="policy_no">Policy No.</label>
                                            <input type="hidden" name="id" value="<?= $receipt_id; ?>">
                                            <input class="form-control border-warning" type="text" name="policy_no"
                                                id="policy_no">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="policy_date">Branch Name</label>
                                            <select class="form-control border-warning" name="branch_name"
                                                id="branch_name">
                                                <?= dropdown($branch_list); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="policy_date">Policy Date</label>
                                            <input class="form-control border-warning" type="date" name="policy_date"
                                                id="policy_date">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="form_no">Form No</label>
                                            <input class="form-control border-warning" type="text" name="form_no"
                                                id="form_no">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="maturity_date">Maturity Date</label>
                                            <input class="form-control border-warning" type="date" name="maturity_date"
                                                id="maturity_date">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="entry_date">Date of Entry</label>
                                            <input class="form-control border-warning" type="date" name="entry_date"
                                                id="entry_date" value="<?= date('Y-m-d'); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="folio_no">Folio No</label>
                                            <input class="form-control border-warning" type="text" name="folio_no"
                                                id="folio_no">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mr_no">MR No</label>
                                            <input class="form-control border-warning" type="text" name="mr_no"
                                                id="mr_no">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="membership_name">Membership Name</label>
                                            <input type="hidden" name="member_id" value="<?= $data_member[0]['id']; ?>">
                                            <input type="text" class="form-control border-warning"
                                                name="membership_name" value="<?= $data_member[0]['name'] ?>"
                                                id="membership_name">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="reg_no">Membership Code No.</label>
                                            <input class="form-control border-warning" type="text" name="reg_no"
                                                id="reg_no" value="<?= $data_member[0]['reg_no'] ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="name">Name</label>
                                            <input class="form-control border-warning" type="text" name="name" id="name"
                                                value="<?= $data_member[0]['name'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="father_name">Father Name</label>
                                            <input class="form-control border-warning" type="text" name="father_name"
                                                id="father_name" value="<?= $data_member[0]['father_name'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="address">Address</label>
                                            <textarea class="form-control border-warning" cols="2" rows="2"
                                                name="address"
                                                id="address"><?= $data_member[0]['permanent_address'] ?></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="dob">DOB</label>
                                            <input class="form-control border-warning" type="date" name="dob" id="dob"
                                                value="<?= $data_member[0]['dob']; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="age">Age</label>
                                            <input class="form-control border-warning" type="text" name="member_age"
                                                id="member_age">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="pincode">PIN</label>
                                            <input class="form-control border-warning" type="text" name="pincode"
                                                id="pincode" value="<?= $data_member[0]['pincode'] ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone">Phone</label>
                                            <input class="form-control border-warning" type="tel" name="phone"
                                                id="phone" value="<?= $data_member[0]['phone'] ?>">
                                        </div>
                                    </div>

                                    <h2>2nd Applicant Details</h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="second_applicant_name">Name</label>
                                            <input class="form-control border-warning" type="text"
                                                name="second_applicant_name" id="second_applicant_name">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="second_applicant_age">Age</label>
                                            <input class="form-control border-warning" type="number"
                                                name="second_applicant_age" id="second_applicant_age">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="relation_with_second_applicant">Relation</label>
                                            <select class="form-control border-warning" name="second_applicant_relation"
                                                id="relation_with_second_applicant">
                                                <?= dropdown($relation_list); ?>
                                            </select>
                                        </div>
                                    </div>

                                    <h2>Nominee Details</h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="nominee_name">Name</label>
                                            <input class="form-control border-warning" type="text" name="nominee_name"
                                                id="nominee_name">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nominee_age">Age</label>
                                            <input class="form-control border-warning" type="number" name="nominee_age"
                                                id="nominee_age">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="relation_with_nominee">Relation</label>
                                            <select class="form-control border-warning" name="nominee_relation"
                                                id="relation_with_nominee">
                                                <?= dropdown($relation_list); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h2>Plan Details</h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="plan_code">Plan Code</label>
                                            <select class="form-control border-warning" name="plan_code" id="plan_code">
                                                <option value="" selected disabled>Select Plan Code</option>
                                                <?= dropdown($plan_code_list); ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="plan_mode">Mode</label>
                                            <select class="form-control border-warning" name="mode" id="mode">
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="plan_table">Table</label>
                                            <select class="form-control border-warning" name="plan_detail"
                                                id="plan_detail">
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="plan_code">MIS</label>
                                            <select class="form-control border-warning" name="mis" id="mis">
                                            </select>
                                        </div>
                                    </div>
                                    <h2>Payment Details</h2>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="amount">Amount</label>
                                            <input class="form-control border-warning" type="text" name="amount"
                                                id="amount">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="deposit_amount">Deposit Amount</label>
                                            <input class="form-control border-warning" type="text" name="deposit_amount"
                                                id="deposit_amount">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="term">Term</label>
                                            <input class="form-control border-warning" type="text" name="term"
                                                id="term">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="maturity_amount">Maturity amount</label>
                                            <input class="form-control border-warning" type="text"
                                                name="maturity_amount" id="maturity_amount">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="reg_amount">Reg. Amt.</label>
                                            <input class="form-control border-warning" type="text" name="reg_amount"
                                                id="reg_amount">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="bonus_amount">Bonus Amount</label>
                                            <input class="form-control border-warning" type="text" name="bonus_amount"
                                                id="bonus_amount">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="mis_return">MIS Return</label>
                                            <input class="form-control border-warning" type="text" name="mis_return"
                                                id="mis_return">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="distinctive_no">Distinctive No(s)</label>
                                            <input class="form-control border-warning" type="text" name="distinctive_no"
                                                id="distinctive_no">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="distinctive_to">To</label>
                                            <input class="form-control border-warning" type="text" name="distinctive_to"
                                                id="distinctive_to">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="cash"><input type="radio" name="payment_mode" id="cash"
                                                    value="Cash">Cash</label>
                                            <label for="cheque"><input type="radio" name="payment_mode" id="cheque"
                                                    value="Cheque">Cheque</label>
                                            <label for="saving_act"><input type="radio" name="payment_mode"
                                                    id="saving_act" value="Savings A/c">Savings A/c</label>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cheque_dd">Cheque/DD No.:</label>
                                            <input class="form-control border-warning" type="text" name="cheque_dd_no"
                                                id="checque_dd">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cheque_dd_date">Cheque/DD Date:</label>
                                            <input class="form-control border-warning" type="date" name="cheque_dd_date"
                                                id="cheque_dd_date">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="cheque_dd_bank">Cheque/DD Bank</label>
                                            <input class="form-control border-warning" type="text" name="cheque_dd_bank"
                                                id="cheque_dd_bank">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="deposit_account_no">Deposit A/c No.</label>
                                            <input class="form-control border-warning" type="text"
                                                name="deposit_account" id="deposit_account_no">
                                        </div>
                                        <!-- <div class="col-md-6">
                                                <label for="deposit_account_no">Deposit A/c No.</label>
                                                <input class="form-control border-warning" type="text"
                                                    name="deposit_account_no" id="deposit_account_no">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="deposit_account_no">Deposit A/c No.</label>
                                                <input class="form-control border-warning" type="text"
                                                    name="deposit_account_no" id="deposit_account_no">
                                            </div> -->
                                        <input type="hidden" name="status" value="Active">
                                    </div>
                                    <h2>Employee</h2>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label for="employee_code">Employee Code</label>
                                            <input class="form-control border-warning" type="text" name="employee_code"
                                                id="employee_code">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="rank">Rank</label>
                                            <input class="form-control border-warning" type="number"
                                                name="employee_rank" id="employee_rank">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="employee_name">Name</label>
                                            <input class="form-control border-warning" type="text" name="employee_name"
                                                id="employee_name">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="remarks">Remarks</label>
                                            <textarea class="form-control border-warning" cols="10" rows="2"
                                                name="remarks" id="remarks"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8"></div>
                                <div class="col-md-4">
                                    <button class="btn btn-success btn-block" id='update_policy_btn'> Save </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </section>
</div>
<?php require_once ('required/footer.php'); ?>