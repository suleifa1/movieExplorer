<?php

$REPORT_TYPES = [
    1 => 'spam',
    2 => 'rude',
    3 => 'other'
];
?>
<header>
    <div class="nav">
        <?php if($role != 'admin' && $role != 'superadmin' ): ?>
            <h1 href="#" id="nav-toggle-sidebar" class="uil-align-justify"></h1>
        <?php endif;?> 
        <ul class="nav_list">
            <?php if($role == 'moderator'): ?>
                <?php if (empty($reports)): ?>
                    <li class="notify">
                <?php else: ?>
                    <li class="notify active">
                <?php endif?>
                    <i class="uil uil-bell"></i>
                        <div class="notifyBox">

                        <ul>                            
                            <?php if (empty($reports)): ?>
                                
                                <p class="report" style="justify-content: center">No reports available.</p>
                            <?php else: ?>


                                <?php foreach ($reports as $report): ?>
                                    <li class="report">
                                        <p>Report #<?= htmlspecialchars($report['id']) ?> : <?= $REPORT_TYPES[$report['type_id']] ?></p>
                                        <div class="report-buttons">
                                            <i data-movie-id="<?= htmlspecialchars($report['movies_id'])?>" data-comment-id="<?= htmlspecialchars($report['comments_id'])?>" class="uil uil-search"></i>
                                            <i class="uil uil-times-circle"></i>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif?>



                        </ul>
                    </div>



                </li>
                <li><a class="add-movie_a uil" data-value="add-movie"></a></li>
            <?php endif; ?>
            <?php if($isLogged): ?>
                <li><a class="profile_a uil" data-value="profile" id="openProfileBtn"></a></li>
                <li><a class="logout_a uil" ></a></li>
        
            
            <?php else: ?>
                <li><a data-value="home" aria-current="page">Home</a></li>
                <li><a href="/signin/auth">Sign in</a></li>
            <?php endif; ?>
        </ul>
    </div>
</header>
<?php if($role == 'moderator'): ?>
    <script>
        var findButtons = document.querySelectorAll('.uil-search');
        if(findButtons){
            findButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var movieId = this.getAttribute('data-movie-id');
                var commentId = this.getAttribute('data-comment-id'); 
                window.location.href = '../movie/' + movieId + '#comment=' + commentId;
            });
        });
        }
        var cancelButton = document.querySelector('.uil-times-circle'); 
    </script>
<?php endif; ?>