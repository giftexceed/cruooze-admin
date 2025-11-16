<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function get_settings($key = '') {
       $CI	=&	get_instance();
        $CI->load->database();
        $CI->db->where('key', $key);
        $result = $CI->db->get('settings')->row()->value;
        return $result;
    }
    function removeSpaces($string)
{
  //  $string = preg_replace('/\s+/', '', $string);
    return  preg_replace('/\s+/', '', $string);
}

function getadsactivities($id='')
{
    
     $CI	=&	get_instance();
    $CI->load->database();
    if($id !='')
    {
        $CI->db->where('id', $id);
        $result = $CI->db->get('adsactivities');
    return $result->row_array();
    }else{
      $result = $CI->db->get('adsactivities');
    return $result;  
    }
    
}

function cleanString($string)
{
    $cleaned_string = preg_replace('/\[.*?\]|\(.*?\)/', '', $string);
    return $cleaned_string;
}
function getAdstype($id='')
{
    
     $CI	=&	get_instance();
    $CI->load->database();
    if($id !='')
    {
        $CI->db->where('id', $id);
        $result = $CI->db->get('adstype');
    return $result->row_array();
    }else{
      $result = $CI->db->get('adstype');
    return $result;  
    }
    
}
    function gethistoryAirtimeData($id)
{
    $CI	=&	get_instance();
    $CI->load->database();
    $CI->db->group_start();
    $CI->db->where('module_id', '1');  
    $CI->db->or_where('module_id', '2'); 
    $CI->db->group_end();
    $CI->db->where('user_id', $id);  
     $CI->db->order_by('id','desc');
   //  $CI->db->limit('50');
    $result = $CI->db->get('transactions');
    return $result;
}
function get_ModulebySubmodule($id)
{
   $CI	=&	get_instance();
    $CI->load->database();
     $CI->db->where('id', $id);  
    $result = $CI->db->get('module');
   
    return $result; 
}
function dateFormatChange($date){
    $date = str_replace('/', '-', $date);
    $currentDate = date('Y-m-d', strtotime($date));
    return $currentDate;
}
function getLastLogin($userid)
{
    
     $CI =&	get_instance();
    $CI->load->database();
    $CI->db->where('user_id',$userid);
    $CI->db->order_by('id','desc');
    $CI->db->limit('1');
    $result =  $CI->db->get('daily_login');
    return $result->row_array();
}
function gethistoryCableElectricity($id)
{
    $CI	=&	get_instance();
    $CI->load->database();
    $CI->db->group_start();
    $CI->db->where('module_id', '3');  
    $CI->db->or_where('module_id', '4');  
    $CI->db->group_end();
    $CI->db->where('user_id', $id);  
     $CI->db->order_by('id','desc');
    //  $CI->db->limit('50');
    $result = $CI->db->get('transactions');
    return $result;
}
function gethistoryExamPIN($id)
{
    $CI	=&	get_instance();
    $CI->load->database();
    $CI->db->group_start();
    $CI->db->where('module_id', '8');  
    $CI->db->or_where('module_id', '9'); 
    $CI->db->group_end();
    $CI->db->where('user_id', $id);  
     $CI->db->order_by('id','desc');
      //$CI->db->limit('50');
    $result = $CI->db->get('transactions');
    return $result;
}
function gethistoryOthers($id)
{
    $CI	=&	get_instance();
    $CI->load->database();
    $CI->db->group_start();
    $CI->db->where('module_id !=', '8');  
    $CI->db->where('module_id !=', '9');  
    $CI->db->where('module_id !=', '1');  
    $CI->db->where('module_id !=', '2');  
    $CI->db->where('module_id !=', '3');
    $CI->db->where('module_id !=', '4'); 
    $CI->db->group_end();
    $CI->db->where('user_id', $id);  
     $CI->db->order_by('id','desc');
     // $CI->db->limit('50');
    $result = $CI->db->get('transactions');
    return $result;
}
function fetch_history_wallet($id)
{
    $CI	=&	get_instance();
    $CI->load->database();
    $CI->db->where('user_id', $id);  
     $CI->db->order_by('date_added','desc');
      $CI->db->limit('50');
    $result = $CI->db->get('deposits');
    return $result;
}
function searchTransaction($order_id,$id)
{
    $CI	=&	get_instance();
    $CI->load->database();
    $CI->db->where('order_id', $order_id);  
    $CI->db->or_where('recipient', $order_id);  
    $CI->db->order_by('id','desc');
    $result = $CI->db->get('transactions');
    return $result;
}
function fetch_history($id,$type='')
{
    $CI	=&	get_instance();
    $CI->load->database();
    if($type !=''){
     $CI->db->where('module_id', $type);  
    }
    $CI->db->where('user_id', $id);  
     $CI->db->order_by('id','desc');
    $result = $CI->db->get('transactions');
    return $result;
}
function removeDuplicate($myArray) {
    $array = is_array($myArray);
    $myArray = ($array) ? $myArray: explode(",",$myArray);
    $myArray = array_flip(array_flip(array_reverse($myArray,true)));
    return ($array) ? $myArray : implode(',',$myArray);
} 

