<?php
/**********************************************************************************************
Document: about/
Creator: Jacob Edmond Kerr
Date: 03-07-12
Purpose: SpotlightHomeTours.com - About Us
**********************************************************************************************/
error_reporting(-1);
ini_set('display_errors',1);
//=======================================================================
// Includes
//=======================================================================

	// HEADER TEMPLATE
	$title = 'About Spotlight Home Tours';
	$header = '<link rel="stylesheet" type="text/css" href="../repository_css/splash.css"/>
<link rel="stylesheet" type="text/css" href="../repository_css/about.css"/>
<script src="../repository_inc/about.js"/></script>
';
	require_once('../repository_inc/template-header.php');
	


//=======================================================================
// A dirty hack until we get better sized images
//=======================================================================
$lrgWidth = ' width=300 ';
$jokes = (isset($_GET['jokes']) ? 1 : 0 );

//=======================================================================
// Document
//=======================================================================
	
?>
	<div class="page-intro">
		<h1>About Us</h1>
		<p><!-- <div class="meet-photographers">
        	<a href="#">Meet the Photographers</a>
        </div> -->
		Photography and video production is our passion!  While searching for a new home one of the founders of Spotlight Home Tours was struck by how poorly homes were being visually represented.  We know it can be done better.  A love for photography and video production runs deep throughout our company including the management, accounting, marketing and editing departments. We were founded with a personal passion for good imagery and presenting properties in the best light possible.  Our commitment is to share this passion with everyone, which is why we strive to provide the highest quality imaging services in the country while keeping it affordable and attainable.  
		<div class="clear"></div>
    </div>
    <!-- TEAM THUMB PIC, NAME, CAPTION -->
    <div class="the-team">
    	<h2>Ownership Group</h2>
        <div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/bret-peterson.jpg" width="184" height="184" alt="Bret Peterson - Owner" /></div>
            <div class="name">Bret Peterson</div>
            <div class="lbl">CEO &amp; Partner</div>
        </div>


