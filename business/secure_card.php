<?php
// Représente une carte de crédit
    class SecureCard
    {
        // Membres privés contenant les détails de la carte de crédit
        private $_mIsDecrypted = false;
        private $_mIsEncrypted = false;
        private $_mCardHolder;
        private $_mCardNumber;
        private $_mIssueDate;
        private $_mExpiryDate;
        private $_mIssueNumber;
        private $_mCardType;
        private $_mEncryptedData;
        private $_mXmlCardData;

        // Constructeur de la classe
        public function __construct()
        {
            // Rien ici
        }

        // Déchiffrer les données
        public function LoadEncryptedDataAndDecrypt($newEncryptedData)
        {
            $this->_mEncryptedData = $newEncryptedData;
            $this->DecryptData();
        }

        // Chiffrer les données
        public function LoadPlainDataAndEncrypt($newCardHolder, $newCardNumber,
                                                $newIssueDate, $newExpiryDate,
                                                $newIssueNumber, $newCardType)
        {
            $this->_mCardHolder = $newCardHolder;
            $this->_mCardNumber = $newCardNumber;
            $this->_mIssueDate = $newIssueDate;
            $this->_mExpiryDate = $newExpiryDate;
            $this->_mIssueNumber = $newIssueNumber;
            $this->_mCardType = $newCardType;
            $this->EncryptData();
        }

        // Créer un XML avec les informations de carte de crédit
        private function CreateXml()
        {
            // Encoder les détails de la carte comme document XML
            $xml_card_data = &$this->_mXmlCardData;
            $xml_card_data = new DOMDocument();

            $document_root = $xml_card_data->createElement('CardDetails');

            $child = $xml_card_data->createElement('CardHolder');
            $child = $document_root->appendChild($child);
            $value = $xml_card_data->createTextNode($this->_mCardHolder);
            $value = $child->appendChild($value);

            $child = $xml_card_data->createElement('CardNumber');
            $child = $document_root->appendChild($child);
            $value = $xml_card_data->createTextNode($this->_mCardNumber);
            $value = $child->appendChild($value);

            $child = $xml_card_data->createElement('IssueDate');
            $child = $document_root->appendChild($child);
            $value = $xml_card_data->createTextNode($this->_mIssueDate);
            $value = $child->appendChild($value);

            $child = $xml_card_data->createElement('ExpiryDate');
            $child = $document_root->appendChild($child);
            $value = $xml_card_data->createTextNode($this->_mExpiryDate);
            $value = $child->appendChild($value);

            $child = $xml_card_data->createElement('IssueNumber');
            $child = $document_root->appendChild($child);
            $value = $xml_card_data->createTextNode($this->_mIssueNumber);
            $value = $child->appendChild($value);

            $child = $xml_card_data->createElement('CardType');
            $child = $document_root->appendChild($child);
            $value = $xml_card_data->createTextNode($this->_mCardType);
            $value = $child->appendChild($value);

            $document_root = $xml_card_data->appendChild($document_root);
        }

        // Extraire les informations des données XML de la carte de crédit
        private function ExtractXml($decryptedData)
        {
            $xml = simplexml_load_string($decryptedData);
            $this->_mCardHolder = (string) $xml->CardHolder;
            $this->_mCardNumber = (string) $xml->CardNumber;
            $this->_mIssueDate = (string) $xml->IssueDate;
            $this->_mExpiryDate = (string) $xml->ExpiryDate;
            $this->_mIssueNumber = (string) $xml->IssueNumber;
            $this->_mCardType = (string) $xml->CardType;
        }

        // Chiffre les données XML de la carte de crédit
        private function EncryptData()
        {
            // Mettre les données dans le document XML
            $this->CreateXml();

            // Chiffrer les données
            $this->_mEncryptedData =
                SymmetricCrypt::Encrypt($this->_mXmlCardData->saveXML());

            // Définir le drapeau chiffré
            $this->_mIsEncrypted = true;
        }

        // Déchiffre les données XML de la carte de crédit
        private function DecryptData()
        {
            // Déchiffrer les données
            $decrypted_data = SymmetricCrypt::Decrypt($this->_mEncryptedData);

            // Extraire les données du XML
            $this->ExtractXml($decrypted_data);

            // Définir le drapeau déchiffré
            $this->_mIsDecrypted = true;
        }

        public function __get($name)
        {
            if ($name == 'EncryptedData')
            {
                if ($this->_mIsEncrypted)
                    return $this->_mEncryptedData;
                else
                    throw new Exception('Data not encrypted');
            }
            elseif ($name == 'CardNumberX')
            {
                if ($this->_mIsDecrypted)
                    return 'XXXX-XXXX-XXXX-' .
                        substr($this->_mCardNumber, strlen($this->_mCardNumber) - 4, 4);
                else
                    throw new Exception('Data not decrypted');
            }
            elseif (in_array($name, array ('CardHolder', 'CardNumber', 'IssueDate',
                'ExpiryDate', 'IssueNumber', 'CardType')))
            {
                $name = '_m' . $name;
                if ($this->_mIsDecrypted)
                    return $this->$name;
                else
                    throw new Exception('Data not decrypted');
            }
            else
            {
                throw new Exception('Property ' . $name . ' not found');
            }
        }
    }
