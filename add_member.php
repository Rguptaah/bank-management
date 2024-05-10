<?php require_once ('required/header.php'); ?>
<?php require_once ('required/menu.php');

if (isset($_GET['link']) and $_GET['link'] != '') {
    $member = decode($_GET['link']);
    $id = $member['id'];
} else {
    $member = insert_row('members');
    // print_r($member);
    // // die();
    $id = $member['id'];
}

if ($id != '') {
    $res = get_data('members', $id);
    if ($res['count'] > 0 and $res['status'] == 'success') {
        extract($res['data']);
        if ($member_registration == '') {
            $member_registration = 1000 + $member['id'];
        }
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Member Details</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item"><a href="#">Member</a></li>
            <li class="breadcrumb-item active">Add Member</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Basic Forms -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Member Details </h3>

                <div class="box-tools pull-right">
                    <button class="btn btn-success" id='update_btn'><i class='fa fa-save'></i> Save</button>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <form id='update_frm' action='update_member'>
                    <div class="row">

                        <div class="col-lg-4">
                            <div class="form-group row">

                                <label for="example-text-input" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input type='hidden' name='id' value='<?php echo $id; ?>' />
                                    <input class="form-control border-warning" type="text"
                                        value='<?php echo $name; ?>' name="name" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Father's Name</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" value='<?php echo $father_name; ?>'
                                        name='father_name' required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Date of Birth</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="date" value='<?php echo $dob; ?>'
                                        name='dob' id='example-date-input'>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Gender</label>
                                <div class="col-sm-8">
                                    <select name='gender' class='form-control' required>
                                        <?php dropdown($gender_list, $gender); ?>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Blood Group</label>
                                <div class="col-sm-8">
                                    <select name='member_bloodgroup' class='form-control'>
                                        <?php //dropdown($bloodgroup_list, $member_bloodgroup); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Religion</label>
                                <div class="col-sm-8">
                                    <select name='member_religion' class='form-control'>
                                        <?php //dropdown($religion_list, $member_religion); ?>
                                    </select>
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Email Id</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="email" value='<?php echo $email; ?>'
                                        name='email'>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Aadhar No.</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" value='<?php echo $aadhar_no; ?>'
                                        name='aadhar_no'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">PAN No.</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" value='<?php echo $pan_no; ?>'
                                        name='pan_no'>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">State</label>
                                <div class="col-sm-8">
                                    <select name='state' class='form-control' onchange='getdistrict(this.value)'>
                                        <?php dropdown_list('state', 'code', 'name', $state); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">District</label>
                                <div class="col-sm-8">
                                    <select name='district' class='form-control' id='district_list'>
                                        <?php dropdown_where('district', 'code', 'name', array('state_code' => $state), $district); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Permanent Address</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" rows="3" id='address1'
                                        name='permanent_address'><?php echo $permanent_address; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Current Address
                                    <div class="checkbox text-sm">
                                        <!-- <input type="checkbox" id="basic_checkbox_1">
                                        <label for="basic_checkbox_1">Same </label> -->
                                    </div>
                                </label>
                                <div class="col-sm-8">
                                    <textarea class="form-control" rows="3" id='address2'
                                        name='current_address'><?php echo $current_address; ?></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Pin Code</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" value='<?php echo $pincode; ?>'
                                        pattern="[0-9]{6}" name="pincode" maxlength="6" minlength="6">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Mobile No.</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="tel" value='<?php echo $phone; ?>'
                                        pattern="[6789][0-9]{9}" name="phone" required minlength="10"
                                        maxlength="10">
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-4">

                            <div class="form-group row">
                                <div class="col-sm-12 text-center">
                                    <?php if(!empty($profile_pic)): ?>
                                        <div id='display'><img src='required/upload/<?= $profile_pic; ?>' width='150px'
                                            height='160px' id='result'></div>
                                    <?php else: ?>
                                    <div id='display'><img src='required/upload/no_image.jpg' width='150px'
                                            height='160px' id='result'></div>
                                    <?php endif; ?>
                                    <input type='hidden' name='profile_pic' id='targetimg' class='form-control'
                                        readonly value='<?php echo $profile_pic; ?>'> <span id='uploadarea'
                                        class='btn btn-secondary'>UPLOAD /CHANGE PHOTO </span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12 text-center">
                                    <?php if(!empty($aadhar_pic)): ?>   
                                        <div id='displayaadhar'><img src='required/upload/<?= $aadhar_pic ?>' width='150px'
                                            height='160px' id='result'></div>
                                    <?php else: ?>
                                        <div id='displayaadhar'><img src='required/upload/no_image.jpg' width='150px'
                                            height='160px' id='result'></div>
                                    <?php endif; ?>
                                    <input type='hidden' name='aadhar_pic' id='targetimgaadhar' class='form-control'
                                        readonly value='<?php echo $aadhar_pic; ?>'> <span id='aadharUploadArea'
                                        class='btn btn-secondary'>UPLOAD /CHANGE AADHAR PHOTO </span>
                                </div>
                            </div>




                        </div>

                    </div>
                    <!-- /.col -->

                    <h3 class="box-title bg-gray p-2">Registration Details</h3>

                    <div class="row">

                        <div class="col-lg-6">

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Date of
                                    registration</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="date"
                                        value='<?php echo $reg_date; ?>' name='reg_date'>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Registration
                                    No.*</label>
                                <div class="col-sm-8">
                                    <input class="form-control border-warning" type="text"
                                        value='<?php echo $reg_no; ?>' name='reg_no'
                                        required>
                                </div>
                            </div>


                        </div>

                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="example-text-input" class="col-sm-4 col-form-label">Status </label>
                                <div class="col-sm-8">
                                    <select name='status' class='form-control' required>
                                        <?php dropdown($status_list, $status); ?>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- /.col -->
                    <!-- /.col -->

            </div>
            <!-- /.row -->

        </div>
        <!-- /.box-body -->
        </form>
    </section>
</div>

<div class='modal' id='uploadmodal'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-body'>
                <h4> Upload Profile </h4>
                <hr>
                <form id='uploadForm' enctype='multipart/form-data'>
                    <div class='form-group'>
                        <label>Upload Photograph (Max 100 KB)</label>
                        <input type='file' name='uploadimg' id='uploadimg' accept='image'>
                        <br><small> Only Jpg and Png image upto 100KB. </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class='modal' id='aadharuploadmodal'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-body'>
                <h4> Upload Aadhar </h4>
                <hr>
                <form id='aadharuploadForm' enctype='multipart/form-data'>
                    <div class='form-group'>
                        <label>Upload Photograph (Max 100 KB)</label>
                        <input type='file' name='uploadaadhar' id='uploadaadhar' accept='image'>
                        <br><small> Only Jpg and Png image upto 100KB. </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once ('required/footer2.php'); ?>