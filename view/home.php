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
    <title>Home Page</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/mobile.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>
    <?php include './includes/header.php' ?>
    <main>
        <div class="container">
            <?php include './includes/sidebar.php';  ?>
            <div class="content" id="content">
                <?php  include "./includes/content_buttons.php"?>

                <!-- Контент главной страницы -->
                <div id="moviesContainer" class="movies_container">
                    <?php  include $content;?>
                </div>
            </div>
        </div>

    </main>
    <?php if ($role != 'guest'):?>
        <?php include './includes/modal_profile.php'?>
        <script src="../js/modal_profile.js"></script>
    <?php endif?>

    <?php include './includes/footer.php' ?>
    <script src="../js/app.js"></script>
    <script src="../js/all_movies.js"></script>
    <script src="http://localhost:3000/socket.io/socket.io.js"></script>
    <?php  if ($role == 'moderator'): ?>
        <script src="../js/mod-socket.js"></script>
        <script src="../js/modal_edit.js"></script>
        
    <?php endif; ?>
</body>
</html>
