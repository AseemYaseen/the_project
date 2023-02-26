<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json;");


class crud{
 
 static function connect(){
   try{

   $con=new PDO('mysql:localhost=localhost;dbname=react-the-project','root','');

   return $con;
   

}catch(PDOException $error){

   echo 'Error' . $error->getMessage();


}

}

}

// crud::connect();
?>