<?php
	ini_set('mysql.connect_timeout', 300);
	ini_set('default_socket_timeout', 300);
	error_reporting(0);
?>

<html>
<head>
	<style>
		.center{
			text-align: center;
		}
		
	</style>
</head>
	<body>
		<form method="post" enctype="multipart/form-data" class="form-horizontal">
     
			<table class="table table-bordered table-responsive">
	 			
	 			<tr>
					<td colspan="2"><input  type="submit" value="Logout" name="btnlogout" />
					</td>
				</tr>

	 			<tr>
					<td colspan="2"><input  type="submit" value="My account" name="btnaccount" />
					</td>
				</tr>
				
				<tr>
				 <td><label class="control-label">Image: </label></td>
					<td><input class="input-group" type="file" name="propic" accept="image/*" /></td>
				</tr>
				
				<tr>
				 <td><label class="control-label">Caption: </label></td>
					<td><input class="form-control" type="text" name="caption" placeholder="Enter Caption" /></td>
				</tr>
				
				<tr>
					<td colspan="2"><input  type="submit" value="Upload" name="btnsave" />
					</td>
				</tr>
		
			</table>
		</form>
	
		<?php
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif');         // Extensions that are valid
			session_start();

			if(isset($_POST['btnlogout']))
			{
				session_destroy();
				header("Location: index.php");
			}

			if(isset($_POST['btnaccount']))
			{
				header("Location: myaccount.php");							// Display account details
			}

			if(isset($_POST['btnsave']))
			{
				if(getimagesize($_FILES['propic']['tmp_name']) == FALSE)
				{
					echo "Please select an image.";							// Error message
				}
				else
				{
					$imageFile = $_FILES['propic']['name'];
					$imgExt = strtolower(pathinfo($imageFile,PATHINFO_EXTENSION));
					$image = addslashes($_FILES['propic']['tmp_name']);
					$caption = $_POST['caption'];
					$image = file_get_contents($image);
					$image = base64_encode($image);							// Encode image using base64 encoding and store in database

					if(in_array($imgExt, $valid_extensions))
					{
						saveimage($caption, $image);						// Function to save image
					}	
					else
					{
						echo  "Sorry, only JPG, PNG and GIF files are allowed";		// Error message
					}
				}
			}
			
			displayimage();
			
			function saveimage($caption, $image)
			{
				require 'dbonn.php';										// Establish connection
				
				$usrName = $_SESSION['userSession'];						// Accepting the username from session variable
				
				$qry = "insert into pics (Pic, Capt, Username, Time) values ('$image','$caption', '$usrName', NOW())";		// Insert query to insert into database
				$result = $conn->query($qry);
				
				if(!$result)
				{
					echo "<br/> Image not uploaded.";
				}
			}
			
			function displayimage()
			{
				require 'dbonn.php';
				
				$sql = "SELECT count(Id) FROM pics";						// Get total number of images in database
				$retval = $conn->query($sql);
				
				if(!$retval) 
				{
					die('Could not get data: ' . mysql_error());			// Error message
				}
				
				$row = mysqli_fetch_array($retval);
				
				$rec_count = $row[0];										// Save the count output
				$rec_limit = 10;
				
				$pages = ceil(($rec_count/$rec_limit));						// Total number of pages needed
				
				if( isset($_GET{'page'} ) ) 
				{
					$page = $_GET{'page'} + 1;								// If page is set, set offset and page
					$offset = $rec_limit * $page ;
				}
				else 
				{
					$page = 0;												// Default value
					$offset = 0;
				 }
				
				$left_rec = $rec_count - ($page * $rec_limit);										// Calculating the count left
				
				$qry = "SELECT * FROM pics ORDER BY Time desc LIMIT $offset, $rec_limit";			// Get all images from database
				$result = $conn->query($qry);
				
				while($row = mysqli_fetch_array($result))											// Display all images
				{
					echo 																					
					'<div class=center>
						<img height="200" width="200" src="data:image;base64,'.$row[1].'" >
						<h1>'.$row[2].'</h1>
					</div>';
					echo "<br/><br/></br></br>";
				}

				if( $page == 0 && $rec_count > 10) 													// Calculations to take care of pagination
				{
					echo "<a href = \"$_PHP_SELF?page=($page+1)\">Next 10 Records</a>";
				}
				else if( $left_rec < $rec_limit && $rec_count > 10) 
				{
					$last = $page - 2;
					echo "<a href = \"$_PHP_SELF?page=$last\">Last 10 Records</a>";
				}
				else if($left_rec > $rec_limit)
				{
					$last = $page - 2;
					echo "<a href = \"$_PHP_SELF?page=$last\">Last 10 Records</a> | ";
					echo "<a href = \"$_PHP_SELF?page=$page\">Next 10 Records</a>";
				}
			} 
		?>
	</body>
</html>
