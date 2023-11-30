<?php
// Controller/MovieController.php

include_once './model/MovieModel.php'; 
include_once './model/CommentModel.php'; 
include_once './model/UserModel.php'; 
include_once './model/ReportModel.php'; 
define('UPLOAD_DIR', 'upload/');

class MainController {
    private $movieModel;
    private $commentModel;
    private $userModel;
    private $reportModel;

    public function __construct() {
        $this->movieModel = new MovieModel();
        $this->commentModel = new CommentModel();
        $this->userModel = new UserModel();
        $this->reportModel = new ReportModel();
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    public function index() {
        $reports = $this->reportModel->getReports();
        $movies = $this->movieModel->getAllMovies(); 
        $content = "./view/all.php";
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        } 
        if (!isset($_SESSION['user_id'])) {
            $isLogged = false;
            $role = 'guest';
        }else{
            $userData = $this->userModel->getUserInfo(($_SESSION['user_id']));
            $isLogged = true;
            $role = ($this->userModel->determineUserRole());
            
        }
        if($role == 'superadmin' || $role == 'admin'){
            $content = "./view/admin_table.php";
            $users = $this->userModel->getAllUsersWithRoles();
            include './view/admin.php';
        }else{
            include './view/home.php'; 
        }

        
        
    }
    public function show($movieId) {
        $movie = $this->movieModel->getMovieById($movieId);
        if(!$movie){
            header('Location: /');
            return;
        }
        $comments = $this->commentModel->getCommentsForMovie($movieId);
        $reports = $this->reportModel->getReports();
        $content = "./view/show_movie.php";
        $genres = $this->movieModel->getGenres();

        
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        } 
        if (!isset($_SESSION['user_id'])) {
            $isLogged = false;
            $role = 'guest';
        }else{
            $userData = $this->userModel->getUserInfo(($_SESSION['user_id']));
            $isLogged = true;
            $role = ($this->userModel->determineUserRole());
            
        }
        if($role == 'superadmin' || $role == 'admin'){
            $content = "./view/admin_table.php";
            $users = $this->userModel->getAllUsersWithRoles();
            include './view/admin.php';
        }else{
            include './view/home.php'; 
        }
        
    }
    public function showMoviesByGenre($genre){
        $reports = $this->reportModel->getReports();
        $movies = $this->movieModel->getMoviesByGenre($genre);
        $content = "./view/all.php";
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        } 
        if (!isset($_SESSION['user_id'])) {
            $isLogged = false;
            $role = 'guest';
        }else{
            $userData = $this->userModel->getUserInfo(($_SESSION['user_id']));
            $isLogged = true;
            $role = ($this->userModel->determineUserRole());
            
        }
        if($role == 'superadmin' || $role == 'admin'){
            $content = "./view/admin_table.php";
            $users = $this->userModel->getAllUsersWithRoles();
            include './view/admin.php';
        }else{
            include './view/home.php'; 
        }

    }
    public function show_add_form(){
        if($this->userModel->determineUserRole() == 'moderator'){
            $genres = $this->movieModel->getGenres();
            $reports = $this->reportModel->getReports();
            $content = "./view/add_movie.php";
            if(session_status() == PHP_SESSION_NONE) {
                session_start();
            } 
            if (!isset($_SESSION['user_id'])) {
                $isLogged = false;
                $role = 'guest';
            }else{
                $userData = $this->userModel->getUserInfo(($_SESSION['user_id']));
                $isLogged = true;
                $role = ($this->userModel->determineUserRole());
                
            }
            if($role == 'superadmin' || $role == 'admin'){
                $content = "./view/admin_table.php";
                $users = $this->userModel->getAllUsersWithRoles();
                include './view/admin.php';
            }else{
                include './view/home.php'; 
            }
        }else{
            header('Location: /');
        }
        
    }
    public function uploadImage() {       
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        } 
        if (!isset($_SESSION['user_id'])) {
            return json_encode(['error' => 'User is not logged in.']);
        }
    
        $userId = $_SESSION['user_id'];
        $file = $_FILES['image']; // Предполагаем, что имя поля ввода файла - 'image'
        
        // Проверяем, был ли файл загружен без ошибок
        if ($file['error'] === UPLOAD_ERR_OK) {
            // Проверяем MIME-тип файла, чтобы убедиться, что это изображение
            $fileType = mime_content_type($file['tmp_name']);
            if (in_array($fileType, ['image/jpeg', 'image/png'])) {
                // Проверяем содержимое файла на наличие PHP-кода
                $content = file_get_contents($file['tmp_name']);
                if (preg_match('/<\?php/i', $content)) {
                    return json_encode(['error' => 'PHP code detected in file.']);
                }
    
                // Создаем директорию для пользователя, если она не существует
                $userDir = UPLOAD_DIR . $userId . '/';
                if (!file_exists($userDir) && !is_dir($userDir)) {
                    mkdir($userDir, 0755, true);
                }
    
                // Генерируем имя файла, соответствующее типу файла
                $fileName = 'profile.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                $filePath =  $userDir . $fileName;
                $this->userModel->updateImage($userId, '../'.$filePath);
    
                // Перемещаем файл из временной директории в конечную
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    return json_encode(['success' => 'File uploaded successfully.', 'path' => '../'.$filePath]);
                } else {
                    return json_encode(['error' => 'File upload failed.']);
                }
            } else {
                return json_encode(['error' => 'Invalid file type. Only JPEG and PNG are allowed.']);
            }
        } else {
            return json_encode(['error' => 'File upload error code: ' . $file['error']]);
        }
    }
    public function AJAXgetAllUers(){
        $roleReq = $this->userModel->determineUserRole();
        if( $roleReq == 'admin' || $roleReq == 'superadmin'){
            return json_encode($this->userModel->getAllUsersWithRoles());
        }
        else {
            return json_encode($this->AJAXmethod_error());
        }

    }
    public function AJAXcomment(){
        header('Content-Type: application/json');
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        if(!isset($input['movie_id']) || !isset($input['comment'])){
            return json_encode(['success' => false, 'error' => 'Missing parametres']);
        }


        $movieId = (int)$input['movie_id'] ?? null;
        $commentText = $input['comment'] ?? '';
        $userId = $_SESSION['user_id'] ?? null;
        if($movieId == null || $commentText == '' ||  $userId == null){
            return json_encode([
                'success' => false, 
                'error' => 'Missing fields',
                'movieId' => $movieId,
                'commentText' => $commentText,
                'userId' => $userId,
            ]);
        }

        $result = $this->commentModel->addComment($movieId, $commentText, $userId);
        if($result){
            return json_encode(['success' => true, 'reulst' => 'Commentary added']);
        }else{
            return  json_encode(['success' => false, 'error' => 'Something went wrong']);;
        }
    }
    public function AJAXadd_movie() {
        header('Content-Type: application/json');
        // Проверяем, отправлены ли данные через AJAX POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['success' => false, 'error' => 'Invalid request method.']);
        }
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        // Получаем данные из POST запроса
        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $rating = floatval($input['rating']) ?? '';
        $poster = $input['poster'] ?? '';
        $genres = $input['genres'] ?? []; // Предполагается, что это массив ID жанров

        if (empty($title) || empty($description) || $rating =='' || empty($poster) || empty($genres)) {
            
            return json_encode([
                'success' => false, 
                'error' => 'Please fill in all fields.',
                'title' => $title,
                'description' => $description,
                'rating' => $rating,
                'poster' => $poster,
                'genres' => $genres,
                'post' => $input
            ]);
        }

        $movieId = $this->movieModel->addMovie($title, $description, $rating, $poster, $genres);

        if ($movieId) {
            return json_encode(['success' => true, 'result' => 'Movie added successfully.', 'movieId' => $movieId]);
        } else {
            return json_encode(['success' => false, 'error' => 'Failed to add the movie.']);
        }
    }
    public function AJAXupdate_movie() {
        header('Content-Type: application/json');
        // Проверяем, отправлены ли данные через AJAX POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return json_encode(['success' => false, 'error' => 'Invalid request method.']);
        }
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        // Получаем данные из POST запроса
        $movieID = $input['movieId'] ?? '';
        $title = $input['title'] ?? '';
        $description = $input['description'] ?? '';
        $rating = $input['rating'] ?? '';
        $poster = $input['poster'] ?? '';
        $genres = $input['genres'] ?? []; // Предполагается, что это массив ID жанров

        if (empty($movieID) || empty($title) || empty($description) || empty($rating) || empty($poster) || empty($genres)) {
            
            return json_encode([
                'success' => false, 
                'error' => 'Please fill in all fields.',
                'movie_id' => $movieID,
                'title' => $title,
                'description' => $description,
                'rating' => $rating,
                'poster' => $poster,
                'genres' => $genres,
                'post' => $input
            ]);
        }

        $movieId = $this->movieModel->updateMovie($movieID,$title, $description, (float)$rating, $poster, $genres);

        if ($movieId) {
            return json_encode(['success' => true, 'result' => 'Movie updated successfully.', 'movieId' => $movieId]);
        } else {
            return json_encode(['success' => false, 'error' => 'Failed to update the movie.']);
        }
    }
    public function AJAXget_user(){
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        } 
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            return json_encode(['error' => 'Unauthorized']);
        }
        $userid = $_SESSION['user_id'];
        return json_encode($this->userModel->getUserInfo($userid));
    }
    public function AJAXpassword_change(){
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        } 
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            return json_encode(['error' => 'Unauthorized']);
        }
        if(!isset($input['password']) || strlen($input['password']) < 8){
            http_response_code(401);
            return json_encode(['error' => 'Provided data is incorrect']);
        }

        $userid = $_SESSION['user_id'];
        $password = $input['password'];
        $result = $this->userModel->updatePassword($userid, $password);
        if($result){
            return json_encode(['success' => true, 'result' => 'Password was successfully changed.']);
        }else{
            return json_encode(['success' => false, 'error' => 'Something went wrong. Please contact administrators.']);
        }

        
    }
    public function AJAXget_all(){
        header('Content-Type: application/json');
        return json_encode( $this->movieModel->getAllMovies());
    }
    public function AJAXget_comments($movieId){
        $role = $this->userModel->determineUserRole();
        $comments = $this->commentModel->getCommentsForMovie($movieId);
        ob_start();
        require  "./view/show_comments.php";
        $html = ob_get_clean();
        $data = [
            'content' => $html
        ];
        return json_encode($data);
    }
    public function AJAXget_movie($movieId) {
        $movie = $this->movieModel->getMovieById($movieId);
        if(!$movie){
            header('Location: /');
            return;
        }
        $reports = $this->reportModel->getReports();
        $comments = $this->commentModel->getCommentsForMovie($movieId);
        $userRole = $this->userModel->determineUserRole();
        $genres = $this->movieModel->getGenres();

        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        } 
        if (!isset($_SESSION['user_id'])) {
            $isLogged = false;
            $role = 'guest';
        }else{
            $userData = $this->userModel->getUserInfo(($_SESSION['user_id']));
            $isLogged = true;
            $role = ($this->userModel->determineUserRole());
            
        }
    
        ob_start();
        
        
        if($role == 'superadmin' || $role == 'admin'){
            $users = $this->userModel->getAllUsersWithRoles();
            require './view/admin.php';
        }else{
            require './view/show_movie.php'; 
        }
        
        $html = ob_get_clean();
        $data = [
            'content' => $html
        ];

        return json_encode($data);
    }
    public function AJAXget_add_form(){
        if($this->userModel->determineUserRole() == 'moderator'){

            $genres = $this->movieModel->getGenres();
            $reports = $this->reportModel->getReports();

            if(session_status() == PHP_SESSION_NONE) {
                session_start();
            } 
            if (!isset($_SESSION['user_id'])) {
                $isLogged = false;
                $role = 'guest';
            }else{
                $userData = $this->userModel->getUserInfo(($_SESSION['user_id']));
                $isLogged = true;
                $role = ($this->userModel->determineUserRole());
                
            }

            ob_start();

            require  "./view/add_movie.php";
            $html = ob_get_clean();
            $data = [
                'content' => $html
            ];
            return json_encode($data);

        }else{
            header('Location: /');
        }

    }
    public function AJAXreport_comment(){
        header('Content-Type: application/json');
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        if($this -> userModel ->determineUserRole() != 'user'){
            http_response_code(404);
            return json_encode(['success'=> false, 'error' => 'no permissions']);
        }
        if(!isset($input['commentId']) || !isset($input['typeId'])){
            return json_encode(['success' => false,
            'error' => 'Wrong data provided. Fill the gaps.']);
        }
        $commentId = intval($input['commentId']);
        $typeId = intval($input['typeId']);
        $result = $this->commentModel->reportComment($commentId, $typeId);
        if($result){
            return  json_encode(['success' => true, 'result' => 'Report sent']);
        }else{
            return json_encode(['success' => false,
            'error' => 'Something went wrong. Try again later or contant support center.']);
        }
    }
    public function AJAXdelete(){
        header('Content-Type: application/json');
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        $roleReq = $this->userModel->determineUserRole();
        $commentId = $input['commentId'] ?? null;
        if($commentId){
            $userid = $this->commentModel->getCommentUserId($commentId);
        }
        

        if( $roleReq == 'moderator' || $_SESSION['user_id'] == $userid){        
        }else{
            if(!isset($input['commentId'])){
                return json_encode(['success' => false,
                    'error' => 'CommentId is missing']);
            }

            if(!isset($input['type'])){
                return json_encode(['success' => false,
                'error' => 'Wrong data provided. Fill the gaps.']);
            }

            http_response_code(401);
            return json_encode(['success'=> false, 'error' => 'no permissions']);
        }


        switch ($input['type']){
            case 'commentary' :                    
                    $result = $this->commentModel->deleteComment(intval($commentId));
                    if($result){
                        return  json_encode(['success' => true, 'result' => 'Commentary deleted']);
                    }else{
                        return json_encode(['success' => false,
                        'error' => 'Something went wrong. Try again later or contant support center.']);
                    }
                break;
            case 'movie':
                if(!isset($input['movieId'])){
                    return json_encode(['success' => false,
                        'error' => 'MovieId is missing']);
                }else{
                    $movieId = intval($input['movieId']);
                    $result = $this->movieModel->deleteMovie($movieId);
                    if($result){
                        return  json_encode(['success' => true, 'result' => 'Movie deleted']);
                    }else{
                        return json_encode(['success' => false,
                        'error' => 'Something went wrong. Try again later or contant support center.']);
                    }
                }
                break;
            default:
                exit();
        }
    }
    public function AJAXupdate_role(){
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        if(!isset($input['userId']) || !isset($input['role']) ||
          strlen($input['userId']) == 0 || strlen($input['role']) == 0){
            return json_encode(['success' => false, 'error' => 'Missing fields.']);
        }
        $userId = $input['userId'];



        $previousRole = $this->userModel->getUserRole($userId);
        $newRoleId = $input['role'];
        $newRoleName = match ($newRoleId){
            '0' => 'guest',
            '1' => 'admin',
            '2' => 'moderator',
            '3' => 'user',
            '4' => 'superadmin',
            default => false
        };
        


        $roleReq = $this->userModel->determineUserRole();
        
        
       

        switch($roleReq){
            case 'superadmin':
            case 'admin':

                if(session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                if(isset($_SESSION["user_id"])) {
                    $userIdReq = $_SESSION["user_id"];
                    
                    if($userId == $userIdReq){
                        return json_encode(['success' => false, 'error' => 'You cant change yours role.']);
                    }
        
                }else{
                    return json_encode(['success' => false, 'error' => 'Critical error. Contact administration.']);
                }

                if(!$newRoleName){
                    return json_encode(['success' => false, 'error' => 'Wrong role id.']);
                }

                if( $roleReq != $previousRole && $previousRole != 'superadmin' ){
                    if($newRoleName == $previousRole){
                        return json_encode(['success' => false, 'error' => 'Bad request.']);
                    }            
                    $result = $this->userModel->updateUserRole($userId, $newRoleId);
                    if($result){
                        echo json_encode(['success' => true, 'result' => 'Role successfully updated.']);
                    }else{
                        echo json_encode(['success' => false, 'error' => 'Something went wrong.']);
                    }
                }else{
                    echo json_encode(['success' => false, 'error' => 'You cant change another admin/superadmin']);
                }
                break;

            default:
                header('Content-Type: application/json');
                http_response_code(401);
                return json_encode(['error' => 'Unauthorized']);
        }


    }
    public function AJAXmethod_error(){
        http_response_code(405);
        $data = [
            'error' => 'This method not supported' 
        ];
        return $data;
    }
}
