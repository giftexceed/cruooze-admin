<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Html extends CI_Controller {
	function __construct(){
	    parent::__construct();
	     $this->load->database();
        $this->load->library('session');
        $this->load->library('cart');
        $this->load->model('User_model');
	}
public function updatePassword()
{
     if(($this->session->userdata('member_session') == true ) ||($this->session->userdata('loginId') == true )){
        $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();
         $page_data['page_name'] = "updatePassword";
        $page_data['page_title'] = 'Change Password';
        $this->session->set_flashdata('error_message', 'Please change your password');
        $this->load->view('Account/index', $page_data);
     }
}
public function fetch_lgas()
  {
    $states = $this->escapeString('states');
    $result = get_lgas($states);
    $output = '<option>Choose Local Government Area</option>';
    foreach($result->result_array() as $each)
    {
        $output .='<option value="'.$each['id'].'">'.$each['name'].'</option>';
    }
   echo $output;
  }
public function do_passwordUpdate()
{
    $this->load->model('Auth_model');
    if(($this->session->userdata('member_session') == true ) ||($this->session->userdata('loginId') == true )){
        $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();
        $new_password = $this->input->post('newpassword'); 
         $confirmpassword = $this->input->post('confirmpassword');
         if($new_password != $confirmpassword)
            {
                $this->session->set_flashdata('error_message', 'Password mismatch. Your password and confirm password do not match');
                    redirect('updatePassword');  
                     exit;
            }
         $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
        $update = $this->Auth_model->changePassword($new_password_hash,$udetails['sEmail']);  
        if($update){
            $this->session->unset_userdata('ChangePassword');
            $this->session->set_flashdata('success_msg', 'Password updated successfully');
            redirect('dashboard');
        }
    }
}
public function getDataplans($network)
 {
    
   $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();       $user_id = $udetails['accountid'];
  $user_id = $udetails['accountid'];
  $return_user_price =$this->User_model->get_user_price($user_id);
   $module_id = '2';
  $networkdetails = $this->User_model->get_network_single($module_id,$network)->row_array();
  $this->db->where('network_id', $network);$this->db->where('parent_id', $module_id);$this->db->where('status', '1');$submodule = $this->db->get('sub_module');
  $currency = currency();
  $output = '';
  $count = '1';
  
  foreach($submodule->result_array() as $modules)
      {
          $counter = $count++;
           $show = ($counter == '1') ? 'show active' : '';
          $this->db->where('network_id', $network);$this->db->where('subtype', $modules['id']);$this->db->where('status', '1');$databundles = $this->db->get('databundles');
          $output .='<div class="tab-pane fade p-0 '.$show.'" id="items-tab-pane'.$counter.'" role="tabpanel"
                        aria-labelledby="home-tab" tabindex="0">';
          $output .='<div class="option d-block mt-3">';
        foreach($databundles->result() as $each){
          $output .='<div class="form-check">
                        <input class="form-check-input dataplan" type="radio" name="dataplan" id="flexradio'.$each->package_id.'" value="'.$each->package_id.'" data-name="'.$networkdetails['network_name'].' " data-price="'.$each->$return_user_price.'"/>
                        <label class="form-check-label" for="flexradio'.$each->package_id.'">'.$each->package_name.' - '.$currency.' '.$each->$return_user_price.'</label>
                    </div><hr>';
          
         }
         $output .='</div>';
         $output .='</div>';
                
      }
   
     return $output;
}
public function manifest()
  {
      if((isset($_GET['ref'])) && (!empty($_GET['ref'])))
      {
          $ref = $_GET['ref'];
          $this->User_model->manifest($ref);
      }
  }
public function searchProductajax()
  {
      $lga = "";
      $state = "";
      $p_cat = "";
      if((isset($_GET['state']))&&(!empty($_GET['state'])))
      {
          $state = $_GET['state'];
      }
      
      if((isset($_GET['lga']))&&(!empty($_GET['lga'])))
      {
          $lga = $_GET['lga'];
      }
      if((isset($_GET['category']))&&(!empty($_GET['category'])))
      {
          $p_cat = $_GET['category'];
      }
      if((isset($_GET['keyword']))&&(!empty($_GET['keyword'])))
      {
          $keyword = $_GET['keyword'];
      }
      
      $html = '';
	  $network = $this->User_model->getProductsSearch($state,$lga,$p_cat,$keyword);
	  if($network->num_rows() > 0){
	  foreach($network->result_array() as $list){
	     $discount = $list['p_price'];
	     $state = get_states($list['state'])->row_array()['name'];
	      $lgas = get_bylgas($list['lga'])->row_array()['name'];
	       $category = p_category($list['p_cat'])['category_name'];
	      $udetails = get_user_info($list['user_id']);
	    $html .='<div class="col-12">
          <div class="news-update-box">
            <div class="d-flex align-items-center gap-3">
              <a href="'.base_url('details/'.$list['id'].'/'.$list['slug']).'">
                <img class="img-fluid news-update-image" src="'.product_img($list['id']).'" alt="'.$list['p_title'].'" />
              </a>
              <div class="news-update-content">
                <a href="'.base_url('details/'.$list['id'].'/'.$list['slug']).'">
                  <h3>'.$list['p_title'].'</h3>
                </a>
                <div class="news-writer">
                  <h6>'.currency().' '.$discount.'</h6>
                  <h6>'.$category.'</h6>
                </div>
              </div>
            </div>
          </div>
        </div>';
	    }
	  }else{
	      $html .='<div class="empty-tab">
        <img class="img-fluid empty-bell w-100" src="https://img.freepik.com/free-vector/search-concept-landing-page_52683-18573.jpg?t=st=1715692927~exp=1715696527~hmac=5bc262524619e9780f9924c1b10fc5348447cd835b1adae93cfadc714d000888&w=1380" alt="Service not available" />
        <h2>Nothing was found!</h2>
        <p>Ops! We couldn\'nt find any item that matches your search criteria</p>
        <div class="w-100">
          <div class="custom-container">
            <a href="'.base_url('marketplace').'" class="btn theme-btn empty-btn w-100 mt-0 p-3" role="button">All Items</a>
          </div>
        </div>
      </div>';
	  }
	  
        echo $html;
  }
public function ajaxGetProductsUser()
{
    $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();
  $user_id = $udetails['sId'];
    $html = '';
	    $network = $this->User_model->getProductsUser($user_id);
	    if($network->num_rows() > 0){
	    foreach($network->result_array() as $list){
	        if($list['p_status'] == '1')
	        {
	            $status = 'Active';
	        }else{
	            $status = 'Inactive';
	        }
	     $discount = $list['p_price'];
	     $state = get_states($list['state'])->row_array()['name'];
	      $lgas = get_bylgas($list['lga'])->row_array()['name'];
	      $udetails = get_user_info($list['user_id']);
	       $category = p_category($list['p_cat'])['category_name'];
	      $count = countViews($list['id']);
	    $html .='<div class="col-12">
          <div class="news-update-box">
            <div class="d-flex align-items-center gap-3">
              <a href="'.base_url('productDetails/'.$list['id'].'/'.$list['slug']).'">
                <img class="img-fluid news-update-image" src="'.product_img($list['id']).'" alt="'.$list['p_title'].'" />
              </a>
              <div class="news-update-content">
                <a href="'.base_url('productDetails/'.$list['id'].'/'.$list['slug']).'">
                  <h3>'.$list['p_title'].'</h3>
                </a>
                <div class="news-writer">
                  <h6>'.currency().' '.$discount.'</h6>
                  <h6>'.$category.'</h6>
                </div>
              </div>
            </div>
          </div>
          
        </div>
        ';
	      }
	    }else{
	        $html .='<div class="empty-tab">
        <img class="img-fluid empty-bell w-100" src="https://img.freepik.com/free-vector/search-concept-landing-page_52683-18573.jpg?t=st=1715692927~exp=1715696527~hmac=5bc262524619e9780f9924c1b10fc5348447cd835b1adae93cfadc714d000888&w=1380" alt="Service not available" />
        <h2>You have no item on marketplace!</h2>
        <p>Post your first item on marketplace</p>
        <div class="w-100">
          <div class="custom-container">
            <a href="'.base_url('marketplace').'" class="btn theme-btn empty-btn w-100 mt-0 p-3" role="button">Add your first Item</a>
          </div>
        </div>
      </div>';
	    }
        echo $html;
}
public function ajaxGetProductsWidget()
{
   $network = $this->User_model->getProducts();
   $html = '';
   if($network->num_rows() > 0){
	    foreach($network->result_array() as $list){
	     $discount = $list['p_price'];
	     $state = get_states($list['state'])->row_array()['name'];
	      $lgas = get_bylgas($list['lga'])->row_array()['name'];
	      $udetails = get_user_info($list['user_id']);
	      $category = p_category($list['p_cat'])['category_name'];
	    $html .='<div class="col-12">
          <div class="news-update-box">
            <div class="d-flex align-items-center gap-3">
              <a href="'.base_url('productDetails/'.$list['id'].'/'.$list['slug']).'">
                <img class="img-fluid news-update-image" src="'.product_img($list['id']).'" alt="'.$list['p_title'].'" />
              </a>
              <div class="news-update-content">
                <a href="'.base_url('productDetails/'.$list['id'].'/'.$list['slug']).'">
                  <h3>'.$list['p_title'].'</h3>
                </a>
                <div class="news-writer">
                  <h6>'.currency().' '.$discount.'</h6>
                  <h6>'.$category.'</h6>
                </div>
              </div>
            </div>
          </div>
          
        </div>
        ';
	      }
	    }else{
	        $html .='<div class="empty-tab">
        <img class="img-fluid empty-bell w-100" src="https://img.freepik.com/free-vector/search-concept-landing-page_52683-18573.jpg?t=st=1715692927~exp=1715696527~hmac=5bc262524619e9780f9924c1b10fc5348447cd835b1adae93cfadc714d000888&w=1380" alt="Service not available" />
        <h2>You have no item on marketplace!</h2>
        <p>Post your first item on marketplace</p>
        <div class="w-100">
          <div class="custom-container">
            <a href="'.base_url('marketplace').'" class="btn theme-btn empty-btn w-100 mt-0 p-3" role="button">Add your first Item</a>
          </div>
        </div>
      </div>';
	    }
        echo $html;
}
public function escapeString($string) {
    return remove_invisible_characters(html_escape($this->input->post($string)));
  }
public function getsubplan($module_id,$network)
{
  $this->db->where('network_id', $network);
  $this->db->where('parent_id', $module_id);
  $this->db->where('status', '1');
  $query_code = $this->db->get('sub_module');
  $countsub = '1';
  $output = '';
   foreach($query_code->result_array() as $subeach)
      {
           $countersub = $countsub++;
           $active = ($countersub == '1') ? 'active' : '';
          $output .='<li class="nav-item" role="presentation">
                        <button class="nav-link '.$active.'" id="items-tab'.$subeach['id'].'" data-bs-toggle="tab"
                            data-bs-target="#items-tab-pane'.$countersub.'" type="button" role="tab">'.$subeach['name'].'</button>
                </li>';
      }
  return $output;
}
public function databundleResult($subdetails,$network)
{
    $condition = '';
    $module_id = '2';
    $html = '';
     $html .= '<ul class="nav nav-tabs tab-style1" id="myTab" role="tablist">'; 
	   $html .= $this->getsubplan($module_id,$network);
       $html .= '</ul>'; 
    $count = '1';
    $html .='<div class="tab-content" id="myTabContent">';
        $html .= $this->getDataplans($network);
      $html .= '</div>';
  return $html;
}
	public function getDataPlan()
	{
	     $network = $this->escapeString('network');
	    $module_id = '2';
	    $html = '';
	    $count = '1';
	    $plans = '1';
	    $networkdetails = $this->User_model->get_network_single($module_id,$network);
	    $subdetails = $this->User_model->sub_module($module_id,$network);
	   $counter = $count++;
	   //$active = ($counter == '1') ? 'active' : '';
	   //$show = ($counter == '1') ? 'show active' : '';
	  
	    $html .=$this->databundleResult($subdetails,$network);
	    echo $html;
	    
	}
	
	public function ajax_refered()
	{
	    $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();
	    $count = $this->db->select('*')->from('account')->where('sponsor_id',$udetails['accountid'])->get()->num_rows();
	    echo $count;
	}
	public function ajax_total_deposit()
	{
	    $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();
	    //$gettotal_deposited = $this->User_model->getTotalFund($udetails['accountid'])->row_array(); 
	    echo '';//$this->User_model->getTotalFund($udetails['accountid']);
	    //echo $count;
	}
	public function ajaxTransactionStatistics($status)
	{
	    if($status == 'All')
	    {
	        $converstatus = '';
	    }elseif($status == 'Pending')
	    {
	        $converstatus = '2';
	    }elseif($status == 'Initiated')
	    {
	        $converstatus = '1';
	    }elseif($status == 'Completed')
	    {
	        $converstatus = '3';
	    }else{
	        $converstatus = "";
	    }
	    $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();
	    if($converstatus ==""){
	    $count = $this->db->select('*')->from('transactions')->where('user_id',$udetails['accountid'])->get()->num_rows();
	    }else{
	     $count = $this->db->select('*')->from('transactions')->where('user_id',$udetails['accountid'])->where('status',$converstatus)->get()->num_rows();     
	    }
	    echo $count;
	}
	public function airtim_fetch_discount($network)
  {
    $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();
    $user_id = $udetails['accountid'];
    $get_discount_value = $this->get_user_package($user_id);
     $network_details = $this->db->get_where('sub_module', array('parent_id'=>1,'network_id'=>$network,'name'=>'VTU'))->row_array();//$this->User_model->get_network_single('1',$network)->row_array();
      $discount = $network_details[$get_discount_value];
   return $discount;
  }
  public function get_user_package($user_id)
  {
    $details = get_user_info($user_id);
    if($details['package_id'] == '1'){
      $discount = 'user_percent';
    }elseif($details['package_id'] == '2'){
      $discount = 'reseller_percent';
    }elseif($details['package_id'] == '3'){
      $discount = 'api_percent';
    }else{
      $discount = 'user_percent';
    }
    return $discount;
  }
  
  public function ajaxGetNetworkBulk()
	{
	    $module_id = '1';
	    
	    $html = '';
	    $network = $this->User_model->get_network($module_id);
	    foreach($network->result_array() as $list){
	     $discount = $this->airtim_fetch_discount($list['network_id']);
	    $html .='<div class="col-3">
	                 <div class="switch-btn">
                    <div class="" style="height:50px">
                        <div class="vertical-box-img" style="width:70px">
                            <img class="img-fluid img" style="border: 1px solid #ddd;border-radius: 4px;padding: 5px;margin-left:5px;width: 40px;" src="'.base_url('assets/images/network/'.$list['network_img']).'" alt="'.$list['network_name'].'" />
                          <input name="network" type="radio" class="form-check-input airtime_network network" id=NetworkID"'.$list['id'].'" value="'.$list['network_id'].'" data-name="'.$list['network_name'].'">
                                
                        </div>
                        </div>
                </div></div>';
	    }
        echo $html;
	}

public function ajaxGetNetworkPIN()
	{
	    $module_id = '9';
	    
	    $html = '';
	    $network = $this->User_model->get_network($module_id);
	    foreach($network->result_array() as $list){
	     $discount = $this->airtim_fetch_discount($list['network_id']);
	    $html .='<div class="col-12">
	                 <div class="switch-btn">
                    <div class="vertical-product-box order-box" style="height:70px">
                        <div class="vertical-box-img" style="height:70px">
                            <img class="img-fluid img" style="height:70px" src="'.base_url('assets/images/network/'.$list['network_img']).'" alt="'.$list['network_name'].'" />
                        </div>

                        <div class="vertical-box-details">
                            <div class="vertical-box-head">
                                <div class="restaurant">
                                    <h5 class="dark-text">'.$list['network_name'].'</h5>
                                    <h5 class="theme-color" id="dicount">'.$discount.'%</h5>
                                </div>
                               
                            </div>
                            <div class="reorder d-flex align-items-center justify-content-between mt-2">
                                <span class="fw-normal success-color">Available</span>
                               
                                    <input name="network" type="radio" class="form-check-input airtime_network network" id=NetworkID"'.$list['id'].'" value="'.$list['network_id'].'" data-name="'.$list['network_name'].'">
                                
                            </div>
                        </div>
                    </div>
                </div></div>';
	    }
        echo $html;
	}

public function ajaxGetNetworkData()
	{
	    $html = '';
	    $network = $this->User_model->getNetwork($module='2');
	    if($network == false)
	    {
	        echo 'No active network';
	        exit;
	    }
	    foreach($network as $list){
	     $html .='<div class="col-md-3 col-6"><label style="width:100%">
         <div class="bill-box">
            <div class="d-flex gap-3">
            <h5 class="dark-text">'.$list['network_name'].'</h5>
              <div class="bill-icon">
                <input name="network" type="radio" style="display:none" class="form-check-input airtime_network datanetwork" id=NetworkID"'.$list['network_id'].'" value="'.$list['network_id'].'" data-name="'.$list['network_name'].'" networkname="'.$list['network_name'].'">
                 <img class="img-fluid icon network_img" src="'.base_url('assets/images/network/'.$list['network_img']).'" alt="'.$list['network_name'].'" />
               </div>
             
            </div>
            
          </div>
        </label></div>';
	    }
        echo $html;
	}
	
	
	public function ajaxGetExcessNetwork()
	{
	   $html = '';
	    $network = $this->User_model->getNetwork($module='7');
	     if($network == false)
	    {
	        echo 'No active network';
	        exit;
	    }
	    foreach($network as $list){
	     $html .='<div class="col-md-3 col-6"><label style="width:100%">
         <div class="bill-box">
            <div class="d-flex gap-3">
            <h5 class="dark-text">'.$list['network_name'].'</h5>
              <div class="bill-icon">
                 <input name="network" type="radio" style="display:none" class="form-check-input airtime_network datanetwork" id=NetworkID"'.$list['network_id'].'" value="'.$list['network_id'].'" data-name="'.$list['network_name'].'" networkname="'.$list['network_name'].'">
               <img class="img-fluid icon network_img" src="'.base_url('assets/images/network/'.$list['network_img']).'" alt="'.$list['network_name'].'" />
               </div>
             
            </div>
            
          </div>
        </label></div>';
	    }
        echo $html;
	}
	public function ajaxGetNetworkAirtimePIN()
	{
	   $html = '';
	    $network = $this->User_model->getNetwork($module='9');
	     if($network == false)
	    {
	        echo 'No active network';
	        exit;
	    }
	    foreach($network as $list){
	     $html .='<div class="col-md-3 col-6"><label style="width:100%">
         <div class="bill-box">
            <div class="d-flex gap-3">
            <h5 class="dark-text">'.$list['network_name'].'</h5>
              <div class="bill-icon">
                 <input name="network" type="radio" style="display:none" class="form-check-input airtime_network datanetwork" id=NetworkID"'.$list['network_id'].'" value="'.$list['network_id'].'" data-name="'.$list['network_name'].'" networkname="'.$list['network_name'].'">
               <img class="img-fluid icon network_img" src="'.base_url('assets/images/network/'.$list['network_img']).'" alt="'.$list['network_name'].'" />
               </div>
             
            </div>
            
          </div>
        </label></div>';
	    }
        echo $html;
	}
	public function geSubPlan(){
	    $module = $this->escapeString('module');
	    $network = $this->escapeString('network');
	    $output = '<option value="">Choose type</option>';
	    $result = $this->User_model->sub_module($module,$network);
	    if($result->num_rows() < 1)
	    {
	       
	        echo '<option value="">No Type was found for the selected network</option>';
	        
	    }
	    foreach($result->result_array() as $list){
	         $discount = $this->airtim_fetch_discount($list['network_id']);
	        $output .= '<option value="'.$list['id'].'" data-discount="'.$discount.'">'.$list['name'].'</option>';
	    }
	   echo $output;
	}
	public function ajaxGetNetworkAirtime()
	{
	   $html = '';
	    $network = $this->User_model->getNetwork($module='1');
	    if($network == false)
	    {
	        echo 'No active network';
	        exit;
	    }
	    foreach($network as $list){
	        if($list['network_id'] == '01'){ $color = '#FFCC08';}elseif($list['network_id'] == '02'){$color = '#FF0000';}elseif($list['network_id'] == '04'){$color = '#00FF00';}else{$color = '#006848ff ';}
	    $html .='<div class="col-md-3 col-6"><label style="width:100%">
         <div class="bill-box">
            <div class="d-flex gap-3">
            <h5 class="dark-text">'.$list['network_name'].'</h5>
              <div class="bill-icon">
                <input name="networkid" type="radio" class="form-check-input airtime_network network" id=NetworkID"'.$list['network_id'].'" value="'.$list['network_id'].'" data-name="'.$list['network_name'].'" networkname="'.$list['network_name'].'">
                <img class="img-fluid icon network_img" src="'.base_url('assets/images/network/'.$list['network_img']).'" alt="'.$list['network_name'].'" />
               </div>
             
            </div>
            
          </div>
        </label></div>';
	    }
        echo $html;
	}
	/*	public function ajaxGetBettingProvider()
	{
	   $module_id = '4';
	    $html = '';
	    $network = getBetting();
	    $currency = currency();
	    foreach($network->result_array() as $list){
	     $discount = '';//$this->airtim_fetch_discount($list['network_id']);
	    $html .='<div class="col-12">
	                 <div class="switch-btn">
                    <div class="vertical-product-box order-box" style="height:70px">
                       <div class="vertical-box-details">
                            <div class="vertical-box-head">
                                <div class="restaurant">
                                    <h5 class="dark-text">'.ucwords($list['provider']).'</h5>
                                    <h5 class="theme-color" id="dicount">'.$currency.' + '.get_settings('betting_charge').'</h5>
                                </div>
                               
                            </div>
                            <div class="reorder d-flex align-items-center justify-content-between mt-2">
                                <span class="fw-normal success-color">Available</span>
                               
                                    <input name="provider" type="radio" class="form-check-input provider" id=NetworkID"'.$list['id'].'" value="'.$list['provider'].'" data-name="'.$list['provider'].'">
                                
                            </div>
                        </div>
                    </div>
                </div></div>';
	    }
        echo $html;
	}

	
	public function ajaxGetProducts()
	{
	    $html = '';
	    $network = $this->User_model->getProducts();
	    foreach($network->result_array() as $list){
	   $state = get_states($list['state'])->row_array()['name'];
	      $lgas = get_bylgas($list['lga'])->row_array()['name'];
	      $udetails = get_user_info($list['user_id']);
	     $discount = $list['p_price'];
	       $html .='<div class="col-6">
                        <div class="grocery-product-box">
                            <h6 class="offer-tag">'.$state.' - '.$lgas.'</h6>
                          <div class="grocery-product-img">
                                <img class="img-fluid img" src="'.product_img($list['id']).'" alt="'.$list['p_title'].'">
                            </div>
                         <div class="grocery-product-details">
                            <h4>'.$list['p_title'].'</h4>
                            <h4 class="light-text price"><span class="theme-color">'.currency().' '.$discount.'</span></h4>
                            <a href="'.base_url('details/'.$list['id'].'/'.$list['slug']).'" class="btn theme-btn add-btn w-100 mt-3">'.currency().' '.$discount.'</a>

                        </div>
                            
                        </div>
                    </div>';
        }
        //$html .='</div>';
        echo $html;
	}
	
		
		*/
		
	
	public function ajaxGetExams()
	{
	   $html = '';
	    $network = $this->User_model->getNetwork($module='8');
	    foreach($network as $list){
	    $html .='<div class="col-md-3 col-6"><label style="width:100%">
         <div class="bill-box">
            <div class="d-flex gap-3">
            <h5 class="dark-text">'.$list['network_name'].'</h5>
              <div class="bill-icon">
                <input name="cabletv" style="display:none" type="radio" class="form-check-input cabletv" id=NetworkID"'.$list['network_id'].'" value="'.$list['network_id'].'" data-name="'.$list['network_name'].'">
                <img class="img-fluid icon network_img" src="'.base_url('assets/images/network/'.$list['network_img']).'" alt="'.$list['network_name'].'" />
               </div>
             
            </div>
            </div>
        </label></div>';
	    }
        echo $html;
	}
	
	
	
	public function ajaxGetNetworkCable()
	{
	   $html = '';
	    $network = $this->User_model->getNetwork($module='3');
	    if($network == false)
	    {
	        echo 'No active network';
	        exit;
	    }
	    foreach($network as $list){
	        if($list['network_id'] == '01'){ $tv = 'dstv';}elseif($list['network_id'] == '02'){$tv = 'gotv';}elseif($list['network_id'] == '03'){$tv = 'startimes';}else{$tv = 'showmax';}
	    $html .='<div class="col-md-3 col-6"><label style="width:100%">
         <div class="bill-box">
            <div class="d-flex gap-3">
            <h5 class="dark-text">'.$list['network_name'].'</h5>
              <div class="bill-icon">
                <input name="cabletv" style="display:none" type="radio" class="form-check-input cabletv airtime_network" id=NetworkID"'.$list['network_id'].'" value="'.$list['network_id'].'" data-name="'.$list['network_name'].'">
                <img class="img-fluid icon network_img" src="'.base_url('assets/images/network/'.$list['network_img']).'" alt="'.$list['network_name'].'" />
               </div>
             
            </div>
            </div>
        </label></div>';
	    }
        echo $html;
	}
	
	public function do_product()
  {
      $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();      
      $user_id = $udetails['sId'];
      $data['p_title'] = $this->escapeString('p_title');
      $data['p_description'] = $this->escapeString('p_description');
      $data['slug'] = slugify($this->escapeString('p_title'));
      $data['state'] = $this->escapeString('state');
      $data['lga'] = $this->escapeString('lga');
      $data['city'] = $this->escapeString('city');
      $data['phone'] = $this->escapeString('phone');
      $data['landmark'] = $this->escapeString('landmark');
      $data['item_condition'] = $this->escapeString('item_condition');
      $data['p_type'] = $this->escapeString('p_type');
      $data['user_id'] = $user_id;
      $data['p_cat'] = $this->escapeString('category');
      $data['date_created'] = date('Y-m-d H:i:s');
      $data['p_price'] = $this->escapeString('p_price');
      if(isset($_POST['is_draft']))
      {
          $data['is_draft'] = '1';
      }
       if(isset($_POST['itemid'])){
          $itemid = $this->escapeString('itemid');
          $pid = $this->escapeString('pid');
          $data['p_imgurl'] = 'assets/images/product/'.$itemid.'.jpg';
          $this->db->where('itemid',$itemid);
          $this->db->update('products',$data);
      }else{
         $itemid= $this->uniqueid();
       $data['itemid'] = $itemid;
       $data['p_imgurl'] = 'assets/images/product/'.$itemid.'.jpg';
      $this->db->insert('products',$data);
      $pid = $this->db->insert_id();
      }
       if ($_FILES['displayImg']['name'] != "") {
            move_uploaded_file($_FILES['displayImg']['tmp_name'], 'assets/images/product/'. $itemid .'.jpg');
       }
       
      $upload_path = 'assets/images/product/gallery/';
      $config = array(
            'upload_path'   => $upload_path,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'overwrite'     => 1,                       
        );

     $this->load->library('upload', $config);
       $dataInfo = [];
            $this->load->library('upload');
            $files = $_FILES;
            if(!empty($_FILES['imageUpload']['name'][0])) {
                $cpt = count($_FILES['imageUpload']['name']);

                for ($i = 0; $i < $cpt; $i++) {
                    $_FILES['imageUpload']['name'] = $files['imageUpload']['name'][$i];
                    $_FILES['imageUpload']['type'] = $files['imageUpload']['type'][$i];
                    $_FILES['imageUpload']['tmp_name'] = $files['imageUpload']['tmp_name'][$i];
                    $_FILES['imageUpload']['error'] = $files['imageUpload']['error'][$i];
                    $_FILES['imageUpload']['size'] = $files['imageUpload']['size'][$i];
                    $_FILES['encrypt_name'] = TRUE;
                    $this->upload->initialize($this->set_upload_options());
                    $this->upload->do_upload('imageUpload');
                    $dataInfo[] = $this->upload->data();
                    $image_url = "assets/images/product/gallery/" . $dataInfo[$i]['file_name'];

                    $imagedata = [
                        'image_gallery_id' => $this->uniqueid(),
                        'itemid' => $pid,
                        'imgurl' => $image_url,
                    ];
                    $result2 = $this->User_model->image_entry($imagedata);
                }
            }
      
     
     send_notification($pid,$udetails['email']);
      $this->session->set_flashdata('success_msg', 'Item updated successfully');
      redirect('myproducts');
  }
  public function uniqueid()
  {
    $un = substr(number_format(time() * rand(),0,'',''),0,12);
    return $un;
  }
  
  public function editItem($id)
  {
  $udetails = $this->User_model->get_user_by_session($this->session->userdata('login_id'))->row_array();       $user_id = $udetails['accountid'];
  $myproducts = getproducts($id);
  if($myproducts->num_rows() > 0){
     $details = $myproducts->row_array();
     if($details['user_id'] == $user_id){
   $page_data['myproducts'] = $details;
   $page_data['page_name'] = "addproduct";
     }else{
          $page_data['page_name'] = "itemnotfound";
     }
  }else{
   $page_data['page_name'] = "itemnotfound";
  }
   $page_data['page_title'] = 'Add Product';
    $this->load->view('Account/index', $page_data);
  }

  
}