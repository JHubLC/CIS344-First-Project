<?php
  
  $document_root = $_SERVER['DOCUMENT_ROOT'];
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Daring Defenders Law Firm - Customer Orders</title>
  </head>
  <body>
    <h1>Daring Defenders Law Firm
    </h1>
    <h2>Customer Orders</h2> 
    <?php
    $orders= file("$document_root/orders.txt");

    $number_of_orders = count($orders);
    if ($number_of_orders == 0) {
      echo "<p><strong>No orders pending.<br />
            Please try again later.</strong></p>";
    }
 
    for ($i=0; $i<$number_of_orders; $i++) {
      echo $orders[$i]."<br />";
    }
    ?>
  </body>
</html>