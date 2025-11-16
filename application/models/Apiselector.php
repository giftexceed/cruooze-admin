<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apiselector extends CI_Model {

    function __construct()
    {
        parent::__construct();
        /*cache control*/
      //  $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
       // $this->output->set_header('Pragma: no-cache');
    }
    function do_verifyCableVTPassAPI($getApi,$subModule,$iucnumber)
    {
     //   $getApi['api_key'],$getApi['endpoint']
     $curl = curl_init();
      $requestId = $this->generateRequestID();
      $requestData = array(
        "serviceID" => $subModule['api_code'],
        "billersCode" => $iucnumber
    );
    $api_key = $getApi['api_key'];
    $sk_key = $getApi['user_id'];
    curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api-service.vtpass.com/api/merchant-verify',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($requestData),
  CURLOPT_HTTPHEADER => array(
  "api-key: ".$api_key,
    "secret-key: ".$sk_key,
    "Content-Type: application/json"
  ),
));
$response = curl_exec($curl);
curl_close($curl);
return $response;
    }
   function do_verifyMeterMSorg($getApi,$meternumber,$subModule)
{
    $curl = curl_init();
    $providercode = urlencode($subModule['api_code']);
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://alrahuzdata.com.ng/api/validatemeter?meternumber='.$meternumber.'&disconame='.$providercode.'&mtype=prepaid',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
curl_close($curl);
return $response;
} 
    public function doVerifyMeter($provider,$meternumber,$subModule)
    {
        $result = '';
      $getApi = get_api_provider('16');
   /*
   
    if($getApi['apiType'] == '1'){
        $result = $this->do_verifyMeterMSorg($getApi,$meternumber,$subModule);
    }
    if($getApi['apiType'] == '2'){
        //$result = $this->do_verifyMeterBasicAuth($getApi,$subModule,$meternumber);
    }
    */
    
    // $result = $this->do_verifyMeterMSorg($getApi,$meternumber,$subModule);
     $api_key = $getApi['api_key'];
    $sk_key = $getApi['user_id'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://vtpass.com/api/merchant-verify',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('request_id' => $this->generateRequestID(),'serviceID' => $subModule['api_code'],'billersCode' => $meternumber),
          CURLOPT_HTTPHEADER => array(
            'api-key: '.$api_key,
            'secret-key: '.$sk_key
          ),
        ));

    $result = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($result,true);
        if(isset($response['content']['Customer_Name'])){
        return $response['content']['Customer_Name'];
        }
        if(isset($response['name'])){
            return $response['name'];//'Unable to validate this IUC';
        }else{
            return json_encode($result);
        }
    }
    
   public function verifyTV($provider,$iucnumber,$subModule)
    {
        $result = '';
       $getApi = get_api_provider('16');
    //if($getApi['apiType'] == '1'){
     //   $result = $this->do_verifyTVMSorg($getApi,$subModule,$iucnumber);
      //  $result = $this->do_verifyCableBasicAuth($getApi,$subModule,$iucnumber);
   // }
   /*
    if($getApi['apiType'] == '2'){
        $result = $this->do_verifyCableBasicAuth($getApi,$subModule,$iucnumber);
    }
    */
    // $result = $this->do_verifyTVMSorg($getApi,$subModule,$iucnumber);
    $api_key = $getApi['api_key'];
    $sk_key = $getApi['user_id'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://vtpass.com/api/merchant-verify',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('request_id' => $this->generateRequestID(),'serviceID' => $subModule['api_code'],'billersCode' => $iucnumber),
          CURLOPT_HTTPHEADER => array(
            'api-key: '.$api_key,
            'secret-key: '.$sk_key
          ),
        ));

    $result = curl_exec($curl);
    curl_close($curl);
    $response = json_decode($result,true);
        if(isset($response['content']['Customer_Name'])){
        return $response['content']['Customer_Name'];
        }
        if(isset($response['name'])){
            return $response['name'];//'Unable to validate this IUC';
        }else{
            return json_encode($result);
        }
    }
    public function do_dailyCashAPI($apiKey,$userid,$endpoint,$itemRow)
{
     $getApi = get_api_provider('13');
     $subModule = $this->User_model->sub_module_single($itemRow['sub_module_id']);
    
   if($itemRow['module_id']=='1'){
        $postData = 'https://dailycashout.com.ng/api/buyvtu.php?network='.$subModule['api_code'].'&api_key='.$getApi['api_key'].'&amount='.$itemRow['amount'].'&phonenumber='.$itemRow['recipient'];
      }elseif($itemRow['module_id']=='2'){
         $postData = 'https://dailycashout.com.ng/api/buydatav3.php?network='.$subModule['api_code'].'&api_key='.$getApi['api_key'].'&plans='.$itemRow['plan_id'].'&phonenumber='.$itemRow['recipient']; 
      }elseif($itemRow['module_id']=='3'){
         $postData = $endpoint.'.service='.$subModule['api_code'].'&api_key='.$getApi['api_key'].'&package='.$itemRow['plan_id'].'&number='.$itemRow['recipient']; 
      }elseif($itemRow['module_id']=='8'){
         // return true;
         $postData = 'https://dailycashout.com.ng/api/buyepin.php?api_key='.$getApi['api_key'].'&examtype='.$itemRow['plan_id'];
         //$postData = $endpoint.'.service='.$subModule['api_code'].'&api_key='.$getApi['api_key'].'&package='.$itemRow['plan_id'].'&number='.$itemRow['recipient']; 
      }
      
$curl = curl_init();
//$getApi['api_key'];
curl_setopt_array($curl, array(
  CURLOPT_URL => $postData,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: PHPSESSID=4ea42c5768234c2a13fc3be2d20cbb70'
  ),
));
$response = curl_exec($curl);
curl_close($curl);
$result = json_decode($response,true);
return $response;
}
   public function send_transaction($id,$isadmin='')
  {
    $details = $this->User_model->get_transaction_id($id); 
    if($details->num_rows() < 0)
    {
        $response['msg']="Invalid transaction Id";
        return $response;
    
    }
    $itemRow = $details->row_array();
    $getApi = get_api_provider($itemRow['api']);
    
    if($isadmin ==''){
        
        if($itemRow['completed'] == '1')
        {
            $response['msg']="This transaction has been completed";
            return $response; 
        }
    }
    
    
    
     if(strpos($getApi['endpoint'], 'dailycashout') !== false){
        $result = $this->do_dailyCashAPI($getApi['api_key'],$getApi['user_id'],$getApi['endpoint'],$itemRow);
         $response = json_decode($result,true);
        $this->updateApiResponse($id,$response);
        return $response;
        
    }
    if($itemRow['api'] == '9'){
        $result = $this->ManualOrder($itemRow);
         $response = json_decode($result,true);
        $this->updateApiResponse($id,$response);
        return $response;
    }
   
     if(strpos($getApi['endpoint'], 'nellobytesystems') !== false){
         $result = $this->do_clubkonnect($getApi['api_key'],$getApi['endpoint'],$itemRow);
          $response = json_decode($result,true);
        $this->updateApiResponse($id,$response);
        return $response;
    }
    if(strpos($getApi['endpoint'], 'smartsmssolutions') !== false){
     $result = $this->do_bulksms($getApi['api_key'],$getApi['endpoint'],$itemRow);
      $response = json_decode($result,true);
        $this->updateApiResponse($id,$response);
        return $response;
    }
    
    if(strpos($getApi['endpoint'], 'vtpass') !== false){
     $result = $this->do_vtpass($getApi['api_key'],$getApi['endpoint'],$itemRow);
      $response = json_decode($result,true);
        $this->updateApiResponse($id,$response);
        return $response;
    }
     if(strpos($getApi['endpoint'], 'ogdams') !== false){
     $result = $this->do_ogdams($getApi['api_key'],$getApi['endpoint'],$itemRow);
      $response = json_decode($result,true);
        $this->updateApiResponse($id,$response);
        return $response;
    }
    if(strpos($getApi['endpoint'], 'autopilotng') !== false){
     $result = $this->autopilotAPI($getApi['api_key'],$getApi['endpoint'],$itemRow);
      $response = json_decode($result,true);
        $this->updateApiResponse($id,$response);
        return $response;
    }
    if(strpos($getApi['endpoint'], 'irecharge') !== false){
     $result = $this->iRechargeAPI($itemRow);
     $response = json_decode($result,true);
    $this->updateApiResponse($id,$response);
        return $response;
    }
   // $result = $this->do_msOrgAPI($getApi['api_key'],$getApi['endpoint'],$itemRow);
    if($getApi['apiType'] == '2'){
        $result = $this->do_basicAuth($getApi['api_key'],$getApi['user_id'],$getApi['endpoint'],$itemRow);
         $response = json_decode($result,true);
        $this->updateApiResponse($id,$response);
        return $response;
        
    }if($getApi['apiType'] == '3'){
        $result = $this->do_TopupkonnectAPI($getApi['api_key'],$getApi['endpoint'],$itemRow);
        $response = json_decode($result,true);
        $this->updateApiResponse($id,$response);
        return $response;
    }
    if($getApi['apiType'] == '1'){
        $result = $this->do_msOrgAPI($getApi['api_key'],$getApi['endpoint'],$itemRow);
         $response = json_decode($result,true);
        $this->updateApiResponse($id,$response);
        return $response;
        
    }
        
    
}
function convertRef($id)
{
    $timezone = date_default_timezone_set('Africa/Lagos');
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $hour = date('H');
    $minute = date('i');
    $seconds = date('s');
    $currentDate = $year.$month.$day.$hour.$minute.$seconds;
    $randomString = substr(md5(uniqid(rand(), true)), 0, 14);
    $referenceString = $currentDate.$randomString;
    $data['autopilotref'] = $referenceString;
    $this->db->where('id',$id);
    $this->db->update('transactions',$data);
    return $referenceString;
}
function get_datType($type,$network)
{
    if(($network == '1') && ($type == '3'))
    {
       // return 'SME';
       return 'DATA TRANSFER';
    }
    if(($network == '1') && ($type == '4'))
    {
        return 'DATA TRANSFER';
        //return 'CORPORATE GIFTING';
    }
    if(($network == '1') && ($type == '27'))
    {
        return 'DATA TRANSFER';
    }
    if(($network == '3') && ($type == '13'))
    {
        return 'CORPORATE GIFTING'; 
    }
    if(($network == '3') && ($type == '11'))
    {
        return 'AWOOF GIFTING'; 
    }
    
    if(($network == '2') && ($type == '7'))
    {
        return 'CORPORATE GIFTING'; 
    }
}
function autopilotAPI($getApi,$host,$itemRow)
    {
        $getReference = $this->convertRef($itemRow['id']);
        $subModule = $this->User_model->sub_module_single($itemRow['sub_module_id']);
    if($itemRow['module_id']=='1'){
        $postData = '{"networkId": "'.$subModule['api_code'].'","amount": "'.$itemRow['amount'].'","phone": "'.$itemRow['recipient'].'","reference" : "'.$getReference.'","airtimeType": "'.$subModule['name'].'"}';
      }
      
      if($itemRow['module_id']=='2'){
     $verifyplanID = $this->User_model->verifyPlanID($itemRow['plan_id'])->row_array();
     $dataType = $this->get_datType($subModule['id'],$subModule['api_code']);
      $postData = '{"networkId": "'.$subModule['api_code'].'","phone": "'.$itemRow['recipient'].'","dataType":"'.$dataType.'","reference" : "'.$getReference.'","planId": "'.$itemRow['plan_id'].'"}';
      }
      if($itemRow['module_id']=='3'){
         $verifyplanID = $this->User_model->getTVPlanbyID($itemRow['plan_id'])->row_array();
          $postData = '{"cablename": "'.$subModule['api_code'].'","smart_card_number": "'.$itemRow['recipient'].'","cableplan":"'.$verifyplanID['provider_code'].'"}';
      }
      if($itemRow['module_id']=='4'){
         $verifyplanID = $this->User_model->getTVPlanbyID($itemRow['plan_id'])->row_array();
          $postData = '{"disco_name": "'.$subModule['api_code'].'","meter_number": "'.$itemRow['recipient'].'","amount":"'.$itemRow['plan_id'].'","MeterType":"'.$verifyplanID['provider_code'].'"}';
      }
    $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $host,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$postData,
             CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer $getApi"
            ),
            ));
            $response = curl_exec($curl);
            return $response;
   }
