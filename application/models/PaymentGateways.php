<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PaymentGateways extends CI_Model {

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

public function paystackVerify()
 {
      $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();       $user_id = $udetails['accountid'];
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
  $pickDetails = $this->db->get_where('deposits',array('reference'=>$reff));
  if($pickDetails->num_rows() > 0){
      $row = $pickDetails->row_array();
      if($row['user_id'] != $user_id)
      {
        $this->session->set_flashdata('error_message','Unable to continue with the request. Please contact support');
      redirect('addfund'); 
      exit;
      }
      
      if($row['payment_status'] == '1')
      {
      $this->session->set_flashdata('error_message','Payment already completed. Please start another process');
      redirect('addfund'); 
      exit;
      }
      $this->User_model->updatePaymentStatus($user_id,$reff,$row['amount']);
      $this->session->set_flashdata('success_msg','Payment successful and wallet balance has been updated');
      redirect('addfund'); 
  }else{
      $this->session->set_flashdata('error_message','Unable to continue with the request. Please contact support');
      redirect('addfund');
      exit;
  }
  }
 }
 public function paystack_standard($amount) {
                $pk_key = payment_getways('1')['pk_live'];
                $sk_key = payment_getways('1')['sk_live'];
                 $gid = payment_getways('1')['gateway_id'];
                $reference = $this->uniqueid();
                if((empty($pk_key))||(empty($sk_key))){ $this->session->set_flashdata('error_message','Unable to continue with the request. Please contact support');redirect('addfund');}
                $result = array();
                $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();       $user_id = $udetails['accountid'];
                $email = get_user_info($user_id)['email'];
                $this->User_model->Adddeposits($reference,$amount,$user_id,$gid);
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
               $response = curl_exec($ch);
                $err = curl_error($ch);
                if($err){
                  die('Curl returned error: ' . $err);
                }
                $tranx = json_decode($response, true);
                if(!$tranx['status']){
                  // there was an error from the API
                  print_r('API returned error: ' . $tranx['message']);
                }
                 header('Location: ' . $tranx['data']['authorization_url']);

     }
public function flutterwave_standard($amount)
{
   
                $pk_key = payment_getways('2')['pk_live'];
                $sk_key = payment_getways('2')['sk_live'];
                $gid = payment_getways('2')['gateway_id'];
                $reference = $this->uniqueid();
                if((empty($pk_key))||(empty($sk_key))){ $this->session->set_flashdata('error_message','Unable to continue with the request. Please contact support');redirect('addfund');}
                $result = array();
                $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();       $user_id = $udetails['accountid'];
                $email = get_user_info($user_id)['email'];
                $this->User_model->Adddeposits($reference,$amount,$user_id,$gid);
                $currency = "NGN";
                $customerData = ["email"];
                $customerData = array('name'=>$udetails['fullname'],'customer_email' => $email,'phonenumber'=>$udetails['phone']);
                 $callback_url = base_url().'flutterwaveVerify';
                 $postdata =  array('customer_email' => $email,'PBFPubKey'=>$sk_key, 'amount' => $amount,"currency"=>$currency,"txref" => $reference, "redirect_url" => $callback_url);
                $url = "https://api.flutterwave.com/v3/payments";
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
               $response = curl_exec($ch);
                $err = curl_error($ch);
                if($err){
                  die('Curl returned error: ' . $err);
                }
                $tranx = json_decode($response, true);
                print_r($response);
                exit;
                 header('Location: ' . $tranx['data']['link']);
}
   public function monnify_standard($amount) {
    $authUrl = "https://api.monnify.com/api/v1/auth/login/";
   $verifyUrl = 'https://api.monnify.com/api/v2/transactions/';
   $initiate_payment = 'https://api.monnify.com/api/v1/merchant/transactions/init-transaction';
    $apikey_sklive = get_siteconfig('monifySecrete');
       $apikey_live = get_siteconfig('monifyApi');
       $contract_code = get_siteconfig('monifyContract');
       $monifyCharges = $this->calculate_percentage(get_siteconfig('monifyCharges'),$amount);
    $amount = ($amount + $monifyCharges);
    $udetails = $this->User_model->get_user_by_session($this->session->userdata('loginId'))->row_array();
    $customer_name = $udetails['sFname'];
    $customer_email = $udetails['sEmail'];
    $invoice_number = $this->uniqueid();
    $paymentDescription = 'Wallet Funding';
    $currencyCode = 'NGN';
    $redirectUrl = base_url('Account/monnify_verify');
    $paymentMethods = ["CARD"];
    $post_data = array('amount' => $amount,'customerName' => $customer_name,'customerEmail' => $customer_email,'paymentReference' => $invoice_number,'paymentDescription' => $paymentDescription,'currencyCode' => $currencyCode,'contractCode' => $contract_code,'redirectUrl' => $redirectUrl,'paymentMethods' => $paymentMethods);
    $curl = curl_init();
       curl_setopt_array($curl, array(
           CURLOPT_URL => $authUrl,
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_HTTPHEADER => array(
               "Authorization: Basic " . base64_encode($apikey_live . ':' . $apikey_sklive)
           ),
       ));
       $result = curl_exec($curl);
       
       $response = json_decode($result);
        curl_close($curl);
        if(!isset($response->responseBody->accessToken))
        {
             $this->session->set_flashdata(array('error_message'=> 'Please check your monnify Keys'));
            redirect('addfund');  
        }
    $curl = curl_init();
    curl_setopt_array($curl, array(
           CURLOPT_URL => $initiate_payment,
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_ENCODING => "",
           CURLOPT_MAXREDIRS => 10,
           CURLOPT_TIMEOUT => 0,
           CURLOPT_FOLLOWLOCATION => true,
           CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
           CURLOPT_CUSTOMREQUEST => "POST",
           CURLOPT_POSTFIELDS => json_encode($post_data), 
           CURLOPT_HTTPHEADER => array(
               "Content-Type: application/json",
               'Authorization: Bearer ' .$response->responseBody->accessToken
           ),
       ));
    $response_secod = curl_exec($curl);
   $result = json_decode($response_secod);
   curl_close($curl);
   //echo $response_secod;
   if(isset($result->responseBody->transactionReference)){
  $reference = $result->responseBody->transactionReference;
   $paymentReference = $result->responseBody->paymentReference;
   $checkoutUrl = $result->responseBody->checkoutUrl;
   redirect($checkoutUrl, 'refresh');
   }else{
      $this->session->set_flashdata(array('error_message'=>'Reference ID exist. Start a new payment process'));
        redirect('addfund');  
   }
  //echo $response_secod;
  
}
}