<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json;");


include 'config.php';


$posts = file_get_contents('php://input'); // to read the data from react(witch is in Json)
// we can see it in inspect -- Network -- Payload and Preview

$method = $_SERVER['REQUEST_METHOD']; // there is a case of GEt when we want to bring data , and case of POSt when we want to send data

switch($method){

        // Recieve data from database
    case "GET":
        $con=crud::connect();
        // echo $_SERVER['REQUEST_URI']; exit;

        $sql = "SELECT * FROM users
        INNER JOIN posts ON posts.user_id = users.id
        ORDER BY posts.created_at DESC" ;
        
        $path = explode('/', $_SERVER['REQUEST_URI']); 
        if(isset($path[4]) && is_numeric($path[4])){ 
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


    case "POST":
        $posts = json_decode(file_get_contents('php://input')); 
        // print_r($posts);
        $db = crud::connect()->prepare("INSERT INTO posts (user_id, content, group_id) VALUES ((SELECT id FROM users WHERE id = :userID), :cont, :Gid)");
        $db->bindValue(':userID' , $posts->userId); 
        $db->bindValue(':cont' , $posts->post);
        $db->bindValue(':Gid' , 0);
        if($db -> execute()) {
            $response = ['status' =>1, 'message'=>"Record created succcesfully"];
        }else{
            $response = ['status' =>0, 'message'=>"Record Faild to creat"];
        }
        echo json_encode($response);
        break;


        case "DELETE":
            $con=crud::connect();
        // echo $_SERVER['REQUEST_URI']; exit;
        $sql="DELETE FROM posts WHERE posts_id = :id" ;
        $path = explode('/', $_SERVER['REQUEST_URI']);
        // print_r($path);
            $db = $con->prepare($sql);
            $db->bindValue(':id' , $path[4]);
            if($db -> execute()) {
                $response = ['status' =>1, 'message'=>"Record Deleted succcesfully"];
            }else{
                $response = ['status' =>0, 'message'=>"Record Faild to Delete"];
            }
            echo json_encode($response); // to send this message as a Json (you can read it in inspect -- Newtwork)
            break;

}
