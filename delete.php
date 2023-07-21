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

  $query = "SELECT * FROM suppliers";
  $result = $con->query($query);

  $query2 = "SELECT * FROM products";
  $result2 = $con->query($query2);

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
			<h1>Deleting Elements from Database</h1>
      <p><?php echo $output; ?></p>
      <br/>
			<hr/>
      <div class="table_container">
        <div class="table2">
          <h3>Products table:</h3>
          <table border="1" cellspacing="2" cellpadding="2">
            <tr>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Product Description</th>
              <th>Product Price</th>
              <th>Quantity</th>
              <th>Product Status</th>
              <th>Supplier ID</th>
            </tr>
            <?php while($rows=$result2->fetch(PDO::FETCH_ASSOC)) { ?>
              <tr>
                <td><?php echo $rows['product_id']; ?></td>
                <td><?php echo $rows['product_name']; ?></td>
                <td><?php echo $rows['product_desc']; ?></td>
                <td><?php echo $rows['product_price']; ?></td>
                <td><?php echo $rows['quantity']; ?></td>
                <td><?php echo $rows['product_status']; ?></td>
                <td><?php echo $rows['supplier_id']; ?></td>
              </tr>
            <?php } ?>
          </table>
          <br/>
          <p>Number of rows (Products): <?php echo $result2->rowCount(); ?></p>
        </div>
      </div>

      <!-- user enters supplier id / product id to delete that element -->
      <div class="delete">
        <form action="delete_data.php" method="post">
          <label for="html">Product ID</label>
          <input type="text" id="product_id" name="product_id">
          <br>
          <label for="html">Supplier ID</label>
          <input type="text" id="supplier_id" name="supplier_id">
          <br>
          <input type="submit", value="Delete Item">
        </form>      
      </div>
      <br/>
      <button onclick="location.href = 'home.php';">Home</button>       
    </div>
  </body>
</html> 

