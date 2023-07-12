<?php
// We start our session
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

  // check if the right tables are already in place:
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
  $sql = "SELECT * FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? LIMIT 1";

  if ($stmt = $con->prepare($sql)) {
   $stmt->bindValue(1, $DATABASE_NAME);
   $stmt->bindValue(2, "suppliers");
	$stmt->execute();
	$result_row = $stmt->fetch();

   if ($result_row) {
      $_SESSION['suppliers_table_status'] = "Exists";
      
   } else {
      $_SESSION['suppliers_table_status'] = "Does not exist";
   }

   $stmt->bindValue(1, $DATABASE_NAME);
   $stmt->bindValue(2, "products");
	$stmt->execute();
	$result_row = $stmt->fetch();

   if ($result_row) {
      $_SESSION['products_table_status'] = "Exists";
   } else {
      $_SESSION['products_table_status'] = "Does not exist";
   }
  }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
		
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
			<h1>This project was made for CP476 by Dhwani Patel, Khushi Patel, Rahil Gandhi and Rohan Uddin.</h1>
			<hr/>
         <h3>Database Table(s) Status:</h3>
         <ul class="status">
            <li>Supplier Table: <?=$_SESSION['suppliers_table_status']?></li>
            <li>Product Table: <?=$_SESSION['products_table_status']?></li>
         </ul>
         <button class="init" onclick="location.href = 'initialize_tables.php';">Initialize Tables</button>
         <h2>Here are your options:</h2>

        <!-- sets up navigation -->
        <form class="options">
            <input type="button" onclick="location.href = 'search.php';" value="View Product/Supplier Information"><br>
            <br>
            <input type="button" onclick="location.href = 'update.php';" value="Update Product/Supplier Information"><br>
            <br>
            <input type="button" onclick="location.href = 'delete.php';" value="Delete Product/Supplier Information"><br>
            <br>
            <input type="button" onclick="location.href = 'calc_inventories.php';" value="Get Inventory Status"><br>
            <br>
            <input type="button" onclick="location.href = 'logout.php';" value="Logout">
        </form>
		</div>
	</body>
</html>