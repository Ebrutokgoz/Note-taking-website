<?php
    try{
        $db=new PDO("mysql:host=localhost;dbname=notdefterim",'root','');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo $e->getMessage();
    }
?>

