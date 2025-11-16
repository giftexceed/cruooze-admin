<div class="row">
						<div class="col-xl-12 col-xxl-12 d-flex">
							<div class="w-100">
						    	<div class="card mb-3">
							
							<div class="card-body h-100">
						    <div class="table-responsive">
              <table class="table table-hover my-0">
        <thead style="background-color: gray;">
                <tr>
<th style="color: #ffffff;">S/N</th>
                   <th style="color: #ffffff;">Provider </th>
                   <th style="color: #ffffff;">Service Name</th>
                 
                <th style="color: #ffffff;">Edit</th>
                
                </tr>
                </thead>
                <tbody>
                    
                  <?php 
                  $counter = 0;
                  if(!empty(get_network())) 
                  {
                  foreach (get_network()->result_array() as $item){
                if($item['status']=='1'){
                      $status = 'De-activate';
                   }else{
                      $status = 'Activate';
                  }
               ?>
                
                  <tr data-phoneid="<?php echo $item['id']; ?>">
                    <td><?php echo ++$counter; ?><input value="<?php echo $item['id']; ?>" type="hidden" name="rowid" class="phone-account"></td>
                   
                    <td><?php echo $item['network_name']; ?> </td>
                     <td><?php echo get_module($item['module_id'])['module'];?></td>
                     

                    <td><div class="form-check form-switch toggle-switch">
                    <input class="form-check-input togglebtn" type="<?php if($item['status'] == '1'){ echo 'checkbox';};?>" onclick="postData(this)" id="toggleSwitch<?php echo $item['id']; ?>" checked />
                    <label class="form-check-label" for="toggleSwitch<?php echo $item['id']; ?>"><?php if($item['status'] == '1'){ echo 'Enabled';}else{ echo 'Disabled';};?></label>
                  </div></td>
                   
                  </tr>
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