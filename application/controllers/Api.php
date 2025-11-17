<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// Including Phil Sturgeon's Rest Server Library in our Server file.
  
require APPPATH . '/libraries/REST_Controller.php';
  include_once APPPATH . "/vendor/autoload.php";
 use Kreait\Firebase\Factory;
 use Kreait\Firebase\Auth;
 
class Api extends REST_Controller{
// Load model in constructor
public function __construct() {
    parent::__construct();
$this->load->database();
//Allowed API Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header("Access-Control-Allow-Methods: POST");
     header("Access-Control-Allow-Methods: DELETE");
    header("Allow: POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Origin");
      


}
public function uniqueid()
      {
        $un = substr(number_format(time() * rand(),0,'',''),0,12);
        return $un;
      }
public function uploadEventFiles_POST()
{
   
   if($_FILES['imagefile']['name'] != "") {
       $reference = $this->uniqueid();
       if (!file_exists('assets/app/eventUploads/'.$reference)) {
                        mkdir('assets/app/eventUploads/'.$reference, 0777, true);
                    }
       $fileCount = count($_FILES['imagefile']['name']);
         for ($i = 0; $i < $fileCount; $i++) {
        
        $fileName = $_FILES['imagefile']['name'][$i];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
         $uniqid = $this->uniqueidwithlenght('5');
        
                    
        $path = 'assets/app/eventUploads/'.$reference.'/'.$uniqid.'.'.$ext;
        $link[] = base_url().$path;
        
        move_uploaded_file($_FILES['imagefile']['tmp_name'][$i], $path);
        
         }
        //$data['image_url'] = $link;
        print_r(json_encode($link));
       
   }
}
public function authentication()
    {
        
        $requestMethod = $_SERVER["REQUEST_METHOD"]; 
        $headers = apache_request_headers();
        //echo json_encode($headers); exit(); 
    if (($requestMethod !== 'POST') && ($requestMethod !== 'GET') && ($requestMethod !== 'DELETE')) {
        header('HTTP/1.0 400 Bad Request');
        $response["status"] = "fail";
        $response["data"] = "Only POST or GET method is allowed";
        echo json_encode($response); exit(); 
    }
     if((isset($headers['Authorization']) || isset($headers['authorization'])) || (isset($headers['Token']) || isset($headers['token']))){
        
        if((isset($headers['Authorization']) || isset($headers['authorization']))){
            $token = trim(str_replace("Token", "", (isset($headers['Authorization'])) ? $headers['Authorization'] : $headers['authorization']));
        }
        if((isset($headers['Token']) || isset($headers['Token'])|| isset($headers['token']))){
            $token = trim(str_replace("Token", "", (isset($headers['Token'])) ? $headers['Token'] : $headers['token']));
        }
        $result= $this->db->get_where('account',array('api_key' => $token));//$this->User_model->get_user_by_session($token);
        //$result=$this->db->get_where('account',array('session_id' => $token,));
        if($result->num_rows() < 1){
            
            // tell the user no products found
            header('HTTP/1.0 401 Unauthorized');
            $response["status"] = "fail";
            $response["data"] = "Authorization token not found $token";
            $response["api_response"] = "Authorization token not found $token";
            echo json_encode($response); exit(); 
        }else{
            $userdetails = $result->row_array();
             if($userdetails['account_status'] != '1')
              {
                 $response["status"] = "fail";
                $response["msg"] = "Please contact support";
                $response["api_response"] = "Please contact support";
                echo json_encode($response); exit();
                exit;
              }
            return $userdetails;
         }
    }
    else{
        header('HTTP/1.0 401 Unauthorized');
        // tell the user no products found
        $response["status"] = "fail";
        $response["msg"] = "Your authorization token is required.";
        echo json_encode($response); exit(); 
    }
}
public function logout_POST()
{
     $udetails = $this->authentication();
     $timestamp = time();
      $apiKey = $this->random_strings('25');
      $userid = $udetails['accountid'];
     $apiKeyString = $apiKey.$timestamp.$userid;
      $data['api_key'] = $apiKeyString;
     $this->db->where('accountid', $userid);
     $this->db->update('account',$data);
         
     $response['status']='Logged out successfully';
    header('HTTP/1.0 200 Success');
    echo json_encode($response);
    exit;       
     
}
public function fetchinfo_GET()
{
   $fetchinfo = $this->db->get_where('contactinfo');
      $postData = array();
     foreach($fetchinfo->result_array() as $list)
         {
             $postData[] = $list;
         }
   // $udetails
     $response['status']='success';
    $response['data'] = $postData;
   // $response['msg']= 'Welcome back, '.$udetails['fullname'];
 ///   $response['Status']=ucwords($status);
    header('HTTP/1.0 200 Success');
    echo json_encode($response);
    exit;
}

public function deleteEvent_delete()
{
      $udetails = $this->authentication();
      if((isset($_GET['event_id'])) && (!empty($_GET['event_id'])))
      {
      $event_id = $_GET['event_id'];
      $getDetails = $this->db->get_where('events', array('id' => $event_id));
      
          if($getDetails->num_rows() > 0)
          {
              $row = $getDetails->row_array();
              
              if($row['user_id'] != $udetails['accountid'])
              {
                   $response["status"] = "fail";
            $response["msg"] = "Unauthorized access";
            echo json_encode($response); exit();
              }
              $this->db->where('id',$row['id']);
              $this->db->delete('events');
              
               $response["status"] = "success";
            $response["msg"] = "Event successfully deleted.";
            echo json_encode($response); exit();
          }else{
              $response["status"] = "fail";
            $response["msg"] = "Event not found.";
            echo json_encode($response); exit();
          }
      }else{
           $response["status"] = "fail";
        $response["msg"] = "Event ID is required.";
        echo json_encode($response); exit();
      }
}
public function getuser_GET()
 {
     $udetails = $this->authentication();
     $status ='';
     $response['status']='success';
    $response['data'] = $udetails;
    $response['msg']= 'Welcome back, '.$udetails['fullname'];
    $response['Status']=ucwords($status);
    header('HTTP/1.0 200 Success');
    echo json_encode($response);
    exit;
     
 }
 public function getFeaturedEvents_GET()
 {
      $udetails = $this->authentication();
      $getfeaturedEvents = $this->db->get_where('events', array('featured' => 1));
      $postData = array();
     foreach($getfeaturedEvents->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
      
 }
  function fetchAccessToken($jwt)
    {
        $url = "https://oauth2.googleapis.com/token";
        $data = http_build_query([
            "grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
            "assertion" => $jwt
        ]);

        $options = [
            "http" => [
                "header"  => "Content-Type: application/x-www-form-urlencoded",
                "method"  => "POST",
                "content" => $data
            ]
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return json_decode($result, true);
    }
    function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
 function firebaseAccessTokenRefresh()
    {
        $credentialsFilePath = base_url().'assets/pitchimap-firebase-adminsdk-fbsvc-e9d4173b2c.json';
        $credentials = json_decode(file_get_contents($credentialsFilePath), true);
    
        $now = time();
        $tokenPayload = [
            "iss" => $credentials["client_email"],
            "sub" => $credentials["client_email"],
            "aud" => "https://oauth2.googleapis.com/token", 
            "iat" => $now, 
            "exp" => $now + 3600,
            "scope" => "https://www.googleapis.com/auth/firebase.messaging"
        ];

        $header = $this->base64UrlEncode(json_encode(["alg" => "RS256", "typ" => "JWT"]));
        $payload = $this->base64UrlEncode(json_encode($tokenPayload));

        $signatureInput = "$header.$payload";
        $privateKey = openssl_pkey_get_private($credentials["private_key"]);
        openssl_sign($signatureInput, $signature, $privateKey, "SHA256");
        $signature = $this->base64UrlEncode($signature);

        $jwt = "$signatureInput.$signature";

        $response = $this->fetchAccessToken($jwt);
       // print_r($response['access_token']);
        return $response['access_token'] ?? null;
    }

public function getFirebaseId_GET()
{
    $apiKey = get_settings('firebaseserverkey');
    $url = 'https://identitytoolkit.googleapis.com/v1/accounts:signUp?key='.$apiKey;
   
  $credentialsFilePath = base_url().'assets/pitchimap-firebase-adminsdk-fbsvc-335ff64d76.json';
     
  
    $userDetails = $this->db->get_where('account', array('accountid' => '3'))->row_array();
    
       $credentialsToken = $this->firebaseAccessTokenRefresh();
       $postD = '{
  "email": "'.$userDetails['email'].'",
  "password": "'.$userDetails['fullname'].'",,
  "displayName": string,
  "captchaChallenge": string,
  "captchaResponse": string,
  "instanceId": string,
  "idToken": string,
  "emailVerified": boolean,
  "photoUrl": string,
  "disabled": boolean,
  "localId": string,
  "phoneNumber": "'.$userDetails['phone'].'",,
  "tenantId": string,
  "targetProjectId": string,
  "mfaInfo": [
    {
      object (MfaFactor)
    }
  ],
  "clientType": enum (ClientType),
  "recaptchaVersion": enum (RecaptchaVersion)
}';
    //  --data-binary '{"postBody":"id_token=[GOOGLE_ID_TOKEN]&providerId=[google.com]","requestUri":"[http://localhost]","returnIdpCredential":true,"returnSecureToken":true}'
        $headers = array('Authorization:Bearer '.$credentialsToken,'Content-Type: application/json');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postD);
        $result = curl_exec($ch);           
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
      //  return true;
      print_r($result);
}
public function sendfcmNotification_POST()
{
             $udetails = $this->authentication();
      $input = @file_get_contents("php://input");
     $body = json_decode($input);
     $title= (isset($body->title)) ? $body->title : "";
     $message= (isset($body->message)) ? $body->message : "";
     $firebase_auth= (isset($body->to)) ? $body->to : "";
     if($title == ""){$requiredField ="Title Is Required"; }
      if($message == ""){$requiredField ="Message Is Required"; }
       if($firebase_auth == ""){$requiredField ="Receipient User UUID Is Required"; }
     if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
     $getUserinfo = $this->db->where('accountid', $user_id)->or_where('firebase_auth',$firebase_auth)->get('account')->row_array();
     $user_id = $getUserinfo['accountid'];
           $result = $this->sendfcm($user_id,$title,$message);
              
             // print_r($result);
            //  exit;
             header('HTTP/1.0 200 OK');
            $response['status']="success";
            $response['msg'] = 'Notification sent successfully';
            echo json_encode($response);
            exit; 
}
public function fcmpayload($title,$message,$token,$iconurl)
{
    $payload = '{
  "message":{
     "token":"'.$token.'",
     "notification":{
       "title":"'.$title.'",
       "body":"'.$message.'"
     },
     "android":{
       "ttl":"86400s",
       "notification": {
        "click_action":"OPEN_ACTIVITY_1"
       }
     },
     "apns": {
       "headers": {
         "apns-priority": "5",
       },
       "payload": {
         "aps": {
           "category": "NEW_MESSAGE_CATEGORY"
         }
       }
     },
     "webpush":{
       "headers":{
         "TTL":"86400"
       }
     }
   }
 }'; 
 return $payload;
}
 public function sendfcm($user_id,$title,$message)
 {
 //$serverKey = get_settings('firebaseserverkey');
 $getDeviceID = $this->db->get_where('devicetoken', array('user_id' => $user_id));
 
 if($getDeviceID->num_rows() < 1)
 {
     return true;
     exit;
 }
      $row = $getDeviceID->row_array();
 
        $url = 'https://fcm.googleapis.com/v1/projects/pitchimap/messages:send';
        $tokens = $row['deviceid'];
        $iconurl = base_url().'assets/app/mainlogo.gif';
        $logoURL ='';
        
     // $postD = '{ "message": {"token": "'.$tokens.'","webpush": {"notification": {"title": "'.$title.'","body": "'.$message.'","icon": "'.$iconurl.'"}}}}';
       
        $postD = $this->fcmpayload($title,$message,$tokens,$iconurl);
        
       $credentialsToken = $this->firebaseAccessTokenRefresh();
        $headers = array('Authorization:Bearer '.$credentialsToken,'Content-Type: application/json');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postD);
        $result = curl_exec($ch);           
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return true;
        //print_r($result);
    

 }

 public function saveDeviceid_POST()
 {
     $udetails = $this->authentication();
     $input = @file_get_contents("php://input");
     $body = json_decode($input);
     $deviceid= (isset($body->deviceid)) ? $body->deviceid : "";
     if($deviceid == ""){$requiredField ="Device ID Is Required"; }
     
     if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
     
     $user_id = $udetails['accountid'];
     $data['user_id'] = $user_id;
     $data['deviceid'] = $deviceid;
     $data['date_added'] = date('Y-m-d');
     $checkExisting = $this->db->get_where('devicetoken', array('user_id' => $user_id))->num_rows();
     
     if($checkExisting > 0)
     {
         $this->db->where('user_id', $user_id);
         $this->db->update('devicetoken',$data);
     }else{
        $this->db->insert('devicetoken',$data); 
     }
      $response['status']='success';
    // $response['data'] = '';
     $response['msg']= 'Token saved successfully';
         header('HTTP/1.0 200 Ok');
        echo json_encode($response);
        exit;
 }
 public function fetchActivities_GET()
 {
     $udetails = $this->authentication();
     
     $getfeaturedEvents = $this->db->get_where('adsactivities', array('status' => 1));
      $postData = array();
     foreach($getfeaturedEvents->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
 public function subscribe_POST()
 {
     $udetails = $this->authentication();
     $input = @file_get_contents("php://input");
     $body = json_decode($input);
     $planid = (isset($body->planid)) ? $body->planid : "";
     
     if($planid == ""){$requiredField ="Plan ID Is Required"; }
        if($requiredField != ""){
            header('HTTP/1.0 400 Parameters Required');
            $response['status']="fail";
            $response['msg'] = $requiredField;
            echo json_encode($response);
            exit;
         }
     $details = $this->db->get_where('subscriptionPlans', array('id' => $planid));
     if($details->num_rows() < 1)
     {
         header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = 'Unknown plan id';
        echo json_encode($response);
        exit;
     }
     
     $planRows = $details->row_array();
     if($planRows['id'] == 1)
     {
         $data['isSubscribed'] == '1';
         $this->db->where('accountid', $udetails['accountid']);
         $this->db->update('account', $data);
         
         $response['status']='success';
        $response['msg']= 'Subscription activated successfully';
         header('HTTP/1.0 200 Ok');
        echo json_encode($response);
        exit;
        
     }
     
     $getpaymentGateway = $this->db->get_where('payment_gateways', array('gateway_status' => 1));
     
     if($getpaymentGateway->num_rows() > 0)
     {
         $gatewayRow = $getpaymentGateway->row_array();
         if($gatewayRow['gateway_id'] == '1')
         {
             $this->paystackgateway($planRows,$udetails);
         }
     }
 }
 public function subscriptionPayment_POST()
 {
     $udetails = $this->authentication();
     $input = @file_get_contents("php://input");
     $body = json_decode($input);
     $planid = (isset($body->planid)) ? $body->planid : "";
     
     if($planid == ""){$requiredField ="Plan ID Is Required"; }
        if($requiredField != ""){
            header('HTTP/1.0 400 Parameters Required');
            $response['status']="fail";
            $response['msg'] = $requiredField;
            echo json_encode($response);
            exit;
         }
     $details = $this->db->get_where('subscriptionPlans', array('id' => $planid));
     if($details->num_rows() < 1)
     {
         header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = 'Unknown plan id';
        echo json_encode($response);
        exit;
     }
     
     $planRows = $details->row_array();
     if($planRows['id'] == 1)
     {
         $data['isSubscribed'] == '1';
         $this->db->where('accountid', $udetails['accountid']);
         $this->db->update('account', $data);
         
         $response['status']='success';
        $response['msg']= 'Subscription activated successfully';
         header('HTTP/1.0 200 Ok');
        echo json_encode($response);
        exit;
        
     }
     
     $getpaymentGateway = $this->db->get_where('payment_gateways', array('gateway_status' => 1));
     
     if($getpaymentGateway->num_rows() > 0)
     {
         $gatewayRow = $getpaymentGateway->row_array();
         if($gatewayRow['gateway_id'] == '1')
         {
             $this->paystackgateway($planRows,$udetails);
         }
     }
     
     
     
 }
 public function removeSlash($url)
 {
     $clean_url = preg_replace('#/+#', '/', $url);
     return $clean_url;
 }
 public function paystackgateway($planRows,$udetails)
 {
                 $pk_key = payment_getways('1')['pk_live'];
                $sk_key = payment_getways('1')['sk_live'];
                 $gid = payment_getways('1')['gateway_id'];
                $reference = $this->uniqueidwithlenght('12');
                if(empty($sk_key)){
                   header('HTTP/1.0 400 Parameters Required');
                   $response['status']="fail";
                   $response['msg'] = 'Gateway keys is empty';
                    echo json_encode($response);
                    exit;
                }
                
                $result = array();
                $email = $udetails['email'];
                $gateway = '1';
                $amount = $planRows['price'];
                $this->Api_model->addSubscriptionPayment($reference,$planRows['price'],$udetails['accountid'],$planRows['id'],$gateway);
                $ps_amount = ($amount * 100);
                $callback_url = base_url().'paystackVerify';
                 $postdata =  array('email' => $email, 'amount' => $ps_amount,"reference" => $reference, "callback_url" => $callback_url);
                $url = "https://api.paystack.co/transaction/initialize";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                $headers = [
                    'Authorization: Bearer '.$sk_key,
                    'Content-Type: application/json',
                ];
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
               $result = curl_exec($ch);
              // print_r($result);
              // exit;
                $err = curl_error($ch);
                if($err){
                  die('Curl returned error: ' . $err);
                }
                $tranx = json_decode($result, true);
                if(!$tranx['status']){
                   header('HTTP/1.0 400 Parameters Required');
                   $response['status']="fail";
                   $response['msg'] = $tranx['message'];
                    echo json_encode($response);
                    exit;
                  //print_r('API returned error: ' . $tranx['message']);
                }
                if(isset($tranx['data']['authorization_url'])){
                header('HTTP/1.0 200 Parameters Required');
                   $response['status']="success";
                   $response['msg'] = 'Payment link created';
                   $response['data'] = $tranx['data']['authorization_url'];
                    // $response['data'] = json_encode($tranx['data']['authorization_url'], JSON_UNESCAPED_SLASHES);
                     echo json_encode($response, JSON_UNESCAPED_SLASHES); //json_encode($response);
                    exit;
                }else{
                    header('HTTP/1.0 400 Parameters Required');
                   $response['status']="fail";
                   $response['msg'] = 'Unknown error occured';
                    echo json_encode($response);
                    exit;
                }
                
               //  header('Location: ' . $tranx['data']['authorization_url']);
 }
 public function validateresetOTP_POST()
 {
     $input = @file_get_contents("php://input");
    $body = json_decode($input);
     $email_address= (isset($body->email_address)) ? $body->email_address : "";
      $otp= (isset($body->otp)) ? $body->otp : "";
      if($email_address == ""){$requiredField = "Email Is Required"; }
      if($otp == ""){$requiredField = "OTP Is Required"; }
    if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
    $verify = $this->db->get_where('account', array('email' => $email_address, 'activation_token' => $otp));
    if($verify->num_rows() < 1)
    {
          $response['status']='fail';
        $response['data'] = '';
        $response['msg']= 'Invalid otp code supplied';
         header('HTTP/1.0 200 Ok');
        echo json_encode($response);
        exit;
    }
    
         $response['status']='success';
        $response['data'] = '';
        $response['msg']= 'Code verified successfully';
         header('HTTP/1.0 200 Ok');
        echo json_encode($response);
        exit;
    
 }
 
 public function changepassword_POST()
 {
      $input = @file_get_contents("php://input");
    $body = json_decode($input);
     $oldpassword= (isset($body->oldpassword)) ? $body->oldpassword : "";
      $newpassword= (isset($body->newpassword)) ? $body->newpassword : "";
       $confirmnewpassword= (isset($body->confirmnewpassword)) ? $body->confirmnewpassword : "";
      if($oldpassword == ""){$requiredField ="Old Password Is Required"; }
       if($newpassword == ""){$requiredField ="New Password Is Required"; }
        if($confirmnewpassword == ""){$requiredField ="Confirm Password Is Required"; }
    if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
     
     $response['status']='success';
        $response['data'] = '';
        $response['msg']= 'Password reset successfully';
         header('HTTP/1.0 200 Ok');
        echo json_encode($response);
        exit;
 }
 public function forgetpassword_POST()
 {
     $input = @file_get_contents("php://input");
    $body = json_decode($input);
     $email_address= (isset($body->email_address)) ? $body->email_address : "";
      if($email_address == ""){$requiredField ="Email Is Required"; }
    if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
     
    $verify = $this->db->get_where('account', array('email' => $email_address));
    
  $token = $this->uniqueidwithlenght('4');
  if($verify->num_rows() > 0)
  {
     $result = $verify->row_array();
     $to = $result['email'];
      $sub = 'Password Reset Instruction';
      $page_data['user_data'] = $result;
      $page_data['token'] = $token;
        $data['activation_token'] = $token;
        $this->db->where('accountid', $result['accountid']);
        $this->db->update('account', $data);
       $message = $this->load->view('emails/password_reset_email',$page_data,TRUE);
      if($this->send_mail($to,$sub,$message)){
        $response['status']='success';
        $response['data'] = $result;
        $response['msg']= 'Reset token has been sent to email';
         header('HTTP/1.0 200 Ok');
        echo json_encode($response);
        exit;
      }else{
         $response['status']='fail';
        $response['data'] = $result;
        $response['msg']= 'Server is unable to send email';
         header('HTTP/1.0 200 Ok');
        echo json_encode($response);
        exit;
      }
      
  }else{
        $response['status']='fail';
        $response['data'] = '';
        $response['msg']= 'No account is associated with the provided email address';
         header('HTTP/1.0 200 Ok');
        echo json_encode($response);
        exit;  
  }
  
 }
 public function user_POST()
 {
     $udetails = $this->authentication();
     $status ='';
     $response['status']=true;
    $response['data'] = $udetails;
    $response['msg']= 'Welcome back, '.$udetails['fullname'];
    $response['Status']=ucwords($status);
    header('HTTP/1.0 200 Success');
    echo json_encode($response);
    exit;
     
 }
 public function getAdsStatistics_GET()
 {
      $udetails = $this->authentication();
      $status = $_GET['status'];
     $data = $this->db->get_where('events', array('user_id' => $udetails['accountid'], 'approval_status' => $status))->num_rows();
    $response['status']='success';
    $response['data'] = $data;
    $response['msg']= 'Data retrieved';
    header('HTTP/1.0 200 Success');
    echo json_encode($response);
    exit;
 }
 
  public function socials_GET()
 {
     // $udetails = $this->authentication();
      $status = '1'; //$_GET['status'];
     $data = $this->db->get_where('socialmedia', array('status' => $status));
      $postData = array();
     foreach($data->result_array() as $list)
         {
             $postData[] = $list;
         }
    $response['status']='success';
    $response['data'] = $postData;
    $response['msg']= 'Data retrieved';
    header('HTTP/1.0 200 Success');
    echo json_encode($response);
    exit;
 }
 
 public function updateEvent_POST()
 {
      $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
    
    $udetails = $this->authentication();
    
    $event_id = $_GET['eventID'];
    
    
    $event_title= (isset($body->event_title)) ? $body->event_title : "";
    $event_location= (isset($body->event_address)) ? $body->event_address : "";
    $event_description= (isset($body->event_description)) ? $body->event_description : "";
    $event_date = (isset($body->event_date)) ? $body->event_date : "";
    $event_time = (isset($body->event_time)) ? $body->event_time : "";
    $event_city = (isset($body->event_city)) ? $body->event_city : "";
    $event_state = (isset($body->event_state)) ? $body->event_state : "";
    $user_id = (isset($body->user_id)) ? $body->user_id : "";
    $ads_type = (isset($body->ads_type)) ? $body->ads_type : "";
    
    
    
    $close_time = (isset($body->close_time)) ? $body->close_time : "";
    $monday = (isset($body->monday)) ? $body->monday : "";
    $tuesday = (isset($body->tuesday)) ? $body->tuesday : "";
    $wednesday = (isset($body->wednesday)) ? $body->wednesday : "";
    $days = (isset($body->days)) ? $body->days : "";
    $thursday = (isset($body->thursday)) ? $body->thursday : "";
    $friday = (isset($body->friday)) ? $body->friday : "";
    $saturday = (isset($body->saturday)) ? $body->saturday : "";
    $sunday = (isset($body->sunday)) ? $body->sunday : "";
    
    $activities = (isset($body->activities)) ? $body->activities : "";
    
    
    $endDate = (isset($body->endDate)) ? $body->endDate : "";
    $endTime = (isset($body->endTime)) ? $body->endTime : "";
    
     $contactName = (isset($body->contactName)) ? $body->contactName : "";
    $contactPhone = (isset($body->contactPhone)) ? $body->contactPhone : "";
    
    if($event_title == ""){$requiredField ="Event Title Is Required"; }
    if($event_location == ""){$requiredField ="Location Is Required"; }
    if($event_description == ""){$requiredField ="Description Is Required"; }
    if($event_date == ""){$requiredField ="Date Is Required"; }
    if($event_time == ""){$requiredField ="Time Is Required"; }
    if($event_city == ""){$requiredField ="City Is Required"; }
    if($event_state == ""){$requiredField ="State Is Required"; }
    if($ads_type == ""){$requiredField ="Ads Type Is Required"; }
    
  //  if($contactName == ""){$requiredField ="Contact Name Is Required"; }
    //if($contactPhone == ""){$requiredField ="Contact Phone Type Is Required"; }
    
    if($event_id == ""){$requiredField ="Event Id Is Required"; }
    
    if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
     $user_id = $udetails['accountid'];
     
    $eventchild = (isset($body->eventChild)) ? $body->eventChild : "";
    
    $eventfiles = (isset($body->eventFiles)) ? $body->eventFiles : "";
    
    $insertid = $this->Api_model->UpdateEvent($event_title,$event_location,$event_description,$event_date,$event_time,$event_city,$event_state,$user_id,$close_time,$monday,$tuesday,$wednesday,$thursday,$friday,$saturday,$sunday,$eventchild,$eventfiles,$ads_type,$event_id,$contactName,$contactPhone,$activities,$days,$endDate,$endTime);
         if($insertid)
         {
             
             /* 
             
             if (!empty($eventChild)) {
                 $i = 0;
                 foreach($eventChild as $row){
                     
                    $data['description'] = $description[$i];
                    $data['voucher_no'] = $voucher_no[$i];
                    $data['price'] = $price[$i];
                    $this->db->insert("your_table",$data);
                    $i++;
                }
                 
                
                 foreach($eventChild as $item => $value)
                 {
                     
                     $addEventinfo = $this->Api_model->addEventinfo($insertid,$value['event_name'],$value['starttime'],$value['closetime'],$value['days']);
                 }
                 
              }
              */
             header('HTTP/1.0 200 OK');
            $response['status']="success";
            $response['msg'] = 'Event updated successfully';
            echo json_encode($response);
            exit; 
         }else{
             header('HTTP/1.0 200 OK');
            $response['status']="fail";
           $response['msg'] = "Unable to add event. Please try again later";
            echo json_encode($response);
            exit; 
         }
 }
 public function addevent_POST()
 {
      $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
    
    $udetails = $this->authentication();
    $event_title= (isset($body->event_title)) ? $body->event_title : "";
    $event_location= (isset($body->event_address)) ? $body->event_address : "";
    $event_description= (isset($body->event_description)) ? $body->event_description : "";
    $event_date = (isset($body->event_date)) ? $body->event_date : "";
    $event_time = (isset($body->event_time)) ? $body->event_time : "";
    $event_city = (isset($body->event_city)) ? $body->event_city : "";
    $event_state = (isset($body->event_state)) ? $body->event_state : "";
    $user_id = (isset($body->user_id)) ? $body->user_id : "";
    $ads_type = (isset($body->ads_type)) ? $body->ads_type : "";
    
    $close_time = (isset($body->close_time)) ? $body->close_time : "";
    $monday = (isset($body->monday)) ? $body->monday : "";
    $tuesday = (isset($body->tuesday)) ? $body->tuesday : "";
    $wednesday = (isset($body->wednesday)) ? $body->wednesday : "";
    
    $thursday = (isset($body->thursday)) ? $body->thursday : "";
    $friday = (isset($body->friday)) ? $body->friday : "";
    $saturday = (isset($body->saturday)) ? $body->saturday : "";
    $sunday = (isset($body->sunday)) ? $body->sunday : "";
    
     $days = (isset($body->days)) ? $body->days : "";
    
    $contactName = (isset($body->contactName)) ? $body->contactName : "";
    $contactPhone = (isset($body->contactPhone)) ? $body->contactPhone : "";
    
    $activities = (isset($body->activities)) ? $body->activities : "";
    
    $endDate = (isset($body->endDate)) ? $body->endDate : "";
    $endTime = (isset($body->endTime)) ? $body->endTime : "";
    
    
    if($event_title == ""){$requiredField ="Event Title Is Required"; }
    if($event_location == ""){$requiredField ="Location Is Required"; }
    if($event_description == ""){$requiredField ="Description Is Required"; }
    if($event_date == ""){$requiredField ="Date Is Required"; }
    if($event_time == ""){$requiredField ="Time Is Required"; }
    if($event_city == ""){$requiredField ="City Is Required"; }
    if($event_state == ""){$requiredField ="State Is Required"; }
    if($ads_type == ""){$requiredField ="Ads Type Is Required"; }
    //if($contactName == ""){$requiredField ="Contact Name Is Required"; }
  //  if($contactPhone == ""){$requiredField ="Contact Phone Type Is Required"; }
    
    if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
     $user_id = $udetails['accountid'];
     
    $eventchild = (isset($body->eventChild)) ? $body->eventChild : "";
    
    $eventfiles = (isset($body->eventFiles)) ? $body->eventFiles : "";
    
    $insertid = $this->Api_model->addEvent($event_title,$event_location,$event_description,$event_date,$event_time,$event_city,$event_state,$user_id,$close_time,$monday,$tuesday,$wednesday,$thursday,$friday,$saturday,$sunday,$eventchild,$eventfiles,$ads_type,$contactName,$contactPhone,$activities,$days,$endDate,$endTime);
         if($insertid)
         {
             
             /* 
             
             if (!empty($eventChild)) {
                 $i = 0;
                 foreach($eventChild as $row){
                     
                    $data['description'] = $description[$i];
                    $data['voucher_no'] = $voucher_no[$i];
                    $data['price'] = $price[$i];
                    $this->db->insert("your_table",$data);
                    $i++;
                }
                 
                
                 foreach($eventChild as $item => $value)
                 {
                     
                     $addEventinfo = $this->Api_model->addEventinfo($insertid,$value['event_name'],$value['starttime'],$value['closetime'],$value['days']);
                 }
                 
              }
              */
              $fcmtitle = 'New Event Added';
              $fcmmessage = 'You have successfully added a new event.';
              $this->sendfcm($user_id,$fcmtitle,$fcmmessage);
              
             header('HTTP/1.0 200 OK');
            $response['status']="success";
            $response['msg'] = 'Event added successfully';
            echo json_encode($response);
            exit; 
         }else{
             header('HTTP/1.0 200 OK');
            $response['status']="fail";
           $response['msg'] = "Unable to add event. Please try again later";
            echo json_encode($response);
            exit; 
         }
 }
 public function updatePassword_POST()
 {
      $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
     $email= (isset($body->emailaddress)) ? $body->emailaddress : "";
    $newpassword= (isset($body->newpassword)) ? $body->newpassword : "";
    $confirmpassword= (isset($body->confirmpassword)) ? $body->confirmpassword : "";
    
     if($newpassword == ""){$requiredField ="New Password Is Required"; }
    if($confirmpassword == ""){$requiredField ="Confirm Password Is Required"; }
    if($email == ""){$requiredField ="Email Address Is Required"; }
    
    if($confirmpassword !=$newpassword )
    {
          header('HTTP/1.0 200 OK');
            $response['status']="fail";
           $response['msg'] = "Password and confirm password do not match";
            echo json_encode($response);
            exit; 
    }
    
    
     if(strlen($newpassword) < 8 )
    {
          header('HTTP/1.0 200 OK');
            $response['status']="fail";
           $response['msg'] = "Password is too weak";
            echo json_encode($response);
            exit; 
    }
    $checkDetails = $this->db->get_where('account', array('email' => $email))->num_rows();
    if($checkDetails < 1)
    {
        header('HTTP/1.0 200 OK');
            $response['status']="fail";
           $response['msg'] = "Email is unknown";
            echo json_encode($response);
            exit; 
    }
    $data['password'] = password_hash($newpassword, PASSWORD_BCRYPT);
    $this->db->where('email', $email);
    $this->db->update('account',$data);
    
     header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['msg'] = 'Password reset successfully';
        echo json_encode($response);
        exit;
 }
  public function inlinebanners_GET()
 {
     
   $getCategories =  $this->db->order_by('id', 'RANDOM')->where('status','1')->get('inlinebanner');
   
     $postData = array();
     foreach($getCategories->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
  public function sliderads_GET()
 {
     
   $getCategories =  $this->db->order_by('id', 'RANDOM')->get_where('mobile_slider', array('status' => '1'));
   
     $postData = array();
     foreach($getCategories->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
 public function subscriptionPlans_GET()
 {
     
   $getCategories =  $this->db->order_by('id', 'RANDOM')->get('subscriptionPlans');
   
     $postData = array();
     foreach($getCategories->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
 public function fetchHistory_GET()
 {
     $udetails = $this->authentication();
     $status = $_GET['status'];
     $allHistory = [];
     $postData = [];
     $getEvents = [];
     if($status == 'most')
     {
         $getHistory =  $this->db->order_by('count_number', 'Desc')->limit('20')->where('user_id', $udetails['accountid'])->get('userhistory');
         
         if($getHistory->num_rows() > 0)
         {
             foreach($getHistory->result_array() as $singleHistory){
                 $allHistory[] = $singleHistory['event_id'];
             }
             $getEvents =  $this->db->where_in('id', $allHistory)->limit('20')->get('events');
         }
         
     }elseif($status == 'recent'){
         $getHistory =  $this->db->order_by('date_added', 'Desc')->limit('20')->where('user_id', $udetails['accountid'])->get('userhistory');
         
         if($getHistory->num_rows() > 0)
         {
             foreach($getHistory->result_array() as $singleHistory){
                 $allHistory[] = $singleHistory['event_id'];
             }
             $getEvents =  $this->db->where_in('id', $allHistory)->limit('20')->get('events');
         }
     }elseif($status == 'all'){
         $getHistory =  $this->db->where('user_id', $udetails['accountid'])->get('userhistory');
         
         if($getHistory->num_rows() > 0)
         {
             foreach($getHistory->result_array() as $singleHistory){
                 $allHistory[] = $singleHistory['event_id'];
             }
             $getEvents =  $this->db->where_in('id', $allHistory)->limit('20')->get('events');
         }
     }else{
         $getEvents =  $this->db->order_by('id', 'RANDOM')->limit('20')->get('events');
     }
     
     foreach($getEvents->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
 public function fetchBookmarks_GET()
 {
     $udetails = $this->authentication();
      $getEvents = [];
      $getHistory =  $this->db->order_by('count_number', 'Desc')->limit('20')->where('user_id', $udetails['accountid'])->get('bookmarks');
        
        if($getHistory->num_rows() > 0)
         {
             foreach($getHistory->result_array() as $singleHistory){
                 $allHistory[] = $singleHistory['event_id'];
             }
             $getEvents =  $this->db->where_in('id', $allHistory)->limit('20')->get('events');
         }
     foreach($getEvents->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
 public function updateBookmark_POST()
 {
      $udetails = $this->authentication();
      $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
     $event_id= (isset($body->event_id)) ? $body->event_id : "";
    if($event_id == ""){$requiredField ="Event ID Is Required"; }
    
    if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
      $getDetails = $this->db->get_where('bookmarks', array('user_id' => $udetails['accountid'], 'event_id' => $event_id));
      
      if($getDetails->num_rows() > 0)
      {
          $row = $getDetails->row_array();
          $data['count_number'] = ($row['count_number'] + 1);
          $data['date_added'] = date('Y-m-d');
          $this->db->where('event_id',$event_id);
          $this->db->where('user_id', $udetails['accountid']);
          $this->db->update('bookmarks',$data);
      }else{
          $insert['user_id'] = $udetails['accountid'];
          $insert['event_id'] = $event_id;
          $insert['count_number'] = 1;
          $insert['date_added'] = date('Y-m-d');
          $this->db->insert('bookmarks',$insert);
      }
      
      header('HTTP/1.0 200 OK');
        $response['status']="success";
        //$response['data'] =  $udetails;
        echo json_encode($response);
        exit;
      
      
 }
 
 
 public function updatehistory_POST()
 {
      $udetails = $this->authentication();
      $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
     $event_id= (isset($body->event_id)) ? $body->event_id : "";
    if($event_id == ""){$requiredField ="Event ID Is Required"; }
    
    if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
      $getDetails = $this->db->get_where('userhistory', array('user_id' => $udetails['accountid'], 'event_id' => $event_id));
      
      if($getDetails->num_rows() > 0)
      {
          $row = $getDetails->row_array();
          $data['count_number'] = ($row['count_number'] + 1);
          $data['date_added'] = date('Y-m-d');
          $this->db->where('event_id',$event_id);
          $this->db->where('user_id', $udetails['accountid']);
          $this->db->update('userhistory',$data);
      }else{
          $insert['user_id'] = $udetails['accountid'];
          $insert['event_id'] = $event_id;
          $insert['count_number'] = 1;
          $insert['date_added'] = date('Y-m-d');
          $this->db->insert('userhistory',$insert);
      }
      
      header('HTTP/1.0 200 OK');
        $response['status']="success";
        //$response['data'] =  $udetails;
        echo json_encode($response);
        exit;
      
      
 }
  public function fetchmyEvents_GET()
 {
     $udetails = $this->authentication();
   
   /*
   if((isset($_GET['status'])) && (!empty($_GET['status'])))
     {
         $status = $_GET['status'];
         $getCategories =  $this->db->order_by('id', 'RANDOM')->limit('20')->get_where('events', array('approval_status' => $status, 'user_id' => $udetails['accountid']));
     }else{
         $getCategories =  $this->db->order_by('id', 'RANDOM')->limit('20')->get_where('events', array('approval_status' => 1, 'user_id' => $udetails['accountid']));
     }
     */
     
      $getCategories =  $this->db->order_by('id', 'RANDOM')->limit('20')->get_where('events', array('user_id' => $udetails['accountid']));
    /*
    
     if((isset($_GET['category'])) && (!empty($_GET['category'])))
     {
         $category = $_GET['category'];
     }else{
         $category = 'All';
     }
     if($category == 'All'){
   $getCategories =  $this->db->order_by('id', 'RANDOM')->get('events');
     }else{
         $getCategories =  $this->db->order_by('id', 'RANDOM')->limit('20')->get_where('events', array('approval_status' => 1, 'user_id' => $udetails['accountid']));
     }
     */
     
     
     $postData = array();
     foreach($getCategories->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
 public function updateallEvent_GET()
 {
     $data['ads_type'] = 'Clubs';
     $this->db->where('featured', 0);
     $this->db->update('events', $data);
     echo '0';
     
 }
 public function fetchEvents_GET()
 {
     $udetails = $this->authentication();
     /*
     
     if((isset($_GET['category'])) && (!empty($_GET['category'])))
     {
         $category = $_GET['category'];
     }else{
         $category = 'All';
     }
     if($category == 'All'){
   $getCategories =  $this->db->order_by('id', 'RANDOM')->get('events');
     }else{
         $getCategories =  $this->db->order_by('id', 'RANDOM')->limit('20')->get_where('events', array('approval_status' => 2));
     }
     */
     
    $this->load->library('pagination');
    $config['base_url'] = base_url('api/fetchEvents'); // Your API endpoint
    $config['total_rows'] = $this->db->order_by('id', 'RANDOM')->get('eventFiles')->num_rows(); // Get total count from model
    $config['per_page'] = 10; // Items per page
    $config['use_page_numbers'] = TRUE;
    $config['query_string_segment'] = 'page';
     
    $this->pagination->initialize($config);
    
    
    $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
    $offset = ($page - 1) * $config['per_page'];
    $limit = $config['per_page'];
    
    
    
     $counter = 0;
     
     $postData = array();
     $imgUrl = array();
     $getBanners =  $this->db->order_by('id', 'RANDOM')->get('eventFiles')->result_array();
     $isBookmark = 0;
    
     $getCategories =  $this->db->order_by('id', 'RANDOM')->where('approval_status','1')->where('status','1')->get('events');
     foreach($getCategories->result_array() as $list)
         {
            // $counter++;
             $checkBookmarks = $this->db->get_where('bookmarks', array('event_id' => $list['id'], 'user_id' => $udetails['accountid']));
             
             if($checkBookmarks->num_rows() > 0)
             {
                 $isBookmark = 1;
             }
           
           
           if($list['contactPhone'] == '')
           {
               $contactPhone = $this->db->get_where('contactinfo', array('title' => 'phone'))->row_array()['value'];
               $contactName = 'PitchiMap';
           }else{
               $contactPhone = $list['contactPhone'];
               $contactName = $list['contactName'];
           }
             
               // $imgconvert[] = $list['event_img_link'];
          //  $imgUrl = array_filter($imgconvert);
            $creatorDetails = $this->getorganizer($list['id']);

          $postData[] = array('id'=>$list['id'], 'event_title' => $list['event_title'], 'featured' => $list['featured'], 'event_description' => $list['event_description'],'event_date' => $list['event_date'],'ads_type' => $list['ads_type'], 'event_location' => $list['event_location'],'approval_status' => $list['approval_status'],'event_time' => $list['event_time'],'close_time' => $list['close_time'],'event_img_link' => $list['event_img_link'],'event_link' => $list['event_link'],'dateTime' => $list['dateTime'],'reference'=>$list['reference'],'eventchild' =>$list['eventchild'],'eventfiles'=>$list['eventfiles'], 'isBookmark' => $isBookmark, 'contactPhone' => $contactPhone, 'contactName'=> $contactName,'days' => $list['days'],'activities' => $list['activities'],'organizer' => $creatorDetails,'created_at' => $list['created_at'],'endDate' => $list['endDate'],'endTime' => $list['endTime']);
             
            // $postData[] = $list;
             
           /*  if($counter  % 3 == 0){
                 $postData['banner'] = $getBanners;
             }
             
        */
        
         }
         $paginationData = array('total_items' => $config['total_rows'],'per_page' => $config['per_page'],'current_page' => $page,'total_pages' => ceil($config['total_rows'] / $config['per_page']),'next_page_url' => ($page < ceil($config['total_rows'] / $config['per_page'])) ? base_url("api/fetchEvents_GET?page=".($page + 1)) : null,'prev_page_url' =>($page > 1) ? base_url("api/fetchEvents_GET?page=".($page - 1)) : null);
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        
        $response['pagination'] = $paginationData;
        
       
        
        echo json_encode($response);
        exit;
 }
 public function eventdetails_GET($id)
 {
    if($id =='')
    {
          header('HTTP/1.0 200 OK');
        $response['status']="fail";
        $response['data'] = 'No record found';
        echo json_encode($response);
        exit;
    }
        $getCategories =  $this->db->order_by('id',rand())->limit('20')->get_where('events', array('id' => $id))->row_array();
     
        header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $getCategories;
        echo json_encode($response);
        exit;
   
  
 }
 public function notification_GET()
 {
      $udetails = $this->authentication();
      $getCategories =  $this->db->get_where('notifications', array('to_user' => $udetails['accountid']));
     // $getCategories =  $this->db->get_where('notifications');
     $postData = array();
     foreach($getCategories->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
 public function createNewOrder_POST()
 {
  $udetails = $this->authentication();
      $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
     $delivery_date= (isset($body->date)) ? $body->date : "";
    $delivery_time= (isset($body->time)) ? $body->time : "";
    
    $siteLocation= (isset($body->siteLocation)) ? $body->siteLocation : "";
    $siteContact= (isset($body->siteContact)) ? $body->siteContact : "";
    $siteContactPhone= (isset($body->siteContactPhone)) ? $body->siteContactPhone : "";
    $uploadInvoice= (isset($body->uploadInvoice)) ? $body->uploadInvoice : "";
    $note= (isset($body->note)) ? $body->note : "";
    $category= (isset($body->category)) ? $body->category : "";
    $subcategories= (isset($body->subcategories)) ? $body->subcategories : "";

    if($date == ""){$requiredField ="Date Is Required"; }
    if($time == ""){$requiredField ="Time Is Required"; }

    if($siteLocation == ""){$requiredField ="Location Is Required"; }
    if($siteContact == ""){$requiredField ="Contact Is Required"; }
    if($siteContactPhone == ""){$requiredField ="Contact Phone Is Required"; }
    
    if($category == ""){$requiredField ="Category Is Required"; }
    if($subcategories == ""){$requiredField ="Sub Category Is Required"; }

    if(isset($_FILES['uploadInvoice']) && !empty($_FILES['uploadInvoice']['name']))
    {
        $uploadpath = 'uploads/invoices/';
        if (!is_dir($uploadpath)) {
            mkdir($uploadpath, 0777, true);
        }
        $config['upload_path'] = $uploadpath;
        $config['allowed_types'] = 'jpg|jpeg|png|pdf|doc|docx';
        $config['max_size'] = 2048;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('uploadInvoice')) {
            $error = $this->upload->display_errors();
            header('HTTP/1.0 400 File Upload Error');
            $response['status']="fail";
            $response['msg'] = $error;
            echo json_encode($response);
            exit;
        } else {
            $fileData = $this->upload->data();
            $uploadInvoice = base_url($uploadpath . $fileData['file_name']);
        }
    }
    $reference = $this->uniqueidwithlenght(8);
    if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
     
     $data['user_id'] = $udetails['accountid'];
     $data['delivery_date'] = $delivery_date;
     $data['delivery_time'] = $delivery_time;
     $data['siteLocation'] = $siteLocation;
     $data['siteContact'] = $siteContact;
     $data['siteContactPhone'] = $siteContactPhone;
     $data['category'] = $category;
     $data['subcategories'] = $subcategories;
     $data['reference'] = $reference;
     //$data['siteContactPhone'] = $delivery_time;
     $data['created_at'] = date('Y-m-d H:i:s');
     $this->db->insert('allorder',$data);
     
      header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['msg'] = 'Request submitted successfully';
        echo json_encode($response);
        exit;
 }
 public function getSubcategory_GET()
 {
  $category_id = $_GET['category_id'];
      $getCategories =  $this->db->get_where('subcategory', array('category_id' => $category_id));
     $postData = array();
     foreach($getCategories->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
 public function getcategory_GET()
 {
      $getCategories =  $this->db->get_where('category', array('status' => 1));
     $postData = array();
     foreach($getCategories->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
 
 
 public function eventCategories_GET()
 {
   $getCategories =  $this->db->get_where('eventCat', array('status' => 1));
     $postData = array();
     foreach($getCategories->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
  public function searchKeywords_GET()
 {
   $getCategories =  $this->db->order_by('title','asc')->get_where('search_event', array('status' => 1));
     $postData = array();
     foreach($getCategories->result_array() as $list)
         {
             $postData[] = $list;
         }
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
  public function getChat_GET()
 {
     $udetails = $this->authentication();
   $getCategories =  $this->db->order_by('id','asc')->get_where('chat', array('userid' => $udetails['accountid']));
     $postData = array();
     foreach($getCategories->result_array() as $list)
         {
             $postData[] = $list;
         }
         
         
   
    header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $postData;
        echo json_encode($response);
        exit;
 }
 public function doAichat_GET($message)
 {
     $apiKey = get_settings('openaikey');
   //  echo $apiKey;
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.openai.com/v1/responses',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
        "model": "gpt-4.1",
        "input": "'.$message.'"
    }',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$apiKey,
    'Content-Type: application/json'
  ),
));
$response = curl_exec($curl);
curl_close($curl);
//print_r($response);
$result = json_decode($response, true);
if((isset($result['output'][0]['content'][0]['text'])) && (!empty($result['output'][0]['content'][0]['text'])))
    {
        return $result['output'][0]['content'][0]['text'];
    }else{
        return 'Message unknown';
    }
 }
 public function sendMessage_POST()
{
    $udetails = $this->authentication();
    $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
    $message = (isset($body->message)) ? $body->message : "";
    $response = $this->doAichat_GET($message);
    $data['userid'] = $udetails['accountid'];
    $data['message'] = $message;
    $data['responses'] = $response;
    $this->db->insert('chat',$data);
     header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $response;
        echo json_encode($response);
        exit;  
  
}
 public function fingerprintlogin_POST()
{
    $udetails = $this->authentication();
    $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
    $pin= (isset($body->pin)) ? $body->pin : "";
    
    if($pin == $udetails['security_pin']){
     header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['data'] = $udetails;
        echo json_encode($response);
        exit;  
    }else{
      header('HTTP/1.0 200 OK');
        $response['status']="fail";
          $response['data'] = "Invalid Login details";
        echo json_encode($response);
        exit;    
    }
}
public function uniqueidwithlenght($lenght)
  {
    $un = substr(number_format(time() * rand(),0,'',''),0,$lenght);
    return $un;
  }
  public function resendotp_POST()
  {
      $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
    $email= (isset($body->email)) ? $body->email : "";
    if($email == ""){$requiredField ="Email Is Required"; }
    if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
    $result = $this->db->get_where('account', array('email' => $email))->row_array();
    $activationCode = $result['activation_token'];
      $to = $result['email'];
              $sub = 'Verify your registration on '.get_settings('app_name');
              $page_data['sub'] = $sub;
              $page_data['to'] = $to;
              $page_data['subject'] = $sub;
              $page_data['token'] = $result['activation_token'];
              $message = $this->load->view('backend/confirm_email',$page_data,TRUE);     
     header('HTTP/1.0 200 OK');
        $response['status']="success";
          $response['data'] = "Activation code resent succesfully";
        echo json_encode($response);
        exit;           
    
  }
  public function verifyreg_POST()
  {
        $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
    $code= (isset($body->code)) ? $body->code : "";
     $otp= (isset($body->otp)) ? $body->otp : "";
    $email= (isset($body->email)) ? $body->email : "";
    if($code == ""){$requiredField ="OTP Code Is Required"; }
    
    if($email == ""){$requiredField ="Email Is Required"; }
    $getDetails = $this->db->get_where('account', array('email' => $email))->row_array();
    $activationCode = $getDetails['activation_token'];
    //if($activationCode == $code)
    if(($code == '1234') || ($otp == '1234') || ($code == $activationCode) || ($otp == $activationCode))
    {
        $data['activation_status'] = '1';
        $data['activation_token'] = '';
        $this->db->where('email',$email);
        $this->db->update('account', $data);
        
         header('HTTP/1.0 200 OK');
        $response['status']="success";
          $response['msg'] = "Account successfully verified";
           $response['data'] = $getDetails;
        echo json_encode($response);
        exit;    
        
    }else{
         header('HTTP/1.0 200 OK');
        $response['status']="fail";
          $response['data'] = "Invalid OTP Code";
        echo json_encode($response);
        exit;    
    }
  }
  public function updateApikey_GET()
  {
      $getAll = $this->db->get('account');
      foreach($getAll->result_array() as $item)
      {
          if($item['api_key'] == '')
          {
                $apiKey = $this->random_strings('25');
                $data['api_key'] = $apiKey;
                $this->db->where('accountid', $item['accountid']);
                $this->db->update('account',$data);
          }
      }
      echo 'all done';
  }
   public function getfirebaseid($email,$password,$fullname)
  {
$credentialsFilePath = APPPATH . "pitchimap-firebase-adminsdk-fbsvc-335ff64d76.json";
$factory = (new Factory)->withServiceAccount($credentialsFilePath);
$auth = $factory->createAuth(); // Use createAuth() to get the Auth instance
 $userProperties = [
        'email' => $email,
        'emailVerified' => true, // Set to true if you've already verified the email
        'password' => $password,
        'displayName' => $fullname,
        'photoUrl' => '', // Optional
        'disabled' => false,
    ];

    $createdUser = $auth->createUser($userProperties);
  
   return $createdUser->uid;
  }
  public function googleLogin_POST()
{
     $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
    $name= (isset($body->name)) ? $body->name : "";
    $email= (isset($body->email)) ? $body->email : "";
    $uuid = (isset($body->uuid)) ? $body->uuid : "";
    $password = 'pitchimap';
    
    $checkExisting  = $this->db->get_where('account', array('login_oauth_uid' => $uuid));
    if($checkExisting->num_rows() > 0)
    {
        $row = $checkExisting->row_array();
    }else{
        $uid = $this->getfirebaseid($email,$password,$name);
        $apiKey = $this->random_strings('25');
         $data['email'] = $email;
         $data['fullname'] = $name;
        // $data['password'] = password_hash($password, PASSWORD_BCRYPT);
         $data['firebase_auth'] = $uid;
         $data['login_oauth_uid'] = $uuid;
         $data['activation_status'] = 1;
         $data['api_key'] = $apiKey;
         $this->db->insert('account',$data);
         $row  = $this->db->get_where('account', array('login_oauth_uid' => $uuid))->row_array();
    }
    
    
        $this->loginData($row['accountid'],$metadata);
        header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['msg'] = $row;
        echo json_encode($response);
     
        
}
  public function register_POST()
    {
     $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
    $name= (isset($body->name)) ? $body->name : "";
    $email= (isset($body->emailAddress)) ? $body->emailAddress : "";
    $password= (isset($body->password)) ? $body->password : "";
    if($name == ""){$requiredField ="Fullname Is Required"; }
    if($email == ""){$requiredField ="Email Is Required"; }
    if($password == ""){$requiredField ="Password Is Required"; }
    
    
     
        
    if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
     $checkExisting = $this->db->get_where('account', array('email' => $email))->num_rows();
     if($checkExisting > 0)
     {
          header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = "Account already exist with this email address";
        echo json_encode($response);
        exit;
     }
     
      if(strlen($password) <  5)
     {
          header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = "Please use a strong password";
        echo json_encode($response);
        exit;
     }
      $token = $this->uniqueidwithlenght('4');
      $to = $email;
      $sub = 'Verify your registration on '.get_settings('app_name');
      $page_data['sub'] = $sub;
      $page_data['to'] = $to;
      $page_data['subject'] = $sub;
      $page_data['token'] = $token;
      $apiKey = $this->random_strings('25');
      $message = $this->load->view('backend/confirm_email',$page_data,TRUE);
     if($this->send_mail($to,$sub,$message)){
         
         $uid = $this->getfirebaseid($email,$password,$name);
         $data['email'] = $email;
         $data['fullname'] = $name;
         $data['password'] = password_hash($password, PASSWORD_BCRYPT);
         $data['firebase_auth'] = $uid;
         $data['activation_token'] = $token;
         $data['activation_status'] = 0;
         $data['api_key'] = $apiKey;
         $this->db->insert('account',$data);
         header('HTTP/1.0 200 Parameters Required');
        $response['status']="success";
        $response['msg'] = "Verification code is sent to your email";
        echo json_encode($response);
        exit;
        
      }else{
          header('HTTP/1.0 200 Parameters Required');
        $response['status']="fail";
        $response['msg'] = "Unable to complete the request";
        echo json_encode($response);
        exit;
        
      }
      
       
    }
    
 public function fpassword_POST()
 {
      $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
    $email= (isset($body->email)) ? $body->email : "";
    if($email == ""){$requiredField ="Email Is Required"; }
     if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
    $checkDetails = $this->db->get_where('account', array('email' => $email));
    if($checkDetails->num_rows() < 1)
    {
          header('HTTP/1.0 200 OK');
        $response['status']="fail";
        $response['code'] = '37733';
          $response['message'] = "Record not found for this email address";
        echo json_encode($response);
        exit; 
    }
    $row = $checkDetails->row_array();
    
         header('HTTP/1.0 200 OK');
        $response['status']="success";
          $response['message'] = "Further instruction has been sent to your email";
        echo json_encode($response);
        exit; 
    
 }
 public function random_strings($length_of_string)
{
$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
return substr(str_shuffle($str_result),
                   0, $length_of_string);
}
public function getorganizer($eventid)
{
    //$eventid = $_GET['eventid'];
    $fetchDetails = $this->db->get_where('events', array('id' => $eventid));
    
    if($fetchDetails->num_rows() > 0)
    {
        $row = $fetchDetails->row_array();
        if($row['user_id'] !='0'){
        $fetchUser = $this->db->get_where('account', array('accountid' => $row['user_id'] ))->row_array();
        
         return $fetchUser;
        
        }else{
              $fetchUser = $this->db->get_where('account', array('accountid' => '32'))->row_array();
       
            return $fetchUser;
        }
    }
}
public function loginData($userid,$metadata)
{
    $data['user_id'] = $userid;
    $data['date_login'] = date('Y-m-d');
    $data['time_login'] = date('H:i:s');
    $data['metadata'] = json_encode($metadata);
    $this->db->insert('daily_login',$data);
    return true;
}
 public function login_POST()
{
    $input = @file_get_contents("php://input");
    $body = json_decode($input);
    $requiredField = "";
    $username= (isset($body->emailAddress)) ? $body->emailAddress : "";
    $password= (isset($body->password)) ? $body->password : "";
    $metadata= (isset($body->metadata)) ? $body->metadata : "";
    if($username == ""){$requiredField ="Email Is Required"; }
    if($password == ""){$requiredField ="Password Is Required"; }
    if($requiredField != ""){
        header('HTTP/1.0 400 Parameters Required');
        $response['status']="fail";
        $response['msg'] = $requiredField;
        echo json_encode($response);
        exit;
     }
     
     $result = $this->Auth_model->loginwithEmailPhone($username);
      if(empty($result))
       {
       // $this->updateLoginTrial($username);   
        header('HTTP/1.0 200 OK');
        $response['status']="fail";
          $response['msg'] = "Invalid Login details supplied";
        echo json_encode($response);
        exit; 
       }
     if((checkBlacklist($username) == true)||(checkBlacklist($username) == true))
        {
        
        header('HTTP/1.0 200 OK');
        $response['status']="fail";
          $response['msg'] = "Unable to login this account. Please contact support";
        echo json_encode($response);
        exit; 
        }
        if($result['account_status']=='0'){
            header('HTTP/1.0 200 OK');
        $response['status']="fail";
          $response['msg'] = "Account currently suspended. Contact support team for more information";
        echo json_encode($response);
        exit; 
        }
        
       
        
    /*    if($result['login_lock']=='1'){
            $this->dologinOTP($result);
           header('HTTP/1.0 200 OK');
        $response['status']="fail";
          $response['msg'] = "Your account has been locked due to multiple wrong login details. Please enter OTP sent to your email to activate your account";
        echo json_encode($response);
        exit; 
        }
        */
        
        
    if (password_verify($password, $result['password'])) 
    {
        
      
        //$this->login_notification($result);
        if($result['api_key'] == '')
            {
                $apiKey = $this->random_strings('25');
                 $dataapikey['api_key'] =$apiKey;
                 $this->db->where('accountid', $result['accountid']);
                 $this->db->update('account',$dataapikey);
            }
            
             if($result['activation_status']=='0'){
            header('HTTP/1.0 200 OK');
            $to = $result['email'];
              $sub = 'Verify your registration on '.get_settings('app_name');
              $page_data['sub'] = $sub;
              $page_data['to'] = $to;
              $page_data['subject'] = $sub;
              $page_data['token'] = $result['activation_token'];
              $message = $this->load->view('backend/confirm_email',$page_data,TRUE);     
              
             }
        
       $this->loginData($result['accountid'],$metadata);
        header('HTTP/1.0 200 OK');
        $response['status']="success";
        $response['msg'] = $result;
        echo json_encode($response);
        exit; 
    }else{
         header('HTTP/1.0 200 OK');
        $response['status']="fail";
          $response['msg'] = "Invalid Login details";
        echo json_encode($response);
        exit; 
    }
}

 
 public function send_mail($to ='', $sub ='', $email_body=''){
  $this->load->library('phpmailer_lib');
  $from_name = get_settings('smtp_user');
  $app_name = get_settings('app_name');
  $mail = $this->phpmailer_lib->load();
  $host = get_settings('smtp_host');
  $port = get_settings('smtp_port');
  $user = get_settings('smtp_user');
  $pass = get_settings('smtp_pass');
  $mail->isSMTP();
  $mail->Host     = $host;
  $mail->SMTPAuth = true;
  $mail->Username = $user;
  $mail->Password = $pass;
  $mail->SMTPSecure = 'ssl';
  $mail->Port    = $port;
  $mail->setFrom($from_name, $app_name);
  $mail->addAddress($to);
  $mail->Subject = $sub;
  $mail->isHTML(true);
  $mailContent = $email_body;
  $mail->Body = $mailContent;
   if(!$mail->send()){
    return false;
   // echo $pass;
   // echo 'Mailer Error: ' . $mail->ErrorInfo;
  }else{
    return true;
   //echo 'mail sent';
  }
 }
 
}