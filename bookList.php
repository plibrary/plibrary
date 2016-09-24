<?php 
	define("FILED_QUANTITY", "quantity");
	
	$page_size = 2;
	$bookCount= $conn->query('select count(book_id) as total from book ');
	$data = $bookCount->fetch_assoc();
	$total_rows = $data['total'];
	$offset = empty($_GET['offset']) ? 0 : $_GET['offset'];
	
	$isAscending = empty($_GET['isAscending']) ? true : !$_GET['isAscending'];
	$orderByField = empty($_GET['orderBy']) ? 'book_title' : $_GET['orderBy'];
	$select = "SELECT * from book";
	$orderBy = " order by ".  $orderByField . ' ' . ($isAscending ? 'asc' : 'desc');
	$limit = " limit ". $offset .", ".$page_size;
	$sql = $select . $orderBy . $limit;
	
	//echo($sql);
	
	$result = $conn->query($sql);
	
	
?>
<h1 class="text-center">Book List</h1>

<table class="table table-bordered">
  <thead>
  		<th>
  			<input type="checkbox" id="checkAll">
  		</th>
  		<th>No</th>
  		<th>
  			<a href="?<?=$_SERVER['QUERY_STRING']?>&orderBy=book_title&isAscending=<?=$isAscending?>">
  				Title
  				<?php 
  					if($orderByField == 'book_title'){
  				?>		
		  				<span class="glyphicon glyphicon-arrow-<?= $isAscending ? 'up' : 'down'  ?>"></span>
		  		<?php 		
  					}
  				?>
  			</a>
  		</th>
  		<th>
  			<a href="?<?=$_SERVER['QUERY_STRING']?>&orderBy=<?=FILED_QUANTITY?>&isAscending=<?=$isAscending?>">
  				Quantity
  				<?php 
  					if($orderByField == FILED_QUANTITY){
  				?>		
		  				<span class="glyphicon glyphicon-arrow-<?= $isAscending ? 'up' : 'down'  ?>"></span>
		  		<?php 		
  					}
  				?>
  			</a>
  		</th>
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

<nav aria-label="..."> 
	<ul class="pagination"> 
		<li><a href="?<?=$_SERVER['QUERY_STRING']?>&offset=0">First</a></li> 
		<li><a href="#">Prev</a></li> 
		<?php 
			for($i = 0 ; $i < ($total_rows/$page_size) ; $i++ ){
		?>
			<li class="<?=$i == $offset ? 'active' : ''?>"><a href="?<?=$_SERVER['QUERY_STRING']?>&offset=<?=$i?>"><?=$i+1?></a></li> 
		<?php 
			}
			$nextLink = "";
			
			if($offset < ($total_rows/$page_size) -1){
				$nextLink .= "href='?".$_SERVER['QUERY_STRING'];
				$nextLink .= '&offset='. ($offset + 1)."'";
			}
		?>
		<li <?=$nextLink ? '' : 'class="disabled"'?>>
			<a <?=$nextLink?> >Next</a></li>
		<li><a href="?<?=$_SERVER['QUERY_STRING']?>&offset=<?=($total_rows/$page_size)-1?>">last</a></li> 
	</ul> 
</nav>

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