<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
         $this->userTbl = 'account';
    }
   
 public function confirmOldPassword($current_password,$user_id)
{
   $this->db->where('email', $user_id);
    $this->db->where('password', $current_password);
    $query = $this->db->get('admin');
   
    if($query->num_rows() > 0)
        return 1;
    else
        return 0;
}


public function getProductsbyID($id)
{
    $this->db->where('id', $id);
    $this->db->or_where('itemid', $id);
     $query = $this->db->get('products');
    return $query;
}
public function dailyTraffic($date)
{
   $this->db->where('date_login',$date); 
   //$this->db->group_by('user_id');
    $query = $this->db->get('daily_login');
    return $query->num_rows(); 
}
function dailycashBalance()
{
     $getApi = get_api_provider('13');
 
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://dailycashout.com.ng/api/balance.php?api_key='.$getApi['api_key'],
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
if(isset($result['balance'])){
return $result['balance'];
}else{
    return '0.00';
}
}
function MaskawasubWalletBanlance()
{
     $getApi = get_api_provider('3');
 $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://maskawasubapi.com/api/user/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Token '.$getApi['api_key']
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$result = json_decode($response,true);
if(isset($result['user']['wallet_balance'])){
return $result['user']['wallet_balance'];
}else{
    return '0.00';
}
}
function getwallet_vtpass()
{
     $getApi = get_api_provider('16');
     $curl = curl_init();
     curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://vtpass.com/api/balance',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
  "api-key: ".$getApi['api_key'],
    "public-key: ".$getApi['password'],
    "Content-Type: application/json"
  ),
));
$response = curl_exec($curl);
curl_close($curl);
$result = json_decode($response,true);
//return $response;
if(isset($result['contents']['balance'])){
return $result['contents']['balance'];
}else{
    return '0.00';
}
//print_r($response);
    
}
public function getTotalServiceTotal($submodule_id,$date)
{
    $data = $this->db->query("SELECT SUM(charge) AS sumtotal FROM transactions WHERE date_added = '$date' AND status = '1' AND sub_module_id='$submodule_id'");
    $result = $data->row_array();
    if(empty($result['sumtotal']))
    {
        return '0.00';
    }else{
     return $result['sumtotal'];  
    }
}
public function getProducts($type)
{
    if($type !='all'){
    $this->db->where('p_status', $type);
    }
    $this->db->where('is_draft', '0');
    $this->db->order_by('id','DESC');
     $query = $this->db->get('products');
    return $query;
}

public function get_all_member($parameters="",$value=""){
    $this->db->select('*');
     $this->db->from('account as t1');
     if($parameters !=''){
     $this->db->where('t1.'.$parameters, $value);
     }
    $this->db->order_by('t1.accountid','DESC');
    $this->db->limit('500');
    return $this->db->get();
  }
public function get_all_transactionuser($user_id){
    $this->db->select('*');
     $this->db->from('transactions as t1');
     $this->db->where('t1.user_id', $user_id);
    $this->db->order_by('t1.id','DESC');
    $this->db->limit('500');
    return $this->db->get();
  }
  public function searchTransaction($parmeter,$parameter_value,$submodule,$transactiondate,$status)
  {if(($parmeter !='')&&($parmeter !='type')&&($parmeter !='status')&&($parmeter !='date_added')){$this->db->where($parmeter, $parameter_value);}if($submodule !=''){$this->db->where('module_id', $submodule);}if($transactiondate !=''){$this->db->where('date_added',$transactiondate);}
    if($parmeter =='status'){$this->db->where('status', $status);}
    $this->db->order_by('id','DESC');
    $this->db->limit('200');
    return $this->db->get('transactions');
  }
 public function get_all_Dashboardtransaction(){
    $this->db->select('*');
     $this->db->from('transactions as t1');
     $this->db->order_by('t1.id','DESC');
    $this->db->limit('10');
    return $this->db->get();
  }
public function get_all_transaction(){
    $this->db->select('*');
     $this->db->from('transactions as t1');
     $this->db->order_by('t1.id','DESC');
    $this->db->limit('50');
    return $this->db->get();
  }
 public function insert_update($data,$user_id,$table,$table_id) {
       $this->db->where($table_id, $user_id);
        $insert = $this->db->update($table, $data);
       return true;
    }
public function update_transaction_status($transaction,$status,$trans_status='') {
        $data['trans_status'] = $status;
       // $data['trans_status'] = $status;
        $this->db->where('id', $transaction);
        $this->db->update('transactions', $data);
  }
 public function update_complaint_status($transaction,$status) {
        //$data['response'] = $status;
        $data['trans_status'] = $status;
        $this->db->where('reference_id', $transaction);
        $this->db->update('complaints', $data);
  }
  public function get_transaction_single($id)
  {
      $this->db->where('id', $id);
      return $this->db->get('transactions');
  }
 public function acc_user_details($login_id = 0) {
        if ($login_id > 0) {
            $this->db->where('accountid', $login_id);
        }
        return $this->db->get('account');
    }
  public function update_user_status($package_id) {
        $get_userdetails = $this->acc_user_details($package_id)->row_array();
        
        $this->db->where('accountid', $package_id);
        $this->db->update('account', $data);
        $this->session->set_flashdata('flash_message',('Record updated successfully'));
        return true;
    }
 public function do_update_user_status($id,$phone,$email,$package,$status,$fullname) {
       $data['fullname'] = $fullname;
       $data['phone'] = $phone;
       $data['email'] = $email;
       $data['package_id'] = $package;
       $data['suspend'] = $package;
        $this->db->where('accountid', $id);
        $this->db->update('account', $data);
        $this->session->set_flashdata('flash_message',('Record updated successfully'));
        return true;
    }
