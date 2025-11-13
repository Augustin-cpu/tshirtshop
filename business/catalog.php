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
  /* Calcule le nombre de pages de produits pouvant être remplies par le nombre de produits renvoyés par la requête $countSql */
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
  // Récupère les attributs du produit
  public static function GetProductAttributes($productId)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_get_product_attributes(:product_id)';

    // Construire le tableau de paramètres
    $params = array(':product_id' => $productId);

    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetAll($sql, $params);
  }
  // Rechercher dans le catalogue
  public static function Search(
    $searchString,
    $allWords,
    $pageNo,
    &$rHowManyPages
  ) {
    // Le résultat de la recherche sera un tableau de cette forme
    $search_result = array(
      'accepted_words' => array(),
      'ignored_words' => array(),
      'products' => array()
    );

    // Retourner vide si la chaîne de recherche est vide
    if (empty($searchString))
      return $search_result;

    // Délimiteurs de la chaîne de recherche
    $delimiters = ',.; ';

    /* Lors du premier appel à strtok, vous fournissez l'intégralité
    de la chaîne de recherche et la liste des délimiteurs.
    Cela renvoie le premier mot de la chaîne */
    $word = strtok($searchString, $delimiters);

    // Analyser la chaîne mot par mot jusqu'à ce qu'il n'y ait plus de mots
    while ($word) {
      // Les mots courts sont ajoutés à la liste ignored_words de $search_result
      if (strlen($word) < FT_MIN_WORD_LEN)
        $search_result['ignored_words'][] = $word;
      else
        $search_result['accepted_words'][] = $word;

      // Obtenir le mot suivant de la chaîne de recherche
      $word = strtok($delimiters);
    }

    // S'il n'y a pas de mots acceptés, retourner $search_result
    if (count($search_result['accepted_words']) == 0)
      return $search_result;

    // Construire $search_string à partir de la liste des mots acceptés
    $search_string = '';

    // Si $allWords est 'on', nous ajoutons un ' +' devant chaque mot
    if (strcmp($allWords, "on") == 0)
      $search_string = implode(" +", $search_result['accepted_words']);
    else
      $search_string = implode(" ", $search_result['accepted_words']);

    // Compter le nombre de résultats de recherche
    $sql = 'CALL catalog_count_search_result(:search_string, :all_words)';
    $params = array(
      ':search_string' => $search_string,
      ':all_words' => $allWords
    );

    // Calculer le nombre de pages requises pour afficher les produits
    $rHowManyPages = Catalog::HowManyPages($sql, $params);

    // Calculer l'élément de départ (start item)
    $start_item = ($pageNo - 1) * PRODUCTS_PER_PAGE;

    // Récupérer la liste des produits correspondants
    $sql = 'CALL catalog_search(:search_string, :all_words,
                                :short_product_description_length,
                                :products_per_page, :start_item)';

    // Construire le tableau de paramètres
    $params = array(
      ':search_string' => $search_string,
      ':all_words' => $allWords,
      ':short_product_description_length' =>
      SHORT_PRODUCT_DESCRIPTION_LENGTH,
      ':products_per_page' => PRODUCTS_PER_PAGE,
      ':start_item' => $start_item
    );

    // Exécuter la requête
    $search_result['products'] = DatabaseHandler::GetAll($sql, $params);

    // Retourner les résultats
    return $search_result;
  }
  // Récupère le nom du département
  public static function GetDepartmentName($departmentId)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_get_department_name(:department_id)';
    // Construire le tableau de paramètres
    $params = array(':department_id' => $departmentId);
    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Récupère le nom de la catégorie
  public static function GetCategoryName($categoryId)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_get_category_name(:category_id)';
    // Construire le tableau de paramètres
    $params = array(':category_id' => $categoryId);
    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Récupère le nom du produit
  public static function GetProductName($productId)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_get_product_name(:product_id)';
    // Construire le tableau de paramètres
    $params = array(':product_id' => $productId);
    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetOne($sql, $params);
  }
  // Récupère tous les départements avec leurs descriptions
  public static function GetDepartmentsWithDescriptions()
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_get_departments()';
    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetAll($sql);
  }

  // Ajouter un département
  public static function AddDepartment($departmentName, $departmentDescription)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_add_department(:department_name,
:department_description)';
    // Construire le tableau de paramètres
    $params = array(
      ':department_name' => $departmentName,
      ':department_description' => $departmentDescription
    );
    // Exécuter la requête
    DatabaseHandler::Execute($sql, $params);
  }

  // Met à jour les détails d'un département
  public static function UpdateDepartment(
    $departmentId,
    $departmentName,
    $departmentDescription
  ) {
    // Construire la requête SQL
    $sql = 'CALL catalog_update_department(:department_id, :department_name,
:department_description)';
    // Construire le tableau de paramètres
    $params = array(
      ':department_id' => $departmentId,
      ':department_name' => $departmentName,
      ':department_description' => $departmentDescription
    );
    // Exécuter la requête
    DatabaseHandler::Execute($sql, $params);
  }

  // Supprime un département
  public static function DeleteDepartment($departmentId)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_delete_department(:department_id)';
    // Construire le tableau de paramètres
    $params = array(':department_id' => $departmentId);
    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetOne($sql, $params);
  }
  // Obtient les catégories d'un département
  public static function GetDepartmentCategories($departmentId)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_get_department_categories(:department_id)';
    // Construire le tableau de paramètres
    $params = array(':department_id' => $departmentId);
    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Ajoute une catégorie
  public static function AddCategory(
    $departmentId,
    $categoryName,
    $categoryDescription
  ) {
    // Construire la requête SQL
    $sql = 'CALL catalog_add_category(:department_id, :category_name,
:category_description)';
    // Construire le tableau de paramètres
    $params = array(
      ':department_id' => $departmentId,
      ':category_name' => $categoryName,
      ':category_description' => $categoryDescription
    );
    // Exécuter la requête
    DatabaseHandler::Execute($sql, $params);
  }

  // Met à jour une catégorie
  public static function UpdateCategory(
    $categoryId,
    $categoryName,
    $categoryDescription
  ) {
    // Construire la requête SQL
    $sql = 'CALL catalog_update_category(:category_id, :category_name,
:category_description)';
    // Construire le tableau de paramètres
    $params = array(
      ':category_id' => $categoryId,
      ':category_name' => $categoryName,
      ':category_description' => $categoryDescription
    );
    // Exécuter la requête
    DatabaseHandler::Execute($sql, $params);
  }

  // Supprime une catégorie
  public static function DeleteCategory($categoryId)
  {
    // Construire la requête SQL
    $sql = 'CALL catalog_delete_category(:category_id)';
    // Construire le tableau de paramètres
    $params = array(':category_id' => $categoryId);
    // Exécuter la requête et retourner les résultats
    return DatabaseHandler::GetOne($sql, $params);
  }
}
