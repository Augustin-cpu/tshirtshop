<?php
class StoreFront
{
  public $mSiteUrl;
  // Définir le fichier de template pour le contenu de la page
  public $mContentsCell = 'first_page_contents.tpl';
  // Définir le fichier de template pour la cellule des catégories
  public $mCategoriesCell = 'blank.tpl';
  // Titre de la page
  // Contrôle la visibilité de la navigation de la boutique (départements, etc.)
  public $mHideBoxes = false;
  // Définit le fichier de modèle pour la cellule du récapitulatif du panier (AJOUTÉ)
  public $mCartSummaryCell = 'blank.tpl';

  public $mPageTitle;
  // PayPal continue shopping link
  public $mPayPalContinueShoppingLink;
    // Définir le fichier de modèle pour la cellule de connexion ou connecté
  public $mLoginOrLoggedCell = 'customer_login.tpl';
  // Constructeur de classe
  public function __construct()
  {
    $this->mSiteUrl = Link::Build('');
  }
  // Initialiser l'objet de présentation
  public function init()
  {
      $_SESSION['link_to_store_front'] =
          Link::Build(str_replace(VIRTUAL_LOCATION, '', getenv('REQUEST_URI')));
    // Create "Continue Shopping" link for the PayPal shopping cart
      // Build the "continue shopping" link
      // Build the "continue shopping" link
  if (!isset ($_GET['CartAction']) && !isset($_GET['Logout']) &&
      !isset($_GET['RegisterCustomer']) &&
      !isset($_GET['AddressDetails']) &&
      !isset($_GET['CreditCardDetails']) &&
      !isset($_GET['AccountDetails']) &&
      !isset($_GET['Checkout']))
      $_SESSION['link_to_last_page_loaded'] = $_SESSION['link_to_store_front'];
      if (Customer::IsAuthenticated())
          $this->mLoginOrLoggedCell = 'customer_logged.tpl';
      if (isset ($_GET['RegisterCustomer']) ||
          isset ($_GET['AccountDetails']))
          $this->mContentsCell = 'customer_details.tpl';
      elseif (isset ($_GET['AddressDetails']))
          $this->mContentsCell = 'customer_address.tpl';

elseif (isset ($_GET['CreditCardDetails']))
$this->mContentsCell = 'customer_credit_card.tpl';
// Build the "cancel" link for customer details pages
  if (!isset($_GET['Logout']) &&
      !isset($_GET['RegisterCustomer']) &&
      !isset($_GET['AddressDetails']) &&
      !isset($_GET['CreditCardDetails']) &&
      !isset($_GET['AccountDetails']))
      $_SESSION['customer_cancel_link'] = $_SESSION['link_to_store_front'];
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
    } // Load search result page if we're searching the catalog
    elseif (isset($_GET['SearchResults']))
      $this->mContentsCell = 'search_results.tpl';

    // Charger la page de détails du produit si nous visitons un produit
    if (isset($_GET['ProductId']))
      $this->mContentsCell = 'product.tpl';
    // Charger la page de détails du produit si l'on visite un produit
    if (isset($_GET['ProductId']))
      $this->mContentsCell = 'product.tpl';
    // Charger la page de résultats de recherche si l'on recherche dans le catalogue
    elseif (isset($_GET['SearchResults']))
      $this->mContentsCell = 'search_results.tpl';
    // Charger le titre de la page
      // Charger le modèle du panier d'achat ou du récapitulatif du panier (MODIFICATION CLÉ)
      if (isset ($_GET['CartAction']))
          $this->mContentsCell = 'cart_details.tpl';
      else
          $this->mCartSummaryCell = 'cart_summary.tpl'; // Charge le récapitulatif par défaut
    $this->mPageTitle = $this->_GetPageTitle();
    // Charger la page de détails du produit si l'on visite un produit
    if (isset($_GET['ProductId']))
      $this->mContentsCell = 'product.tpl';

  if (isset ($_GET['Checkout']))
  {
      if (Customer::IsAuthenticated())
          $this->mContentsCell = 'checkout_info.tpl';
      else
          $this->mContentsCell = 'checkout_not_logged.tpl';
      $this->mHideBoxes = true;
  }

    // Charger le titre de la page
    $this->mPageTitle = $this->_GetPageTitle();
  }

  // Retourne le titre de la page
  private function _GetPageTitle()
  {
    $page_title = 'TShirtShop: ' .
      'Catalogue de produits de démonstration de Beginning PHP and MySQL E-Commerce';

    if (isset($_GET['DepartmentId']) && isset($_GET['CategoryId'])) {
      $page_title = 'TShirtShop: ' .
        Catalog::GetDepartmentName($_GET['DepartmentId']) . ' - ' .
        Catalog::GetCategoryName($_GET['CategoryId']);

      if (isset($_GET['Page']) && ((int)$_GET['Page']) > 1)
        $page_title .= ' - Page ' . ((int)$_GET['Page']);
    } elseif (isset($_GET['DepartmentId'])) {
      $page_title = 'TShirtShop: ' .
        Catalog::GetDepartmentName($_GET['DepartmentId']);

      if (isset($_GET['Page']) && ((int)$_GET['Page']) > 1)
        $page_title .= ' - Page ' . ((int)$_GET['Page']);
    } elseif (isset($_GET['ProductId'])) {
      $page_title = 'TShirtShop: ' .
        Catalog::GetProductName($_GET['ProductId']);
    } elseif (isset($_GET['SearchResults'])) {
      $page_title = 'TShirtShop: "';
      // Display the search string
      $page_title .= trim(str_replace('-', ' ', $_GET['SearchString'])) . '" (';
      // Display "all-words search " or "any-words search"
      $all_words = isset($_GET['AllWords']) ? $_GET['AllWords'] : 'off';
      $page_title .= (($all_words == 'on') ? 'all' : 'any') .
        '-words search';
      // Display page number
      if (isset($_GET['Page']) && ((int)$_GET['Page']) > 1)
        $page_title .= ', page ' . ((int)$_GET['Page']);
      $page_title .= ')';
    } else {
      if (isset($_GET['Page']) && ((int)$_GET['Page']) > 1)
        $page_title .= ' - Page ' . ((int)$_GET['Page']);
    }

    return $page_title;
  }
}