function countRecipient($receiver) {
    $searchKeys = array("\r\n","<br>","\n","\r",";"," ");
    $replaceKeys = array(",",",",",",",");
    $receiver = str_replace( $searchKeys , $replaceKeys , $receiver);
    $receiver = preg_replace("/[^0-9+,]/", "", $receiver );
    $receiver = rtrim($receiver,',');
    if(empty($receiver)) return 0;
    $receiver = explode(',',$receiver); 
    $receiver = removeDuplicate($receiver);
    return count($receiver);
}
function countCharacters($text) {
  $text_Len = strlen($text);
  if (strlen($text) != strlen(utf8_decode($text))){
    $test_Len = strlen(utf8_decode($text));
  }
  $text_Len = strlen($text);
  $lines = substr_count( $text, "\n" );
  $messageLength = $text_Len - $lines;
return $messageLength;  
}
function countPage($message) {
  $lenght = strlen($message);
  
    if($lenght <= 160) {  $page = 1;
    } elseif($lenght > 160 && $lenght <= 306) { $page = 2;
    } elseif($lenght > 306 && $lenght <= 459) { $page = 3;
    } elseif($lenght > 459 && $lenght <= 612) { $page = 4;
    } elseif($lenght > 612 && $lenght <= 765) { $page = 5;
    } elseif($lenght > 765 && $lenght <= 918) { $page = 6;  
    } else{ $page = 7; }
  //}
  
  return $page;
}
    function countViews($id)
{
    $CI	=&	get_instance();
    $CI->load->database();
    $CI->db->where('p_id', $id);  
    $result = $CI->db->get('productViews');
    return $result->num_rows();
}
function user_current_package($id)
{
    $CI	=&	get_instance();
    $CI->load->database();
    $CI->db->where('id', $id);  
    $result = $CI->db->get('package')->row_array();
    return $result['package_title'];
}
function generator($lenth)
	{
	    $con = "";
		$number=array("A","B","C","D","E","F","G","H","I","J","K","L","N","M","O","P","Q","R","S","U","V","T","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0");
	
		for($i=0; $i<$lenth; $i++)
		{
			$rand_value=rand(0,34);
			$rand_number=$number["$rand_value"];
		
			if(empty($con))
			{ 
			$con=$rand_number;
			}
			else
			{
			$con="$con"."$rand_number";}
		}
		return $con;
	}
	
function get_module($id="")
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    $result = $CI->db->get('module')->row_array();
    }else{
    //$CI->db->where('status','1');  
    $result = $CI->db->get('module');
    }
    return $result; 
}

