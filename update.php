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

  if(isset($_POST['pull_record'])){ //check if form was submitted
    $_SESSION["supplier_id"] = clean_input($_POST['supplier_id']); //get input text
    $_SESSION["product_id"] = clean_input($_POST['product_id']); //get input text

    // validate input
    if (empty($_SESSION["supplier_id"]) || empty($_SESSION["product_id"])) {
      $output = "Please enter a valid supplier ID and product ID";
    } else {
      $output = "Valid input";
    }

    $query = "SELECT product_id, product_name, quantity, product_price, product_status, supplier_name FROM suppliers INNER JOIN products ON suppliers.supplier_id = products.supplier_id WHERE (products.supplier_id = ? AND products.product_id = ?)";
    $result= $con->prepare($query);
    $result->bindParam(1, $_SESSION["supplier_id"], PDO::PARAM_STR);
    $result->bindParam(2, $_SESSION["product_id"], PDO::PARAM_STR);
    $result->execute();
    $rows=$result->fetch(PDO::FETCH_ASSOC);

    if ($rows) {
      $output = "Record found ";
      $_SESSION["product_name"] = $rows['product_name'];
      $_SESSION["quantity"] = $rows['quantity'];
      $_SESSION["product_price"] = $rows['product_price'];
      $_SESSION["product_status"] = $rows['product_status'];
    } else {
      $output = "Record not found";
    }
  }

  

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
      <div class="update">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
          <div class="box">
            <!-- drop down for productid -->
            <label for="html">Product ID</label>
            <select name="product_id">
              <?php
                $query = "SELECT product_id FROM products";
                $result = $con->query($query);
                while($rows=$result->fetch(PDO::FETCH_ASSOC)) {
                  echo "<option value=" . $rows['product_id'] . ">" . $rows['product_id'] . "</option>";
                }
              ?>
            </select>
          </div>
          <!-- datalist of Supplier name based on selected Product ID -->
          <div class="box">
            <label for="html">Supplier ID</label>
            <select name="supplier_id">
              <?php
                $query = "SELECT supplier_id FROM suppliers";
                $result = $con->query($query);
                while($rows=$result->fetch(PDO::FETCH_ASSOC)) {
                  echo "<option value=" . $rows['supplier_id'] . ">" . $rows['supplier_id'] . "</option>";
                }
              ?>
            </select>
          </div>
          <input type="submit", name="pull_record", value="Pull Record">
          <br/>
          <?php echo isset($_SESSION['product_id']) ? "Product ID: ".$_SESSION['product_id'] : "Product ID: "; ?>
          <?php echo isset($_SESSION['supplier_id']) ? "Supplier ID: ".$_SESSION['supplier_id'] : "Supplier ID: "; ?>
        </form>
        <br/>
        <!-- Actual place to update the data -->
        <form action="update_data.php" method="post">
          <div class="box">
            <label for="html">Product Name</label>
            <input type="text" name="product_name" value=<?php echo isset($_SESSION['product_name']) ? $_SESSION['product_name'] : "Name";?>>
          </div>
          <div class="box">
            <label for="html">Quantity</label>
            <input type="text" name="quantity" value=<?php echo isset($_SESSION['quantity']) ? $_SESSION['quantity'] : "Quantity";?>>
          </div>
          <div class="box">
            <label for="html">Price</label>
            <input type="text" name="price" value=<?php echo isset($_SESSION['product_price']) ? $_SESSION['product_price'] : "Price";?>>
          </div>
          <div class="box">
            <label for="html">Status</label>
            <input type="text" name="status" value=<?php echo isset($_SESSION['product_status']) ? $_SESSION['product_status'] : "Status";?>>
          </div>
          <br>
          <?php echo isset($_SESSION['update_status']) ? $_SESSION['update_status'] : ""; ?>
          <input type="submit", value="Update Record">     
      </div>

      
      <br/>
      <button onclick="location.href = 'home.php';">Home</button>       
    </div>
  </body>
</html> 