public function saveNewPassword($new_Password,$user_email){
    $array = array(
            'password'=>$new_Password
            );
    $this->db->where('email', $user_email);
    $query = $this->db->update('admin',$array);
    if($query){
      return true;
    }else{
      return false;
    }
  }
  public function saveNewPasswordUser($new_Password,$user_id){
    $array = array(
            'password'=>$new_Password
            );
    $this->db->where('accountid', $user_id);
    $query = $this->db->update('account',$array);
    if($query){
      return true;
    }else{
      return false;
    }
  }
 public function fetch_report($user_id) 
    { 
 $this->db->select('transaction_type,cost');
 $this->db->from('transaction');
$this->db->where('user_id',$user_id);
return $this->db->get()->result();
}    
 public function get_chart_report($user_id) 
    { 
    	//$today_date = date('Y-m-d');
$this->db->select('*,sum(cost) as sumtotal', FALSE);
$this->db->group_by('transaction_type');
$this->db->from('transaction');

$this->db->where('user_id',$user_id);
return $this->db->get()->result();
  
    } 

  public function updateToken($token,$user_id){
    $array = array(
            'token'=>$token
            );
    $this->db->where('phone', $user_id);
    $query = $this->db->update('account',$array);
    if($query){
      return true;
    }else{
      return false;
    }
    
  }  
  public function saveNewPasswordHome($new_Password,$user_id){
    $array = array(
            'password'=>$new_Password
            );
    $this->db->where('phone', $user_id);
    $query = $this->db->update('account',$array);
    if($query){
      return true;
    }else{
      return false;
    }
    
  }  
     function getRows($params = array()){
        $this->db->select('*');
        $this->db->from($this->userTbl);
        
        //fetch data by conditions
        if(array_key_exists("conditions",$params)){
            foreach ($params['conditions'] as $key => $value) {
                $this->db->where($key,$value);
            }
        }
        
        if(array_key_exists("id",$params)){
            $this->db->where('id',$params['id']);
            $query = $this->db->get();
            $result = $query->row_array();
        }else{
            //set start and limit
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit'],$params['start']);
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
                $this->db->limit($params['limit']);
            }
            $query = $this->db->get();
            if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){
                $result = $query->num_rows();
            }elseif(array_key_exists("returnType",$params) && $params['returnType'] == 'single'){
                $result = ($query->num_rows() > 0)?$query->row_array():FALSE;
            }else{
                $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            }
        }

        //return fetched data
        return $result;
    }
public function login($email) 
{
    $this->db->where('email', $email);
    $query = $this->db->get('admin');

    if ($query->num_rows() == 1) {
        $user = $query->row_array();
        return $user;
    } else {
        return false;
    }
}
public function check_exist($phone) 
{
    $this->db->where('phone', $phone);
    //$this->db->where('email', $email);
    $query = $this->db->get('account');
    if ($query->num_rows() == 1) {
        //$user = $query->row_array();
        return true;
    } else {
        return false;
    }
}
public function check_token($phone,$token) 
{
    $this->db->where('phone', $phone);
    $this->db->where('token', $token);
    $query = $this->db->get('account');
    if ($query->num_rows() == 1) {
        $user = $query->row_array();
        return $user;
    } else {
        return false;
    }
}
public function getTotalSalesbyUser($userid,$date)
{
    $data = $this->db->query("SELECT SUM(charge) AS sumtotal FROM transactions WHERE date_added = '$date' AND status = '1' AND user_id='$userid'");
    $result = $data->row_array();
    if(empty($result['sumtotal']))
    {
        return '0.00';
    }else{
     return $result['sumtotal'];  
    }
}
public function getTotalSales($date)
{
    $data = $this->db->query("SELECT SUM(charge) AS sumtotal FROM transactions WHERE date_added = '$date' AND status = '1'");
    $result = $data->row_array();
    if(empty($result['sumtotal']))
    {
        return '0.00';
    }else{
     return $result['sumtotal'];  
    }
}
public function getTotalFund($date)
{
    $data = $this->db->query("SELECT SUM(amount) AS sumtotal FROM deposits WHERE date_added = '$date' AND payment_status = '1'");
    $result = $data->row_array();
    if(empty($result['sumtotal']))
    {
        return '0.00';
    }else{
      return $result['sumtotal'];  
    }
}
public function getTotalProfit($date)
{
    $data = $this->db->query("SELECT SUM(profit) AS sumtotal FROM transactions WHERE date_added = '$date' AND status = 1");
    $result = $data->row_array();
    if(empty($result['sumtotal']))
    {
        return '0.00';
    }else{
     return  $result['sumtotal'];  
    }
}
public function getTotalCustomerWallet()
{
    $data = $this->db->query("SELECT SUM(wallet_balance) AS sumtotal FROM account");
    $result = $data->row_array();
    if(empty($result['sumtotal']))
    {
        return '0.00';
    }else{
     return $result['sumtotal'];  
    }
}
public function getTotalFundbyUser($userid)
{
    $data = $this->db->query("SELECT SUM(amount) AS sumtotal FROM deposits WHERE user_id = '$userid' AND payment_status = 1");
   $result = $data->row_array();
    if(empty($result['sumtotal']))
    {
        return '0.00';
    }else{
     return $result['sumtotal'];  
    }
}
 public function user_details($login_id = 0) {
        if ($login_id > 0) {
            $this->db->where('admin_id', $login_id);
        }
        return $this->db->get('admin');
    }
    public function get_user_deposited($login_id = 0) {
        if ($login_id > 0) {
        $status = '1';
        $this->db->select('*,sum(amount) as sumtotal', FALSE);
        $this->db->where('status', $status);
        
        
        }
        return $this->db->get('deposits');
    }

public function count_users($status) {
      $this->db->where('accountid', $status);
     return $this->db->get('account')->row_array();
}
    public function get_user_used($login_id = 0) {
        if ($login_id > 0) {
        $status = 'success';
        $this->db->select('*,sum(cost) as sumtotal', FALSE);
        $this->db->where('trans_status', $status);
        $this->db->where('user_id', $login_id);
        
        }
        return $this->db->get('transaction');
    }
