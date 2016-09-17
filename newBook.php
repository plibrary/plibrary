<?php 
	if($_POST){
		$quantity = $_POST['quantity'];
		
		if(empty($_POST['book_title']) || $quantity == NULL || empty($_POST['price']) ){
			echo "Please input all required fileds.";
		}
		else if( strlen(trim($_POST['book_title'])) < 2 ){
			echo "Please input at least 2 characters of book title.";
		}
		else if( is_numeric($quantity) == false ){
			echo "Please input number of quantity as number only.";
		}
		else if( $quantity < 1 ){
			echo "Please input number of quantity greater than 0.";
		}
		else{
			$target_dir = "./uploads/bookImage/";
			$target_file = $target_dir . basename($_FILES["image_url"]["name"]);
			$uploadOk = 1;
			$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
			
			// Check if image file is a actual image or fake image
			if(isset($_POST["submit"])) {
				$check = getimagesize($_FILES["image_url"]["tmp_name"]);
				if($check !== false) {
					echo "File is an image - " . $check["mime"] . ".";
					$uploadOk = 1;
				} else {
					echo "File is not an image.";
					$uploadOk = 0;
				}
			}
			// Check if file already exists
			if (file_exists($target_file)) {
				echo "Sorry, file already exists.";
				$uploadOk = 0;
			}
			// Check file size
			if ($_FILES["image_url"]["size"] > 500000) {
				echo "Sorry, your file is too large.";
				$uploadOk = 0;
			}
			// Allow certain file formats
			if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
					&& $imageFileType != "gif" ) {
				echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
				$uploadOk = 0;
			}
			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
				// if everything is ok, try to upload file
			} else {
				if (move_uploaded_file($_FILES["image_url"]["tmp_name"], $target_file)) {
					$sql = "INSERT INTO book (book_title, quantity, price, description, image_url)
					VALUES ('".$_POST['book_title']."', '".$quantity."', '".$_POST['price']."', '".$_POST['description']
					."', '".$_FILES["image_url"]["name"]."')";
					
					if ($conn->query($sql) === TRUE) {
						echo "New record created successfully";
					} else {
						echo "Error: " . $sql . "<br>" . $conn->error;
					}
				} else {
					echo "Sorry, there was an error uploading your file.";
				}
			}
			
		}
		
	}else{
?>
<form action="?page=newBook" method="post" id="form" enctype="multipart/form-data">
  <div class="form-group">
    <label for="book_title">Title</label>
    <input type="text" class="form-control" name="book_title" id="book_title" placeholder="" required minlength="2">
  </div>
  <div class="form-group">
    <label for="quantity">quantity</label>
    <input min="1" required="required" type="number" class="form-control" name="quantity" id="quantity" placeholder="">
  </div>
  <div class="form-group">
    <label for="price">price</label>
    <input min="1" type="number" required="required" class="form-control" name="price" id="price" placeholder="">
  </div>
  <div class="form-group">
    <label for="description">description</label>
    <textarea class="form-control" rows="3" name="description" id="description"></textarea>
  </div>
  <div class="form-group">
    <label for="image_url">Image</label>
    <input type="file" id="image_url" name="image_url" required="required">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
  <a href="?page=bookList" class="btn btn-default">Cancel</a>
</form>

<?php 
	}
?>

<script src="static/src/js/newBook.js"></script>