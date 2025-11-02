<?php
class ProductsList
{
  // Variables publiques à lire depuis le gabarit Smarty
  public $mPage = 1;
  public $mrTotalPages;
  public $mLinkToNextPage;
  public $mLinkToPreviousPage;
  public $mProducts;

  // Membres privés
  private $_mDepartmentId;
  private $_mCategoryId;

  // Constructeur de classe
  public function __construct()
  {
    // Récupérer DepartmentId de la chaîne de requête en le transtypant en int
    if (isset ($_GET['DepartmentId']))
      $this->_mDepartmentId = (int)$_GET['DepartmentId'];

    // Récupérer CategoryId de la chaîne de requête en le transtypant en int
    if (isset ($_GET['CategoryId']))
      $this->_mCategoryId = (int)$_GET['CategoryId'];

    // Récupérer le numéro de page de la chaîne de requête en le transtypant en int
    if (isset ($_GET['Page']))
      $this->mPage = (int)$_GET['Page'];

    if ($this->mPage < 1)
      trigger_error('Valeur de Page incorrecte');
    
    // Enregistrer la requête de page pour la fonctionnalité "Continuer mes achats"
    $_SESSION['link_to_continue_shopping'] = $_SERVER['QUERY_STRING'];
  }

  public function init()
  {

  /* Si l'on navigue dans une catégorie, obtenir la liste des produits en
     appelant la méthode GetProductsInCategory() de la couche métier */
  if (isset ($this->_mCategoryId))
    $this->mProducts = Catalog::GetProductsInCategory(
      $this->_mCategoryId, $this->mPage, $this->mrTotalPages);

  /* Si l'on navigue dans un département, obtenir la liste des produits en
     appelant la méthode GetProductsOnDepartment() de la couche métier */
  elseif (isset ($this->_mDepartmentId))
    $this->mProducts = Catalog::GetProductsOnDepartment(
      $this->_mDepartmentId, $this->mPage, $this->mrTotalPages);

  /* Si l'on navigue sur la première page, obtenir la liste des produits en
     appelant la méthode GetProductsOnCatalog() de la couche métier */
  else
    $this->mProducts = Catalog::GetProductsOnCatalog(
      $this->mPage, $this->mrTotalPages);

  /* S'il existe des sous-pages de produits, afficher les
     commandes de navigation */
  if ($this->mrTotalPages > 1)
  {
    // Construire le lien Suivant
    if ($this->mPage < $this->mrTotalPages)
    {
      if (isset($this->_mCategoryId))
        $this->mLinkToNextPage =
          Link::ToCategory($this->_mDepartmentId, $this->_mCategoryId,
                           $this->mPage + 1);
      elseif (isset($this->_mDepartmentId))
        $this->mLinkToNextPage =
          Link::ToDepartment($this->_mDepartmentId, $this->mPage + 1);
      else
        $this->mLinkToNextPage = Link::ToIndex($this->mPage + 1);
    }

    // Construire le lien Précédent
    if ($this->mPage > 1)
    {
      if (isset($this->_mCategoryId))
        $this->mLinkToPreviousPage =
          Link::ToCategory($this->_mDepartmentId, $this->_mCategoryId,
                           $this->mPage - 1);
      elseif (isset($this->_mDepartmentId))
        $this->mLinkToPreviousPage =
          Link::ToDepartment($this->_mDepartmentId, $this->mPage - 1);
      else
        $this->mLinkToPreviousPage = Link::ToIndex($this->mPage - 1);
    }
  }


    // Construire des liens pour les pages de détails des produits
    for ($i = 0; $i < count($this->mProducts); $i++)
    {
      $this->mProducts[$i]['link_to_product'] =
        Link::ToProduct($this->mProducts[$i]['product_id']);

      if ($this->mProducts[$i]['thumbnail'])
        $this->mProducts[$i]['thumbnail'] =
          Link::Build('product_images/' . $this->mProducts[$i]['thumbnail']);
    }
  }

}
?>