<?php 

    include "includes/header.php";
    include "includes/navigation.php";
    include "includes/db.php";
?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">
                
                
                <?php 
                
                $query = "SELECT * FROM posts";
                $select_all_posts_query = mysqli_query($connection, $query);
                $count_posts = 0;
                while($row = mysqli_fetch_assoc($select_all_posts_query)){
                    $post_id =$row['post_id'];
                    $post_title =$row['post_title'];
                    $post_author =$row['post_author'];
                    $post_date =$row['post_date'];
                    $post_image =$row['post_image'];
                    $post_content =substr($row['post_content'],0,100);
                    $post_status =$row['post_status'];
                    
                    if($post_status === 'published'){
                        $count_posts++;
                 ?>
              
                <h1 class="page-header">
                    Page Heading
                    <small>Secondary Text</small>
                </h1>

                <!-- First Blog Post -->
                <h2>
                    <a href="post.php?p_id=<?php echo $post_id?>"><?php echo $post_title ?></a>
                </h2>
                <p class="lead">
                    by <a href="author_post.php?author=<?php echo $post_author ?>&p_id=<?php echo $post_id?>"><?php echo $post_author ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
                <hr>
                <a href="post.php?p_id=<?php echo $post_id?>">
                <img class="img-responsive" src="images/<?php echo $post_image ?>" alt="">
                </a>
                <hr>
                <p><?php echo $post_content ?></p>
                <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                <hr>
                
                <?php
                     } 
                }
                if($count_posts === 0){
                    echo "<h1>No post SOrry</h1>";
                }
                ?>
            </div>
<?php 

    include "includes/sidebar.php";

?>
           

        </div>
        <!-- /.row -->

        <hr>


<?php 

    include "includes/footer.php";

?>
