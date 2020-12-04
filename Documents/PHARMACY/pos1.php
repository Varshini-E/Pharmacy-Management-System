<!DOCTYPE html>
<html>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="form3.css">
<link rel="stylesheet" type="text/css" href="table2.css">
<title>
New Sales
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
	<h2> POINT OF SALE</h2>
	</div>
	</center>
	

	<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
		<center>
		
		<select id="cid" name="cid">
        <option value="0" selected="selected">*Select Customer ID (only once for a customer's sales)</option>
					
					
	<?php	
			
		include "config.php";
		$qry="SELECT c_id FROM customer";
		$result= $conn->query($qry);
		echo mysqli_error($conn);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				echo "<option>".$row["c_id"]."</option>";
			}
		}
	?>
		
    </select>
	&nbsp;&nbsp;
	<input type="submit" name="custadd" value="Add to Proceed.">
	</form>
	
		
	<?php
	
		session_start();
		
		$qry1="SELECT id from admin where a_username='$_SESSION[user]'";
		$result1=$conn->query($qry1);
		$row1=$result1->fetch_row();
		$eid=$row1[0];
		
			if(isset($_GET['sid'])) 
			{
				$sid=$_GET['sid'];
			}
			
			if(isset($_POST['cid']))
				$cid=$_POST['cid'];
			
		if(isset($_POST['custadd'])) {
			
				$qry2="INSERT INTO sales(c_id,e_id) VALUES ('$cid','$eid')"; 
				if(!($result2=$conn->query($qry2))) {
					echo "<p style='font-size:8; color:red;'>Invalid! Enter valid Customer ID to record Sales.</p>";
				}
		}
	?>
			
		<form method="post">
			<select id="med" name="med">
			<option value="0" selected="selected">Select Medicine</option>
					
					
	<?php	
		$qry3="SELECT med_name FROM meds";
		$result3 = $conn->query($qry3);
		echo mysqli_error($conn);
		if ($result3->num_rows > 0) {
			while($row4 = $result3->fetch_assoc()) {
				
				echo "<option>".$row4["med_name"]."</option>";
			}
		}
	?>
		
    </select>
	&nbsp;&nbsp;
	<input type="submit" name="search" value="Search">
	</form>
	
	<br><br><br>
	</center>
	

	<?php
	
		if(isset($_POST['search'])&&! empty($_POST['med'])) {
			
					$med=$_POST['med'];
					$qry4="SELECT * FROM meds where med_name='$med'";
					$result4=$conn->query($qry4); 
					$row4 = $result4 -> fetch_row();
				
			}
	?>
	
			<div class="one row" style="margin-right:160px;">
			<form method="post">
					<div class="column">
					
					<label for="medid">Medicine ID:</label>
					<input type="number" name="medid" value="<?php echo $row4[0]; ?>"readonly ><br><br>
					
					<label for="mdname">Medicine Name:</label>
					<input type="text" name="mdname" value="<?php echo $row4[1]; ?>" readonly><br><br>
					
					</div>
					<div class="column">
					
					<label for="mcat">Category:</label>
					<input type="text" name="mcat" value="<?php echo $row4[3]; ?>" readonly><br><br>
					
					<label for="mloc">Location:</label>
					<input type="text" name="mloc" value="<?php echo $row4[5]; ?>" readonly><br><br>
					
					</div>
					<div class="column">
					
					<label for="mqty">Quantity Available:</label>
					<input type="number" name="mqty" value="<?php echo $row4[2]; ?>" readonly><br><br>
					
					<label for="mprice">Price of One Unit:</label>
					<input type="number" name="mprice" value="<?php echo $row4[4]; ?>" readonly><br><br>
					
					</div>
					<label for="mcqty">Quantity Required:</label>
					<input type="number" name="mcqty">
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="add" value="Add Medicine">&nbsp;&nbsp;&nbsp;
	
		<?php
		
		if(isset($_POST['add'])) {
			
					$qry5="select sale_id from sales ORDER BY sale_id DESC LIMIT 1";
					$result5=$conn->query($qry5); 
					$row5=$result5->fetch_row();
					$sid=$row5[0];
					echo mysqli_error($conn);
			
					$mid=$_POST['medid'];
					$aqty=$_POST['mqty'];
					$qty=$_POST['mcqty'];
					
					if($qty>$aqty||$qty==0)
					{echo "QUANTITY INVALID!";}
					else {
					$price=$_POST['mprice']*$qty;
					$qry6="INSERT INTO sales_items(`sale_id`,`med_id`,`sale_qty`,`tot_price`) VALUES($sid,$mid,$qty,$price)";
					$result6 = mysqli_query($conn,$qry6);
					echo mysqli_error($conn);
					
					echo "<br><br> <center>";
					echo "<a class='button1 view-btn' href=pos2.php?sid=".$sid.">View Order</a>";
					echo "</center>";
					}
		}	
		?>

		
		</form>
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