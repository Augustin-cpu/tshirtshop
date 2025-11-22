<?php
// Classe qui gère le panier d'achat
class CartDetails
{
// Variables publiques disponibles dans le modèle Smarty
    public $mCartProducts;
    public $mSavedCartProducts;
    public $mTotalAmount;
    public $mIsCartNowEmpty = 0; // Le panier d'achat est-il vide ?
    public $mIsCartLaterEmpty = 0; // La liste 'enregistrée pour plus tard' est-elle vide ?
    public $mLinkToContinueShopping;
    public $mUpdateCartTarget;

// Attributs privés
    private $_mItemId;
    private $_mCartAction;

// Constructeur de classe
    public function __construct()
    {
        if (isset ($_GET['CartAction']))
            $this->_mCartAction = $_GET['CartAction'];
        else
            trigger_error('CartAction non défini', E_USER_ERROR);

// Ces opérations de panier nécessitent un ID de produit valide
        if ($this->_mCartAction == ADD_PRODUCT ||
            $this->_mCartAction == REMOVE_PRODUCT ||
            $this->_mCartAction == SAVE_PRODUCT_FOR_LATER ||
            $this->_mCartAction == MOVE_PRODUCT_TO_CART)
        {
            if (isset ($_GET['ItemId']))
                $this->_mItemId = $_GET['ItemId'];
            else
                trigger_error('ItemId doit être défini pour ce type de requête',
                    E_USER_ERROR);
        }

        $this->mUpdateCartTarget = Link::ToCart(UPDATE_PRODUCTS_QUANTITIES);

// Définir la cible du lien "Continuer vos achats"
        if (isset ($_SESSION['link_to_last_page_loaded']))
            $this->mLinkToContinueShopping = $_SESSION['link_to_last_page_loaded'];
    }

    public function init()
    {
        switch ($this->_mCartAction)
        {
            case ADD_PRODUCT:
                $selected_attributes = array ();
                $selected_attribute_values = array ();

// Obtenir les attributs de produit sélectionnés s'il y en a
                foreach ($_POST as $key => $value)
                {
// S'il y a des champs commençant par "attr_" dans le tableau POST
                    if (substr($key, 0, 5) == 'attr_')
                    {
// Obtenir le nom et la valeur de l'attribut sélectionné
                        $selected_attributes[] = substr($key, strlen('attr_'));
                        $selected_attribute_values[] = $_POST[$key];
                    }
                }

                $attributes = '';
                if (count($selected_attributes) > 0)
                    $attributes = implode('/', $selected_attributes) . ': ' .
                        implode('/', $selected_attribute_values);
                ShoppingCart::AddProduct($this->_mItemId, $attributes);

                if (!isset ($_GET['AjaxRequest']))
                    header('Location: ' . $this->mLinkToContinueShopping);
                else
                    return;
                break;

            case REMOVE_PRODUCT:
                ShoppingCart::RemoveProduct($this->_mItemId);
                if (!isset ($_GET['AjaxRequest']))
                    header('Location: ' . Link::ToCart());
                break;

            case UPDATE_PRODUCTS_QUANTITIES:
                for($i = 0; $i < count($_POST['itemId']); $i++)
                    ShoppingCart::Update($_POST['itemId'][$i], $_POST['quantity'][$i]);
                if (!isset ($_GET['AjaxRequest']))
                    header('Location: ' . Link::ToCart());
                break;

            case SAVE_PRODUCT_FOR_LATER:
                ShoppingCart::SaveProductForLater($this->_mItemId);
                if (!isset ($_GET['AjaxRequest']))
                    header('Location: ' . Link::ToCart());
                break;

            case MOVE_PRODUCT_TO_CART:
                ShoppingCart::MoveProductToCart($this->_mItemId);
                if (!isset ($_GET['AjaxRequest']))
                    header('Location: ' . Link::ToCart());
                break;

            default:
// Ne rien faire
                break;
        }

        /* Calculer le montant total pour le panier d'achat
        avant taxes applicables et/ou frais de livraison */
        $this->mTotalAmount = ShoppingCart::GetTotalAmount();
        // If the Place Order button was clicked ...
        if(isset ($_POST['place_order']))
        {
// Create the order and get the order ID
            $order_id = ShoppingCart::CreateOrder();
// This will contain the PayPal link
            $redirect =
                PAYPAL_URL . '&item_name=TShirtShop Order ' . urlencode('#') . $order_id .
                '&item_number=' . $order_id .
                '&amount=' . $this->mTotalAmount .
                '&currency_code=' . PAYPAL_CURRENCY_CODE .
                '&return=' . PAYPAL_RETURN_URL .
                '&cancel_return=' . PAYPAL_CANCEL_RETURN_URL;
// Redirection to the payment page
            header('Location: ' . $redirect);
            exit();
        }
// Obtenir les produits du panier d'achat
        $this->mCartProducts =
            ShoppingCart::GetCartProducts(GET_CART_PRODUCTS);

// Obtient les produits enregistrés pour plus tard
        $this->mSavedCartProducts =
            ShoppingCart::GetCartProducts(GET_CART_SAVED_PRODUCTS);

// Vérifier si nous avons un panier d'achat vide
        if (count($this->mCartProducts) == 0)
            $this->mIsCartNowEmpty = 1;

// Vérifier si nous avons une liste Enregistré pour plus tard vide
        if (count($this->mSavedCartProducts) == 0)
            $this->mIsCartLaterEmpty = 1;

// Construire les liens pour les actions du panier
        for ($i = 0; $i < count($this->mCartProducts); $i++)
        {
            $this->mCartProducts[$i]['save'] =
                Link::ToCart(SAVE_PRODUCT_FOR_LATER,
                    $this->mCartProducts[$i]['item_id']);
            $this->mCartProducts[$i]['remove'] =
                Link::ToCart(REMOVE_PRODUCT,
                    $this->mCartProducts[$i]['item_id']);
        }

        for ($i = 0; $i < count($this->mSavedCartProducts); $i++)
        {
            $this->mSavedCartProducts[$i]['move'] =
                Link::ToCart(MOVE_PRODUCT_TO_CART,
                    $this->mSavedCartProducts[$i]['item_id']);
            $this->mSavedCartProducts[$i]['remove'] =
                Link::ToCart(REMOVE_PRODUCT,
                    $this->mSavedCartProducts[$i]['item_id']);
        }
    }
}