public function do_ogdams($api_key,$endpoint,$itemRow)
{
    $subModule = $this->User_model->sub_module_single($itemRow['sub_module_id']);
     $verifyplanID = $this->User_model->verifyPlanID($itemRow['plan_id'])->row_array();
      $postData = '{"networkId": "'.$subModule['api_code'].'","phoneNumber": "'.$itemRow['recipient'].'","Ported_number":true,"reference" : "'.$itemRow['order_id'].'","planId": "'.$itemRow['plan_id'].'"}';
    $curl = curl_init();
  curl_setopt_array($curl, array(
  CURLOPT_URL => $endpoint,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>$postData,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$api_key,
    'Content-Type: application/json',
    'Accept: application/json'
  ),
));
$response = curl_exec($curl);
curl_close($curl);
return $response;
}
function do_bulksms($apikey,$endpoint,$itemRow)
{
$curl = curl_init();
 curl_setopt_array($curl, array(
  CURLOPT_URL => $endpoint,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('token' => $apikey,'sender' => $itemRow['network'],'to' => $itemRow['recipient'],'message' => $itemRow['description'],'type' => '0','routing' => '3','ref_id' => $itemRow['order_id ']),
));
$response = curl_exec($curl);
return $response;
}
function convertElectricityCode($uservice,$metertype)
{
    if($uservice == "IKEDC" && $metertype == "01"){
       return "Ikeja_Token_Purchase";
    }
    if($uservice == "IKEDC" && $metertype == "02"){
        return "Ikeja_Electric_Bill_Payment";
    }
    if($uservice == "EKEDC" && $metertype == "01"){
        return "Eko_Prepaid";
    }
    if($uservice == "EKEDC" && $metertype == "02"){
        return "Eko_Postpaid";
    }
     if($uservice == "AEDC" && $metertype == "01"){
        return "AEDC";
    }
    if($uservice == "AEDC" && $metertype == "02"){
        return "AEDC_Postpaid";
    }
    if($uservice == "KEDC" && $metertype == "01"){
        return "Kano_Electricity_Disco";
    }
    if($uservice == "KEDC" && $metertype == "02"){
        return "Kano_Electricity_Disco_Postpaid";
    }
     if($uservice == "JEDC" && $metertype == "01"){
        return "Jos_Disco";
    }
    if($uservice == "JEDC" && $metertype == "02"){
        return "Jos_Disco_Postpaid";
    }
    if($uservice == "IBEDC" && $metertype == "01"){
       return "Ibadan_Disco_Prepaid";
    }
    if($uservice == "IBEDC" && $metertype == "02"){
        return "Ibadan_Disco_Postpaid";
    }
     if($uservice == "KAEDC" && $metertype == "01"){
        return "Kaduna_Electricity_Disco";
    }
    if($uservice == "KAEDC" && $metertype == "02"){
        return "Kaduna_Electricity_Disco_Postpaid";
    }
    if($uservice == "EEDC" && $metertype == "01"){
        return "Enugu_Electricity_Distribution_Prepaid";
    }
    if($uservice == "EEDC" && $metertype == "02"){
        return "Enugu_Electricity_Distribution_Postpaid";
    }
     if($uservice == "PhED" && $metertype == "01"){
        return "PhED_Electricity";
    }
    if($uservice == "PhED" && $metertype == "02"){
        return "PH_Disco";
    }
    return $uservice;
}
public function uniqueid()
  {
    $un = substr(number_format(time() * rand(),0,'',''),0,12);
    return $un;
  }

