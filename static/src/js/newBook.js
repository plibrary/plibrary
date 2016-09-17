$(function(){
	var newBook = {
		init : function(){
			newBook.validate();
			
		},
		
		validate : function(){
			$("#form").validate();
		},
	};
	
	newBook.init();
	
});