<?php
// Classe de niveau métier pour lire les informations du catalogue de produits 
class Catalog
{
  // Récupère tous les départements
  public static function GetDepartments()
  {
    // Construit la requête SQL
    $sql = 'CALL catalog_get_departments_list()';
    // Exécute la requête et renvoie les résultats
    return DatabaseHandler::GetAll($sql);
  }
  // Récupère les détails complets du département spécifié
  public static function GetDepartmentDetails($departmentId)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_get_department_details(:department_id)';
    // Construire le tableau de paramètres
    $params = array(':department_id' => $departmentId);
    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetRow($sql, $params);
  }
  // Récupère la liste des catégories qui appartiennent à un département
  public static function GetCategoriesInDepartment($departmentId)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_get_categories_list(:department_id)';
    // Construire le tableau de paramètres
    $params = array(':department_id' => $departmentId);
    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetAll($sql, $params);
  }
  // Récupère les détails complets de la catégorie spécifiée
  public static function GetCategoryDetails($categoryId)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_get_category_details(:category_id)';
    // Construire le tableau de paramètres
    $params = array(':category_id' => $categoryId);
    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetRow($sql, $params);
  }
  /* Calcule le nombre de pages de produits pouvant être remplies par le
nombre de produits renvoyés par la requête $countSql */
  private static function HowManyPages($countSql, $countSqlParams)
  {
    // Créer un hachage pour la requête sql 
    $queryHashCode = md5($countSql . var_export($countSqlParams, true));

    // Vérifier si nous avons les résultats de la requête en cache
    if (
      isset($_SESSION['last_count_hash']) &&
      isset($_SESSION['how_many_pages']) &&
      $_SESSION['last_count_hash'] === $queryHashCode
    ) {
      // Récupérer la valeur mise en cache
      $how_many_pages = $_SESSION['how_many_pages'];
    } else {
      // Exécuter la requête
      $items_count = DatabaseHandler::GetOne($countSql, $countSqlParams);
      // Calculer le nombre de pages
      $how_many_pages = ceil($items_count / PRODUCTS_PER_PAGE);
      // Sauvegarder la requête et son résultat de comptage dans la session
      $_SESSION['last_count_hash'] = $queryHashCode;
      $_SESSION['how_many_pages'] = $how_many_pages;
    }

    // Retourner le nombre de pages 
    return $how_many_pages;
  }
  // Récupère la liste des produits qui appartiennent à une catégorie
  public static function GetProductsInCategory(
    $categoryId,
    $pageNo,
    &$rHowManyPages
  ) {
    // Requête qui renvoie le nombre de produits dans la catégorie
    $sql = 'CALL catalog_count_products_in_category(:category_id)';
    // Construire le tableau de paramètres
    $params = array(':category_id' => $categoryId);

    // Calculer le nombre de pages requis pour afficher les produits
    $rHowManyPages = Catalog::HowManyPages($sql, $params);

    // Calculer l'élément de départ
    $start_item = ($pageNo - 1) * PRODUCTS_PER_PAGE;

    // Récupérer la liste des produits
    $sql = 'CALL catalog_get_products_in_category(
    :category_id, :short_product_description_length,
    :products_per_page, :start_item)';
    // Construire le tableau de paramètres
    $params = array(
      ':category_id' => $categoryId,
      ':short_product_description_length' =>
      SHORT_PRODUCT_DESCRIPTION_LENGTH,
      ':products_per_page' => PRODUCTS_PER_PAGE,
      ':start_item' => $start_item
    );

    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetAll($sql, $params);
  }
  // Récupère la liste des produits pour la page du département
  public static function GetProductsOnDepartment(
    $departmentId,
    $pageNo,
    &$rHowManyPages
  ) {
    // Requête qui renvoie le nombre de produits dans la page du département
    $sql = 'CALL catalog_count_products_on_department(:department_id)';
    // Construire le tableau de paramètres
    $params = array(':department_id' => $departmentId);

    // Calculer le nombre de pages requis pour afficher les produits
    $rHowManyPages = Catalog::HowManyPages($sql, $params);

    // Calculer l'élément de départ
    $start_item = ($pageNo - 1) * PRODUCTS_PER_PAGE;

    // Récupérer la liste des produits
    $sql = 'CALL catalog_get_products_on_department(
    :department_id, :short_product_description_length,
    :products_per_page, :start_item)';
    // Construire le tableau de paramètres
    $params = array(
      ':department_id' => $departmentId,
      ':short_product_description_length' =>
      SHORT_PRODUCT_DESCRIPTION_LENGTH,
      ':products_per_page' => PRODUCTS_PER_PAGE,
      ':start_item' => $start_item
    );

    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetAll($sql, $params);
  }
  // Récupère la liste des produits sur la page du catalogue
  public static function GetProductsOnCatalog($pageNo, &$rHowManyPages)
  {
    // Requête qui renvoie le nombre de produits pour la page d'accueil du catalogue
    $sql = 'CALL catalog_count_products_on_catalog()';

    // Calculer le nombre de pages requis pour afficher les produits
    $rHowManyPages = Catalog::HowManyPages($sql, null);

    // Calculer l'élément de départ
    $start_item = ($pageNo - 1) * PRODUCTS_PER_PAGE;

    // Récupérer la liste des produits
    $sql = 'CALL catalog_get_products_on_catalog(
    :short_product_description_length,
    :products_per_page, :start_item)';
    // Construire le tableau de paramètres
    $params = array(
      ':short_product_description_length' =>
      SHORT_PRODUCT_DESCRIPTION_LENGTH,
      ':products_per_page' => PRODUCTS_PER_PAGE,
      ':start_item' => $start_item
    );

    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetAll($sql, $params);
  }
  // Récupère les détails complets du produit
  public static function GetProductDetails($productId)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_get_product_details(:product_id)';
    // Construire le tableau de paramètres
    $params = array(':product_id' => $productId);
    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetRow($sql, $params);
  }
  // Récupère les emplacements du produit (catégories et départements)
  public static function GetProductLocations($productId)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_get_product_locations(:product_id)';
    // Construire le tableau de paramètres
    $params = array(':product_id' => $productId);
    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetAll($sql, $params);
  }
}
