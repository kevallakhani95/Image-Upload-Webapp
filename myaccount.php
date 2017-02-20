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
</html>
<?php
	
	displayimage();

	function displayimage()
	{
		require 'dbonn.php';
		session_start();
		$usrName = $_SESSION['userSession'];

		$sql = "SELECT count(Id) FROM pics where Username='".$usrName."'";					// Count all id's in the database of that account
		$retval = $conn->query($sql);
		
		if(!$retval ) 
		{
			die('Could not get data: ' . mysql_error());
		}
		
		$row = mysqli_fetch_array($retval);
		
		$rec_count = $row[0];
		$rec_limit = 10;
		
		$pages = ceil(($rec_count/$rec_limit));
		
		if( isset($_GET{'page'} ) ) 
		{
			$page = $_GET{'page'} + 1;
			$offset = $rec_limit * $page ;
		}
		else 
		{
			$page = 0;
			$offset = 0;
		 }
		
		$left_rec = $rec_count - ($page * $rec_limit);


		$qry = "SELECT * FROM pics where Username='".$usrName."' ORDER BY Time desc LIMIT $offset, $rec_limit";			// select all pics to display
		$result = $conn->query($qry);
		
		while($row = mysqli_fetch_array($result))
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