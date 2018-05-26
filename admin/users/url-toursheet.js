function drawMicrositeUrlList(tourId,ele){
    $.ajax({
        url: '/repository_queries/admin_get_urls.php',
        data:{
            "tourId": tourId,
            "type": 'microsite'
        },
        type: "POST"
    }).done(function(msg){
        json = $.parseJSON(msg);
        if( json.count > 0 ){
            $(ele).append(json.html);
            $(ele).append("<b>Point To:</b> http://" + json.pointTo + ".spotlighthometours.com");
        }
    });
}

function drawPropertyUrlList(tourId,ele){
    $.ajax({
        url: '/repository_queries/admin_get_urls.php',
        data:{
            "tourId": tourId,
            "type": 'property'
        },
        type: "POST"
    }).done(function(msg){
        json = $.parseJSON(msg);
        if( json.count > 0 ){
            $(ele).append(json.html);
        }
    });
}
