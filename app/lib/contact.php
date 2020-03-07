<?php
require __DIR__ . '/vendors/PHPMailer/PHPMailerAutoload.php';

$mailSent   = false;
$errors     = array(); 

if(isset($_GET['mailSent'])) {
    $mailSent = true;
}

$contactCookie = (isset($_COOKIE['UTCC_'])) ? $_COOKIE['UTCC_'] : 0 ;

function espaceString($string) {
    return (strip_tags(trim($string)));
}



if(isset($_POST['contactForm'])) { 


    $name       = espaceString($_POST['name']);
    $email      = espaceString($_POST['email']);
    $message    = espaceString($_POST['message']);


    $mail = new PHPMailer();            
    
    if(strlen($name) < 2)              { $errors[] = 'Votre nom est invalide'; }
    if(!$mail->validateAddress($email)){ $errors[] = 'Votre email est invalide'; }
    if(strlen($message) < 10)          { $errors[] = 'Votre message est trop court'; }
    
    if(count($errors) == 0) {
        $msg =   'Refuge droit de vivre - Formulaire de contact ' . "\n"
                .'___________________________________________________' . "\n" . "\n" 
                .'    Nom/Prenom : '. $name . "\n"
                .'    Email : '.      $email . "\n"
                .'___________________________________________________' . "\n" . "\n"
                .$message;

            $mail->CharSet = 'utf-8';
            $mail->isSMTP(); 
            $mail->isHTML(false);
            $mail->setFrom('no-reply@droit-de-vivre.com', 'Refuge droit de vivre');
            $mail->AddReplyTo($email, $name);
            $mail->Subject = 'Refuge droit de vivre - Formulaire de contact';
            $mail->Body = $msg;

            $mail->addAddress('contact@droit-de-vivre.com');
            
            //$mail->addAddress('matt@rentabiliweb.com', 'Matt');
            try {
                $mailSent = $mail->send();
                header("Location: /contact.html?mailSent=" . (int) $mailSent);
                exit;
            } catch (Exception $e){
                $errors[] = 'Une erreur est survenue.';
            }
            unset($mail);

            unset($name);
            unset($company);
            unset($email);
            unset($message);
    }
    else {

    }

}
else {
    setcookie('UTCC_', 1, time()+60, '/', $domain);

}

?>