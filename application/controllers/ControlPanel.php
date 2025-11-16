<?php
 include_once APPPATH . "/vendor/autoload.php";
 use Kreait\Firebase\Factory;
 use Kreait\Firebase\Auth;
defined('BASEPATH') OR exit('No direct script access allowed');
class ControlPanel extends CI_Controller {
	function __construct(){
    parent::__construct();
	$this->load->database();
     $this->load->library('cart');
   }
  public function index() {
	$page_data['page_name'] = "login";
    $page_data['page_title'] = 'Admin Login';
    $this->load->view('backend/login', $page_data);
    }
    
    public function googlemap($id) {
    $getCategories =  $this->db->get_where('events', array('id' => $id))->row_array();
     
	$page_data['page_name'] = "googlemap";
    $page_data['page_title'] = 'Google Map';
    $page_data['data'] = $getCategories;
    $this->load->view('backend/googlemap', $page_data);
    }
   
 public function chat() {
  	$page_data['page_name'] = "chat";
    $page_data['page_title'] = 'Ask PITCHIMAP';
     $this->load->view('backend/chat', $page_data);
    } 
    public function socialMediaEvents() {
  	$page_data['page_name'] = "socialMediaEvents";
    $page_data['page_title'] = 'Social Media Events';
    $getCategories =  $this->db->get_where('socialmedia');
   
    $page_data['data'] = $getCategories;
    $this->load->view('backend/index', $page_data);
    }
    
    public function adminLogin() 
{
        $email = $this->input->post('txtemail');
        $password = $this->input->post('txtpasskode');
        $result = $this->Admin_model->login($email);
        if(empty($result))
        {
            $this->session->set_flashdata('error_message', 'Email is invalid. Please try again!');
            redirect(adminController().'index');
        }
    if (password_verify($password, $result['password'])) 
    {
      //$this->session->set_userdata('member_session') == true;
     $this->session->set_userdata('super_admin_session', '1');
       $this->session->set_userdata('login_id', $result['admin_id']);
       $this->session->set_userdata('email', $result['email']);
      redirect(adminController().'dashboard');
    } else {
        $this->session->set_flashdata('error_message', 'Password is invalid. Please try again!');
        redirect(adminController().'index');
    }      
}
public function ChangeDateFormat($date)
{
      $tdate = str_replace('/', '-', $date);
      return $tdate;
    // $currentDate = date('Y-m-d', strtotime($date));
}
public function salesAnalysis()
{
if ($this->session->userdata('super_admin_session') == true) {
    
    if(isset($_POST['search']))
    {
    $date = $this->escapeString('fromDate');
    }else{
     $date  = date('Y-m-d');
    }
    $page_data['services'] = $this->db->get_where('sub_module',array('status' => 1));
    $page_data['page_name'] = "salesAnalysis";
    $page_data['date'] = $this->ChangeDateFormat($date);
    $page_data['page_title'] = 'Sales Analysis '.$this->ChangeDateFormat($date);
    $this->load->view('backend/index', $page_data);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  } 
}

public function updatekyc_approve()
 {
    if ($this->session->userdata('super_admin_session') == false) {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');    
    redirect(adminController().'logout');
    }
    $userid = $this->escapeString('userid');
    $details = $this->db->get_where('account', array('accountid' =>$userid))->row_array();
    if($details['kyc_approve'] == '1'){
        $data['kyc_approve'] = '0';
        }else{
        $data['kyc_approve'] = '1';
        $data['bvn'] = '123';
        }
    $this->db->where('accountid',$userid);
    $this->db->update('account',$data);
    $this->session->set_flashdata('success_alert', 'Record updated successfully');
    echo '1';
 }
public function updateApiaccess()
 {
    if ($this->session->userdata('super_admin_session') == false) {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');    
    redirect(adminController().'logout');
    }
    $userid = $this->escapeString('userid');
    $details = $this->db->get_where('account', array('accountid' =>$userid))->row_array();
    if($details['api_access'] == '1'){
        $data['api_access'] = '0';
        }else{
        $data['api_access'] = '1';
        $data['package_id'] = '3';
        }
    $this->db->where('accountid',$userid);
    $this->db->update('account',$data);
    $this->session->set_flashdata('success_alert', 'Record updated successfully');
    echo '1';
 }
public function updateActivationStatus()
 {
    if ($this->session->userdata('super_admin_session') == false) {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');    
    redirect(adminController().'logout');
    }
    $userid = $this->escapeString('userid');
    $details = $this->db->get_where('account', array('accountid' =>$userid))->row_array();
    if($details['activation_status'] == '1'){
        $data['activation_status'] = '0';
        }else{
        $data['activation_status'] = '1';
        }
    $this->db->where('accountid',$userid);
    $this->db->update('account',$data);
    $this->session->set_flashdata('success_alert', 'Record updated successfully');
    echo '1';
 }
public function uploadAssets(){
     if ($this->session->userdata('super_admin_session') == true) {
            $this->Admin_model->uploadAssets();
            $this->session->set_flashdata('success_msg', 'Changes has been saved');
            redirect(adminController().'siteSettings');
        }else {
      $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
        redirect(adminController().'index');
  }
}
public function send_broadcast(){
    if ($this->session->userdata('super_admin_session') == true) {
        $id = '1';
        $subject = $this->escapeString('subject');
        $body = $this->input->post('message');
        $status = $this->input->post('status');
    
        $data['title'] = $subject;
        $data['body'] = $body;
        $data['status'] = $status;
        $this->db->where('id',$id);
        $this->db->update('broadcast',$data);
        $get_all_registered_members = $this->db->get('account');
      //  if($being_sent_to !='4'){
            foreach ($get_all_registered_members->result_array()  as $members){
            $data_user['broadCastEmailStatus'] = '1';
                
            $this->db->where('accountid',$members['accountid']);
                
            $this->db->update('account',$data_user);
              
        }
          $this->session->set_flashdata('success_msg', 'Message has been scheduled');
       redirect(adminController().'siteBroadcast');
       
        
    }else{
       redirect(adminController().'index');
    }
}
public function update_site(){
     if ($this->session->userdata('super_admin_session') == true) {
            $this->Admin_model->update_system_settings();
            $this->session->set_flashdata('success_msg', 'Changes has been saved');
            redirect(adminController().'siteSettings');
        }else {
      $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
        redirect(adminController().'index');
  }
}


public function logout() {
      $this->admin_session_destroy();
    redirect(adminController().'index');
    }
    public function admin_session_destroy() {
      $this->session->unset_userdata('login_id');
      $this->session->unset_userdata('email');
     if ($this->session->userdata('super_admin_session') == 1) {
        $this->session->unset_userdata('super_admin_session');
      }
    }
public function dashboard() {
	if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "dashboard";
        $page_data['page_title'] = 'Dashboard';
       $this->load->view('backend/index', $page_data);
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }
}
public function depositDetails($id)
{
    if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "depositDetails";
        $page_data['list'] = $this->db->get_where('deposits',array('id'=>$id))->row_array();
        $page_data['page_title'] = 'Wallet Deposits Details';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}




public function updateactivities()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $details = $this->db->get_where('adsactivities',array('id'=>$id))->row_array();
    if($details['status'] == '1')
    {
        $data['status'] = '0';
    }else{
        $data['status'] = '1';
    }
    $this->db->where('id',$id);
    $this->db->update('adsactivities',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
      redirect(adminController().'adsactivities');
  }  
}


public function addActivities()
{
    if ($this->session->userdata('super_admin_session') == true) {
       $adsactivities = $this->escapeString('adsactivities');
        $data['activities'] = $adsactivities;
        $this->db->insert('adsactivities', $data);
        $this->session->set_flashdata('success_alert',('Item added successfully'));
       
    redirect(adminController().'adsactivities');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function deleteAdsactivities($id)
{
    $this->db->where('id',$id);
    $this->db->delete('adsactivities');
     $this->session->set_flashdata('success_alert',('Item deleted successfully'));
    redirect(adminController().'adsactivities');
}
public function adsactivities()
{
    if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "adsactivities";
        $page_data['list'] = $this->db->get_where('adsactivities');
        $page_data['page_title'] = 'Events Activities';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}

public function updateAllEvents($status,$source)
{
    $data['status'] = $status;
    $this->db->where('source',$source);
    $this->db->update('events',$data);
    return true;
    
}
public function updatesocialMedia()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $status = '1';
    $details = $this->db->get_where('socialmedia',array('id'=>$id))->row_array();
   $source = $details['id'];
    if($details['status'] == '1')
    {
        $status = '0';
        
        $data['status'] = $status;
    }else{
        $status = '1';
        $data['status'] = '1';
    }
    $this->db->where('id',$id);
    $this->db->update('socialmedia',$data);
    $this->updateAllEvents($status,$source);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
      redirect(adminController().'socialMediaEvents');
  }  
}



