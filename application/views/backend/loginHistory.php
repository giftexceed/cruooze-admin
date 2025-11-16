  <div class="row">
            <div class="col-lg-12"> 
        <div class="card flex-fill">
            <div class="card-body">
 <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
        <thead style="background-color: gray;">
                <tr>
                    <th style="color: #fff;">SN</th>
                          <th style="color: #fff;">UserEmail</th>
                          <th style="color: #fff;">Date / Time</th>
                          <th style="color: #fff;">Login Count</th>
                          
                          <th style="color: #fff;">MetaData</th>
                        
                          
                        </tr>
                </thead>
                <tr>
                   <?php 
                         if(!empty($data)) 
                  {
                      $count = 1;
                    foreach ($data->result_array()  as $list){
                        
                    ?>
                          <tr>
                        <td><?php echo $count++;?></td>
                          <td><a href="<?php echo adminController();?>customerDetails/<?php echo $list['user_id']; ?>" targe="blank"><?php echo get_user_info($list['user_id'])['email'];?></a></td>
                         <td><?php echo dateConvert($list['date_login']);?> <br/> <?php echo $list['time_login'];?></td>
                          <td><?php echo $this->db->get_where('daily_login',array('user_id'=>$list['user_id'],'date_login' => $list['date_login']))->num_rows();?></td>
                         
                          <td><?php echo $list['metadata'];?></td>
                         
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