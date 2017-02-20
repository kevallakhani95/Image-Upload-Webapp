<?php
	ini_set('mysql.connect_timeout', 300);
	ini_set('default_socket_timeout', 300);
	error_reporting(0);
?>

<html>
<head>
	<style>
		.center{
			text-align: center;   // To align images in center
		}
		
	</style>
</head>
	<body>
		<form method="post" enctype="multipart/form-data" class="form-horizontal">
     
			<table class="table table-bordered table-responsive">
				
				<tr>
				 <td><label class="control-label">User Name: </label></td>
					<td><input class="form-control" type="text" name="uname" placeholder="Enter User Name" /></td>
				</tr>

				<tr>
				 <td><label class="control-label">Pasword: </label></td>
					<td><input class="form-control" type="password" name="upassword" placeholder="Enter Password" /></td>
				</tr>

				<tr>
					<td colspan="2"><input  type="submit" value="Login" name="btnlogin" />
					</td>
				</tr>
				
				<tr>
					<td colspan="2"><input  type="submit" value="New User? Enter details and press here." name="btnsignup" />
					</td>
				</tr>

			</table>
		</form>
	
		<?php

			session_start();										// Session start
			require 'dbonn.php';									// Establish connection to database

			if(isset($_SESSION['userSession']) != "")				// To keep user signed in if a new tab is openend
			{
				header("Location: timeline.php");					// Redirect the page to Timeline 
				exit;
			}
			

			if(isset($_POST['btnsignup']))
			{
				$name = strip_tags($_POST['uname']);
				$password = strip_tags($_POST['upassword']);

				$name = $conn->real_escape_string($name);			// Get username and password
 				$password = $conn->real_escape_string($password);

 				$sqlquery=$conn->query("select Username,Password from users where Username='$name'");		// Insert details into database
 				$row = $sqlquery->fetch_array();
 				$count = $sqlquery->num_rows;

 				if($count == 0)																				// Precaution against already signed up users
				{
					$qry = "insert into users (Username, Password) values ('$name', '$password')";
					$result = $conn->query($qry);
					$_SESSION['userSession'] = $name;
 					header("Location: timeline.php");
				}
				else 																						// Error Message
				{
					echo "<br/> User already signed up. Please try logging in.";
				}
			}

			if(isset($_POST['btnlogin']))																	// Login button activated
			{
				$name = strip_tags($_POST['uname']);
				$password = strip_tags($_POST['upassword']);

				$name = $conn->real_escape_string($name);
 				$password = $conn->real_escape_string($password);

 				$sqlquery=$conn->query("select Username,Password from users where Username='$name'");		// Validate user from users
 				$row = $sqlquery->fetch_array();															// Store result in an array
 				$count = $sqlquery->num_rows;																// Count the number of rows

 				if($password == $row['Password'] && $count==1)										// Validate password in database with entered field
 				{
 					$_SESSION['userSession'] = $name;												// To access the username in other files we pass it in session variable
 					header("Location: timeline.php");
 				}
 				else
 				{
 					echo "Invalid username or password";
 				}
			}
			
		?>
	
	
	</body>
</html>
