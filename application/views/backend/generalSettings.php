 <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo adminController();?>update_site">
                             <div class="row">
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group ">
                                    <label> Site Title</label>
                                    <input class="form-control" type="text" name="app_name" value="<?php echo get_settings('app_name');?>">
                                </div>
                            </div>

                             <div class="col-xl-6 col-sm-6">
                                <div class="form-group">
                                    <label> Support Email                                      
                                    </label>
                                    <input class="form-control" name="super_email" type="email" value="<?php echo get_settings('super_email');?>">
                                </div>
                            </div>
                             <div class="col-xl-6 col-sm-6">
                                <div class="form-group ">
                                    <label>Support Phone</label>
                                    <input class="form-control" type="text" name="phone" value="<?php echo get_settings('phone');?>">
                                </div>
                            </div>
                  
                              <div class="col-xl-6 col-sm-6">
                                <div class="form-group ">
                                    <label>Address</label>
                                    <input class="form-control" type="text" name="address" value="<?php echo get_settings('address');?>">
                                </div>
                            </div>
                            
                              <div class="col-xl-6 col-sm-6">
                                <div class="form-group ">
                                    <label>Site Color</label>
                                    <input class="form-control" type="color" name="primary_color" required value="<?php echo get_settings('primary_color');?>">
                                </div>
                            </div>
                           <!--
                           <div class="col-xl-6 col-sm-6">
                                <div class="form-group ">
                                    <label>Payment with Card</label>
                                    <select name="cardPaymentGateway" id="cardPaymentGateway" class="form-control">
                            <option value="">Choose provider</option>
                            <?php if(payment_getways()->num_rows() > 0){
                                foreach(payment_getways()->result_array() as $each)
                            {
                        ?>
                        <option value="<?php echo $each['gateway_id'];?>"  <?php if(get_settings('cardPaymentGateway') ==  $each['gateway_id']) { echo 'selected'; } ?>><?php echo $each['gateway_name'];?></option>
                        <?php }
                            } ?>
                        </select>
                                </div>
                            </div>
                           
                            
                            
                               <div class="col-xl-6 col-sm-6">
                                <div class="form-group ">
                                    <label>Upgrade Fee</label>
                                    <input class="form-control" type="text" name="upgradeFee" value="<?php echo get_settings('upgradeFee');?>">
                                </div>
                            </div>
                            
                                <div class="col-xl-6 col-sm-6">
                                <div class="form-group ">
                                    <label>WhatsApp support Link</label>
                                    <input class="form-control" type="text" name="whatsapp" value="<?php echo get_settings('whatsapp');?>">
                                </div>
                            </div>
                            
                              
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group ">
                                    <label>Transfer Charge</label>
                                    <input class="form-control" type="number" name="transfer_fee" value="<?php echo get_settings('transfer_fee');?>">
                                </div>
                            </div>
                             <div class="col-xl-6 col-sm-6">
                                <div class="form-group ">
                                    <label>Referral Bonus</label>
                                    <input class="form-control" type="text" name="ref_Bonus" value="<?php echo get_settings('ref_Bonus');?>">
                                </div>
                            </div>
                            
                            
                            <div class="col-xl-6 col-sm-6">
                                <div class="form-group ">
                                    <label>API Documentation Link</label>
                                    <input class="form-control" type="text" name="apidocsLink" value="<?php echo get_settings('apidocsLink');?>">
                                </div>
                            </div>
                            
                             -->
                             
                             
                     
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">Submit</button>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
 