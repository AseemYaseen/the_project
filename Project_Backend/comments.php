<?php include 'config.php'; ?>

<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
header("Content-Type: application/json;");


$method = $_SERVER['REQUEST_METHOD'];

$con=crud::connect();
switch ($method) {
    case 'GET' :
        $sql = "SELECT * FROM `users`
                INNER JOIN `comments` ON comments.user_id = users.id" ;
        $query = $con->prepare($sql);
        $query->execute();
        $comments = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($comments);
        break;
        
    case 'PUT' :
        $comments = json_decode(file_get_contents('php://input'));        
        $sql = "UPDATE comments 
                SET comment_content = ?
                WHERE comment_id = ?" ;
        $query = $con->prepare($sql);
        $query->execute([$comments->comment_content , $comments->comment_id]);
        break;

    case 'POST' :
        $comments = json_decode(file_get_contents('php://input'));

        print_r($comments);
        
        $sql = "INSERT INTO comments (user_id , post_id , comment_content)
                VALUES (  ? , ? , ? )" ;
        $query = $con->prepare($sql);
        if($query->execute([$comments->user_id , $comments->post_id , $comments->comment_content])){
            echo 'add success';
        };
        break;

    case 'DELETE' :
        $sql = "DELETE FROM comments WHERE comment_id = ?" ;
        $path = explode('/' , $_SERVER['REQUEST_URI']);
        print_r($path);
        if(isset($path[5]) && is_numeric($path[5])){
            $query = $con->prepare($sql);
            $query->execute([$path[5]]);
        }
        break;
    }