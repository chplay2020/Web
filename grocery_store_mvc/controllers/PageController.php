<?php

require_once 'core/Controller.php';

class PageController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function about() {
        $this->requireAuth();
        $this->view('pages/about');
    }
    
    public function contact() {
        $this->requireAuth();
        
        $message = [];
        
        if (isset($_POST['send'])) {
            $userId = $this->getUserId();
            $name = $this->sanitize($_POST['name']);
            $email = $this->sanitize($_POST['email']);
            $number = $this->sanitize($_POST['number']);
            $msg = $this->sanitize($_POST['msg']);
            
            // Kiểm tra tin nhắn đã được gửi chưa
            $selectMessage = $this->db->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
            $selectMessage->execute([$name, $email, $number, $msg]);
            
            if ($selectMessage->rowCount() > 0) {
                $message[] = 'already sent message!';
            } else {
                // Thêm tin nhắn mới
                $insertMessage = $this->db->prepare("INSERT INTO `message`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
                $insertMessage->execute([$userId, $name, $email, $number, $msg]);
                $message[] = 'sent message successfully!';
            }
        }
        
        $this->view('pages/contact', ['message' => $message]);
    }
}
