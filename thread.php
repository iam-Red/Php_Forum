<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iForum </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php include 'partials/_header.php'; ?>
    <?php
    $id=$_GET['threadid'];
    $sql= "SELECT * FROM `threads` WHERE thread_id=$id";
    $result=mysqli_query($conn,$sql);
    while($row = mysqli_fetch_assoc($result)){
        $title=$row['thread_title'];
        $desc=$row['thread_desc'];
        $thread_user_id=$row['thread_user_id'];
        $sql2="SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
        $result2=mysqli_query($conn,$sql2);
        $row2=mysqli_fetch_assoc($result2);
        $posted_by=$row2['user_email'];

    }
    ?>
    <?php
    $showAlert=false;
    $method=$_SERVER['REQUEST_METHOD'];
     if($method == 'POST'){
        $comment=$_POST['comment'];
        $comment=str_replace("<","&lt;",$comment);
        $comment=str_replace(">","&gt;",$comment);

        $sno=$_POST['sno'];
        $sql="INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by` , `comment_time`) VALUES ('$comment', '$id', '$sno', current_timestamp())";
        $result=mysqli_query($conn,$sql);
        $showAlert=true;
        if($showAlert){
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> Your comment has been added!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
     }

    ?>

    <div class="container">
        <div class="p-5 mb-4 bg-body-tertiary rounded-3">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold"><?php echo $title; ?></h1>
                <p class="col-md-8 fs-4"><?php echo $desc; ?></p>
                <hr class="my-4">
                <p>This is a peer forum for sharing knowledge with each other.</p>
                <p>Posted by: <em><?php echo $posted_by; ?></em></p>
            </div>
        </div>
    </div>

    <?php
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){

                echo '
                <div class="container">
        <h1 class="py-2">Post a Comment</h1>
        <form action="'. $_SERVER["REQUEST_URI"] .'" method="post">
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Type your comment</label>
                <textarea name="comment" id="comment" rows="3" class="form-control"></textarea>
                <input type="hidden" name="sno" value="'.$_SESSION["sno"].'">
            </div>

            <button type="submit" class="btn btn-success">Post Comment</button>
        </form>
    </div>';
    }else{

    echo '
    <div class="container">
        <h1 class="py-2">Post a Comment</h1>
        <p class="lead">You are not logged in. Please login to be able to post Comment.</p>
    </div>';

    }
    ?>

    <div class="container">
        <h1 class="py-2">Discussions</h1>
        <?php
    $id=$_GET['threadid'];
    $sql= "SELECT * FROM `comments` WHERE thread_id=$id";
    $result=mysqli_query($conn,$sql);
    $noresult=true;
    while($row = mysqli_fetch_assoc($result)){
        $noresult=false;
        $id=$row['comment_id'];
        $content=$row['comment_content'];
        $comment_time=$row['comment_time'];
        $thread_user_id=$row['comment_by'];
        $sql2="SELECT user_email FROM `users` WHERE sno='$thread_user_id'";
        $result2=mysqli_query($conn,$sql2);
        $row2=mysqli_fetch_assoc($result2);

   
        echo '<div class="d-flex my-3">
            <div class="flex-shrink-0">
                <img src="img/userd.jpg" width="54px" alt="...">
            </div>
            <div class="flex-grow-1 ms-3">
                <p class="font-weight-bold my-0">'.$row2['user_email'].' at '.$comment_time.'</p>
               '.$content.'
            </div>
        </div>';

    }
    if($noresult){
        echo '<div class="container">
        <div class="p-5 mb-4 bg-body-tertiary rounded-3">
            <div class="container-fluid py-5">
                <p class="display-4">No Comments Found</p>
                <p class="lead">Be the first person to comment.</p>
            </div>
        </div>
    </div>';
    }
    ?>
    </div>


    <?php include 'partials/_footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
</body>

</html>