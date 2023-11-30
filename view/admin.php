<!-- view/home.php -->
<?php
if(session_status() == PHP_SESSION_NONE) {
    session_start();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/mobile.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>
    <style>
        .content{
            margin: 10px 10px 0 10px;
        }
        .nav{
            justify-content: flex-end;
        }
    </style>
    <?php include './includes/header.php' ?>
    <main>
        <div class="container">
            <div class="content" id="content">
                <div class="table-content">
                    <?php  include $content;?>
                </div>
            </div>
        </div>

    </main>
    <?php if ($role != 'guest'):?>
        <?php include './includes/modal_profile.php'?>
    <?php endif?>
    <?php if ($role == 'admin' || $role == 'superadmin'):?>
        <?php include './includes/modal_roles.php'?>
    <?php endif?>

    <script src="../js/app.js"></script>
    <script src="../js/modal_profile.js"></script>
    <script src="../js/all_movies.js"></script>
    <script src="../js/admin.js"></script>
    
</body>
</html>
