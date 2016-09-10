<?php 
	$bookId = $_GET['bookId'];

	if($_POST){
		if(empty($_POST['book_title']) || empty($_POST['quantity']) || empty($_POST['price']) ){
			echo "Please input all required fileds.";
		}else{
			$sql = "UPDATE BOOK SET book_title = '".$_POST['book_title']."', quantity = ".$_POST['quantity'].", price=".$_POST['price'].", description='".$_POST['description']."' where book_id=".$bookId;
			
			if ($conn->query($sql) === TRUE) {
				header("Location: index.php?page=bookList");
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		
	}else{
		$sql = "SELECT * from book where book_id = " . $bookId;
		$result = $conn->query($sql);
		$book = null;
		
		while($row = $result->fetch_assoc()) {
			$book = $row;
		}
		
?>
<form method="post">
  <div class="form-group">
    <label for="book_title">Title</label>
    <input value="<?=$book['book_title']?>" type="text" class="form-control" name="book_title" id="book_title" placeholder="">
  </div>
  <div class="form-group">
    <label for="quantity">quantity</label>
    <input value="<?=$book['quantity']?>" type="number" class="form-control" name="quantity" id="quantity" placeholder="">
  </div>
  <div class="form-group">
    <label for="price">price</label>
    <input value="<?=$book['price']?>" type="number" class="form-control" name="price" id="price" placeholder="">
  </div>
  <div class="form-group">
    <label for="description">description</label>
    <textarea class="form-control" rows="3" name="description" id="description"><?=$book['description']?></textarea>
  </div>
  <div class="form-group">
    <label for="exampleInputFile">Image</label>
    <input type="file" id="exampleInputFile">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
  <a href="?page=bookList" class="btn btn-default">Cancel</a>
</form>

<?php 
	}
?>