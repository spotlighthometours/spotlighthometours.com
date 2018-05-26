<?php
    require 'repository_inc/classes/inc.global.php';
 
    $en = new emailnotifications;
    if( isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'ajax' ){
        $_SESSION['checklistSent'] = true;
        $en->sendChecklistEmail($_REQUEST['tourId'],$_REQUEST['email']);
        die(json_encode(array('status'=>'ok')));
    }

    if( isset($_GET['clear']) ){
        unset($_SESSION['checklistSent']);
    }else{
        $email = $en->getChecklistEmail($_REQUEST['tourId']);
        if( strlen($email) ){
            if( !isset($_SESSION['checklistSent']) ){
                $_SESSION['checklistSent'] = true;
                $en->sendChecklistEmail($_REQUEST['tourId'],$email);
            }
        }
    }
?>
<html>
<head>
    <link rel='stylesheet' type='text/css'  href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'/>
    <script src='https://code.jquery.com/jquery-1.11.3.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
    <script>
        function send(){
            loading();
            $.ajax({
                url:'homeowner_email.php?mode=ajax',
                data: { 
                    'email': $("#emailBox").val(),
                    'tourId': <?php echo intval($_GET['tourId']); ?>
                }
            }).done(function(msg){
                stopLoading();
                
            });
        }
        function loading(){
            $("#emailBox").attr("disabled",true);
            $("#sendButton").text("Sending...");
        }
        function stopLoading(){
            $("#sendPrompt").slideUp("slow",function(){
                $("#success").attr("class","show").slideDown();
            });
        }
    </script>
    <title>Send Home Owner Email</title>
    <style type='text/css'>
        .centered .jumbotron{ 
            margin: 0 auto;
            text-align: center;
        }
        .hide { 
            visibility: hidden;
        }
        .show { 
            visibility: show;
        }
    </style>
</head>
<body>
<div class="centered">
<div class="jumbotron" style='width: 50%;'>

<?php
    if( isset($_SESSION['checklistSent']) ){
        echo "<h1>Email sent!</h1>";
?>
    <p>Email sent successfully. :)</p>
<?php
    }else{
?>
    <div id='sendPrompt'>
    <h1>One more step...</h1>
    <p>Enter the Homeowner's email address here and click Send</p>
    <p><input id='emailBox' type="text" class="form-control" placeholder="Recipient's email" aria-describedby="basic-addon2" style='width: 200px;margin:0 auto;'>
    <p><a id='sendButton' class="btn btn-primary btn-lg" href="#" role="button" onClick='send()'>Send</a></p>
    </div>
    <div id='success' class='hide'>
        <h1>Email sent! :)</h1>
        <p>P.S.: You can close this window now</p>
    </div>
<?php
    }
?>
</div>

</div>
</body></html>
