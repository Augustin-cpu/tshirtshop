<?php
// Include utility files
require_once 'include/config.php';
// Load the application page template
require_once BUSINESS_DIR . 'error_handler.php'; // Nouveau
require_once PRESENTATION_DIR . 'application.php';

// Définir le gestionnaire d'erreurs
ErrorHandler::SetHandler(); // Nouveau
// Load Smarty template file
$application = new Application();
// Display the page
$application->display('store_front.tpl');