<?php
	/*
        <div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/lance-may.jpg" width="184" height="184" alt="Lance May - Senior Vice President" /></div>
            <div class="name">Lance May</div>
            <div class="lbl">Senior VP & Partner</div>
        </div>
*/
?>
        <div class="clear"></div>



        <h2>Management Team</h2>
        <div class="team-member">
        <div class="photo"><img src="../repository_images/new/about/headshots/ben-lawhorn.jpg" width="184" height="184" alt="Ben Lawhorn - Editing" /></div>
            <div class="name">Ben Lawhorn</div>
            <div class="lbl">Video Production Manager</div>
        </div>
		<div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/randy-shankula.jpg" width="184" height="184" alt="Randy Shankula - Director Of Photography" /></div>
            <div class="name">Randy Shankula</div>
            <div class="lbl">Director Of Photography</div>
        </div>
        <div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/jeff-sylvester.jpg" width="184" height="184" alt="Jeff Sylvester - Graphic Design" /></div>
            <div class="name">Jeff Sylvester</div>
            <div class="lbl">Graphic Art Manager</div>
        </div>   
        <div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/alecia-bergeron.jpg" width="184" height="184" alt="Alecia Bergeron" /></div>
            <div class="name">Alecia Bergeron</div>
            <div class="lbl">Quality Control Manager</div>
        </div>
          
           
        <div class="clear"></div>
        <h2>Corporate Office</h2>


        <div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/cynthia-westfall.jpg" width="184" height="184" alt="Cynthia Westfall - Scheduling" /></div>
            <div class="name">Cynthia Westfall</div>
            <div class="lbl">Scheduling</div>
        </div>
		<div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/brianna-naylor.jpg" width="184" height="184" alt="Brianna Naylor - Scheduling" /></div>
            <div class="name">Brianna Naylor</div>
            <div class="lbl">Scheduling</div>
        </div>
		<div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/stefan-casper.jpg" width="184" height="184" alt="Stefan Casper - Scheduling" /></div>
            <div class="name">Stefan Casper</div>
            <div class="lbl">Scheduling</div>
        </div>
		<div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/andrew-simeona.jpg" width="184" height="184" alt="Andrew Simeona - Sales" /></div>
            <div class="name">Andrew Simeona</div>
            <div class="lbl">Sales</div>
        </div>
		<div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/sammy-walker.jpg" width="184" height="184" alt="Sammy Walker - Sales" /></div>
            <div class="name">Sammy Walker</div>
            <div class="lbl">Sales</div>
        </div>
		<div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/female-avatar.jpg" width="184" height="184" alt="Jessica Avalos - Executive Assistant" /></div>
            <div class="name">Jessica Avalos</div>
            <div class="lbl">Executive Assistant</div>
        </div>
        <div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/delaney-chlarson.jpg" width="184" height="184" alt="Delaney Chlarson - Editing" /></div>
            <div class="name">Delaney Chlarson</div>
            <div class="lbl">Editing</div>
        </div>
        <div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/melissa.jpg" width="184" height="184" alt="Melissa Worthen - Editing" /></div>
            <div class="name">Melissa Worthen</div>
            <div class="lbl">Editing</div>
        </div>
		<div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/female-avatar.jpg" width="184" height="184" alt="Mengxi Li - Editing" /></div>
            <div class="name">Mengxi Li</div>
            <div class="lbl">Editing</div>
        </div>
		<div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/alexis-scheering.jpg" width="184" height="184" alt="Alexis Scheering - Editing" /></div>
            <div class="name">Alexis Scheering</div>
            <div class="lbl">Editing</div>
        </div>
		<div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/jason-murray.jpg" width="184" height="184" alt="Jason Murray - Editing" /></div>
            <div class="name">Jason Murray</div>
            <div class="lbl">Editing</div>
        </div>
		<div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/tanner-shahan.jpg" width="184" height="184" alt="Tanner Shahan - Editing" /></div>
            <div class="name">Tanner Shahan</div>
            <div class="lbl">Editing</div>
        </div>
            <div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/sheri-peterson.jpg" width="184" height="184" alt="Sheri Peterson - Accounting" /></div>
            <div class="name">Sheri Peterson</div>
            <div class="lbl">Accounting</div>
        </div>
        <div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/eileen-valdez.jpg" width="184" height="184" alt="Eileen Valdez - Accounting" /></div>
            <div class="name">Eileen Valdez</div>
            <div class="lbl">Accounting</div>
        </div>
        <!--<div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/male-avatar.jpg" width="184" height="184" alt="Son Duong - Developer" /></div>
            <div class="name">Matt Snow</div>
            <div class="lbl">Developer</div>
        </div>-->
        <!--<div class="team-member">
        	<div class="photo"><img src="../repository_images/new/about/headshots/jacob-kerr.jpg" width="184" height="184" alt="Jacob Kerr - Developer" /></div>
            <div class="name">Jacob Kerr</div>
            <div class="lbl">Developer</div>
        </div>-->
        <div class="clear"></div>
    </div>





    <!-- END TEAM THUMB PIC, NAME, CAPTION -->



    <!-- TEAM BIO POPUPS -->
    <!-- KARIANN HASKELL -->
    <div class="team-bio-popup" id="kariann-haskell-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img <?php echo $lrgWidth; ?> src="../repository_images/new/about/headshots/lrg/kari-ann-haskellf.jpg" alt="Kariann Haskell - Editing" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Kariann Haskell</h2>
                <div class="bio-txt">
                	<h4>Editing</h4>
<p>
Coming soon...
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">kariann@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
	</div>
    <!-- MIRANDA KIMBERLIN -->
    <div class="team-bio-popup" id="miranda-kimberlin-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img <?php echo $lrgWidth; ?> src="../repository_images/new/about/headshots/lrg/miranda-kimberlin.jpg" alt="Miranda Kimberlin - Editing" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Miranda Kimberlin</h2>
                <div class="bio-txt">
                	<h4>Editing</h4>
<p>
Coming soon...
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">miranda@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
    </div>
    <!-- ASHLEY BURGE -->
    <div class="team-bio-popup" id="ashley-burge-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img <?php echo $lrgWidth; ?> src="../repository_images/new/about/headshots/lrg/ashley-burge.jpg" alt="Ashley Burge - Affiliate Liaison” /></div>
            <div class="team-bio-txt"">
            	<h2 class="team-member-name">Ashley Burge</h2>
                <div class="bio-txt">
                	<h4>Affiliate Liaison</h4>
