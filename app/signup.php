<?php
require_once './response.php';
require_once './dboperations.php';

$res = new Response;

 if($_SERVER['REQUEST_METHOD'] != 'POST'){
    $res->setSuccess(false);
    $res->addMessages('Eltévedtél...');
    $res->setHttpStatusCode(405);
    $res->send();
    exit;         
}

$postData = file_get_contents('php://input');

$jsonData = json_decode($postData);



if(!$jsonData = json_decode($postData)){
    $res->setSuccess(false);
    $res->addMessages('Töltse ki a mezőket!');
    $res->send();
    exit;
}

$name = $jsonData->name;
$email = $jsonData->email;



if($errors = validateSignUp($name, $email)){
    $res->setSuccess(false);
    foreach($errors as $error){
        $res->addMessages($error);
    }
    $res->send();
    exit;
}



$result = save($name, $email);


if($result == 1){
    $res->setSuccess(true);
    $res->addMessages('Sikeres feliratkozás! :)');
}else{
    $res->setSuccess(false);
    $res->addMessages('A feliratkozás nem sikerült, próbála később! :(');

}
$res->setHttpStatusCode(200);
$res->send();



function validateSignUp($name, $email){
    $errors = [];
    if( strlen($name) > 30 || $name === "" ){
        $errors[] = 'A név mező nem lehet üres, és max 30 karakter hosszú lehet.';
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 30  ){
        $errors[] = "Az email email formátum kell, hogy legyen, és max karakter hosszú lehet.";
    }
    if(uniqueEmail($email) !== 0){
        $errors[] = "Ezzel az email címmmel már van feliratkozás";
    }
    return $errors;

}