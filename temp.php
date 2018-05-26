<?php
/**
 * LOGIC PORTION
 * 
 * This is where the actual soap query takes place
 */
    # SETUP VARIABLES
    $site		= 'http://xml.wfrmls.com';       // The xml server
    $script     = '/service.php';                   // The script to talk to
    $connectParams = array(
        'location'      => $site . $script,
        'uri'           => 'http://www.spotlighthometours.com/',
        'exceptions'    => 0
    );

    $method		= 'searchres';                      // The remote method to invoke
    $methodParams = array(                          // This sets up our hash to send in as the request
        new SoapParam('4ef1477dc99fb623fd5d06dc4b26d1aa', 'dev-tag'),
        new SoapParam('2011_03_01', 'service-version'),
         new SoapParam('long', 'report-type'),
        new SoapParam($_GET['listno'], 'listingid'),
        new SoapParam('all', 'report-struct')
    );


   

    # SETUP SOAP CLIENT
    $client = new SoapClient(null, $connectParams);

    # CALL SOAP METHOD, in production we recommend you catch the returned exception
    $response = $client->__soapCall($method, $methodParams);

    # GRAB DATA OF RESPONSE
    $totalCount     = $response[0];
    $currentPage    = $response[1];
    $totalPages     = $response[2];
    $searchTime     = $response[3];
    $homes          = $response[4];
    
/**
 * VIEW PORTION
 *
 * This is where the view/template portion takes place, showing in html
 */
