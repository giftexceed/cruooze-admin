<div class="row">
						<div class="col-xl-12 col-xxl-12 d-flex">
							<div class="w-100">
						    	<div class="card mb-3">
							<div class="pull-right text-end" style="margin:10px; float:right"><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addNew">Add New</button></div>

							<div class="card-body h-100">
						   <div class="table-responsive">
      <table class="table--light style--two custom-data-table table" id="tableRow">
        <thead style="background-color: gray;">
                <tr>
<th style="color: #ffffff;">S/N</th>
                   <th style="color: #ffffff;">Service Title </th>
                   <th style="color: #ffffff;">Provider</th>
                 <th style="color: #ffffff;">Logo</th>
                 <th style="color: #ffffff;">API</th>
                <th style="color: #ffffff;">on/off</th>
                <th style="color: #ffffff;">Edit</th>
                </tr>
                </thead>
                <tbody>
                    
                  <?php 
                  $counter = 0;
                  if($details->num_rows() > 0 ) 
                  {
                  foreach ($details->result_array() as $item){
                      $networkDetails = get_networkbyModule($item['parent_id'],$item['network_id']);
                if($item['status']=='1'){
                      $status = 'De-activate';
                   }else{
                      $status = 'Activate';
                  }
               ?>
                
                  <tr data-phoneid="<?php echo $item['id']; ?>">
                    <td><?php echo ++$counter; ?><input value="<?php echo $item['id']; ?>" type="hidden" name="rowid" class="phone-account"></td>
                   
                    <td><?php echo $item['name']; ?> </td>
                     <td><?php echo $networkDetails['network_name'];?></td>
                     <td><img src="<?php echo base_url('assets/images/network/'.$networkDetails['network_img']);?>" width="50" style="border-radius:50px"></td>
                    <td><?php echo get_api_provider($item['api'])['provider_name'];?></td>
                    <td><div class="form-check form-switch toggle-switch">
                    <input class="form-check-input togglebtn" type="<?php if($item['status'] == '1'){ echo 'checkbox';};?>" onclick="postData(this)" id="toggleSwitch<?php echo $item['id']; ?>" checked />
                    <label class="form-check-label" id="labelText<?php echo $item['id']; ?>" for="toggleSwitch<?php echo $item['id']; ?>"><span class="badge badge--<?php if($item['status'] == '1'){ echo 'success';}else{ echo 'danger';};?>"><?php if($item['status'] == '1'){ echo 'Enabled';}else{ echo 'Disabled';};?></span></label>
                  </div></td>
                   <td><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editRow<?php echo $item['id']; ?>">Edit</button>
                    </td>
                  </tr>
                  
                  <div id="editRow<?php echo $item['id']; ?>" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="type"></span> <span>Balance</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="<?php echo adminController();?>editSubModuleData" class="balanceAddSub" method="POST">
                   <div class="modal-body">
                        <div class="form-group">
                            <label>Service Name</label>
                                <input type="text" step="any" name="name" class="form-control" placeholder="" value="<?php echo $item['name'];?>">
                                <input type="hidden" id="inputName" class="form-control"name="id" value="<?php echo $item['id'];?>">
                            
                        </div>
                        <div class="form-group">
                            <label>Choose Provider</label>
                            <select name="network" id="network" class="form-control">
                            <option value="">Choose provider</option>
                            <?php if(get_network()->num_rows() > 0){
                                foreach(get_network()->result_array() as $each)
                            {
                        ?>
                        <option value="<?php echo $each['network_id'];?>"  <?php if($networkDetails['id'] ==  $each['id']) { echo 'selected'; } ?>><?php echo $each['network_name'];?> <?php echo get_module($each['module_id'])['module'];?></option>
                        <?php }
                            } ?>
                        </select>
                        </div>
                        
                        <div class="form-group">
                            <label>API Provider</label>
                           <select name="api" id="api" class="form-control">
                            <option value="">Choose provider</option>
                            <?php if(get_api_provider()->num_rows() > 0){
                                foreach(get_api_provider()->result_array() as $each)
                            {
                        ?>
                        <option value="<?php echo $each['id'];?>"  <?php if($item['api'] ==  $each['id']) { echo 'selected'; } ?>><?php echo $each['provider_name'];?></option>
                        <?php }
                            } ?>
                        </select>
                        </div>
                        <?php if(($item['parent_id'] == '1') ||($item['parent_id'] == '4')||($item['parent_id'] == '5')){?>
                        <div class="form-group">
                            <label>User Percent</label>
                                <input type="text" step="any" name="user_percent" class="form-control" placeholder="" value="<?php echo $item['user_percent'];?>">
                        </div>
                         <div class="form-group">
                            <label>Vendor Percent</label>
                                <input type="text" step="any" name="reseller_percent" class="form-control" placeholder="" value="<?php echo $item['reseller_percent'];?>">
                        </div>
                         <div class="form-group">
                            <label>API Percent</label>
                                <input type="text" step="any" name="api_percent" class="form-control" placeholder="" value="<?php echo $item['api_percent'];?>">
                        </div>
                        <?php } ?>
                        <div class="form-group">
                            <label>Network ID</label>
                                <input type="text" step="any" name="api_code" class="form-control" placeholder="" value="<?php echo $item['api_code'];?>">
                              <input type="hidden" id="inputName" class="form-control"name="moduleid" value="<?php echo $moduleDetails['id'];?>">
                        
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>  <?php } } else { ?>
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
        <form action="<?php echo adminController();?>addSubModuleData" method="POST">
                   <div class="modal-body">
                        <div class="form-group">
                            <label>Service Name</label>
                                <input type="text" step="any" name="name" class="form-control" placeholder="" value="">
                            
                        </div>
                        <div class="form-group">
                            <label>Choose Provider</label>
                            <select name="network" id="network" class="form-control">
                            <option value="">Choose provider</option>
                            <?php if(get_network()->num_rows() > 0){
                                foreach(get_network()->result_array() as $each)
                            {
                        ?>
                        <option value="<?php echo $each['network_id'];?>"><?php echo $each['network_name'];?> <?php echo get_module($each['module_id'])['module'];?></option>
                        <?php }
                            } ?>
                        </select>
                        </div>
                        
                        <div class="form-group">
                            <label>API Provider</label>
                           <select name="api" id="api" class="form-control">
                            <option value="">Choose provider</option>
                            <?php if(get_api_provider()->num_rows() > 0){
                                foreach(get_api_provider()->result_array() as $each)
                            {
                        ?>
                        <option value="<?php echo $each['id'];?>"><?php echo $each['provider_name'];?></option>
                        <?php }
                            } ?>
                        </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Network ID</label>
                                <input type="text" step="any" name="api_code" class="form-control" placeholder="" value="">
                            <input type="hidden" id="inputName" class="form-control"name="moduleid" value="<?php echo $moduleDetails['id'];?>">
                         
                            
                        </div>
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
    url:'<?php echo adminController();?>updateSubModuleStatus',
    method:"POST",
    data:{id:id},
    success:function(data)
    {$("#tableRow").load(" #tableRow");
    }
   });
}

</script>