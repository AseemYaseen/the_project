<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json;");

include 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {

  // Send data to database
  case "POST":
    $data = json_decode(file_get_contents('php://input'));
    $user_id = $data->user_id;
    $post_id = $data->post_id;

    $db = crud::connect();

    // Check if the user has already liked the post
    $check = $db->prepare("SELECT * FROM likes WHERE user_id = :usID AND post_id = :poIS");
    $check->bindValue(':usID', $user_id);
    $check->bindValue(':poIS', $post_id);
    $check->execute();

    if ($check->rowCount() == 0) {
      // Add a new like
      $insert = $db->prepare("INSERT INTO likes (user_id, post_id) VALUES (:usID, :poIS)");
      $insert->bindValue(':usID', $user_id);
      $insert->bindValue(':poIS', $post_id);
      $insert->execute();

      $response = ['status' => 1, 'message' => "Record created successfully"];
    } else {
      // Delete the existing like
      $delete = $db->prepare("DELETE FROM likes WHERE user_id = :usID AND post_id = :poIS");
      $delete->bindValue(':usID', $user_id);
      $delete->bindValue(':poIS', $post_id);
      $delete->execute();

      $response = ['status' => 0, 'message' => "Record deleted successfully"];
    }

    echo json_encode($response);
    break;

    case "GET":
        $con=crud::connect();
        // echo $_SERVER['REQUEST_URI']; exit;
        

        $sql="SELECT * FROM likes";
        $path = explode('/', $_SERVER['REQUEST_URI']); // explode exepting 2 parameters first how do you want to explode the string , then  the path
        //  print_r($path); // to show you array of the data
        if(isset($path[4]) && is_numeric($path[4])){ // to see if there is an id and it is a number in index of that array
            $sql .= " WHERE id =:id";
            $db = $con->prepare($sql);
            $db->bindValue(':id' , $path[4]);
            $db->execute();
            $data= $db->fetch(PDO::FETCH_ASSOC);
        }else{

            $db =$con->prepare($sql);
            $db->execute();
            $data= $db->fetchAll(PDO::FETCH_ASSOC);
            // echo json_encode($data);
        }

    echo json_encode($data);
    break;




    
    }