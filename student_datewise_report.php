<!--datewise student report-->
<?php require_once('required/header.php'); ?>
<?php
require_once('required/menu.php');

if (isset($_REQUEST['att_date'])) {
    $att_date = $_REQUEST['att_date'];
} else {
    $att_date = date('Y-m-d');
}
$month = date('m', strtotime($att_date));
$year = date('Y', strtotime($att_date));
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Datewise Student Attendance</h1>
        <!--&nbsp;<span class="badge badge-success badge-sm p-2">NEW</span>-->
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
                        [<?php if (isset($_REQUEST['class']) and isset($_REQUEST['section'])) {
                                extract($_REQUEST);
                                echo $class . "-" . $section;
                            } ?>] &nbsp; <buuton class="btn btn-success btn-sm" id="export">Export</buuton>
                    </div>
                    <div class="col-md-4 float-right">
                        <div class="row">
                            <div class="col-md-12">
                                <form>
                                    <select name="class" id="class" class='w-120 h6'>
                                        <?php dropdown($class_list, $class); ?>
                                    </select>
                                    <select name="section" id="section" class='h6'>
                                        <?php dropdown($section_list, $section); ?>
                                    </select>

                                    <input type="date" name="att_date" id="att_date" class="h6 border-rounded bg-light float-right" onblur="submit()" value="<?php echo $att_date; ?>">
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <div class='row'>
                    <div class="col-lg-12 col-md-12">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-stripped">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                        <?php
                                        for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++) {
                                            echo "<th>" . $i . "</th>";
                                        }
                                        ?>
                                        <th>Total Present</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    if (isset($_REQUEST['class']) and isset($_REQUEST['section']) and isset($_REQUEST['att_date'])) {
                                        extract($_REQUEST);
                                        $res = get_all('student', '*', array('student_class' => $class, 'student_section' => $section));
                                    ?>
                                    <?php
                                        if ($res['count'] > 0) {
                                            foreach ($res['data'] as $row) {
                                                $id = $row['id'];
                                                $stu_class = $row['student_class'];
                                                $stu_section = $row['student_section'];
                                                echo "<tr>";
                                                echo "<td>" . $id . "</td>";
                                                echo "<td>" . $row['student_name'] . "</td>";
                                                echo "<td>" . $stu_class . "</td>";
                                                echo "<td>" . $stu_section . "</td>";
                                                // echo "<td width='185'>";
                                                $tbl_name = "student_att";
                                                if (date('D', strtotime($att_date)) == 'Sun') {
                                                    echo "<script> alert('Selected Date is sunday');</script>";
                                                }
                                                $mvalue = remove_space(date('M_Y', strtotime($att_date)));
                                                $post = array('att_month' => $mvalue, 'student_id' => $id);
                                                $sql = "SELECT * FROM `student_att` WHERE `student_id` = $id AND `att_month` LIKE '$mvalue' ";
                                                $stu_att = direct_sql($sql);
                                                if ($stu_att['count'] > 0) { {
                                                        foreach ($stu_att['data'] as $row) {
                                                            $count = 0;
                                                            for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++) {
                                                                $day = 'd_' . $i;
                                                                echo "<td>" . $row[$day] . "</td>";
                                                                if ($row[$day] == "P") {
                                                                    $count++;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    echo "<td>" . $count . "</td>";
                                                    echo "</tr>";
                                                    $i++;
                                                }
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
<?php require_once('required/footer2.php'); ?>
<script>
    //=========SELECT ALL CHECK BOX WITH SAME NAME=======//
    function selectAll(source) {
        checkboxes = document.getElementsByName('sel_id[]');
        for (var i in checkboxes) {
            checkboxes[i].checked = source.checked;
        }
    }
    $("#export").click(function() {
        $("#example1").table2excel({
            // exclude CSS class
            exclude: ".noExl",
            name: "Worksheet Name",
            filename: "Datewise_Student_Attendance_Report", //do not include extension
            fileext: ".xls", // file extension
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    });
</script>