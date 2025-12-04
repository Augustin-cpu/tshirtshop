<?php
// Classe qui prend en charge la page de paiement
    class CheckoutInfo
    {
        // Attributs publics
        public $mCartItems;
        public $mTotalAmount;
        public $mCreditCardNote;
        public $mOrderButtonVisible;
        public $mNoShippingAddress = 'no';
        public $mNoCreditCard = 'no';
        public $mPlainCreditCard;
        public $mShippingRegion;
        public $mCustomerData;
        public $mLinkToCheckout;
        public $mLinkToCart;
        public $mLinkToContinueShopping;

        // Constructeur de la classe
        public function __construct()
        {
            $this->mLinkToCheckout = Link::ToCheckout();
            $this->mLinkToCart = Link::ToCart();
            $this->mLinkToContinueShopping = $_SESSION['link_to_last_page_loaded'];
        }

        public function init()
        {
            // Définir les membres à utiliser dans le modèle Smarty
            $this->mCartItems = ShoppingCart::GetCartProducts(GET_CART_PRODUCTS);
            $this->mTotalAmount = ShoppingCart::GetTotalAmount();
            $this->mCustomerData = Customer::Get();

            // Si le bouton "Passer la commande" a été cliqué, enregistrer la commande dans la base de données...
            if(isset ($_POST['place_order']))
            {
                // Créer la commande et obtenir l'ID de la commande
                $order_id = ShoppingCart::CreateOrder();

                // Ceci contiendra le lien PayPal
                $redirect =
                    PAYPAL_URL . '&item_name=TShirtShop Order ' .
                    urlencode('#') . $order_id .
                    '&item_number=' . $order_id .
                    '&amount=' . $this->mTotalAmount .
                    '¤cy_code=' . PAYPAL_CURRENCY_CODE .
                    '&return=' . PAYPAL_RETURN_URL .
                    '&cancel_return=' . PAYPAL_CANCEL_RETURN_URL;

                // Redirection vers la page de paiement
                header('Location: ' . $redirect);
                exit();
            }

            // Nous autorisons à passer des commandes uniquement si nous avons les détails client complets
            if (empty ($this->mCustomerData['credit_card']))
            {
                $this->mOrderButtonVisible = 'disabled="disabled"';
                $this->mNoCreditCard = 'yes';
            }
            else
            {
                $this->mPlainCreditCard = Customer::DecryptCreditCard(
                    $this->mCustomerData['credit_card']);
                $this->mCreditCardNote = 'Carte de crédit à utiliser : ' .
                    $this->mPlainCreditCard['card_type'] .
                    '<br />Numéro de carte : ' .
                    $this->mPlainCreditCard['card_number_x'];
            }

            if (empty ($this->mCustomerData['address_1']))
            {
                $this->mOrderButtonVisible = 'disabled="disabled"';
                $this->mNoShippingAddress = 'yes';
            }
            else
            {
                $shipping_regions = Customer::GetShippingRegions();
                foreach ($shipping_regions as $item)
                    if ($item['shipping_region_id'] ==
                        $this->mCustomerData['shipping_region_id'])
                        $this->mShippingRegion = $item['shipping_region'];
            }
        }
    }