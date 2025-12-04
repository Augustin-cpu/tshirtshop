<?php
    class CustomerDetails
    {
        // Attributs publics
        public $mEditMode = 0;
        public $mEmail;
        public $mName;
        public $mPassword;
        public $mDayPhone = null;
        public $mEvePhone = null;
        public $mMobPhone = null;
        public $mNameError = 0;
        public $mEmailAlreadyTaken = 0;
        public $mEmailError = 0;
        public $mPasswordError = 0;
        public $mPasswordConfirmError = 0;
        public $mPasswordMatchError = 0;
        public $mLinkToAccountDetails;
        public $mLinkToCancelPage;

        // Attributs privés
        private $_mErrors = 0;

        // Constructeur de la classe
        public function __construct()
        {
            // Vérifier si nous avons un nouvel utilisateur ou si nous modifions les détails d'un client existant
            if (Customer::IsAuthenticated())
                $this->mEditMode = 1;

            if ($this->mEditMode == 0)
                $this->mLinkToAccountDetails = Link::ToRegisterCustomer();
            else
                $this->mLinkToAccountDetails = Link::ToAccountDetails();

            // Définir la page d'annulation
            if (isset ($_SESSION['customer_cancel_link']))
                $this->mLinkToCancelPage = $_SESSION['customer_cancel_link'];
            else
                $this->mLinkToCancelPage = Link::ToIndex();

            // Vérifier si nous avons soumis des données
            if (isset ($_POST['sended']))
            {
                // Le nom ne peut pas être vide
                if (empty ($_POST['name']))
                {
                    $this->mNameError = 1;
                    $this->_mErrors++;
                }
                else
                    $this->mName = $_POST['name'];

                if ($this->mEditMode == 0 && empty ($_POST['email']))
                {
                    $this->mEmailError = 1;
                    $this->_mErrors++;
                }
                else
                    $this->mEmail = $_POST['email'];

                // Le mot de passe ne peut pas être vide
                if (empty ($_POST['password']))
                {
                    $this->mPasswordError = 1;
                    $this->_mErrors++;
                }
                else
                    $this->mPassword = $_POST['password'];

                // La confirmation du mot de passe ne peut pas être vide
                if (empty ($_POST['passwordConfirm']))
                {
                    $this->mPasswordConfirmError = 1;
                    $this->_mErrors++;
                }
                else
                    $password_confirm = $_POST['passwordConfirm'];

                // Le mot de passe et la confirmation du mot de passe doivent être identiques
                if (!isset ($password_confirm) ||
                    $this->mPassword != $password_confirm)
                {
                    $this->mPasswordMatchError = 1;
                    $this->_mErrors++;
                }

                if ($this->mEditMode == 1)
                {
                    if (!empty ($_POST['dayPhone']))
                        $this->mDayPhone = $_POST['dayPhone'];
                    if (!empty ($_POST['evePhone']))
                        $this->mEvePhone = $_POST['evePhone'];
                    if (!empty ($_POST['mobPhone']))
                        $this->mMobPhone = $_POST['mobPhone'];
                }
            }
        }

        public function init()
        {
            // Si nous avons soumis des données et qu'il n'y a pas d'erreurs dans les données soumises
            if ((isset ($_POST['sended'])) && ($this->_mErrors == 0))
            {
                // Vérifier si nous avons un client avec l'e-mail soumis...
                $customer_read = Customer::GetLoginInfo($this->mEmail);

                /* ...si nous en avons un et que nous sommes en mode 'nouvel utilisateur',
                alors l'e-mail est déjà pris */
                if ((!(empty ($customer_read['customer_id']))) &&
                    ($this->mEditMode == 0))
                {
                    $this->mEmailAlreadyTaken = 1;
                    return;
                }

                // Nous avons un nouvel utilisateur ou nous mettons à jour les détails d'un utilisateur existant
                if ($this->mEditMode == 0)
                    Customer::Add($this->mName, $this->mEmail, $this->mPassword);
                else
                    Customer::UpdateAccountDetails($this->mName, $this->mEmail,
                        $this->mPassword, $this->mDayPhone, $this->mEvePhone,
                        $this->mMobPhone);

                header('Location:' . $this->mLinkToCancelPage);
                exit();
            }

            if ($this->mEditMode == 1 && !isset ($_POST['sended']))
            {
                // Nous modifions les détails d'un client existant
                $customer_data = Customer::Get();
                $this->mName = $customer_data['name'];
                $this->mEmail = $customer_data['email'];
                $this->mDayPhone = $customer_data['day_phone'];
                $this->mEvePhone = $customer_data['eve_phone'];
                $this->mMobPhone = $customer_data['mob_phone'];
            }
        }
    }
