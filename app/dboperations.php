<?php
require_once './db.php';
$database = new Database;
$db = $database->getConnection();


function save($name, $email){
    $database = new Database;
    $db = $database->getConnection();


    try {
        $query = $db->prepare(
            'INSERT INTO
                signups 
            (name, email)
                VALUES
            (:name, :email)');
        
        $query->bindParam('name', $name, PDO::PARAM_STR);
        $query->bindParam('email', $email, PDO::PARAM_STR);
    
        $query->execute();

        return $query->rowCount();
    } catch (PDOException $ex) {

        //TODO: HANDLE AND LOG ERROR PROPERLY
        //throw $th;
    }
}

function uniqueEmail($email){
    $database = new Database;
    $db = $database->getConnection();

    
    try {
        $query = $db->prepare(
            'SELECT
                email
            FROM
                signups
            WHERE
                email = :email'
                

        );

        $query->bindParam('email', $email, PDO::PARAM_STR);

        $query->execute();

        return $query->rowCount();
    } catch (PDOExeption  $ex) {
        //TODO: HANDLE AND LOG ERROR PROPERLY
        //throw $th;
    }
}