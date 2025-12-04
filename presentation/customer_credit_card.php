<?php
    class CustomerCreditCard
    {
        // Attributs publics
        public $mCardHolderError;
        public $mCardNumberError;
        public $mExpDateError;
        public $mCardTypesError;
        public $mPlainCreditCard;
        public $mCardTypes;
        public $mLinkToCreditCardDetails;
        public $mLinkToCancelPage;

        // Attributs privés
        private $_mErrors = 0;

        public function __construct()
        {
            $this->mPlainCreditCard = array('card_holder' => '',
                'card_number' => '', 'issue_date' => '', 'expiry_date' => '',
                'issue_number' => '', 'card_type' => '', 'card_number_x' => '');

            // Définir la cible de l'action du formulaire
            $this->mLinkToCreditCardDetails = Link::ToCreditCardDetails();

            // Définir la page d'annulation
            if (isset ($_SESSION['customer_cancel_link']))
                $this->mLinkToCancelPage = $_SESSION['customer_cancel_link'];
            else
                $this->mLinkToCancelPage = Link::ToIndex();

            $this->mCardTypes = array ('Mastercard' => 'Mastercard',
                'Visa' => 'Visa', 'Mastercard' => 'Mastercard',
                'Switch' => 'Switch', 'Solo' => 'Solo',
                'American Express' => 'American Express');

            // Vérifier si nous avons soumis des données
            if (isset ($_POST['sended']))
            {
                // Initialisation/validation
                if (empty ($_POST['cardHolder']))
                {
                    $this->mCardHolderError = 1;
                    $this->_mErrors++;
                }
                else
                    $this->mPlainCreditCard['card_holder'] = $_POST['cardHolder'];

                if (empty ($_POST['cardNumber']))
                {
                    $this->mCardNumberError = 1;
                    $this->_mErrors++;
                }
                else
                    $this->mPlainCreditCard['card_number'] = $_POST['cardNumber'];

                if (empty ($_POST['expDate']))
                {
                    $this->mExpDateError = 1;
                    $this->_mErrors++;
                }
                else
                    $this->mPlainCreditCard['expiry_date'] = $_POST['expDate'];

                if (isset ($_POST['issueDate']))
                    $this->mPlainCreditCard['issue_date'] = $_POST['issueDate'];

                if (isset ($_POST['issueNumber']))
                    $this->mPlainCreditCard['issue_number'] = $_POST['issueNumber'];

                $this->mPlainCreditCard['card_type'] = $_POST['cardType'];

                if (empty ($this->mPlainCreditCard['card_type']))
                {
                    $this->mCardTypeError = 1;
                    $this->_mErrors++;
                }
            }
        }

        public function init()
        {
            if (!isset ($_POST['sended']))
            {
                // Obtenir les informations de carte de crédit
                $this->mPlainCreditCard = Customer::GetPlainCreditCard();
            }
            elseif ($this->_mErrors == 0)
            {
                // Mettre à jour les informations de carte de crédit
                Customer::UpdateCreditCardDetails($this->mPlainCreditCard);
                header('Location:' . $this->mLinkToCancelPage);
                exit();
            }
        }
    }