public function verifyMeter($provider,$meter_no,$subModule)
    {
    $result = '';
    $this->load->library('session');
    $getpCode = $this->db->get_where('electricity', array('id'=>$provider))->row_array();
    $disco = $this->convertElectricityCode($getpCode['ringo_code'],$meterTyp="01");
     $details = get_api_provider($subModule['api']);
     $reference = $this->uniqueid();
   $hash_string_1 = $details['user_id']."|".$reference."|".$meter_no."|".$disco."|".$details['api_key'];
    $hash_string_2 = hash_hmac("sha1", $hash_string_1, $details['password']);
    //https://irecharge.com.ng/pwr_api_sandbox/v2/get_meter_info.php?vendor_code=YOUR_VENDOR_CODE&reference_id=UNIQUE_REFERENCE_ID&meter=CUSTOMER_METER_NUMBER&disco=AEDC&response_format=json&hash=GENERATED_HASH
    $url = $details['endpoint'].'/pwr_api_live/v2/get_meter_info.php?vendor_code='.$details['user_id'].'&reference_id='.$reference.'&meter='.$meter_no.'&disco='.$disco.'&response_format=json&hash='.$hash_string_2;
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($response,true);
    if(isset($result['customer']['name'])){
    $this->saveAccessToken($meter_no,$result['access_token'],$result['customer']['name']);
     return $result['customer']['name'];
     //return json_encode($result);
    }else{
        return json_encode($result);
    }
}
public function saveAccessToken($meter_no,$access_token,$customer)
{
     $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();$user_id = $udetails['accountid'];
    $data['user_id'] = $user_id;
    $data['access_token'] = $access_token;
    $data['meterNumber'] = $meter_no;
    $data['customerName'] = $customer;
    $this->db->insert('meterTokens',$data);
    return true;
}
public function getAccessTOken($meterNumber)
{
     $this->db->where('meterNumber',$meterNumber);
     $this->db->order_by('id','Desc');
    $this->db->limit('1');
    return $this->db->get('meterTokens');
}

