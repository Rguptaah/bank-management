<?php require_once('header.php'); ?>
<?php require_once('../op_lib.php'); ?>
  <main id="main">

    <!-- ======= Breadcrumbs ======= -->
    <section id="breadcrumbs" class="breadcrumbs bg-warning">
      <div class="container">

        <div class="d-flex justify-content-between align-items-center">
          <h2>Holiday of The Year</h2>
        
        </div>

      </div>
    </section><!-- End Breadcrumbs -->

    <!-- ======= Portfolio Details Section ======= -->
    <section id="portfolio-details" class="portfolio-details">
      <div class="container">
          <table class='table table-stripped'>
              <tr>
                  <th><i class='bi  bi-calendar-check'></i></th>
                  <th>Holiday Name</th>
                  <th>Date</th>
              </tr>
              <?php
               $res = get_all('holiday');
               foreach($res['data'] as $row)
               {
                   echo "<tr>";
                   echo "<td><i class='bi  bi-calendar'></i></td>";
                   echo "<td>" . $row['holiday_name']. "</td>";
                   echo "<td>" . date('d-M-Y', strtotime($row['holiday_date'])). "</td>";
                   echo "</tr>";
               }
              ?>
           </table>
      </div>
    </section><!-- End Portfolio Details Section -->

  </main><!-- End #main -->
<?php require_once('footer.php'); ?>
