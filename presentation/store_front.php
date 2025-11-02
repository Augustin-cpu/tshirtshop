<?php
class StoreFront
{
  public $mSiteUrl;
  // Définir le fichier de gabarit pour le contenu de la page
  public $mContentsCell = 'first_page_contents.tpl';
  // Définit le fichier de gabarit pour la cellule des catégories
  public $mCategoriesCell = 'blank.tpl';
  // Constructeur de classe
  public function __construct()
  {
    $this->mSiteUrl = Link::Build('');
  }
  // Initialiser l'objet de présentation
  public function init()
  {
    // Charger les détails du département si l'on visite un département
    if (isset($_GET['DepartmentId'])) {
      $this->mContentsCell = 'department.tpl';
      $this->mCategoriesCell = 'categories_list.tpl';
    } elseif (
      isset($_GET['ProductId']) &&
      isset($_SESSION['link_to_continue_shopping']) &&
      strpos($_SESSION['link_to_continue_shopping'], 'DepartmentId', 0)
      !== false
    ) {
      // Afficher les catégories si nous venons d'une page de département/catégorie
      $this->mCategoriesCell = 'categories_list.tpl';
    }

    // Charger la page de détails du produit si nous visitons un produit
    if (isset($_GET['ProductId']))
      $this->mContentsCell = 'product.tpl';
  }
}
