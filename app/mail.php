<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require './phpmailer/src/Exception.php';
require './phpmailer/src/PHPMailer.php';
require './phpmailer/src/SMTP.php';
require_once './response.php';



$res = new Response;

// HA BÖNGÉSZŐBŐL JÖN IDE (GET) AKKOR NEM ENGEDJÜK TOVÁBB
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    $res->setSuccess(false);
    $res->addMessages('Eltévedtél...');
    $res->setHttpStatusCode(405);
    $res->send();
    exit;         
}

// KINYERJÜK A POSTOLT ADATOKAT
$postData = file_get_contents('php://input');

$jsonData = json_decode($postData);


// HA MÉGIS ELJUTOTT ADATOK NÉLKÜL IDÁIG, AKKOR ITT MÉG LEÁLLÍTHATJUK
if(!$jsonData = json_decode($postData)){
    $res->setSuccess(false);
    $res->addMessages('Nincs email cím és címzett.');
    $res->send();
    exit;
}

$name = $jsonData->name;
$email = $jsonData->email;



sendMail($email, $name, $res);



function sendMail($emailTo, $nameTo, $res){
    $mail = new PHPMailer(true);

    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();                                            
    $mail->Host       = '';                    
    $mail->SMTPAuth   = true;                                  
    $mail->Username   = '';                    
    $mail->Password   = '';                             
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         
    $mail->Port       = 465 ;                                

    //Recipients
    $mail->setFrom('gablini@demo.com', 'WebDev - Gablini');
    $mail->addAddress($emailTo, $nameTo);     

    $mail->isHTML(true);                                  
    $mail->Subject = 'Értesítés regisztrációról';
    $mail->Body    = '<h2>Tisztelt ' . $nameTo . '!</h2><p>Ezt a levelet azért kapta, mert retisztrált nálunk.</p>';
    $mail->AltBody = 'Tisztelt' .$nameTo . '! \nEzt a levelet azért kapta, mert regisztrált nálunk.';


    try {
        $mail->send();
        $res->setSuccess(true);
        $res->addMessages('Sikeres levélküldés');
        $res->setHttpStatusCode(200);
        $res->send();

    } catch (Exception $e) {
        // TODO: kezelni és logolni a sikertelenség okát.
        $res->setSuccess(false);
        $res->addMessages('Sikertelen levélküldés');
        $res->setHttpStatusCode(200);
        $res->send();
    }    
}

