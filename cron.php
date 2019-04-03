<?php
error_reporting(E_ALL); 
define('ROOT_DIR', dirname(__FILE__));
define('SITE_URL', 'https://' . $_SERVER['HTTP_HOST']);
date_default_timezone_set("Europe/Kaliningrad");
session_start();

$this_year = (int)date('Y');
$this_month = (int)date('n');
$previous_month = $this_month==1 ? 12 : $this_month-1;
$previous_year = $this_month==1 ? $this_year-1 : $this_year;
$this_day = (int)date('j');
$is_last_day = mktime(0, 0, 0, $this_month+1, 1, $this_year) - time() <= 3600*24;

// here you can add your cron tasks
?>