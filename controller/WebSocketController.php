<?php 

include_once './model/NotificationModel.php'; 

class WebSocketController {

    private $notifiticationModel;

    public function __construct() {
        $this->notifiticationModel = new NotificationModel();
    }
    function sendNotifitication() {
        $data = array('type' => 'new_report', 'message' => 'New report added');
        $this->notifiticationModel->notifyNodeServer($data);
    
    }
}