<div class="row">
						<div class="col-md-4 col-xl-4">
							<div class="card mb-3">
								<div class="card-header">
									<h5 class="card-title mb-0">Profile Details</h5>
								</div>
								<div class="card-body text-center">
									<img src="<?php echo user_avater($details['accountid']);?>" alt="Christina Mason" class="img-fluid rounded-circle mb-2" width="128" height="128" />
									<h5 class="card-title mb-0"> <?php echo $details['fullname'];?></h5>
									<div class="text-muted mb-2"><?php echo $details['email'];?></div>
                                    <div class="text-muted mb-2"><?php echo $details['phone'];?></div>
									<div>
										<a class="btn btn-primary btn-sm" href="#">Login as</a>
									    <a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#creditWallet">Credit</a>
										<a class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#debitWallet">Debit</a>
										<a class="btn btn-danger btn-sm" href="#">Suspend</a>
									</div>
								</div>
								<hr class="my-0" />
								<div class="card-body">
									<form action="<?php echo base_url('bio_update');?>" method="post" role="form" enctype="multipart/form-data" class="form-horizontal form-groups-bordered">
          
<div class="card-body">
 <div class="col-12">
<div class="input-style-1">
<label>Full Name</label>
<input type="text"  name="fullname" value="<?php echo $details['fullname'];?>"/>
<input type="hidden" id="inputName" class="form-control"name="user_id" value="<?php echo $details['accountid'];?>">
</div>
</div>


<div class="col-12">
<div class="input-style-1">
<label>Email Address</label>
<input type="text"  name="email" value="<?php echo $details['email'];?>"/>
</div>
</div>

<div class="col-12">
<div class="input-style-1">
<label>Full Name</label>
<input type="text"  name="phone" value="<?php echo $details['phone'];?>"/>
</div>
</div>

<div class="col-12">
<div class="input-style-1">
<label>API key</label>
<input type="text" readonly name="api_key" value="<?php echo $details['api_key'];?>"/>
</div>
</div>
<div class="col-12">
<div class="input-style-1">
<label>Wallet Balance</label>
<input type="text" readonly value="<?php echo currency();?> <?php echo $details['wallet_balance'];?>"/>
</div>
</div>
<div class="col-12">
<div class="input-style-1">
<label>Commission Balance</label>
<input type="text" readonly value="<?php echo currency();?> <?php echo $details['cash_wallet'];?>"/>
</div>
</div>
<div class="col-12">
<div class="input-style-1">
<label>API key</label>
<input type="text"  name="fullname" value="<?php echo $details['api_key'];?>"/>
</div>
</div>
<div class="col-12">
                      <button class="main-btn primary-btn btn-hover" type="submit">
                        Update Profile
                      </button>
                    </div>
</div>
</form>	
								</div>
								<hr class="my-0" />
								
							</div>
						</div>

						<div class="col-md-8 col-xl-8">
							<div class="card">
								
								<div class="card-body h-100">
                                    
									<hr />
									
									 <form action="<?php echo adminController();?>manual_topup" method="post">
                                     <div class="d-flex align-items-start">
									     <div class="flex-grow-1">
										<div class="col-12">
                                        <div class="input-style-1">
                                        <label>Send mail to <?php echo $details['email'];?></label>
                                        <textarea class="form-control" rows="2" name="message" placeholder="Compose Message"></textarea>
                                        </div>
                                        </div>
                                        <div class="col-12">
                                          <button class="main-btn success-btn btn-hover" type="submit">
                                            Send Email
                                          </button>
                                        </div>
                                        
										</div>
									</div>
                                    </form>
									

								</div>
							</div>
							
							<div class="row">
							    <div class="card">
							        <div class="card-header">
							            Transaction Log
							        </div>
							        <div class="card-body h-100">
							            <?php include('includes/transactionHistory.php');?>
							         </div>
							     </div>
							</div>
							
								<div class="row">
							    <div class="card">
							        <div class="card-header">
							            Deposit Log
							        </div>
							        <div class="card-body h-100">
							            <?php include('includes/transactionHistory.php');?>
							         </div>
							     </div>
							</div>
						</div>
					</div>


<div class="modal fade" id="creditWallet" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Credit <?php echo $details['fullname'];?> wallet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo adminController();?>manual_topup" method="post" role="form" enctype="multipart/form-data" class="form-horizontal form-groups-bordered">
                    <div class="col-12">
                        <div class="input-style-1">
                        <label>Current Balance</label>
                        <input type="text" readonly value="<?php echo currency();?> <?php echo $details['wallet_balance'];?>"/>
                        <input type="hidden" id="inputName" class="form-control"name="user_id" value="<?php echo $details['accountid'];?>">
                        <input type="hidden" id="inputName" class="form-control"name="actiontype" value="credit">
                        </div>
                        </div>
                        
                        
                    <div class="col-12">
                        <div class="input-style-1">
                        <label>Amount to Add</label>
                        <input type="number" value=""name="amount" min="1"/>
                        </div>
                        </div>
                    <div class="col-12">
                        <div class="input-style-1">
                        <label>wallet Type</label>
                        <select name="wallet_type" class="form-select mb-3">
                            <option value="wallet_balance">Main Wallet</option>
                            <option value="cash_wallet">Commission Wallet</option>
                        </select>
                        </div>
                        </div>
                    <div class="col-12">
                      <button class="main-btn primary-btn btn-hover" type="submit">
                        Credit User
                      </button>
                    </div>
                 </form>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
       </div>
    </div>
  </div>
  
  
  <div class="modal fade" id="debitWallet" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Debit <?php echo $details['fullname'];?> wallet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo adminController();?>manual_topup" method="post" role="form" enctype="multipart/form-data" class="form-horizontal form-groups-bordered">
                    <div class="col-12">
                        <div class="input-style-1">
                        <label>Current Balance</label>
                        <input type="text" readonly value="<?php echo currency();?> <?php echo $details['wallet_balance'];?>"/>
                        <input type="hidden" id="inputName" class="form-control"name="user_id" value="<?php echo $details['accountid'];?>">
                        <input type="hidden" id="inputName" class="form-control"name="actiontype" value="debit">
                        </div>
                        </div>
                        
                        
                    <div class="col-12">
                        <div class="input-style-1">
                        <label>Amount to Add</label>
                        <input type="number" value=""name="amount" />
                        </div>
                        </div>
                    <div class="col-12">
                        <div class="input-style-1">
                        <label>wallet Type</label>
                        <select name="wallet_type" class="form-select mb-3">
                            <option value="wallet_balance">Main Wallet</option>
                            <option value="cash_wallet">Commission Wallet</option>
                        </select>
                        </div>
                        </div>
                    <div class="col-12">
                      <button class="main-btn primary-btn btn-hover" type="submit">
                        Debit User
                      </button>
                    </div>
                 </form>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
       </div>
    </div>
  </div>