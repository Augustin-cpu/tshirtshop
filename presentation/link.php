<?php
class Link
{
    public static function Build($link, $type = 'http')
    {
        $base = 'http://' . getenv('SERVER_NAME');

        // Si HTTP_SERVER_PORT est défini et différent de la valeur par défaut
        if (defined('HTTP_SERVER_PORT') && HTTP_SERVER_PORT != '80') {
            // Ajoute le port du serveur
            $base .= ':' . HTTP_SERVER_PORT;
        }

        // Construit le lien absolu
        $link = $base . VIRTUAL_LOCATION . $link;

        // Échappe le HTML (pour la sécurité)
        return htmlspecialchars($link, ENT_QUOTES);
    }

    public static function ToDepartment($departmentId, $page = 1)
    {
        $link = self::CleanUrlText(Catalog::GetDepartmentName($departmentId)) .
            '-d' . $departmentId . '/';
        if ($page > 1)
            $link .= 'page-' . $page . '/';
        return self::Build($link);
    }

    public static function ToCategory($departmentId, $categoryId, $page = 1)
    {
        $link = self::CleanUrlText(Catalog::GetDepartmentName($departmentId)) .
            '-d' . $departmentId . '/' .
            self::CleanUrlText(Catalog::GetCategoryName($categoryId)) .
            '-c' . $categoryId . '/';
        if ($page > 1)
            $link .= 'page-' . $page . '/';
        return self::Build($link);
    }

    public static function ToProduct($productId)
    {
        $link = self::CleanUrlText(Catalog::GetProductName($productId)) .
            '-p' . $productId . '/';
        return self::Build($link);
    }

    public static function ToIndex($page = 1)
    {
        $link = '';
        if ($page > 1)
            $link .= 'page-' . $page . '/';
        return self::Build($link);
    }
    public static function QueryStringToArray($queryString)
    {
        $result = array();
        if ($queryString != '') {
            $elements = explode('&', $queryString);
            foreach ($elements as $key => $value) {
                $element = explode('=', $value);
                $result[urldecode($element[0])] =
                    isset($element[1]) ? urldecode($element[1]) : '';
            }
        }
        return $result;
    }
    // Créer un lien vers la page de recherche
    public static function ToSearch()
    {
        return self::Build('index.php?Search');
    }

