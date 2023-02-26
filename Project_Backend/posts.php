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
                INNER JOIN `posts` ON posts.user_id = users.id
                ORDER BY posts.created_at DESC" ;
        $query = $con->prepare($sql);
        $query->execute();
        $posts = $query->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($posts);
        break;


    case 'POST' :

        $text = $_POST["post"];
        $user_id = $_POST['user_id'];
        if($_FILES["file"] == null){
        $file = "";
        } else {
            $file = $_FILES["file"] ;
        }


        if($file != ""){
            $targetDir = "../src/Components/images/";
            $fileName = basename($file["name"]);
            $targetPath = $targetDir . $fileName;
        
            if (move_uploaded_file($file["tmp_name"], $targetPath)) {
            echo "File uploaded successfully";
                $sql = "INSERT INTO posts (user_id , content , post_image)
                        VALUES ( ? , ? , ? )" ;
                $query = $con->prepare($sql);
                $query->execute([$user_id , $text , $fileName ]);
                break;
            } else {
            echo "Error uploading file";
            }
        } else {
            $sql = "INSERT INTO posts (user_id , content )
                    VALUES ( ? , ? )" ;
            $query = $con->prepare($sql);
            $query->execute([$user_id , $text ]);
            break;
        }



    case 'DELETE' :
        $sql = "DELETE FROM posts WHERE post_id = ?" ;
        $path = explode('/' , $_SERVER['REQUEST_URI']);
        if(isset($path[6]) && is_numeric($path[6])){
            $query = $con->prepare($sql);
            $query->execute([$path[6]]);
        }
        break;
}