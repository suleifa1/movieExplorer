<?php
// index.php

require_once './model/MovieModel.php';
require_once './controller/MainController.php';
require_once './controller/AuthController.php';
require_once './controller/WebSocketController.php';



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$uri = trim($_SERVER['REQUEST_URI'], '/');
$uriSegments = explode('/', $uri);

$mainController = new MainController();
$authControlelr = new AuthController();



$isAjaxRequest = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($isAjaxRequest) {

    header('Content-Type: application/json'); 

    switch ($uriSegments[0]) {
        case 'movie':
            /*TODO: */
            if ($uriSegments[1] === 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
                // Обрабатываем AJAX POST запрос на добавление нового фильма
                echo $mainController->AJAXadd_movie();
                return;
            } elseif ($uriSegments[1] === 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
                /*TODO: */ 
               echo $mainController->AJAXupdate_movie();
                return;
            }
            echo json_encode($mainController->AJAXmethod_error());
            break;
        
        case 'get-movies':
            echo $mainController->AJAXget_all();
            break;
        
        case 'get-movie-form':
            if (isset($uriSegments[1])) {
                echo $mainController->AJAXget_movie($uriSegments[1]);            }
            break;
        case 'get-comments':
            if(isset($uriSegments[1])){
                echo $mainController->AJAXget_comments($uriSegments[1]);
            }else{
                echo json_encode($mainController->AJAXmethod_error());
            }
            break;

        case 'get-add-form':
            echo $mainController->AJAXget_add_form();
            break; 
        case'api':
            switch($uriSegments[1]){
                case 'auth':
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        echo $authControlelr->login();
                    }
                    else{
                        echo json_encode($mainController->AJAXmethod_error());
                    }
                    break;
                case 'registration':
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        echo $authControlelr->register();
                    }
                    else{
                        echo json_encode($mainController->AJAXmethod_error());
                    }
                    break;    
                case 'upload':
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        echo $mainController->uploadImage();                        
                    }
                    else                    {
                        echo json_encode($mainController->AJAXmethod_error());
                    }

                    break;
                case 'report':
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $result = $mainController->AJAXreport_comment(); 
                        if(json_decode($result, true)['success']){
                            $websocketController = new WebSocketController();
                            $websocketController->sendNotifitication();
                        }
                        
                         
                        echo $result;
                    }else{
                        echo json_encode($mainController->AJAXmethod_error());
                    }
                    break;
                case 'delete' :
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        echo $mainController->AJAXdelete(); 
                    }else{
                        echo json_encode($mainController->AJAXmethod_error());
                    } 

                    break; 
            
                case 'comment':
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        echo $mainController->AJAXcomment(); 
                    }else{
                        echo json_encode($mainController->AJAXmethod_error());
                    } 
                    break;
            
                case 'user':
                    echo $mainController->AJAXget_user();
                    break;
                case 'update-role':
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        echo $mainController->AJAXupdate_role(); 
                    }else{
                        echo json_encode($mainController->AJAXmethod_error());
                    } 
                    break;   
                case 'update-password':
                    echo $mainController->AJAXpassword_change();
                    break;
                case 'users':
                    echo $mainController->AJAXgetAllUers();
                    break; 
                default:
                    http_response_code(404);
                    echo json_encode(['error' => 'Not Found']);
                }
            break;
        case 'logout':
            $authControlelr->logout();
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);

        break;
    }
    exit; 
}

switch ($uriSegments[0]) {
    case '':
    case 'home':
        $mainController->index();
        break;
    case 'movie':
        if (isset($uriSegments[1])) {
            $parts = explode('#', $uriSegments[1]);
            $movieId = $parts[0]; 
    
            if (is_numeric($movieId)) {
                $mainController->show($movieId);
            } else {
                echo "Error: Movie ID is not specified or is invalid.";
            }
        } else {
            echo "Error: Movie ID is not specified.";
        }
        break;
    
    case 'add-movie':
        $mainController->show_add_form();
        break;
    case 'signin':
        $authControlelr->index();
        break;
    case 'comedy':
    case 'action':
    case 'horror':
    case 'adventure':
    case 'drama':
    case 'anime':
        $mainController->showMoviesByGenre($uriSegments[0]);
        break;
    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
    break;
}



