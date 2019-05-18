 <?php 

function confirm($result){
    global $connection;
     if(!$result ){
        die("Query failed" . mysqli_error($connection));
    }
}


 function insert_categories(){
      global $connection;
      if(isset($_POST['submit'])){
         $cat_title = $_POST['cat_title'];
        if($cat_title == "" || empty($cat_title)){
            echo "This field should not be empty";
        }else{
            $query = "INSERT INTO categories(cat_title) ";
            $query .= "VALUE('{$cat_title}')";
                                     
            $create_category_query = mysqli_query($connection, $query);
            if(!$create_category_query){
                                         
              die("QUERY FAILED" . mysqli_error($connection));
                                         
            }
        }
    }
 }

function delete_categories(){
    global $connection;
    if(isset($_GET['delete'])){
              $the_cat_id = $_GET['delete'];
              $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id}";
              $delete_query = mysqli_query($connection, $query);
              if($delete_query){
                header('Location: categories.php' ); 
              }else{
                   die("This operation can't be done");                                      
            }
        }
}

function show_categories(){
    global $connection;
    $query = "SELECT * FROM categories ";
    $select_categories = mysqli_query($connection, $query);

    while($row = mysqli_fetch_assoc($select_categories)){
    $cat_id =$row['cat_id'];
    $cat_title =$row['cat_title'];
    echo "<tr>";
    echo "<td>{$cat_id}</td>";
    echo "<td>{$cat_title}</td>";
    echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
    echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
    echo "<tr>";

    }
}

function user_online(){
  if(isset($_GET['onlineusers'])){
    global $connection;
    if(!$connection){
      session_start();
      include ("../includes/db.php");
      $session = session_id();
      $time_session = time();
      $time_out_in_seconds = 5;
      $time_out = $time_session - $time_out_in_seconds;

      $query = "SELECT * FROM user_online WHERE session = '$session'";
      $send_query = mysqli_query($connection, $query);
      $count = mysqli_num_rows($send_query);
      if($count == NULL){
        $query_second = "INSERT INTO user_online(session, time_session) ";
        $query_second .= "VALUES('{$session}', '{$time_session}')";
                                       
              $create_row_query = mysqli_query($connection, $query_second);
              if(!$create_row_query){
                                           
                die("QUERY FAILED" . mysqli_error($connection));
                                           
              }
      }else{
         $thi = mysqli_query($connection, "UPDATE user_online SET time_session = '{$time_session}' WHERE session = '{$session}'");
         if(!$thi){
          echo "QUERY FAILED" . mysqli_error($connection);
         }
      }

       $users_online_query = mysqli_query($connection, "SELECT * FROM user_online WHERE time_session >'$time_out'");
       echo $count_user = mysqli_num_rows($users_online_query); 
    }
    
  }
}
user_online();

function recordCount($table){
  global $connection;
   $query = "SELECT * FROM " . $table;
   $select_all_post = mysqli_query($connection, $query);
   $result = mysqli_num_rows($select_all_post);

   confirm($result);

   return $result;
}

function countThings($table, $where, $egal){
  global $connection;
  $query = "SELECT * FROM $table WHERE $where = '$egal'";
  $select_all_rows = mysqli_query($connection, $query);
  $post_count = mysqli_num_rows($select_all_rows);
  confirm($select_all_rows);

   return $post_count;
}

function is_admin($username){
  global $connection; 
  $query = "SELECT user_role FROM users WHERE username = '$username'";
  $result = mysqli_query($connection, $query);

  confirm($result);

  $row = mysqli_fetch_array($result);

  if($row['user_role'] == 'admin'){
    return true;
  }else{
    return false;
  }
}

function username_exists($username){
  global $connection; 
  $query = "SELECT username FROM users WHERE username = '$username'";
  $result = mysqli_query($connection, $query);

  if(mysqli_num_rows($result) > 0){
    return true;
  }else{
    return false;
  }
}

function email_exists($email){
  global $connection; 
  $query = "SELECT user_email FROM users WHERE user_email = '$email'";
  $result = mysqli_query($connection, $query);

  if(mysqli_num_rows($result) > 0){
    return true;
  }else{
    return false;
  }
}


function register_user($username, $email, $password, $firstname, $secondname){
            global $connection;
                
                $username   = mysqli_real_escape_string($connection, $username);
                $email      = mysqli_real_escape_string($connection, $email);
                $password   = mysqli_real_escape_string($connection, $password);
                $firstname  = mysqli_real_escape_string($connection, $firstname);
                $secondname = mysqli_real_escape_string($connection, $secondname);

                $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 12));

                $query = "INSERT INTO users (username, user_email, user_password, user_role, user_firstname, user_lastname)";
                $query .= "VALUES('{$username}', '{$email}', '{$password}', 'subscriber', '{$firstname}', '{$secondname}')";
                $register_user_query = mysqli_query($connection, $query);

                confirm($register_user_query);
                login_user($username, $password); 
}

function login_user($username, $password){
    global $connection;
      echo $username . "<br>";
      echo $password . "<br>";

      $username = trim($username);
      $password = trim($password);
         
         
      $username = mysqli_real_escape_string($connection, $username);
      $password = mysqli_real_escape_string($connection, $password);
         
         $query = "SELECT * FROM users WHERE username = '{$username}'";
         $select_user_query = mysqli_query($connection, $query);
         if(!$select_user_query){
             die ("Query failed " . mysqli_error($connection));
         }

      while($row = mysqli_fetch_array($select_user_query)){
          $db_user_id = $row['user_id'];
          $db_user_firstname= $row['user_firstname'];
          $db_user_lastname = $row['user_lastname'];
          $db_user_role = $row['user_role'];
          $db_username = $row['username'];
          $db_user_password = $row['user_password'];
      }

      if(password_verify($password, $db_user_password)){
          $_SESSION['username'] = $db_username;
          $_SESSION['firstname'] = $db_user_firstname;
          $_SESSION['lastname'] = $db_user_lastname;
          $_SESSION['user_role'] = $db_user_role;
          header("Location: /cms/admin");
        }else{
        header("Location: /cms/index.php");
    }
}
?>