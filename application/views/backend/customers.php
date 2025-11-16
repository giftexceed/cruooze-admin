 <div class="row">
        <div class="col-lg-12">
            <div class="card">
                	<div class="pull-right text-end" style="margin:10px; float:right"><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addNew">Add New</button></div>
                	
               <div class="show-filter mb-3 text-end">
            <button type="button" class="btn btn-outline--primary showFilterBtn btn-sm"><i class="las la-filter"></i> Filter</button>
        </div>
            <div class="card responsive-filter-card mb-4">
            <div class="card-body">
                <form method="post" action="<?php echo adminController();?>searchCustomer">
                    <div class="d-flex flex-wrap gap-4">
                         <div class="flex-grow-1">
                            <label>Search by</label>
                            <select name="searchBy" id="searchBy" class="form-control select2">
                                <option value=""></option>
                                <option value="email">Email</option>
                                <!-- option value="phone">Phone number</option>-->
                                
                                
                                
                            </select>
                        </div>
                         
                        <div class="flex-grow-1">
                            <label>Filter Value</label>
                            <input type="text" name="inputvalue" value="" class="form-control">
                        </div>
                       
                       
                        <div class="flex-grow-1 align-self-end">
                            <button class="btn btn--primary w-100 h-45" type="submit"><i class="fa fa-search"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        	<div class="col-12 col-lg-12 col-xxl-12 d-flex">
							
							<div class="card flex-fill">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Email-Mobile</th>
                                <th>Joined At</th>
                                
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($data->num_rows() > 0)
                                {
                                    foreach($data->result_array() as $each)
                                {
                            ?>  
                                
                                                        <tr>
                                <td>
                                    <span class="fw-bold"><?php echo $each['fullname'];?></span>
                                    <br>
                                    <span class="small">
                                    <a href="<?php echo adminController();?>customerDetails/<?php echo $each['accountid'];?>"><!-- <span>@</span><?php echo $each['referral_code'];?>--></a>
                                    </span>
                                </td>


                                <td>
                                    <?php echo $each['email'];?><br><?php echo $each['phone'];?>
                                </td>
                              



                                <td>
                                   <?php echo date_convert($each['date_registered']);?><br>
                                </td>

                              
                               
                                <td>
                                    <div class="button--group">
                                        <a href="<?php echo adminController();?>customerDetails/<?php echo $each['accountid'];?>" class="btn btn-sm btn-outline--primary">
                                            <i class="las la-desktop"></i> Details                                        </a>
                                                                            </div>
                                </td>

                            </tr>
                            <?php }
                                } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
          </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <form action="<?php echo adminController();?>do_register" method="POST" class="form-validate is-alter" autocomplete="off">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email-address">Full Name</label>
                                            
                                        </div>
                                        <div class="form-control-wrap">
                                            <input autocomplete="off" type="text" name="fullname" class="form-control form-control-lg" required id="full-name" placeholder="Enter full name">
                                        </div>
                                        
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email-address">Phone Number</label>
                                            
                                        </div>
                                        <div class="form-control-wrap">
                                            <input type="number" autocomplete="off" minlength="11" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==11) return false;" class="form-control" id="basicInput" required="required" name="phonenumber" maxlength="11" placeholder="Enter phone number"  value="">
                                            
                                        </div>
                                        
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email-address">Email Address</label>
                                            
                                        </div>
                                        <div class="form-control-wrap">
                                            <input autocomplete="off" type="email" name="email" class="form-control form-control-lg" required id="email-address" placeholder="Enter Active Email Address">
                                        </div>
                                    </div><!-- .form-group -->
                                    

                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password">Password</label>
                                          
                                        </div>
                                        <div class="form-control-wrap">
                                            <a tabindex="-1" href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                                                <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                            </a>
                                            <input autocomplete="new-password" name="password" type="password" class="form-control form-control-lg" required id="password" placeholder="Enter password">
                                        </div>
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit">Create Account</button>
                                    </div>
                                </form><!-- form -->
            </div>
      </div>
      
    </div>
  </div>	