function checkBlacklist($id)
{
   $CI	=&	get_instance();
    $CI->load->database();
    $CI->db->where('phonenumber', $id);  
    $result = $CI->db->get('blacklist');
    if($result->num_rows() > 0)
    { return true;}else{return false;}
}
function get_ApiClass($id="")
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    $result = $CI->db->get('apiclass')->row_array();
    }else{
    $result = $CI->db->get('apiclass');
    }
    return $result; 
}
function get_networkbyModule($module_id,$network)
{
   $CI	=&	get_instance();
    $CI->load->database();
     $CI->db->where('module_id', $module_id);
    $CI->db->where('network_id', $network);
    $result = $CI->db->get('network')->row_array();
    return $result; 
}
function get_SubmodulebyModule($id="")
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('parent_id', $id);  
    $CI->db->order_by('network_id','Asc');
    $result = $CI->db->get('sub_module');
    }else{
    $result = $CI->db->get('sub_module');
    }
    return $result; 
}
function get_Submodule($id="")
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    $result = $CI->db->get('sub_module')->row_array();
    }else{
    $result = $CI->db->get('sub_module');
    }
    return $result; 
}
function get_Databundles($id="")
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('package_id', $id);  
    $result = $CI->db->get('databundles')->row_array();
    }else{
    $result = $CI->db->get('databundles');
    }
    return $result; 
}
function getPackage($id="")
{
    $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    $result = $CI->db->get('subscriptionPlans')->row_array();
    }else{
    $result = $CI->db->get('subscriptionPlans');
    }
    return $result; 
}
function get_tvplanPrice($plan)
{
    
    $CI	=&	get_instance();
    $CI->load->database();
    if($plan !=''){
    $CI->db->where('id', $plan);  
    $CI->db->or_where('package_name', $plan);  
    $result = $CI->db->get('cabletv');
    }else{
    $result = $CI->db->get('cabletv');
    }
    return $result; 
}    
function get_DiscoElec($id="")
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    $CI->db->or_where('name', $id);  
    $result = $CI->db->get('sub_module');
    }else{
    $result = $CI->db->get('sub_module');
    }
    return $result; 
}
function get_CablePlanAPI($id="")
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    $CI->db->or_where('cabletv', $id);  
    $result = $CI->db->get('cabletv');
    }else{
    $result = $CI->db->get('cabletv');
    }
    return $result; 
}
function get_CablePlan($id="")
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    $CI->db->or_where('cabletv', $id);  
    $result = $CI->db->get('cabletv')->row_array();
    }else{
    $result = $CI->db->get('cabletv');
    }
    return $result; 
}
function get_network($id="")
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    $result = $CI->db->get('network')->row_array();
    }else{
    $result = $CI->db->get('network');
    }
    return $result; 
}
function adminAssets()
{
    $url = base_url().'assets/';
    return $url;
}
function adminController()
{
    return base_url().'ControlPanel/';
}
   function getDeposits($sId)
    {
        $CI =&	get_instance();
        $CI->load->database();
         $CI->db->where('user_id',$sId); 
         $result = $CI->db->get('deposits');
         return $result;
    }
    
    function getHistory($sId,$type="")
    {
        $CI =&	get_instance();
        $CI->load->database();
         $CI->db->where('user_id',$sId); 
         if($type !=""){
          $CI->db->where('module_id',$type);
         }
         $CI->db->order_by('id','DESC');
         $CI->db->limit('50');
         $result = $CI->db->get('transactions');
         return $result;
    }
    function getDownlines($userid)
    {
        $CI =&	get_instance();
        $CI->load->database();
         $CI->db->where('sponsor_id',$userid);  
         $result = $CI->db->get('account');
         return $result;
    }
    
    function getBlog($blog_id="")
    {
        $CI =&	get_instance();
        $CI->load->database();
        if($blog_id !=""){
         $CI->db->where('id',$blog_id);  
        }
         $result = $CI->db->get('blog');
         return $result;
    }
   function verifyPhoneNumber($phone,$network_name){
            $response = array();
            $validate = substr($phone, 0, 4);
            $response["status"] = "success";


            //Automatically Disable Validator
                return $response;
            //Remove The Above Line To Allow Validator

            if($network_name=="MTN"){
                if(strpos(" 0702 0703 0713 0704 0706 0716 0802 0803 0806 0810 0813 0814 0816 0903 0913 0906 0916 0804 ", $validate) == FALSE || strlen($phone) != 11){
                  $response['msg'] = "This number is not an $network_name Number $phone";
                  $response["status"] = "fail";
                }
            }
            else if($network_name=="GLO"){
                if(strpos(" 0805 0705 0905 0807 0907 0707 0817 0917 0717 0715 0815 0915 0811 0711 0911 ", $validate) == FALSE || strlen($phone) != 11){
                 $response['msg'] = "This number is not an $network_name Number $phone";
                 $response["status"] = "fail";
                }
            }
            else if($network_name=="AIRTEL"){
                if(strpos(" 0904 0802 0902 0702 0808 0908 0708 0918 0818 0718 0812 0912 0712 0801 0701 0901 0907 0917 ", $validate) == FALSE || strlen($phone) != 11){
                    $response['msg'] = "This number is not an $network_name Number $phone";
                    $response["status"] = "fail";
                }
            }
            else if($network_name =="9MOBILE"){
                if(strpos(" 0809 0909 0709 0819 0919 0719 0817 0917 0717 0718 0918 0818 0808 0708 0908 ", $validate) == FALSE || strlen($phone) != 11){
                    $response['msg'] = "This number is not an $network_name Number $phone";
                    $response["status"] = "fail";
                }
            }
            else{
                $response['msg'] = "Unidentified Network Id";
                $response["status"] = "fail";
            }

            return $response;
        }  
