 using JBoss, specify the keystore
location as $JAVA_HOME/jre/lib/security/cacerts.</p>
</div></div><div id="WS5A4BEE63-DBAB-458a-B2E2-956A0D7FEEA3" class="nochunk"><a name="WS5A4BEE63-DBAB-458a-B2E2-956A0D7FEEA3"><!-- --></a><h3 class="topictitle3">Enable SSL in the ColdFusion Administrator</h3><div><ol><li><p>Select Data &amp; Services &gt; Flex Integration,
and specify the keystore file in the Full Path To Keystore box.</p>
</li>
<li><p>Specify the keystore password in the Keystore Password box.</p>
</li>
<li><p>Select Enable RMI Over SSL For Data Management, and then
click Submit Changes.</p>
</li>
</ol>
<p>If you specify an invalid keystore file or password, ColdFusion
does not enable SSL, and disables LiveCycle Data Management Support.</p>
</div></div></div>


<!-- BEGIN USER PREFERENCES -->
        <div id="userprefs">
        </div>
<!-- END USER PREFERENCES -->

        <div id="related">
          <div class="separator"><a href="#top"><img src="images/BTT.jpg" /></a>&#160;</div>
          
          
        </div>
        <div id="footer">
          <p><img src="images/Community.png" />&#160;<a title="community help (adobe.com)" href="http://www.adobe.com/go/lr_ColdFusion_community_en">community help (adobe.com)</a></p>
<!-- BEGIN IONCOMMENTS -->
          <div id="ionComHere">
          </div>
<!-- END IONCOMMENTS -->
<!--#include virtual="ionComments.ssi" -->
          <p id="creativecommons"><a href="http://creativecommons.org/licenses/by-nc-sa/3.0/" id="creativecommons_text"><img id="creativecommons_img" src="images/CC.png" alt="This work is licensed under a Creative Commons Attribution-Noncommercial-Share Alike 3.0 Unported License" /></a></p>
        </div>
      </div>
<!-- BEGIN BREADCRUMBS -->
      <div id="breadcrumb">
        
<ul class="navigation"><li class="prev"><a accesskey="p" class="prev" href="WSc3ff6d0ea77859461172e0811cbf3638e6-7ffc.html" title="Server Settings section"><img src="images/blank.gif" alt="Previous" width="17" height="17" /></a></li><li class="next"><a accesskey="n" class="next" href="WSc3ff6d0ea77859461172e0811cbf3638e6-7fe0.html" title="Debugging &amp; Logging section"><img src="images/blank.gif" alt="Next" width="17" height="17" /></a></li></ul><div class="hierarchy" id="hierarchy"><a href="WSc3ff6d0ea77859461172e0811cbf364104-8000.html"><b>Using the ColdFusion Administrator</b></a> 
     </div>

      </div>
<!-- END BREADCRUMBS -->
<!-- END CONTENT WRAPPER -->
<!--#include virtual="contentfooter.ssi" -->
</td>
</tr>
</table>
    </div>
<!-- END PAGE CONTENT WRAPPER -->
<script type="text/javascript">
<!--[CDATA[
scrollToNameAnchor();
// ]]-->
</script> 
<!--#include virtual="/en_US/ssi/productmenu.ssi" -->
<!--#include virtual="/ssi/globalfooter.ssi" -->
<!--#include virtual="/en_US/ssi/localfooter.ssi" -->
<!--#include virtual="footer.ssi" -->
<!--#include virtual="/ubi/analytics/analytics_ssi.html" -->
  </body>
</html>                                                                                      ariable);
$t_last = gettext('last');
$t_days = gettext('days');

$gdg = new Graph(250, 250, "$t_variable ($t_last $days_word $t_days)",
    255, 255, 255,
    0, 0, 0,
    0, 0, 0,
    false);

$gdg->bar_graph($data, '', '', $days_back == 7 ? 80 : 100, 5);