<p>
Coming soon...
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">ashley@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
    </div>
    <!-- BEN LAWHORN -->
    <div class="team-bio-popup" id="tyrell-masterson-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img <?php echo $lrgWidth; ?> src="../repository_images/new/about/headshots/male-avatar.jpg" alt="Tyrell Masterson - Customer Service" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Tyrell Masterson</h2>
                <div class="bio-txt">
                	<h4>Customer Service</h4>
<p>
Coming soon...
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">tyrell@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
    </div>
    <!-- BEN LAWHORN -->
    <div class="team-bio-popup" id="ben-lawhorn-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img <?php echo $lrgWidth; ?> src="../repository_images/new/about/headshots/male-avatar.jpg" alt="Ben Lawhorn - Video Production" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Ben Lawhorn</h2>
                <div class="bio-txt">
                	<h4>Video Production</h4>
<p>
Coming soon...
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">ben@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
    </div>
    <!-- KYLE -->
    <div class="team-bio-popup" id="kyle-wilcox-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img <?php echo $lrgWidth; ?> src="../repository_images/new/about/headshots/male-avatar.jpg" alt="Kyle Wilcox - Video Production" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Kyle Wilcox</h2>
                <div class="bio-txt">
                	<h4>Video Production</h4>
<p>
Coming soon...
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">kyle@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
    </div>
    <!-- ISIL -->
    <div class="team-bio-popup" id="isil-hessick-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img <?php echo $lrgWidth; ?> src="../repository_images/new/about/headshots/lrg/isil.jpg" alt="Isil Hessick - Video Production and Editing" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Isil Hessick</h2>
                <div class="bio-txt">
                	<h4>Video Production and Editing</h4>
<p>
Film major, storyteller, mom. Takes great pride in her editing, re-editing and re-reediting and… Loves coffee, dark chocolate and humor if possible all at the same time. Has a slight accent. Known to forcefully feed her co-workers and sometimes total strangers. When not editing, looking for her son's socks, picking up small legos from the most unlikely places, negotiating with her daughter at dinner time, homework time or pretty much any other time, she actually writes. 
<br><br>
A human. Being. 
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">isil@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
    </div>
    <div class="modal-bg"></div>
    <!-- BRET PETERSON -->
    <div class="team-bio-popup" id="bret-peterson-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/bret-peterson.jpg" alt="Bret Peterson" /></div>
            <div class="team-bio-txt">
                <h2 class="team-member-name">Bret Peterson</h2>
               	<div class="bio-txt">
                    <h4>Owner</h4>
                    <p>While looking to purchase a new home, Bret and his wife quickly learned that if they did not find a listing that included photos they did not want to waste time searching. One of the homes they did decide to take a look at had a photo tour online but was so poorly photographed that when they did get out to seeing the house they were surprised at how the photos had misrepresented the home. Bret had been shooting photography since he was 17 and the light went off, he started Spotlight Home Tours in 2003 and has been creating the best photos and videos offered to the Real Estate market ever since. While not spending time at Spotlight Bret spends time with his wife and family in Utah enjoying hobbies like Fishing, Camping and of course Photography.</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">bret@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin" title="My LinkedIn"></div>
                <div class="icon facebook" title="My Facebook"></div>
                <div class="icon video" title="My YouTube"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>
    <!-- SIMONE  -->
    <div class="team-bio-popup" id="simone-tilley-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/simone.jpg" alt="Simone Tilley" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Simone Tilley</h2>
                <div class="bio-txt">
                	<h4>Editing</h4>
<p>
<?php if( $jokes ): ?>
Ariel is a fictional character and the title character of Walt Disney Pictures' 28th animated film The Little Mermaid (1989). She subsequently appears in the film's prequel television series, direct-to-video sequel The Little Mermaid II: Return to the Sea (where her daughter takes over as the lead character, while Ariel is a secondary character instead) and direct-to-video prequel The Little Mermaid: Ariel's Beginning. Ariel is voiced by Jodi Benson in all official animated appearances and merchandise. She is fourth in the Disney Princess lineup, and the only princess to become a mother to her own child.
<?php endif; ?>
Bio coming soon...
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">simone.falls.ut@gmail.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>

    
    <!-- WILL -->
    <div class="team-bio-popup" id="william-merfalen-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img <?php echo $lrgWidth;?> src="../repository_images/new/about/headshots/lrg/william.jpg" alt="Will Merfalen" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">William Merfalen</h2>
                <div class="bio-txt">
                	<h4>Web Development</h4>
