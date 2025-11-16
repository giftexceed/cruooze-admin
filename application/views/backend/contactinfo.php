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
                          <th style="color: #fff;">Title</th>
                          <th style="color: #fff;">Value</th>
                         <th style="color: #fff;">Edit</th>
                          
                        </tr>
                </thead>
                <tr>
                   <?php 
                         if(!empty($list->num_rows())) 
                  {
                      $count = 1;
                    foreach ($list->result_array()  as $item){
                    ?>
                          <tr data-phoneid="<?php echo $item['id']; ?>">
                        <td><?php echo $count++;?></td>
                          <td><?php echo $item['title'];?></td>
                          <td><?php echo $item['value'];?></td>
                         <td><a href="#edit-popup<?php echo $item['id'];?>" data-bs-toggle="modal"><button class="btn btn--primary btn--shadow btn-sm bal-btn">Edit</button></a></td>
                      </tr>
                      
                      
                      <div id="edit-popup<?php echo $item['id'];?>" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit <?php echo $item['title'];?> Info </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                <form action="<?php echo adminController();?>updateContactinfo" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" value="<?php echo $item['title'];?>" name="title" class="form-control">
                                 <input type="hidden" value="<?php echo $item['id'];?>" name="id">
                            </div>
                            
                            <div class="form-group">
                                <label>Value</label>
                                <input type="text" value="<?php echo $item['value'];?>" name="data" class="form-control">
                                
                            </div>
                   
                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn--primary h-45 w-100">Update Records</button>
                                            </div>
                </form>
                </div>
            </div>
        </div>
     </div>
			
			
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
                    <h5 class="modal-title">Add New Info</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                <form action="<?php echo adminController();?>addContactinfo" enctype="multipart/form-data" method="POST">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" value="" name="title" class="form-control">
                                
                            </div>
                            
                            <div class="form-group">
                                <label>Value</label>
                                <input type="text" value="" name="data" class="form-control">
                                
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
    url:'<?php echo adminController();?>updateadstype',
    method:"POST",
    data:{id:id},
    success:function(data)
    {$("#tableRow").load(" #tableRow");
    }
   });
}
</script>