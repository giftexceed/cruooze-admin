 <div class="row">
        <div class="col-12">
            
            <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5  bg--white">

                <i class="las la-hammer overlay-icon text--info"></i>
    
    <div class="widget-two__icon b-radius--5   bg--info  ">
       <i class="las la-money-bill-wave-alt"></i>
    </div>

    <div class="widget-two__content">
        <h3><?php echo $this->db->get_where('events', array('user_id' => $data['accountid'],'approval_status' => '1'))->num_rows();?></h3>
        <p>Active Events</p>
    </div>
 </div>

        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5  bg--white">
    <i class="la la-list overlay-icon text--primary"></i>
    <div class="widget-two__icon b-radius--5   bg--primary  ">
        <i class="fa fa-list"></i>
    </div>

    <div class="widget-two__content">
       <h3><?php echo $this->db->get_where('events', array('user_id' => $data['accountid'],'approval_status' => '0'))->num_rows();?></h3>
        <p>Pending Events</p>
    </div>

    </div>

        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5  bg--white">

                <i class="fa fa-list-alt overlay-icon text--success"></i>
    
    <div class="widget-two__icon b-radius--5   bg--success  ">
        <i class="fa fa-list-alt"></i>
    </div>

    <div class="widget-two__content">
       <h3><?php echo $this->db->get_where('subscriptionPlans', array('id' => $data['isSubscribed']))->row_array()['title'];?></h3>
        <p>Plan</p>
    </div>

          
    </div>

        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5  bg--white">

                <i class="fa fa-money-bill overlay-icon text--dark"></i>
    
    <div class="widget-two__icon b-radius--5   bg--dark  ">
        <i class="fa fa-money-bill"></i>
    </div>

    <div class="widget-two__content">
        <h3><?php echo $this->db->get_where('events', array('event_date' => currentDate()))->num_rows();?></h3>
        <p>Today Events</p>
    </div>

           
    </div>


        </div>
    </div>
    
    
    
            

            <div class="d-flex flex-wrap gap-3 mt-4">
                
                
                <!--
                <div class="flex-fill">
                    <button data-bs-toggle="modal" data-bs-target="#addSubModal" class="btn btn--success btn--shadow w-100 btn-lg bal-btn" data-act="add">
                        <i class="las la-plus-circle"></i> Credit Wallet                    </button>
                </div>

                <div class="flex-fill">
                    <button data-bs-toggle="modal" data-bs-target="#SubtratSubModal" class="btn btn--danger btn--shadow w-100 btn-lg bal-btn" data-act="sub">
                        <i class="las la-minus-circle"></i> Debit Wallet                    </button>
                </div>
-->

                <div class="flex-fill">
                    <a href="<?php echo adminController();?>loginHistory?user_id=<?php echo $data['accountid'];?>" class="btn btn--primary btn--shadow w-100 btn-lg">
                        <i class="las la-list-alt"></i>Logins                    </a>
                </div>
<!--


                <div class="flex-fill">
                    <a href="<?php echo adminController();?>loginas/<?php echo $data['accountid'];?>" target="_blank" class="btn btn--secondary btn--shadow w-100 btn-lg">
                        <i class="las la-bell"></i>Login as user                    </a>
                </div>
