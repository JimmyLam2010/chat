<?php
include_once("../includes/init.php");

$ulname = $db->select('ul_name')->from('website')->where("id=1")->find();

var_dump($ulname);