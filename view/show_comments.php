<?php

 $REPORT_TYPES = [
    1 => 'spam',
    2 => 'rude',
    3 => 'other'
];
?>
<h3>Комментарии:</h3>
<div class="comments-section">
    
    <?php if (!empty($comments)): ?>
        <?php foreach ($comments as $comment): ?>


            <div class="comment" data-hidden-value='<?= htmlspecialchars($comment['user_id']) ?>'>
                <div class="flex-comcontainer">
                    <div class="grid-info">
                        <h4><?= htmlspecialchars($comment["username"]) ?></h4>
                        <h5><?= htmlspecialchars($comment["role_name"]) ?></h5>
                        <div class="image-container">
                            <img src="<?= $comment["image_path"]?>" alt="">
                        </div>
                    </div>
                    <div class="flex-comments">
                        <p><?= htmlspecialchars($comment["text"]) ?></p>
                        <div class="name-time">
                            <h5><?= htmlspecialchars($comment["timestamp"]) ?></h5>
                            <?php if (isset($_SESSION['user_id']) && ($role == "moderator" || $_SESSION['user_id'] == $comment['user_id'])): ?>
                                <button id="commentButton" class="yN_dsM" data-comment-id="<?= $comment["id"] ?>">Delete</button>
                            <?php elseif ($role == "user"): ?>
                                <button id="commentButton" class="yN_dsU" data-comment-id="<?= $comment["id"] ?>">Report</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>



        <?php endforeach; ?>
    <?php else: ?>
        <p style="font-family: 'Poppins', sans-serif;" >No comments yet.</p>
    <?php endif; ?>
    <?php if($role == 'user'):?>
        <div id="reportModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Выберите причину жалобы:</h3>
            <?php
                foreach($REPORT_TYPES as $id => $typeName) {
                    echo "<p><input type='radio' name='reportType' value='{$id}'> {$typeName}</p>";
                }
            ?>
            <button id="submitReport">Отправить</button>
        </div>
    </div>
    <?php endif;?>
    <style> 
        #submitReport {
          background-color: black;
          color:#888;
          padding: 10px 20px;
          border: none;
          border-radius: 5px;
          cursor: pointer;
          margin-top: 20px;
      }

      #submitReport:hover {
          color:whitesmoke
      }
    </style>



</div>
