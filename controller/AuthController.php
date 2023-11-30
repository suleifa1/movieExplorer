<?php

include_once './model/UserModel.php';


class AuthController{
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    } 
    public function index() {
        include './signin/auth.php'; 
    }
    public function register() {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        
        if (isset($input['email']) && isset($input['password'])) {
            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';
            $username = $input['name'] ?? '';

            if (empty($email) || empty($password)  || empty($username)) {
            
                return json_encode([
                    'success' => false, 
                    'error' => 'Please fill in all fields.',
                    'post' => $input
                ]);
            }
            $exist = $this->userModel->userExists($username, $email);
            if($exist){
                return  json_encode(['success' => false, 'error' => 'User exist.']);
                
            }

            $result = $this->userModel->registerUser($username, $email, $password);
            if ($result) {
                $this->userModel->loginUser($email, $password);
                
                return json_encode(['success' => true, 'redirect' => '/']); 
            } else {
                return json_encode(['success' => false, 'error' => 'Registration failed']);
            }
        } else {
            return json_encode(['success' => false, 'error' => 'Missing credentials']);
        }     
    }
    public function login() {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        if (isset($input['email']) && isset($input['password'])) {
            $email = $input['email'] ?? '';
            $password = $input['password'] ?? '';

            if (empty($email) || empty($password)) {
            
                return json_encode([
                    'success' => false, 
                    'error' => 'Please fill in all fields.',
                    'post' => $input
                ]);
            }

            $result = $this->userModel->loginUser($email, $password);
            if ($result) {
                echo json_encode(['success' => true, 'redirect' => '/']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Login failed']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Missing credentials']);
        }       
    }
    public function logout() {
        $result = $this->userModel->logout();
        if($result){
            echo json_encode(['success' => true, 'redirect' => '/']);
        }else{
            echo json_encode(['success' => false, 'error' => 'Logout failed']);
        }

        
    }
}
