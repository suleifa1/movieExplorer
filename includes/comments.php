
<div class="comments-section">
    <h3>Комментарии:</h3>
    <?php foreach ($comments as $comment): ?>
        <div class="comment">
            <h4><?= htmlspecialchars($comment["username"]) ?></h4>
            <p><?= htmlspecialchars($comment["text"]) ?></p>
            <?php if($userRole == "moderator"): ?>
                <button data-comment-id="<?= $comment["id"] ?>">Удалить комментарий</button>
            <?php elseif($userRole == "user"): ?>
                <button data-comment-id="<?= $comment["id"] ?>">Пожаловаться</button>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
