<?php
//Johnny Drop Tables
out("Dropping all relevant tables");
$sql = "DROP TABLE `sipcomments`";
$result = sql($sql);
