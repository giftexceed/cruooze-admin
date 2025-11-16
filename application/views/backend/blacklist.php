  <div class="row">
      <div class="w-100">
						    	<div class="card mb-3">
							<div class="pull-right text-end" style="margin:10px; float:right"><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addNew">Add New</button></div>

     
            <div class="col-lg-12"> 
            	
        <div class="card flex-fill">
            <div class="card-body">
 <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
        <thead style="background-color: gray;">
                <tr>
                    <th style="color: #fff;">SN</th>
                          <th style="color: #fff;">Phone/Email</th>
                         <th style="color: #fff;">Delete</th>
                          
                        </tr>
                </thead>
                <tr>
                   <?php 
                         if(!empty($data)) 
                  {
                      $count = 1;
                    foreach ($data->result_array()  as $list){
                    ?>
                          <tr>
                        <td><?php echo $count++;?></td>
                          <td><?php echo $list['phonenumber'];?></td>
                         <td><a href="<?php echo adminController();?>deleteBlacklist/<?php echo $list['id']; ?>" onclick="return confirm('You are about to remove <?php echo $list['phonenumber'];?> from blacklist');"><button class="btn btn--success btn--shadow w-100 btn-lg bal-btn">Delete</button></td>
                      </tr>
                              <?php }
                  }else{ ?>
                    <tr>
                  <td colspan="11"><p class="text-danger text-center m-b-0">No Records Found</p></td></tr>
                  <?php }?>
                </tr>
              </table>
              
								
								</div>
	</div>
	</div>
	</div>
	</div>
	</div>
	</div>
	
<div id="addNew" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Phone number or Email registered here will be blacklisted on all services</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="<?php echo adminController();?>addBlacklist" method="POST">
                            <div class="form-group">
                                <label>Email or Phone number</label>
                                <input type="text" value="" name="phonenumber" class="form-control">
                                
                            </div>
                   
                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn--primary h-45 w-100">Submit</button>
                                            </div>
                </form>
            </div>
        </div>
     </div>