public function get_last_deposit($login_id = 0) {
        if ($login_id > 0) {
        $status = '1';
        $this->db->select('amount as sumtotal', FALSE);
        $this->db->where('status', $status);
        $this->db->where('user_id', $login_id);
        $this->db->order_by('id',"desc");
        $this->db->limit(1);
               
        }
        return $this->db->get('deposits');
    }
     public function insert($data = array()) {
        //add created and modified data if not included
        if(!array_key_exists("date_created", $data)){
            $data['date_created'] = date("Y-m-d H:i:s");
        }
       
        
        //insert user data to users table
        $insert = $this->db->insert($this->userTbl, $data);
        
        //return the status
        if($insert){
            return $this->db->insert_id();;
        }else{
            return false;
        }
    }

public function create_faq()
{
    $question = html_escape($this->input->post('question'));
    $response = html_escape($this->input->post('response'));
         $data['question'] = $question;
        $data['response'] = $response;
    //    $this->db->where('id', $id);
        $this->db->insert('faqs', $data);
        return true;
       
}
public function create_testimonies()
{
    $testimony = html_escape($this->input->post('testimony'));
    $name = html_escape($this->input->post('name'));
    $title = html_escape($this->input->post('title'));
         $data['testimony'] = $testimony;
        $data['name'] = $name;
        $data['title'] = $title;
    //    $this->db->where('id', $id);
        $this->db->insert('testimonies', $data);
        return true;
       
}

