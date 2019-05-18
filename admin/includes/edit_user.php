<?php 

if(isset($_GET['edit_user'])){
    $the_user_id = $_GET['edit_user'];
    


    $query = "SELECT * FROM users WHERE user_id = $the_user_id ";
    $select_user_by_id= mysqli_query($connection, $query);

      while($row = mysqli_fetch_assoc($select_user_by_id)){                          
     $user_firstname =$row['user_firstname'];                            
     $user_lastname =$row['user_lastname'];                            
    
     $username =$row['username'];                           
     $user_email =$row['user_email'];                           
     $user_password =$row['user_password']; 
      }

    //UPDATE USERS
    if(isset($_POST['edit_user'])){
     $user_firstname =$_POST['user_firstname'];                            
     $user_lastname =$_POST['user_lastname'];                            
    
     $username =$_POST['username'];                           
     $user_email =$_POST['user_email'];                           
     $user_password =$_POST['user_password'];   
   
                //ENCRYPTING THE PASSWOD
    
            if(!empty($user_password)){
            $query_password = "SELECT user_password FROM users WHERE user_id = $the_user_id";
            $get_user_query = mysqli_query($connection, $query_password);
            $row = mysqli_fetch_assoc($get_user_query);

            $db_user_password = $row['user_password'];
             if($db_user_password != $user_password){
                $hashed_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 12));
             }
         $query = "UPDATE users SET ";
         $query .= "user_firstname = '{$user_firstname}', ";
         $query .= "user_lastname = '{$user_lastname}', ";
         $query .= "username = '{$username}', ";
         $query .= "user_email = '{$user_email}', ";
         $query .= "user_password = '{$hashed_password}' ";
         $query .= "WHERE user_id = '{$the_user_id}' ";
            
         $update_user = mysqli_query($connection, $query);
            
         confirm($update_user);

         echo "User Updated" . "<a href='users.php'>View Users </a>";
        }
    }
}else{
    header("Location: index.php");
}
?>
   
   <form action="" method="post" enctype="multipart/form-data">
    
    <div class="form-group">
        <label for="title">First name</label>
        <input value="<?php echo $user_firstname; ?>" type="text" class="form-control" name="user_firstname">
    </div>
    
    <div class="form-group">
        <label for="post_status">Last Name</label>
        <input value="<?php echo $user_lastname; ?>"  type="text" class="form-control" name="user_lastname">
    </div>
    
<!--
    <div class="form-group">
        <label for="post_image">Image</label>
        <input type="file"  name="image" id="image">
    </div>
-->
    
    <div class="form-group">
        <label for="post_tags">Username</label>
        <input value="<?php echo $username; ?>"  type="text" class="form-control" name="username">
    </div>
    
    <div class="form-group">
        <label for="post_content">Email</label>
         <input value="<?php echo $user_email; ?>"  type="email" class="form-control" name="user_email">
    </div> 
    <div class="form-group">
        <label for="post_content">Password</label>
         <input value="<?php echo $user_password; ?>" autocomplete="off" type="password" class="form-control" name="user_password">
    </div>
    
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="edit_user" value="Update User">
    </div>
    
    
</form>