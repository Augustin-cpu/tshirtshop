<?php
    if (isset ($_POST['to_be_hashed']))
    {
        require_once 'include/config.php';
        require_once BUSINESS_DIR . 'password_hasher.php';
        $original_string = $_POST['to_be_hashed'];

        echo 'Le hachage de "' . $original_string . '" est ' .
            PasswordHasher::Hash($original_string, false);
        echo '<br />';

        echo '... et le hachage de "' . HASH_PREFIX . $original_string .
            '" (préfixe secret concaténé au mot de passe) est ' .
            PasswordHasher::Hash($original_string, true);
    }
?>
<br /><br />
<form action="test_hasher.php" method="post">
    <label>Écrivez votre mot de passe :</label>
    <input type="text" name="to_be_hashed" /><br />
    <input type="submit" value="Hacher" />
</form>