<?php
// Classe fournissant une fonctionnalité générique d'accès aux données
class DatabaseHandler
{
    // Maintient une instance de la classe PDO
    private static $_mHandler;

    // Constructeur privé pour empêcher la création directe d'objet
    private function __construct() {}

    // Renvoie un gestionnaire de base de données initialisé 
    private static function GetHandler()
    {
        // Crée une connexion à la base de données seulement si elle n'existe pas déjà
        if (!isset(self::$_mHandler)) {
            // Exécute le code en interceptant les exceptions potentielles
            try {
                // Crée une nouvelle instance de la classe PDO
                self::$_mHandler =
                    new PDO(
                        PDO_DSN,
                        DB_USERNAME,
                        DB_PASSWORD,
                        array(PDO::ATTR_PERSISTENT => DB_PERSISTENCY)
                    );

                // Configure PDO pour lever des exceptions
                self::$_mHandler->setAttribute(
                    PDO::ATTR_ERRMODE,
                    PDO::ERRMODE_EXCEPTION
                );
            } catch (PDOException $e) {
                // Ferme le gestionnaire de base de données et déclenche une erreur
                self::Close();
                trigger_error($e->getMessage(), E_USER_ERROR);
            }
        }

        // Renvoie le gestionnaire de base de données
        return self::$_mHandler;
    }

    // Méthode enveloppante pour PDOStatement::execute()
    public static function Execute($sqlQuery, $params = null)
    {
        // Tente d'exécuter une requête SQL ou une procédure stockée
        try {
            // Obtient le gestionnaire de base de données
            $database_handler = self::GetHandler();
            // Prépare la requête pour l'exécution
            $statement_handler = $database_handler->prepare($sqlQuery);
            // Exécute la requête
            $statement_handler->execute($params);
        }
        // Déclenche une erreur si une exception a été levée lors de l'exécution de la requête SQL
        catch (PDOException $e) {
            // Ferme le gestionnaire de base de données et déclenche une erreur
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }

    // Méthode enveloppante pour PDOStatement::fetchAll()
    public static function GetAll(
        $sqlQuery,
        $params = null,
        $fetchStyle = PDO::FETCH_ASSOC
    ) {
        // Initialise la valeur de retour à null
        $result = null;
        // Tente d'exécuter une requête SQL ou une procédure stockée
        try {
            // Obtient le gestionnaire de base de données
            $database_handler = self::GetHandler();
            // Prépare la requête pour l'exécution
            $statement_handler = $database_handler->prepare($sqlQuery);
            // Exécute la requête
            $statement_handler->execute($params);
            // Récupère le résultat
            $result = $statement_handler->fetchAll($fetchStyle);
        }
        // Déclenche une erreur si une exception a été levée lors de l'exécution de la requête SQL
        catch (PDOException $e) {
            // Ferme le gestionnaire de base de données et déclenche une erreur
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        // Renvoie les résultats de la requête
        return $result;
    }
    // Méthode enveloppante pour PDOStatement::fetch()
    public static function GetRow(
        $sqlQuery,
        $params = null,
        $fetchStyle = PDO::FETCH_ASSOC
    ) {
        // Initialise la valeur de retour à null
        $result = null;
        // Tente d'exécuter une requête SQL ou une procédure stockée
        try {
            // Obtient le gestionnaire de base de données
            $database_handler = self::GetHandler();
            // Prépare la requête pour l'exécution
            $statement_handler = $database_handler->prepare($sqlQuery);
            // Exécute la requête
            $statement_handler->execute($params);
            // Récupère le résultat
            $result = $statement_handler->fetch($fetchStyle);
        }
        // Déclenche une erreur si une exception a été levée lors de l'exécution de la requête SQL
        catch (PDOException $e) {
            // Ferme le gestionnaire de base de données et déclenche une erreur
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
        // Renvoie les résultats de la requête
        return $result;
    }
    // Renvoie la première valeur de colonne d'une ligne
    public static function GetOne($sqlQuery, $params = null)
    {
        // Initialise la valeur de retour à null 
        $result = null;
        // Tente d'exécuter une requête SQL ou une procédure stockée
        try {
            // Obtient le gestionnaire de base de données
            $database_handler = self::GetHandler();
            // Prépare la requête pour l'exécution
            $statement_handler = $database_handler->prepare($sqlQuery);
            // Exécute la requête
            $statement_handler->execute($params);
            // Récupère le résultat
            $result = $statement_handler->fetch(PDO::FETCH_NUM);
            /* Enregistre la première valeur de l'ensemble de résultats (première colonne de la première ligne)
    dans $result */
            $result = $result[0];
        }
        // Déclenche une erreur si une exception a été levée lors de l'exécution de la requête SQL
        catch (PDOException $e) {
            // Ferme le gestionnaire de base de données et déclenche une erreur
            self::Close();
            trigger_error($e->getMessage(), E_USER_ERROR);
        }

        // Renvoie les résultats de la requête
        return $result;
    }
    // Efface l'instance de la classe PDO
    public static function Close()
    {
        self::$_mHandler = null;
    }
}
