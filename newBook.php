<?php 
	if($_POST){
		if(empty($_POST['book_title']) || empty($_POST['quantity']) || empty($_POST['price']) ){
			echo "Please input all required fileds.";
		}else{
			$sql = "INSERT INTO book (book_title, quantity, price, description)
			VALUES ('".$_POST['book_title']."', '".$_POST['quantity']."', '".$_POST['price']."', '".$_POST['description']."')";
			
			if ($conn->query($sql) === TRUE) {
				echo "New record created successfully";
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		
	}else{
?>
<form action="?page=newBook" method="post">
  <div class="form-group">
    <label for="book_title">Title</label>
    <input type="text" class="form-control" name="book_title" id="book_title" placeholder="">
  </div>
  <div class="form-group">
    <label for="quantity">quantity</label>
    <input type="number" class="form-control" name="quantity" id="quantity" placeholder="">
  </div>
  <div class="form-group">
    <label for="price">price</label>
    <input type="number" class="form-control" name="price" id="price" placeholder="">
  </div>
  <div class="form-group">
    <label for="description">description</label>
    <textarea class="form-control" rows="3" name="description" id="description"></textarea>
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