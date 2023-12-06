
<form method="post" class="add-movie-form" id="add-movie-form">
    <div class="form-row">
        <label for="title">Movie Name:</label>
        <input type="text" name="title" required>
    </div>
    <div class="form-row">
        <label for="description">Description:</label>
        <textarea name="description" rows="4" required></textarea>
    </div>
    <div class="form-row">
        <label for="rating">Rating:</label>
        <input type="number" name="rating" step="0.1" min="0" max="10" required>
    </div>
    <div class="form-row">
        <label for="poster">Poster (URL):</label>
        <input type="text" name="poster" required placeholder="Use an Imgur link">
    </div>
    <div class="form-row">
        <label>Genres:</label>
        <div class="genre-list">
        <?php // Получение списка жанров
                foreach ($genres as $genre) {
                    // Вывод чекбокса для каждого жанра в отдельном блоке div
                    echo '<div class="genre-item">';
                    echo '<input type="checkbox" id="genre' . htmlspecialchars($genre['id']) . '" name="genres" value="' . htmlspecialchars($genre['id']) . '" class="hidden-checkbox">';
                    echo '<label for="genre' . htmlspecialchars($genre['id']) . '"><span class="custom-checkbox"></span>' . htmlspecialchars($genre['name']) . '</label>';
                    echo '</div>';
                }
        ?>
        </div>
        </div>


    <div class="form-row">
        <input type="submit" name="submit" value="Add Movie" id="addMovie">
    </div>
    <link rel="stylesheet" href="../css/add.css">
    <style>
        @media (max-width: 850px){
            .movies_container{
            display: flex;
            }
            .content-buttons{
                display: none;
            }

        }
        @media (min-width: 851px){
            .movies_container{
            display: flex;
            }
            .filtering{
                display: none;
            }
        }

    </style>


</form>



