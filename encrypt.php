<?php
session_start();

if(!$_SESSION['IV']){
    $_SESSION["IV"] = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
}
if($_POST){
    $name = $_POST['name'];
    $password = $_POST['password'];
    $message = $_POST['secret_message'];

    if(strlen($message)>0){
        //Encrpt
        encrypt($message, $password);
    }else{
        //Decrypt
        $decrypted_string = decrypt($password);
    }
}

function encrypt($message, $key){
    $secret_key = md5($key);
    $encrypted_string = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $secret_key, $message, MCRYPT_MODE_CBC, $_SESSION["IV"]);
    $_SESSION['fileEncrypted'] = $encrypted_string;
}

function decrypt($key){
    $secret_key = md5($key);
    $encrypted_string = $_SESSION['fileEncrypted'];
    $decrypted_string = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $secret_key, $encrypted_string, MCRYPT_MODE_CBC, $_SESSION["IV"]);
    return $decrypted_string;
}

?>

<h1>Encrypt-R-us</h1>
<form action="" method="POST">
<span>Naam: </span><input type="text" name='name' /><br /><br />
<span>Geheime text: </span><textarea name='secret_message'><?php if(isset($decrypted_string)){ echo $decrypted_string;} ?></textarea><br /><br />
<span>Wachtwoord: </span><input type="password" name='password' /><br /><br />
<input type="submit" value="Verstuur!" />
</form>