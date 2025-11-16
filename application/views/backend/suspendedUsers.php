 <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two" id="tableRow">
                            <thead>
                            <tr>
                                <th>User</th>
                                <th>Email-Mobile</th>
                                <th>Joined At</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($data->num_rows() > 0)
                                {
                                    foreach($data->result_array() as $each)
                                {
                            ?>  
                                
                            <tr data-phoneid="<?php echo $each['accountid']; ?>">
                                <td>
                                    <span class="fw-bold"><?php echo $each['fullname'];?></span>
                                    <br>
                                    <span class="small">
                                    <a href="<?php echo adminController();?>customerDetails/<?php echo $each['accountid'];?>"><span>@</span><?php echo $each['referral_code'];?></a>
                                    </span>
                                </td>


                                <td>
                                    <?php echo $each['email'];?><br><?php echo $each['phone'];?>
                                </td>
                              



                                <td>
                                   <?php echo date_convert($each['date_registered']);?><br>
                                </td>
                            <td><div class="form-check form-switch toggle-switch">
                                                <input class="form-check-input togglebtn" type="<?php if($each['suspend'] == '1'){ echo 'checkbox';};?>" onclick="postData(this)" id="toggleSwitch<?php echo $each['accountid']; ?>" checked />
                                                <label class="form-check-label" id="labelText<?php echo $each['accountid']; ?>" for="toggleSwitch<?php echo $each['accountid']; ?>"><span class="badge badge--<?php if($each['suspend'] == '0'){ echo 'success';}else{ echo 'danger';};?>"><?php if($each['accountid'] == '0'){ echo 'Active';}else{ echo 'Suspended';};?></span></label>
                                              </div></td>
                                <td>
                                    <div class="button--group">
                                        <a href="<?php echo adminController();?>customerDetails/<?php echo $each['accountid'];?>" class="btn btn-sm btn-outline--primary">
                                            <i class="las la-desktop"></i> Details                                        </a>
                                                                            </div>
                                </td>

                            </tr>
                            <?php }
                                } ?>
                        </tbody>
                    </table>
                </div>
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
    url:'<?php echo adminController();?>unsuspendUser',
    method:"POST",
    data:{id:id},
    success:function(data)
    {
       $("#tableRow").load(" #tableRow");
    }
   });
}

</script>