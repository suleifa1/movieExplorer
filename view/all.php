<?php foreach ($movies as $movie): ?>
    <div class="movie">
        <div class="movie-image">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
        </div>
        <hr class="solid">
        <div class="movie-details">
            <h2><?= htmlspecialchars($movie['title']) ?></h2>
            <p class="rating">Rating: <?= htmlspecialchars($movie['rating']) ?> / 10</p>
            <p class="genres">Genres: <?= htmlspecialchars(implode(', ', $movie['genres'])) ?></p>
            <hr class="solid">
            <div class="description-container">
                <p class="description"><?= htmlspecialchars($movie['description']) ?></p>
            </div>
            <hr class="solid">
            <a id="details-button" data-value="movie/<?= htmlspecialchars($movie['id']) ?>" class="details-button">Details</a>
        </div>
    </div>
<?php endforeach; ?>