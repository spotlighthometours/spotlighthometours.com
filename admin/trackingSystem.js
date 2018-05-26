$(document).ready(function(){
	
    console.log("Window width: " + $(window).width());
    //Aesthetic changes
    if( browser.mozilla ){

        $("#notesCreateDiv").
            css("margin","125px 0px 0px 0px")
        ;
        $("#createAddFilesDiv").
            css("margin","19px 0px 20px 0px")
        ;
        $("#createAddFilesDiv2").
            css('margin','140px 0px 0px 90px')
        ;
        $("#notesCreateRequired").
            css('margin','10px 0px 0px 0px')
        ;
        $("#form_wrapper").
            css('width','552').
            css('height','310')
        ;
        $("#createButton").
            css("left","400px").
            css("top","110px").
            css("position","relative").
            css("clear","left")
        ;

    }else if( browser.chrome ){
        $("#createAddFilesDiv").
            css("padding-top","10px")
        ;
    }
    
    $("input[id^='cbAssign_']").each(function(){
    	$(this).bind("click",function(){
    		if( $(this).is(":checked")){
    			var a = "switch-on";
    		}else{
    			var a = "switch-off";
    		}
  		
    		var b = $(this).attr("id").split("_")[1];
    		$.ajax({
    			type: "POST",
				data: {
					id: b,
					switch_direction: a
				}
    		}).done(function(msg){
    			console.log(msg);
    			if( msg == 'on' ){
    				alert("You are now assigned to this task");
    			}else{
    				alert("You are no longer assigned to this task");
    			}
    		});
    	});
    });
    
    $("#viewNotesSaveWrapper").bind("click",function(){
    	$.ajax({
    		type: "POST",
    		data: {
    			updateFixNotes: $("#viewNotesTextarea").val().replace(/<pre>/,'').replace(/<\/pre>/,'') ,
    			fixIndex: $("#fixIndex").val()
    		},
    		async: false
    	}).done(function(msg){
    		alert("Notes updated");
    		revertNotesSaved();
    	});
    });
    
    function revertNotesSaved(){
       	$("#viewNotesTWrapper").fadeOut();	//css("display","none");
      	$("#viewNotesTextarea").fadeOut(); //css("display","none").
      	$("#viewNotesModalNote").css("display","block").html( 
      		"<pre>"  + getNotes($("#fixIndex").val(),false) + "</pre>"
      	);
    }
    

    function getNotes(fixId,useJson){
    	window.notes;
    	$.ajax({
            data:{
            	'id': fixId,
                'request': 'notes'
            },
            type: 'POST',
            url: '/admin/fixes/trackingSystem.php',
            cache: false,
            async: false
        }).done(function(m){
        	if( useJson ){
        		a = $.parseJSON(m);
        		window.notes = a[0].notes;
        	}else{
        		window.notes = m;
        	}
        });
    	
    	return window.notes;
    }
    function br2nl(varTest){
    	return varTest.replace(/(\r\n|\n\r|\r|\n)/g, "<br>");
    };
    
    function nl2br(str, is_xhtml) {
    	  var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display
    	  return (str + '')
    	    .replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    	}    

    $("#viewNotesEdit").bind("click",function(){
    	event.preventDefault();
    	$("#viewNotesModalNote").css("display","none");
    	$("#viewNotesTWrapper").css("display","block");
    	$("#viewNotesTextarea").css("display","block").
    		css("height","380px").
    		css("width","480px").val( $("#viewNotesModalNote").html().replace(/<pre>/,'').replace(/<\/pre>/,'') );
    	
    });
    
    //Send a fixid as a param
    //send a function as a callback for done
    function grabUploadsJson(b,func){
    	$.ajax({
			type: "POST",
			data: {
				id: b,
				uploadsJson: 1
			}
		}).done(func);
    }
    
    $("a[id^='aUploads_']").each(function(){
    	
    	$(this).bind("click",function(e){
    		e.preventDefault();
    		b = $(this).attr("id").split("_")[1];
    		window.uploadsContainer;
    		grabUploadsJson(b,function(msg){
    			$("#uploadsModal").dialog();
    			$("#uploadsModalContent").html(msg);
    		});
    	});
    	$(this).qtip();
    });
    
    $("#tableWrapper").
        css("width",$(window).width()).
        css("margin-left","-10px")
    ;
	$("a[id^=editButton_]").each(function(){
		$(this).bind("click",function(){
			//Load modal dialog
			$("#fixIndex").attr("value",$(this).attr("id").split('_')[1]);
			$("#dialog").dialog({width: 500});
			$.ajax({
				type: "POST",
				data: {
					grabNote: $("#fixIndex").val()
				}
			}).done(function(msg){
				$("#updateFixNotes").val(msg);
			});
		});
	});

    $("tr[id^='slideToggle_']").each(function(){
        $(this).slideToggle();
        
    });
    $("a[id^='slideToggleAnchor_']").each(function(){
        $(this).bind("click",function(event){
        	if( $(this).html() == "Click here to see replies"){
        		$(this).html(">>> Collapse");
        	}else{
        		$(this).html("Click here to see replies");
        	}
            event.preventDefault();
            $("tr[id='slideToggle_" + $(this).attr("id").split("_")[1] + "']").each(function(){
console.log("Inside slide toggle each");
                $(this).slideToggle();
            });
        });
    }); 

	$("#dialogBtnSubmit").bind("click",function(){
		$.ajax({
					type: "POST",
					data: {
						updateFixNotes: $("#updateFixNotes").val(),
						fixIndex: $("#fixIndex").val()
					}
		}).done(function(msg){
			$("#saved").html("<b>Notes have been updated</b>");
		});
	});

	$("a[id^=replyButton_]").each(function(){
		$(this).bind("click",function(event){
			event.preventDefault();
			$("#dialog").dialog({width:500});
			$("#parent_node").val($(this).attr("href"));
            $("#dialogTourId").val( $(this).attr("tourId") );
			//alert($("#parent_node").val());
		});
	});
	$("#createAddFiles").bind("click",function(event){
		event.preventDefault();
        window.obj = null;
		$("#createAddFilesDiv input").each(function(){
            window.obj = $(this);
        });
        window.obj.after("<br><input style='left:110px;position:absolute;' class=file type='file' name='attachments[]'>");
	});
	$("#replyAddFiles").bind("click",function(event){
		event.preventDefault();
        window.obj2 = null;
		$("#replyAddFilesDiv input").each(function(){
            window.obj2 = $(this);
        });
        window.obj2.after("<br><input class=file type='file' name='attachments[]' style='left: 109px;'>");
	});
    if( browser.chrome ){
        $("#empid").bind("change",function(event){
            window.location.href = "/admin/fixes/trackingSystem.php?empid=" + $(this).val();
        });
    }else{
    	$("option[class='employeeList']").bind("click",function(event){
    		window.location.href = "/admin/fixes/trackingSystem.php?empid=" + $(this).val();
    	});
    }
	$("#searchButton").bind("click",function(event){
		event.preventDefault();
		$("#searchForm").trigger("submit");
	});
   
    $("input[id^='resolver_']").each(function(){
        $(this).bind("click",function(){
            var status = $(this).is(":checked");
            var a = $(this).attr('id').split("_");
            
            if( status ){
                //Resolve issue
console.log("Resolve issue true");
                setRowState(a[1],true);
            }else{
                //Unresolve issue
                setRowState(a[1],false);
            }
     
        });
    });

    $("input[id^='heatherizer_']").each(function(){
        $(this).bind("click",function(){
            var status = $(this).is(":checked");
            a = $(this).attr('id').split("_");
           console.log(a); 
            if( status ){
console.log("Set heatherized true");
                setHeatherized(a[1],true);
            }else{
                setHeatherized(a[1],false);
            }
     
        });
    });
    $("a[class^='viewNotes_']").each(function(){
        $(this).bind("click",function(event){
            event.preventDefault();
            a = $(this).attr("class").split('_')[1];
            $("#fixIndex").val(a);
            $.ajax({
                data:{
                    'id': a,
                    'request': 'notes'
                },
                type: 'POST',
                url: '/admin/fixes/trackingSystem.php'
            }).done(function(m){
                $("#viewNotesModal").dialog({width: 500,height: 500});
                $("#viewNotesModalNote").html('<pre>' + m + '</pre>');
            });
        });
    });
    
    $("a[id^='infoLink_']").bind("click",function(event){
    	event.preventDefault();
    	viewTour($(this).attr("id").split('_')[1]);
    });

    function setHeatherized(id,st){
        $.ajax({
            data:{
                'id': id,
                'heatherized': (st?'1':'0') 
            },
            type: 'POST',
            url: '/admin/fixes/trackingSystem.php?heatherized_set=1'
        }).done(function(msg){
            $("#trRows_"+id).fadeIn();
            if( st ){
                color = "#e2f0fa";
            }else{
                color = "white";
            }
            $("#trRows_"+id).css("background-color",color);//#98FB98");
            $("tr[class='reply_"+id+"']").each(function(){
                $(this).css("background-color",color); //#BCED91");
            });
            $("div[id^='replyDiv_']").each(function(){
                $(this).css("background-color",color);
            });
        });
    }
    function setRowState(id,st){
        var a;
        if( st ){
            a = "resolved";
        }else{
            a = "open";
        }
        $("#status_"+id).html(a);
        $.ajax({
            data:{
                'id': id,
                'status': a
            },
            type: 'POST',
            url: '/admin/fixes/trackingSystem.php?status_set=1'
        }).done(function(msg){
            $("#trRows_"+id).fadeIn();
            if( st ){
                color = "#e2f0fa";
            }else{
                color = "white";
            }
            $("#trRows_"+id).css("background-color",color);//#98FB98");
            $("tr[class='reply_"+id+"']").each(function(){
                $(this).css("background-color",color); //#BCED91");
            });
            $("div[id^='replyDiv_']").each(function(){
                $(this).css("background-color",color);
            });
        
        });
    }
});
