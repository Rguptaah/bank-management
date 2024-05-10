<?php require_once ('required/header.php'); ?>
<?php require_once ('required/menu.php'); ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> Manage Members <span class="badge badge-success badge-sm p-2">NEW</span></h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="breadcrumb-item active">Members</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Basic Forms -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Manage Members </h3>
                <div class="box-tools pull-right">
                    <a class='fa fa-plus btn btn-success btn-sm' title='New Member' href='add_member'> </a>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body">
                <div class='row'>
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Reg. No.</th>
                                                        <th>Name </th>
                                                        <th>Mobile</th>
                                                        <th>Email </th>
                                                        <th>Aadhar No</th>
                                                        <th>Status</th>
                                                        <th>Operation </th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    $i = 1;
                                                    $res = get_all('members');
                                                    if ($res['count'] > 0) {
                                                        foreach ($res['data'] as $row) {
                                                            $id = $row['id'];
                                                            echo "<tr>";
                                                            echo "<td>" . $row['reg_no'] . "</td>";
                                                            echo "<td>" . $row['name'] . "</td>";
                                                            echo "<td>" . $row['phone'] . "</td>";
                                                            echo "<td>" . $row['email'] . "</td>";
                                                            echo "<td>" . $row['aadhar_no'] . "</td>";
                                                            echo "<td>" . $row['status'] . "</td>";
                                                            ?>
                                                            <td>
                                                                <a href='add_member.php?link=<?php echo encode('id=' . $id); ?>'
                                                                    class='fa fa-edit btn btn-info btn-xs'></a>
                                                                <span class='delete_btn btn btn-danger btn-sm'
                                                                    data-table='members' data-id='<?php echo $id; ?>'
                                                                    data-pkey='id'><i class='fa fa-trash'></i></span>
                                                            </td>
                                                            <?php
                                                            $i++;
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</section>
</div>
<?php require_once ('required/footer2.php'); ?>