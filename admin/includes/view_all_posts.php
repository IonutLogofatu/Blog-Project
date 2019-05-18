
<?php
    include("delete_modal.php");
    if(isset($_POST['checkBoxArray'])){
        
        foreach($_POST['checkBoxArray'] as $post_id){
           $bulk_option = $_POST['bulk_options'];
            switch($bulk_option){
                 case 'published':
                    $query = "UPDATE posts SET post_status = '{$bulk_option}' WHERE post_id= $post_id";
                    $update_to_published = mysqli_query($connection, $query);
                    break;
                 case 'draft':
                    $query = "UPDATE posts SET post_status = '{$bulk_option}' WHERE post_id= $post_id";
                    $update_to_draft = mysqli_query($connection, $query);
                    break;
                    
                 case 'delete':
                   $query = "DELETE FROM posts WHERE post_id = {$post_id}";
                   $delete_query = mysqli_query($connection, $query);
                   if($delete_query){
                        header('Location: posts.php');
                     }
                    break;
                case  'clone':
                    $query = "SELECT * FROM posts WHERE post_id = '{$post_id}' ";
                    $select_post_query = mysqli_query($connection, $query);
                    
                    while($row = mysqli_fetch_array($select_post_query)){
                        $post_author =$row['post_user'];                            
                        $post_title =$row['post_title'];                            
                        $post_category_id =$row['post_category_id'];                    
                        $post_status =$row['post_status'];                            
                        $post_image =$row['post_image'];                            
                        $post_content =$row['post_content'];                                                        
                        $post_date =$row['post_date'];  
                        $post_tags = $row['post_tags'];
                    }
                    
                    $query = "INSERT INTO posts(post_category_id, post_title, post_user, post_status, post_image, post_content, post_tags, post_date)";
                    
                    $query .= "VALUES({$post_category_id}, '{$post_title}', '{$post_author}', '{$post_status}', '{$post_image}', '{$post_content}', '{$post_tags}', now())";
                    
                    $copy_query = mysqli_query($connection, $query);
                    if(!$copy_query){
                        die("QUERY FAILED" . mysqli_error($connection));
                    }
                    break;
                case 'reset':
                    $view_query = "UPDATE posts SET post_view_count = 0 WHERE post_id = $post_id";
                    $send_query = mysqli_query($connection, $view_query);

                    break;
            }
        }
        
    }

