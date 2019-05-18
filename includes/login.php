<?php session_start(); ?>
      <?php include "db.php"; ?> 
     <?php  include "../../cms/admin/functions.php"; ?>
    
<?php 
   if(isset($_POST['login'])){
    echo "HEREE";
      login_user($_POST['username'], $_POST['password']);
    }
    
?>