<!DOCTYPE html>
<html>

<link rel="stylesheet" type="text/css" href="table1.css">
<link rel="stylesheet" type="text/css" href="nav2.css">
<link rel="stylesheet" type="text/css" href="form4.css">

<style>
body {font-family:Arial;}
</style>

<body>

	<div class="sidenav">
			<h2 style="font-family:Arial; color:white; text-align:center;"> PHARMACIA </h2>
			<a href="pharmmainpage.php">Dashboard</a>
			
			<a href="pharm-inventory.php">View Inventory</a>
			<a href="pharm-pos1.php">Add New Sale</a>
			<button class="dropdown-btn">Customers
			<i class="down"></i>
			</button>
			<div class="dropdown-container">
				<a href="pharm-customer.php">Add New Customer</a>
				<a href="pharm-customer-view.php">View Customers</a>
			</div>
	</div>

	<?php
	include "config.php";
	session_start();
	
	$sql="SELECT E_FNAME from EMPLOYEE WHERE E_ID='$_SESSION[user]'";
	$result=$conn->query($sql);
	$row=$result->fetch_row();
	
	$ename=$row[0];
	
	?>

	<div class="topnav">
		<a href="logout1.php">Logout(signed in as <?php echo $ename; ?>)</a>
	</div>
	
	<center>
	<div class="head">
	<h2> ADD CUSTOMER DETAILS</h2>
	</div>
	</center>
	
	<br><br><br><br><br><br><br><br>
	
	<div class="one">
		<div class="row">
			<form action="<?=$_SERVER['PHP_SELF']?>" method="post">
				<div class="column">
					<p>
						<label for="cid">Customer ID:</label><br>
						<input type="number" name="cid">
					</p>
					<p>
						<label for="cfname">First Name:</label><br>
						<input type="text" name="cfname">
					</p>
					<p>
						<label for="clname">Last Name:</label><br>
						<input type="text" name="clname">
					</p>
					<p>
						<label for="age">Age:</label><br>
						<input type="number" name="age">
					</p>
					
					<p>
						<label for="sex">Sex: </label><br>
						<select id="sex" name="sex">
								<option value="selected">Select</option>
								<option>Female</option>
								<option>Male</option>
								<option>Others</option>
						</select>
					</p>
					
				</div>
				<div class="column">
					
					<p>
						<label for="phno">Phone Number: </label><br>
						<input type="number" name="phno">
					</p>
					<p>
						<label for="emid">Email ID:</label><br>
						<input type="text" name="emid">
					</p>
				</div>
				
			<input type="submit" name="add" value="Add Customer">
			</form>
		<br>
		
		
		<?php
			
			include "config.php";
			
			if(isset($_POST['add']))
			{
			$id = mysqli_real_escape_string($conn, $_REQUEST['cid']);
			$fname = mysqli_real_escape_string($conn, $_REQUEST['cfname']);
			$lname = mysqli_real_escape_string($conn, $_REQUEST['clname']);
			$age = mysqli_real_escape_string($conn, $_REQUEST['age']);
			$sex = mysqli_real_escape_string($conn, $_REQUEST['sex']);
			$phno = mysqli_real_escape_string($conn, $_REQUEST['phno']);
			$mail = mysqli_real_escape_string($conn, $_REQUEST['emid']);

			 
			$sql = "INSERT INTO customer VALUES ($id, '$fname', '$lname',$age,'$sex',$phno, '$mail')";
			if(mysqli_query($conn, $sql)){
				echo "<p style='font-size:8;'>Customer successfully added!</p>";
			} else{
				echo "<p style='font-size:8; color:red;'>Error! Check details.</p>";
			}
			}
			 
			$conn->close();
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