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

  // get cleaned POST data
  $product_id = clean_input($_SESSION['product_id']);
  $supplier_id = clean_input($_SESSION['supplier_id']);

  // unset session variables
  unset($_SESSION['product_id']);
  unset($_SESSION['supplier_id']);
  unset($_SESSION['product_name']);
  unset($_SESSION['product_price']);
  unset($_SESSION['quantity']);
  unset($_SESSION['product_status']);

  // data validation on POST data
  if (empty($_POST['product_name']) && empty($_POST['quantity']) && empty($_POST['price']) && empty($_POST['status'])) {
    $_SESSION['update_status'] = "No data entered";
    header('Location: update.php');
  } else {
    unset($_SESSION['update_status']);
  }

  // get cleaned POST data
  $update_name = clean_input($_POST['product_name']);
  $update_quantity = clean_input($_POST['quantity']);
  $update_price = clean_input($_POST['price']);
  $update_status = clean_input($_POST['status']);


  // query to update the database
  $query = "UPDATE products SET product_name = ?, quantity = ?, product_price = ?, product_status = ? WHERE (supplier_id = ? AND product_id = ?)";
  $result= $con->prepare($query);
  //bind the parameters
  $result->bindParam(1, $update_name, PDO::PARAM_STR);
  $result->bindParam(2, $update_quantity, PDO::PARAM_INT);
  $result->bindParam(3, $update_price, PDO::PARAM_STR);
  $result->bindParam(4, $update_status, PDO::PARAM_STR);
  $result->bindParam(5, $supplier_id, PDO::PARAM_STR);
  $result->bindParam(6, $product_id, PDO::PARAM_STR);
  //execute the prepared statement
  $result->execute();

  // use INNER JOIN Query to get all the data from both tables
  $query2 = "SELECT product_id, product_name, quantity, product_price, product_status, supplier_name FROM suppliers INNER JOIN products ON suppliers.supplier_id = products.supplier_id";
  $display = $con->query($query2);

  // function to clean POST data
  function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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
			<h1>Update Database</h1>
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
            <?php while($rows=$display->fetch(PDO::FETCH_ASSOC)) { ?>
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
          <p>Number of rows: <?php echo $display->rowCount(); ?></p>
        </div>
      </div>
      
      <br/>
      <button onclick="location.href = 'update.php';">Go Back</button>
      <button onclick="location.href = 'home.php';">Home</button>       
    </div>
  </body>
</html> 