function site_logo()
{
    $logo = get_settings('site_logo');
    $url = base_url().'assets/images/logo/'.$logo;
    return $url;
}
function get_faq($id="")
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    $result = $CI->db->get('faqs')->row_array();
    }else{
    $result = $CI->db->get('faqs');
    }
    return $result; 
}
function get_module_home($id="")
{
   $CI	=&	get_instance();
    $CI->load->database();
    $CI->db->where('id !=', '6');  
    if($id !=''){
    $CI->db->where('id', $id);  
    $result = $CI->db->get('module')->row_array();
    }else{
    $CI->db->limit('6');
    $result = $CI->db->get('module');
    }
    return $result; 
}
function get_api_provider($id="")
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    $result = $CI->db->get('api_provider')->row_array();
    }else{
    $result = $CI->db->get('api_provider');
    }
    return $result; 
}
function send_transaction($id)
  {
        $CI	=&	get_instance();
        $CI->load->database();
        $CI->load->model('User_model');
    $details = $CI->User_model->get_transaction_id($id); 
    if($details->num_rows() < 0)
    {
        $response['msg']="Invalid transaction Id";
        return $response;
    
    }
    $itemRow = $details->row_array();
    if($itemRow['completed'] == '1')
    {
        $response['msg']="This transaction has been completed";
        return $response; 
    }
    $getApi = get_api_provider($itemRow['api']);
    if($getApi['apiType'] == '1'){
        $result = do_msOrgAPI($getApi['api_key'],$getApi['endpoint'],$itemRow);
    }
        $response = json_decode($result,true);
        $CI->User_model->updateApiResponse($id,$response);
       // $response['msg']= $response;
        return $response;
    
}
function do_msOrgAPI($getApi,$host,$itemRow)
{
        $CI	=&	get_instance();
        $CI->load->database();
        $CI->load->model('User_model');
        $subModule = $CI->User_model->sub_module_single($itemRow['sub_module_id']);
    if($itemRow['module_id']=='1'){
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
            CURLOPT_POSTFIELDS =>'{
                "network": "'.$subModule['api_code'].'",
                "amount": "'.$itemRow['amount'].'",
                "mobile_number": "'.$itemRow['recipient'].'",
                "Ported_number":"true",
                "request-id" : "'.$itemRow['order_id'].'",
                "airtime_type": "'.$subModule['name'].'"
            }',
             CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Token $getApi"
            ),
            ));
            $response = curl_exec($curl);
            return $response;
    }
    if($itemRow['module_id']=='2'){
         $verifyplanID = $CI->User_model->verifyPlanID($itemRow['plan_id'])->row_array();
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
            CURLOPT_POSTFIELDS =>'{
                "network": "'.$subModule['api_code'].'",
                "mobile_number": "'.$itemRow['recipient'].'",
                "Ported_number":true,
                "request-id" : "'.$itemRow['order_id'].'",
                "plan": "'.$verifyplanID['provider_code'].'"
            }',
            
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Token $getApi"
            ),
            ));
         $response = curl_exec($curl);
            return $response;
    }
    
    
    if($itemRow['module_id']=='3'){
         $verifyplanID = $CI->User_model->verifyPlanID($itemRow['plan_id'])->row_array();
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
             CURLOPT_POSTFIELDS =>'{
                "cablename": "'.$subModule['api_code'].'",
                "smart_card_number": "'.$itemRow['recipient'].'",
                "cableplan":"'.$verifyplanID['provider_code'].'"
            }',
            
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Token $getApi"
            ),
            ));
         $response = curl_exec($curl);
            return $response;
    }
}
 function date_formatNew($date)
   {
    $newdateformat = date("D, d M Y H:i:s", strtotime($date));
    return $newdateformat;
   }
