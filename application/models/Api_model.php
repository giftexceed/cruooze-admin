<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_model extends CI_Model {

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
    public function addSubscriptionPayment($reference,$price,$userid,$planrowsid,$gateway)
    {
        $data['amount'] = $price;
        $data['user_id'] = $userid;
        $data['plan_id'] = $planrowsid;
        $data['date_created'] = date('Y-m-d');
        $data['reference'] = $reference;
        $data['gateway'] = $gateway;
        
        $this->db->insert('subscriptionPayment', $data);
        return true;
        
    }
    public function uniqueidwithlenght($lenght)
  {
    $un = substr(number_format(time() * rand(),0,'',''),0,$lenght);
    return $un;
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
    
     public function trim_and_return_json_upload($eventid,$reference,$untrimmed_array = [])
    {
        if (!is_array($untrimmed_array)) {
            $untrimmed_array = [];
        }
        $trimmed_array = array();
        if (!file_exists('assets/app/eventsFiles/'.$reference)) {
                        mkdir('assets/app/eventsFiles/'.$reference, 0777, true);
                    }
                    
        if (sizeof($untrimmed_array) > 0) {
            foreach ($untrimmed_array as $row) {
                if ($row != "") {
                    if($row->filetype =='video')
                    {
                      $extension = '.mp4';
                    }else{
                        $extension = '.png';
                    }
                    $base64_image_string = $row->fileData;
                    $uniqid = $this->uniqueidwithlenght('5');
                    $decoded_image_data = base64_decode($base64_image_string);
                    $fextension = pathinfo($decoded_image_data, PATHINFO_EXTENSION);
                     $file_path = 'assets/app/eventsFiles/'.$reference.'/'.$uniqid.$fextension; // Specify the desired file path and extension
                     file_put_contents($file_path, $decoded_image_data);
                     
                     /*
                     
                     $image_parts = explode(";base64,", $base64Image);
                     $image_type_aux = explode("image/", $image_parts[0]);
                     $image_type = $image_type_aux[1];
                     $image_base64 = base64_decode($image_parts[1]);
    
                    $uploadDir = 'assets/app/eventsFiles/';
                    $uniqid = $this->uniqueidwithlenght('5');
                    $filePath = $uploadDir . $uniqid . '.' . $image_type;
                    
                    file_put_contents($filePath, $image_base64);
    
                 //  move_uploaded_file($row->fileData['tmp_name'], 'assets/app/eventsFiles/' . $row->fileName.$extension);
                   $data['filetype'] = $row->filetype;
                   $data['fileName'] = $row->fileName;
                   $data['fileData'] = base_url().'assets/app/eventsFiles/'.$uniqid.$image_type;
                   
                   
                   */
                   
                  // $fileName = $_FILES['imagefile']['name'];
                  //$ext = pathinfo($fileName, PATHINFO_EXTENSION);
        
        
                   $data['filetype'] = $fextension;
                   $data['fileName'] = $uniqid.$fextension;
                   $data['fileData'] = base_url().'assets/app/eventsFiles/'.$reference.'/'.$uniqid.$fextension;
                   
                   
                    $imgURL = base_url().'assets/app/eventsFiles/'.$reference.'/'.$uniqid.$fextension;

                  $dataImag = $imgURL;
                   
                //   $dataR['fileurl'] =   base_url().'assets/app/eventsFiles/'.$uniqid.$extension;
                 //  $dataR['file_name'] = $uniqid.$extension;
                 //  $dataR['file_type'] =  $extension;
                 //  $dataR['rawFile'] = $row->fileData;
                 //  $dataR['event_id'] = $eventid;
                  // $this->db->insert('eventFiles', $dataR);
                   
                    array_push($trimmed_array, $dataImag);
                }
            }
        }
        return json_encode($trimmed_array);
    }
    
    
  
  
  public function trim_and_return_json_upload_update($eventid,$untrimmed_array = [])
    {
        if (!is_array($untrimmed_array)) {
            $untrimmed_array = [];
        }
        $trimmed_array = array();
        if (sizeof($untrimmed_array) > 0) {
            foreach ($untrimmed_array as $row) {
                if ($row != "") {
                    if($row->filetype =='video')
                    {
                      $extension = '.mp4';
                    }else{
                        $extension = '.png';
                    }
                    $base64_image_string = $row->fileData;
                    $uniqid = $this->uniqueidwithlenght('5');
                    $decoded_image_data = base64_decode($base64_image_string);
                     
                     $file_path = 'assets/app/eventsFiles/'.$reference.'/'.$uniqid.$extension; // Specify the desired file path and extension
                     file_put_contents($file_path, $decoded_image_data);
                     
                   
                   
                   
                   $data['filetype'] = $extension;
                   $data['fileName'] = $uniqid.$extension;
                   $data['fileData'] = base_url().'assets/app/eventsFiles/'.$reference.'/'.$uniqid.$extension;
                   
                   
                    $imgURL = base_url().'assets/app/eventsFiles/'.$uniqid.$extension;

                  $dataImag = $imgURL;
                  
                  
                  
                  /*
                  $data['filetype'] = $row->filetype;
                   $data['fileName'] = $row->fileName;
                   $data['fileData'] = base_url().'assets/app/eventsFiles/'.$uniqid.$extension;
                   
                   $dataImag[] = base_url().'assets/app/eventsFiles/'.$uniqid.$extension;
                   */
                   
                  $dataR['fileurl'] =   base_url().'assets/app/eventsFiles/'.$uniqid.$extension;
                   $dataR['file_name'] = $uniqid.$extension;
                   $dataR['file_type'] =  $extension;
                   $dataR['rawFile'] = $row->fileData;
                  // $dataR['event_id'] = $eventid;
                   $this->db->where('event_id',$eventid);
                   $this->db->update('eventFiles', $dataR);
                   
                    array_push($trimmed_array, $dataImag);
                }
            }
        }
        return json_encode($trimmed_array);
    }
    
    
    
    
    public function uniqueid()
      {
        $un = substr(number_format(time() * rand(),0,'',''),0,12);
        return $un;
      }
      public function addEventinfo($insertid,$event_name,$starttime,$closetime,$days)
      {
          $data['event_name'] = $event_name;
          $data['starttime'] = $starttime;
          $data['closetime'] = $closetime;
          $data['days'] = $days;
          $data['event_id'] = $insertid;
           $this->db->insert('eventinfo', $data);
        return true;
      }
      
      
      public function UpdateEvent($event_title,$event_location,$event_description,$event_date,$event_time,$event_city,$event_state,$user_id,$close_time,$monday,$tuesday,$wednesday,$thursday,$friday,$saturday,$sunday,$eventchild,$eventfiles,$ads_type,$event_id,$contactName,$contactPhone,$activities,$days,$endDate,$endTime)
    {
        /*
        if(!empty($eventfiles))
        {
            // move_uploaded_file($_FILES['upcoming_image_thumbnail']['tmp_name'], 'uploads/thumbnails/upcoming_thumbnails/' . $data['upcoming_image_thumbnail']);
        }
        */
        $getEventDetails = $this->db->get_where('events', array('id' => $event_id))->row_array();
        
        $reference = $getEventDetails['reference'];
        
        if(empty($user_id))
        {
        $data['user_id'] = '0';
        }else{
        $data['user_id'] = $user_id;
        }
        $data['event_title'] = $event_title;
        $data['event_description'] = $event_description;
        $data['event_date'] = $event_date;
        $data['event_location'] = $event_location;
        $data['event_time'] = $event_time;
        $data['approval_status'] = '0';
        //$data['reference'] = $this->uniqueid();
        
        $data['close_time'] = $close_time;
        $data['monday'] = $monday;
        
        $data['tuesday'] = $tuesday;
        $data['wednesday'] = $wednesday;
        
        $data['thursday'] = $thursday;
        $data['friday'] = $friday;
        
        $data['saturday'] = $saturday;
        $data['sunday'] = $sunday;
        
         $data['days'] = $this->trim_and_return_json($days);
         
         $data['endDate'] = $endDate;
        $data['endTime'] = $endTime;
        
         $data['contactPhone'] = $contactPhone;
        $data['contactName'] = $contactName;
         $data['activities'] = json_encode($activities);//$this->trim_and_return_json($activities);
     if (is_array($eventchild)) {
         if (count($eventchild) != 0) {
        $data['eventchild'] = $this->trim_and_return_json($eventchild);
         }
     }
         
        if (is_array($eventfiles)) {
           if (count($eventfiles) != 0) {
        $data['eventfiles'] = $this->trim_and_return_json($eventfiles);//$this->trim_and_return_json_upload($event_id,$reference,$eventfiles);
          
        $data['event_img_link'] = $this->trim_and_return_json($eventfiles); //$this->trim_and_return_json_upload($event_id,$reference,$eventfiles);
          }
      }
         $data['ads_type'] = $ads_type;
         $this->db->where('id', $event_id);
        $this->db->update('events', $data);
      
        return $event_id;
        
    }
    
    
    
    
    
    public function addEvent($event_title,$event_location,$event_description,$event_date,$event_time,$event_city,$event_state,$user_id,$close_time,$monday,$tuesday,$wednesday,$thursday,$friday,$saturday,$sunday,$eventchild,$eventfiles,$ads_type,$contactName,$contactPhone,$activities,$days,$endDate,$endTime)
    {
        if(!empty($eventfiles))
        {
            // move_uploaded_file($_FILES['upcoming_image_thumbnail']['tmp_name'], 'uploads/thumbnails/upcoming_thumbnails/' . $data['upcoming_image_thumbnail']);
        }
        
        
        if(empty($user_id))
        {
        $data['user_id'] = '0';
        }else{
        $data['user_id'] = $user_id;
        }
        $reference = $this->uniqueid();
        $data['event_title'] = $event_title;
        $data['event_description'] = $event_description;
        $data['event_date'] = $event_date;
        $data['event_location'] = $event_location;
        $data['event_time'] = $event_time;
        $data['approval_status'] = '0';
        $data['reference'] = $reference;
        
        $data['close_time'] = $close_time;
        $data['monday'] = $monday;
        
        $data['tuesday'] = $tuesday;
        $data['wednesday'] = $wednesday;
        
        $data['thursday'] = $thursday;
        $data['friday'] = $friday;
        
        $data['saturday'] = $saturday;
        $data['sunday'] = $sunday;
        
        $data['days'] = $this->trim_and_return_json($days);
        
        
        $data['endDate'] = $endDate;
        $data['endTime'] = $endTime;
        
        
        $data['contactPhone'] = $contactPhone;
        $data['contactName'] = $contactName;
        $data['eventchild'] = $this->trim_and_return_json($eventchild);
         $data['ads_type'] = $ads_type;
         $data['activities'] = json_encode($activities);//$this->trim_and_return_json($activities);
        $this->db->insert('events', $data);
        $eventid = $this->db->insert_id();
        
        $filesData['event_img_link'] = $this->trim_and_return_json($eventfiles);//$this->trim_and_return_json_upload($eventid,$reference,$eventfiles);
        $filesData['eventfiles'] = $this->trim_and_return_json($eventfiles);//$this->trim_and_return_json_upload($eventid,$reference,$eventfiles);
        $this->db->where('id', $eventid);
        $this->db->update('events',$filesData);
        return $eventid;
        
    }

}