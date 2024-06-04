<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('../includes/connect.php');

$user_name = $_SESSION['username'];

if (!isset($user_name)) {
    echo "<script>window.open('user_login.php','_self')</script>";
}

if (isset($_GET['edit_account'])) {
    $user_session_name = $_SESSION['username'];
    $select_query = "Select * from user_table where username='$user_session_name'";
    $result_query = mysqli_query($con, $select_query);
    $row_fetch = mysqli_fetch_assoc($result_query);
    $user_id = $row_fetch['user_id'];
    $username = $row_fetch['username'];
    $user_email = $row_fetch['user_email'];
    $user_address = $row_fetch['user_address'];
    $user_mobile = $row_fetch['user_mobile'];
}
// Initialize error messages
$nameErr = $emailErr = $mobileErr = "";
$update_success = false;

if (isset($_POST['user_update'])) {
    $update_id = $user_id;
    $username = $_POST['user_username'];
    $user_email = $_POST['user_email'];
    $user_address = $_POST['user_address'];
    $user_mobile = $_POST['user_mobile'];
    $user_image = $_FILES['user_image']['name'];
    $user_image_tmp = $_FILES['user_image']['tmp_name'];
    // move_uploaded_file($user_image_tmp, "user_images/$user_image");

     // Move uploaded image
     if ($user_image) {
        move_uploaded_file($user_image_tmp, "user_images/$user_image");
    } else {
        $user_image = $row_fetch['user_image'];
    }

     // Validate username
    if (!preg_match("/^[a-zA-Z-' ]*$/", $username)) {
       
            $nameErr = "Invalid last name. Only letters, spaces, and hyphens are allowed.";        
    }
    // Validate email
    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format.";
    }
    // Validate mobile number
    if (!preg_match("/^[0-9]{10}$/", $user_mobile)) {
        $mobileErr = "Invalid mobile number. Must be 10 digits.";
    }

    //update query
    if (empty($nameErr) && empty($emailErr) && empty($mobileErr)) {
    $update_data = "Update user_table set username='$username',user_email='$user_email',
        user_image='$user_image',user_address='$user_address',user_mobile='$user_mobile' where
        user_id=$update_id";

    $result_query_update = mysqli_query($con, $update_data);
    if ($result_query_update) {
        echo "<script>alert('User data updated successfully')</script>";
        $update_success = true;
        // echo "<script>window.open('logout.php','_self')</script>";
    }
}
}
if ($update_success) {
    echo "<script>window.open('logout.php','_self')</script>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    
</head>

<body>
    <h3 class="m-4">EDIT <span class="px-4">ACCOUNT</span></h3>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']). '?edit_account=' . $user_id;; ?>" method="post" enctype="multipart/form-data">
        <div class="form-outline mb-4">
            <input type="text" class="form-control w-50 m-auto" value="<?php echo $username; ?>" name="user_username">
            <?php echo $nameErr?>
        </div>
        <div class="form-outline mb-4 d-flex w-50 m-auto">
            <input type="email" class="form-control m-auto" value="<?php echo $user_email; ?>" name="user_email">
            <?php echo $emailErr;?>
        </div>
        <div class="form-outline mb-4 d-flex w-50 m-auto">
            <input type="file" class="form-control m-auto" name="user_image">
            <img src="./user_images/<?php echo $user_image; ?>" alt="" width="100" height="100" class="edit_image">
        </div>
        <div class="form-outline mb-4">
            <input type="text" class="form-control w-50 m-auto" value="<?php echo $user_address; ?>" name="user_address">
        </div>
        <div class="form-outline mb-4">
            <input type="text" class="form-control w-50 m-auto" value="<?php echo $user_mobile; ?>" name="user_mobile">
            <?php echo $mobileErr;?>
        </div>
        <input type="submit" value="Update" class="mbtn2 mb-3" name="user_update">

    </form>

</body>

</html>