<?php

include 'includes/book-utilities.inc.php';

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$customers = readCustomers(__DIR__ . '/data/customers.txt');
$selectedCustomerId = filter_input(INPUT_GET, 'customer', FILTER_VALIDATE_INT);
$selectedCustomer = null;
$orders = [];

if ($selectedCustomerId !== null && $selectedCustomerId !== false) {
    foreach ($customers as $customer) {
        if ($customer['id'] === $selectedCustomerId) {
            $selectedCustomer = $customer;
            break;
        }
    }

    if ($selectedCustomer !== null) {
        $orders = readOrders($selectedCustomer, __DIR__ . '/data/orders.txt');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>dc229853 Zihan Zhang</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/material.min.css">
    <link rel="stylesheet" href="css/styles.css">
    
    <script src="https://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="js/material.min.js"></script>
    <script src="js/jquery.sparkline.2.1.2.js"></script>
    
  
</head>

<body>
    
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer
            mdl-layout--fixed-header">
            
    <?php include 'includes/header.inc.php'; ?>
    <?php include 'includes/left-nav.inc.php'; ?>
    
    <main class="mdl-layout__content mdl-color--grey-50">
        <section class="page-content">

            <div class="mdl-grid">

              <!-- mdl-cell + mdl-card -->
              <div class="mdl-cell mdl-cell--7-col card-lesson mdl-card  mdl-shadow--2dp">
                <div class="mdl-card__title mdl-color--orange">
                  <h2 class="mdl-card__title-text">Customers</h2>
                </div>
                <div class="mdl-card__supporting-text">
                    <table class="mdl-data-table  mdl-shadow--2dp">
                      <thead>
                        <tr>
                          <th class="mdl-data-table__cell--non-numeric">Name</th>
                          <th class="mdl-data-table__cell--non-numeric">University</th>
                          <th class="mdl-data-table__cell--non-numeric">City</th>
                          <th>Sales</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($customers as $customer) : ?>
                        <tr<?php echo $selectedCustomerId === $customer['id'] ? ' class="selected-customer-row"' : ''; ?>>
                          <td class="mdl-data-table__cell--non-numeric">
                            <a class="customer-link" href="<?php echo e(basename($_SERVER['PHP_SELF'])); ?>?customer=<?php echo e($customer['id']); ?>">
                              <?php echo e($customer['full_name']); ?>
                            </a>
                          </td>
                          <td class="mdl-data-table__cell--non-numeric"><?php echo e($customer['university']); ?></td>
                          <td class="mdl-data-table__cell--non-numeric"><?php echo e($customer['city']); ?></td>
                          <td><span class="inlinesparkline"><?php echo e($customer['sales_raw']); ?></span></td>
                        </tr>
                      <?php endforeach; ?>
                      </tbody>
                    </table>
                </div>
              </div>  <!-- / mdl-cell + mdl-card -->
              
              
            <div class="mdl-grid mdl-cell--5-col">
    

       
                  <!-- mdl-cell + mdl-card -->
                  <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card  mdl-shadow--2dp">
                    <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                      <h2 class="mdl-card__title-text">Customer Details</h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <?php if ($selectedCustomer !== null) : ?>
                          <h4><?php echo e($selectedCustomer['full_name']); ?></h4>
                          <div class="customer-details">
                            <p><strong>Email:</strong> <?php echo e($selectedCustomer['email']); ?></p>
                            <p><strong>University:</strong> <?php echo e($selectedCustomer['university']); ?></p>
                            <p><strong>Address:</strong> <?php echo e($selectedCustomer['address']); ?></p>
                            <p><strong>City:</strong> <?php echo e($selectedCustomer['city']); ?></p>
                            <?php if ($selectedCustomer['state'] !== '') : ?>
                              <p><strong>State:</strong> <?php echo e($selectedCustomer['state']); ?></p>
                            <?php endif; ?>
                            <p><strong>Country:</strong> <?php echo e($selectedCustomer['country']); ?></p>
                            <?php if ($selectedCustomer['postal'] !== '') : ?>
                              <p><strong>Postal Code:</strong> <?php echo e($selectedCustomer['postal']); ?></p>
                            <?php endif; ?>
                            <p><strong>Phone:</strong> <?php echo e($selectedCustomer['phone']); ?></p>
                          </div>
                        <?php elseif ($selectedCustomerId !== null && $selectedCustomerId !== false) : ?>
                          <h4>Requested customer not found</h4>
                          <p class="empty-state">The selected customer id does not match any record in the data file.</p>
                        <?php else : ?>
                          <h4>Customer Name here</h4>
                          <p class="empty-state">Select a customer name from the table to view the full record.</p>
                        <?php endif; ?>
                    </div>    
                  </div>  <!-- / mdl-cell + mdl-card -->   

                  <!-- mdl-cell + mdl-card -->
                  <div class="mdl-cell mdl-cell--12-col card-lesson mdl-card  mdl-shadow--2dp">
                    <div class="mdl-card__title mdl-color--deep-purple mdl-color-text--white">
                      <h2 class="mdl-card__title-text">Order Details</h2>
                    </div>
                    <div class="mdl-card__supporting-text">       
                               
                                                                      

                               <table class="mdl-data-table  mdl-shadow--2dp">
                              <thead>
                                <tr>
                                  <th class="mdl-data-table__cell--non-numeric">Cover</th>
                                  <th class="mdl-data-table__cell--non-numeric">ISBN</th>
                                  <th class="mdl-data-table__cell--non-numeric">Title</th>
                                </tr>
                              </thead>
                              <tbody>
                              <?php if ($selectedCustomer !== null && count($orders) > 0) : ?>
                                <?php foreach ($orders as $order) : ?>
                                  <tr>
                                    <td class="mdl-data-table__cell--non-numeric">
                                      <img class="book-cover" src="<?php echo e($order['cover']); ?>" alt="<?php echo e($order['title']); ?>">
                                    </td>
                                    <td class="mdl-data-table__cell--non-numeric"><?php echo e($order['isbn']); ?></td>
                                    <td class="mdl-data-table__cell--non-numeric"><?php echo e($order['title']); ?></td>
                                  </tr>
                                <?php endforeach; ?>
                              <?php elseif ($selectedCustomer !== null) : ?>
                                <tr>
                                  <td colspan="3" class="mdl-data-table__cell--non-numeric empty-state">No order information found for this customer.</td>
                                </tr>
                              <?php else : ?>
                                <tr>
                                  <td colspan="3" class="mdl-data-table__cell--non-numeric empty-state">Select a customer to view order details.</td>
                                </tr>
                              <?php endif; ?>
                              </tbody>
                            </table>
       

                        </div>    
                   </div>  <!-- / mdl-cell + mdl-card -->             


               </div>   
           
           
            </div>  <!-- / mdl-grid -->    

        </section>
        <footer class="page-footer">
            <p>CISC3003 Web Programming: dc229853 Zihan Zhang 2026</p>
        </footer>
    </main>    
</div>    <!-- / mdl-layout --> 

<script>
  $(function () {
    $('.inlinesparkline').sparkline('html', {
      type: 'bar',
      barColor: '#5c6bc0',
      negBarColor: '#ef5350',
      height: '30px',
      barWidth: 6,
      barSpacing: 2
    });
  });
</script>

</body>
</html>
