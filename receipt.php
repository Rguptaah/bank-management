<?php require_once ('required/op_config.php'); ?>
<?php require_once ('required/op_lib.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Receipt</title>
</head>
<style>
  #receipt_template {
    width: 96%;
    margin: 0 auto;
    height: 100%;
    padding: 0px 10px;
    border: 2px solid grey;
  }

  h1,
  h3,
  .subheading p {
    text-align: center;
  }

  .receipt_body {
    display: flex;
    justify-content: space-between;
  }

  table {
    width: 100%;
    border: 1px solid #000;
  }

  #print_btn {
    width: 90%;
    margin: 10px 0px;
    text-align: center;
  }

  button {
    width: 200px;
    color: white;
    background-color: black;
    padding: 10px;
    border: 1px solid black;
    cursor: pointer;
  }

  @media print {
    .btn {
      display: none !important;
    }
  }
</style>

<body>
  <?php
  if (isset($_GET['link']) && $_GET['link'] != '') {
    $data = decode($_GET['link']);
    $id = $data['id'];
  }
  // echo $users_id;
  
  if ($id != '') {
    $res = get_data('receipt_data', $id);
    if ($res['count'] > 0 and $res['status'] == 'success') {
      extract($res['data']);
      // print_r($res['data']);
      // echo $member_id;
      $member = get_data('members', $member_id);
      if ($member['count'] > 0 && $member['status'] == 'success') {
        extract($member['data']);
        // print_r($member);
      }
    }
  }
  ?>
  <section id="receipt_template">
    <div class="heading">
      <h1><?= $full_name; ?></h1>
    </div>
    <div class="subheading">
      <p>
        <?= $inst_address1; ?>
      </p>
    </div>
    <h3>Renewal Receipt</h3>
    <div class="receipt_body">
      <div id="left">
        <p><strong>Branch Name & Code:</strong> <?= $branch_name ?></p>
        <p><strong>Received with thanks from:</strong> <?= $name; ?></p>
        <p>
          <strong>Address:</strong> <?= $permanent_address; ?>
        </p>
        <div class="receipt_body">
          <div class="left">
            <p><strong>Application No.:</strong> <?= $form_no ?></p>
            <p><strong>Plan Code:</strong> <?= $plan_detail ?></p>
          </div>
          <div class="right">
            <p><strong>Policy No.:</strong> <?= $policy_no; ?></p>
            <p>
              <strong>Term:</strong> <?= $term; ?>
              <span class="mode"><strong>Mode:</strong> <?= $mode; ?></span>
            </p>
          </div>
        </div>
        <h4>Total Amount: <span class="amount"><?= $amount; ?></span></h4>
        <p class="amount_in_words">
          <strong><?= strtoupper(numberToIndianRupees((int) $amount)); ?> ONLY</strong>
        </p>

        <!-- Employee Area  -->
        <div class="receipt_body">
          <div class="left">
            <p><strong>Employee Code:</strong> <?= $employee_code ?></p>
            <p><strong>Installments Paid:</strong> <?= $installment_paid; ?></p>
            <p><strong>Next Due Installment:</strong> <?= $next_installment_due; ?></p>
          </div>
          <div class="right">
            <p><strong>Employee Name:</strong> <?= $employee_name; ?></p>
            <p><strong>Total Deposits Till Date:</strong> <?= $total_deposits; ?></p>
            <p><strong>Next Due Installment Date:</strong> <?= $next_deposit_due_date; ?></p>
          </div>
        </div>
      </div>
      <div id="right">
        <p><strong>Maturity Date:</strong> <?= $maturity_date; ?></p>
        <p>D.O.C.: <?= $doc ?></p>
        <p>Deposit Date: <?= $entry_date; ?></p>
        <p>Previous Bal: <?= $previous_balance; ?></p>

        <table cellpadding="10px" cellspacing="0px" border="1">
          <tbody>
            <tr>
              <th>Particulars</th>
              <th>Amount</th>
            </tr>
            <tr>
              <td>Investment</td>
              <td align="right"><?= $amount; ?></td>
            </tr>
            <tr>
              <td>Late Charge</td>
              <td align="right"><?= $late_charge; ?></td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td>Total Amount</td>
              <td align="right"><?= (int) $amount + (int) $late_charge; ?></td>
            </tr>
          </tfoot>
        </table>

        <p><strong>Payment Mode:</strong> <?= $payment_mode; ?></p>
      </div>
    </div>
  </section>
  <div id="print_btn">
    <button class="btn btn-warning">Print Receipt</button>
  </div>

  <script>
    let btns = document.getElementsByClassName("btn");
    for (let i = 0; i < btns.length; i++) {
      btns[i].addEventListener('click', function () {
        window.print();
      });
    }

  </script>
</body>

</html>