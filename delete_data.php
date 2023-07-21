<?php
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

  // TODO: Data validation from the form
  // if (empty($_POST['supplier_id'])) {
  //   exit('Please provide the ID of the user you want to delete!');
  // }


  // delete supplier from database
  try {
    $sql = "DELETE FROM products WHERE product_id=? AND supplier_id=?";
    $stmt= $con->prepare($sql);
    
    //bind the parameters but clean them up first
    $p_id = clean_input($_POST['product_id']);
    $s_id = clean_input($_POST['supplier_id']);

    $stmt->bindParam(1, $p_id, PDO::PARAM_STR);
    $stmt->bindParam(2, $s_id, PDO::PARAM_STR);
    //execute the prepared statement
    $stmt->execute();
  } catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
  }
  

  // display the table queries
  $query = "SELECT * FROM suppliers";
  $result = $con->query($query);

  $query2 = "SELECT * FROM products";
  $result2 = $con->query($query2);


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
			<h1>Deleting Elements from Database</h1>
      <p><?php echo $output; ?></p>
      <br/>
			<hr/>
      <h2>Row deleted. Data now looks like:</h2>
      <div class="table_container">
        <div class="table1">
          <h3>Suppliers table:</h3>
          <table border="1" cellspacing="2" cellpadding="2">
            <tr>
              <th>Supplier ID</th>
              <th>Supplier Name</th>
              <th>Supplier Address</th>
              <th>Phone</th>
              <th>Email</th>
            </tr>
            <?php while($rows=$result->fetch(PDO::FETCH_ASSOC)) { ?>
              <tr>
                <td><?php echo $rows['supplier_id']; ?></td>
                <td><?php echo $rows['supplier_name']; ?></td>
                <td><?php echo $rows['supplier_address']; ?></td>
                <td><?php echo $rows['phone']; ?></td>
                <td><?php echo $rows['email']; ?></td>
              </tr>
            <?php } ?>
          </table>
          <!-- echo row count here -->
          <br/>
          <p>Number of rows (Suppliers): <?php echo $result->rowCount(); ?></p>
        </div>

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
      <button onclick="location.href = 'delete.php';">Go Back</button>
      <button onclick="location.href = 'home.php';">Home</button>
    </div>
  </body>
</html> 