<?php
class ProductsList
{
  // Variables publiques à lire depuis le template Smarty
  public $mPage = 1;
  public $mrTotalPages;
  public $mLinkToNextPage;
  public $mLinkToPreviousPage;
  public $mProductListPages = array();
  public $mProducts;
  public $mSearchDescription;
  public $mAllWords = 'off';
  public $mSearchString;
  // Membres privés
  private $_mDepartmentId;
  private $_mCategoryId;

  // Constructeur de classe
  public function __construct()
  {
    // Récupérer la chaîne de recherche et AllWords à partir de la chaîne de requête
    if (isset($_GET['SearchResults'])) {
      $this->mSearchString = trim(str_replace('-', ' ', $_GET['SearchString']));
      $this->mAllWords = isset($_GET['AllWords']) ? $_GET['AllWords'] : 'off';
    }
    // Récupérer DepartmentId de la chaîne de requête en le transtypant en int
    if (isset($_GET['DepartmentId']))
      $this->_mDepartmentId = (int)$_GET['DepartmentId'];

    // Récupérer CategoryId de la chaîne de requête en le transtypant en int
    if (isset($_GET['CategoryId']))
      $this->_mCategoryId = (int)$_GET['CategoryId'];

    // Récupérer le numéro de page de la chaîne de requête en le transtypant en int
    if (isset($_GET['Page']))
      $this->mPage = (int)$_GET['Page'];

    if ($this->mPage < 1)
      trigger_error('Valeur de Page incorrecte');

    // Enregistrer la requête de page pour la fonctionnalité "Continuer mes achats"
    $_SESSION['link_to_continue_shopping'] = $_SERVER['QUERY_STRING'];
  }

  public function init()
  {
    /* --- 1. CHARGEMENT DES PRODUITS --- */

    // Si l'on recherche dans le catalogue
    if (isset($this->mSearchString)) {
      $search_results = Catalog::Search(
        $this->mSearchString,
        $this->mAllWords,
        $this->mPage,
        $this->mrTotalPages
      );
      $this->mProducts = $search_results['products'];

      // Construire la description de la recherche
      if (count($search_results['accepted_words']) > 0)
        $this->mSearchDescription =
          '<p class="description">Produits contenant <font class="words">'
          . ($this->mAllWords == 'on' ? 'tous' : 'n\'importe quel') . '</font>'
          . ' de ces mots : <font class="words">'
          . implode(', ', $search_results['accepted_words']) .
          '</font></p>';

      if (count($search_results['ignored_words']) > 0)
        $this->mSearchDescription .=
          '<p class="description">Mots ignorés : <font class="words">'
          . implode(', ', $search_results['ignored_words']) .
          '</font></p>';

      if (!(count($search_results['products']) > 0))
        $this->mSearchDescription .=
          '<p class="description">Votre recherche n\'a donné aucun résultat.</p>';
    }
    // Si l'on navigue dans une catégorie
    elseif (isset($this->_mCategoryId)) {
      $this->mProducts = Catalog::GetProductsInCategory(
        $this->_mCategoryId,
        $this->mPage,
        $this->mrTotalPages
      );
    }
    // Si l'on navigue dans un département
    elseif (isset($this->_mDepartmentId)) {
      $this->mProducts = Catalog::GetProductsOnDepartment(
        $this->_mDepartmentId,
        $this->mPage,
        $this->mrTotalPages
      );
    }
    // Si l'on navigue sur la page d'accueil/catalogue (par défaut)
    else {
      $this->mProducts = Catalog::GetProductsOnCatalog(
        $this->mPage,
        $this->mrTotalPages
      );
    }


    /* --- 2. REDIRECTION 404 --- */
    // Vérifier si le numéro de page demandé est supérieur au total (et s'il y a des pages au total)
    if ($this->mPage > $this->mrTotalPages && $this->mrTotalPages > 0) {
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


    /* --- 3. GESTION DE LA PAGINATION (Liens Précédent/Suivant/Numéros) --- */
    if ($this->mrTotalPages > 1) {
      // Construire le lien Suivant
      if ($this->mPage < $this->mrTotalPages) {
        if (isset($_GET['SearchResults']))
          $this->mLinkToNextPage = Link::ToSearchResults($this->mSearchString, $this->mAllWords, $this->mPage + 1);
        elseif (isset($this->_mCategoryId))
          $this->mLinkToNextPage = Link::ToCategory($this->_mDepartmentId, $this->_mCategoryId, $this->mPage + 1);
        elseif (isset($this->_mDepartmentId))
          $this->mLinkToNextPage = Link::ToDepartment($this->_mDepartmentId, $this->mPage + 1);
        else
          $this->mLinkToNextPage = Link::ToIndex($this->mPage + 1); // Cas de la page d'accueil
      }

      // Construire le lien Précédent
      if ($this->mPage > 1) {
        if (isset($_GET['SearchResults']))
          $this->mLinkToPreviousPage = Link::ToSearchResults($this->mSearchString, $this->mAllWords, $this->mPage - 1);
        elseif (isset($this->_mCategoryId))
          $this->mLinkToPreviousPage = Link::ToCategory($this->_mDepartmentId, $this->_mCategoryId, $this->mPage - 1);
        elseif (isset($this->_mDepartmentId))
          $this->mLinkToPreviousPage = Link::ToDepartment($this->_mDepartmentId, $this->mPage - 1);
        else
          $this->mLinkToPreviousPage = Link::ToIndex($this->mPage - 1); // Cas de la page d'accueil
      }

      // Construire les liens de pages numérotées
      for ($i = 1; $i <= $this->mrTotalPages; $i++) {
        if (isset($_GET['SearchResults']))
          $this->mProductListPages[] = Link::ToSearchResults($this->mSearchString, $this->mAllWords, $i);
        elseif (isset($this->_mCategoryId))
          $this->mProductListPages[] = Link::ToCategory($this->_mDepartmentId, $this->_mCategoryId, $i);
        elseif (isset($this->_mDepartmentId))
          $this->mProductListPages[] = Link::ToDepartment($this->_mDepartmentId, $i);
        else
          $this->mProductListPages[] = Link::ToIndex($i);
      }
    }


    /* --- 4. PRÉPARATION DES DONNÉES DES PRODUITS --- */
    for ($i = 0; $i < count($this->mProducts); $i++) {
      $this->mProducts[$i]['link_to_product'] =
        Link::ToProduct($this->mProducts[$i]['product_id']);

      if ($this->mProducts[$i]['thumbnail'])
        $this->mProducts[$i]['thumbnail'] =
          Link::Build('images/product_images/' . $this->mProducts[$i]['thumbnail']);
      // Create the Add to Cart link
      $this->mProducts[$i]['link_to_add_product'] =
        Link::ToAddProduct($this->mProducts[$i]['product_id']);
      $this->mProducts[$i]['attributes'] =
        Catalog::GetProductAttributes($this->mProducts[$i]['product_id']);
    }
  }
}
