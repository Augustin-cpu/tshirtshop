<?php
// Classe de la couche métier pour le panier d'achat
class ShoppingCart
{
// Stocke l'ID du panier du visiteur
    private static $_mCartId;

// Constructeur privé pour empêcher la création directe d'objet
    private function __construct()
    {
    }

    /* Sera appelée par GetCartId pour s'assurer que nous avons l'ID
    du panier du visiteur dans sa session, au cas où $_mCartID
    n'aurait aucune valeur définie */
    public static function SetCartId()
    {
// Si l'ID du panier n'a pas déjà été défini ...
        if (self::$_mCartId == '')
        {
// Si l'ID du panier du visiteur est dans la session, le récupérer de là
            if (isset ($_SESSION['cart_id']))
            {
                self::$_mCartId = $_SESSION['cart_id'];
            }
// Sinon, vérifier si l'ID du panier a été sauvegardé comme cookie
            elseif (isset ($_COOKIE['cart_id']))
            {
// Sauvegarder l'ID du panier depuis le cookie
                self::$_mCartId = $_COOKIE['cart_id'];
                $_SESSION['cart_id'] = self::$_mCartId;
// Régénérer le cookie pour qu'il soit valide pendant 7 jours (604800 secondes)
                setcookie('cart_id', self::$_mCartId, time() + 604800);
            }
            else
            {
                /* Générer l'ID du panier et le sauvegarder dans le membre de classe $_mCartId,
                la session et un cookie (lors des requêtes suivantes, $_mCartId
                sera rempli à partir de la session) */
                self::$_mCartId = md5(uniqid(rand(), true));
// Stocker l'ID du panier dans la session
                $_SESSION['cart_id'] = self::$_mCartId;
// Le cookie sera valide pendant 7 jours (604800 secondes)
                setcookie('cart_id', self::$_mCartId, time() + 604800);
            }
        }
    }

// Renvoie l'ID du panier du visiteur actuel
    public static function GetCartId()
    {
// S'assurer que nous avons un ID de panier pour le visiteur actuel
        if (!isset (self::$_mCartId))
            self::SetCartId();
        return self::$_mCartId;
    }

// Ajoute un produit au panier d'achat
    public static function AddProduct($productId, $attributes)
    {
// Construire la requête SQL
        $sql = 'CALL shopping_cart_add_product(
:cart_id, :product_id, :attributes)';
// Construire le tableau de paramètres
        $params = array (':cart_id' => self::GetCartId(),
            ':product_id' => $productId,
            ':attributes' => $attributes);
// Exécuter la requête
        DatabaseHandler::Execute($sql, $params);
    }

// Met à jour le panier d'achat avec de nouvelles quantités de produits
    public static function Update($itemId, $quantity)
    {
// Construire la requête SQL
        $sql = 'CALL shopping_cart_update(:item_id, :quantity)';
// Construire le tableau de paramètres
        $params = array (':item_id' => $itemId,
            ':quantity' => $quantity);
// Exécuter la requête
        DatabaseHandler::Execute($sql, $params);
    }

// Supprime un produit du panier d'achat
    public static function RemoveProduct($itemId)
    {
// Construire la requête SQL
        $sql = 'CALL shopping_cart_remove_product(:item_id)';
// Construire le tableau de paramètres
        $params = array (':item_id' => $itemId);
// Exécuter la requête
        DatabaseHandler::Execute($sql, $params);
    }

// Récupère les produits du panier d'achat
    public static function GetCartProducts($cartProductsType)
    {
        $sql = '';
// Si récupération des produits du panier "actifs"...
        if ($cartProductsType == GET_CART_PRODUCTS)
        {
// Construire la requête SQL
            $sql = 'CALL shopping_cart_get_products(:cart_id)';
        }
// Si récupération des produits enregistrés pour plus tard...
        elseif ($cartProductsType == GET_CART_SAVED_PRODUCTS)
        {
// Construire la requête SQL
            $sql = 'CALL shopping_cart_get_saved_products(:cart_id)';
        }
        else
            trigger_error($cartProductsType. ' valeur inconnue', E_USER_ERROR);
// Construire le tableau de paramètres
        $params = array (':cart_id' => self::GetCartId());
// Exécuter la requête et retourner les résultats
        return DatabaseHandler::GetAll($sql, $params);
    }

    /* Récupère le montant total des produits du panier avant taxes et/ou
    frais de livraison (sans inclure ceux qui sont enregistrés pour plus tard) */
    public static function GetTotalAmount()
    {
// Construire la requête SQL
        $sql = 'CALL shopping_cart_get_total_amount(:cart_id)';
// Construire le tableau de paramètres
        $params = array (':cart_id' => self::GetCartId());
// Exécuter la requête et retourner les résultats
        return DatabaseHandler::GetOne($sql, $params);
    }

// Enregistre un produit dans la liste "Enregistrer pour plus tard"
    public static function SaveProductForLater($itemId)
    {
// Construire la requête SQL
        $sql = 'CALL shopping_cart_save_product_for_later(:item_id)';
// Construire le tableau de paramètres
        $params = array (':item_id' => $itemId);
// Exécuter la requête
        DatabaseHandler::Execute($sql, $params);
    }

// Récupère un produit de la liste "Enregistrer pour plus tard" et le remet dans le panier
    public static function MoveProductToCart($itemId)
    {
// Construire la requête SQL
        $sql = 'CALL shopping_cart_move_product_to_cart(:item_id)';
// Construire le tableau de paramètres
        $params = array (':item_id' => $itemId);
// Exécuter la requête
        DatabaseHandler::Execute($sql, $params);
    }
    // Compter les anciens paniers d'achat
    public static function CountOldShoppingCarts($days)
    {
// Construire la requête SQL
        $sql = 'CALL shopping_cart_count_old_carts(:days)';
// Construire le tableau de paramètres
        $params = array (':days' => $days);
// Exécuter la requête et retourner les résultats
        return DatabaseHandler::GetOne($sql, $params);
    }

// Supprimer les anciens paniers d'achat
    public static function DeleteOldShoppingCarts($days)
    {
// Construire la requête SQL
        $sql = 'CALL shopping_cart_delete_old_carts(:days)';
// Construire le tableau de paramètres
        $params = array (':days' => $days);
// Exécuter la requête
        DatabaseHandler::Execute($sql, $params);
    }
    // Créer une nouvelle commande
    public static function CreateOrder()
    {
// Construire la requête SQL
        $sql = 'CALL shopping_cart_create_order(:cart_id)';
// Construire le tableau de paramètres
        $params = array (':cart_id' => self::GetCartId());
// Exécuter la requête et retourner les résultats
        return DatabaseHandler::GetOne($sql, $params);
    }
        // Obtenir les recommandations de produits pour le panier d'achat
        public static function GetRecommendations()
        {
// Construire la requête SQL
            $sql = 'CALL shopping_cart_get_recommendations(
:cart_id, :short_product_description_length)';
// Construire le tableau de paramètres
            $params = array (':cart_id' => self::GetCartId(),
                ':short_product_description_length' =>
                    SHORT_PRODUCT_DESCRIPTION_LENGTH);
// Exécuter la requête et retourner les résultats
            return DatabaseHandler::GetAll($sql, $params);
        }
}
