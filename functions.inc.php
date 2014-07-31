<?php
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }

/**
 * Get Configuration from Sipcomments table
 */
function sipcomment_get_config () {
	global $core_conf,$db;

	$sql="SELECT extension,comment from sipcomments";
	$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
	foreach ($results as $user) {
		$core_conf->addSipAdditional($user["extension"],"#comment",$user["comment"]);
	}
}

/**
 * Intercept Config Page Init for pagename
 * @param  {string} $pagename The FreePBX Pagename
 * @return {[type]}           [description]
 */
function sipcomment_configpageinit($pagename) {
	global $db;
	if ($pagename=="extensions") {
		// Only display if we are on the extensions page
		sipcomment_applyhooks();
	}

	if (isset ($_REQUEST ["action"])) {
		if (isset ($_REQUEST ["sipcomment"])) {
			$sql="INSERT into sipcomments (extension,comment) VALUES (\"".$db->escapeSimple($_REQUEST["extension"])."\",\"".$db->escapeSimple($_REQUEST["sipcomment"])."\") ON DUPLICATE KEY UPDATE comment=\"".$db->escapeSimple($_REQUEST["sipcomment"])."\"";
			sql($sql);
		}
	}
}

function sipcomment_applyhooks() {
	global $currentcomponent;

	$currentcomponent->addguifunc('sipcomment_configpageload');
}


function sipcomment_configpageload() {
	global $currentcomponent, $endpoint, $db;

	if ((strlen ($_REQUEST["extdisplay"]) > 0) || ($_REQUEST ["Submit"]=="Submit")) {
		$section = _('Device Options');

		if (isset ($_REQUEST ["Submit"])) {
			// Default value
			$current_value="";
		} else {
			$sql="SELECT comment from sipcomments where extension=\"".$db->escapeSimple($_REQUEST["extdisplay"])."\"";
			$results = $db->getAll($sql, DB_FETCHMODE_ASSOC);
			if (count ($results)>0) {
				$current_value=$results [0]["comment"];
			}
		}
		$currentcomponent->addguielem($section, new gui_textbox('sipcomment', $current_value, 'comment', 'The data in this field will be added to the sip.conf file as a comment on this section', ''), 5);
	}
}
