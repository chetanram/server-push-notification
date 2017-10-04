<?php
define('ABSPATH', dirname(__FILE__) . '/');
include("conn.php");

//DEFINE('UPLOAD_URL',"admin/gallery/");
class webservice {
    private $connection;
//login,register,update_profile,verify_otp
    public function webservice($requestArr) {
        global $con;
        $this->connection = $con;
        $this->action = $requestArr['action'];
        switch ($this->action) {
           
            case 'push_android':
                $this->push_android($requestArr);
                break;
            case 'push_ios':
                $this->push_ios($requestArr);
                break;
        

        }
    }
   

    function push_android($requestArr){
        $title = mysqli_real_escape_string($this->connection,$requestArr['title']);
         $message = mysqli_real_escape_string($this->connection,$requestArr['message']);
         $symbol_id = mysqli_real_escape_string($this->connection,$requestArr['symbol_id']);
         $symbol_title = mysqli_real_escape_string($this->connection,$requestArr['symbol_title']);
         $timeframe = mysqli_real_escape_string($this->connection,$requestArr['timeframe']);
          $symbolmonth = mysqli_real_escape_string($this->connection,$requestArr['symbolmonth']);
         
         $device_token = mysqli_real_escape_string($this->connection,$requestArr['device_token']);
         // echo file_get_contents('php://input');exit;
           
            
        require_once 'Firebase.php';        
        $mPushNotification = array();
        $mPushNotification['data']['title'] = $title;
        $mPushNotification['data']['message'] = $message;
        $mPushNotification['data']['symbol_id'] = $symbol_id;
        $mPushNotification['data']['symbol_title'] = $symbol_title;
        $mPushNotification['data']['timeframe'] = $timeframe;
        $mPushNotification['data']['symbolmonth'] = $symbolmonth;
         
        $devicetoken = array($device_token);
         //creating firebase class object 
        $firebase = new Firebase(); 
         
         //sending push notification and displaying result 
        header('Content-type: application/json');
        echo $firebase->send($devicetoken, $mPushNotification);

    }
    function push_ios($requestArr){{

         $title = mysqli_real_escape_string($this->connection,$requestArr['title']);
         $message = mysqli_real_escape_string($this->connection,$requestArr['message']);
         $symbol_id = mysqli_real_escape_string($this->connection,$requestArr['symbol_id']);
         $symbol_title = mysqli_real_escape_string($this->connection,$requestArr['symbol_title']);
         $timeframe = mysqli_real_escape_string($this->connection,$requestArr['timeframe']);
          $symbolmonth = mysqli_real_escape_string($this->connection,$requestArr['symbolmonth']);
         
         $deviceToken = mysqli_real_escape_string($this->connection,$requestArr['device_token']);

        $passphrase = 'agc123';
        $ctx = stream_context_create();
        // ck.pem is your certificate file
        stream_context_set_option($ctx, 'ssl', 'local_cert', $_SERVER['DOCUMENT_ROOT'].'/'.basename(__DIR__).'/apns-dev-cert.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        // Create the payload body

        $body['aps'] = array(
            'alert' => array(
                'title' => $title,
                'body' => $message,
             ),
            'sound' => 'default'
        );

        $body['data']['title'] = $title;
        $body['data']['message'] = $message;
        $body['data']['symbol_id'] = $symbol_id;
        $body['data']['symbol_title'] = $symbol_title;
        $body['data']['timeframe'] = $timeframe;
        $body['data']['symbolmonth'] = $symbolmonth;
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
        
        // Close the connection to the server
        fclose($fp);
        if (!$result)
            return 'Message not delivered' . PHP_EOL;
        else{
            header('Content-type: application/json');
            echo json_encode($body); exit;
        
        }
    }
}
}

if (isset($_REQUEST['action'])) {

    $webservice = new webservice($_REQUEST);
} else {
    $jsonOutput['meta']['status'] = 'error';
    $jsonOutput['meta']['code'] = '400';
    $jsonOutput['meta']['message'] = 'Please enter proper action.';
    $jsonOutput['data'] = '';
    header('Content-type: application/json');
    echo json_encode($jsonOutput); 
}

?>