?>
<HTML>
    <HEAD>
        <TITLE>Sample SOAP1.1 Code - Residential Details</TITLE>
    </HEAD>
    <BODY>
        <?php foreach ($homes as $homeObj):?>
            <? $home = get_object_vars($homeObj); // SoapClient puts stuff in stdclass  ?>
            <TABLE border=0 align=center cellpadding=8 cellspacing=0>
                <TR>
                    <TD align=center valign=top>
                        <IMG SRC="<?= $home['primaryphotos'][2] ?>" BORDER="0" />
                        <BR />
                        <B>MLS #<?= $home['listingid'] ?></B>
                    </TD>
                    <TD valign=top>
                        <STRONG><?= $home['city'] . ', ' . $home['county'] ?></STRONG>
                        <BR />
                        <?= $home['housenum'] . ' ' . $home['street'] . ' ' . $home['streettype'] . ' ' . $home['unit'] ?>
                        <BR />
                        <? if ($home['dirpre'] == 'E' || $home['dirpre'] == 'W'): ?>
                            <?= $home['coordew'].' '.$home['dirpre'].' '.$home['coordns'].' '.$home['dirpost']."<br>" ?>
                        <? elseif($home['dirpre'] == 'N' || $home['dirpre'] == 'S'): ?>
                            <?= $home['coordns'].' '.$home['dirpre'].' '.$home['coordew'].' '.$home['dirpost']."<br>" ?>
                        <? endif; ?>
                        $<?= number_format($home['listprice']) ?>
                        <BR />
                        <STRONG>
                            <?= $home['state'] ?>,
                            <I>(<?= $home['zip'] ?>)</I>
                        </STRONG>
                        <P>&nbsp;</P>
                        <TABLE border=0>
                            <TR>
                                <TH align=right>Type&nbsp;/&nbsp;Style:</TH>
                                <TD><?= $home['proptype'] . ' / ' . $home['style'] ?></TD>
                            </TR>
                            <TR>
                                <TH align=right>Year&nbsp;Built:</TH>
                                <TD><?= $home['yearblt'] ?>
                            </TR>
                            <TR>
                                <TD colspan=2><HR></TD>
                            </TR>
                            <TR>
                                <TH align=right>Agent:</TH>
                                <TD><?= $home['agtfirst'] . ' ' . $home['agtinitial'] . ' ' . $home['agtlast'] ?></TD>
                            </TR>
                            <TR>
                                <TH align=right>Phone:</TH>
                                <TD><I><?= $home['agtphone1'] ?></I></TD>
                            </TR>
                            <TR>
                                <TH align=right>Email:</TH>
                                <TD><I><?= $home['agtemail'] ?></I></TD>
                            </TR>
                            <TR>
                                <TD colspan=2><HR></TD>
                            </TR>
                            <TR>
                                <TH align=right>Office:</TH>
                                <TD><?= $home['offname'] ?></TD>
                            </TR>
                            <TR>
                                <TH align=right>Phone:</TH>
                                <TD><I><?= $home['offphone1'] ?></I></TD>
                            </TR>
                        </TABLE>
                    </TD>
                </TR>
                <TR>
                    <TD valign=top WIDTH="50%">
                        <B>&nbsp;&nbsp;Interior Features Include:</B>
                        <UL>
                            <LI><?= $home['featuresint'] ?>
                            <LI>Window Cov: <?= $home['window'] ?>
                            <LI>Air Cond: <?= $home['aircon'] ?>
                            <LI>Heating: <?= $home['heating'] ?>
                            <LI>Floor Type: <?= $home['floor'] ?>
                            <LI>Basement: (<?= $home['basmntfin'] ? $home['basmntfin'] : 0 ?>% Finished) <?= $home['basement'] ?>
                        </UL>
                    </TD>
                    <TD valign=top WIDTH="50%">
                        <B>&nbsp;&nbsp;Rooms Include:</B>
                        <UL>
                            <LI><B><?= $home['totbed'] ?> Total Bedrooms:</B>
                                <UL>
                                    <? if ($home['lev0bed']): ?>
                                        <LI><?='Bsmnt: '.$home['lev0bed'] ?></LI>
                                    <? endif; ?>
                                    <? for ($i = 1; $i <= 4; $i++): ?>
                                        <? if ($home['lev' . $i . 'bed']): ?>
                                            <LI><?='Flr ' . $i . ': '.$home['lev' . $i . 'bed'] ?></LI>
                                        <? endif; ?>
                                    <? endfor; ?>
                                </UL>
                            </LI>
                            <LI>
                                <STRONG><?= number_format($home['totbath']) ?> Total Bathrooms:</STRONG>
                                <UL>
                                    <? if ($home['lev0bath']): ?>
                                        <LI><?='Bsmnt: '.number_format($home['lev0bath']) ?></LI>
                                    <? endif; ?>
                                    <? for ($i = 1; $i <= 4; $i++): ?>
                                        <? if ($home['lev' . $i . 'bathfull']): ?>
                                            <LI><?='Flr ' . $i . ': '. $home['lev' . $i . 'bathfull']. ' Full'?></LI>
                                        <? endif; ?>
                                        <? if ($home['lev' . $i . 'bathhalf']): ?>
                                            <LI><?='Flr ' . $i . ': '. $home['lev' . $i . 'bathhalf']. ' Half'?></LI>
                                        <? endif; ?>
                                        <? if ($home['lev' . $i . 'bath3qrt']): ?>
                                            <LI><?='Flr ' . $i . ': '. $home['lev' . $i . 'bath3qrt']. ' 3Qrt'?></LI>
                                        <? endif; ?>
                                    <? endfor; ?>
                                    
                                </UL>
                            </LI>
                        </UL>
                    </TD>
                </TR>
                <TR>
                    <TD colspan=2><HR></TD>
                </TR>
                <TR>
                    <TD valign=top WIDTH="50%">
                        <B>&nbsp;&nbsp;Exterior Features Include:</B>
                        <UL>
                            <LI>Exterior: <?= $home['featuresext'] ?>
                            <LI>Lot: <?= $home['lotfacts'] ?>
                            <LI>Landscape: <?= $home['landscape'] ?>
                            <LI>Roof: <?= $home['roof'] ?>
                            <LI>Exterior: <?= $home['exterior'] ?>
                            <LI>Wood Deck: <?= $home['deck'] ?>
                            <LI>Patio: <?= $home['patio'] ?>
                            <LI>Garage Type: <?= $home['garage'] ?>
                            <LI>Garage Capacity: <?= $home['capgarage'] ?>
                            <LI>Carport Capacity: <?= $home['capcarport'] ?>
                        </UL>
                    </TD>
                    <TD valign=top WIDTH="50%">
                        <B>&nbsp;&nbsp;Square feet on each floor:</B>
                        <UL>
                            <? if ($home['lev0sqf']): ?>
                                <?='<li>Bsmnt: '. $home['lev0-sqf'] ?>
                            <? endif; ?>
                            <? for ($i = 1; $i <= 4; $i++): ?>
                                <? if ($home['lev'.$i.'sqf']): ?>
                                    <LI><?='Flr ' . $i . ': '. $home['lev' . $i . 'sqf']?></LI>
                                <? endif; ?>
                            <? endfor; ?>
                            <LI>Total: <?= $home['totsqf'] ?>
                        </UL>
                        <HR>
                        <B>&nbsp;&nbsp;Local Schools <I>(<?= $home['schooldistrict'] ?> District)</I>:</B>
                        <UL>
                            <LI><B>Elementary:</B> <?= $home['schoolelem'] ?></LI>
                            <LI><B>Jr High:</B> <?= $home['schooljunior'] ?></LI>
                            <LI><B>High School:</B> <?= $home['schoolsenior'] ?></LI>
                            <LI><B>Private:</B> <?= $home['schoolprivate'] ?></LI>
                            <LI><B>Other:</B> <?= $home['schoolother'] ?></LI>
                        </UL>
                    </TD>
                </TR>
                <TR>
                    <TD colspan=2><HR></TD>
                </TR>
                <TR>
                    <TD valign=top WIDTH="50%">
                        <B>&nbsp;&nbsp;Other Features Include:</B>
                        <UL>
                            <LI>Inclusions: <?= $home['inclusions'] ?></LI>
                            <LI>Amenities: <?= $home['amenities'] ?></LI>
                            <LI>Water: <?= $home['water'] ?></LI>
                            <LI>Utilities: <?= $home['utilities'] ?></LI>
                            <LI>Pool: <?= $home['pool'] ?></LI>
                        </UL>
                        <HR>
                        <B>&nbsp;&nbsp;Zoning Info:</B>
                        <UL>
                            <LI><?= $home['zoningchar'] ?></LI>
                        </UL>
                    </TD>
                    <TD valign=top WIDTH="50%">
                        <B>&nbsp;&nbsp;Total Size in Acres:</B>
                        <UL>
                            <LI><?= number_format($home['dimacres'], 2) ?>
                        </UL>
                    </TD>
                </TR>
            </TABLE>
            <BR /><BR /><BR />
        <? endforeach; ?>
        <? if ($totalCount > 0): ?>
            <DIV align=center><SMALL>Query Took: <?= $searchTime ?> seconds</SMALL></DIV>
        <? endif; ?>
    </BODY>
</HTML>
