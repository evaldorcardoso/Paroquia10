<?php
$valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp'); // valid extensions
$path = '../uploads/'; // upload directory

if($_FILES['imagec'])
{
    $img = $_FILES['imagec']['name'];
    $tmp = $_FILES['imagec']['tmp_name'];

    // get uploaded file's extension
    $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

    // can upload same image using rand function
    //$final_image = rand(1000,1000000).$img;
    $final_image=rand(1000,1000000).$_GET['tipo'].'_'.$_GET['id'].'.'.$ext;

    // check's valid format
    if(in_array($ext, $valid_extensions)) 
    { 
        $path = $path.strtolower($final_image); 

        if(move_uploaded_file($tmp,$path)) 
        {            
            echo 'OK_'.$final_image;
            /*foreach($_POST as $key => $value) {
                echo "POST parameter '$key' has '$value'";
              }*/
        }
    }
} 
else 
{
    echo 'invalid';
}
?>