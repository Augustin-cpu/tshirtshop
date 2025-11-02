<?php
// Activer la session
session_start();

// Include utility files
require_once 'include/config.php';
// Load the application page template
require_once BUSINESS_DIR . 'error_handler.php'; // Nouveau
// Charge le gestionnaire de base de données
require_once BUSINESS_DIR . 'database_handler.php';
// Load Business Tier (Mise en évidence)
require_once BUSINESS_DIR . 'catalog.php';
require_once PRESENTATION_DIR . 'application.php';

// Charge la classe Link (Mise en évidence)
require_once PRESENTATION_DIR . 'link.php';

// Définir le gestionnaire d'erreurs
ErrorHandler::SetHandler(); // Nouveau
// Load Smarty template file
$application = new Application();
// Display the page
$application->display('store_front.tpl');

// Ferme la connexion à la base de données
DatabaseHandler::Close();
