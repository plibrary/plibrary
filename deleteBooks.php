<?php 
	$bookIds = $_GET['bookIds'];
	
	
	if(!empty($bookIds)){
		$sql = "DELETE from book where book_id in (".$bookIds.")";
		$result = $conn->query($sql);
		
		if ($conn->query($sql) === TRUE) {
			header("Location: index.php?page=bookList");
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}else{
		echo "Sorry, book id is required.";
	}
?>