public function update_testimonies($id)
{
    $testimony = html_escape($this->input->post('testimony'));
    $name = html_escape($this->input->post('name'));
    $title = html_escape($this->input->post('title'));
         $data['testimony'] = $testimony;
        $data['name'] = $name;
        $data['title'] = $title;
        $this->db->where('id', $id);
        $this->db->update('testimonies', $data);
        return true;
       
}
public function update_faqs($id)
{
    $question = html_escape($this->input->post('question'));
    $response = html_escape($this->input->post('response'));
         $data['question'] = $question;
        $data['response'] = $response;
        $this->db->where('id', $id);
        $this->db->update('faqs', $data);
        return true;
       
}
public function update_page($id)
{
    $page_content = html_escape($this->input->post('page_content'));
    if($_FILES['featured_image']['name'] != "") {
        $banner_id = $this->random_strings(6);
        $url = base_url('assets/images/pages/'.$banner_id.'.jpg');
        move_uploaded_file($_FILES['featured_image']['tmp_name'], 'assets/images/pages/'. $banner_id . '.jpg');
    }else{
        $url = '';
    }
         $data['featured_image'] = $url;
        $data['page_content'] = $page_content;
        $this->db->where('id', $id);
        $this->db->update('pages', $data);
        return true;
       
}
public function random_strings($length_of_string)
{
$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
return substr(str_shuffle($str_result),
                   0, $length_of_string);
}
public function uploadAssets()
{
    if($_FILES['site_logo']['name'] != "") {
        $slide_id = $this->random_strings(6);
        move_uploaded_file($_FILES['site_logo']['tmp_name'], 'assets/images/logo/'. $slide_id . '.jpg');
        $data['value'] = $slide_id.'.jpg';
        $this->db->where('key', 'site_logo');
        $this->db->update('settings', $data);
       }
      
       if($_FILES['home_banner_url']['name'] != "") {
        $banner_id = $this->random_strings(6);
        $url = base_url('assets/images/banner/'.$banner_id.'.jpg');
        move_uploaded_file($_FILES['home_banner_url']['tmp_name'], 'assets/images/banner/'. $banner_id . '.jpg');
        $data['value'] = $url;
        $this->db->where('key', 'home_banner_url');
        $this->db->update('settings', $data);
       }
    
       if($_FILES['site_favicon']['name'] != "") {
        $favicon_id = $this->random_strings(6);
        move_uploaded_file($_FILES['site_favicon']['tmp_name'], 'assets/images/favicon/'. $favicon_id . '.jpg');
        $data['value'] = $favicon_id.'.jpg';
        $this->db->where('key', 'site_favicon');
        $this->db->update('settings', $data);
       }
     
      if($_FILES['announcement_img']['name'] != "") {
        $announcement_img = $this->random_strings(6);
        move_uploaded_file($_FILES['announcement_img']['tmp_name'], 'assets/images/announcement/'. $announcement_img . '.jpg');
        $data['value'] = $announcement_img.'.jpg';
        $this->db->where('key', 'announcement_img');
        $this->db->update('settings', $data);
       }
}
public function update_system_settings() {
        if(isset($_POST['app_name'])){
        $data['value'] = html_escape($this->input->post('app_name'));
        $this->db->where('key', 'app_name');
        $this->db->update('settings', $data);
        }

        if(isset($_POST['super_email'])){
        $data['value'] = html_escape($this->input->post('super_email'));
        $this->db->where('key', 'super_email');
        $this->db->update('settings', $data);
        }
        
        if(isset($_POST['ref_Bonus'])){
        $data['value'] = html_escape($this->input->post('ref_Bonus'));
        $this->db->where('key', 'ref_Bonus');
        $this->db->update('settings', $data);
        }
        
        if(isset($_POST['whatsapp'])){
        $data['value'] = html_escape($this->input->post('whatsapp'));
        $this->db->where('key', 'whatsapp');
        $this->db->update('settings', $data);
        }
        
        if(isset($_POST['upgradeFee'])){
        $data['value'] = html_escape($this->input->post('upgradeFee'));
        $this->db->where('key', 'upgradeFee');
        $this->db->update('settings', $data);
        }
        
        if(isset($_POST['airtimemin'])){
        $data['value'] = html_escape($this->input->post('airtimemin'));
        $this->db->where('key', 'airtimemin');
        $this->db->update('settings', $data);
        }
        
        if(isset($_POST['airtimemax'])){
        $data['value'] = html_escape($this->input->post('airtimemax'));
        $this->db->where('key', 'airtimemax');
        $this->db->update('settings', $data);
        }
        
        if(isset($_POST['apidocsLink'])){
        $data['value'] = html_escape($this->input->post('apidocsLink'));
        $this->db->where('key', 'apidocsLink');
        $this->db->update('settings', $data);
        }
        
         if(isset($_POST['electricitymin'])){
        $data['value'] = html_escape($this->input->post('electricitymin'));
        $this->db->where('key', 'electricitymin');
        $this->db->update('settings', $data);
        }
        
         if(isset($_POST['electricitymax'])){
        $data['value'] = html_escape($this->input->post('electricitymax'));
        $this->db->where('key', 'electricitymax');
        $this->db->update('settings', $data);
        }
        
        if(isset($_POST['phone'])){
        $data['value'] = html_escape($this->input->post('phone'));
        $this->db->where('key', 'phone');
        $this->db->update('settings', $data);
        }
        
if(isset($_POST['betting_charge'])){
        $data['value'] = html_escape($this->input->post('betting_charge'));
        $this->db->where('key', 'betting_charge');
        $this->db->update('settings', $data);
}
 
 if(isset($_POST['address'])){
     $data['value'] = html_escape($this->input->post('address'));
        $this->db->where('key', 'address');
        $this->db->update('settings', $data);
 }
 if(isset($_POST['docVerification'])){
        $data['value'] = html_escape($this->input->post('docVerification'));
        $this->db->where('key', 'docVerification');
        $this->db->update('settings', $data);
 }
     if(isset($_POST['welcome_msg'])){   
        
         $data['value'] = html_escape($this->input->post('welcome_msg'));
        $this->db->where('key', 'description');
        $this->db->update('settings', $data);
     }
      
     if(isset($_POST['fb_url'])){
         $data['value'] = html_escape($this->input->post('fb_url'));
        $this->db->where('key', 'fb_url');
        $this->db->update('settings', $data);
     }
    if(isset($_POST['twitter_url'])){    
        $data['value'] = html_escape($this->input->post('twitter_url'));
        $this->db->where('key', 'twitter_url');
        $this->db->update('settings', $data);
    }
    if(isset($_POST['instagram_url'])){    
        $data['value'] = html_escape($this->input->post('instagram_url'));
        $this->db->where('key', 'instagram_url');
        $this->db->update('settings', $data);
    }
     if(isset($_POST['notification_button_text'])){
         $data['value'] = html_escape($this->input->post('notification_button_text'));
        $this->db->where('key', 'notification_button_text');
        $this->db->update('settings', $data);
     }
      if(isset($_POST['notification'])){  
        $data['value'] = html_escape($this->input->post('notification'));
        $this->db->where('key', 'notification');
        $this->db->update('settings', $data);
      }
      if(isset($_POST['notification_button_link'])){  
        
        $data['value'] = html_escape($this->input->post('notification_button_link'));
        $this->db->where('key', 'notification_button_link');
        $this->db->update('settings', $data);
      }
      if(isset($_POST['youtube_url'])){  
        $data['value'] = html_escape($this->input->post('youtube_url'));
        $this->db->where('key', 'youtube_url');
        $this->db->update('settings', $data);
      }
      if(isset($_POST['announcement'])){  
        $data['value'] = html_escape($this->input->post('announcement'));
        $this->db->where('key', 'announcement');
        $this->db->update('settings', $data);
      }
      if(isset($_POST['scrolling_message'])){  
        $data['value'] = html_escape($this->input->post('scrolling_message'));
        $this->db->where('key', 'scrolling_message');
        $this->db->update('settings', $data);
      }
        
        if(isset($_POST['default_password'])){
        $data['value'] = html_escape($this->input->post('default_password'));
        $this->db->where('key', 'default_password');
        $this->db->update('settings', $data);
        }
        
        
        if(isset($_POST['virtualAccountsGateway'])){
        $data['value'] = html_escape($this->input->post('virtualAccountsGateway'));
        $this->db->where('key', 'virtualAccountsGateway');
        $this->db->update('settings', $data);
        }
        
        if(isset($_POST['transfer_fee'])){
        $data['value'] = html_escape($this->input->post('transfer_fee'));
        $this->db->where('key', 'transfer_fee');
        $this->db->update('settings', $data);
        }
        
        if(isset($_POST['smtp_host'])){
        $data['value'] = html_escape($this->input->post('smtp_host'));
        $this->db->where('key', 'smtp_host');
        $this->db->update('settings', $data);
        }
        if(isset($_POST['smtp_port'])){
        $data['value'] = html_escape($this->input->post('smtp_port'));
        $this->db->where('key', 'smtp_port');
        $this->db->update('settings', $data);
        }
        if(isset($_POST['smtp_user'])){
            
        $data['value'] = html_escape($this->input->post('smtp_user'));
        $this->db->where('key', 'smtp_user');
        $this->db->update('settings', $data);
        }
        if(isset($_POST['smtp_pass'])){
            
        $data['value'] = html_escape($this->input->post('smtp_pass'));
        $this->db->where('key', 'smtp_pass');
        $this->db->update('settings', $data);
        }
        if(isset($_POST['google_recaptcha_site_key'])){
            
        $data['value'] = html_escape($this->input->post('google_recaptcha_site_key'));
        $this->db->where('key', 'google_recaptcha_site_key');
        $this->db->update('settings', $data);
        }
       if(isset($_POST['google_recaptcha_secrete_key'])){ 
         $data['value'] = html_escape($this->input->post('google_recaptcha_secrete_key'));
        $this->db->where('key', 'google_recaptcha_secrete_key');
        $this->db->update('settings', $data);
       }
       
        if(isset($_POST['secondary_color'])){
         $data['value'] = html_escape($this->input->post('secondary_color'));
        $this->db->where('key', 'secondary_color');
        $this->db->update('settings', $data);
        }
        if(isset($_POST['cardPaymentGateway'])){
         $data['value'] = html_escape($this->input->post('cardPaymentGateway'));
        $this->db->where('key', 'cardPaymentGateway');
        $this->db->update('settings', $data);
        }
        
        
        if(isset($_POST['primary_color'])){
        $data['value'] = html_escape($this->input->post('primary_color'));
        $this->db->where('key', 'primary_color');
        $this->db->update('settings', $data);
        }
        if(isset($_POST['footer_js'])){
        $data['value'] = $this->input->post('footer_js');
        $this->db->where('key', 'footer_js');
        $this->db->update('settings', $data);
        }
       if(isset($_POST['head_js'])){ 
        $data['value'] = $this->input->post('head_js');
        $this->db->where('key', 'head_js');
        $this->db->update('settings', $data);
       }
       if(isset($_POST['become_an_agent'])){
           
         $data['value'] = html_escape($this->input->post('become_an_agent'));
        $this->db->where('key', 'become_an_agent');
        $this->db->update('settings', $data);
       }
        if(isset($_POST['transfer_fee'])){
            
        $data['value'] = html_escape($this->input->post('transfer_fee'));
        $this->db->where('key', 'transfer_fee');
        $this->db->update('settings', $data);
        }
       if(isset($_POST['virtual_funding'])){ 
        $data['value'] = html_escape($this->input->post('virtual_funding'));
        $this->db->where('key', 'virtual_funding');
        $this->db->update('settings', $data);
       } 
        
        
        return true;
        
  }
 public function activate_data($package_id,$status) {
        $data['status'] = $status;
        $this->db->where('package_id', $package_id);
        $this->db->update('databundles', $data);
        $this->session->set_flashdata('flash_message',('Record updated successfully'));
    }
    
 public function activate_cabletv($package_id,$status) {
        $data['tv_status'] = $status;
        $this->db->where('id', $package_id);
        $this->db->update('cabletv', $data);
        $this->session->set_flashdata('flash_message',('Record updated successfully'));
    }
    
 public function update_data_record($package_id) {
     
     $data['network_id'] = html_escape($this->input->post('network'));
        $data['package_name'] = html_escape($this->input->post('package'));
        $data['price'] = html_escape($this->input->post('price'));
    
        $data['data_size'] = html_escape($this->input->post('data_size'));
       $data['code_id'] = $this->slugify(html_escape($this->input->post('package')));
        $data['ussd_code'] = html_escape($this->input->post('ussd_code'));
       $data['status'] = '1';
       $data['provider_code'] = html_escape($this->input->post('provider_code'));
       $data['ncwallet'] = html_escape($this->input->post('ncwallet'));
       $data['nabatulusub'] = html_escape($this->input->post('nabatulusub'));
       
       $data['special_code'] = html_escape($this->input->post('special_code'));
       $data['product_code'] = $this->slugify(html_escape($this->input->post('package')));
      $data['user_price'] = html_escape($this->input->post('user_price'));
       $data['api_price'] = html_escape($this->input->post('api_price'));
       $data['reseller_price'] = html_escape($this->input->post('reseller_price'));
    $this->db->where('package_id', $package_id);
        $this->db->update('databundles', $data);
     $this->session->set_flashdata('flash_message',('New Product added successfully'));
     
     /*
        $data['network_id'] = html_escape($this->input->post('network'));
        $data['package_name'] = html_escape($this->input->post('package'));
         $data['price'] = html_escape($this->input->post('price'));
       // $data['reseller_price'] = html_escape($this->input->post('reseller_price'));
       // $data['special_price'] = html_escape($this->input->post('special_price'));
        $data['code_id'] = html_escape($this->input->post('package_code'));
        $data['ussd_code'] = html_escape($this->input->post('ussd_code'));
      $this->db->where('package_id', $package_id);
        $this->db->update('databundles', $data);
    $this->session->set_flashdata('flash_message',('Record updated successfully'));
     */      
      
    }