<p>
William is an integral part of the Spotlight team, bringing in 5+ years of professional web development experience. His analytical     mind and capability for creative problem solving make him a valued member here at Spotlight. Born and raised in sunny San Diego, his personal life reflects that of an introvert as he codes not only at work but also at home over the weekends and at nights. He just loves code <i>that</i> much. :)
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">william@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>
    <!-- CHRIS -->
    <div class="team-bio-popup" id="chris-oakley-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/chris.jpg" alt="Chris Oakley" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Chris Oakley</h2>
                <div class="bio-txt">
                	<h4>Editing</h4>
                    <p>Bio coming soon...</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address"></h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
    </div>

    <!-- JONATHON -->
    <div class="team-bio-popup" id="jonathon-holmgren-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/jonathon.jpg" alt="Chris Oakley" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Jonathon Holmgren</h2>
                <div class="bio-txt">
                	<h4>Editing</h4>
<p>
<?php if($jokes): ?>
Jonathon Holmgren (born July 6, 1975), better known by his stage name 25 Cent, is an American rapper, entrepreneur, investor, and actor from New York City, New York. He rose to fame with the release of his albums Get Rich or Die Tryin' (2003) and The Massacre (2005). 25 Cent also gained prominence with East Coast hip hop group G-Unit, of which he is the de facto leader.
<?php endif; ?>

Bio coming soon...
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address"></h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
    </div>

    <!-- Melissa -->
    <div class="team-bio-popup" id="melissa-worthen-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img <?php echo $lrgWidth; ?> src="../repository_images/new/about/headshots/lrg/melissa.jpg" alt="Melissa Worthen" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Melissa Worthen</h2>
                <div class="bio-txt">
                	<h4>Editing</h4>
<p>
<?php if($jokes): ?>
Melissa Worthen (born November 27, 1955), popularly known as Melissa Worthen the Science Girl, is an American science educator, comedian, television host, actor, writer, scientist and former mechanical engineer, best known as the host of the Disney/PBS children's science show Bill Nye the Science Guy (1993–98) and for his many subsequent appearances in popular media as a science educator.
<?php endif; ?>
Bio coming soon...
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address"></h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
    </div>

    <!-- tyler -->
    <div class="team-bio-popup" id="tyler-beck-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img <?php echo $lrgWidth; ?> src="../repository_images/new/about/headshots/lrg/tyler-beck.jpg" alt="Tyler Beck" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Tyler Beck</h2>
                <div class="bio-txt">
                	<h4>Video Production Manager</h4>
                    <p>Bio coming soon...</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">tyler@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
    </div>

    <!-- AMANDA -->
    <div class="team-bio-popup" id="amanda-maher-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img <?php echo $lrgWidth; ?> src="../repository_images/new/about/headshots/lrg/amanda-maher.jpg" alt="Amanda Maher" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Amanda Maher</h2>
                <div class="bio-txt">
                	<h4>Affiliate Coordinator</h4>
                    <p>Bio coming soon...</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">amanda@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
    </div>

    <!-- Chris Lee -->
    <div class="team-bio-popup" id="chris-lee-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="Chris Lee" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Chris Lee</h2>
                <div class="bio-txt">
                	<h4>Customer Service</h4>
                    <p>Bio coming soon...</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">chrislee@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
       </div>
    </div>

