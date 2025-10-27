<?php
// Include utility files
require_once 'include/config.php';
// Load the application page template
require_once PRESENTATION_DIR . 'application.php';
// Load Smarty template file
$application = new Application();
// Display the page
$application->display('store_front.tpl');
