<div class="row">
  						<div class="col-xl-12 col-xxl-12 d-flex">
  						      
							<div class="w-100">
						    	<div class="card mb-3">
							<div class="pull-right text-end" style="margin:10px; float:right"><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addNew">Add New API</button></div>

							<div class="card-body h-100">
						    <div class="table-responsive">
              <table class="table table-hover my-0">
        <thead style="background-color: gray;">
                <tr>
<th style="color: #ffffff;">S/N</th>
                   <th style="color: #ffffff;">Provider </th>
                 <th style="color: #ffffff;">Provider Key</th>
                 <th style="color: #ffffff;">Endpoint</th>
                 <th style="color: #ffffff;">API class</th>
                <th style="color: #ffffff;">Edit</th>
                <th style="color: #ffffff;">Delete</th>
                </tr>
                </thead>
                <tbody>
                    
                  <?php 
                  $counter = 0;
                  if(!empty(get_api_provider())) 
                  {
                  foreach (get_api_provider()->result_array() as $item){
                if($item['status']=='1'){
                      $status = 'De-activate';
                   }else{
                      $status = 'Activate';
                  }
               ?>
                
                  <tr data-phoneid="<?php echo $item['id']; ?>">
                    <td><?php echo ++$counter; ?><input value="<?php echo $item['id']; ?>" type="hidden" name="rowid" class="phone-account"></td>
                   
                    <td><?php echo $item['provider_name']; ?> </td>
                     <td><?php echo $item['api_key'];?></td>
                     <td><?php echo $item['endpoint'];?></td>
                     <td><?php echo get_ApiClass($item['apiType'])['className'];?></td>
                    <td><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editRow<?php echo $item['id']; ?>">Edit</button>
                    </td>
                    <td><a href="<?php echo adminController();?>deleteAPI/<?php echo $item['id']; ?>"><button class="btn btn-danger btn-sm" onclick="return confirm('Do you really want to Delete <?php echo $item['provider_name']?>?');">Delete</button></a>
                    </td>
                  </tr>
                  <div class="modal fade" id="editRow<?php echo $item['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit <?php echo $item['provider_name'];?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo adminController();?>editApiProvider" method="post" role="form" class="form-horizontal form-groups-bordered">
                    <div class="col-12">
                        <div class="input-style-1">
                        <label>API Name</label>
                        <input type="text" name="provider_name" value="<?php echo $item['provider_name'];?>"/>
                        <input type="hidden" id="inputName" class="form-control"name="id" value="<?php echo $item['id'];?>">
                        </div>
                        </div>
                        
                        <div class="col-12">
                        <div class="input-style-1">
                        <label>Endpoint link</label>
                        <input type="text" name="endpoint" value="<?php echo $item['endpoint'];?>"/>
                        </div>
                        </div>
                        
                        <div class="col-12">
                        <div class="input-style-1">
                        <label>Provider Key</label>
                        <input type="text" name="api_key" value="<?php echo $item['api_key'];?>"/>
                        </div>
                        </div>
                        
                         <div class="col-12">
                        <div class="input-style-1">
                        <label>API Class</label> 
                        <select name="apiClass" class="form-select mb-3">
                            <option value="">Select API class</option>
                            <?php if(get_ApiClass()->num_rows() > 0){
                                foreach(get_ApiClass()->result_array() as $each)
                            {
                        ?>
                        <option value="<?php echo $each['id'];?>"><?php echo $each['className'];?></option>
                        <?php }
                            } ?>
                        </select>
                        </div>
                        </div>
                        
                        <!--
                        
                        <div class="col-12">
                        <div class="input-style-1">
                        <label>Choose Provider</label>
                        <select name="Network" id="network" class="form-select mb-3">
                            <option value="">Choose provider</option>
                            <?php if(get_network()->num_rows() > 0){
                                foreach(get_network()->result_array() as $each)
                            {
                        ?>
                        <option value="<?php echo $each['id'];?>"  <?php if($item['subModule'] ==  $each['id']) { echo 'selected'; } ?>><?php echo $each['network_name'];?> <?php echo get_module($each['module_id'])['module'];?></option>
                        <?php }
                            } ?>
                        </select>
                        </div>
                        </div>
                        
                        
                       
                    
                    <div class="col-12">
                        <div class="input-style-1">
                        <label>Link this API to a service</label>
                        <select name="submodule" id="submodule" class="form-control select2">
                            <option value="">Select service</option>
                            <?php if(get_Submodule()->num_rows() > 0){
                                foreach(get_Submodule()->result_array() as $each)
                            {
                        ?>
                        <option value="<?php echo $each['id'];?>" <?php if($item['subModule'] ==  $each['id']) { echo 'selected'; } ?>><?php echo $each['name'];?></option>
                        <?php }
                            } ?>
                        </select>
                        </div>
                        </div>
                        -->
                    <div class="col-12">
                      <button class="main-btn primary-btn btn-hover" type="submit">
                        Update
                      </button>
                    </div>
                 </form>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
       </div>
    </div>
  </div>
                  <?php } } else { ?>
                  <tr>
                    <td colspan="6"><p class="text-danger text-center m-b-0">No Records Found</p></td>
                  </tr>
                  <?php } ?>
              </tbody>
      
              </table>
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
        <form action="<?php echo adminController();?>addApiProvider" method="post" role="form" class="form-horizontal form-groups-bordered">
                    <div class="col-12">
                        <div class="input-style-1">
                        <label>API Name</label>
                        <input type="text" name="provider_name" value=""/>
                        </div>
                        </div>
                        
                        <div class="col-12">
                        <div class="input-style-1">
                        <label>Endpoint link</label>
                        <input type="text" name="endpoint" value=""/>
                        </div>
                        </div>
                        
                        <div class="col-12">
                        <div class="input-style-1">
                        <label>Provider Key</label>
                        <input type="text" name="api_key" value=""/>
                        </div>
                        </div>
                        
                         <div class="col-12">
                        <div class="input-style-1">
                        <label>API Class</label> 
                        <select name="apiClass" class="form-select mb-3">
                            <option value="">Select API class</option>
                            <?php if(get_ApiClass()->num_rows() > 0){
                                foreach(get_ApiClass()->result_array() as $each)
                            {
                        ?>
                        <option value="<?php echo $each['id'];?>"><?php echo $each['className'];?></option>
                        <?php }
                            } ?>
                        </select>
                        </div>
                        </div>
                        
                       
                    <div class="col-12">
                      <button class="main-btn primary-btn btn-hover" type="submit">
                        Add Record
                      </button>
                    </div>
                 </form>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
       </div>
    </div>
  </div>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
function postData(el) {
    let $parent = $(el).closest('tr');
    $('.togglebtn').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Processing ...');
    var id = $parent.attr('data-phoneid');
    $.ajax({
    url:'<?php echo adminController();?>updateProviders',
    method:"POST",
    data:{id:id},
    success:function(data)
    {
        // alert(data);
         toastr.success(data.message, data.title);
         location.reload();
    }
   });
}


</script>

<script>
$('#network').change(function(){
   var network = $('#network').find(":selected").val();
 if(network != '')
  {
   $.ajax({
    url:'<?php echo adminController();?>getsubModule',
    method:"POST",
    data:{network:network},
    success:function(data)
    {
     $('#submodule').html(data);
    }
   });
  }
  
 });

</script>