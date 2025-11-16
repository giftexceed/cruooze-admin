 <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo adminController();?>update_site">
                            <div class="col-xl-12 col-sm-12">
                                <div class="form-group ">
                                    <label>Scrolling message</label>
                                    <textarea name="scrolling_message" class="form-control" rows="6" cols="6"><?php echo get_settings('scrolling_message');?></textarea>
                                 </div>
                            </div>
                        
                        
                        <div class="col-xl-12 col-sm-12">
                                <div class="form-group ">
                                    <label>POPup message</label>
                                    <textarea name="announcement" class="form-control" rows="6" cols="6"><?php echo get_settings('announcement');?></textarea>
                                 </div>
                            </div>
                            
                     
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">Submit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
 