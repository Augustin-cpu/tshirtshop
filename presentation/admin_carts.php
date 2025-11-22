<?php
// Classe qui prend en charge la fonctionnalité d'administration des paniers
class AdminCarts
{
// Variables publiques disponibles dans le modèle smarty
    public $mMessage;
    public $mDaysOptions = array (0 => 'Tous les paniers d\'achat',
        1 => 'Un jour d\'ancienneté',
        10 => 'Dix jours d\'ancienneté',
        20 => 'Vingt jours d\'ancienneté',
        30 => 'Trente jours d\'ancienneté',
        90 => 'Quatre-vingt-dix jours d\'ancienneté');
    public $mSelectedDaysNumber = 0;
    public $mLinkToCartsAdmin;

// Membres privés
    public $_mAction = '';
    public $mDeletedCarts;

// Constructeur de classe
    public function __construct()
    {
        foreach ($_POST as $key => $value)
// Si un bouton de soumission a été cliqué ...
            if (substr($key, 0, 6) == 'submit')
            {
// Obtenir la portée du bouton de soumission
                $this->_mAction = substr($key, strlen('submit_'), strlen($key));
// Obtenir le nombre de jours sélectionné
                if (isset ($_POST['days']))
                    $this->mSelectedDaysNumber = (int) $_POST['days'];
                else
                    trigger_error('valeur de days non définie');
            }

        $this->mLinkToCartsAdmin = Link::ToCartsAdmin();
    }

    public function init()
    {
// Si on compte les paniers d'achat ...
        if ($this->_mAction == 'count')
        {
            $count_old_carts =
                ShoppingCart::CountOldShoppingCarts($this->mSelectedDaysNumber);

            if ($count_old_carts == 0)
                $count_old_carts = 'aucun';

            $this->mMessage = 'Il y a ' . $count_old_carts .
                ' anciens paniers d\'achat (option sélectionnée : ' .
                $this->mDaysOptions[$this->mSelectedDaysNumber] .
                ').';
        }

// Si on supprime les paniers d'achat ...
        if ($this->_mAction == 'delete')
        {
            $this->mDeletedCarts =
                ShoppingCart::DeleteOldShoppingCarts($this->mSelectedDaysNumber);

            $this->mMessage = 'Les anciens paniers d\'achat ont été supprimés de la
base de données (option sélectionnée : ' .
                $this->mDaysOptions[$this->mSelectedDaysNumber] .').';
        }
    }
}
