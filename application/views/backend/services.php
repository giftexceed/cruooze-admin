<div class="row gy-4">
						<div class="col-xl-12 col-xxl-12 d-flex">
							<div class="w-100">
								<div class="row" id="ModuleWidgets">
								    
								   <?php if(get_module()->num_rows() > 0){
								       foreach(get_module()->result_array() as $each){
								   ?> 
								  
									<div class="col-sm-3" style="margin-bottom:10px">
										<div class="card">
											<div class="card-body">
											    		<div class="text-end"><a href="<?php echo adminController();?>setService/<?php echo $each['id'];?>"><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editRow<?php echo $each['id']; ?>">See sub Module</button></a></div>
									
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title"><?php echo $each['module'];?></h5>
													</div>
                                                   </div>
												<div class="form-check form-switch toggle-switch" data-phoneid="<?php echo $each['id']; ?>">
                                                        <input class="form-check-input togglebtn" type="<?php if($each['status'] == '1'){ echo 'checkbox';};?>" onclick="postData(this)" id="toggleSwitch<?php echo $each['id']; ?>" checked />
                                                        <label class="form-check-label" id="labelText<?php echo $each['id']; ?>" for="toggleSwitch<?php echo $each['id']; ?>"><span class="badge badge--<?php if($each['status'] == '1'){ echo 'success';}else{ echo 'danger';};?>"><?php if($each['status'] == '1'){ echo 'Enabled';}else{ echo 'Disabled';};?></span></label>
                                                      </div>
                                                      
											</div>
										</div>
									</div><br/>
								<?php } } ?>
									
									
								</div>
							</div>
						</div>

					</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
function postData(el) {
    let $parent = $(el).closest('div');
   var id = $parent.attr('data-phoneid');
   $('#labelText'+id).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Processing ...');
    $.ajax({
    url:'<?php echo adminController();?>updateModuleStatus',
    method:"POST",
    data:{id:id},
    success:function(data)
    {
       $("#ModuleWidgets").load(" #ModuleWidgets");
    }
   });
}

</script>