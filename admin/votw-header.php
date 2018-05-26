
<form id=tourForm name=submit method=GET action="/admin/votw.php">
<div class="form_line" style="position:relative;top: 0px;">
        <div class="input_line w_lg" style="width: 360px;">
            <div class="input_title">Tour ID</div>
		<input style="width: 100px;" type="text" id='tourId' name="tourId" value="<?php if(isset($_GET['tourId'])){ echo intval($_GET['tourId']);}?>">
		<div id='submit' class="button_new button_blue_big button_mid" style='top:-10px;float:left;'>
                <div class="curve curve_left"></div>
                <div class="button_caption">Search</div>
                <div class="curve curve_right"></div>
            </div>
        </div>
</div>
</form>