public function iRechargeAPI($itemRow)
{
$this->load->library('session');
$details = get_api_provider($itemRow['api']);   
$getpCode = $this->db->get_where('electricity', array('id'=>$itemRow['network']))->row_array();
$disco = $this->convertElectricityCode($getpCode['ringo_code'],$meterTyp="01");
$get_AccessToken = $this->getAccessTOken($itemRow['recipient'])->row_array();
$udetails = get_user_info($itemRow['user_id']);
$accessToken = $get_AccessToken['access_token'];
$hash_string_1 = $details['user_id']."|".$itemRow['order_id']."|".$itemRow['recipient']."|".$disco."|".$itemRow['plan_id']."|".$accessToken."|".$details['api_key'];
$hash_string_2 = hash_hmac("sha1", $hash_string_1, $details['password']);
$url = $details['endpoint'].'/pwr_api_live/v2/vend_power.php?vendor_code='.$details['user_id'].'&reference_id='.$itemRow['order_id'].'&meter='.$itemRow['recipient'].'&access_token='.$accessToken.'&disco='.$disco.'&phone='.$udetails['phone'].'&email='.$udetails['email'].'&response_format=json&hash='.$hash_string_2.'&amount='.$itemRow['plan_id'];
$url_2 = '';
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));
$response = curl_exec($curl);
curl_close($curl);
$result = json_decode($response,true);
return $response;
}
public function sendNotificationMail($id)
{
       $to  = 'aslamfastsub@gmail.com';//get_settings('super_email');
      $sub = 'Pending Order with reference ID '.$id;
      $message = 'Hello Admin, <br/><br/> You have a pending manual order with reference ID '.$id.' waiting for your attention<br/><br/>Please login to your admin and complete the transaction.';
      if($this->send_mail($to,$sub,$message)){
        return true;
      }else{
       return false;
      }
}
public function ManualOrder($details)
{
      $this->sendNotificationMail($details['order_id']);
      $data['status'] = 'success';
      $data['completed'] = '1';
    //  $data['api_response'] = json_encode($apiResponse);
    //  $this->db->where('id',$id);
     $result = json_encode($data);
     return $result;
        
}
public function updateApiResponse($id,$apiResponse)
{
    $details = $this->User_model->get_transaction_id($id)->row_array(); 
     $userinfo = get_user_info($details['user_id']);
     
      if((isset($apiResponse['content']['transactions']['status'])) && (!empty($apiResponse['content']['transactions']['status'])))
     {
         if(($apiResponse['content']['transactions']['status'] == 'delivered') || ($apiResponse['content']['transactions']['status'] == 'success'))
         {
              $data['status'] = '1';
         }else{
             $data['status'] = '5';
         }
     }
     
     if((isset($apiResponse['message']))&&(!empty($apiResponse['message']))){
     $data['message'] = $apiResponse['message'];
     }
     
     if((isset($apiResponse['api_response']))&&(!empty($apiResponse['api_response']))){
     $data['message'] = $apiResponse['api_response'];
     }
     
     if((isset($apiResponse['status']))&&(!empty($apiResponse['status']))){
        if($apiResponse['status'] =='success'){
     $data['status'] = '1';
         }
         if(($apiResponse['status'] =='failed')||($apiResponse['status'] == 'fail')){
     $data['status'] = '5';
         }
     }
     if((isset($apiResponse['Status']))&&(!empty($apiResponse['Status']))){
        if($apiResponse['Status'] =='successful'){
     $data['status'] = '1';
         }
         if(($apiResponse['Status'] =='failed')||($apiResponse['Status'] == 'fail')){
     $data['status'] = '5';
         }
     }
         if(isset($apiResponse['error'])){
         $data['status'] = '5';
             }
     if((isset($apiResponse['meter_token']))&&(!empty($apiResponse['meter_token']))){
     $data['meter_token'] = $apiResponse['meter_token'];
     }
     
     if((isset($apiResponse['purchased_code']))&&(!empty($apiResponse['purchased_code']))){
     $data['meter_token'] = $apiResponse['purchased_code'];
     }
     
     if((isset($apiResponse['pin']))&&(!empty($apiResponse['pin']))){
     $data['meter_token'] = $apiResponse['pin'];
     }
     
     if((isset($apiResponse['code']))&&(!empty($apiResponse['code']))){
        if($apiResponse['code'] =='1000'){
     $data['status'] = '1';
         }
     }
     
      if((isset($apiResponse['code']))&&(!empty($apiResponse['code']))){
        if($apiResponse['code'] ==200){
     $data['status'] = '1';
         }
      }
      
      if($details['api'] == '9'){
         $data['status'] = '0';
     }
     if((isset($apiResponsed['data']['msg']))&&(!empty($apiResponse['data']['msg']))){
     $data['message'] = $apiResponse['data']['msg'];
     }
     $data['completed'] = '1';
    //  $data['api_response'] = $apiResponse;
     $data['api_response'] = json_encode($apiResponse);
     $this->db->where('id',$id);
     $this->db->update('transactions',$data);
     return true;
}
function generateRequestID() {
    $timezone = date_default_timezone_set('Africa/Lagos');
    $year = date('Y');
    $month = date('m');
    $day = date('d');
    $currentDate = $year.$month.$day;
    $randomString = substr(md5(uniqid(rand(), true)), 0, 12);
    return $currentDate . $randomString;
}

