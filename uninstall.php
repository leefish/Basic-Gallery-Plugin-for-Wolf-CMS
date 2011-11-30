<?php

if (!defined('IN_CMS')) { exit(); }

$conn = Record::getConnection();
 
$conn->exec("DROP TABLE bgphoto");
 
exit();
