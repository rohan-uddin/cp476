<?php
  // We need to use sessions, so you should always start sessions using the below code.
  session_start();
  // If the user is not logged in redirect to the login page...
  if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
  }
  //connect to database
  $DATABASE_HOST = 'localhost';
  $DATABASE_USER = 'root';
  $DATABASE_PASS = '';
  $DATABASE_NAME = 'cp476';

  // Try and connect using the info above.
  try {
    $con = new PDO("mysql:host=$DATABASE_HOST;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
  } catch (PDOException $e) {
    exit('Failed to connect to MySQL: ' . $e->getMessage());
  }

  // BELOW CODE NEEDS TO BE MODIFIED - 'made our own'
  /** Converts file into 2D indexed array. 
   ** Splits comma separated data into individual items. */
  function get_lines($fh) {
    while (!feof($fh)) {
        yield explode(', ', fgets($fh));
    }
  } 

  $product_file = fopen("ProductFile.txt", "r");
  $supplier_file = fopen("SupplierFile.txt", "r");

  // delete existing tables
  try {
    $sql = file_get_contents('del_db.sql');
    // echo $sql .'\n';
    $con->exec($sql);
  } catch (PDOException $e) {
      echo $sql . '\r\n'. $e->getMessage();
  }
  // Create tables (schema)
  try {
    $sql = file_get_contents("init_tables.sql");
    $con->exec($sql);

    echo "<script>console.log('Tables created successfully');</script>";
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }

  // INSERT DATA INTO TABLES - use prepared statements
  
  // Suppliers table
  $sql = $con->prepare("INSERT INTO suppliers (supplier_id, supplier_name, supplier_address, phone, email) VALUES (?, ?, ?, ?, ?)");
  foreach (get_lines($supplier_file) as $line) {
    if (count($line) > 1) {
      $sql->bindValue(1, $line[0]);
      $sql->bindValue(2, $line[1]);
      $sql->bindValue(3, $line[2]);
      $sql->bindValue(4, $line[3]);
      $sql->bindValue(5, $line[4]);
      $sql->execute();
    }
  }

  // Products table
  $sql = $con->prepare("INSERT INTO products (product_id, product_name, product_desc, product_price, quantity, product_status, supplier_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
  foreach (get_lines($product_file) as $line) {
    if (count($line) > 1) {
      echo "<script>console.log('" . json_encode($line) . "');</script>";
      $sql->bindValue(1, $line[0]);
      $sql->bindValue(2, $line[1]);
      $sql->bindValue(3, $line[2]);
      $sql->bindValue(4, $line[3]);
      $sql->bindValue(5, $line[4]);
      $sql->bindValue(6, $line[5]);
      $sql->bindValue(7, $line[6]);
      $sql->execute();
    }
  }
  // close files
  fclose($product_file);
  fclose($supplier_file);

  echo "<script>alert('Data added to tables!');window.location.href='home.php';</script>";
?>