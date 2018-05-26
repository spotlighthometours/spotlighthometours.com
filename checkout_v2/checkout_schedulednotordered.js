$(document).ready(function(){
    a = location.href;
    b = a.split("notOrdered")[1];
    c = b.split("&")[0].split("=")[1];
    $.ajax({
        type: "POST",
        url: "/repository_queries/get_tour_info.php",
        data:{
            tourId: c    
        }
    }).done(function(msg){
        a = $.parseJSON(msg);
        data = a.data[0];
        $("#tour_title").val(data['title']);
        $("#tour_address").val(data['address']);
        $("#tour_unitNumber").val(data['unitNumber']);
        $("#tour_city").val(data['city']);
        $("#tour_state").val(data['state']);
        $("#tour_zip").val(data['zipCode']);
    });
});
