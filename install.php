<?php
/* FreePBX installer file
 * This file is run when the module is installed through module admin
 *
 * Note: install.sql is depreciated and may not work. Its recommended to use this file instead.
 * 
 * If this file returns false then the module will not install
 * EX:
 * return false;
 *
 */
$sql = "CREATE TABLE IF NOT EXISTS sipcomments (
`extension` varchar(255) NOT NULL default '',
`comment` varchar(255) NOT NULL default '',
PRIMARY KEY (`extension`)
);";

$check = sql($sql);
if (DB::IsError($check)) {
        die_freepbx( "Can not create `SIPComment` table: " . $check->getMessage() .  "\n");
}
