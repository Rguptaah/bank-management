<?php require_once('header.php'); ?>
<?php require_once('../function.php'); 

  if(isset($_GET['link']))
    {
        $data = decode($_GET['link']);
        
        $student_id=$data['student_id'];
        $student = get_data('student', $student_id)['data'];
        $dues_month = duesmonthcount($student_id);
        //print_r($dues_month);
        extract($student);
        $total_dues = finaldues($student_id);
        $payment_month = implode(', ',duesmonthcount($student_id)['list']);
        $demand_id =sprintf("%04d", $student_id).date('ymdHi');
        $idata = array(
            'student_id' =>$student_id,
            'student_admission' =>$student_admission,
            'demand_id'=>$demand_id,
            'total_amount'=>$total_dues,
            'payment_month'=>$payment_month
        );
        
        $res = insert_data('online_payment',$idata);
    }

?>
  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs bg-warning">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Order No. #<?php echo $demand_id; ?> </h2>
          <ol>
           
            <li>Verify Information </li>
          </ol>
        </div>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details" class="portfolio-details">
      <div class="container">
           <div class='row'>
            <div class="col-lg-2"></div>
            <div class="col-lg-8 m-1">
        <table width='100%' border='0' rules='all' class='table table-striped'>
        <tr>
			<td> Order No. </td> <td colspan='2' ><b><?php echo $demand_id; ?></b></td>
			<td align='right'> Date & Time </td><td align='right'><?php echo $current_date_time; ?></td>
		</tr>
        <tr>
			<td> Name </td> <td colspan='4'><?php echo strtoupper($student['student_name']); ?></td>
		</tr>
		<tr>
			<td> Father's Name </td> <td colspan='4'><?php echo strtoupper($student['student_father']); ?></td>
			
		</tr>
		<tr>
			<td> Adm No.</td> <td colspan='2'><?php echo strtoupper($student['student_admission']); ?></td>
			<td> Mobile No. </td><td><?php echo strtoupper($student['student_mobile']); ?></td>
		</tr>
		<tr>
			<td> Class/Sec. </td> <td colspan='2'><?php echo strtoupper($student['student_class']); ?>-<?php echo strtoupper($student['student_section']); ?> <b> (<?php echo strtoupper($student['student_type']); ?>) </b></td>
			<td> Roll No. </td> <td><?php echo strtoupper($student['student_roll']); ?></td>
		</tr>
		<tr>
		    <td colspan='2' >Fee of Month(s) </td>
		    <td></td>
		    <td colspan='2' align='right'> <?php  echo add_space(implode(', ',$dues_month['list'])) ?> </td>
		</tr>	
				<?php
				$prev_dues =0;
			
				$prev_dues = intval(get_data('student_fee',$student_id,'current_dues','student_id')['data']);
				if($prev_dues!=0){?>
				<tr>
					<td  colspan='4'> Previous Dues </td>
					<td align='right' ><?php echo $prev_dues; ?></td>
				</tr>
				<?php } ?>
				
				<?php 
				$all_fee = nmonth_fee($student_id,$dues_month['list']); 
				//print_r($all_fee);
				foreach($all_fee as $key=>$value)
				{
					if($value<>0)
					{
						if($key<>'total')
						{
						echo "<tr><td colspan='4'>"
						.add_space($key) 
						."<b> @" .get_fee_by_name($student_id, $key) 
						." X ". $dues_month['count']
						."</b></td>
						<td align='right'>". intval($value)."</td></tr>";
						}
					}
				}
				
				?>
		<tr>
		    <td colspan='4' >Total Dues</td>
		    <td align='right'> <b><?php  echo $total_dues; ?></b> </td>
		</tr>
				</table>  
		    </div>
		</div>
    <form method="POST" action='../pay/pay.php?checkout=manual'>
        <div class='row'>
            <div class='col-lg-12'>
           <input type='hidden'  placeholder='Order No.' name='order_no' class ='form-control m-2' value='<?php echo $demand_id; ?>' readonly >
         
         <input type='hidden'  placeholder='Demand No.' name='pay_req_no' class ='form-control m-2' value='<?php echo $demand_id; ?>' readonly >
        <input type='hidden'  placeholder='Name of Student' name='student_name' class ='form-control m-2' readonly value='<?php echo $student_name; ?>'>
        <input type='hidden'  placeholder='Mobile No' name='student_mobile' class ='form-control m-2' value='<?php echo $student_mobile; ?>' readonly>
        
        <input type='hidden'  placeholder='Remarks If any ' name='fee_details' class ='form-control m-2' value='<?php echo $payment_month; ?>' readonly>
        <input type='hidden' name='amount' class ='form-control m-2' value='<?php echo $total_dues; ?>' readonly >
        
            </div>
        
        <div class="col-lg-2"></div>
        <div class="col-lg-3">
        
            <input type='text'  placeholder='Email Id ' name='student_email' class ='form-control m-2' value='<?php echo $student_email; ?>' required> 
        </div>
        <div class="col-lg-3">
            <input type='number'  placeholder='Enter Amount to Pay' name='amount' class ='form-control m-2' autofocus>
        </div>
        <div class="col-lg-2">
        
            <center><button class='btn btn-block btn-warning mt-1'>Continute to Pay	</button></center>
        </div>	                           
        </div>
    </form>
      </div>
    </section><!-- End Portfolio Details Section -->

  </main><!-- End #main -->

<?php require_once('footer.php'); ?>
