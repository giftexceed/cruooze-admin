        <div class="row">
            <div class="col-lg-12">
        <div class="show-filter mb-3 text-end">
            <button type="button" class="btn btn-outline--primary showFilterBtn btn-sm"><i class="las la-filter"></i> Filter</button>
        </div>
           <div class="card responsive-filter-card mb-4">
            <div class="card-body">
                <form method="post" action="<?php echo adminController();?>searchTransaction">
                    <div class="d-flex flex-wrap gap-4">
                         <div class="flex-grow-1">
                            <label>Search by</label>
                            <select name="parmeter" id="searchBy" class="form-control select2">
                                <option value=""></option>
                                <option value="date_added">Date</option>
                                <option value="recipient">Benefiary</option>
                                <option value="order_id">Reference ID</option>
                                 <option value="status">Status</option>
                                 <option value="type">Transaction type</option>
                                 
                            </select>
                        </div>
                         <div class="flex-grow-1" id="Ttype">
                            <label>Transaction type</label>
                        <select name="submodule" id="submodule" class="form-control select2">
                            <option value="">Select service</option>
                            <?php if(get_Submodule()->num_rows() > 0){
                                foreach(get_Submodule()->result_array() as $each)
                            {
                        ?>
                        <option value="<?php echo $each['id'];?>"><?php echo $each['name'];?></option>
                        <?php }
                            } ?>
                        </select>
                        </div>
                        
                         <div class="flex-grow-1" id="status">
                            <label>Transaction type</label>
                        <select name="status" id="status" class="form-control select2">
                            <option value="">Select Status</option>
                            <?php if(status_code()->num_rows() > 0){
                                foreach(status_code()->result_array() as $each)
                            {
                        ?>
                        <option value="<?php echo $each['code'];?>"><?php echo $each['value'];?></option>
                        <?php }
                            } ?>
                        </select>
                        </div>
                        
                        
                        <div class="flex-grow-1">
                            <label>Filter Value</label>
                            <input type="text" name="parameter_value" value="" class="form-control">
                        </div>
                       
                       
                        <div class="flex-grow-1">
                            <label>Date</label>
                            <input name="transactiondate" type="date" class="form-control bg--white" autocomplete="off" value="">
                        </div>
                        <div class="flex-grow-1 align-self-end">
                            <button class="btn btn--primary w-100 h-45" type="submit"><i class="fa fa-search"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        
						<div class="col-12 col-lg-12 col-xxl-12 d-flex">
							
							<div class="card flex-fill">
							
								<div class="card-body">
								<?php include('includes/transactionHistory.php');?>
							    </div>
							</div>
						</div>
						
						
					
					</div>
					</div>
<script>
$('#Ttype').hide();
$('#status').hide();
$('#searchBy').change(function(){
 var searchBy = $('#searchBy').find(":selected").val(); 
 if(searchBy === "type"){
  $('#Ttype').show();   
 } else{
      $('#Ttype').hide();   
 } 
 if(searchBy === "status"){
  $('#status').show();   
 }else{
     $('#status').hide();   
 }
});
	</script>