function site_favicon()
{
    $url = base_url().'assets/images/logo/logodarkicon.png';
    return $url;
}
function host_site()
{
     $apiUrl = base_url().'Api/';
    return  $apiUrl;
}
function getBanks($id='')
{
     $CI =&	get_instance();
      $CI->load->database();
      if($id !='')
      {
        $CI->db->where('id', $id);  
      }
     $result = $CI->db->get('banks');
     return $result;
}

function getManualAccount()
{
     $CI =&	get_instance();
      $CI->load->database();
      $status = '1';
      $CI->db->where('status', $status);  
     $result = $CI->db->get('system_bank_accounts');
     return $result->row_array();
}
function getElectricityProvider($id='')
{
     $CI =&	get_instance();
      $CI->load->database();
      if($id !='')
      {
        $CI->db->where('id', $id);  
      }
      $CI->db->where('parent_id','4');
   //   $CI->db->where('status', '1');  
     $result = $CI->db->get('sub_module');
     return $result;
}


function getSliderBanner($id='')
{
     $CI	=&	get_instance();
      $CI->load->database();
      if($id !='')
      {
        $CI->db->where('id', $id);  
      }
       $CI->db->where('status', '1');
     $result = $CI->db->get('mobile_slider');
     return $result;
}
function send_mail($to ='', $sub ='', $email_body=''){
  $CI	=&	get_instance();
  $CI->load->database();
  $CI->load->library('phpmailer_lib');
  $from_name = get_settings('smtp_user');;
  $app_name = get_settings('app_name');
  $mail = $CI->phpmailer_lib->load();
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
  $mail->Port     = $port;
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
function date_format_convert($date)
{
$convett = strtotime($date);
return date('F d, Y h:i:s a', $convett);  
}

function get_states($id='')
{
    $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    }
    $result = $CI->db->get('states');
    return $result;
}
function get_lgas($id='')
{
    $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('state_id', $id);  
    }
    $result = $CI->db->get('local_governments');
    return $result;
}
function getproducts_id($id)
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    }
    $result = $CI->db->get('products');
    return $result;  
}
function getproducts($id)
{
   $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('itemid', $id);  
    }
    $result = $CI->db->get('products');
    return $result;  
}
function get_bylgas($id='')
{
    $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('id', $id);  
    }
    $result = $CI->db->get('local_governments');
    return $result;
}
function time_format()
{
return date('H:i:s');  
}
function currentDate()
{
    return date('Y-m-d');
}

