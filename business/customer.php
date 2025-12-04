<?php
// Classe de la couche métier qui gère la fonctionnalité des comptes clients
    class Customer
    {
        // Vérifie si un customer_id existe en session
        public static function IsAuthenticated()
        {
            if (!(isset ($_SESSION['tshirtshop_customer_id'])))
                return 0;
            else
                return 1;
        }

        // Retourne customer_id et password pour le client avec l'email $email
        public static function GetLoginInfo($email)
        {
            // Construire la requête SQL
            $sql = 'CALL customer_get_login_info(:email)';
            // Construire le tableau de paramètres
            $params = array (':email' => $email);
            // Exécuter la requête et retourner les résultats
            return DatabaseHandler::GetRow($sql, $params);
        }

        public static function IsValid($email, $password)
        {
            $customer = self::GetLoginInfo($email);

            if (empty ($customer['customer_id']))
                return 2;

            $customer_id = $customer['customer_id'];
            $hashed_password = $customer['password'];

            if (PasswordHasher::Hash($password) != $hashed_password)
                return 1;
            else
            {
                $_SESSION['tshirtshop_customer_id'] = $customer_id;
                return 0;
            }
        }

        public static function Logout()
        {
            unset($_SESSION['tshirtshop_customer_id']);
        }

        public static function GetCurrentCustomerId()
        {
            if (self::IsAuthenticated())
                return $_SESSION['tshirtshop_customer_id'];
            else
                return 0;
        }

        /* Ajoute un nouveau compte client, le connecte si $addAndLogin est true
        et retourne customer_id */
        public static function Add($name, $email, $password, $addAndLogin = true)
        {
            $hashed_password = PasswordHasher::Hash($password);
            // Construire la requête SQL
            $sql = 'CALL customer_add(:name, :email, :password)';
            // Construire le tableau de paramètres
            $params = array (':name' => $name, ':email' => $email,
                ':password' => $hashed_password);
            // Exécuter la requête et obtenir le customer_id
            $customer_id = DatabaseHandler::GetOne($sql, $params);

            if ($addAndLogin)
                $_SESSION['tshirtshop_customer_id'] = $customer_id;

            return $customer_id;
        }

        public static function Get($customerId = null)
        {
            if (is_null($customerId))
                $customerId = self::GetCurrentCustomerId();

            // Construire la requête SQL
            $sql = 'CALL customer_get_customer(:customer_id)';
            // Construire le tableau de paramètres
            $params = array (':customer_id' => $customerId);
            // Exécuter la requête et retourner les résultats
            return DatabaseHandler::GetRow($sql, $params);
        }

        public static function UpdateAccountDetails($name, $email, $password,
                                                    $dayPhone, $evePhone, $mobPhone,
                                                    $customerId = null)
        {
            if (is_null($customerId))
                $customerId = self::GetCurrentCustomerId();

            $hashed_password = PasswordHasher::Hash($password);
            // Construire la requête SQL
            $sql = 'CALL customer_update_account(:customer_id, :name, :email,
            :password, :day_phone, :eve_phone, :mob_phone)';
            // Construire le tableau de paramètres
            $params = array (':customer_id' => $customerId, ':name' => $name,
                ':email' => $email, ':password' => $hashed_password,
                ':day_phone' => $dayPhone, ':eve_phone' => $evePhone,
                ':mob_phone' => $mobPhone);
            // Exécuter la requête
            DatabaseHandler::Execute($sql, $params);
        }

        public static function DecryptCreditCard($encryptedCreditCard)
        {
            $secure_card = new SecureCard();
            $secure_card->LoadEncryptedDataAndDecrypt($encryptedCreditCard);

            $credit_card = array();
            $credit_card['card_holder'] = $secure_card->CardHolder;
            $credit_card['card_number'] = $secure_card->CardNumber;
            $credit_card['issue_date'] = $secure_card->IssueDate;
            $credit_card['expiry_date'] = $secure_card->ExpiryDate;
            $credit_card['issue_number'] = $secure_card->IssueNumber;
            $credit_card['card_type'] = $secure_card->CardType;
            $credit_card['card_number_x'] = $secure_card->CardNumberX;

            return $credit_card;
        }

        public static function GetPlainCreditCard()
        {
            $customer_data = self::Get();

            if (!(empty ($customer_data['credit_card'])))
                return self::DecryptCreditCard($customer_data['credit_card']);
            else
                return array('card_holder' => '', 'card_number' => '',
                    'issue_date' => '', 'expiry_date' => '',
                    'issue_number' => '', 'card_type' => '',
                    'card_number_x' => '');
        }

        public static function UpdateCreditCardDetails($plainCreditCard,
                                                       $customerId = null)
        {
            if (is_null($customerId))
                $customerId = self::GetCurrentCustomerId();

            $secure_card = new SecureCard();
            $secure_card->LoadPlainDataAndEncrypt($plainCreditCard['card_holder'],
                $plainCreditCard['card_number'], $plainCreditCard['issue_date'],
                $plainCreditCard['expiry_date'], $plainCreditCard['issue_number'],
                $plainCreditCard['card_type']);
            $encrypted_card = $secure_card->EncryptedData;

            // Construire la requête SQL
            $sql = 'CALL customer_update_credit_card(:customer_id, :credit_card)';
            // Construire le tableau de paramètres
            $params = array (':customer_id' => $customerId,
                ':credit_card' => $encrypted_card);
            // Exécuter la requête
            DatabaseHandler::Execute($sql, $params);
        }

        public static function GetShippingRegions()
        {
            // Construire la requête SQL
            $sql = 'CALL customer_get_shipping_regions()';
            // Exécuter la requête et retourner les résultats
            return DatabaseHandler::GetAll($sql);
        }

        public static function UpdateAddressDetails($address1, $address2, $city,
                                                    $region, $postalCode, $country,
                                                    $shippingRegionId, $customerId = null)
        {
            if (is_null($customerId))
                $customerId = self::GetCurrentCustomerId();

            // Construire la requête SQL
            $sql = 'CALL customer_update_address(:customer_id, :address_1,
            :address_2, :city, :region, :postal_code, :country,
            :shipping_region_id)';
            // Construire le tableau de paramètres
            $params = array (':customer_id' => $customerId,
                ':address_1' => $address1, ':address_2' => $address2,
                ':city' => $city, ':region' => $region,
                ':postal_code' => $postalCode,
                ':country' => $country,
                ':shipping_region_id' => $shippingRegionId);
            // Exécuter la requête
            DatabaseHandler::Execute($sql, $params);
        }
    }