-->


                
                <div class="flex-fill">
                                            <button type="button" class="btn btn--warning btn--shadow w-100 btn-lg userStatus" data-bs-toggle="modal" data-bs-target="#userStatusModal">
                            <i class="las la-ban"></i>Suspend account                       </button>
                                    </div>
                                    
                 <div class="flex-fill">
                                            <a href="<?php echo adminController();?>eventsbyuser?user_id=<?php echo $data['accountid'];?>" <button type="button" class="btn btn--success btn--shadow w-100 btn-lg userStatus" >
                            <i class="las la-ban"></i>See Events</button></a>
                                    </div>
                
                <div class="flex-fill">
                    <a href="<?php echo adminController();?>deleteUserAccount/<?php echo $data['accountid'];?>" onclick="return confirm('You are about to delete account of <?php echo $data['fullname'];?> All records associated with this account will be deleted alongside and this cannot be reverse. Proceed only if you are sure');"class="btn btn--danger btn--shadow w-100 btn-lg">
                        <i class="las la-bell"></i>Delete Account                        </a>
                   </div>
            </div>

            <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">Information of <?php echo $data['fullname'];?></h5>
                </div>
                <div class="card-body" id="updateUserProfile">
                    <form action="<?php echo adminController();?>updateUser" method="POST"  enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input class="form-control" type="text" name="fullname" required value="<?php echo $data['fullname'];?>">
                                    <input class="form-control" type="hidden" name="accountid" id="accountid"  value="<?php echo $data['accountid'];?>">
                                </div>
                            </div>

                          

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email </label>
                                    <input class="form-control" type="email" name="email" value="<?php echo $data['email'];?>" required>
                                </div>
                            </div>
 <!--
 
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mobile Number </label>
                                    <div class="input-group ">
                                        <span class="input-group-text mobile-code">+234</span>
                                        <input type="number" name="phone" value="<?php echo $data['phone'];?>" id="mobile" class="form-control checkUser" required>
                                    </div>
                                </div>
                            </div>
  
   
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Referral Code</label>
                                    <input class="form-control" type="text" name="referral_code" value="<?php echo $data['referral_code'];?>">
                                </div>
                            </div>
                         
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Webhook URL</label>
                                    <input class="form-control" type="text" name="webhook_url" value="<?php echo $data['webhook_url'];?>">
                                    
                                </div>
                            </div>
                            
                            
                             <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Security PIN</label>
                                    <input class="form-control" type="text" name="security_pin" value="<?php echo $data['security_pin'];?>">
                                </div>
                            </div>
                            -->
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Change Password (Only fill if you intend to change the customer password)</label>
                                    <input class="form-control" type="text" name="passwordUpdate" value="">
                                    
                                </div>
                            </div>


                           
                           <div class="col-md-6">
                                <div class="form-group ">
                                    <label>API Key</label>
                                    <input class="form-control" type="text" name="api_key" value="<?php echo $data['api_key'];?>">
                                    
                                </div>
                            </div>
                      <!--
                      <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Daily Spending Limit</label>
                                    <input class="form-control" type="number" name="dailyLimit" value="<?php echo $data['dailyLimit'];?>">
                                    
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Reversal Count Today</label>
                                    <input class="form-control" type="number" name="dailyReversal" value="<?php echo $data['dailyReversal'];?>">
                                    
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>Moniepoint Account</label>
                                    <input class="form-control" type="text" name="moniepoint" value="<?php echo $data['moniepoint'];?>">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>Wema Account</label>
                                    <input class="form-control" type="text" name="wema" value="<?php echo $data['wema'];?>">
                                </div>
                            </div>
                            
                             <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>Palmpay Account</label>
                                    <input class="form-control" type="text" name="palmpay" value="<?php echo $data['palmpay'];?>">
                                </div>
                            </div>
                             <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>9PSB</label>
                                    <input class="form-control" type="text" name="payvessel" value="<?php echo $data['payvessel'];?>">
                                </div>
                            </div>
                            -->
                            
                              <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label>Change user package</label>
                                   <select name="package_id" id="network" class="form-control">
                                       <?php if(getPackage()->num_rows() > 0){
                                            foreach(getPackage()->result_array() as $each)
                                        {
                                    ?>
                                    <option value="<?php echo $each['id'];?>"  <?php if($data['package_id'] ==  $each['id']) { echo 'selected'; } ?>><?php echo $each['title'];?></option>
                                    <?php }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            
                           
                            
                             

                            
                            <!--
                            

                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Email Verification</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" value="<?php echo $data['activation_status'];?>" data-bs-toggle="toggle" data-on="Verified" data-off="Unverified" name="activation_status" id="activation_status" onchange="updateActivationStatus(this)" <?php if($data['activation_status'] == '0'){ echo 'checked';}?> >
                                </div>
                            </div>
        
        
                            <div class="col-xl-3 col-md-6 col-12">
                                <div class="form-group">
                                    <label>API Access</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" value="<?php echo $data['api_access'];?>" data-bs-toggle="toggle" data-on="Yes" data-off="No" id="updateApiaccess" onchange="updateApiaccessStatus()" name="updateApiaccess"  <?php if($data['api_access'] == '1'){ echo 'checked';}?> >
                                </div>
                            </div>
                          
                          
                            <div class="col-xl-3 col-md- col-12">
                                <div class="form-group">
                                    <label>Approve KYC </label>
                                    <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" value="<?php echo $data['kyc_approve'];?>" data-bs-toggle="toggle" data-on="Yes" data-off="No" id="kyc_approve" onchange="updatekyc_approve()"  name="kyc_approve"  <?php if($data['kyc_approve'] == '1'){ echo 'checked';}?> >
                                </div>
                            </div>
                            -->
                            
                            <div class="col-md-12">
                                <button type="submit" class="btn btn--primary w-100 h-45">Submit                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="type"></span> <span>Balance</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="<?php echo adminController();?>manual_topup" class="balanceAddSub" method="POST">
                   <div class="modal-body">
                        <div class="form-group">
                            <label>Amount</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" min="1" placeholder="Please provide positive amount" required>
                                <input type="hidden" id="inputName" class="form-control"name="user_id" value="<?php echo $data['accountid'];?>">
                        <input type="hidden" id="inputName" class="form-control"name="actiontype" value="credit">
                                <div class="input-group-text"><?php echo currency();?> <?php echo $data['wallet_balance'];?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remark</label>
                            <textarea class="form-control" placeholder="Remark" name="remark" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100" id="submitButton">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
 <div id="SubtratSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="type"></span> <span>Balance</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="<?php echo adminController();?>manual_topup" class="balanceAddSub" method="POST">
                   <div class="modal-body">
                        <div class="form-group">
                            <label>Amount</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" placeholder="Please provide positive amount" required>
                                <input type="hidden" id="inputName" class="form-control"name="user_id" value="<?php echo $data['accountid'];?>">
                        <input type="hidden" id="inputName" class="form-control"name="actiontype" value="debit">
                                <div class="input-group-text"><?php echo currency();?> <?php echo $data['wallet_balance'];?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remark</label>
                            <textarea class="form-control" placeholder="Remark" name="remark" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div> 
    
    <div id="userStatusModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                                                    Ban User                                            </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                 <div class="modal-body">
                <form action="#" method="POST">
                             <h6 class="mb-2">If you ban this user, he/she won't able to access his/her account.</h6>
                            <label>Reason</label>
                            <div class="form-group">
                               
                                <textarea class="form-control" name="reason" rows="4" required></textarea>
                            </div>
                   
                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn--primary h-45 w-100">Submit</button>
                                            </div>
                </form>
                </div>
            </div>
        </div>
     </div>
     
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
function updateActivationStatus(element) {
     var userid = $('#accountid').val();
   $('#submitButton').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Processing ...');
    $.ajax({
    url:'<?php echo adminController();?>updateActivationStatus',
    method:"POST",
    data:{userid:userid},
    success:function(data)
    {
        location.reload();
    }
   });
}
</script>

<script>
function updatekyc_approve() {
     var userid = $('#accountid').val();
    $.ajax({
    url:'<?php echo adminController();?>updatekyc_approve',
    method:"POST",
    data:{userid:userid},
    success:function(data)
    {
        location.reload();
    }
   });
}
</script>
<script>
function updateApiaccessStatus() {
     var userid = $('#accountid').val();
    $.ajax({
    url:'<?php echo adminController();?>updateApiaccess',
    method:"POST",
    data:{userid:userid},
    success:function(data)
    {
        location.reload();
    }
   });
}
</script>