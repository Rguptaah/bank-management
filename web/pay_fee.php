<?php require_once('header.php'); ?>
<?php require_once('../function.php'); ?>
<style>
@media print {
  body * {
    visibility: hidden;
  }
  #DivIdToPrint, #DivIdToPrint * {
    visibility: visible;
  }
  #DivIdToPrint {
    position: absolute;
    left: 0;
    top: 0;
  }
}
</style>
  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs bg-warning">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2><?php echo $full_name; ?> </h2>
          <ol>
           
            <li>Online Fee Payment </li>
          </ol>
        </div>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details" class="portfolio-details">
      <div class="container">
          
           <form method='post'>
            <div class='row bg-warning p-2 pb-4 m-1'>
                <div class="col-lg-4">
                 
                    <label> Search By  </label>
                    <select name='search_by' class="form-control" required>
                        <option value='student_admission'> Admission No.</option>
                        <option value='student_mobile'> Mobile No.</option>
                    </select>
                </div>
                
                <div class="col-lg-5">
                    <label>Enter Value</label>
                    <input name='search_text' type='text' class="form-control" required > 
                </div>
                <div class="col-lg-3">
                    .<br>
                    <button class="input-group-text btn btn-danger btn-block" id="basic-addon2" onclick='submit()' name='search'>SEARCH</button>
                 
                </div>
            </div>
        </form>
       
        <div class='row'>
            <div class="col-lg-12">
					<hr>
                    <!-- Advanced Tables -->
							<?php
							
							if (isset($_POST['search_text']))
							{
							    extract($_POST);
								$res = get_all('student','*',array($search_by => $search_text) , 'student_name');
							?>
             
                            <div class="table-responsive">
                                <table rules='all' border='1' width='100%' cellpadding='5'>
                                    <thead>
                                        <tr class='bg-secondary text-light'>
                                            <th>Adm No.</th>
											<th>Student Name</th>
											<th>Father Name</th>
											<th>Class </th>
											<th>Roll No.</th>
											<th>Student Type </th>
											<th>Mobile No </th>
											<th>Dues </th>
											<th align='right'>Operation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
									//print_r($res);
									if($res['count']>0)
									{
										foreach($res['data'] as $row)
										{
										$id =$row['id'];
										$student_admission =$row['student_admission'];
										$status = $row['status'];
										
										$mobile = $row['student_mobile'];
										
										$link =encode("student_id=$id&student_admission=$student_admission");
										echo"<tr class='odd gradeX'>";
										//echo"<td><a href='print_application.php?student_id=$stu_id' target='_blank'>".$row['student_name']."</a></td>";
										echo"<td>".$row['student_admission']."</td>";
										echo"<td>".$row['student_name']."</td>";
										echo"<td>".$row['student_father']."</td>";
									
										echo"<td>".$row['student_class']."-".$row['student_section']."</td>";
										echo"<td>".$row['student_roll']."</td>";
										echo"<td>".$row['student_type']."</td>";
										echo"<td>".mask($mobile,3)."</td>";
										echo"<td>".get_data('student_fee',$student_admission,'current_dues','student_admission')['data']."</td>";
										echo"<td width='185' align='right'>";
									    echo "<a href='pay_now.php?link=$link&action=pay' title='Pay Now ' class='btn btn-success btn-sm' name='Pay_fee'>Pay Now </a>";
										echo "</td></tr>";
										}
									}
							}
                                       ?>
                                    </tbody>
                                </table>
                            </div>
        
        </div>

      </div>
    </section><!-- End Portfolio Details Section -->

  </main><!-- End #main -->

<?php require_once('footer.php'); ?>