function user_avater($string)
    {
     $expr = '/(?<=\s|^)\w/iu';
    preg_match_all($expr, $string, $matches);
    $result = implode('', $matches[0]);
    $result = substr(strtoupper($result),0,2);
    $pic_url = 'assets/images/users/'.$string.'.jpg';
    if(file_exists($pic_url)){
       return base_url().$pic_url;
    }else{
        return base_url('assets/images/undraw_profile.svg');
    }
}
    function slugify($string, $spaceRepl = "-")
{
    $string = str_replace("&", "and", $string);

    $string = preg_replace("/[^a-zA-Z0-9 _-]/", "", $string);

    $string = strtolower($string);

    $string = preg_replace("/[ ]+/", " ", $string);

    $string = str_replace(" ", $spaceRepl, $string);

    return $string;
}
function currency(){
    return '&#8358';
}
function p_category($id='')
{
    $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
     $CI->db->where('category_id', $id);  
     $result = $CI->db->get('category')->row_array();
    }else{
   $CI->db->order_by('category_id','ASC');
    $result = $CI->db->get('category');
   }
    return $result;
}
function payment_getways($id='')
{
    $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
     $CI->db->where('gateway_id', $id);  
     $result = $CI->db->get('payment_gateways')->row_array();
    }else{
   //$CI->db->where('gateway_status', '1'); 
   $CI->db->order_by('gateway_id','ASC');
    $result = $CI->db->get('payment_gateways');
   }
    return $result;
}
function products($id='')
{
    $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
     $CI->db->where('id', $id);  
     $CI->db->order_by('id','desc');
     $result = $CI->db->get('products')->row_array();
    }else{
   $CI->db->order_by('id','desc');
    $result = $CI->db->get('products');
   }
    return $result;
}
function product_img($id=""){
    $details = products($id);
    if(file_exists($details['p_imgurl'])){
       return base_url().$details['p_imgurl'] ;
    }else{
        return base_url().'assets/images/product/ads_picture.jpg';
        //return base_url().'assets/images/product/imgdefault.jpg';
    }
		
	}
function fetchUserbyStatus($status)
{
     $CI	=&	get_instance();
    $CI->load->database();
    //$CI->db->where('account_status', $status); 
   $CI->db->order_by('accountid','DESC');
   $CI->db->limit('100');
    $result = $CI->db->get('account');
    return $result;
}
function status_code($id='')
{
     $CI	=&	get_instance();
    $CI->load->database();
    if($id !=''){
    $CI->db->where('code', $id); 
    $result = $CI->db->get('status_code')->row_array();
    }else{
    $result = $CI->db->get('status_code');
    }
    return $result;
}
function get_user_info($user_id='')
{
    $CI	=&	get_instance();
    $CI->load->database();
    if($user_id !=''){
     $CI->db->where('accountid', $user_id);  
     $CI->db->or_where('email', $user_id); 
     $CI->db->or_where('phone', $user_id);  
      $CI->db->or_where('api_key', $user_id); 
     $result = $CI->db->get('account')->row_array();
    }else{
   // $CI->db->where('item_status', '1'); 
   $CI->db->order_by('accountid','DESC');
    $result = $CI->db->get('account');
    
    }
    return $result;
}
    function date_convert($date='')
    {
        $converted = date("F j, Y, g:i a", strtotime($date));//strftime("%b %d, %Y", strtotime($date));
        return $converted;
    }
    function dateConvert($date='')
    {
        $converted = date("F j, Y", strtotime($date));;
        return $converted;
    }
   
   
    function img_loading(){
		return base_url().'assets/images/image_loading.gif';
	}
    function current_datetime()
    {
        return date('Y-m-d H:i:s');
    }
    function get_time_ago($time)
{
    $time_difference = time() - $time;

    if( $time_difference < 1 ) { return 'less than 1 second ago'; }
    $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );
}