public function update_special_data($package_id) {
        $data['user_price'] = html_escape($this->input->post('user_price'));
        $data['reseller_price'] = html_escape($this->input->post('reseller_price'));
         $data['api_price'] = html_escape($this->input->post('api_price'));
         $data['status'] = html_escape($this->input->post('status'));
       $this->db->where('id', $package_id);
        $this->db->update('product_price', $data);
   
    $data_sub['status'] = html_escape($this->input->post('status'));
    $product_code = html_escape($this->input->post('product_code'));
    $this->db->where('special_code', $product_code);
    $this->db->update('sub_module', $data_sub);        
    
    return true;  
    }
public function update_discount_record($package_id) {
        $data['user_percent'] = html_escape($this->input->post('discount'));
        $data['reseller_percent'] = html_escape($this->input->post('discount_reseller'));
         $data['api_percent'] = html_escape($this->input->post('discount_api'));
         if(isset($_POST['network_status'])){
        $data['status'] = '1';
         }else{
             $data['status'] = '0';
         }
       $this->db->where('id', $package_id);
        $this->db->update('network', $data);
    $this->session->set_flashdata('flash_message',('Record updated successfully'));
           
      
    }
public function update_dstv_record($package_id) {
        $data['package_name'] = html_escape($this->input->post('package'));
         $data['price'] = html_escape($this->input->post('price'));
        /*
        $data['reseller_price'] = html_escape($this->input->post('reseller_price'));
        $data['special_price'] = html_escape($this->input->post('special_price'));
        $data['package_code'] = html_escape($this->input->post('package_code'));
        */
        $this->db->where('id', $package_id);
        $this->db->update('cabletv', $data);
    $this->session->set_flashdata('flash_message',('Record updated successfully'));
           
      
    }
