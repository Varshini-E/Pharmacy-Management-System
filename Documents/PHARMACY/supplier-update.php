<?php
		include "config.php";
	
		if(isset($_GET['id']))
		{
			$id=$_GET['id'];
			$qry1="SELECT * FROM suppliers WHERE sup_id='$id'";
			$result = $conn->query($qry1);
			$row = $result -> fetch_row();
		}
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="form4.css">
<title>
Suppliers
</title>
</head>

<body>

		<div class="sidenav">
			<h2 style="font-family:Arial; color:white; text-align:center;"> PHARMACIA </h2>
			<a href="adminmainpage.php">Dashboard</a>
			<button class="dropdown-btn">Inventory
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="inventory-add.php">Add New Medicine</a>
				<a href="inventory-view.php">Manage Inventory</a>
			</div>
			<button class="dropdown-btn">Suppliers
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="supplier-add.php">Add New Supplier</a>
				<a href="supplier-view.php">Manage Suppliers</a>
			</div>
			<button class="dropdown-btn">Stock Purchase
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="purchase-add.php">Add New Purchase</a>
				<a href="purchase-view.php">Manage Purchases</a>
			</div>
			<button class="dropdown-btn">Employees
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="employee-add.php">Add New Employee</a>
				<a href="employee-view.php">Manage Employees</a>
			</div>		
			<button class="dropdown-btn">Customers
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="customer-add.php">Add New Customer</a>
				<a href="customer-view.php">Manage Customers</a>
			</div>
			<a href="sales-view.php">View Sales Invoice Details</a>
			<a href="salesitems-view.php">View Sold Products Details</a>
			<a href="pos1.php">Add New Sale</a>			
			<button class="dropdown-btn">Reports
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="stockreport.php">Medicines - Low Stock</a>
				<a href="expiryreport.php">Medicines - Soon to Expire</a>
				<a href="salesreport.php">Transactions Reports</a>		
			</div>
	</div>

	<div class="topnav">
		<a href="logout.php">Logout</a>
	</div>
	
	<center>
	<div class="head">
	<h2> UPDATE SUPPLIER DETAILS</h2>
	</div>
	</center>


	<div class="one">
		<div class="row">
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
				<div class="column">
					<p>
						<label for="sid">Supplier ID:</label><br>
						<input type="number" name="sid" value="<?php echo $row[0]; ?>" readonly>
					</p>
					<p>
						<label for="sname">Supplier Company Name:</label><br>
						<input type="text" name="sname" value="<?php echo $row[1]; ?>">
					</p>
					<p>
						<label for="sadd">Address:</label><br>
						<input type="text" name="sadd" value="<?php echo $row[2]; ?>">
					</p>
					
					
				</div>
				<div class="column">
					<p>
						<label for="sphno">Phone Number:</label><br>
						<input type="number" name="sphno" value="<?php echo $row[3]; ?>">
					</p>
					
					<p>
						<label for="smail">Email Address </label><br>
						<input type="text" name="smail" value="<?php echo $row[4]; ?>">
					</p>
					
				</div>
				
			
			<input type="submit" name="update" value="Update">
			</form>
			
	<?php
		 if( isset($_POST['update']))
		 {
			$id = $_POST['sid'];
			$name = $_POST['sname'];
			$add = $_POST['sadd'];
			$phno = $_POST['sphno'];
			$mail = $_POST['smail'];
			 
		$sql="UPDATE suppliers SET sup_name='$name',sup_add='$add',sup_phno='$phno',sup_mail='$mail' where sup_id='$id'";
		if ($conn->query($sql))
		header("location:supplier-view.php");
		else
		echo "<p style='font-size:8; color:red;'>Error! Unable to update.</p>";
		}

	?>
		</div>
	</div>

</body>

<script>
		var dropdown = document.getElementsByClassName("dropdown-btn");
		var i;

			for (i = 0; i < dropdown.length; i++) {
			  dropdown[i].addEventListener("click", function() {
			  this.classList.toggle("active");
			  var dropdownContent = this.nextElementSibling;
			  if (dropdownContent.style.display === "block") {
			  dropdownContent.style.display = "none";
			  } else {
			  dropdownContent.style.display = "block";
			  }
			  });
			}		
</script>
	
</html>