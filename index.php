<?php

// Coded by Bond on a cold December night in 2018
// Project: Koto
// Description: Simple API for photo sharing

// Connect to db, load custom functions, load configuration settings
require_once("connect.php");
require_once("functions.php");
require_once("config.php");

// API _GET calls
if ($_SERVER['REQUEST_METHOD'] == "GET") {

        // custom GET call DEBUG FOR TESTING ONLY        
        if ($_GET['url'] == "testing") {
 
        } 

        // force correct POST method for common API calls
        else if ($_GET['url'] == "photo") {
            echo '{ "Message": "Incorrect Method" }';
            exit();
        }
        else if ($_GET['url'] == "upload") {
            echo '{ "Message": "Incorrect Method" }';
            exit();
        }


// API _POST calls
} else if ($_SERVER['REQUEST_METHOD'] == "POST") {

        // begin /rest-api/photo (url = photo)
        if ($_GET['url'] == "photo") {
        
            // set token
            $token = $_POST[token];
            
            // set title
            $title = "";
            if($_POST[title]) {
                $title = $_POST[title];
            }

            // set caption
            $caption = "";
            if($_POST[caption]) {
                $caption = $_POST[caption];
            }

            // set privacy
            $privacy = "0";
            if($_POST[privacy]) {
                $privacy = $_POST[privacy];
            }

            // allowed formats 
            $photo_name = $_FILES['photo']['name'];
            $ext = pathinfo($photo_name, PATHINFO_EXTENSION);
            $allowed = array('jpg','png','gif');

            if(!in_array($ext, $allowed)) {
                // Return error
                $arr = array('Message' => 'File format not supported');
                echo json_encode($arr);  
                exit();
            }

                // Confirm sha1 token and only proceed for valid tokens
                if ($db->query("SELECT token FROM login_tokens WHERE token=:token", array(':token'=>sha1($token)))) {

                    // UPLOAD PHOTO
                    $file_tmp = $_FILES['photo']['tmp_name'];
                    
                    // if photo_id set, UPDATE 
                    if($_POST[photo_id])
                    {
                        $photo_id = $_POST[photo_id];
                        $action = "update";
                    }
                    else
                    {
                        // if photo_id is NOT set, INSERT 
                        // generate uuid for new photo 
                        $photo_id = gen_uuid(); 
                        $action = "insert";

                    }

                    // if photo is included in POST, upload the photo (force to png)
                    if($_FILES['photo']['name'] != '')
                    {
                            // photos are uploaded to the /media/ folder by default
                            if(!@copy($file_tmp,'../media/' . $photo_id . '.png'))
                            {
                                // display errors if there is a problem
                                $errors= error_get_last();
                                echo "Upload error: ".$errors['type'];
                                echo "<br />\n".$errors['message'];
                            } 

                    }

                    // get user id for insert/update
                    $user_id = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];

                    // INSERT NEW photo into db
                    if($action == "insert")
                    {

                    $db->query('INSERT INTO photos VALUES (:photo_id, :user_id, :title, :caption, :privacy, :deleted, :created_date)', array(':photo_id'=>$photo_id, ':user_id'=>$user_id, ':title'=>$title, ':caption'=>$caption, ':privacy'=>$privacy, ':deleted'=>0, ':created_date'=>$now));

                    // RETURN SUCCESS
                        $arr = array('Message' => 'Photo successfully uploaded', 'Photo ID' => $photo_id, 'Title' => $title, 'Caption' => $caption, 'Privacy' => $privacy);
                        echo json_encode($arr);  

                    }
               
                    // UPDATE existing photo
                    if($action == "update")
                    {
                       $photo_user_id = $db->query('SELECT user_id FROM photos WHERE deleted != 1 AND id=:photo_id', array(':photo_id'=>$photo_id))[0]['user_id'];

                        if($user_id == $photo_user_id)
                          {
                             $db->query('UPDATE photos SET privacy=:privacy, title=:title, caption=:caption WHERE id=:photo_id', array(':privacy'=>$privacy, ':caption'=>$caption, ':title'=>$title, ':photo_id'=>$photo_id));
                         
                          // RETURN SUCCESS
                          $arr = array('Message' => 'Photo successfully updated', 'Photo' => $photo_name, 'Photo ID' => $photo_id, 'Title' => $title, 'Caption' => $caption, 'Privacy' => $privacy);
                          echo json_encode($arr);  
                          }
                          else
                          {

                            // photo not found (perhaps deleted)
                            if($photo_user_id == '') {
                                $arr = array('Message' => 'Photo not found', 'Photo ID' => $photo_id);
                            }
                            else {
                            // user not allowed to edit this photo (current user_id and photo user_id mismatch)
                                $arr = array('Message' => 'Not authorized to update this photo', 'Photo ID' => $photo_id);
                            }
                            echo json_encode($arr);  

                          }

                    // end photo update
                    }

            // end token check    
            }
            else
            {
          
            // POST /rest-api/photo/ token not found
            $arr = array('Message' => 'Bad Token');
            echo json_encode($arr);   
            
            }
            

        // end POST /rest-api/photo, start POST /rest-api/auth + create token 
        } else if ($_GET['url'] == "auth") {
           
                $username = $_POST[username];
                $password = $_POST[password];

                if ($db->query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
                        
                    // proceed if 'password_verify' returns true (sha1 of password is stored in db)
                    if (password_verify($password, $db->query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])) {

                            // create token
                            $encrypt = True;
                            $token = bin2hex(openssl_random_pseudo_bytes(64, $encrypt));

                            // get user id and insert token
                            $user_id = $db->query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
                            $db->query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                         
                            // RETURN SUCCESS (display token)
                            $arr = array('Token' => $token);
                            echo json_encode($arr);   

                    } else {
                            // RETURN ERROR (unauthorized, wrong password)
                            $arr = array('Message' => 'Unauthorized');
                            echo json_encode($arr); 
                            http_response_code(401);
                    }
         
                } else {
                        // RETURN ERROR (unauthorized, wrong username)
                        $arr = array('Message' => 'Unauthorized');
                        echo json_encode($arr); 
                        http_response_code(401);
                }

       // end POST /rest-api/auth
       }

