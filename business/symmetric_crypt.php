<?php
    class SymmetricCrypt
    {
        // Clé de chiffrement/déchiffrement
        private static $_msSecretKey = 'From Dusk Till Dawn';

        // Le vecteur d'initialisation (Doit être de 16 bytes pour AES-128)
        private static $_msHexaIv = 'c7098adc8d6128b5d4b4f7b2fe7f7f05';

        // NOUVEAU : Utiliser l'algorithme de chiffrement OpenSSL AES-128-CBC
        private static $_msCipherAlgorithm = 'AES-128-CBC';

        // NOUVEAU : Utiliser un mode de fonctionnement qui gère la conversion de données
        private static $_msOptions = 0; // Pas de padding nécessaire
        /* Fonction qui chiffre la chaîne en texte clair reçue en paramètre
        et retourne le résultat au format hexadécimal */
        public static function Encrypt($plainString)
        {
            // Conversion de l'IVᵉ hex en binaire (nécessaire pour OpenSSL)
            $binary_iv = pack('H*', self::$_msHexaIv);

            // Chiffrer $plainString en utilisant OpenSSL
            // Le résultat est une chaîne BASE64 pour un stockage et une transmission sécurisés
            $encrypted_string = openssl_encrypt(
                $plainString,
                self::$_msCipherAlgorithm,
                self::$_msSecretKey,
                self::$_msOptions,
                $binary_iv
            );

            // Retourner la chaîne chiffrée (déjà encodée par openssl_encrypt)
            return $encrypted_string;
        }

        /* Fonction qui déchiffre la chaîne hexadécimale reçue en paramètre
        et retourne le résultat au format texte clair */
        public static function Decrypt($encryptedString)
        {
            // Conversion de l'IV hexa en binaire (nécessaire pour OpenSSL)
            $binary_iv = pack('H*', self::$_msHexaIv);

            // Déchiffrer $encryptedString
            $decrypted_string = openssl_decrypt(
                $encryptedString,
                self::$_msCipherAlgorithm,
                self::$_msSecretKey,
                self::$_msOptions,
                $binary_iv
            );

            // Supprime les caractères nuls à la fin (problème des anciennes méthodes)
            // Note: openssl gère souvent mieux le padding, mais trim peut être utile
            return trim($decrypted_string);
        }
    }
