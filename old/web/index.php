<?php require_once('header.php'); ?>
<?php require_once('../op_lib.php'); ?>
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
           
            <li>Admit Card</li>
          </ol>
        </div>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details" class="portfolio-details">
      <div class="container">
          <h2> Download Admit Card </h2>
           <form method='post'>
            <div class='row'>
                <div class="col-lg-3">
                 
                    Select Class
                    <select name='student_class' class="form-control" required>
                        <?php dropdown($class_list); ?>
                    </select>
                </div>
                
                 <div class="col-lg-3">
                    Select Section
                    <select name='student_section' class="form-control" required>
                        <?php dropdown($section_list); ?>
                    </select>
                </div>
                
                 <div class="col-lg-3">
                    Enter Roll No. 
                    <input name='student_roll' type='text' class="form-control" required > 
                </div>
                <div class="col-lg-3">
                    .<br>
                    <button class="input-group-text btn btn-warning btn-block" id="basic-addon2" onclick='submit()' name='search'>SEARCH</button>
                 
                </div>
            </div>
        </form>
            <?php if(isset($_POST['search'])){ 
            
            //print_r($_POST);
            extract($_POST);
            $student = get_all('student', '*', array('student_class' =>$student_class, 'student_section' => $student_section,  'student_roll' => $student_roll))['data'];
             
             //print_r($student); 
             $sid = $student[0]['id'];
             
             $dues = get_data('student_fee', $sid, 'current_dues', 'student_id')['data'];
             
             if ($dues >1000)
             {
             ?>
                 <div class="alert alert-danger mt-5" role="alert">
                  Your Dues Amount is too high <b> <?php echo $dues; ?> </b> kindly contact in school office.
                </div>
            <?php
             }
             else{
            ?>
            
            <table border='1' class='idcard mt-4' cellpadding='3' rules='all' width='100%' id='DivIdToPrint'>
	
	<tr height='85px'>
		
			<td align='center' class='header'> 
			<img src='../images/logo.png' height='120px' align='left'>
			<span style='font-size:36px;font-weight:800;font-family:calibri;text-transform:uppercase;color:maroon;'> <?php echo $full_name; ?> </span><br>
			<b>(Affiliated to CBSE, New Delhi upto 10+2) <br>
			<?php echo $inst_address1; ?>, <?php echo $inst_address2; ?> <br>
			Contact No.: <?php echo $inst_contact; ?><br>
			Email : <?php echo $inst_email; ?> | Website : <?php echo $inst_url; ?>
			</td>
		
	</tr>
	<tr height='30px' bgcolor='#d5d5d5'>
		<td><center><b> <?php echo $_POST['exam_name']; ?>HALL TICKET </b> </center></td>
	</tr>
	<tr>
	    <td style='text-align:left;padding:10px;vertical-align:top;'>
	
            
            <table class='table'>		
            <tr><td>Admission No.: </td><td><?php echo get_data("student",$sid,'student_admission')['data']; ?> </td>
                <td rowspan='6'>
            	<img src='upload/<?php echo get_data("student",$sid,'student_photo'); ?>' align='right' alt='Student Photo Here' style='border : solid 1px #ddd' width='150px' height='180px'/>
            	</td>
            </tr>
            
            <tr><td>Class & Section : </td><td><?php echo get_data("student",$sid,'student_class')['data']; ?> <?php echo get_data("student",$sid,'student_section')['data']; ?> </td></tr>
            
            <tr><td>Roll No. : </td><td><?php echo get_data("student",$sid,'student_roll')['data']; ?> </td></tr>
            
            <tr><td>Name : </td><td><?php echo get_data("student",$sid,'student_name')['data']; ?> </td></tr>
            
            <tr><td>Father's Name :</td><td> <?php echo get_data("student",$sid,'student_father')['data']; ?> </td></tr>
            
           <tr><td> Address : </td><td><?php echo get_data("student",$sid,'student_address1')['data']; ?> </td></tr>

                <tr class='bg-dark text-light text-center'>
                    <th colspan='3'> Exam Time Table for <?php echo $student_class; ?> </th>
                </tr>
                <tr>
                    <th> Date </th>
                    <th> Subject </th>
                    <th> Timing </th>
                </tr>
          
            <?php $routine = get_all('admit_card', '*', array('student_class'=> $student_class))['data'];
            
            foreach($routine as $row)
            {
                echo "<tr>";
                echo "<td>" . date('d-M-Y', strtotime($row['exam_date'])) ."</td>";
                echo "<td>" .$row['subject'] ."</td>";
                echo "<td>" .$row['timing'] ."</td>";
                echo "</tr>";
            }
	        ?>
	          </table>
	   
		</td>
	</tr>
	<tr>
		<td valign='bottom'>
		   
			 <div style='float:right;bottom:10px;'>  
			 <img src ='../images/sign.jpg' align='right' height='65px'> <br>
			 Signature of Principal 
			 </div>
		</td>
	</tr>
	<tr>
	    <td>
	        <b> Instructions :</b>
	        <ul>
	        <li>
	            Candidates should note that an authenticated Admit Card is an important document without which the candidate will not be permitted to appear for further selection
	        </li>
	        <li>Cell phones, calculators, watch calculators, alarm clocks, digital watches with built in calculators/ memory or any electronic or smart devices are not be allowed in the exam hall.</li>
	        <li>
	            Candidates will not be allowed to leave the test hall till the test is completed. After submission of the test, candidate will not be allowed to re-enter the test hall. 
	        </li>
	        </ul>
	    </td>
	</tr>
</table>
	<center><button class='btn btn-dark btn-sm text-light mt-4' onclick='window.print();'> PRINT </button>
	</center>
        <?php } } ?> 
          </div>

        
        </div>

      </div>
    </section><!-- End Portfolio Details Section -->

  </main><!-- End #main -->

<?php require_once('footer.php'); ?>
