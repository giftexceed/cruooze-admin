  <div class="row">
      <div class="w-100">
						    	<div class="card mb-3">
							<div class="pull-right text-end" style="margin:10px; float:right"><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addNew">Add New</button></div>

     
            <div class="col-lg-12"> 
            	
        <div class="card flex-fill">
            <div class="card-body">
 <div class="table-responsive--sm table-responsive" id="tableRow">
                    <table class="table table--light style--two">
        <thead style="background-color: gray;">
                <tr>
                    <th style="color: #fff;">SN</th>
                          <th style="color: #fff;">Social Media</th>
                          <th style="color: #fff;">on/off</th>
                        <!--- <th style="color: #fff;">Delete</th> -->
                        
                          
                        </tr>
                </thead>
                <tr>
                   <?php 
                         if(!empty($data->num_rows())) 
                  {
                      $count = 1;
                    foreach ($data->result_array()  as $item){
                    ?>
                          <tr data-phoneid="<?php echo $item['id']; ?>">
                        <td><?php echo $count++;?></td>
                          <td><?php echo $item['social'];?></td>
                          <td><div class="form-check form-switch toggle-switch">
                    <input class="form-check-input togglebtn" type="<?php if($item['status'] == '1'){ echo 'checkbox';};?>" onclick="postData(this)" id="toggleSwitch<?php echo $item['id']; ?>" checked />
                    <label class="form-check-label" id="labelText<?php echo $item['id']; ?>" for="toggleSwitch<?php echo $item['id']; ?>"><span class="badge badge--<?php if($item['status'] == '1'){ echo 'success';}else{ echo 'danger';};?>"><?php if($item['status'] == '1'){ echo 'Enabled';}else{ echo 'Disabled';};?></span></label>
                  </div></td>
                         <!--
                         
                         <td><a href="<?php echo adminController();?>deleteSocial/<?php echo $item['id']; ?>" onclick="return confirm('You are about to remove this item');"><button class="btn btn--danger btn--shadow w-100 btn-lg bal-btn">Delete</button></td>
                         -->
                         
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
                    <h5 class="modal-title">Add New</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                <form action="<?php echo adminController();?>addSocial" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <label>Ads Social Media</label>
                                <input type="text" value="" name="social" class="form-control">
                                
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
function postData(el) {
    let $parent = $(el).closest('tr');
    var id = $parent.attr('data-phoneid');
    $('#labelText'+id).html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Processing ...');
    $.ajax({
    url:'<?php echo adminController();?>updatesocialMedia',
    method:"POST",
    data:{id:id},
    success:function(data)
    {$("#tableRow").load(" #tableRow");
    }
   });
}
</script>