<?php

require_once dirname(__FILE__) . '/forms/entries.php';
/*require_once dirname(__FILE__) . '/forms/nottypes.php';*/
/* require_once dirname(__FILE__) . '/forms/settings.php';*/
if (get_option('pd_nb_inserttype', 'header') == 'header') {
	require_once dirname(__FILE__) . '/forms/metabox.php';
}
?>