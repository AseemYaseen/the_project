<?php include 'config.php'; ?>

<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods:*");



$method = $_SERVER['REQUEST_METHOD'];


$con=crud::connect();
switch ($method) {
        case 'POST' :

        print_r($_POST);
        $text = $_POST["post_content"];
        $post_id = $_POST['post_id'];
        if($_FILES["file"] == null ){
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
                $sql = "UPDATE posts 
                        SET content = ? , post_image = ?
                        WHERE post_id = ?" ;
                $query = $con->prepare($sql);
                $query->execute([$text , $fileName , $post_id ]);
                break;
            } else {
            echo "Error uploading file";
            }
        } else {
                $sql = "UPDATE posts 
                        SET content = ?
                        WHERE post_id = ?" ;
                $query = $con->prepare($sql);
                $query->execute([$text , $post_id ]);
            break;
        }
}