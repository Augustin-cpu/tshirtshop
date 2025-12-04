<?php
    if (isset ($_POST['my_string']))
    {
        require_once 'include/config.php';
        require_once BUSINESS_DIR . 'symmetric_crypt.php';
        $string = $_POST['my_string'];

        echo 'La chaîne est :<br />' . $string . '<br /><br />';

        $encrypted_string = SymmetricCrypt::Encrypt($string);
        echo 'Chaîne chiffrée : <br />' . $encrypted_string . '<br /><br />';

        $decrypted_string = SymmetricCrypt::Decrypt($encrypted_string);
        echo 'Chaîne déchiffrée :<br />' . $decrypted_string;
    }
?>
<br /><br />
<form action="test_encryption.php" method="post">
    Entrez la chaîne à chiffrer :
    <input type="text" name="my_string" /><br />
    <input type="submit" value="Chiffrer" />
</form>