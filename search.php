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
      <div class="delete">
        <form action="search_data.php" method="post">
          <label for="html">Supplier ID</label>
          <input type="text" id="supplier_id" name="supplier_id">
          <br>

          <label for="html">Product ID</label>
          <input type="text" id="product_id" name="product_id">
          <br>
          <?php echo isset($_SESSION['status']) ? $_SESSION['status'] : ""; ?>
          <input type="submit", value="Search Item">
        </form>      
      </div>

      
      <br/>
      <button onclick="location.href = 'home.php';">Home</button>       
    </div>
  </body>
</html> 