 <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo adminController();?>update_site">
                            <div class="col-xl-12 col-sm-12">
                                <div class="form-group ">
                                    <label> SMTP Host</label>
                                    <input class="form-control" type="text" name="smtp_host" required value="<?php echo get_settings('smtp_host');?>">
                                </div>
                            </div>

                            <div class="col-xl-12 col-sm-12">
                                <div class="form-group">
                                    <label> SMTP Port                                      
                                    </label>
                                    <input class="form-control" name="smtp_port" type="text" value="<?php echo get_settings('smtp_port');?>">
                                </div>
                            </div>
                            <div class="col-xl-12 col-sm-12">
                                <div class="form-group ">
                                    <label>SMTP Username</label>
                                    <input class="form-control" type="email" name="smtp_user" required value="<?php echo get_settings('smtp_user');?>">
                                </div>
                            </div>
                  
                            <div class="col-xl-12 col-sm-12">
                                <div class="form-group ">
                                    <label>SMTP Password</label>
                                    <input class="form-control" type="text" name="smtp_pass" required value="<?php echo get_settings('smtp_pass');?>">
                                </div>
                            </div>
                     
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
 