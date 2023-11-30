<div class="movie-content" data-value = "<?= htmlspecialchars($movieId)?>">
    <div class="flex-container"> 
        <div class="item-image">
            <img src="<?= htmlspecialchars($movie["poster"]) ?>" alt="">
        </div>
        <div class="movie-details-container"> 
            <h1 class="title"><?= htmlspecialchars($movie["title"]) ?></h1>
            
            <div class="movie-metadata">
                <p class="movie-rating">Rating: <?= htmlspecialchars($movie["rating"]).'/10' ?></p>
                <p class="movie-genres">Genre: <?= implode(", ", $movie["genres"]) ?></p>

            </div>
            
            <div class="description-content">
                <h3>Description</h3>
                <p class="movie-description"><?= htmlspecialchars($movie["description"]) ?></p>
            </div>

            <div class="comment-form">
                <form id="addCommentForm">
                    <textarea name="comment" placeholder="Напишите ваш комментарий..."></textarea>
                    <input type="submit" value="Comment">
                    <?php if($role == 'moderator'):?>
                        <button type="button" id="editMovieModalOpenBtn">Edit Movie</button>
                    <?php endif;?>
                </form>
            </div>

            <div id="comments-container" class="comments-container">
                <?php include "./view/show_comments.php"?>
            </div>

        </div> 
    </div> 
    

    <link rel="stylesheet" href="../css/new.css" class="rel">



</div>
<?php if($role == 'moderator'):?>
    <?php include "./view/modal_update.php"?>

<?php endif;?>