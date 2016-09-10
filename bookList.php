<?php 
	$sql = "SELECT * from book";
	$result = $conn->query($sql);
?>
<h1 class="text-center">Book List</h1>

<table class="table table-bordered">
  <thead>
  		<th>
  			<input type="checkbox" id="checkAll">
  		</th>
  		<th>No</th>
  		<th>Title</th>
  		<th>quantity</th>
  		<th>Price</th>
  		<th>Description</th>
  		<th>Action</th>
  </thead>
  <tbody>
  		<?php 
	  		if ($result->num_rows > 0) {
	  			
	  			$index=1;
	  			// output data of each row
	  			while($row = $result->fetch_assoc()) {
	  	?>			
	  				<tr>
	  					<td>
	  						<input type="checkbox" class="check" data-book-id="<?=$row['book_id']?>">
	  					</td>
			  			<td><?=$index?></td>
			  			<td><?=$row['book_title']?></td>
			  			<td><?=$row['quantity']?></td>
			  			<td><?=$row['price']?></td>
			  			<td><?=$row['description']?></td>
			  			<td>
			  				<a class="btn btn-default" href="?page=updateBook&bookId=<?=$row['book_id']?>">Edit</a>
			  				<a class="btn btn-danger btnDel" data-book-id="<?=$row['book_id']?>">Delete</a>
			  			</td>
			  		</tr>
		<?php 	
					$index++;
	  			}
	  		} else {
	  			echo "No book found.";
	  		}
  		?>
  </tbody>
</table>
<a class="btnDelAll btn btn-danger" >Delete all</a>
<script>
	jQuery(function(){
		$('.btnDel').click(function(){
			if(confirm('Are you sure, you want to delete this book?')){
				var bookId = $(this).data().bookId;

				document.location.href = '?page=deleteBook&bookId=' + bookId;
			}	
		});

		$('#checkAll').change(function(){
			var $checkAll = $(this);

			$(".check").prop('checked', $checkAll.is(':checked') );
				
		});

		$('.btnDelAll').click(function(){
			if(confirm('Are you sure, you want to delete these books?')){
				var bookIds = [];

				$('.check:checked').each(function(){
					var bookId = $(this).data().bookId;

					bookIds.push(bookId);
				});

				document.location.href = '?page=deleteBooks&bookIds=' + bookIds;
			}
		});
	});
</script>