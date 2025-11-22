<?php
// Classe de la couche métier pour les commandes
class Orders
{
    public static $mOrderStatusOptions = array ('placed', // 0
        'verified', // 1
        'completed', // 2
        'canceled'); // 3

// Obtenir les $how_many commandes les plus récentes
    public static function GetMostRecentOrders($how_many)
    {
// Construire la requête SQL
        $sql = 'CALL orders_get_most_recent_orders(:how_many)';
// Construire le tableau de paramètres
        $params = array (':how_many' => $how_many);
// Exécuter la requête et retourner les résultats
        return DatabaseHandler::GetAll($sql, $params);
    }

// Obtenir les commandes entre deux dates
    public static function GetOrdersBetweenDates($startDate, $endDate)
    {
// Construire la requête SQL
        $sql = 'CALL orders_get_orders_between_dates(:start_date, :end_date)';
// Construire le tableau de paramètres
        $params = array (':start_date' => $startDate, ':end_date' => $endDate);
// Exécuter la requête et retourner les résultats
        return DatabaseHandler::GetAll($sql, $params);
    }

// Obtenir les commandes par statut
    public static function GetOrdersByStatus($status)
    {
// Construire la requête SQL
        $sql = 'CALL orders_get_orders_by_status(:status)';
// Construire le tableau de paramètres
        $params = array (':status' => $status);
// Exécuter la requête et retourner les résultats
        return DatabaseHandler::GetAll($sql, $params);
    }
    // Obtient les détails d'une commande spécifique
    public static function GetOrderInfo($orderId)
    {
// Construire la requête SQL
        $sql = 'CALL orders_get_order_info(:order_id)';
// Construire le tableau de paramètres
        $params = array (':order_id' => $orderId);
// Exécuter la requête et retourner les résultats
        return DatabaseHandler::GetRow($sql, $params);
    }

// Obtient les produits qui appartiennent à une commande spécifique
    public static function GetOrderDetails($orderId)
    {
// Construire la requête SQL
        $sql = 'CALL orders_get_order_details(:order_id)';
// Construire le tableau de paramètres
        $params = array (':order_id' => $orderId);
// Exécuter la requête et retourner les résultats
        return DatabaseHandler::GetAll($sql, $params);
    }

// Met à jour les détails de la commande
    public static function UpdateOrder($orderId, $status, $comments,
                                       $customerName, $shippingAddress, $customerEmail)
    {
// Construire la requête SQL
        $sql = 'CALL orders_update_order(:order_id, :status, :comments,
:customer_name, :shipping_address, :customer_email)';
// Construire le tableau de paramètres
        $params = array (':order_id' => $orderId,
            ':status' => $status,
            ':comments' => $comments,
            ':customer_name' => $customerName,
            ':shipping_address' => $shippingAddress,
            ':customer_email' => $customerEmail);
// Exécuter la requête
        DatabaseHandler::Execute($sql, $params);
    }
}