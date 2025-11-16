<div class="row ">
        <div class="col-xl-12">
            <div class="card">
                <form class="notify-form" method="POST" enctype="multipart/form-data" action="<?php echo adminController();?>doPasswordUpdate">
                    <div class="card-body">
                        <div class="row">
                           
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Current Password </label>
                                   
                                    <input type="password" class="form-control" placeholder="" name="oldpassword"
                                    value="">
                                </div>
                                <div class="input-append">
                                </div>
                            </div>
                            <div class="form-group col-md-12 subject-wrapper">
                                <label>New Password <span class="text--danger">*</span> </label>
                                <input type="password" class="form-control" placeholder="" name="newPassword"
                                    value="">
                            </div>
                           
                            <div class="form-group col-md-12 subject-wrapper">
                                <label>Confirm Password <span class="text--danger">*</span> </label>
                                <input type="password" class="form-control" placeholder="" name="confirmPassword"
                                    value="">
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn w-100 h-45 btn--primary me-2" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>