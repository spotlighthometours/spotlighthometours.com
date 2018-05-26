<?php
echo "PHP Extensions Installed:<br />";
foreach(get_loaded_extensions() as $extension)
{
    echo $extension . "<br />\n";
}
?> 