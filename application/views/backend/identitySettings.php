 <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo adminController();?>uploadAssets" enctype="multipart/form-data">
                            <div class="col-xl-12 col-sm-12">
                                <div class="form-group ">
                                    <label> Browse Logo</label>
                                    <input class="form-control" type="file" class="image-upload-input" name="site_logo" id="uploadLogo" accept=".png, .jpg, .jpeg" required>
                                </div>
                            </div>

                            <div class="col-xl-12 col-sm-12">
                                <div class="form-group">
                                    <label> Browse Favicon                                      
                                    </label>
                                    <input class="form-control" type="file" class="image-upload-input" name="site_favicon" id="uploadLogo" accept=".png, .jpg, .jpeg" >
                                </div>
                            </div>
                           
                     
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
 