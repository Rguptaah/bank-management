<?php require_once('required/header.php'); ?>
<?php
require_once('required/menu.php');
if (isset($_REQUEST['category']) and isset($_REQUEST['att_date'])) {
    $category = $_REQUEST['category'];
    $att_date = $_REQUEST['att_date'];
} else {
    $att_date = date('Y-m-d');
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h2> Manage Employee Attendance &nbsp;<span class="badge badge-warning badge-sm p-2 circle">NEW</span></h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item active">Attendance</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Basic Forms -->
        <div class="box box-default">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-md-8">
                        <b><span class="text-danger">Month</span> : <?php echo  date('M') ?> <span class="text-danger"> Year</span> : <?php echo date("Y"); ?></b> [<?php echo $category; ?>]
                    </div>
                    <div class="col-md-4 float-right">
                        <div class="row">
                            <div class="col-md-12">
                                <form>
                                    <select name="category" id="category" class="display6 float-right" required onchange='submit()'>
                                        <option value="">--Select Type--</option>
                                        <?php
                                        $emp_cat = get_all('emp_cat');
                                        foreach ($emp_cat['data'] as $row) {
                                            $cat_name_list[$row['cat_name']] = $row['cat_name'];
                                        }
                                        dropdown($cat_name_list, $category);
                                        ?>
                                    </select>
                                    <input type='date' name='att_date' value='<?php echo $att_date; ?>' id='att_date' max='<?php echo date('Y-m-d'); ?>' class="display6">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-end" style="float:right">
                            <span class='btn btn-primary btn-sm border ' style="float:right">
                                <input type="checkbox" id="selectall" onclick="selectAll(this)" /> Check All
                            </span>&nbsp;
                            <button id='present_btn' class='btn btn-success btn-sm' title='Present All Checked Data' style="float:right; margin-right:8px;">Present</button> &nbsp;
                            <button id='abs_btn' class='btn btn-danger btn-sm' title='Absent All Checked Data' style="float:right; margin-right:8px;">Absent</button> &nbsp;
                            <!-- <button id='att_btn' class='btn btn-warning btn-sm' title='Leave Checked Data' style="float:right; margin-right:8px;">Leave</i> </button> &nbsp; -->
                            <!-- <button id='att_btn' class='btn btn-success btn-sm' title='Save Data' style="float:right; margin-right:8px;"><i class='fa fa-save'></i> </button> &nbsp; -->
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class="col-lg-12 col-md-12">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-stripped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    if (isset($_REQUEST['category']) and isset($_REQUEST['att_date'])) {
                                        extract($_REQUEST);
                                        $res = get_all('employee', '*', array('e_category' => $category)); ?>
                                        <form action='required/master_process.php?task=make_emp_att' method='post' id='att_frm'>
                                        <?php
                                        if ($res['count'] > 0) {
                                            foreach ($res['data'] as $row) {
                                                $id = $row['id'];
                                                $emp_type = $row['e_category'];
                                                echo "<tr>";
                                                echo "<td>" . $i . "</td>";
                                                echo "<td>" . $row['e_code'] . "</td>";
                                                echo "<td>" . $row['e_name'] . "</td>";
                                                echo "<td>" . $emp_type . "</td>";
                                                echo "<td width='185'>";
                                                $tbl_name = "employee_att";
                                                $col_name = 'd_' . date('j', strtotime($att_date));
                                                if (date('D', strtotime($att_date)) == 'Sun') {
                                                    echo "<script> alert('Selected Date is sunday');</script>";
                                                }
                                                $mvalue = remove_space(date('M_Y', strtotime($att_date)));
                                                $post = array('att_month' => $mvalue, 'emp_id' => $id);
                                                $sql = "SELECT * from $tbl_name where emp_id = $id and att_month like '$mvalue' ";
                                                $emp_att = direct_sql($sql);
                                                if ($emp_att['count'] == 0) {
                                                    insert_data($tbl_name, $post);
                                                }
                                                if ($emp_att['data'][0][$col_name] == 'P') {
                                                    echo "P";
                                                } else if ($emp_att['data'][0][$col_name] == 'A') {
                                                    echo "A";
                                                } else if ($emp_att['data'][0][$col_name] == 'L') {
                                                    echo "L";
                                                } else {
                                                    echo "<input data-emp='$emp_type' type='checkbox' value ='$id' name='sel_id[]' class='chk'>&nbsp;";
                                                    echo "<a data-id='$id' class='btn btn-danger btn-sm text-light' data-target='#addLeave' data-toggle='modal' id='add_leave_btn'>Add Leave</a>";
                                                }

                                                echo "</td></tr>";
                                                $i++;
                                            }
                                        }
                                    }
                                        ?>
                                        </form>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal for Leave Starts-->
<div class="modal fade" id="addLeave" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog w-90" role="document">
        <div class="modal-content">
            <!--<div class="modal-body"> -->
            <div class="card rounded">
                <div class="card-header">
                    <h3 class="text-center">Add Leave</h3>
                </div>
                <div class="card-body">
                    <?php
                    $leave = insert_row("leave_details");
                    $lid = $leave['id'];
                    ?>
                    <form action="leave_details" id="leave_frm" method="POST">
                        <div class="form-group">
                            <label for="leave_type">Leave Type</label>
                            <select name="leave_type" id="leave_type" class="form-control" required>
                                <option value="">--Select Leave Type--</option>
                                <?php dropdown($leave_type_list, $leave_type); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="from_date">From Date</label>
                            <input type="date" name="from_date" id="from_date" class="form-control" value="<?php echo date("Y-m-d"); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="to_date">To Date</label>
                            <input type="date" name="to_date" id="to_date" class="form-control" value="<?php echo date("Y-m-d"); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="leave_cause">Leave Cause (upto 100 words)</label>
                            <input type="hidden" name="id" value="<?php echo $lid; ?>">
                            <input type="hidden" name="emp_id" id="emp_id">
                            <textarea type="text" name="leave_cause" id="leave_cause" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="leave_app" id="leave">
                            <form id="uploadLeaveApp" enctype="multipart/form-data">
                                <div id="displayLeaveApp"></div>
                                <label class="form-label">Upload Application</label>
                                <input type="file" class="form-control" id="leave_app">
                            </form>
                        </div>
                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <input type="text" name="remarks" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <?php dropdown($status_list, $status); ?>
                            </select>
                        </div>
                    </form>
                </div>
                <!-- </div>
            </div> -->
                <div class="card-footer p-2 text-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="leave_btn">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Ends -->

<?php require_once('required/footer2.php'); ?>
<script>
    //========== SELECT ALL CHECK BOX WITH PRESENT =======//

    function selectAll(source) {
        checkboxes = document.getElementsByName('sel_id[]');
        for (var i in checkboxes)
            checkboxes[i].checked = source.checked;
    }

    $("#add_leave_btn").on('click', function() {
        let emp_id = $(this).attr("data-id");
        document.getElementById("emp_id").value = emp_id;
    })

    //========================UPLOAD LEAVE APPLICATION=======================
    $('#leave_app').change(function() {
        $("#uploadLeaveApp").submit();
    });

    $("#uploadLeaveApp").on('submit', (function(e) {
        e.preventDefault();
        $.ajax({
            url: "required/master_process?task=uploadLeaveApp",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                var obj = JSON.parse(data);
                $("#leave").val(obj.id);
                $("#displayLeaveApp").html("<img src='required/upload/" + obj.id + "' width='100px' height='100px' class='img-thumbnail'>");
                $.notify(obj.msg, obj.status);
            },
            error: function() {}
        });
    }));
</script>