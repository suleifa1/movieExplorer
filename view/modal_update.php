


<div id="editMovieModal" class="modal" style="display: none;">
  <!-- Содержимое модального окна -->
  <div class="modal-content">
    <!-- Кнопка закрыть (x) -->
    <span class="close">&times;</span>
    <form method="post" class="add-movie-form" id="update-movie-form">
    <div class="form-row">
        <label for="title">Movie Name:</label>
        <input type="text" value="<?= htmlspecialchars($movie["title"]) ?>" name="title" required>
    </div>
    <div class="form-row">
        <label for="description">Description:</label>
        <textarea name="description" rows="4" required><?= htmlspecialchars($movie["description"]) ?></textarea>
    </div>
    <div class="form-row">
        <label for="rating">Rating:</label>
        <input type="number" name="rating" step="0.1" min="0" max="10" value="<?= htmlspecialchars($movie["rating"])?>" required>
    </div>
    <div class="form-row">
        <label for="poster">Poster (URL):</label>
        <input type="text" name="poster" value="<?= htmlspecialchars($movie["poster"]) ?>" placeholder="Use an Imgur link">
    </div>
    <div class="form-row">
        <label>Genres:</label>
        <div class="genre-list">
        <?php // Получение списка жанров
            foreach ($genres as $genre) {
              // Проверяем, находится ли текущий жанр в массиве жанров фильма
              $isChecked = in_array($genre['name'], $movie["genres"]) ? 'checked' : '';

              echo '<div class="genre-item">';
              echo '<input type="checkbox" id="genre' . htmlspecialchars($genre['id']) . '" name="genres" value="' . htmlspecialchars($genre['id']) . '" class="hidden-checkbox" ' . $isChecked . '>';
              echo '<label for="genre' . htmlspecialchars($genre['id']) . '"><span class="custom-checkbox"></span>' . htmlspecialchars($genre['name']) . '</label>';
              echo '</div>';
            }
        ?>
        </div>
        </div>


    <div class="form-row">
        <input type="submit" name="submit" value="Update" id="updateMovie">
        <button type="button" id="deleteMovie">Delete</button>
    </div>

    <link rel="stylesheet" href="../css/add.css">

</form>

  </div>
</div>

<!-- Стили для модального окна -->
<style>
  .modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0,0,0);
    background-color: rgba(0,0,0,0.4);
    padding-top: 60px;
  }

  .modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
  }

  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }

  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }
</style>