public function update_gotv_record($package_id) {
        $data['package_name'] = html_escape($this->input->post('package'));
         $data['price'] = html_escape($this->input->post('price'));
       $data['cabletv'] = html_escape($this->input->post('tv_type'));
         $data['ncwallet'] = html_escape($this->input->post('ncwallet'));
          $data['nabatulusub'] = html_escape($this->input->post('nabatulusub'));
          /*
        $data['special_price'] = html_escape($this->input->post('special_price'));
        $data['package_code'] = html_escape($this->input->post('package_code'));
        */
      $this->db->where('id', $package_id);
        $this->db->update('cabletv', $data);
    $this->session->set_flashdata('flash_message',('Record updated successfully'));
           
      
    }
public function update_package($id) {
        $data['package_title'] = html_escape($this->input->post('package_title'));
        $data['upgrade_cost'] = html_escape($this->input->post('upgrade_cost'));
        $data['upline_earn'] = html_escape($this->input->post('upline_earn'));
    $this->db->where('id', $id);
        $this->db->update('package', $data);
    $this->session->set_flashdata('flash_message',('Record updated successfully'));
           
      
    }
public function update_module_record($module_id) {
        $data['module'] = html_escape($this->input->post('module_name'));
        $data['api'] = html_escape($this->input->post('api_route'));
    $data['bonus_status'] = html_escape($this->input->post('bonus_status'));
    $data['referral_comm'] = html_escape($this->input->post('referral_comm'));
    $data['short_description'] = html_escape($this->input->post('short_description'));
    $data['extra'] = html_escape($this->input->post('extra'));
        $data['status'] = html_escape($this->input->post('status'));
      $this->db->where('id', $module_id);
        $this->db->update('module', $data);
    $this->session->set_flashdata('flash_message',('Record updated successfully'));
           
      
    }
public function update_payment_gateway($id) {
        $data['gateway_name'] = html_escape($this->input->post('gateway_name'));
        $data['gateway_percent'] = html_escape($this->input->post('gateway_percent'));
    $data['pk_live'] = html_escape($this->input->post('pk_live'));
    $data['sk_live'] = html_escape($this->input->post('sk_live'));
        $data['pk_test'] = html_escape($this->input->post('pk_test'));
        
        $data['sk_test'] = html_escape($this->input->post('sk_test'));
        $data['gateway_status'] = html_escape($this->input->post('gateway_status'));
        
        $data['mode'] = html_escape($this->input->post('mode'));
        $data['contract_code'] = html_escape($this->input->post('contract_code'));
        
      $this->db->where('gateway_id', $id);
        $this->db->update('payment_gateways', $data);
    $this->session->set_flashdata('flash_message',('Record updated successfully'));
           
      
    }
public function update_api_provider($id) {
        $data['provider_name'] = html_escape($this->input->post('api_name'));
        $data['user_id'] = html_escape($this->input->post('user_id'));
    $data['api_key'] = html_escape($this->input->post('api_key'));
    if(isset($_POST['ncpin_pin'])){
    $data['ncpin_pin'] = html_escape($this->input->post('ncpin_pin'));
    }
   // $data['success_response'] = html_escape($this->input->post('success_response'));
     //   $data['api_status'] = html_escape($this->input->post('api_status'));
        
      $this->db->where('id', $id);
        $this->db->update('api_provider', $data);
    $this->session->set_flashdata('flash_message',('Record updated successfully'));
           
      
    }
public function update_startimes_record($package_id) {
        
        
        $data['package_name'] = html_escape($this->input->post('package'));
         $data['price'] = html_escape($this->input->post('price'));
   /*
    $data['reseller_price'] = html_escape($this->input->post('reseller_price'));
    $data['special_price'] = html_escape($this->input->post('special_price'));
        $data['package_code'] = html_escape($this->input->post('package_code'));
    */
      $this->db->where('id', $package_id);
        $this->db->update('cable_tv_package', $data);
    $this->session->set_flashdata('flash_message',('Record updated successfully'));
           
      
    }
 
public function update_user_bal_admin($user_id,$phone,$amount,$wallet_type) {
    $details = $this->acc_user_details($user_id)->row_array();
        $data[$wallet_type] = $details[$wallet_type] + $amount;
        $this->db->where('accountid', $user_id);
        $this->db->update('account', $data);
  }
public function get_all_balance() {
        $data = $this->db->query("Select SUM(wallet_balance) as sumtotal from account");
        //$this->db->select('*,sum(wallet_balance) as sumtotal', FALSE);
       return $data;//$this->db->get('account');
    }
public function get_all_deposit() {
        //$this->db->select('*,sum(amount) as sumtotal', FALSE);
        $data = $this->db->query("Select SUM(amount) as sumtotal from deposits");
       return $data;// $this->db->get('deposits');
    }
 public function add_deposit_history($user_id,$getbal,$status,$amount,$invoice_number,$new_bal,$narration) {
        
        $data['reference'] = $invoice_number;
        $data['user_id'] = $user_id;
        $data['amount'] = $amount;
         $data['gateway'] = '5';
        $data['date_added'] = date('Y-m-d');
        $data['payment_status'] = $status;
        $data['initia_bal'] = $getbal;
       $data['new_bal'] = $new_bal;
       $data['response'] = $narration;
      $this->db->insert('deposits', $data);
    
      
    }
public function upload_new_slide() {
        
        $data['active'] = '1';
       $this->db->insert('slides', $data);
      $slide_id  =   $this->db->insert_id();
      if ($_FILES['slide_img']['name'] != "") {
             move_uploaded_file($_FILES['slide_img']['tmp_name'], 'assets/img/slider/'. $slide_id . '.jpg');
       }
     $this->session->set_flashdata('flash_message',('New Slide added successfully'));
           
      
    }
 public function create_new_dstv() {
        
        $data['package'] = html_escape($this->input->post('package'));
        $data['price'] = html_escape($this->input->post('price'));
        $data['package_code'] = html_escape($this->input->post('package_code'));
       $data['status'] = '1';
      $this->db->insert('dstv_package', $data);
     $this->session->set_flashdata('flash_message',('New Product added successfully'));
           
      return true;
    }
