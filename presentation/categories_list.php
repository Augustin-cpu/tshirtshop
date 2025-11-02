<?php
// Gère la liste des catégories
class CategoriesList
{
  // Variables publiques pour le gabarit Smarty
  public $mSelectedCategory = 0;
  public $mSelectedDepartment = 0;
  public $mCategories;

  // Le constructeur lit le paramètre de la chaîne de requête
  public function __construct()
  {
    if (!isset($_GET['ProductId'])) {
      if (isset($_GET['DepartmentId']))
        $this->mSelectedDepartment = (int)$_GET['DepartmentId'];
      else
        trigger_error('DepartmentId non défini');

      if (isset($_GET['CategoryId']))
        $this->mSelectedCategory = (int)$_GET['CategoryId'];
    } else {
      $continue_shopping =
        Link::QueryStringToArray($_SESSION['link_to_continue_shopping']);

      if (array_key_exists('DepartmentId', $continue_shopping))
        $this->mSelectedDepartment =
          (int)$continue_shopping['DepartmentId'];
      else
        trigger_error('DepartmentId non défini');

      if (array_key_exists('CategoryId', $continue_shopping))
        $this->mSelectedCategory =
          (int)$continue_shopping['CategoryId'];
    }
  }

  public function init()
  {
    $this->mCategories =
      Catalog::GetCategoriesInDepartment($this->mSelectedDepartment);

    // Construction des liens pour les pages de catégorie
    for ($i = 0; $i < count($this->mCategories); $i++)
      $this->mCategories[$i]['link_to_category'] =
        Link::ToCategory(
          $this->mSelectedDepartment,
          $this->mCategories[$i]['category_id']
        );
  }
}
