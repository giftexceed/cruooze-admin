<style>
    .list-group-item{
        background-color:#fff
    }
</style>
<div class="row">
<div class="col-12">
    
    <div class="card">
        <div class="card-header with-border">
          <h4 class="card-title">Transaction Details</h4>
        </div>
        <!-- /.box-header -->
        <div class="card-body">
    
        
        <ul class="list-group">
        <li class="list-group-item">
                <b>Customer Identity:</b>
                <?php echo get_user_info($data['user_id'])['fullname'];?> (<?php echo get_user_info($data['user_id'])['email'];?>) 
                <a href="<?php echo adminController();?>customerDetails/<?php echo $data['user_id'];?>" class="btn btn-sm btn-outline--primary">
                                            <i class="las la-desktop"></i> See customer details                                        </a>
                                            </li>
            
            <li class="list-group-item">
                <b>Transaction No:</b> <span class="text-end"><?php echo $data['order_id'];?></span>            </li>
            <li class="list-group-item">
                <b>Service:</b><?php if($data['module_id'] != '0') { echo get_module($data['module_id'])['module'];};?>            </li>
            <li class="list-group-item">
                <b>Description:</b> <?php echo $data['description'];?></li>
            <li class="list-group-item">
                <b>Amount:</b><?php echo $data['charge'];?>            </li>
             <li class="list-group-item">
                <b>Initial Balance:</b>
                <?php echo currency();?> <?php echo $data['prev_bal'];?>     </li>
             <li class="list-group-item">
                <b>New Balance:</b>
               <?php echo currency();?> <?php echo $data['new_bal'];?>         </li>
            <li class="list-group-item">
                <b>Status:</b>
                <b class='text-success'><?php echo status_code($data['status'])['value'];?></b>            </li>
            <li class="list-group-item">
                <b>Date / Time:</b>
                 <?php echo dateConvert($data['date_added']);?>  / <?php echo $data['trans_time'];?>              </li>
            <li class="list-group-item">
                <b>API Logs:</b>
                <?php echo $data['api_response'];?> </li>
            </ul>
             
            <div class="d-flex flex-wrap gap-3 mt-4">
                <div class="flex-fill">
                    <a href="<?php echo adminController();?>reverse/<?php echo $data['id']; ?>" onclick="return confirm('You are about to reverse this transaction and customer wallet will be updated. Please proceed if you are sure');"><button class="btn btn--success btn--shadow w-100 btn-lg bal-btn">
                        <i class="las la-plus-circle"></i> Reverse</button></a>
                </div>

                <div class="flex-fill">
                    <a href="<?php echo adminController();?>reprrocessTransaction/<?php echo $data['id']; ?>" onclick="return confirm('You are about to reprocess this transaction. Please proceed if you are sure');"><button class="btn btn--danger btn--shadow w-100 btn-lg bal-btn" >
                        <i class="las la-minus-circle"></i> Reprocess                    </button></a>
                </div>
                
                <div class="flex-fill">
                    <button data-bs-toggle="modal" data-bs-target="#updateStatus" class="btn btn--primary btn--shadow w-100 btn-lg bal-btn">
                        <i class="las la-minus-circle"></i> Change Status                    </button>
                </div>

               
                 <div class="flex-fill">
                                            <button type="button" class="btn btn--warning btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal">
                            <i class="las la-ban"></i>Suspend account                       </button>
                                    </div>
            </div>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
      
      
       
             
             
</div>
</div>
 <div id="updateStatus" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">You are change the status of this order</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="<?php echo adminController();?>updateTransactionStatus" method="POST">
                            <div class="form-group">
                                <label>Status</label>
                                <input type="hidden" value="<?php echo $data['id'];?>" name="tid">
                                <select name="status" class="form-control">
                                    <option></option>
                                    <?php if(status_code()->num_rows() > 0){ foreach(status_code()->result_array() as $each){?>
                                    <option value="<?php echo $each['code'];?>"><?php echo $each['value'];?></option>    
                                    <?php } } ?>
                                </select>
                            </div>
                   
                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn--primary h-45 w-100">Submit</button>
                                            </div>
                </form>
            </div>
        </div>
     </div>