<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . "/vendor/autoload.php";
 use Kreait\Firebase\Factory;
 use Kreait\Firebase\Auth;
class Landing extends CI_Controller {
      protected $auth;
	function __construct(){
    parent::__construct();
		  $this->load->database();
        $this->load->library('session');
       $this->load->model('Auth_model');
       $this->load->model('User_model');
      
      // $this->load->library('google'); 
	}
public function index(){
	
    $page_data['page_name'] = "home";
    $page_data['page_title'] = 'Login Page';
    $this->load->view('Landing/index', $page_data);
  }
  public function convertImg()
  {
$path = 'assets/images/random.jpeg';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
echo $base64;
  }
  public function get_userBulkEmail($status,$limit)
{
    $this->db->where('broadCastEmailStatus', $status);
    $this->db->limit($limit);
    $query = $this->db->get('account');
    return $query;
}
  public function sendBulkEmail()
 {
$status = '1';
$get_result = $this->db->get_where('broadcast', array('id' => '1', 'status' => '1'));
if($get_result->num_rows() < 1)
{
  exit;
}else{
    $row = $get_result->row_array();
    $limit = '100';
    $get_details = $this->get_userBulkEmail($status,$limit);//$this->db->get_where('account', array('accountid' => '569'));
        if($get_details->num_rows() < 1)
        {
            $data['status'] =  '0';$this->db->where('id',$status);$this->db->update('broadcast',$data);exit; 
        }else{
            
        foreach($get_details->result_array() as $item){
            $page_data['message'] = $row['body'];
            $page_data['title'] = $row['title'];
            $page_data['email'] = $item['email'];
            $page_data['data'] = $item;
            $message = $this->load->view('emails/bulkemail',$page_data,TRUE);
           $this->send_mail($item['email'],$row['title'],$message);
           $dataA['broadCastEmailStatus'] =  '0';
            $this->db->where('email',$item['email']);
            $this->db->update('account',$dataA);
           }
            exit;
            
        }
         exit;
            //$data_queue['status'] =  '1';
            //$this->db->where('id',$list['id']);
            //$this->db->update('cron_queue',$data_queue);
    }

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
  public function runCronBookmarks()
  {
      
      $todayDate = date('Y-m-d');
      $currentTime = date('H:i');
      $getAllToayEvents = $this->db->get_where('events', array('event_date' => $todayDate));
      if($getAllToayEvents->num_rows() > 0)
      {
          foreach($getAllToayEvents->result_array() as $each)
          {
              if($currentTime == $each['event_time'])
              {
                 $getBookmarks =  $this->db->get_where('bookmarks', array('event_id' => $each['event_id']));
                 if($getBookmarks->num_rows() > 0)
                 {
                     foreach($getBookmarks->result_array() as $items)
                     {
                          $fcmtitle = $each['event_title'].' is Live';
                          $fcmmessage = 'Hello, we are please to notify you that the event you bookmark is live now.';
                          $this->sendfcm($items['user_id'],$fcmtitle,$fcmmessage);
                     }
                 }else{
                          echo 1;
                      }
              }else{
                      echo 1;
                  }
          }
      }else{
          echo 1;
      }
  }
  public function websocket(){
	$this->load->library('Codeigniter_websocket');
	$this->config->set_callback('auth', array($this, '_auth'));
        $this->config->set_callback('event', array($this, '_event'));
        $this->config->run();
    $page_data['page_name'] = "home"; 
    $page_data['page_title'] = 'Login Page';
    $this->load->view('Landing/index', $page_data);
  }
 
  public function firebaseID()
  {
       $apiKey = get_settings('firebaseserverkey');
    $url = 'https://identitytoolkit.googleapis.com/v1/accounts:signUp?key='.$apiKey;
   
            
            
  $credentialsFilePath =  APPPATH . "/pitchimap-firebase-adminsdk-fbsvc-335ff64d76.json"; //'https://admin.pitchimap.com/assets/pitchimap-firebase-adminsdk-fbsvc-335ff64d76.json';
   
     $factory = new Factory();
  $factory->withServiceAccount($credentialsFilePath); 
    $auth = $factory->createAuth();   
     
    
  }
  public function loginwithGoogle()
  {
      include_once APPPATH . "vendor/autoload.php";

      $google_client = new Google_Client();
    
      $google_client->setClientId(get_settings('google_client_id')); //Define your ClientID
    
      $google_client->setClientSecret(get_settings('google_secrete_key')); //Define your Client Secret Key
    
      $google_client->setRedirectUri(get_settings('google_redirect_uri')); //Define your Redirect Uri
    
      $google_client->addScope('email');
    
      $google_client->addScope('profile');

  
  
      $login_button = '';
     
       $login_button = '<a href="'.$google_client->createAuthUrl().'"><img src="'.base_url().'asset/sign-in-with-google.png" /></a>';
      
      redirect($google_client->createAuthUrl());
     
 

        
         //$data['loginURL'] = $this->google->loginURL(); 
         
         //print_r(this->google->loginURL());
  }
  
  public function authlogin()
  {
       include_once APPPATH . "vendor/autoload.php";

      $google_client = new Google_Client();
      
       $google_client->setClientId(get_settings('google_client_id')); //Define your ClientID
    
      $google_client->setClientSecret(get_settings('google_secrete_key')); //Define your Client Secret Key
    
      $google_client->setRedirectUri(get_settings('google_redirect_uri')); //Define your Redirect Uri
    
      $google_client->addScope('email');
    
      $google_client->addScope('profile');

  
  
  
      
                 if(isset($_GET["code"]))
                   {
                   $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);
                
                   if(!isset($token["error"]))
                   {
                    $google_client->setAccessToken($token['access_token']);
                
                    $this->session->set_userdata('access_token', $token['access_token']);
                
                    $google_service = new Google_Service_Oauth2($google_client);
                
                    $data = $google_service->userinfo->get();
                
                    $current_datetime = date('Y-m-d H:i:s');
                    
                    $checkExisting  = $this->db->get_where('account', array('email' => $data['email']));
                    
                    
                    if($checkExisting->num_rows() > 0)
                    {
                        
                        $userDetails['login_oauth_uid'] = $data['id'];
                        $userDetails['profile_picture'] = $data['picture'];
                        $this->db->where('email', $data['email']);
                        $this->db->update('account',$userDetails);
                        
                        $fetchfreshdata  = $this->db->get_where('account', array('email' => $data['email']))->row_array();
                        
                         header('HTTP/1.0 200 OK');
                        $response['status']="success";
                        $response['msg'] = $fetchfreshdata;
                        echo json_encode($response);
                        exit; 
                    }
                    else
                    {
                        
                     //insert data
                     
                     $dataNew['email'] = $data['email'];
                     $dataNew['fullname'] =  $data['given_name'].' '.$data['family_name'];
                     $dataNew['profile_picture'] = $data['picture'];
                     $dataNew['login_oauth_uid'] = $data['id'];
                      
                      $fetchfreshdata  = $this->db->get_where('account', array('email' => $data['email']))->row_array();
                      
                     $this->db->insert('account', $dataNew);
                      header('HTTP/1.0 200 OK');
                        $response['status']="success";
                        $response['msg'] = $fetchfreshdata;
                        echo json_encode($response);
                        exit; 
                        
                     
                    }
                    
                   }else{
                       
                       print_r($token["error"]);
                       
                        header('HTTP/1.0 200 OK');
                        $response['status']="error";
                        $response['msg'] = 'Error occured. Please try again later';
                        echo json_encode($response);
                        exit; 
                        
                   }
                  }else{
                       redirect('Landing/loginwithGoogle');
                  }
                  
                
  
  }
 public function paystackVerify()
 {
      $reff = ($this->input->get('trxref')) ? $this->input->get('trxref') : $this->input->get('reference');
    $sk_key = payment_getways('1')['sk_live'];
     $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/".$reff,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Authorization: Bearer ".$sk_key,
      "Cache-Control: no-cache",
    ),
  ));
  $apiresult = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);
  $result = json_decode($apiresult,true);
  if((isset($result['data']['status']))&&($result['data']['status'] == 'success')){
  $ref_id = $result['data']['id'];
  $pickDetails = $this->db->get_where('subscriptionPayment',array('reference'=>$reff));
  if($pickDetails->num_rows() > 0){
      $row = $pickDetails->row_array();
      
      $user_id = $row['user_id'];
      if($row['status'] == '1')
      {
                 header('HTTP/1.0 400 Parameters Required');
                   $response['status']="fail";
                   $response['msg'] = 'Payment already completed. Please start another process';
                    echo json_encode($response);
                    exit;
      }
     // $this->User_model->updatePaymentStatus($user_id,$reff,$row['amount']);
       $data['isSubscribed'] = $row['plan_id'];
         $this->db->where('accountid', $user_id);
         $this->db->update('account', $data);
         
         $response['status']='success';
        $response['msg']= 'Subscription activated successfully';
         header('HTTP/1.0 200 Ok');
        echo json_encode($response);
        exit;
          
                    exit;
  }else{
     header('HTTP/1.0 400 Parameters Required');
                   $response['status']="fail";
                   $response['msg'] = 'Unable to continue with the request. Please contact support';
                    echo json_encode($response);
                    exit;
      exit;
  }
  }
 }
 
 
  public function resetReversalCount()
{
    $getUsers = $this->db->get('account');
    
    foreach($getUsers->result_array() as $each)
    {
        $dataR['dailyReversal'] =  '0' ;$this->db->where('accountid',$each['accountid']);$this->db->update('account',$dataR);
    }
    return true;
}
 public function checkReversal()
 {
    // return true;
    $status = '1';
    $get_result = $this->User_model->checkReversal($status);
if($get_result->num_rows() < 1)
{
  exit;
}else{
    foreach($get_result->result_array() as $list){
          
                   if($list['status'] == 1){
                        $data['checkReversal'] =  '0';$this->db->where('id',$list['id']);$this->db->update('transactions',$data);
                    }else{
                         $udetails = get_user_info($list['user_id']);
                        if($udetails['dailyReversal'] < 6){
                      $reverse = $this->reverse($list['id']);
                      $dataq['checkReversal'] =  '0';$this->db->where('id',$list['id']);$this->db->update('transactions',$dataq);
                      $dataR['dailyReversal'] =  ($udetails['dailyReversal'] + 1) ;$this->db->where('accountid',$list['user_id']);$this->db->update('account',$dataR);
                   }
                 
                }
                   
                  
           }
      return true;
   }
} 

