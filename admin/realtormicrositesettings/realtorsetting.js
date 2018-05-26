
/************ handle for video upload *************/
$(document).ready(function (e) {
	
	// Variable to store your files
	var files;
	// Add events
	jQuery('input[type=file]').on('change', prepareUpload);

	// Grab the files and set them to our variable
	function prepareUpload(event)
	{
		files = event.target.files;
	}

	$("#fileUploadBtn").on('click', function(e) {

		e.preventDefault();
		// Create a formdata object and add the files
		var data = new FormData();

		jQuery.each( files, function( key, value )
		{
			data.append(key, value);
		});
	  $('#loading-image4').show();

	    $.ajax({
				url: "/admin/realtormicrositesettings/add-info.php?dmajax=1&upload_vdo=1", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				dataType: 'json',       // The content type used when sending data to the server.
				contentType: false,       // The content type used when sending data to the server.
				cache: false,             // To unable request pages to be cached
				processData:false,        // To send DOMDocument or non processed data file it is set to false
				success: function(data)   // A function to be called if request succeeds
				{      
				
					if(data){
						console.log(data.path);

						if(data.status){

							jQuery("#uploadedVdoFile").val(data.path);
							 //alert("Video Upload ");
						}
						if(!data.status){
							alert(data.error);
							jQuery(".error").val(data.error);
						}
					}

				},
				 complete: function(){
			        $('#loading-image4').hide();
			      },

					error : function(){
						
					      console.log(error);	
					      	
				     }
		});
	});
	
 });
/************ handel ajax for video form *************/
  jQuery(document).ready(function(){

        jQuery("#bg-video-form").validate(
        {
            // Rules for form validation
            rules:
            {
                video_name: { required: true },
                fileupload: { required: true},
                video: { required: true},
                theme_name: {
					require_from_group:[1, ".theme_season_holiday"]
				},
				 holidays: {
					require_from_group:[1, ".theme_season_holiday"]
				}, seasons: {
					require_from_group:[1, ".theme_season_holiday"]
				}
            },
                                
            // Messages for form validation
            messages:
            {
                video: { required: 'Please enter your video name' },
                fileupload: { required: 'Please upload your video' }//,
                //theme_name: {required: 'Please enter your thene name'}
                
            },

           // Ajax form submition
          submitHandler: function(form)
            {
                
		    $('#loading-image5').show();
				$.ajax({
				      url: "/admin/realtormicrositesettings/add-info.php?dmajax=1&vdo_frm=1",
				      data: $( "#bg-video-form" ).serialize(),
				      type: "POST",
				      dataType: "json",
				      
			     success: function( data )
			     {   
			      	if(data){
			      		console.log(data);
			      		  $('#success_message1').show("slow");
						     setTimeout(function() {
							$('#success_message1').hide("slow");
						}, 2000 );
				    
					$("#bg-video-form").trigger("reset");
			        }
			          	 
				    $("#add-table").load(window.location + " #add-table");
			    },
			    complete: function(){
			        $('#loading-image5').hide();
			        $('#success_message').css({display: "none"});
			    },
	            error: function()
	               {
	                     alert( "Network Connection Error: " + errorThrown );
	             }

                });
                
            }
            
        });

  });
/************ handle for image upload *************/
$(document).ready(function (e) {
	// Variable to store your files
	var files;
	// Add events
	jQuery('input[type=file]').on('change', prepareUpload);

	// Grab the files and set them to our variable
	function prepareUpload(event)
	{
	files = event.target.files;
	}

	$("#imguploadbtn").on('click', function(e) {

		e.preventDefault();
		// Create a formdata object and add the files
		var data = new FormData();

		jQuery.each( files, function( key, value )
		{
			data.append(key, value);
		});
		 $('#loading-image6').show();
		$.ajax({
				url: "/admin/realtormicrositesettings/add-info.php?dmajax=1&upload_img=1", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				dataType: 'json',       // The content type used when sending data to the server.
				contentType: false,       // The content type used when sending data to the server.
				cache: false,             // To unable request pages to be cached
				processData:false,        // To send DOMDocument or non processed data file it is set to false
				success: function(data)   // A function to be called if request succeeds
				{      
					if(data){
						console.log(data.path);
						if(data.status){
							jQuery("#uploadimgfile").val(data.path);
							//console.log();
							 //alert("Video Upload ");
						}
						if(!data.status){
							alert(data.error);
						}
					}
				},
				complete: function(){
			        $('#loading-image6').hide();
			    },
				failure : function(){
						
					console.log("AJAX Failed!");		
				}
	    });
	});
});
/************ handel ajax for image form *************/
$(document).ready(function(){
 	

        $("#bg-photo-form").validate(
        {
            // Rules for form validation
            rules:
            {
                photo_name: { required: true },
                fileupload: { required: true},
               theme_name: {
					require_from_group:[1, ".photo_group"]
				},
				 holidays: {
					require_from_group:[1, ".photo_group"]
				}, seasons: {
					require_from_group:[1, ".photo_group"]
				},
                image: {required: true}
            },
                                
            // Messages for form validation
            messages:
            {
                photo: { required: 'Please enter your video name' },
                fileupload: { required: 'Please upload your image' },
                theme_name: {required: 'Please enter your thene name'},
                image: {required: 'Please enter your thene name'}
                
            },

           // Ajax form submition
           submitHandler: function(form)
            {
		     $('#loading-image7').show();

		     $.ajax({
			      url: "/admin/realtormicrositesettings/add-info.php?dmajax=1&img_frm=1",
			      data: $( "#bg-photo-form" ).serialize(),
			      type: "POST",
			      dataType: "json",
			     
			    success: function (data) {
			    	if(data){
				     	console.log(data.path);
				       
				        $('#success_message2').show("slow");
						     setTimeout(function() {
							$('#success_message2').hide("slow");
						}, 2000 );
						$("#bg-photo-form").trigger("reset");
				    }

					$("#add-table").load(window.location + " #add-table");
					      
			    },
		        complete: function(){
				        $('#loading-image7').hide();
				       
				},
		        failure : function(){
							console.log("AJAX Failed!");
						}

		  });
		 }
		});

});