?>

                    
                    
                     <form action="" method='post'>   

                           <table class="table table-bordered table-hover">
                           <div id="bulkOptionContainer" class="col-xs-4">
                               <select class="form-control" name="bulk_options" id="">
                                   <option value="">Select Options</option>
                                   <option value="published">Publish</option>
                                   <option value="draft">Draft</option>
                                   <option value="delete">Delete</option>
                                   <option value="clone">Clone</option>
                                   <option value="reset">Reset Views</option>
                               </select>
                           </div>
                           <div class="col-xs-4">
                               <input type="submit" name="submit" class="btn btn-success" value="Apply">
                               <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
                           </div>
                            <thead>
                                <tr>
                                    <th><input id="selectAllBoxes"  type="checkbox"></th>
                                    <th>Id</th>
                                    <th>Users</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Image</th>
                                    <th>Tags</th>
                                    <th>Comments</th>
                                    <th>Date</th>
                                    <th>View Post</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th>Viewes</th>
                                    <th>Reset Viewes</th>
                                </tr>
                            </thead>
                            <tbody>
                           
                               <?php 
                                
                                   
                                        $query = "SELECT posts.post_id, posts.post_user, posts.post_author, posts.post_title, posts.post_category_id, posts.post_status, ";
                                        $query .= " posts.post_image, posts.post_content, posts.post_comment_count, posts.post_date, posts.post_tags, posts.post_view_count, categories.cat_id, categories.cat_title ";
                                        $query .= "FROM posts ";
                                        $query .= "LEFT JOIN categories ON posts.post_category_id = categories.cat_id";
                                        $select_posts= mysqli_query($connection, $query);

                                        while($row = mysqli_fetch_assoc($select_posts)){
                                        $post_id =$row['post_id'];                           
                                        $post_user =$row['post_user'];   
                                        $post_author = $row['post_author'];                         
                                        $post_title =$row['post_title'];                            
                                        $post_category_id =$row['post_category_id'];                            
                                        $post_status =$row['post_status'];                            
                                        $post_image =$row['post_image'];                            
                                        $post_content =$row['post_content'];                            
                                        $post_comment_count =$row['post_comment_count'];                            
                                        $post_date =$row['post_date'];  
                                        $post_tags = $row['post_tags'];
                                        $post_view_count = $row['post_view_count'];
                                        $cat_title = $row['cat_title'];
                                        $cat_id = $row['cat_id'];
                                        
                                            echo "<tr>";
                                                echo "<td><input class='checkBoxes'  type='checkbox' name='checkBoxArray[]' value='$post_id'></td>";
                                                echo "<td>$post_id</td>";

                                                if(!empty($post_author)){
                                                    echo "<td>$post_author</td>";
                                                }else if(!empty($post_user)) {
                                                    echo "<td>$post_user</td>";
                                                }
                                                echo "<td>$post_title</td>";
                                                echo "<td>{$cat_title}</td?>";
                                            
                                                echo "<td>$post_status</td>";
                                                echo "<td><img width=100px src='../images/$post_image' alt='image'></td>";
                                                echo "<td>$post_tags</td>";

                                                $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
                                                $send_comment_query = mysqli_query($connection, $query);

                                                $row = mysqli_fetch_array($send_comment_query);
                                                $comment_id = $row['comment_id']; 
                                                $count_comments = mysqli_num_rows($send_comment_query);
                                                echo "<td class='text-center'><a href='post_comments.php?id=$post_id'>$count_comments</a></td>";


                                                echo "<td>$post_date</td>";
                                                echo "<td><a href='../post.php?p_id={$post_id}'>View Post</a></td>";
                                                echo "<td><a href='posts.php?source=edit_post&p_id={$post_id}'>EDIT</a></td>";

                                                ?>

                                                <form method="POST">
                                                    <input type="hidden" name="post_id" value="<?php echo $post_id ?>">
                                                    <td><input class="btn btn-danger" type="submit" name="remove" value="Delete"></td>
                                                </form>

                                                <?php

                                                // echo "<td><a href='javascript:void(0)' rel='$post_id' class='delete_link'>Delete</a></td>";


                                                echo "<td><p class='text-center'>$post_view_count</p></td>";
                                                echo "<td><a href='posts.php?reset={$post_id}'>Reset Views</a></td>";
                                            echo "</tr>";
                                            
                                        }
                                ?>
                                
                                <?php
                                
                                if(isset($_POST['remove'])){
                                    $the_post_id = $_POST['post_id'];
                                    $query = "DELETE FROM posts WHERE post_id = {$the_post_id}";
                                    $delete_query = mysqli_query($connection, $query);
                                    if($delete_query){
                                        header('Location: posts.php');
                                    }
                                }
                                
                                 if(isset($_GET['reset'])){
                                    $the_post_id = $_GET['reset'];
                                    $view_query = "UPDATE posts SET post_view_count = 0 WHERE post_id = $the_post_id ";
                                    $send_query = mysqli_query($connection, $view_query);
                                }
                                ?>
                                <script type="text/javascript">
                                    $(document).ready(function(){
                                        $(".delete_link").on('click',function(){
                                            var id = $(this).attr("rel");
                                            var delete_url = "posts.php?remove=" + id + " ";

                                            $(".modal_delete_link").attr("href", delete_url);
                                            
                                            $("#myModal").modal('show');

                                        });
                                    });
                                </script>
                                
                        </tbody>
                        </table>
                        </form>   