<?php
class StoreFront
{
  public $mSiteUrl;
  // Définir le fichier de gabarit pour le contenu de la page
  public $mContentsCell = 'blank.tpl';
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
    if (isset ($_GET['DepartmentId']))
    {
      $this->mContentsCell = 'department.tpl';
      $this->mCategoriesCell = 'categories_list.tpl';
    }
  }
}
?>