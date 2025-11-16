 <div class="row">
     
        <div class="col-lg-12">
            
            <div class="card">
                
                <div class="card-body p-0">
<div class="table-responsive">
      <table class="table--light style--two custom-data-table table">
        <thead>
                <tr>
                          <th>Account name</th>
                        
                          <th>Date</th>
                         <th>Amount</th>
                          <th>Status</th>
                          <th>Payment Method</th>
                          
                        </tr>
                <tr>
                   <?php 
                   if($data->num_rows() > 0){
                    foreach ($data->result_array()  as $list){ 
                    if($list['status']=='0'){
                        $get_status = 'Pending';
                    }elseif($list['status']=='1'){
                    $get_status = 'Approved';
                    }else{
                        $get_status = 'Failed';
                    }
                
                 
                    
              ?>
                          
                          <tr>
                          <td><?php echo get_user_info($list['user_id'])['fullname'];?></td>
                          <td><?php echo dateConvert($list['date_created']);?> </td>
                          <td><?php echo currency();?> <?php echo $list['amount'];?></td>
                          <td><?php echo $get_status;?></td>
                          <td><?php echo payment_getways($list['gateway'])['gateway_name'];?></td>
                          
                        
                        </tr>
                              <?php }
                  }else{ ?>
                    <tr>
                  <td colspan="11"><p class="text-danger text-center m-b-0">No Records Found</p></td></tr>
                  <?php }?>
                </tr>
              </table>
              </div>
             </div>
            </div>
        </div>
    </div>