public function save_notification($member_type,$subject,$body)
{
        $data['user_id'] = $member_type;
        $data['subject'] = $subject;
        $data['body'] = $body;
       $data['date_created'] = date('Y-m-d');
      $this->db->insert('notification', $data);
      return true;
}
function slugify($string, $spaceRepl = "_")
{
    $string = str_replace("&", "and", $string);

    $string = preg_replace("/[^a-zA-Z0-9 _-]/", "", $string);

    $string = strtolower($string);

    $string = preg_replace("/[ ]+/", " ", $string);

    $string = str_replace(" ", $spaceRepl, $string);

    return $string;
}
public function create_new_data_product() {
        $data['network_id'] = html_escape($this->input->post('network'));
        $data['package_name'] = html_escape($this->input->post('package'));
        $data['price'] = html_escape($this->input->post('price'));
       $data['data_size'] = html_escape($this->input->post('data_size'));
       $data['code_id'] = $this->slugify(html_escape($this->input->post('package')));
        $data['ussd_code'] = html_escape($this->input->post('ussd_code'));
       $data['status'] = '1';
       $data['special_code'] = html_escape($this->input->post('special_code'));
       $data['nabatulusub'] = html_escape($this->input->post('nabatulusub'));
      $data['ncwallet'] = html_escape($this->input->post('ncwallet'));
       $data['provider_code'] = html_escape($this->input->post('provider_code'));
       
       $data['product_code'] = $this->slugify(html_escape($this->input->post('package')));
      $data['user_price'] = html_escape($this->input->post('user_price'));
       $data['api_price'] = html_escape($this->input->post('api_price'));
       $data['reseller_price'] = html_escape($this->input->post('reseller_price'));
      $this->db->insert('databundles', $data);
     $this->session->set_flashdata('flash_message',('New Product added successfully'));
           
      
    }
public function create_bank_accounts() {
       
        $data['bank_name'] = html_escape($this->input->post('bank_name'));
        $data['account_number'] = html_escape($this->input->post('account_number'));
       $data['account_name'] = html_escape($this->input->post('account_name'));
       $data['account_type'] = html_escape($this->input->post('account_type'));
      $this->db->insert('system_bank_accounts', $data);
     return true;
    }
public function update_bank_record($bank_id) {
        $data['bank_name'] = html_escape($this->input->post('bank_name'));
        $data['account_number'] = html_escape($this->input->post('account_number'));
       $data['account_name'] = html_escape($this->input->post('account_name'));
       $data['account_type'] = html_escape($this->input->post('account_type'));
      $this->db->where('id', $bank_id);
        $this->db->update('system_bank_accounts', $data);
    return true;
    }
public function create_new_plan_product() {
       
        $data['package_name'] = html_escape($this->input->post('package'));
        $data['price'] = html_escape($this->input->post('price'));
        $data['upline_earn'] = html_escape($this->input->post('upline_earn'));
       $data['status'] = '1';
      $this->db->insert('pacakge', $data);
     $this->session->set_flashdata('flash_message',('New Plan added successfully'));
           
      
    }
public function create_new_gotv() {
        
        $data['package_name'] = html_escape($this->input->post('package'));
        $data['price'] = html_escape($this->input->post('price'));
       $data['package_code'] = html_escape($this->input->post('ncwallet'));
        $data['cabletv'] = html_escape($this->input->post('tv_type'));
         $data['ncwallet'] = html_escape($this->input->post('ncwallet'));
       $data['nabatulusub'] = html_escape($this->input->post('nabatulusub'));
       $data['tv_status'] = '1';
      $this->db->insert('cabletv', $data);
     $this->session->set_flashdata('flash_message',('New Product added successfully'));
   }
public function create_new_startimes() {
        
        $data['package'] = html_escape($this->input->post('package'));
        $data['price'] = html_escape($this->input->post('price'));
        $data['package_code'] = html_escape($this->input->post('package_code'));
       $data['status'] = '1';
      $this->db->insert('startimes', $data);
     $this->session->set_flashdata('flash_message',('New Product added successfully'));
           
      
    }

public function delete_testimonies($id) {
        $this->db->where('id', $id);
        $this->db->delete('testimonies');
  }
public function delete_faqs($id) {
        $this->db->where('id', $id);
        $this->db->delete('faqs');
  }
public function delete_user($id) {
        $this->db->where('accountid', $id);
        $this->db->delete('account');
  }
public function delete_plan($id) {
        $this->db->where('id', $id);
        $this->db->delete('pacakge');
  }
public function delete_dstv($id) {
        $this->db->where('id', $id);
        $this->db->delete('dstv_package');
  }
public function delete_bank($id) {
        $this->db->where('id', $id);
        $this->db->delete('system_bank_accounts');
  }
 public function delete_slide($id) {
        $this->db->where('slide_id', $id);
        $this->db->delete('slides');
  }
public function delete_gotv($id) {
        $this->db->where('id', $id);
        $this->db->delete('cabletv');
  }
public function delete_startimes($id) {
        $this->db->where('id', $id);
        $this->db->delete('startimes');
  }
 public function data_product($id) {
        $this->db->where('package_id', $id);
        $this->db->delete('databundles');
  }