<?php 
/*
    <!-- LANCE MAY 
    <div class="team-bio-popup" id="lance-may-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/lance-may.jpg" alt="Lance May" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Lance May</h2>
                <div class="bio-txt">
                	<h4>Senior Vice President</h4>
                	<p>Lance began his real estate career in 1997 at the age of 23 and quickly established himself as the industry leader in the sales and marketing of new home communities along the Wasatch Front. Over the next 15 years his innovative mindset provided the successful sell out to more than 32 developments in the State of Utah and numerous sales and marketing awards on a local and national stage. Hailing from the Pacific Northwest, and then residing in Utah for 16 years, Lance and his wife Amy felt it was time for a change from the mountain lifestyle, so they moved with their 3 sons to the North Shore of Kauai. During this time Lance established a solid operation for Spotlight Home Tours in the Hawaiian Islands. Having an ownership interest in Spotlight offers Lance a very unique perspective on video production and methods to effectively market and showcase real estate. He has personally produced and filmed real estate videos for properties owned by Julia Roberts and Pierce Brosnan, as well as for owners whose properties were used in the filming of Pirates of the Caribbean and Jurassic Park. He most recently turned 40, and though we tell him that 40 is the new 30, he needs no encouragement to keep going.</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">lance@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon linkedin"></div>
                <div class="icon facebook"></div>
                <div class="icon video"></div>
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>
-->
*/
?>
    
    
    <!-- PHIL LLOYD -->
    <div class="team-bio-popup" id="phil-lloyd-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/phil-lloyd.jpg" alt="Phil Lloyd" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Phil Lloyd</h2>
                <div class="bio-txt">
                	<h4>Office Manager</h4>
               		<p>Phil is the Controller/Office Manager for Spotlight Home Tours. He has a professional background in cost accounting and office management in both home-building and commercial construction. Living in Salt Lake his entire life and being an avid skier and outdoors man, he has always had a love and appreciation for great photography. When the opportunity was presented to him, he was thrilled to become a part of the Spotlight team and contribute his experiences to promote the success of the company.</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">plloyd@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>
    
    <!-- BRIANA MALORY -->
    <div class="team-bio-popup" id="briana-mallory-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/briana-mallory.jpg" alt="Briana Mallory" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Briana Mallory</h2>
                <div class="bio-txt">
                	<h4>Managing Assistant</h4>
                	<p>Briana is the Affiliate Liaison and Executive Assistant for the ownership group with Spotlight Home Tours. She has an extensive background in real estate and has a firm understanding of what an agents needs are. Briana enjoys the energetic and creative atmosphere at Spotlight and is excited to be a part of the growth and success of the company. Briana has traveled a lot and lived in 5 different states, but with hobbies like skiing, hiking and camping Utah seems to be a great fit for her. She also love spending time with her family and two dogs.</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">briana@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>

    <!-- Amy Hunter -->
    <div class="team-bio-popup" id="amy-hunter-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/amy-hunter.jpg" alt="Amy Hunter" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Amy Hunter</h2>
                <div class="bio-txt">
                	<h4>Photo Production Manager</h4>
<p>
<?php if($jokes):?>
Amy is an American editor/graphic designer. Raised in Wyomissing, Pennsylvania, Amy moved to Nashville, Tennessee, at the age of 14 to pursue a career in photoshopping cats faces to human bodies. She joined Spotlight and became the youngest editor ever hired. The release of Amy's eternal rage in 2006 established her as a brutally honest management figure. At Spotlight she became the youngest person to single-handedly manage an entire department full of ex-felons.
<?php endif; ?>
Bio coming soon...
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">famousamousa@yahoo.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>
    
    
    <!-- Jacob Kerr -->
    <div class="team-bio-popup" id="jacob-kerr-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/jacob-kerr.jpg" alt="Jacob Kerr" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Jacob Kerr</h2>
                <div class="bio-txt">
                	<h4>Web Development</h4>
                	<p>Jacob has been working in the web design, development and internet marketing field for over 13 years. With an extensive background as the lead web design and development engineer at some of Utah's most prestigious web design firms Jacob has has developed a strong set of skills. With Spotlight and Jacob's insatiable hunger for the technological incline Spotlight is consistently improving their online and offline operations. Jacob loves all forms of art whether it's cooking Italian food, designing and developing new concepts, pushing a new market or just simply going for a hike and listening to music he does it all with a smile on his face.</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">jacob@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>
    
	<!-- Alex Linsley -->
    <div class="team-bio-popup" id="alex-linsley-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/alex-linsley.jpg" alt="Alex Linsley" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Alex Linsley</h2>
                <div class="bio-txt">
                	<h4>Scheduling</h4>
                	<p>
