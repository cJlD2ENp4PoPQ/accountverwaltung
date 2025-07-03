<?php
include 'content/de/lang/'.$_SESSION['ums_language'].'_footer.lang.php';

echo '<br style="clear:both;">';

echo '
<div id="footer">
				&copy; <a href="'.$GLOBALS['env_url_portal'].'" target="_blank">'.$footer_lang['dieewigen'].'</a>

        - <a href="'.$GLOBALS['env_url_impressum'].'" target="_blank">'.$footer_lang['impressum'].'</a>
        - <a href="'.$GLOBALS['env_url_datenschutz'].'" target="_blank">Datenschutz</a>
</div>';

//content-div
echo '</div>';
