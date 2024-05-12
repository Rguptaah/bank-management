<?php require_once ('required/header.php'); ?>
<?php require_once ('required/menu.php');

if (isset($_GET['link']) and $_GET['link'] != '') {
    $fds = decode($_GET['link']);
    $id = $fds['id'];
} else {
    $fds = insert_row('plans');
    $id = $fds['id'];
}

if ($id != '') {
    $res = get_data('plans', $id);
    if ($res['count'] > 0 and $res['status'] == 'success') {
        extract($res['data']);
        if ($fds_registration == '') {
            $fds_registration = 1000 + $fds['id'];
        }
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Plan Details</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="#">Plan</a></li>
            <li class="breadcrumb-item active">Add Plan</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Basic Forms -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Plan Details </h3>

                <div class="box-tools pull-right">
                    <button class="btn btn-success" id='update_plans_btn'><i class='fa fa-save'></i> Save</button>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <form id='update_frm' action='update_plans'>
                    <div class="row">

                        <div class="col-lg-12">
                            <div class="form-group row">

                                <label for="example-text-input" class="col-sm-4 col-form-label">Plan code</label>
                                <div class="col-sm-8">
                                    <input type='hidden' name='id' value='<?php echo $id; ?>' />
                                    <select class="form-control border-warning" name="plan_code" id="plan_code">
                                        <?= dropdown($plan_code_list,$plan_code); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Plan Detail</label>
                                <div class="col-sm-8">
                                    <input class="form-control border-warning" type="text" value='<?php echo $plan_detail; ?>'
                                        name='plan_detail' required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Mode</label>
                                <div class="col-sm-8">
                                    <select class="form-control border-warning" name="mode" id="mode">
                                        <?= dropdown($mode_list,$mode); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">MIS</label>
                                <div class="col-sm-8">
                                    <select class="form-control border-warning" name="mis" id="mis">
                                        <?= dropdown($mis_list,$mis); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Term</label>
                                <div class="col-sm-8">
                                    <input type="text" name="term" id="term" class="form-control border-warning" value="<?= $term; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Status</label>
                                <div class="col-sm-8">
                                    <select class="form-control border-warning" name="status" id="status">
                                        <?= dropdown($status_list,$status); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.box-body -->
        </form>
    </section>
</div>
<?php require_once ('required/footer2.php'); ?>