Alex has been employed at Spotlight Home Tours as a scheduling coordinator for a little over a year. She is currently attending the University of Utah and is planning on graduating in Decemeber with a degree in Strategic Communications. In her spare time she loves being with her friends and family, as well as volunteering at the Best Friends Animal Shelter.
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">alex@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>
    
    
    <!-- Teisha Ahlstrom -->
    <div class="team-bio-popup" id="teisha-ahlstrom-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/teisha-ahlstrom.jpg" alt="Teisha Ahlstrom" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Teisha Ahlstrom</h2>
                <div class="bio-txt">
                	<h4>Scheduling</h4>
                	<p>Bio coming soon...</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">swaydehead@gmail.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>
    
    <!-- EILEEN VALDEZ -->
    <div class="team-bio-popup" id="eileen-valdez-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/eileen-valdez.jpg" alt="Eileen Valdez" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Eileen Valdez</h2>
                <div class="bio-txt">
                	<h4>Accounting</h4>
                	<p>I was born in Chicago IL , raised in Denver CO and I am a big fan of the Denver Broncos!!! I have an identical twin sister.  I came to work for Spotlight Home tours in June of 2011. I enjoy working with people. I enjoy spending time with my children.</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">eileen@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>
    
    <!-- SHERI PETERSON -->
    <div class="team-bio-popup" id="sheri-peterson-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/sheri-peterson.jpg" alt="Sheri Peterson" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Sheri Peterson</h2>
                <div class="bio-txt">
                	<h4>Accounting</h4>
                	<p>Sheri is Bret Peterson's mother so she has had a natural passion for the company since she first heard about the idea and concept. She worked in the automobile industry as a controller/office manager for 26 years, and after long dedicated hours and stressful situations she loves being part of the Spotlight today as a member of the Accounting Department today. Sheri is amazed by the work of our photographers and editors and enjoys watching the Video and Cinematic Tours. Outside of work She enjoys spending time with grandchildren and being crafty.</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">sheri@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>
    
    <!-- JOSH RUSHTON -->
    <div class="team-bio-popup" id="josh-rushton-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/josh-rushton.jpg" alt="Josh Rushton" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Josh Rushton</h2>
                <div class="bio-txt">
                	<h4>Editing</h4>
                	<p>Bio coming soon...</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">jjrush87@gmail.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>
    
    <!-- HEATHER MORLEY -->
    <div class="team-bio-popup" id="heather-morley-bio">
    	<div class="team-bio">
        	<div class="close"></div>
            <div class="photo"><img src="../repository_images/new/about/headshots/lrg/heather.jpg" alt="Heather Morley" /></div>
            <div class="team-bio-txt">
            	<h2 class="team-member-name">Heather Morley</h2>
                <div class="bio-txt">
                	<h4>Tour Coordinator</h4>
			<p>
Heather has been employed with Spotlight Home Tours since 2007. She appreciates architecture, and as Spotlight's Tour Coordinator, enjoys viewing the amazing properties showcased through Spotlight's website. She is a Utah native and attended school at Weber State University, USU and SLCC.   Heather is a jack of all trades and has many hobbies which include photography, cooking, music, hiking, family history research, arts & crafts, crochet/knitting, gardening, piano, guitar and biking.  She grew up in a small town and prefers the country life, but loves to travel and see other parts of the world. She has an affinity for the vintage world, so has often been referred to as an "old soul" who was born in the wrong era.  
</p>
            	</div>
                <div class="email-form">
                    <h4 class="email-address">heather@spotlighthometours.com</h4>
                    <div id="emailTMMsg"></div>
                    <form class="contact-form">
                        <label style="margin-top:0px;">From:</label>
                        <input name="from_email" value="Your email address" class="hint-text" onfocus="if(this.value=='Your email address'){this.className='';this.value=''}" />
                        <label>Message:</label>
                        <textarea name="message" rows="10" class="hint-text" onfocus="if(this.value=='Your message'){this.className='';this.value=''}">Your message</textarea>
                        <input type="button" class="submit-btn" value="Send Message">
                    </form>
                </div>
            </div>
            <div class="icons">
                <div class="icon email" title="Email Me"></div>
            </div>
        </div>
    </div>
    <!-- END TEAM BIO POPUPS -->
    <!-- <div class="join-us">
    	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod. <a href="#">Join our team!</a>
    </div> -->
    <p>&nbsp;</p>
    <p>&nbsp;</p>
	<div class="image-reel-pattern"></div>
<?PHP
	if(isset($_REQUEST['memberName'])){
?>

	<script>
		showBio('<?php echo $_REQUEST['memberName'] ?>');
	</script>
<?PHP
	}
	// FOOTER TEMPLATE
	require_once('../repository_inc/template-footer.php');
?>
