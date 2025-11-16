 <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
        <thead style="background-color: gray;">
                <tr>
                    <th style="color: #fff;">SN</th>
                          <th style="color: #fff;">CustomerName</th>
                          <th style="color: #fff;">Beneficiary</th>
                            <th style="color: #fff;">Reference ID</th>
                         <th style="color: #fff;">Charged</th>
                          <th style="color: #fff;">Date</th>
                          <th style="color: #fff;">Prev. Bal.</th>
                          <th style="color: #fff;">New Bal.</th>
                          <th style="color: #fff;">Status</th>
                          <th style="color: #fff;">Details</th>
                          
                        </tr>
                </thead>
                <tr>
                   <?php 
                         if(!empty($history)) 
                  {
                      $count = 1;
                    foreach ($history->result_array()  as $list){ 
                    if($list['status'] == '1')
                    {$caption = 'completed';$class = 'success';}else{$caption = 'failed';$class = 'danger';}?>
                          <tr>
                        <td><?php echo $count++;?></td>
                          <td><a href="<?php echo adminController();?>customerDetails/<?php echo $list['user_id']; ?>" targe="blank"><?php echo get_user_info($list['user_id'])['fullname'];?></a></td>
                         <td><?php echo $list['recipient'];?></td>
                         <td><a href="<?php echo adminController();?>trans_details/<?php echo $list['id']; ?>"><?php echo $list['order_id'];?></a></td>
                         <td><?php echo currency();?> <?php echo $list['charge'];?></td>
                          <td><?php echo dateConvert($list['date_added']);?> - <?php echo $list['trans_time'];?> </td>
                          <td><?php echo currency();?> <?php echo $list['prev_bal'];?></td>
                          <td><?php echo currency();?> <?php echo $list['new_bal'];?></td>
                          <td><span class="badge bg-<?php echo $class;?>"><?php echo status_code($list['status'])['value'];?></span></td>
                          <td><a href="<?php echo adminController();?>trans_details/<?php echo $list['id']; ?>" class="btn btn-success btn-sm">Details</a></td>
                        </tr>
                              <?php }
                  }else{ ?>
                    <tr>
                  <td colspan="11"><p class="text-danger text-center m-b-0">No Records Found</p></td></tr>
                  <?php }?>
                </tr>
              </table>
              
								
								</div>
	