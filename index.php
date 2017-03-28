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
		.marginbottom{
			margin-bottom: 20px;  
		}
		
	</style>
</head>
	<body>
		<?php

		echo "<div class=center><h2><u>Welcome to Image Sharing Web Application!</u></h2> Please log in. If this is your first time then enter the username and password and click on Sign up.</div>"; 
		echo "<br><br><br><br><br>";
		?>
		<center>
			<form method="post" enctype="multipart/form-data" class="form-horizontal">
	     
				<table class="table table-bordered table-responsive">
					
					<tr>
					 <td>
					 	<label class="control-label">User Name: 
					 	</label>
					 </td>
					<td>
						<input class="form-control" type="text" name="uname" placeholder="Enter User Name" autofocus=""/>
					</td>
					</div>
					</tr>

					<tr>
					 <td><label class="control-label">Pasword: </label></td>
						<td><input class="form-control" type="password" name="upassword" placeholder="Enter Password" /></td>
					</tr>

					<tr>
						<td colspan="2">
							<div class=center>
								<input  type="submit" value="Login" name="btnlogin" />
							</div>
						</td>
					</tr>
					
					<tr>
						<td colspan="2">
							<div class=center>
								<input  type="submit" value="New User? Enter details and Sign Up" name="btnsignup" />
							</div>
						</td>
					</tr>

				</table>
			</form>
		</center>
		<?php

			session_start();										// Session start
			require 'dbonn.php';									// Establish connection to database
			$random_salt = 'random';

			/*if(isset($_SESSION['userSession']) != "")				// To keep user signed in if a new tab is openend
			{
				header("Location: timeline.php");					// Redirect the page to Timeline 
				exit;
			}
			*/

			if(isset($_POST['btnsignup']))
			{
				$name = strip_tags($_POST['uname']);
				$password = strip_tags($_POST['upassword']);

				$name = $conn->real_escape_string($name);			// Get username and password
 				$password = $conn->real_escape_string($password);

 				$hashed_password = crypt($random_salt ,$password);

 				$sqlquery=$conn->query("select Username,Password from users where Username='$name'");		// Insert details into database
 				$row = $sqlquery->fetch_array();
 				$count = $sqlquery->num_rows;

 				if($count == 0)																				// Precaution against already signed up users
				{
					if($name == "" || $password == "")
					{
						echo "<script type='text/javascript'> alert('Enter a valid username and password!');</script>";
					}
					else
					{	
						$qry = "insert into users (Username, Password) values ('$name', '$hashed_password')";
						$result = $conn->query($qry);
						$_SESSION['userSession'] = $name;
	 					header("Location: timeline.php");
	 				}
				}
				else 																						// Error Message
				{
					echo "<script type='text/javascript'> alert('User already signed up. Please try logging in.');</script>";
				}
			}

			if(isset($_POST['btnlogin']))																	// Login button activated
			{
				$name = strip_tags($_POST['uname']);
				$password = strip_tags($_POST['upassword']);

				$name = $conn->real_escape_string($name);					// SQL Injection Prevention
 				$password = $conn->real_escape_string($password);

 				$sqlquery=$conn->query("select Password from users where Username='$name'");		// Validate user from users
 				$row = $sqlquery->fetch_array();															// Store result in an array															

 				if(hash_equals($row[0], crypt($random_salt, $password)))										// Validate password in database with entered field
 				{
 					$_SESSION['userSession'] = $name;												// To access the username in other files we pass it in session variable
 					header("Location: timeline.php");
 				}
 				else
 				{
 					echo "<script type='text/javascript'> alert('Invalid username or password');</script>";
 				}
			}
			
		?>
	
	
	</body>
</html>
