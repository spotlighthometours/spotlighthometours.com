<h3>Add New Community</h3>

<?php require(__DIR__.'/frm-new-community-frm.php');?>

<script type="text/javascript">
jQuery("document").ready(function(){
	jQuery("#mlsinfo").on('click', '.delMLSInfo', function(e){

		e.preventDefault();
		mlsFieldCount = jQuery("#mlsinfo table").length;

		if(mlsFieldCount > 1){
			// jQuery("#subtbl_"+ (mlsFieldCount-1) ).remove();
			jQuery(this).closest('table').remove();

		}
		else{
			alert("At least 1 MLS fieldset is required!");
		}
		
		

	});
	jQuery("#mlsinfo").on('click', '.addMLSInfo', function(e){

			e.preventDefault();

			mlsFieldCount = jQuery("#mlsinfo table").length;
			// console.log(mlsFieldCount); return;

			var loaderImg = $(this).parent().find('img.loader');
			loaderImg.show();
			$.ajax({
					url: "/admin/realtormicrositesettings/add-comm.php?dmajax=1&mlsHTML="+mlsFieldCount, // Url to which the request is send
					type: "POST",             // Type of request to be send, called as method
					data: { num : mlsFieldCount }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
					dataType: 'json',       // The content type used when sending data to the server.
					contentType: false,       // The content type used when sending data to the server.
					cache: false,             // To unable request pages to be cached
					processData:false,        // To send DOMDocument or non processed data file it is set to false
					success: function(data)   // A function to be called if request succeeds
					{
	 
						if(data){
							
							if(data.status){
								
								console.log(data);
								jQuery("#mlsinfo").append(data.response);
							    
							}
							if(!data.status){
								alert(data.error);
					        }
						}
					},

					 complete: function(){
				        loaderImg.hide();
				      },

					failure : function(){
						console.log("AJAX Failed!");
					}

			});

	});
});
</script>

