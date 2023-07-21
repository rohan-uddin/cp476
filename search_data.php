<?php
  // We start our session
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
    $output = 'Database connection established.';
  } catch (PDOException $e) {
    $output = 'Unable to establish connection: ' . $e->getMessage();
    // exit('Failed to connect to MySQL: ' . $e->getMessage());
  }

  // data validation


  // different queries based on form data
  if (empty($_POST['product_id']) && empty($_POST['supplier_id'])) {
    $_SESSION['status'] = "Both fields cannot be empty";
    header('Location: search.php');
  } else if (empty($_POST['supplier_id']) && isset($_POST['product_id'])) {
    $query = "SELECT product_id, product_name, quantity, product_price, product_status, supplier_name FROM suppliers INNER JOIN products ON suppliers.supplier_id = products.supplier_id WHERE products.product_id = ?";
    $result= $con->prepare($query);
    //bind the parameters
    $result->bindParam(1, $_POST['product_id']);
    //execute the prepared statement
    $result->execute();

    unset ($_SESSION['status']);
  } else if (empty($_POST['product_id']) && isset($_POST['supplier_id'])) {
    $query = "SELECT product_id, product_name, quantity, product_price, product_status, supplier_name FROM suppliers INNER JOIN products ON suppliers.supplier_id = products.supplier_id WHERE products.supplier_id = ?";
    $result= $con->prepare($query);
    //bind the parameters
    $result->bindParam(1, $_POST['supplier_id']);
    //execute the prepared statement
    $result->execute();

    unset ($_SESSION['status']);
  } else if (isset($_POST['product_id']) && isset($_POST['supplier_id'])) {
    $query = "SELECT product_id, product_name, quantity, product_price, product_status, supplier_name FROM suppliers INNER JOIN products ON suppliers.supplier_id = products.supplier_id WHERE (products.supplier_id = ? AND products.product_id = ?)";
    $result= $con->prepare($query);
    //bind the parameters
    $result->bindParam(1, $_POST['supplier_id']);
    $result->bindParam(2, $_POST['product_id']);
    //execute the prepared statement
    $result->execute();

    unset ($_SESSION['status']);
  } else {
    $_SESSION['status'] = "Something went wrong";
    header('Location: search.php');
  }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">
  </head>
	<body class="loggedin">
		<header class="navtop">
      <h1>CP476 Project</h1>
			<nav>
				<p>Welcome back, <strong><?=$_SESSION['name']?></strong>!</p>
      </nav>
    </header>
		<div class="content">
			<h1>Search Database</h1>
      <p><?php echo $output; ?></p>
      <br/>
			<hr/>
      <h2>Displayed Data</h2>
      <div class="table_container">
        <div class="table1">
          <h3>Inventory table:</h3>
          <table border="1" cellspacing="2" cellpadding="2">
            <tr>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Status</th>
              <th>Supplier Name</th>
            </tr>
            <?php while($rows=$result->fetch(PDO::FETCH_ASSOC)) { ?>
              <tr>
                <td><?php echo $rows['product_id']; ?></td>
                <td><?php echo $rows['product_name']; ?></td>
                <td><?php echo $rows['quantity']; ?></td>
                <td><?php echo $rows['product_price']; ?></td>
                <td><?php echo $rows['product_status']; ?></td>
                <td><?php echo $rows['supplier_name']; ?></td>
              </tr>
            <?php } ?>
          </table>
          <!-- echo row count here -->
          <br/>
          <p>Number of rows: <?php echo $result->rowCount(); ?></p>
        </div>
      </div>
      
      <br/>
      <button onclick="location.href = 'search.php';">Go Back</button>
      <button onclick="location.href = 'home.php';">Home</button>       
    </div>
  </body>
</html> 