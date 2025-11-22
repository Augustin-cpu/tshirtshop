<?php
// Classe qui gère le récapitulatif du panier d'achat
class CartSummary
{
// Variables publiques à utiliser dans le modèle Smarty
    public $mTotalAmount;
    public $mItems;
    public $mLinkToCartDetails;
    public $mEmptyCart;

// Constructeur de classe
    public function __construct()
    {
        /* Calculer le montant total pour le panier d'achat
        avant taxes applicables et/ou frais de livraison */
        $this->mTotalAmount = ShoppingCart::GetTotalAmount();

// Obtenir les produits du panier d'achat
        $this->mItems = ShoppingCart::GetCartProducts(GET_CART_PRODUCTS);

        if (empty($this->mItems))
            $this->mEmptyCart = true;
        else
            $this->mEmptyCart = false;

        $this->mLinkToCartDetails = Link::ToCart();
    }
}