// end POST 
// begin /rest-api/photo method DELETE (delete photo)
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
     
        if ($_GET['url'] == "photo") {

            $token = $_GET[token];

            // verify token 
            if ($db->query("SELECT token FROM login_tokens WHERE token=:token", array(':token'=>sha1($token)))) {

            $photo_id = $_GET[photo_id];
            $token = $_GET[token];

            $user_id = $db->query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
            $photo_user_id = $db->query('SELECT user_id FROM photos WHERE deleted != 1 AND id=:photo_id', array(':photo_id'=>$photo_id))[0]['user_id'];

                // only proceed if this user is allowed to delete this photo
                if($user_id == $photo_user_id)
                {

                // set 'deleted' = 1 for this photo
                $db->query('UPDATE photos SET deleted=:one WHERE id=:photo_id', array(':one'=>1, ':photo_id'=>$photo_id));

                // UNLINK photo here if necessary

                // RETURN
                $arr = array('Message' => 'Photo successfully deleted');
                echo json_encode($arr);   
            

                }
                else
                {
                    // photo not found
                    if($photo_user_id == "")
                    {
                      $arr = array('Message' => 'Photo not found');
                      echo json_encode($arr);   
                      http_response_code(200);  
                      exit();
                    }
                    else
                    { 
                      // user not allowed to delete this photo
                      $arr = array('Message' => 'Unauthorized to delete this photo');
                      echo json_encode($arr);   
                      http_response_code(401);  
                      exit();
                    } 

                     
                }


        // close token verify if
        }
        else
        {
            echo '{ "Message": "Unauthorized" }';
            http_response_code(401); 
            exit();
        }

        // close URL DELETE photo
        }

    // begin /rest-api/auth/ method DELETE (logout)
    if ($_GET['url'] == "auth")
    {
        // confirm and delete token
        if (isset($_GET['token'])) {
                   
            if ($db->query("SELECT token FROM login_tokens WHERE token=:token", array(':token'=>sha1($_GET['token'])))) {
                         
            // DELETE token
            $db->query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_GET['token'])));
      
            // RETURN success (delete token successful)
            $arr = array('Message' => 'Token has been deleted');
            echo json_encode($arr); 
            http_response_code(200);
            } else {
                    // RETURN error (invalid token)
                    $arr = array('Message' => 'Invalid token');
                    echo json_encode($arr); 
                    http_response_code(400);
            }

        } else {
            // missing token
            echo '{ "Error": "Malformed request" }';
            http_response_code(400);
        }

    // end /rest-api/auth/ method DELETE (logout)
    }

// end method DELETE
} else {
        // RETURN method not allowed for all unused methods
        $arr = array('Message' => 'Method not allowed');
        echo json_encode($arr); 
        http_response_code(405);
}
?>