function do_vtpass($getApi,$host,$itemRow){
    $getApi = get_api_provider($itemRow['api']);
    $submoduleDetails = $this->db->get_where('sub_module', array('id' => $itemRow['sub_module_id']))->row_array();
    $network = $submoduleDetails['api_code'];
     $curl = curl_init();
      $requestId = $this->generateRequestID();
      
    if($itemRow['module_id'] == '4')
    {
             $requestData = array(
            "request_id" => $requestId,
            "serviceID" => $network,
            "amount" => $itemRow['plan_id'],
            "phone" => $itemRow['recipient'],
            "billersCode" => $itemRow['recipient'],
            "variation_code" => "prepaid"
        );
    }elseif($itemRow['module_id'] =='3')
     {
             $requestData = array(
            "request_id" => $requestId,
            "serviceID" => $network,
            "amount" => $itemRow['amount'],
            "phone" => $itemRow['recipient'],
            "billersCode" => $itemRow['recipient'],
            "variation_code" => $itemRow['amount']
        );
    }else{
        $requestData = array(
        "request_id" => $requestId,
        "serviceID" => $network,
        "amount" => $itemRow['amount'],
        "phone" => $itemRow['recipient']
    );
   
    }
    
    curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api-service.vtpass.com/api/pay',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($requestData),
  CURLOPT_HTTPHEADER => array(
  "api-key: ".$getApi['api_key'],
    "secret-key: ".$getApi['user_id'],
    "Content-Type: application/json"
  ),
));
$response = curl_exec($curl);
curl_close($curl);
return $response;
}
function do_clubkonnect($getApi,$host,$itemRow)
{
$getApi = get_api_provider($itemRow['api']);
$request = "";
$param["UserID"] = $getApi['user_id'];
$param["APIKey"] = $getApi['api_key'];
$param["MobileNetwork"] = $itemRow['network'];
$param["Amount"] = $itemRow['amount'];
$param["MobileNumber"] = $itemRow['recipient'];
$param["CallBackURL"] = "";
foreach($param as $key=>$val)
{
$request .= $key . "=" . urlencode($val);
$request .= '&';
}
$len = strlen($request) - 1;
$request = substr($request, 0, $len); 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$host$request");
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
$response = curl_exec($ch);
curl_close($ch);
return $response;
}
function do_TopupkonnectAPI($getApi,$host,$itemRow)
{
    $subModule = $this->User_model->sub_module_single($itemRow['sub_module_id']);
    if($itemRow['module_id']=='1'){
         $postData = '{"network": "'.$subModule['api_code'].'","amount": "'.$itemRow['amount'].'","phone": "'.$itemRow['recipient'].'"}';
     }elseif($itemRow['module_id']=='2'){
        $postData = '{"network": "'.$subModule['api_code'].'","plan_id": "'.$itemRow['amount'].'","phone": "'.$itemRow['recipient'].'"}';
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
  CURLOPT_URL => $host,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $postData,
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$getApi,
    'Content-Type: application/json',
  ),
));
$response = curl_exec($curl);
curl_close($curl);
return $response;
}
function generateTokenBasicAuth($getApi,$host)
{
    $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $host);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt(
                   $ch, CURLOPT_HTTPHEADER, [
                        "Authorization: Basic ".$getApi,
                    ]
                );
                $json = curl_exec($ch);
                curl_close($ch); 
                $response = json_decode($json,true);
                return $response['AccessToken'];
}
function  do_verifyCableBasicAuth($getApi,$subModule,$iucnumber)
{
     if($subModule['api_code'] == 'gotv'){$tvname = '1';}elseif($subModule['api_code'] == 'dstv'){$tvname = '2';}else{$tvname='3';};
   $curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://bilalsadasub.com/api/cable/cable-validation?iuc='.$iucnumber.'&cable='.$tvname,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));
$response = curl_exec($curl);
curl_close($curl);
return $response;
}
function  do_basicAuth($getApi,$authUrl,$host,$itemRow)
{
    $accessKey = $this->generateTokenBasicAuth($getApi,$authUrl);
    $subModule = $this->User_model->sub_module_single($itemRow['sub_module_id']);
    if($itemRow['module_id']=='1'){
    $paypload = array('network' => $subModule['api_code'],'phone' => $itemRow['recipient'],'amount' => $itemRow['amount'],'plan_type' => $subModule['name'],'bypass' => true,'request-id' => $itemRow['order_id']);
    }
    if($itemRow['module_id']=='2'){
    $paypload = array('network' => $subModule['api_code'],'phone' => $itemRow['recipient'],'data_plan' => $itemRow['plan_id'],'bypass' => true,'request-id' => $itemRow['order_id']);
   }
   if($itemRow['module_id']=='3'){
       $verifyplanID = $this->User_model->getTVPlanbyID($itemRow['plan_id'])->row_array();
    $paypload = array('cable' => $subModule['api_code'],'iuc' => $itemRow['recipient'],'cable_plan' => $verifyplanID['provider_code'],'bypass' => true,'request-id' => $itemRow['order_id']);
   }
   $ch = curl_init();
                     curl_setopt($ch, CURLOPT_URL, $host);
                     curl_setopt($ch, CURLOPT_POST, 1);
                     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paypload));
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                     $headers = [
                          "Authorization: Token ".$accessKey,
                         'Content-Type: application/json'
                     ];
                     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                     $result = curl_exec($ch);
                     curl_close($ch);
                     $response = json_decode($result,true);
                    return $result;
}
function do_verifyTVMSorg($getApi,$subModule,$iucnumber)
{
    $curl = curl_init();
    if($subModule['api_code'] == '1'){$tvname = 'GOTV';}elseif($subModule['api_code'] == '2'){$tvname = 'DSTV';}else{$tvname='STARTIMES';};
    curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://alrahuzdata.com.ng/api/validateiuc?smart_card_number='.$iucnumber.'&cablename='.$tvname,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  
));
$response = curl_exec($curl);
curl_close($curl);
return $response;
}
function do_msOrgAPI($getApi,$host,$itemRow)
{
     $subModule = $this->User_model->sub_module_single($itemRow['sub_module_id']);
    if($itemRow['module_id']=='1'){
        $postData = '{"network": "'.$subModule['api_code'].'","amount": "'.$itemRow['amount'].'","mobile_number": "'.$itemRow['recipient'].'","Ported_number":"true","request-id" : "'.$itemRow['order_id'].'","airtime_type": "'.$subModule['name'].'"}';
      }
      if($itemRow['module_id']=='2'){
     $verifyplanID = $this->User_model->verifyPlanID($itemRow['plan_id'])->row_array();
      $postData = '{"network": "'.$subModule['api_code'].'","mobile_number": "'.$itemRow['recipient'].'","Ported_number":true,"request-id" : "'.$itemRow['order_id'].'","plan": "'.$itemRow['plan_id'].'"}';
      }
      if($itemRow['module_id']=='3'){
         $verifyplanID = $this->User_model->getTVPlanbyID($itemRow['plan_id'])->row_array();
          $postData = '{"cablename": "'.$subModule['api_code'].'","smart_card_number": "'.$itemRow['recipient'].'","cableplan":"'.$verifyplanID['provider_code'].'"}';
      }
      if($itemRow['module_id']=='4'){
         $verifyplanID = $this->User_model->getTVPlanbyID($itemRow['plan_id'])->row_array();
          $postData = '{"disco_name": "'.$subModule['api_code'].'","meter_number": "'.$itemRow['recipient'].'","amount":"'.$itemRow['plan_id'].'","MeterType":"'.$verifyplanID['provider_code'].'"}';
      }
    $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $host,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$postData,
             CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Token $getApi"
            ),
            ));
            $response = curl_exec($curl);
            return $response;
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
}