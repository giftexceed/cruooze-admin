<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }
public function login($username,$password) 
{
    $hash=substr(sha1(md5($password)), 3, 10);
    $this->db->where('sPhone', $username);
    $this->db->where('sPass', $hash);
    $query = $this->db->get('subscribers');
    if ($query->num_rows() == 1) {
        $user = $query->row_array();
        return $user;
    } else {
        return false;
    }
}
public function loginwithEmailPhone($username) 
{
    $this->db->where('email',$username);
    $this->db->or_where('phone',$username);
    $query = $this->db->get('account');
    if ($query->num_rows() > 0) {
        $user = $query->row_array();
        return $user;
    } else {
        return false;
    }
}
 public function Is_already_register($id,$email)
 {
  $this->db->where('login_oauth_uid', $id);
  $this->db->or_where('sEmail', $email);
  $query = $this->db->get('subscribers');
  if($query->num_rows() > 0)
  {
   return $query->row_array();
  }
  else
  {
   return false;
  }
 }
 public function create_account_google($fname,$lastname,$id,$email,$picture,$current_date)
 {
     
     $data['fullname'] = $fname.' '.$lastname;
     $data['email'] = $email;
     $data['login_oauth_uid'] = $id;
    // $data['picture'] = $picture;
     $data['date_created'] = $current_date;
     $this->db->insert('account', $data);
     return $this->db->insert_id();
 }

public function loginwithEmail($username) 
{
    $this->db->where('email',$username);
    $query = $this->db->get('account');
    if ($query->num_rows() == 1) {
        $user = $query->row_array();
        return $user;
    } else {
        return false;
    }
}
public function update_session_id($user_id,$session_id)
{
   $data['session_id'] = $session_id;
  $this->db->where('accountid',$user_id);
  $this->db->update('account',$data);
   return true;
}
public function insert_login($user_id,$last_login_ip,$session_id)
{
   // $getloc = json_decode(file_get_contents('http://ipinfo.io/'.$last_login_ip));
   $data['user_id'] = $user_id;
    $data['date_login'] = date('Y-m-d');
    $data['ip'] = $last_login_ip;
    $data['time_login'] = date('H:i:s');
    $data['session_id'] = $session_id;
    $data['browser'] = $this->agent->browser();
    //$data['location'] = $getloc->city;
    $this->db->insert('daily_login',$data);
   return true;
}
public function validate_refcode($login_id) {
    $this->db->where('phone', $login_id);
    $result = $this->db->get('account');
    if($result->num_rows() > 0)
    {
        return $result->row_array();
    }else{
    return false;
    }
}
public function clear_token($token,$email)
{
    $data['token'] = '' ;
       $this->db->where('token', $token);
        $this->db->where('email', $email);
        $this->db->update('account', $data); 
    return true;
}
public function changePassword($hash_password,$email)
{
    $data['tempPassword'] = '0';
    $data['sPass'] = $hash_password;
    $this->db->where('email', $email);
    $this->db->update('account', $data); 
    return true;
}
public function update_new_password($token,$hash_password,$email)
{
        $data['password'] = $hash_password ;
         $data['activation_token'] = '';
        $this->db->where('activation_token', $token);
        $this->db->where('email', $email);
        $this->db->update('account', $data); 
    return true;
}
public function token_login($phone) 
{
    $this->db->where('activation_token',$phone);
    $query = $this->db->get('account');
    if ($query->num_rows() == 1) {
        $user = $query->row_array();
        return $user;
    } else {
        return false;
    }
}
public function update_user_token($phone,$token){

    $data['activation_token'] = $token;
   $this->db->where('phone', $phone);
    $this->db->update('account', $data);
}
public function checkEmailorPhone($string) 
{
    $this->db->where('phone', $string);
    $this->db->or_where('email', $string);
   $query = $this->db->get('account');
    if ($query->num_rows() == 1) {
        $user = $query->row_array();
        return $user;
    } else {
        return false;
    }
}
public function check_exist($email,$phone) 
{
    $this->db->where('phone', $phone);
    $this->db->or_where('email', $email);
   $query = $this->db->get('account');
    if ($query->num_rows() == 1) {
        //$user = $query->row_array();
        return true;
    } else {
        return false;
    }
}
public function register_new($data = array()) {
    $insert = $this->db->insert('account', $data);
    if($insert){
        return $this->db->insert_id();
    }else{
        return false;
    }
}

}