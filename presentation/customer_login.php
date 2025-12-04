<?php
    class CustomerLogin
    {
        // Éléments publics
        public $mErrorMessage;
        public $mLinkToLogin;
        public $mLinkToRegisterCustomer;
        public $mEmail = '';

        // Constructeur de la classe
        public function __construct()
        {
            if (USE_SSL == 'yes' && getenv('HTTPS') != 'on')
                $this->mLinkToLogin =
                    Link::Build(str_replace(VIRTUAL_LOCATION, '', getenv('REQUEST_URI')),
                        'https');
            else
                $this->mLinkToLogin =
                    Link::Build(str_replace(VIRTUAL_LOCATION, '', getenv('REQUEST_URI')));

            $this->mLinkToRegisterCustomer = Link::ToRegisterCustomer();
        }

        public function init()
        {
            // Décider si nous avons soumis le formulaire
            if (isset ($_POST['Login']))
            {
                // Obtenir le statut de connexion
                $login_status = Customer::IsValid($_POST['email'], $_POST['password']);

                switch ($login_status)
                {
                    case 2:
                        $this->mErrorMessage = 'E-mail non reconnu.';
                        $this->mEmail = $_POST['email'];
                        break;
                    case 1:
                        $this->mErrorMessage = 'Mot de passe non reconnu.';
                        $this->mEmail = $_POST['email'];
                        break;
                    case 0:
                        $redirect_to_link =
                            Link::Build(str_replace(VIRTUAL_LOCATION, '',
                                getenv('REQUEST_URI')));
                        header('Location:' . $redirect_to_link);
                        exit();
                }
            }
        }
    }