public function updateadstype()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $details = $this->db->get_where('adstype',array('id'=>$id))->row_array();
    if($details['status'] == '1')
    {
        $data['status'] = '0';
    }else{
        $data['status'] = '1';
    }
    $this->db->where('id',$id);
    $this->db->update('adstype',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
      redirect(adminController().'adstype');
  }  
}
public function addContactinfo()
{
    if ($this->session->userdata('super_admin_session') == true) {
       $title = $this->escapeString('title');
        $data['title'] = $title;
        $data['value'] =  $this->escapeString('data');;
        $this->db->insert('contactinfo', $data);
        $this->session->set_flashdata('success_alert',('Item added successfully'));
       
    redirect(adminController().'contactinfo');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function updateContactinfo()
{
    if ($this->session->userdata('super_admin_session') == true) {
       $title = $this->escapeString('title');
       $id = $this->escapeString('id');
        $data['title'] = $title;
        $data['value'] =  $this->escapeString('data');
        $this->db->where('id', $id);
        $this->db->update('contactinfo', $data);
        $this->session->set_flashdata('success_alert',('Item update successfully'));
       
    redirect(adminController().'contactinfo');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}

public function editActivities()
{
    if ($this->session->userdata('super_admin_session') == true) {
       $adstype = $this->escapeString('activities');
       $id = $this->escapeString('id');
        $data['type'] = $adstype;
        $this->db->where('id', $id);
        $this->db->update('adsactivities', $data);
        $this->session->set_flashdata('success_alert',('Item updated successfully'));
       
    redirect(adminController().'adsactivities');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function editAdstype()
{
    if ($this->session->userdata('super_admin_session') == true) {
       $adstype = $this->escapeString('adstype');
       $id = $this->escapeString('id');
        $data['type'] = $adstype;
        $this->db->where('id', $id);
        $this->db->update('adstype', $data);
        $this->session->set_flashdata('success_alert',('Item updated successfully'));
       
    redirect(adminController().'adstype');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function addAdstype()
{
    if ($this->session->userdata('super_admin_session') == true) {
       $adstype = $this->escapeString('adstype');
        $data['type'] = $adstype;
        $this->db->insert('adstype', $data);
        $this->session->set_flashdata('success_alert',('Item added successfully'));
       
    redirect(adminController().'adstype');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}

public function addSocial()
{
    if ($this->session->userdata('super_admin_session') == true) {
       $social = $this->escapeString('social');
        $data['social'] = $social;
        $this->db->insert('socialmedia', $data);
        $this->session->set_flashdata('success_alert',('Item added successfully'));
       
    redirect(adminController().'socialMediaEvents');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}


public function deleteAdstype($id)
{
    $this->db->where('id',$id);
    $this->db->delete('adstype');
     $this->session->set_flashdata('success_alert',('Item deleted successfully'));
    redirect(adminController().'adstype');
}

public function contactinfo()
{
    if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "contactinfo";
        $page_data['list'] = $this->db->get_where('contactinfo');
        $page_data['page_title'] = 'Contact and Social Links Information';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}

public function adstype()
{
    if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "adstype";
        $page_data['list'] = $this->db->get_where('adstype');
        $page_data['page_title'] = 'Events Categories';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}

public function searchTransaction()
{
    if ($this->session->userdata('super_admin_session') == true) {
    $parmeter="";$parameter_value="";$submodule="";$transactiondate="";$condate="";$status="";
    if((isset($_POST['parmeter']))&&(!empty($_POST['parmeter']))){
        $parmeter = $this->escapeString('parmeter');
    }
    if((isset($_POST['parameter_value']))&&(!empty($_POST['parameter_value']))){
        $parameter_value = $this->escapeString('parameter_value');
    }
    if((isset($_POST['submodule']))&&(!empty($_POST['submodule']))){
        $submodule = $this->escapeString('submodule');
    }
    if((isset($_POST['status']))&&(!empty($_POST['status']))){
        $status = $this->escapeString('status');
    }
    if((isset($_POST['transactiondate']))&&(!empty($_POST['transactiondate']))){
        $transactiondate = $this->escapeString('transactiondate');
        $condate = date("Y-m-d", strtotime($transactiondate));
    }
    //echo $status;
    //exit;
    $search = $this->Admin_model->searchTransaction($parmeter,$parameter_value,$submodule,$condate,$status);
    $page_data['page_name'] = "transactions";
    $page_data['history'] = $search;
    $page_data['page_title'] = 'Transactions';
    $this->load->view('backend/index', $page_data);
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }
}
public function create_session($result)
  {
      //$this->session_destroy();
       $session_id = sha1(time());
        $myTime = date('Y-m-d H:i:s', time());
        $ip_address = $this->input->ip_address(); 
      $this->Auth_model->update_session_id($result['accountid'],$session_id); 
      $session_data = array(
                    'loginId' => $session_id,
                    'session_id' => session_id(),
                    'loginName' => $result['fullname'],
                    'loginPhone' => $result['phone'],
                    'member_session' => TRUE
                );
        $this->session->set_userdata($session_data);
        return $session_data;
  }
public function loginas($userid)
{
    if ($this->session->userdata('super_admin_session') == true) {
        $udetails = get_user_info($userid);
        $this->create_session($udetails);
        redirect('dashboard');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function loginHistory()
{
   if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "loginHistory";
        $date = date('Y-m-d');
        if((isset($_GET['user_id']))&&(!empty($_GET['user_id']))){
        $page_data['data'] = $this->db->get_where('daily_login',array('user_id'=>$_GET['user_id']));
        }else{
        $page_data['data'] = $this->db->group_by('user_id')->get_where('daily_login',array('date_login'=>$date));    
        }
        $page_data['page_title'] = 'Login History';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}
public function reprrocessTransaction($id)
{
    if ($this->session->userdata('super_admin_session') == true) {
        $this->Apiselector->send_transaction($id,$isadmin='yes');
         $this->session->set_flashdata('success_alert', 'Record successfully updated');
     redirect(adminController().'trans_details/'.$id);
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}
public function reverse($id)
{
    if ($this->session->userdata('super_admin_session') == true) {
        $getDetails = $this->db->get_where('transactions',array('id'=>$id))->row_array();
        $reversedCode = $this->db->get_where('status_code',array('value'=>'reversed'))->row_array();
        $userDetails = get_user_info($getDetails['user_id']);
        $newbal = ($userDetails['wallet_balance'] + $getDetails['charge']);
        $sub = 'Trasaction ID ('.$getDetails['order_id'].') Reversed';
        $body = 'Dear '.$userDetails['fullname'].', <br/><br/> This is to nofity you that transaction with reference ID: '.$getDetails['order_id'].' has just been reversed. See details below <br/><br/>
        Description: '.$getDetails['description'].' <br/> Beneficiary: '.$getDetails['recipient'].' <br/> Transaction Date: '.$getDetails['date_added'].'<br/> Reversed Date: '.date('Y-m-d').'<br/> Previous Balance: NGN '.$userDetails['wallet_balance'].' <br/>New Balance: '.$newbal.' <br/>Transaction Ref: '.$getDetails['order_id'].' <br/><br/>Regards<br/> '.get_settings('app_name').' Team';
     $this->User_model->do_CreditWallet($userDetails['accountid'],$getDetails['charge'],$wallet_type='wallet_balance');
     $verify_transaction = $this->db->select('*')->from('complaints')->where('reference_id',$getDetails['order_id'])->limit('1')->get();
     if($verify_transaction->num_rows() > 0){$this->Admin_model->update_complaint_status($getDetails['order_id'],$response="Reversed"); }
     $data['status'] = $reversedCode['code'];$this->db->where('id',$id);$this->db->update('transactions',$data);
     $this->send_mail($userDetails['email'],$sub,$body);
     $this->session->set_flashdata('success_alert', 'Record successfully updated');
     redirect(adminController().'trans_details/'.$id);
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }   
}
public function updateTransactionStatus()
{
    $tid = $this->escapeString('tid');
    $status = $this->escapeString('status');
    $data['status'] = $status;$this->db->where('id',$tid);$this->db->update('transactions',$data);
    $this->session->set_flashdata('success_alert', 'Record successfully updated');
    redirect(adminController().'trans_details/'.$tid);
}
public function transactionbySessionID($session_id)
{
      if ($this->session->userdata('super_admin_session') == true) {
    $page_data['history'] = $this->db->get_where('transactions',array('session_id'=>$session_id));
   $page_data['page_name'] = "transactions";
   $page_data['page_title'] = 'Transactions';
   $this->load->view('backend/index', $page_data); 
      }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }   
}
public function transactions()
{
   if ($this->session->userdata('super_admin_session') == true) {
       if((isset($_GET['user_id']))&&(!empty($_GET['user_id']))){
         $page_data['history'] = $this->Admin_model->get_all_transactionuser($_GET['user_id']); 
      }else{
    $page_data['history'] = $this->Admin_model->get_all_transaction(); 
      }
	$page_data['page_name'] = "transactions";
       $page_data['page_title'] = 'Transactions';
         $this->load->view('backend/index', $page_data);		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}
public function changePassword()
{
   if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "changePassword";
        $page_data['page_title'] = 'Change Password';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }      
}
public function fetchDeposits($status)
{
    $this->db->where('status',$status);
    $this->db->order_by('id','DESC');
    $query = $this->db->get('subscriptionPayment');
    return $query;
}
public function deposits($status)
{
   if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "deposits";
        $page_data['data'] = $this->fetchDeposits($status);
        $page_data['page_title'] = 'Subscription Payments';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}
public function paymentGateways()
{
   if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "paymentGateways";
        $page_data['page_title'] = 'Payment Gateways';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}
public function deleteEmptyRow()
{
    $mpty = '';
    $this->db->where('email',$mpty);
    $this->db->delete('account');
    echo 'All done';
}
 public function deleteUserFirebaseAccount($uidToDelete)
  {
$credentialsFilePath = APPPATH . "pitchimap-firebase-adminsdk-fbsvc-335ff64d76.json";
$factory = (new Factory)->withServiceAccount($credentialsFilePath);
$auth = $factory->createAuth(); // Use createAuth() to get the Auth instance
   $auth->deleteUser($uidToDelete);
  return true;
  }
public function deleteUserAccount($id)
{
    if ($this->session->userdata('super_admin_session') == true) {
        $userDetails = $this->db->get_where('account', array('accountid' => $id));
        
        if($userDetails->num_rows() < 1)
        {
             $this->session->set_flashdata('error_message', 'Account not found');
             redirect(adminController().'customers/1');
        }
        $row = $userDetails->row_array();
        $this->deleteUserFirebaseAccount($row['firebase_auth']);
       $this->db->where('user_id',$id);
        $this->db->delete('deposits');
        
        $this->db->where('user_id',$id);
        $this->db->delete('transactions');
        
        $this->db->where('accountid',$id);
        $this->db->delete('account');
       
       $this->session->set_flashdata('success_alert', 'Record successfully deleted');
    redirect(adminController().'customers/1');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function deleteAdmin($id)
{
    if ($this->session->userdata('super_admin_session') == true) {
       $this->db->where('admin_id',$id);
        $this->db->delete('admin');
        $this->session->set_flashdata('success_alert', 'Record successfully deleted');
    redirect(adminController().'adminUser');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function deleteBlacklist($id)
{
    if ($this->session->userdata('super_admin_session') == true) {
       $this->db->where('id',$id);
        $this->db->delete('blacklist');
        $this->session->set_flashdata('success_alert', 'Record successfully deleted');
    redirect(adminController().'blacklist');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}

public function updateinlineBanner()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $details = $this->db->get_where('inlinebanner',array('id'=>$id))->row_array();
    if($details['status'] == '1')
    {
        $data['status'] = '0';
    }else{
        $data['status'] = '1';
    }
    $this->db->where('id',$id);
    $this->db->update('inlinebanner',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
      redirect(adminController().'inlinebanner');
  }  
}


public function updateBanner()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $details = $this->db->get_where('mobile_slider',array('id'=>$id))->row_array();
    if($details['status'] == '1')
    {
        $data['status'] = '0';
    }else{
        $data['status'] = '1';
    }
    $this->db->where('id',$id);
    $this->db->update('mobile_slider',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
      redirect(adminController().'banner');
  }  
}
public function removeApiProvider($id)
{
    $this->db->where('id',$id);
    $this->db->delete('api_provider');
     $this->session->set_flashdata('success_alert',('Provider deleted successfully'));
    redirect(adminController().'apiProviders');
}
public function deleteinlineBanner($id)
{
    $this->db->where('id',$id);
    $this->db->delete('inlinebanner');
     $this->session->set_flashdata('success_alert',('Slide deleted successfully'));
    redirect(adminController().'inlinebanner');
}

public function deleteSocial($id)
{
    $this->db->where('id',$id);
    $this->db->delete('socialmedia');
     $this->session->set_flashdata('success_alert',('Social media deleted successfully'));
    redirect(adminController().'socialMediaEvents');
}
public function deleteSlider($id)
{
    $this->db->where('id',$id);
    $this->db->delete('mobile_slider');
     $this->session->set_flashdata('success_alert',('Slide deleted successfully'));
    redirect(adminController().'banner');
}
public function random_strings($length_of_string)
{
$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
return substr(str_shuffle($str_result),
                   0, $length_of_string);
}
public function editBanner()
{
    if ($this->session->userdata('super_admin_session') == true) {
        if($_FILES['imagefile']['name'] != "") {
        $fileRename = $this->random_strings(6);
        
        $fileName = $_FILES['imagefile']['name'];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $bid = $this->escapeString('bid');
        $path = 'assets/images/banner/'. $fileRename.$ext;
        $link = base_url().$path;
        move_uploaded_file($_FILES['imagefile']['tmp_name'], $path);
        $data['image_url'] = $link;
        $this->db->where('id',$bid);
        $this->db->update('mobile_slider', $data);
        $this->session->set_flashdata('success_alert',('Slide updated successfully'));
       }else{
           $this->session->set_flashdata('error_message',('Image file not found'));
       }
    redirect(adminController().'banner');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function addBanner()
{
    if ($this->session->userdata('super_admin_session') == true) {
        if($_FILES['imagefile']['name'] != "") {
        $fileRename = $this->random_strings(6);
        
        $fileName = $_FILES['imagefile']['name'];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        
        $path = 'assets/images/banner/'. $fileRename.$ext;
        $link = base_url().$path;
        move_uploaded_file($_FILES['imagefile']['tmp_name'], $path);
        $data['image_url'] = $link;
        $this->db->insert('mobile_slider', $data);
        $this->session->set_flashdata('success_alert',('Slide added successfully'));
       }else{
           $this->session->set_flashdata('error_message',('Image file not found'));
       }
    redirect(adminController().'banner');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}

public function editinlineBanner()
{
    if ($this->session->userdata('super_admin_session') == true) {
        if($_FILES['imagefile']['name'] != "") {
        $fileRename = $this->random_strings(6);
        $fileName = $_FILES['imagefile']['name'];
         $bid = $this->escapeString('bid');
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $path = 'assets/images/inbanner/'. $fileRename.$ext;
        $link = base_url().$path;
        move_uploaded_file($_FILES['imagefile']['tmp_name'], $path);
        $data['fileurl'] = $link;
        $this->db->where('id',$bid);
        $this->db->update('inlinebanner', $data);
        $this->session->set_flashdata('success_alert',('Slide added updated'));
       }else{
           $this->session->set_flashdata('error_message',('Image file not found'));
       }
    redirect(adminController().'inlinebanner');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}

public function addinlineBanner()
{
    if ($this->session->userdata('super_admin_session') == true) {
        if($_FILES['imagefile']['name'] != "") {
        $fileRename = $this->random_strings(6);
        $fileName = $_FILES['imagefile']['name'];
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $path = 'assets/images/inbanner/'. $fileRename.$ext;
        $link = base_url().$path;
        move_uploaded_file($_FILES['imagefile']['tmp_name'], $path);
        $data['fileurl'] = $link;
        $this->db->insert('inlinebanner', $data);
        $this->session->set_flashdata('success_alert',('Slide added successfully'));
       }else{
           $this->session->set_flashdata('error_message',('Image file not found'));
       }
    redirect(adminController().'inlinebanner');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}


public function addAdminUser()
{
    if ($this->session->userdata('super_admin_session') == true) {
        $data['email'] = $this->escapeString('email');
        $data['password'] = password_hash($this->escapeString('password'), PASSWORD_BCRYPT);
        $data['account_name'] = $this->escapeString('accountName');
        $this->db->insert('admin',$data);
        $this->session->set_flashdata('success_alert', 'Record successfully updated');
    redirect(adminController().'adminUser');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function doPasswordUpdate()
{
if ($this->session->userdata('super_admin_session') == true) {
    $current_password =$this->escapeString('oldpassword');
    $new_password = $this->escapeString('newPassword');
    $confirm_password = $this->escapeString('confirmPassword');
    $adetails = $this->Admin_model->user_details($this->session->userdata('login_id'))->row_array();
    if($new_password != $confirm_password)
    {
     $this->session->set_flashdata('error_message', 'New password and confirm new password does not match');
       redirect(adminController().'changePassword');
        exit;    
    }
   if(password_verify($current_password, $adetails['password']))
    {
        $data['password'] = password_hash($current_password, PASSWORD_BCRYPT);
         $this->db->where('admin_id', $adetails['admin_id']);
        $this->db->update('admin', $data);
         $this->session->set_flashdata('success_msg', 'Your Account Password has been successfully updated');
       redirect(adminController().'changePassword');
        exit; 
     
    }else{
       $this->session->set_flashdata('error_message', 'Current password is invalid');
     redirect(adminController().'changePassword');
        exit; 
    }
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function addBlacklist()
{
    if ($this->session->userdata('super_admin_session') == true) {
        $data['phonenumber'] = $this->escapeString('phonenumber');
        $this->db->insert('blacklist',$data);
        $this->session->set_flashdata('success_alert', 'Record successfully updated');
    redirect(adminController().'blacklist');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function suspendedUsers()
{
   if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "suspendedUsers";
        $page_data['page_title'] = 'Suspended Customers';
        $page_data['data'] = $this->db->get_where('account',array('suspend'=>'1'));
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}
public function blacklist()
{
   if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "blacklist";
        $page_data['page_title'] = 'Blacklist';
        $page_data['data'] = $this->db->get('blacklist');
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}
public function plans()
{
   if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "plans";
        $page_data['page_title'] = 'Plan Management';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}
public function searchCustomer()
{
   if ($this->session->userdata('super_admin_session') == true) {
       if((isset($_POST['searchBy']))&&(!empty($_POST['searchBy']))){
       $searchBy = $this->escapeString('searchBy');
       $inputvalue = $this->escapeString('inputvalue');
       $page_data['data'] = $this->db->get_where('account', array($searchBy =>$inputvalue));
       }else{
           $page_data['data'] = fetchUserbyStatus($status='');
       }
        $page_data['page_name'] = "customers";
        
        $page_data['page_title'] = 'Customer Management';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }       
}
public function customers($status)
{
    if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "customers";
        $page_data['data'] = fetchUserbyStatus($status);
        $page_data['page_title'] = 'Customer Management';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}
public function customerDetails($id)
{
     if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "customerDetails";
        $page_data['data'] = get_user_info($id);
        $page_data['page_title'] = 'Customer Details';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}
public function fetchCablePlans()
{
    $this->db->order_by('cabletv','Asc');
    $query = $this->db->get('cabletv');
    return $query;
}

public function fetchExams()
{
    $this->db->where('module_id','8');
    $query = $this->db->get('network');
    return $query;
}
public function cablePlans()
{
   if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "cablePlans";
        $page_data['plans'] = $this->fetchCablePlans();
        $page_data['page_title'] = 'Cable Plans Management';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}
public function exampins()
{
   if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "exampins";
        $page_data['plans'] = $this->fetchExams();
        $page_data['page_title'] = 'Exam Management';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}
public function dataplans()
{
   if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "dataplans";
        $page_data['network_type'] = $this->db->where('parent_id','2')->get('sub_module')->result_array();
        if((isset($_GET['type'])) &&(!empty($_GET['type']))){
            $type = $_GET['type'];
        $page_data['plans'] = $this->db->where('subtype',$type)->get('databundles');
        }else{
            $page_data['plans'] = $this->db->order_by('subtype')->get('databundles');
        }
        $page_data['page_title'] = 'Data Plans Management';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }     
}

public function setService($id)
{
  if ($this->session->userdata('super_admin_session') == true) {
      $details = get_SubmodulebyModule($id);
      $moduleDetails = get_module($id);
      $page_data['details'] = $details;
        $page_data['page_name'] = "setService";
        $page_data['moduleDetails'] = $moduleDetails;
        $page_data['page_title'] = $moduleDetails['module'].' Settings';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function siteSettings()
{
  if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "siteSettings";
        $page_data['page_title'] = 'Site Settings';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function generalSettings()
{
  if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "generalSettings";
        $page_data['page_title'] = 'General Settings';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function updateEvent()
{
     $title = $this->escapeString('event_title');
    $description = $this->escapeString('event_description');
    $event_date = $this->escapeString('event_date');
    $event_location = $this->escapeString('event_location');
    $event_time = $this->escapeString('event_time');
    if((empty($title)) || (empty($description)) || (empty($event_date)) || (empty($event_location)) || (empty($event_time)))
    {
         $this->session->set_flashdata('error_message', 'Please fill all required fields');
          redirect(adminController().'events');
    }
    if($_FILES['event_img']['name'] != "") {
        $fileRename = $this->random_strings(6);
        $path = 'assets/images/event/'. $fileRename . '.jpg';
        $link = base_url().$path;
        move_uploaded_file($_FILES['event_img']['tmp_name'], $path);
        $data['event_img_link'] = $link;
      }
       
    $reference = $this->escapeString('reference');
    //$data['user_id'] = '0';
    $data['event_title'] = $this->escapeString('event_title');
    $data['event_description'] = $this->escapeString('event_description');
    $data['event_date'] = $this->escapeString('event_date');
    $data['event_location'] = $this->escapeString('event_location');
    $data['event_time'] = $this->escapeString('event_time');
    $data['approval_status'] = '1';
    $data['reference'] = $reference;
    $this->db->where('reference', $reference);
    $this->db->update('events', $data);
       $this->session->set_flashdata('success_msg', 'Event successfully updated');
     redirect(adminController().'events');
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
        return $response['access_token'] ?? null;
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
public function saveNotification($user_id,$title,$message)
{
    $data['to_user'] = $user_id;
    $data['title'] = $title;
    $data['message'] = $message;
    $this->db->insert('notifications',$data);
    return true;
    
}
 public function sendfcm($user_id,$title,$message)
 {
 //$serverKey = get_settings('firebaseserverkey');
 $getDeviceID = $this->db->get_where('devicetoken', array('user_id' => $user_id));
 $this->saveNotification($user_id,$title,$message);
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
        //$postD = '{ "message": {"token": "'.$tokens.'","webpush": {"notification": {"title": "'.$title.'","body": "'.$message.'","icon": "'.$iconurl.'"}}}}';
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
 
 public function fetchEvent($id)
 {
     $details = $this->db->get_where('events', array('reference' => $id))->row_array();
     return $details;
 }
public function approveEvent($reference)
{
    $data['approval_status'] = '1';
    $this->db->where('reference', $reference);
    $this->db->update('events', $data);
    $eventDetails = $this->fetchEvent($reference);
    $fcmtitle = 'Event Approved';
    $fcmmessage = 'Your submitted event has been approved';
    $this->sendfcm($eventDetails['user_id'],$fcmtitle,$fcmmessage);
     $this->session->set_flashdata('success_msg', 'Event successfully updated');
     redirect(adminController().'events');
}
 function remove_files_and_folders($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        $this->remove_files_and_folders($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
            return true;
        }else{
            return true;
        }
    }
public function deleteEvent($reference)
{
  
    
    $eventDetails = $this->fetchEvent($reference);
    $fcmtitle = 'Event Deleted';
    $fcmmessage = 'Your submitted event has been deleted';
    $getReferenceid = $eventDetails['reference'];
    $pathtoDelete = 'assets/app/eventsFiles/'.$getReferenceid;
    //delete folder path with the event reference
   $this->remove_files_and_folders($pathtoDelete);
    $this->sendfcm($eventDetails['user_id'],$fcmtitle,$fcmmessage);
    
      $this->db->where('reference', $reference);
    $this->db->delete('events');
    
     $this->session->set_flashdata('success_msg', 'Event successfully deleted');
     redirect(adminController().'events');
}
public function declineEvent()
{
    $reference = $this->escapeString('reference');
    $reason = $this->escapeString('reason');
    $data['approval_status'] = '2';
    $this->db->where('reference', $reference);
    $this->db->update('events', $data);
    
     $eventDetails = $this->fetchEvent($reference);
    $fcmtitle = 'Event Declined';
    $fcmmessage = $reason;//'Your submitted event has been declined';
    $this->sendfcm($eventDetails['user_id'],$fcmtitle,$fcmmessage);
    
    
     $this->session->set_flashdata('success_msg', 'Event successfully updated');
     redirect(adminController().'events');
}


public function postEvent()
{
    if ($this->session->userdata('super_admin_session') == true) {
         $this->load->library('cart');
       $page_data['page_name'] = "postEvent";
       
        $page_data['page_title'] = 'Add New Event';
        $this->load->view('backend/index', $page_data);
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
 public function uniqueidwithlenght($lenght)
  {
    $un = substr(number_format(time() * rand(),0,'',''),0,$lenght);
    return $un;
  }
 public function trim_and_return_json_upload($eventid,$untrimmed_array = [])
    {
        if (!is_array($untrimmed_array)) {
            $untrimmed_array = [];
        } 
        $trimmed_array = array();
        $i = 0;
        if (sizeof($untrimmed_array) > 0) {
         //   foreach ($untrimmed_array as $row) {
                
         foreach ($untrimmed_array['name'] as $key => $row) {
    
    
                if ($row != "") {
                   
                        $extension = '.png';
                    
                  //  $normalFormart = $row->fileData;
                    $uniqid = $this->uniqueidwithlenght('5');
                    //$decoded_image_data = base64_decode($base64_image_string);
                     
                     $file_path = 'assets/app/eventsFiles/'.$uniqid.$extension; // Specify the desired file path and extension
                    // file_put_contents($file_path, $normalFormart);
                       
                    move_uploaded_file($row, $file_path);
                
              
                   $data['filetype'] = $extension;
                   $data['fileName'] = $uniqid.$extension;
                   $data['fileData'] = base_url().'assets/app/eventsFiles/'.$uniqid.$extension;
                   
                   
                    $imgURL = base_url().'assets/app/eventsFiles/'.$uniqid.$extension;

                  $dataImag = $imgURL;
                   
                   $dataR['fileurl'] =   base_url().'assets/app/eventsFiles/'.$uniqid.$extension;
                   $dataR['file_name'] = $uniqid.$extension;
                   $dataR['file_type'] =  $extension;
                   $dataR['rawFile'] = $row->fileData;
                   $dataR['event_id'] = $eventid;
                   $this->db->insert('eventFiles', $dataR);
                   
                    array_push($trimmed_array, $dataImag);
                }
            }
        }
        return json_encode($trimmed_array);
    }
    
  public function liveLocationSearch()
  {
     // echo ['Tokyo', 'Delhi', 'Shanghai', 'Mumbai', 'Sao Paolo', 'Beijing', 'Mexico City','New York City', 'Rio de Janeiro', 'Taipei', 'London'];//'Lagos';
     //$searchTerm = urlencode('No.13 Government House Lokoja, Kogi State.'); //$_GET['searchTerm'];
    $searchTerm = urlencode($_GET['searchTerm']);
    $url = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input='.$searchTerm.'&fields=formatted_address&inputtype=textquery&key=AIzaSyBYqOVZ7mKhMCO_VFV2_7SATdAHZrQGruw';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
//print_r($resp);

curl_close($curl);
$result = json_decode($resp, true);
$response = $result['candidates'];
foreach($result as $each){
$postData = $result['candidates'][0]['formatted_address'];
}
//echo json_encode($postData); //json_encode($result['candidates'][0]['formatted_address']);
echo $result['candidates'][0]['formatted_address'];
  }
public function removeFile()
{
    $files = [];
    $reference = $_GET['reference'];
    $filetoremove = $_GET['file'];
    
    $getDetails = $this->db->get_where('events', array('reference' => $reference))->row_array();
    $allFiles = json_decode($getDetails['event_img_link']);
    $key = array_search($filetoremove, $allFiles);
    if ($key !== false) {
        unset($allFiles[$key]);
        $files = array_values($allFiles);
    }
    
    $data['event_img_link'] = json_encode($files);
    $this->db->where('reference',$reference);
    $this->db->update('events',$data);
     $this->session->set_flashdata('success_msg', 'Item removed');
          redirect(adminController().'event_details/'.$reference);
}
public function doEventupdate()
{
   
   $event_title = $this->escapeString('event_title');
   $event_description = $this->escapeString('event_description');
   $event_date = $this->escapeString('event_date');
   
    $endDate = $this->escapeString('endDate');
    
   $event_time = $this->escapeString('event_time');
   $close_time = $this->escapeString('close_time');
   $adstype = $this->escapeString('adstype');
  // $state = $this->escapeString('state');
   $event_location = $this->escapeString('event_location');
   
   $event_img = $this->escapeString('event_img');
   $videos = $this->escapeString('videos');
   $contactName = $this->escapeString('contactName');
   $contactPhone = $this->escapeString('contactPhone');
   
   $referenceid = $this->escapeString('reference');//$this->uniqueid();
    $uploadedFileNames = [];
    $activities = $this->escapeString('activities');
   
    if(isset($_FILES['event_img'])) {
        
         if (!file_exists('assets/app/eventsFiles/'.$referenceid)) {
                        mkdir('assets/app/eventsFiles/'.$referenceid, 0777, true);
                    }
                    
                    
    foreach ($_FILES['event_img']['name'] as $key => $fileName) {
        $extension = '.png';
        $uniqid = $this->uniqueidwithlenght('5');
     $uploadDirectory = 'assets/app/eventsFiles/'.$referenceid.'/'.$uniqid.$extension; 
    
        // Check for upload errors
        if ($_FILES['event_img']['error'][$key] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['event_img']['tmp_name'][$key];
            $targetPath = $uploadDirectory;

            // Move the uploaded file from its temporary location to the target directory
            if (move_uploaded_file($tmpName, $targetPath)) {
                $uploadedFileNames[] = base_url().'assets/app/eventsFiles/'.$referenceid.'/'.$uniqid.$extension; // Add the filename to the array
            } else {
               $this->session->set_flashdata('error_message', 'Fail to upload files');
                    redirect(adminController().'events');
            }
        } else {
            $this->session->set_flashdata('error_message', 'Error uploading file ' . htmlspecialchars($fileName) . ': ' . $_FILES['uploaded_files']['error'][$key]);
          redirect(adminController().'events');
        }
    }
    
    }else{
         $this->session->set_flashdata('error_message', 'Upload at least 1 image file');
          redirect(adminController().'events');
    }
$eventchild = array();
$getEventItems = $this->db->get('listitemstemp');
if($getEventItems->num_rows() > 0)
{
    foreach($getEventItems->result_array() as $list)
    {
        $eventchild[] = $list;
    }
 $this->db->where('status', '1');
 $this->db->delete('listitemstemp');
}


  $event_time = date('g:i A', strtotime($event_time));


    $dateString = $this->escapeString('event_date');
    $timeString = $this->escapeString('event_time');
        $dateonly = date('D, M d', strtotime($event_date));
      $datetimeString =  $dateonly.' at '.$event_time.' WAT';
        
        $data['user_id'] = '0';
        $data['event_title'] = $event_title;
        $data['event_description'] = $event_description;
        $data['event_date'] = $event_date;
        $data['event_location'] = $event_location;
        $data['event_time'] = $event_time;
        $data['approval_status'] = '1';
      //  $data['reference'] = $referenceid;
        
        $data['close_time'] = $close_time;
        
   
        $data['endDate'] = $endDate;
        $data['contactPhone'] = $contactPhone;
        $data['contactName'] = $contactName;
        $data['days'] = '';
        $data['dateTime'] = $datetimeString;//date_format_convert($dateString.':'.$timeString);
        $data['activities'] = json_encode($activities);
        $data['eventchild'] = json_encode($eventchild);//$this->trim_and_return_json($eventchild);
         $data['ads_type'] = $adstype;
         $this->db->where('reference',$referenceid);
        $this->db->update('events', $data);
       $this->session->set_flashdata('success_msg', 'Event successfully updated');
     redirect(adminController().'events');
}


public function doEventadding()
{
   
   $event_title = $this->escapeString('event_title');
   $event_description = $this->escapeString('event_description');
   $event_date = $this->escapeString('event_date');
   
    $endDate = $this->escapeString('endDate');
    
   $event_time = $this->escapeString('event_time');
   $close_time = $this->escapeString('close_time');
   $adstype = $this->escapeString('adstype');
  // $state = $this->escapeString('state');
   $event_location = $this->escapeString('event_location');
   
   $event_img = $this->escapeString('event_img');
   $videos = $this->escapeString('videos');
   $contactName = $this->escapeString('contactName');
   $contactPhone = $this->escapeString('contactPhone');
   
   $referenceid = $this->uniqueid();
    $uploadedFileNames = [];
    $activities = $this->escapeString('activities');
   
    if(isset($_FILES['event_img'])) {
        
         if (!file_exists('assets/app/eventsFiles/'.$referenceid)) {
                        mkdir('assets/app/eventsFiles/'.$referenceid, 0777, true);
                    }
                    
                    
    foreach ($_FILES['event_img']['name'] as $key => $fileName) {
        $extension = '.png';
        $uniqid = $this->uniqueidwithlenght('5');
     $uploadDirectory = 'assets/app/eventsFiles/'.$referenceid.'/'.$uniqid.$extension; 
    
        // Check for upload errors
        if ($_FILES['event_img']['error'][$key] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['event_img']['tmp_name'][$key];
            $targetPath = $uploadDirectory;

            // Move the uploaded file from its temporary location to the target directory
            if (move_uploaded_file($tmpName, $targetPath)) {
                $uploadedFileNames[] = base_url().'assets/app/eventsFiles/'.$referenceid.'/'.$uniqid.$extension; // Add the filename to the array
            } else {
               $this->session->set_flashdata('error_message', 'Fail to upload files');
                    redirect(adminController().'events');
            }
        } else {
            $this->session->set_flashdata('error_message', 'Error uploading file ' . htmlspecialchars($fileName) . ': ' . $_FILES['uploaded_files']['error'][$key]);
          redirect(adminController().'events');
        }
    }
    
    }else{
         $this->session->set_flashdata('error_message', 'Upload at least 1 image file');
          redirect(adminController().'events');
    }
$eventchild = array();
$getEventItems = $this->db->get('listitemstemp');
if($getEventItems->num_rows() > 0)
{
    foreach($getEventItems->result_array() as $list)
    {
        $eventchild[] = $list;
    }
 $this->db->where('status', '1');
 $this->db->delete('listitemstemp');
}


  $event_time = date('g:i A', strtotime($event_time));


    $dateString = $this->escapeString('event_date');
    $timeString = $this->escapeString('event_time');
        $dateonly = date('D, M d', strtotime($event_date));
      $datetimeString =  $dateonly.' at '.$event_time.' WAT';
        
        $data['user_id'] = '0';
        $data['event_title'] = $event_title;
        $data['event_description'] = $event_description;
        $data['event_date'] = $event_date;
        $data['event_location'] = $event_location;
        $data['event_time'] = $event_time;
        $data['approval_status'] = '1';
        $data['reference'] = $referenceid;
        
        $data['close_time'] = $close_time;
        
   
        $data['endDate'] = $endDate;
        $data['contactPhone'] = $contactPhone;
        $data['contactName'] = $contactName;
        $data['days'] = '';
        $data['dateTime'] = $datetimeString;//date_format_convert($dateString.':'.$timeString);
        $data['activities'] = json_encode($activities);
        $data['eventchild'] = json_encode($eventchild);//$this->trim_and_return_json($eventchild);
         $data['ads_type'] = $adstype;
        $this->db->insert('events', $data);
        $eventid = $this->db->insert_id();
    
        $data['eventfiles'] = json_encode($uploadedFileNames); //$this->trim_and_return_json_upload($eventid,$_FILES['event_img']);
        $data['event_img_link'] = json_encode($uploadedFileNames);// $this->trim_and_return_json_upload($eventid,$_FILES['event_img']);
         
         $this->db->where('id', $eventid);
        $this->db->update('events', $data);
    
   
      $this->session->set_flashdata('success_msg', 'Event successfully added');
     redirect(adminController().'events');
}
  public function trim_and_return_json($untrimmed_array = [])
    {
        if (!is_array($untrimmed_array)) {
            $untrimmed_array = [];
        }
        $trimmed_array = array();
        if (sizeof($untrimmed_array) > 0) {
            foreach ($untrimmed_array as $row) {
                if ($row != "") {
                    array_push($trimmed_array, $row);
                }
            }
        }
        return json_encode($trimmed_array);
    }

public function addlistingCart()
{
     $data = array(
            'id'    => $this->uniqueidwithlenght('4'),
            'starttime'    => $this->escapeString('starttime'),
             'closetime'    => $this->escapeString('closetime'),
            'days' => json_encode($this->escapeString('days')),
            'event_name'    => $this->escapeString('event_name')
        );
       
        $this->db->insert('listitemstemp',$data);
      return true;
}
public function removeitemCart($itemid)
{
   $this->db->where('id',$itemid);
   $this->db->delete('listitemstemp');
     $this->session->set_flashdata(array('success_msg' => 'itemremoved'));
      redirect(adminController().'postEvent');
}
public function addEvent()
{
    $title = $this->escapeString('event_title');
    $description = $this->escapeString('event_description');
    $event_date = $this->escapeString('event_date');
    $event_location = $this->escapeString('event_location');
    $event_time = $this->escapeString('event_time');
    if((empty($title)) || (empty($description)) || (empty($event_date)) || (empty($event_location)) || (empty($event_time)))
    {
         $this->session->set_flashdata('error_message', 'Please fill all required fields');
          redirect(adminController().'events');
    }
     if($_FILES['event_img']['name'] != "") {
        $fileRename = $this->random_strings(6);
        $path = 'assets/images/event/'. $fileRename . '.jpg';
        $link = base_url().$path;
        move_uploaded_file($_FILES['event_img']['tmp_name'], $path);
        $data['event_img_link'] = $link;
         }else{
           $this->session->set_flashdata('error_message',('Event file not found'));
           redirect(adminController().'events');
       }
    $dateString = $this->escapeString('event_date');
    $timeString = $this->escapeString('event_time');
    $data['user_id'] = '0';
    $data['event_title'] = $this->escapeString('event_title');
    $data['event_description'] = $this->escapeString('event_description');
    $data['event_date'] = $this->escapeString('event_date');;
    $data['event_location'] = $this->escapeString('event_location');
    $data['event_time'] = $this->escapeString('event_time');
    $data['close_time']  = $this->escapeString('close_time');
    $data['ads_type'] = $this->escapeString('adstype');
    $data['approval_status'] = '1';
    $data['dateTime'] = date_format_convert($dateString.':'.$timeString);
   
    $data['reference'] = $this->uniqueid();
    $this->db->insert('events', $data);
       $this->session->set_flashdata('success_msg', 'Event successfully added');
     redirect(adminController().'events');
}
public function event_details($id)
{
     if ($this->session->userdata('super_admin_session') == true) {
         
         if($id =='')
         {
             redirect(adminController().'transaction');
             exit;
         }
        $page_data['page_name'] = "event_details";
        $page_data['data'] = $this->db->get_where('events', array('reference' => $id))->row_array();
        $page_data['page_title'] = 'Event Details';
        $this->load->view('backend/index', $page_data);
        }else {
        $this->session->set_flashdata('error_message', 'Access denied. Please login again!');    
     redirect(adminController().'logout');
  }
}
public function trans_details($id)
{
     if ($this->session->userdata('super_admin_session') == true) {
         
         if($id =='')
         {
             redirect(adminController().'transaction');
             exit;
         }
        $page_data['page_name'] = "trans_details";
        $page_data['data'] = $this->Admin_model->get_transaction_single($id)->row_array();
        $page_data['page_title'] = 'Transaction Details';
        $this->load->view('backend/index', $page_data);
        }else {
        $this->session->set_flashdata('error_message', 'Access denied. Please login again!');    
     redirect(adminController().'logout');
  }
}


public function eventsbyuser()
{

  if ($this->session->userdata('super_admin_session') == true) {
      
      if((isset($_GET['user_id']))&&(!empty($_GET['user_id'])))
      {
          $user_id = $_GET['user_id'];
          $data = $this->db->get_where('events', array('user_id' => $user_id));
      }else{
          $data = $this->db->get('events');
      }
        $page_data['page_name'] = "events";
        $page_data['page_title'] = 'All Events';
        $page_data['history'] = $data;
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function events()
{

  if ($this->session->userdata('super_admin_session') == true) {
      
      if((isset($_GET['status']))&&($_GET['status'] != ""))
      {
          $status = $_GET['status'];
          $data = $this->db->order_by('id','desc')->get_where('events', array('approval_status' => $status));
      }else{
          $data = $this->db->order_by('id','desc')->get('events');
      }
        $page_data['page_name'] = "events";
        $page_data['page_title'] = 'All Events';
        $page_data['history'] = $data;
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}

public function siteBroadcast()
{
  if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "siteBroadcast";
        $page_data['page_title'] = 'Send Broadcast';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function siteNotification()
{
  if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "siteNotification";
        $page_data['page_title'] = 'Site Notification';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function siteConfiguration()
{
  if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "siteConfiguration";
        $page_data['page_title'] = 'Site Configuration';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}

public function identitySettings()
{
  if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "identitySettings";
        $page_data['page_title'] = 'Identity Settings';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}

public function inlinebanner()
{
  if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "inlinebanner";
         $page_data['data'] = $this->db->get('inlinebanner');
        $page_data['page_title'] = 'Inline Management';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function banner()
{
  if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "banner";
         $page_data['data'] = $this->db->get('mobile_slider');
        $page_data['page_title'] = 'Banner Management';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}

public function services()
{
  if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "services";
        $page_data['page_title'] = 'Modules Status';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function adminUser()
{
  if ($this->session->userdata('super_admin_session') == true) {
      $adetails = $this->Admin_model->user_details($this->session->userdata('login_id'))->row_array();
      $this->db->where('admin_id !=',$adetails['admin_id']);
      $admins = $this->db->get('admin');
        $page_data['page_name'] = "adminUser";
        $page_data['page_title'] = 'Admin User';
        $page_data['data'] = $admins;
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function updateAdminStatus()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $details = $this->db->get_where('admin',array('admin_id' => $id))->row_array();
    if($details['status'] == '1')
    {
        $data['status'] = '0';
    }else{
        $data['status'] = '1';
    }
    $this->db->where('admin_id',$id);
    $this->db->update('admin',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    $this->session->set_flashdata('success_alert', 'Record successfully updated');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function unsuspendUser()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $details = get_user_info($id);
    if($details['suspend'] == '1')
    {
        $data['suspend'] = '0';
    }else{
        $data['suspend'] = '1';
    }
    $this->db->where('accountid',$id);
    $this->db->update('account',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    $this->session->set_flashdata('success_alert', 'Record successfully updated');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function updatePaymentGatewayStatus()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $details = payment_getways($id);
    if($details['gateway_status'] == '1')
    {
        $data['gateway_status'] = '0';
    }else{
        $data['gateway_status'] = '1';
    }
    $this->db->where('gateway_id',$id);
    $this->db->update('payment_gateways',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    $this->session->set_flashdata('success_alert', 'Record successfully updated');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function updateExamStatus()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $details = get_CablePlan($id);
    if($details['status'] == '1')
    {
        $data['status'] = '0';
    }else{
        $data['status'] = '1';
    }
    $this->db->where('id',$id);
    $this->db->update('network',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function updateCableplanStatus()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $details = get_CablePlan($id);
    if($details['tv_status'] == '1')
    {
        $data['tv_status'] = '0';
    }else{
        $data['tv_status'] = '1';
    }
    $this->db->where('id',$id);
    $this->db->update('cabletv',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function updateModuleStatus()
{
     if ($this->session->userdata('super_admin_session') == true) {
          $id = $this->escapeString('id');
          $details = get_module($id);
    if($details['status'] == '1')
    {
        $data['status'] = '0';
    }else{
        $data['status'] = '1';
    }
    $this->db->where('id',$id);
    $this->db->update('module',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    echo json_encode($arr);
     }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  

}
public function updateDataplanStatus()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $details = get_Databundles($id);
    if($details['status'] == '1')
    {
        $data['status'] = '0';
    }else{
        $data['status'] = '1';
    }
    $this->db->where('package_id',$id);
    $this->db->update('databundles',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function updateSubModuleStatus()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $details = get_Submodule($id);
    if($details['status'] == '1')
    {
        $data['status'] = '0';
    }else{
        $data['status'] = '1';
    }
    $this->db->where('id',$id);
    $this->db->update('sub_module',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function updateProviders()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $details = get_network($id);
    if($details['status'] == '1')
    {
        $data['status'] = '0';
    }else{
        $data['status'] = '1';
    }
    $this->db->where('id',$id);
    $this->db->update('network',$data);
    $arr = array('message' => 'Record successfully updated', 'title' => 'Success');
    echo json_encode($arr);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function deleteAPI($id)
{
    if ($this->session->userdata('super_admin_session') == true) {
    $this->db->where('id',$id);
    $this->db->delete('api_provider');
    $this->session->set_flashdata('success_msg', 'Record deleted successfully');
     redirect(adminController().'apiSettings');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
   }  
}

public function editSubModuleData()
{
    if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $moduleid = $this->escapeString('moduleid');
    $data['name'] = $this->escapeString('name');
    $data['network_id'] = $this->escapeString('network');
    $data['api'] = $this->escapeString('api');
    $data['api_code'] = $this->escapeString('api_code');
    $data['parent_id'] = $this->escapeString('moduleid');
    if(isset($_POST['user_percent'])){
    $data['user_percent'] = $this->escapeString('user_percent');
    $data['reseller_percent'] = $this->escapeString('reseller_percent');
    $data['api_percent'] = $this->escapeString('api_percent');
    }
    $this->db->where('id',$id);
    $this->db->update('sub_module',$data);
    $this->session->set_flashdata('success_msg', 'Record updated successfully');
     redirect(adminController().'setService/'.$moduleid);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}

public function editDataPlans()
{
    if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('package_id');
    $subModule = $this->escapeString('subtype');
    $suDetails = get_Submodule($subModule);
    $data['package_name'] = $this->escapeString('package_name');
    $data['subtype'] = $subModule;
    $data['price'] = $this->escapeString('price');
    $data['user_price'] = $this->escapeString('user_price');
    $data['reseller_price'] = $this->escapeString('reseller_price');
    $data['api_price'] = $this->escapeString('api_price');
    $data['network_id'] = $suDetails['network_id'];
    $data['provider_code'] = $this->escapeString('api_code');
    $this->db->where('package_id',$id);
    $this->db->update('databundles',$data);
    $this->session->set_flashdata('success_msg', 'Record updated successfully');
     redirect(adminController().'dataplans');
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function editCablePlan()
{
    if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $subModule = $this->escapeString('subtype');
    $suDetails = get_Submodule($subModule);
    $data['package_name'] = $this->escapeString('package_name');
    $data['subtype'] = $subModule;
    $data['price'] = $this->escapeString('price');
    $data['user_price'] = $this->escapeString('user_price');
    $data['reseller_price'] = $this->escapeString('reseller_price');
    $data['api_price'] = $this->escapeString('api_price');
    $data['cabletv'] = $suDetails['network_id'];
    $data['provider_code'] = $this->escapeString('api_code');
    $this->db->where('id',$id);
    $this->db->update('cabletv',$data);
   $this->session->set_flashdata('success_msg', 'Record added successfully');
     redirect(adminController().'cablePlans');
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}

public function editExampins()
{
    if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $subModule = $this->escapeString('subtype');
    $suDetails = get_Submodule($subModule);
    $data['network_name'] = $this->escapeString('package_name');
   // $data['subtype'] = $subModule;
   // $data['price'] = $this->escapeString('price');
    $data['user_percent'] = $this->escapeString('user_price');
    $data['reseller_percent'] = $this->escapeString('reseller_price');
    $data['api_percent'] = $this->escapeString('api_price');
    //$data['cabletv'] = $suDetails['network_id'];
    //$data['provider_code'] = $this->escapeString('api_code');
    $this->db->where('id',$id);
    $this->db->update('network',$data);
   $this->session->set_flashdata('success_msg', 'Record added successfully');
     redirect(adminController().'cablePlans');
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function addAPIProvider()
{
    if ($this->session->userdata('super_admin_session') == true) {
   // $id = $this->escapeString('id');
    $data['provider_name'] = $this->escapeString('provider_name');
    $data['apiType'] = $this->escapeString('apiType');
    $data['user_id'] = $this->escapeString('user_id');
    $data['api_key'] = $this->escapeString('api_key');
    $data['endpoint'] = $this->escapeString('endpoint');
    $data['subModule'] = $this->escapeString('subModule');
    $data['password'] = $this->escapeString('password');
    $this->db->insert('api_provider',$data);
   $this->session->set_flashdata('success_msg', 'Record added successfully');
     redirect(adminController().'apiProviders');
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function updateAPIProvider()
{
    if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $data['provider_name'] = $this->escapeString('provider_name');
    $data['apiType'] = $this->escapeString('apiType');;
    $data['api_key'] = $this->escapeString('api_key');
    $data['user_id'] = $this->escapeString('user_id');
    $data['endpoint'] = $this->escapeString('endpoint');
    $data['subModule'] = $this->escapeString('subModule');
    $data['password'] = $this->escapeString('password');
    $this->db->where('id',$id);
    $this->db->update('api_provider',$data);
   $this->session->set_flashdata('success_msg', 'Record updated successfully');
     redirect(adminController().'apiProviders');
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function updatePaymentGateway()
{
    if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('gateway_id');
    $data['gateway_name'] = $this->escapeString('gateway_name');
    $data['charge_type'] = $this->escapeString('charge_type');;
    $data['gateway_percent'] = $this->escapeString('gateway_percent');
    $data['pk_live'] = $this->escapeString('pk_live');
    $data['sk_live'] = $this->escapeString('sk_live');
    $data['contract_code'] = $this->escapeString('contract_code');
    $this->db->where('gateway_id',$id);
    $this->db->update('payment_gateways',$data);
   $this->session->set_flashdata('success_msg', 'Record added successfully');
     redirect(adminController().'paymentGateways');
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function addCablePlan()
{
    if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $subModule = $this->escapeString('subtype');
    $suDetails = get_Submodule($subModule);
    $data['package_name'] = $this->escapeString('package_name');
    $data['subtype'] = $subModule;
    $data['price'] = $this->escapeString('price');
    $data['user_price'] = $this->escapeString('user_price');
    $data['reseller_price'] = $this->escapeString('reseller_price');
    $data['api_price'] = $this->escapeString('api_price');
    $data['cabletv'] = $suDetails['network_id'];
    $data['provider_code'] = $this->escapeString('api_code');
    $this->db->insert('cabletv',$data);
    $this->session->set_flashdata('success_msg', 'Record added successfully');
     redirect(adminController().'cablePlans');
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function addDataPlan()
{
    if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $subModule = $this->escapeString('subtype');
    $suDetails = get_Submodule($subModule);
    $data['package_name'] = $this->escapeString('package_name');
    $data['subtype'] = $subModule;
    $data['price'] = $this->escapeString('price');
    $data['user_price'] = $this->escapeString('user_price');
    $data['reseller_price'] = $this->escapeString('reseller_price');
    $data['api_price'] = $this->escapeString('api_price');
    $data['network_id'] = $suDetails['network_id'];
    $data['provider_code'] = $this->escapeString('api_code');
    $this->db->insert('databundles',$data);
    $this->session->set_flashdata('success_msg', 'Record added successfully');
     redirect(adminController().'dataplans');
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function addSubModuleData()
{
    if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $moduleid = $this->escapeString('moduleid');
    $data['name'] = $this->escapeString('name');
    $data['network_id'] = $this->escapeString('network');
    $data['api'] = $this->escapeString('api');
    $data['api_code'] = $this->escapeString('api_code');
    $data['parent_id'] = $this->escapeString('moduleid');
    $this->db->insert('sub_module',$data);
    $this->session->set_flashdata('success_msg', 'Record updated successfully');
     redirect(adminController().'setService/'.$moduleid);
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
/*
public function addApiProvider()
{
    if ($this->session->userdata('super_admin_session') == true) {
    $data['provider_name'] = $this->escapeString('provider_name');
    $data['endpoint'] = $this->escapeString('endpoint');
    $data['apiType'] = $this->escapeString('apiClass');
    $data['api_key'] = $this->escapeString('api_key');
    $this->db->insert('api_provider',$data);
    $this->session->set_flashdata('success_msg', 'Record added successfully');
     redirect(adminController().'apiSettings');
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
*/
public function editApiProvider()
{
     if ($this->session->userdata('super_admin_session') == true) {
    $id = $this->escapeString('id');
    $data['provider_name'] = $this->escapeString('provider_name');
    $data['endpoint'] = $this->escapeString('endpoint');
    $data['apiType'] = $this->escapeString('apiClass');
    $data['api_key'] = $this->escapeString('api_key');
    $this->db->where('id',$id);
    $this->db->update('api_provider',$data);
    $this->session->set_flashdata('success_msg', 'Record updated successfully');
     redirect(adminController().'apiSettings');
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}

public function getsubModule()
{
    if ($this->session->userdata('super_admin_session') == true) {
        $network = $this->escapeString('network');
        $this->db->where('network');
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}
public function networkSettings()
{
  if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "networkSettings";
        $page_data['page_title'] = 'Providers Settings';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}
public function apiSettings()
{
  if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "apiSettings";
        $page_data['page_title'] = 'API Settings';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }    
}

public function configurations()
{
    if ($this->session->userdata('super_admin_session') == true) {
        $page_data['page_name'] = "configuration";
        $page_data['page_title'] = 'Configuration';
         $this->load->view('backend/index', $page_data);;		
}else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
  }  
}


public function member_details($member_id='') {
  if ($this->session->userdata('super_admin_session') == true) {
    $page_data['page_name'] = "member_details";
        $page_data['page_title'] = 'Member Details';
        $page_data['member_id'] = $member_id;
        $page_data['details'] = get_user_info($member_id);
        $this->load->view('backend/index', $page_data);
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');    
    redirect(adminController().'logout');
  }
}
public function uniqueid()
      {
        $un = substr(number_format(time() * rand(),0,'',''),0,12);
        return $un;
      }
public function escapeString($string) {
    return remove_invisible_characters(html_escape($this->input->post($string)));
  }
 
 public function updateSystemAccount()
 {
    if ($this->session->userdata('super_admin_session') == false) {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');    
    redirect(adminController().'logout');
    }
    $id = '1';
    $data['bank_name'] = $this->escapeString('bank_name');
    $data['account_name'] = $this->escapeString('account_name');
    $data['account_number'] = $this->escapeString('account_number');
    $data['account_type'] =$this->escapeString('account_type');
    $this->db->where('id',$id);
    $this->db->update('system_bank_accounts',$data);
    $this->session->set_flashdata('success_alert', 'Record updated successfully');
    redirect(adminController().'paymentGateways');
 }
 public function chooseOrganizer()
 {
      if ($this->session->userdata('super_admin_session') == false) {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');    
    redirect(adminController().'logout');
    }
     $ereference = $this->escapeString('reference');
     $emailAddress = $this->escapeString('emailAddress');
     $checkUser = $this->db->get_where('account', array('email' => $emailAddress));
     
     if($checkUser->num_rows() < 1)
     {
         $this->session->set_flashdata('error_message', 'Email does not exist');    
     redirect(adminController().'event_details/'.$ereference);
     }
     $userRow = $checkUser->row_array();
     
     $data['user_id'] = $userRow['account'];
     $this->db->where('reference',$ereference);
     $this->db->update('event',$data);
     
     $this->session->set_flashdata('success_alert', 'Record updated successfully');    
     redirect(adminController().'event_details/'.$ereference);
 }
 public function updateUser()
 {
    if ($this->session->userdata('super_admin_session') == false) {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');    
    redirect(adminController().'logout');
    }
    $id = $this->escapeString('accountid');
    $data['fullname'] = $this->escapeString('fullname');
    $data['email'] = $this->escapeString('email');
    $data['phone'] = $this->escapeString('phone');
    $data['api_access'] =$this->escapeString('api_access');
    $data['security_pin'] = $this->escapeString('security_pin');
    $data['palmpay'] = $this->escapeString('palmpay');
    $data['wema'] = $this->escapeString('wema');
    $data['moniepoint'] = $this->escapeString('moniepoint');
    $data['payvessel'] = $this->escapeString('payvessel');
    if((isset($_POST['passwordUpdate']))&&(!empty($_POST['passwordUpdate'])))
    {
    $password = $this->escapeString('passwordUpdate');
     $data['password'] = password_hash($password, PASSWORD_BCRYPT);
    }
    $data['dailyLimit'] = $this->escapeString('dailyLimit');
     $data['dailyReversal'] =$this->escapeString('dailyReversal');
    $data['package_id'] =$this->escapeString('package_id');
    $data['kyc_approve'] =$this->escapeString('kyc_approve');
    $this->db->where('accountid',$id);
    $this->db->update('account',$data);
    $this->session->set_flashdata('success_alert', 'Record updated successfully');
    redirect(adminController().'customerDetails/'.$id);
 }
  public function do_register() 
{
     if ($this->session->userdata('super_admin_session') == false) {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');    
    redirect(adminController().'logout');
  }
    $this->load->helper('email');
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
    $phonenumber = $this->escapeString('phonenumber');
    $email = $this->escapeString('email');
    $fullname = $this->escapeString('fullname');
    $password = $this->escapeString('password');
    $password_lenght = strlen($password);
        if($password_lenght < get_settings('password_lenght'))
           {
             $this->session->set_flashdata('error_message', 'Minimum of '.get_settings('password_lenght').' character is required for your password');
             redirect(adminController().'customers/1'); 
             exit;
           }
           
           if((strlen($phonenumber) < '11')||(strlen($phonenumber) > '11'))
           {
             $this->session->set_flashdata('error_message', 'Invalid Phone number detected');
             redirect(adminController().'customers/1');
             exit;
           }
           
           
           if((empty($phonenumber)||empty($email)))
           {
             $this->session->set_flashdata('error_message', 'Email address and Phone Number is required');
              redirect(adminController().'customers/1');
           }
    
    
    $userData = array(
        'email' => $email,
        'phone' => $phonenumber,
        'fullname' => $fullname,
         'password' => password_hash($password, PASSWORD_BCRYPT),
         'referral_code' =>$this->random_strings('4'),
         'date_registered'=> current_datetime(),
         'activation_status' => get_settings('activation_status'),
         
    );
    $checkPhone = $this->Auth_model->check_exist($phonenumber,$email);
             if(($checkPhone == false)){
                $insert = $this->Auth_model->register_new($userData);
               // $key = $this->User_model->create_key_table($insert);
                $this->session->set_flashdata('success_alert', 'Thank you for creating account with us. Your account has been created successfully.');
            redirect(adminController().'customers/1');
             }else{
            $this->session->set_flashdata('error_message', 'Account could not be created. It seems you have account with us. Forget password? please use the forget password link');
           redirect(adminController().'customers/1');
             }
}
public function manualFunding()
{
    if ($this->session->userdata('super_admin_session') == true) {
    $page_data['page_name'] = "manualFunding";
        $page_data['page_title'] = 'Manual Funding';
        $this->load->view('backend/index', $page_data);
    }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');    
    redirect(adminController().'logout');
  }
}
public function manual_topup(){
     if ($this->session->userdata('super_admin_session') == true) {
         $phone = $this->escapeString('user_id');
         $amount = $this->escapeString('amount');
         $actiontype = $this->escapeString('actiontype');
         $wallet_type = 'wallet_balance';//$this->input->post('wallet_type');
         $invoice_number = $this->uniqueid();
        $user = $this->Admin_model->fetch_member($phone)->row_array();
        if(empty($user)){
        $this->session->set_flashdata('error_message', 'Customer does not exist.');
        redirect(adminController().'customerDetails/'.$user['accountid']); 
        }
        if($actiontype == 'credit'){$sign = '+';$alert = 'credited';$function = 'do_CreditWallet';}else{$sign = '-';$alert = 'debited';$function = 'do_DebitWallet';}
        $to = $user['email'];
        $full_name = $user['fullname'];
        $app_name = $this->db->get_where('settings',array('key'=>'app_name'))->row()->value;
        $sub = 'NGN '.$amount.' was '.$alert.' on your account on '.$app_name;
        $user_id = $user['accountid'];
        $new_bal = ($user[$wallet_type] + $amount);
        $getbal = $user[$wallet_type];
        $status = '1';
        $body = 'Dear '.$full_name.', <br/><br/> This is to nofity you that NGN '.$amount.' has just been '.$alert.' to your wallet on '.$app_name.' website as follows <br/><br/>
        Previous Balance: NGN '.$getbal.' <br/>New Balance: '.$new_bal.' <br/>Narration: '.$sub.'<br/>Wallet Type: '.$wallet_type.'<br/>Transaction Ref: '.$invoice_number.' <br/><br/>Regards<br/> '.$app_name.' Team';
        $save_order = $this->User_model->insert_transaction($user_id,$sub,$module_details='6',$validate_number='',$user['phone'],$amount,$amount,$apiRoute='',$invoice_number,$sub_module='',$extra='',$discount='');
        $this->User_model->$function($user_id,$amount,$wallet_type);
        $this->User_model->initiate_fund($user_id,$invoice_number,$amount,$payment_getways='',$payment_status='1',$getbal,$new_bal);
        $updateS['status'] = '1';$this->db->where('id',$save_order);$this->db->update('transactions',$updateS);
        $this->User_model->updateBalances($save_order,$getbal);
        $this->session->set_flashdata('success_msg', 'User balance updated successfully');
        $this->send_mail($to,$sub,$body);
        redirect(adminController().'customerDetails/'.$user['accountid']);
         }else {
    $this->session->set_flashdata('error_message', 'Access denied. Please login again!');
     redirect(adminController().'logout');
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
  }else{
     return true;
   
  }
  
}
}