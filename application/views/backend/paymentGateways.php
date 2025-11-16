    <div class="row justify-content-center">
        
        <div class="col-md-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two" id="tableRow">
                            <thead>
                                <tr>
                                    <th>#</th>
                   <th>Gateway Name </th>
                  <th>Gateway % </th>
                   <th>PK Live</th>
                   <th>SK Live </th>
                   <th>Webhook Link </th>
                   <th>Status</th>
                   <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter=0; if(payment_getways()->num_rows() > 0) { foreach(payment_getways()->result_array() as $each) {?>
                                    <tr data-phoneid="<?php echo $each['gateway_id']; ?>">
                    <td><?php echo ++$counter; ?></td>
                   
                    <td><?php echo $each['gateway_name'];?></td>
                    <td><?php echo $each['gateway_percent'];?></td>
                    <td><?php echo $each['pk_live'];?></td>
                    <td><?php echo $each['sk_live'];?></td>
                    <td><?php echo base_url();?><?php echo $each['webhook'];?></td>
                    <td><div class="form-check form-switch toggle-switch">
                    <input class="form-check-input togglebtn" type="<?php if($each['gateway_status'] == '1'){ echo 'checkbox';};?>" onclick="postData(this)" id="toggleSwitch<?php echo $each['gateway_id']; ?>" checked />
                    <label class="form-check-label" id="labelText<?php echo $each['gateway_id']; ?>" for="toggleSwitch<?php echo $each['gateway_id']; ?>"><span class="badge badge--<?php if($each['gateway_status'] == '1'){ echo 'success';}else{ echo 'danger';};?>"><?php if($each['gateway_status'] == '1'){ echo 'Enabled';}else{ echo 'Disabled';};?></span></label>
                  </div></td>
                   <td><button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editRow<?php echo $each['gateway_id']; ?>">Edit</button>
                    </td>
                  </tr>
                  
                  <div class="modal fade" id="editRow<?php echo $each['gateway_id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit <?php echo $each['gateway_name'];?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo adminController();?>updatePaymentGateway" method="post" role="form" class="form-horizontal form-groups-bordered">
                    <div class="col-12">
                        <div class="input-style-1">
                        <label>Gateway title</label>
                        <input type="text" name="gateway_name" value="<?php echo $each['gateway_name'];?>" class="form-control"/>
                        <input type="hidden" id="inputName" class="form-control"name="gateway_id" value="<?php echo $each['gateway_id'];?>">
                        </div>
                        </div>
                        
                        <div class="col-12">
                        <div class="input-style-1">
                        <label>Charge Type</label>
                        <select name="charge_type" id="charge_type" class="form-control">
                           <?php if($each['charge_type'] ==  "flat") {?>
                        <option value="flat">Flat</option>
                         <option value="percent">Percent</option>
                        <?php }else{?>
                       <option value="percent">Percent</option>
                        <option value="flat">Flat</option>
                       <?php } ?>
                        </select>
                        </div>
                        </div>
                        
                        <div class="col-12">
                        <div class="input-style-1">
                        <label>Charge</label>
                        <input type="text" name="gateway_percent" class="form-control" value="<?php echo $each['gateway_percent'];?>"/>
                        </div>
                        </div>
                        
                        
                        <div class="col-12">
                        <div class="input-style-1">
                        <label>Live Public Key</label>
                        <input type="text" name="pk_live" class="form-control" value="<?php echo $each['pk_live'];?>"/>
                        </div>
                        </div>
                         <div class="col-12">
                        <div class="input-style-1">
                        <label>Live Secrete Key</label>
                        <input type="text" name="sk_live" class="form-control" value="<?php echo $each['sk_live'];?>"/>
                        </div>
                        </div>
                        
                       <div class="col-12">
                        <div class="input-style-1">
                        <label>Contract code or Business ID (as applicable)</label>
                        <input type="text" name="contract_code" class="form-control" value="<?php echo $each['contract_code'];?>"/>
                        </div>
                        </div>
                        
                        
                    <div class="col-12">
                        <div class="input-style-1">
                      <button class="btn btn--primary h-45 w-100" type="submit">
                        Update
                      </button>
                    </div>
                    </div>
                 </form>
            </div>
      </div>
     
    </div>
  </div>
                                <?php } } ?>
                                                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>

</div>
             </div>    </div>
<div class="row justify-content-center" style="display:none">
    <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">Account Details for Manual funding</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo adminController();?>updateSystemAccount" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <input class="form-control" type="text" name="bank_name" required value="<?php echo getManualAccount()['bank_name'];?>">
                                   
                                </div>
                            </div>

                          

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Name/Title </label>
                                    <input class="form-control" type="text" name="account_name" value="<?php echo getManualAccount()['account_name'];?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Account Number</label>
                                    <div class="input-group ">
                                        <input type="number" name="account_number" value="<?php echo getManualAccount()['account_number'];?>" id="mobile" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Account Type (Savings or Current)</label>
                                    <input class="form-control" type="text" name="account_type" value="<?php echo getManualAccount()['account_type'];?>">
                                </div>
                            </div>
                           
                            <div class="col-md-12">
                                <button type="submit" class="btn btn--primary w-100 h-45">Submit                                </button>
                            </div>
                        </div>
                    </form>
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
    url:'<?php echo adminController();?>updatePaymentGatewayStatus',
    method:"POST",
    data:{id:id},
    success:function(data)
    {
        $("#tableRow").load(" #tableRow");
    }
   });
}

</script>