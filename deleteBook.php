<?php 
	$bookId = $_GET['bookId'];
	
	if(!empty($bookId)){
		$sql = "DELETE from book where book_id = " . $bookId;
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