    // Créer un lien vers une page de résultats de recherche
    public static function ToSearchResults(
        $searchString,
        $allWords,
        $page = 1
    ) {
        $link = 'search-results/find';
        if (empty($searchString))
            $link .= '/';
        else
            $link .= '-' . self::CleanUrlText($searchString) . '/';

        $link .= 'all-words-' . $allWords . '/';

        if ($page > 1)
            $link .= 'page-' . $page . '/';

        return self::Build($link);
    }
    // Redirige vers l'URL appropriée si ce n'est pas déjà le cas
    // Redirige vers l'URL appropriée si ce n'est pas déjà le cas
    public static function CheckRequest()
    {
        $proper_url = '';
        if (isset ($_GET['Search']) || isset($_GET['SearchResults']) ||
            isset ($_GET['CartAction']) || isset ($_GET['AjaxRequest']))
        {
            return ;
        }
        // Obtenir l'URL appropriée pour les pages de catégorie
        elseif (isset($_GET['DepartmentId']) && isset($_GET['CategoryId'])) {
            if (isset($_GET['Page']))
                $proper_url = self::ToCategory(
                    $_GET['DepartmentId'],
                    $_GET['CategoryId'],
                    $_GET['Page']
                );
            else
                $proper_url = self::ToCategory(
                    $_GET['DepartmentId'],
                    $_GET['CategoryId']
                );
        }
        // Obtenir l'URL appropriée pour les pages de département
        elseif (isset($_GET['DepartmentId'])) {
            if (isset($_GET['Page']))
                $proper_url = self::ToDepartment(
                    $_GET['DepartmentId'],
                    $_GET['Page']
                );
            else
                $proper_url = self::ToDepartment($_GET['DepartmentId']);
        }
        // Obtenir l'URL appropriée pour les pages de produit
        elseif (isset($_GET['ProductId'])) {
            $proper_url = self::ToProduct($_GET['ProductId']);
        }
        // Obtenir l'URL appropriée pour la page d'accueil
        else {
            if (isset($_GET['Page']))
                $proper_url = self::ToIndex($_GET['Page']);
            else
                $proper_url = self::ToIndex();
        }

        /* Supprimer l'emplacement virtuel de l'URL demandée
       afin que nous puissions comparer les chemins */
        $requested_url = self::Build(str_replace(
            VIRTUAL_LOCATION,
            '',
            $_SERVER['REQUEST_URI']
        ));
        // Redirection 404 si le produit, la catégorie ou le département demandé 
        // n'existe pas
        if (strstr($proper_url, '/-')) {
            // Nettoyer le buffer de sortie
            ob_clean();
            // Charger la page 404
            include '404.php';
            // Vider le buffer de sortie et arrêter l'exécution
            flush();
            ob_flush();
            ob_end_clean();
            exit();
        }
        // Redirection 301 vers l'URL appropriée si nécessaire
        if ($requested_url != $proper_url) {
            // Nettoyer le buffer de sortie
            ob_clean();
            // Redirection 301 
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $proper_url);

            // Vider le buffer de sortie et arrêter l'exécution
            flush();
            ob_flush();
            ob_end_clean();
            exit();
        }
    }
    // Prépare une chaîne à être incluse dans une URL
    public static function CleanUrlText($string)
    {
        // Supprimer tous les caractères qui ne sont pas a-z, 0-9, tiret, underscore ou espace
        $not_acceptable_characters_regex = '#[^-a-zA-Z0-9_ ]#';
        $string = preg_replace($not_acceptable_characters_regex, '', $string);
        // Supprimer tous les espaces au début et à la fin
        $string = trim($string);
        // Changer tous les tirets, underscores et espaces en tirets
        $string = preg_replace('#[-_ ]+#', '-', $string);
        // Retourner la chaîne modifiée
        return strtolower($string);
    }

    // Créer un lien vers le panier d'achat
    public static function ToCart($action = 0, $target = null)
    {
        $link = '';
        switch ($action)
        {
            case ADD_PRODUCT:
                $link = 'index.php?CartAction=' . ADD_PRODUCT . '&ItemId=' . $target;
                break;
            case REMOVE_PRODUCT:
                $link = 'index.php?CartAction=' .
                    REMOVE_PRODUCT . '&ItemId=' . $target;
                break;
            case UPDATE_PRODUCTS_QUANTITIES:
                $link = 'index.php?CartAction=' . UPDATE_PRODUCTS_QUANTITIES;
                break;
            case SAVE_PRODUCT_FOR_LATER:
                $link = 'index.php?CartAction=' .
                    SAVE_PRODUCT_FOR_LATER . '&ItemId=' . $target;
                break;
            case MOVE_PRODUCT_TO_CART:
                $link = 'index.php?CartAction=' .
                    MOVE_PRODUCT_TO_CART . '&ItemId=' . $target;
                break;
            default:
                $link = 'cart-details/';
        }
        return self::Build($link);
    }
    // Créer un lien vers la page d'administration
    public static function ToAdmin($params = '')
    {
        $link = 'admin.php';
        if ($params != '')
            $link .= '?' . $params;
        return self::Build($link, 'http');
    }

    // Créer un lien de déconnexion
    public static function ToLogout()
    {
        return self::ToAdmin('Page=Logout');
    }
    // Crée un lien vers la page d'administration des départements
    public static function ToDepartmentsAdmin()
    {
        return self::ToAdmin('Page=Departments');
    }
    // Crée un lien vers la page d'administration des catégories
    public static function ToDepartmentCategoriesAdmin($departmentId)
    {
        $link = 'Page=Categories&DepartmentId=' . $departmentId;
        return self::ToAdmin($link);
    }
    // Crée le lien vers la page d'administration des attributs
    public static function ToAttributesAdmin()
    {
        return self::ToAdmin('Page=Attributes');
    }
    // Crée le lien vers la page d'administration des valeurs d'attribut
    public static function ToAttributeValuesAdmin($attributeId)
    {
        $link = 'Page=AttributeValues&AttributeId=' . $attributeId;
        return self::ToAdmin($link);
    }
    // Crée un lien vers la page d'administration des produits
    public static function ToCategoryProductsAdmin($departmentId, $categoryId)
    {
        $link = 'Page=Products&DepartmentId=' . $departmentId .
            '&CategoryId=' . $categoryId;
        return self::ToAdmin($link);
    }
    // Crée un lien vers la page d'administration des détails du produit
    public static function ToProductAdmin($departmentId, $categoryId, $productId)
    {
        $link = 'Page=ProductDetails&DepartmentId=' . $departmentId .
            '&CategoryId=' . $categoryId . '&ProductId=' . $productId;
        return self::ToAdmin($link);
    }
    // Créer un lien vers la page d'administration des paniers d'achat
    public static function ToCartsAdmin()
    {
        return self::ToAdmin('Page=Carts');
    }
    // Créer le lien vers la page d'administration des commandes
    public static function ToOrdersAdmin()
    {
        return self::ToAdmin('Page=orders');
    }

// Créer le lien vers la page d'administration des détails de la commande
    public static function ToOrderDetailsAdmin($orderId)
    {
        $link = 'Page=OrderDetails&OrderId=' . $orderId;
        return self::ToAdmin($link);
    }
}
