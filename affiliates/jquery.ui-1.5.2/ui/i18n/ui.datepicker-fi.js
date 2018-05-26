				"Good exposures!",
				"Great composition!"
			);
?>				<TR> 
				<TD><INPUT type="radio" name="shootType" value="Initial" <?php if($Progress['VideoReIsNull']) echo"checked=checked";?>>Initial Shoot</TD>
				<TD><?php echo($VideoShootDate);?></TD>
				<TD><?php echo($VideoPhotographerName);?></TD>
				<TD><?php echo($VideoEditorName);?></TD>
			</TR>
<?php		if ($Progress['VideoReIsNull'] == 0) { ?>
				<TR>
					<TD><INPUT type="radio" name="shootType" value="Subsequent" checked=checked>Re-Shoot</TD>
					<TD><?php echo($VideoReShootDate);?></TD>
					<TD><?php echo($VideoRePhotographerName);?></TD>
					<TD><?php echo($VideoReEditorName);?></TD>
				</TR>
<?php 		} else { ?>
                <TR>
                    <TD><INPUT type="radio" name="shootType" value="Subsequent" disabled="disabled">Re-Shoot</TD>
                    <TD>Not Scheduled</TD>
                    <TD></TD>
                    <TD></TD>
                </TR>
<?php   	}
		}
    ?>
    </table>
    <div id="packageMsg" style="margin-bottom:-10px;"></div>
    <div class="form_line" >
        <div class="form_direction" >Negative Feedback</div>
    </div>
    <div class="form_line" style="height:aut