public function update_user_bal($user_id,$wallet,$price){

        $data['wallet_balance'] = $wallet - $price;
        //$data['accountid'] = $user_id;
        
       $this->db->where('accountid', $user_id);
        $this->db->update('account', $data);
 }
    public function insert_sent_message($user_id,$sender,$recipient,$message_bodySMS,$status,$cost){

        $data['user_id'] = $user_id;
         $data['recipients'] = $recipient;
        $data['message'] = $message_bodySMS;
        $data['sender'] = $sender;
        $data['cost'] = $cost;
       $data['date_sent'] = date('Y-m-d');
      
       $data['status'] = $status;
       
         $this->db->insert('messages', $data);
          
      
    }
     public function insert_transaction($user_id,$date,$price,$transaction_type,$status,$recipient,$orderid){

       $data['transaction_type'] = $transaction_type;
        $data['user_id'] = $user_id;
        $data['recipients'] = $recipient;
       $data['date_sent'] = $date;
      $data['cost'] = $price;
      $data['order_id'] = $orderid;
       $data['trans_status'] = $status;
       $this->db->insert('transaction', $data);
          
      
    }

     public function insert_buy_airtime($user_id,$phone_number,$network,$cost,$transaction_type,$status,$message){

        $data['user_id'] = $user_id;
         $data['transaction_type'] = $transaction_type;
        $data['recipients'] = $phone_number;
        $data['message'] = $message;
        $data['sender'] = $cost;
        $data['cost'] = $cost;
       $data['date_sent'] = date('Y-m-d');
      
       $data['trans_status'] = $status;
       
         $this->db->insert('transaction', $data);
          
      
    }
     public function add_upgrade_record($user_id,$amount,$bname,$bphone,$bemail,$bdomain,$Apackage,$payment_status,$invoice_number,$getemail){

        $data['user_id'] = $user_id;
         $data['amount_paid'] = $amount;
        $data['bphone'] = $bphone;
        $data['bname'] = $bname;
        $data['bemail'] = $bemail;
        $data['bdomain'] = $bdomain;
        $data['package'] = $Apackage;
        $data['paid'] = $payment_status;
        $data['reference_no'] = $invoice_number;
        $data['user_email'] = $getemail;
        
       $data['date_created'] = date('Y-m-d'); 
      $this->db->insert('upgrade_package', $data);
          
      
    }
 public function fetch_member($phone) {
        $this->db->where('accountid', $phone);
        $this->db->or_where('phone', $phone);
        $this->db->or_where('email', $phone);
        return $this->db->get('account');
     }


     public function create_new_item() {
        
     $data['title'] = html_escape($this->input->post('title'));
        $data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->input->post('title'))));
        
        $data['description'] = html_escape($this->input->post('description'));
    
       $data['active'] = '1';
        $data['date_added'] = date('Y-m-d');
         $this->db->insert('items', $data);
         $item_id  =   $this->db->insert_id();
        if ($_FILES['picture']['name'] != "") {
             move_uploaded_file($_FILES['picture']['tmp_name'], 'assets/img/services/'. $item_id . '.png');
       }
       
        $this->session->set_flashdata('flash_message',('New Item Added'));
      
    }
    public function account_opening($account_type = '', $email = '', $pass = '')
    {
        //$this->load->database();
        $from_name  = $this->db->get_where('settings',array('key' => 'app_name'))->row()->value;
       
     
            $from = $this->db->get_where('settings', array('key' => 'super_email'))->row()->value;
        
        
        $to   = $email;
        $query = $this->db->get_where($account_type, array('email' => $email));
         $to_name    = $query->row()->name;
                $url        = "<a href='".base_url()."login/'>".base_url()."login</a>";
                
                $sub        = $this->db->get_where('email_template', array('email_template_id' => 5))->row()->subject;
                $email_body      = $this->db->get_where('email_template', array('email_template_id' => 5))->row()->body;
       
            
            $email_body      = str_replace('[[to]]',$to_name,$email_body);
            $email_body      = str_replace('[[sitename]]',$from_name,$email_body);
            $email_body      = str_replace('[[account_type]]',$account_type,$email_body);
            $email_body      = str_replace('[[email]]',$to,$email_body);
            $email_body      = str_replace('[[password]]',$pass,$email_body);
            $email_body      = str_replace('[[url]]',$url,$email_body);
            $email_body      = str_replace('[[from]]',$from_name,$email_body);
             $logo           = base_url().'assets/img/konnect_logo.png';
             //$final_email    = str_replace('[[logo]]',$logo,$final_email);
             //$final_email = str_replace('[[body]]',$email_body,$final_email);
            $send_mail  = $this->send_email($from,$from_name,$to, $sub,$email_body);
            //$background = $this->db->get_where('ui_settings',array('type' => 'email_theme_style'))->row()->value;
          
            return $send_mail;
        
    }
    function send_email($from = '', $from_name = '', $to = '', $sub ='', $msg ='')
    {   
        $this->load->library('email');
        $this->email->set_newline("\r\n");
        $this->email->from($from, $from_name);
        $this->email->to($to);        
        $this->email->subject($sub);
        $this->email->message($msg);
        $config = array(
        'protocol'  => 'smtp',
        'crlf' => '\r\n',
        'newline' => '\r\n',
        'smtp_host' => 'ssl://smtp.gmail.com',
        'smtp_port' => 465,
        'smtp_user' => '',
        'smtp_pass' => '',
        'mailtype'  => 'html',
        'charset'   => 'utf-8'
    );
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->load->library('encrypt');
        if($this->email->send()){
            return true;
        }else{
            //echo $this->email->print_debugger();
            return false;
        }
        //echo $this->email->print_debugger();
    }

public function suspend_account($id){
    $details = $this->acc_user_details($id)->row_array();
    if($details['suspend'] == '0'){
    $data['suspend'] = '1';
    }else{
      $data['suspend'] = '0';  
    }
     $this->db->where('accountid', $id);
     $this->db->update('account', $data);
     return true;
 }
 public function update_user_token($phone,$token){

        $data['token'] = $token;
        $data['phone'] = $phone;
        $this->db->where('phone', $phone);
        $this->db->update('account', $data);
        return true;
 }
  
}
