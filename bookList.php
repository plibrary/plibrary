<?php 
	define("FILED_QUANTITY", "quantity");
	
	$page_size = 2;
	
	$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
	
	
	$offset = 0;
	if (isset($_REQUEST['offset']) && !empty($_REQUEST['offset']))
	{
		$offset = $_REQUEST['offset'];
	}
	
	if (isset($_REQUEST['isAscending']) && !empty($_REQUEST['isAscending']))
	{
		$isAscending = $_REQUEST['isAscending'];
		
		if (isset($_REQUEST['doOrderBy']) && !empty($_REQUEST['doOrderBy']) && $_REQUEST['doOrderBy']){
			$isAscending  = $isAscending ? 0 : 1;
		}
	}else{
		$isAscending = 1;
	}
	
	$orderByField = 'book_title';
	if (isset($_REQUEST['orderBy']) && !empty($_REQUEST['orderBy']))
	{
		$orderByField = $_REQUEST['orderBy'];
	}
	
	$dataUrl = array(
  						'isAscending'=>$isAscending,
  						'orderBy'=>$orderByField,
						'offset' => $offset
  					);
	
	$select = "SELECT * from book";
	$search = " WHERE book_title LIKE '%".$search
				."%' OR quantity LIKE '%".$search
				."%' OR price LIKE '%".$search
				."%' OR description LIKE '%".$search."%' ";
	$orderBy = " order by ".  $orderByField . ' ' . ($isAscending ? 'asc' : 'desc');
	$limit = " limit ". $offset .", ".$page_size;
	
	$sql = $select . $search . $orderBy . $limit;
	
	$bookCount= $conn->query('select count(book_id) as total from book '.$search);
	$data = $bookCount->fetch_assoc();
	$total_rows = $data['total'];
	
	//echo($sql);
	
	$result = $conn->query($sql);
	
	
?>
<h1 class="text-center">Book List</h1>

 <div class="form-group">
    <div class="input-group">
      <input type="text" class="form-control" id="search" placeholder="search" value="<?=isset($_GET['search']) ? $_GET['search'] : '' ?>">
      <div class="input-group-addon" id="btnSearch">search</div>
    </div>
  </div>
<br>

<table class="table table-bordered">
  <thead>
  		<th>
  			<input type="checkbox" id="checkAll">
  		</th>
  		<th>No</th>
  		<th>
  			<?php 
  				$dataUrl = array(
  						'isAscending'=>$isAscending,
  						'orderBy'=>'book_title',
						'offset' => $offset,
  						'doOrderBy' => 1
  				);
  				
	  			$url = http_build_query($dataUrl);
  			?>
  			<a href="?<?= $url ?>">
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
  			<?php 
	  			$dataUrl = array(
	  					'isAscending'=>$isAscending,
	  					'orderBy'=>FILED_QUANTITY,
	  					'offset' => $offset,
	  					'doOrderBy' => 1
	  			);
	  			
	  			$url = http_build_query($dataUrl);
  			?>
  			<a href="?<?=$url?>">
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
		<?php 
  				$dataUrl = array(
	  					'isAscending'=>$isAscending,
	  					'orderBy'=> isset($_REQUEST['orderBy']) &&  !empty($_REQUEST['orderBy']) ? $_REQUEST['orderBy'] : $orderByField,
	  					'offset' => $offset,
	  					'doOrderBy' => 0
	  			);
	  			
	  			$url = http_build_query($dataUrl);
  			?>
  			
		<li><a href="?<?=$url?>">First</a></li> 
		<li><a href="#">Prev</a></li> 
		<?php 
			for($i = 0 ; $i < ($total_rows/$page_size) ; $i++ ){
		?>
		<?php 
  				$dataUrl = array(
	  					'isAscending'=>$isAscending,
	  					'orderBy'=> isset($_REQUEST['orderBy']) &&  !empty($_REQUEST['orderBy']) ? $_REQUEST['orderBy'] : $orderByField,
	  					'offset' => $i,
	  					'doOrderBy' => 0
	  			);
	  			
	  			$url = http_build_query($dataUrl);
  			?>
			<li class="<?=$i == $offset ? 'active' : ''?>"><a href="?<?=$url?>"><?=$i+1?></a></li> 
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
		
		function updateQueryStringParameter(uri, key, value) {
			  var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
			  var separator = uri.indexOf('?') !== -1 ? "&" : "?";
			  if (uri.match(re)) {
			    return uri.replace(re, '$1' + key + "=" + value + '$2');
			  }
			  else {
			    return uri + separator + key + "=" + value;
			  }
			}
		
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

		$('#btnSearch').click(function(){
			var search = $('#search').val();
			
			document.location.href = updateQueryStringParameter(window.location.href, 'search', search);
		});
	});
</script>