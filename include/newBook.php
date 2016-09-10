<?php if (!empty($_POST)): ?>

	<?php 
		$sql = "INSERT INTO book (book_title, quantity, price, description)
		VALUES ('".$_POST['book_title']."', '".$_POST['quantity']."', '".$_POST['price']."', '".$_POST['description']."')";
		
		if ($conn->query($sql) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		
		$conn->close();
	?>
   
<?php else: ?>
    <div class="row">
		<div class="col-lg-6">
			<div class="panel panel-default">
			  <div class="panel-heading">Add New Book</div>
			  <div class="panel-body">
					<form action="?page=newBook" method="post">
					  <div class="form-group">
					    <label for="book_title">Title</label>
					    <input type="text" class="form-control" id="book_title" name="book_title" placeholder="">
					  </div>
					  <div class="form-group">
					    <label for="quantity">Quantity</label>
					    <input type="number" class="form-control" name="quantity" id="quantity" placeholder="">
					  </div>
					  <div class="form-group">
					    <label for="price">Price</label>
					    <input type="number" class="form-control" name="price" id="price" placeholder="">
					  </div>
					  <div class="form-group">
					    <label for="description">Description</label>
					    <textarea class="form-control" rows="3" name="description" id="description" placeholder=""></textarea>
					  </div>
					  <button type="submit" class="btn btn-primary">Save</button>
					  <a class="btn btn-default" href="?page=bookList">Cancel</a>
					</form>
			  </div>
			</div>
		</div>
	</div>
<?php endif; ?>

