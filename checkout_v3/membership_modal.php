<?php $membershipName = $_POST['membershipName'];?>
<div class="complete_frame" >
	<div class="complete_title" >
    	<div class="check" ></div>
    	<?php echo $membershipName;?> ordered!
	</div>    
    <div class="complete_text" style="line-height:20px !important;">
        Thank you for activating your <?php echo $membershipName;?> with Spotlight Home Tours!
        <br />
        <br />
        Once you click on "Let's get started" below you will be taken to your membership account home page, click on <?php echo $membershipName;?> on the navigation pane to begin setting up your <?php echo $membershipName;?> Accounts.
    </div>
    <div class="contact_info" >
    	<span class="bold" >Please contact us with any questions</span><br />
        support@spotlighthometours.com<br />
        801.466.4074<br />
        888.838.8810
    </div>
    <div class="button_new button_blue button_mid" onclick="window.location = '../users/new/'">
        <div class="curve curve_left" ></div>
        <span class="button_caption" >Let's get started!</span>
        <div class="curve curve_right" ></div>
    </div>
</div>