<?php
  require('header.php');
?>

<?php
  require_once("file_exceptions.php");

  
  $theftqty = (int) $_POST['theftqty'];                                           
  $tresspassingqty = (int) $_POST['tresspassingqty'];                                             
  $loiteringqty = (int) $_POST['loiteringqty'];                                         
  $address = preg_replace('/\t|\R/',' ',$_POST['address']);                     
  $document_root = $_SERVER['DOCUMENT_ROOT'];                                   
  $date = date('H:i, jS F Y'); 
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Daring Defenders Law Firm - Order Results</title>
  </head>
  <body>
    <h1>Daring Defenders Law Firm</h1>
    <h2>Order Results</h2> 
    <?php
      echo "<p>Order processed at ".date('H:i, jS F Y')."</p>";
      echo "<p>Your order is as follows: </p>";

      $totalqty = 0;
      $totalamount = 0.00;

      define('THEFTPRICE', 1000);
      define('TRESSPASSPRICE', 150);
      define('LOITERPRICE', 45);

      $totalqty = $theftqty + $tresspassingqty + $loiteringqty;
      echo "<p>Items ordered: ".$totalqty."<br />";

      if ($totalqty == 0) {
        echo "You did not order anything on the previous page!<br />";
      } else {
        if ($theftqty > 0) {
          echo htmlspecialchars($theftqty).' theft<br />';
        }
        if ($tresspassingqty > 0) {
          echo htmlspecialchars($tresspassingqty).' tresspassing<br />';
        }
        if ($loiteringqty > 0) {
          echo htmlspecialchars($loiteringqty).' loitering<br />';
        }
      }


      $totalamount = $theftqty * THEFTPRICE
                   + $tresspassingqty * TRESSPASSPRICE
                   + $loiteringqty * LOITERPRICE;

      echo "Subtotal: $".number_format($totalamount,2)."<br />";

      $taxrate = 0.10;  // local sales tax is 10%
      $totalamount = $totalamount * (1 + $taxrate);
      echo "Total including tax: $".number_format($totalamount,2)."</p>";

      echo "<p>Address to ship to is ".htmlspecialchars($address)."</p>";

      $outputstring = $date."\t".$theftqty." theftqty \t".$tresspassingqty." tresspassingqty\t"
                      .$loiteringqty." loiteringqty\t\$".$totalamount
                      ."\t". $address."\n";

      
      try
      {
        if (!($fp = @fopen("$document_root/orders.txt", 'ab'))) {
            throw new fileOpenException();
        }
      
        if (!flock($fp, LOCK_EX)) {
           throw new fileLockException();
        }
      
        if (!fwrite($fp, $outputstring, strlen($outputstring))) {
           throw new fileWriteException();
        }

        flock($fp, LOCK_UN);
        fclose($fp);
        echo "<p>Order written.</p>";
      }
      catch (fileOpenException $foe)
      {
         echo "<p><strong>Orders file could not be opened.<br/>
               Please contact our webmaster for help.</strong></p>";
      }
      catch (Exception $e)
      {
         echo "<p><strong>Your order could not be processed at this time.<br/>
               Please try again later.</strong></p>";
      }
    ?>
  <?php
  require('footer.php');
?>