public function reverse($id)
{
        $getDetails = $this->db->get_where('transactions',array('id'=>$id))->row_array();
        if($getDetails['status'] == '6')
        {
            return false;
            exit;
        }
        if($getDetails['api'] == '9')
        {
            return true;
            exit;
        }
        $reversedCode = $this->db->get_where('status_code',array('value'=>'reversed'))->row_array();
        $userDetails = get_user_info($getDetails['user_id']);
        $newbal = ($userDetails['wallet_balance'] + $getDetails['charge']);
        $sub = 'Trasaction ID ('.$getDetails['order_id'].') Reversed';
        $body = 'Dear '.$userDetails['fullname'].', <br/><br/> This is to nofity you that transaction with reference ID: '.$getDetails['order_id'].' has just been reversed. See details below <br/><br/>
        Description: '.$getDetails['description'].' <br/> Beneficiary: '.$getDetails['recipient'].' <br/> Transaction Date: '.$getDetails['date_added'].'<br/> Reversed Date: '.date('Y-m-d').'<br/> Previous Balance: NGN '.$userDetails['wallet_balance'].' <br/>New Balance: '.$newbal.' <br/>Transaction Ref: '.$getDetails['order_id'].' <br/><br/>Regards<br/> '.get_settings('app_name').' Team';
     $this->User_model->do_CreditWallet($userDetails['accountid'],$getDetails['charge'],$wallet_type='wallet_balance');
     $data['status'] = $reversedCode['code'];$this->db->where('id',$id);$this->db->update('transactions',$data);
     $this->send_mail($userDetails['email'],$sub,$body);
    return false;    
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
  }else{
     return true;
   
  }
 }
  public function test_curl()
  {
      $ch       = curl_init();
      $country_name = 'ZM';
      $url = 'https://bingpay.ng/api/v1/all-giftcards/'.$country_name;
$headers  = array(
  "Authorization: Bearer 1dd501141dffd9d68f254b241f05871b8f10754c90f4832ad9" // my token
);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt( $ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
$res = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if(curl_error($ch))
{
    echo 'error:' . curl_error($ch); //giving error
}
 curl_close($ch);
 $response = json_decode($res,true);
 foreach($response['data'] as $item){
//$this->User_model->save_giftcards_int($country_name,$item['productId'],$item['productName'],$item['global'],$item['denominationType'],$item['recipientCurrencyCode'],$item['minRecipientDenomination'],$item['maxRecipientDenomination'],$item['senderCurrencyCode'],$item['minSenderDenomination'],$item['maxSenderDenomination'],json_encode($item['fixedRecipientDenominations']),json_encode($item['logoUrls']),json_encode($item['brand']),json_encode($item['country']),json_encode($item['redeemInstruction']));     
 //$this->User_model->save_giftcards($item['id'],$item['name'],$item['image_url'],$item['short_desc'],$item['description'],$item['currency'],$item['fee'],$item['min_range'],$item['max_range']);
}
echo 'all done';
  }
  public function logout($from = "") {
      //destroy sessions of specific userdata. We've done this for not removing the cart session
      $this->session_destroy();
      $this->session->set_flashdata('success_alert',('Logged out successfully'));
     redirect('login');
     
    }
    public function session_destroy() {
      $this->session->unset_userdata('login_id');
      $this->session->unset_userdata('member_session');
      $this->session->unset_userdata('email');
      $this->session->unset_userdata('name');
      $this->session->unset_userdata('bal');
     
    }
public function webhook(){
        $this->config->load('flutterwave');
        
        $local_secret_hash = $this->config->item('secret_hash');
        
        $body = @file_get_contents("php://input");
        
        $response = json_decode($body,1);
        
		/* 
			to store the flutter wave response and server response into the log file, 
			which can be found under 'application/logs/' folder
			Make a note many times codeIgniter cannot directly read the values of '$_SERVER' variable therefore if such problem arises 
			you can add the following line in your root .htaccess file
			
			SetEnvIf Authorization "(.*)" HTTP_AUTHORIZATION=$1 
			
		*/
        log_message('debug', 'Flutter Wave Webhook - Normal Response - JSON DATA --> ' . var_export($response, true));
        log_message('debug', 'Server Variable --> '.var_export($_SERVER,true));
        
		/* Reading the signature sent by flutter wave webhook */
        $signature = (isset($_SERVER['HTTP_VERIF_HASH']))?$_SERVER['HTTP_VERIF_HASH']:'';
        
		/* comparing our local signature with received signature */
        if(empty($signature) || $signature != $local_secret_hash ){
            log_message('error', 'Flutter Wave Webhook - Invalid Signature - JSON DATA --> ' . var_export($response, true));
            log_message('error', 'Server Variable --> '.var_export($_SERVER,true));
            exit();
        }
		
        if(strtolower($response['status']) == 'successful') {
            // TIP: you may still verify the transaction
            // before giving value.
            $response = $this->flutterwave->verify_transaction($response['txRef']);
            
            $response = json_decode($response,1);
            
            if(!empty($response) && isset($response['data']['status']) && strtolower($response['data']['status']) == 'successful' 
                && isset($response['data']['chargecode']) && ( $response['data']['chargecode'] == '00' || $response['data']['chargecode'] == '0')
            ){
                
                $payer_email = $response['data']['custemail'];
                $paymentplan = $response['data']['paymentplan'];
                
                /* 
					Perform Database Operations here 
					Add your custom code here for any other operation like 
					selling good / inserting / update transaction record in database / anything else
				*/
                
            }else{
                /* Transaction failed */
                log_message('error', 'Flutter Wave Webhook - Inner Verification Failed --> ' . var_export($response, true));
                log_message('error', 'Server Variable -->  '.var_export($_SERVER,true));
            }
            
        }else{
            /* Transaction failed */
            log_message('error', 'Flutter Wave Webhook - Outter Verification Failed --> ' . var_export($response, true));
            log_message('error', 'Server Variable -->  '.var_export($_SERVER,true));
        }
        
    }


public function admin_logout($from = "") {
      //destroy sessions of specific userdata. We've done this for not removing the cart session
      $this->admin_session_destroy();
    redirect(site_url('Admin/index'), 'refresh');
    }
    public function admin_session_destroy() {
      $this->session->unset_userdata('login_id');
      $this->session->unset_userdata('email');
      $this->session->unset_userdata('name');
      $this->session->unset_userdata('bal');
      if ($this->session->userdata('admin_session') == 1) {
        $this->session->unset_userdata('admin_session');
      }
    }
}