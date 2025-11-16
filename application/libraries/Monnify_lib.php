<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 

/**  
 * Paystack Standard Library for CodeIgniter 3.x
 * 
 * Library for Paystack payment gateway. This follows the Paystack
 * Standard form submission, which redirects customers to paystack's
 * secured page, where the payment is made.
 * 
 * This class handles submission of an order (purchasing of a package)
 * to Paystack, as well as processing Payment Verification process.
 * 
 * @package CodeIgniter
 * @subpackage Libraries
 * @category eCommerce (Payments)
 * @author Samuel Asor
 * @link https://github.com/Sammyskills/codeigniter-paystack-library
*/
class Monnify_lib
{
public function monify_auth(){
//$monify_authorization_token = $this->db->get_where('settings' , array('key'=>'monify_authorization_token'))->row()->value;
$apikey = payment_getways('7')['pk_live'];
$monify_secret = payment_getways('7')['sk_live'];
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_USERPWD, $apikey . ":" . $monify_secret);
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.monnify.com/api/v1/auth/login",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => array(
      "Content-Type: application/json",
   
  ),
));
$response = curl_exec($curl);
curl_close($curl);
$result = json_decode($response,true);
return $result['responseBody']['accessToken'];
//echo $response;
  }
  
public function validateAccount($bank,$account){
      $apikey = payment_getways('7')['pk_live'];
       $monify_secret = payment_getways('7')['sk_live'];
       $monify_contract = payment_getways('7')['contract_code'];
       //$get_user = get_user_info($user_id);
       $authorization = $this->monify_auth();
        $curl = curl_init();
        //curl_setopt($curl, CURLOPT_USERPWD, $authorization);
     curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.monnify.com/api/v1/disbursements/account/validate?accountNumber=".$account."&bankCode=".$bank,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
     'Content-Type: application/json',
    'Authorization: Bearer '.$authorization
  ),
));
       $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response,true);
        if(isset($result['responseBody']['accountName'])){
       $account_number =  $result['responseBody']['accountName'];
        }else{
            $account_number = 'This account cannot be verify';
        }
       return $account_number;
       
  }
public function monnify_standard($reference) {
     $authUrl = "https://api.monnify.com/api/v1/auth/login/";
    $verifyUrl = 'https://api.monnify.com/api/v2/transactions/';
    $initiate_payment = 'https://api.monnify.com/api/v1/merchant/transactions/init-transaction';

    $apikey_sktest = $this->CI->db->get_where('payment_gateways',array('gateway_id'=>'4'))->row_array()['sk_test'];
        $apikey_test = $this->CI->db->get_where('payment_gateways',array('gateway_id'=>'4'))->row_array()['pk_test'];
        $apikey_sklive = $this->CI->db->get_where('payment_gateways',array('gateway_id'=>'4'))->row_array()['sk_live'];
        $apikey_live = $this->CI->db->get_where('payment_gateways',array('gateway_id'=>'4'))->row_array()['pk_live'];
        $apikey_mode = $this->CI->db->get_where('payment_gateways',array('gateway_id'=>'4'))->row_array()['mode'];
        $contract_code = $this->CI->db->get_where('payment_gateways',array('gateway_id'=>'4'))->row_array()['contract_code'];

     $payment_details = $this->User_model->get_deposit_details($reference)->row_array();
     $amount = ($payment_details['amount'] + $payment_details['getpercentage']);
     $get_user = $this->User_model->user_details($this->session->userdata('login_id'))->row_array();   
     $customer_name = $get_user['phone'];
     $customer_email = $get_user['email'];
     $invoice_number = $payment_details['invoice_number'];
     $paymentDescription = 'Wallet Funding';
     $currencyCode = 'NGN';
     $redirectUrl = base_url('Paystack/monnify_verify/'.$invoice_number);
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
        $response = json_decode(curl_exec($curl));
         curl_close($curl);
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
    //if(isset())
   $reference = $result->responseBody->transactionReference;
    $paymentReference = $result->responseBody->paymentReference;
    $checkoutUrl = $result->responseBody->checkoutUrl;
    redirect($checkoutUrl, 'refresh');
   //echo $response_secod;
   
}
}