<style>
    .btn-outline{
        background-color:#fff;
        border: 1px solid #000;
        display:flex;
        margin:5px;
        padding:5px;
    }
    .checkboxinput{
        margin:8px;
    }
    .results {
	background: white;
	border-radius: 3px;
	margin-top: 8px;
	list-style: none;
}
.result {
	padding: 8px 10px;
	border-bottom: 1px solid #f0f0f0;
}
.result:hover {
	background: #fcfafa;
	cursor: pointer;
}
</style>

<div class="row">
        <div class="col-12">
             <div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
    <h6 class="page-title">User Details</h6>
    <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center breadcrumb-plugins">
           <?php if($data['approval_status'] == '0'){ ?>
            <button class="btn btn-sm btn-outline--danger"><i class="las la-sign-in-alt"></i>Pending</button>
            <?php }else{ ?>
            <button class="btn btn-sm btn-outline--success"><i class="las la-sign-in-alt"></i>Approved</button>
            <?php } ?>
    </div>
</div>
            <div class="d-flex flex-wrap gap-3 mt-4">
                <div class="flex-fill">
                    <?php if($data['approval_status'] == '0'){ ?>
                    <a href="<?php echo adminController();?>approveEvent/<?php echo $data['reference'];?>" onclick="return confirm('Are you sure? Proceed only if you are sure');"><button class="btn btn--success btn--shadow w-100 btn-lg bal-btn">
                        <i class="las la-plus-circle"></i> Approve Event                    </button></a>
                        
                    <?php }else{ ?>
                     <a href="<?php echo adminController();?>declineEvent/<?php echo $data['reference'];?>" onclick="return confirm('Are you sure? Proceed only if you are sure');"><button class="btn btn--success btn--shadow w-100 btn-lg bal-btn">
                        <i class="las la-plus-circle"></i> De-activate Event                    </button></a>
                    <?php } ?>
                </div>

              
                <div class="flex-fill">
                  
                       <button type="button" data-bs-toggle="modal" data-bs-target="#declineEvent" class="btn btn--warning btn--shadow w-100 btn-lg bal-btn" >
                        <i class="las la-minus-circle"></i> Decline Event                    </button>
                </div>
                
               
                
                   <div class="flex-fill">
                    <button  type="button" data-bs-toggle="modal" data-bs-target="#chooseOrganizer" class="btn btn--primary btn--shadow w-100 btn-lg">
                        <i class="las la-bell"></i>Choose Organizer                       </button>
                   </div>
                   
                   
             
                <div class="flex-fill">
                    <a href="<?php echo adminController();?>deleteEvent/<?php echo $data['reference'];?>" onclick="return confirm('You are about to delete account of <?php echo $data['event_title'];?> All records associated details with this event will be deleted alongside and this cannot be reverse. Proceed only if you are sure');"class="btn btn--danger btn--shadow w-100 btn-lg">
                        <i class="las la-bell"></i>Delete Event                        </a>
                   </div>
            </div>

            <div class="card mt-30">
                <div class="card-header">
                    <h5 class="card-title mb-0">Information of <?php echo $data['event_title'];?></h5>
                </div>
                <div class="card-body" id="updateUserProfile">
                    <form action="<?php echo adminController();?>doEventupdate" method="POST"  enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Event Title</label>
                                    <input class="form-control" type="text" name="event_title" required value="<?php echo $data['event_title'];?>">
                                    <input class="form-control" type="hidden" name="reference" id="reference"  value="<?php echo $data['reference'];?>">
                                </div>
                            
                            
                            
                                 <div class="form-group">
                                    <label>Event Description </label>
                                    <textarea class="form-control" cols="6" name="event_description" rows="6"><?php echo $data['event_description'];?></textarea>
                                 
                                </div>
                                
                                 <div class="form-group ">
                                    <label>Start Date</label>
                                    <input class="form-control" type="date" name="event_date" value="<?php echo $data['event_date'];?>">
                                </div>
                                
                                 <div class="form-group ">
                                    <label>Start Time</label>
                                    <input class="form-control" type="time" name="event_time" value="<?php echo $data['event_time'];?>">
                                </div>
                                 <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">End Date</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                    <input autocomplete="off" type="date" name="endDate" class="form-control form-control-lg" id="endDate" <?php echo $data['endDate'];?> placeholder="Enter Event End Date">
                                                </div>
                                            </div><!-- .form-group -->
                                
                                <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">End Time</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                      <input type="text" name="close_time"  class="time-pickable form-control form-control-lg" value="<?php echo $data['close_time'];?>">
                                                    
                                                    <!--
                                                    <input autocomplete="off" type="time" name="close_time" class="form-control form-control-lg" required id="close_time" placeholder="Enter Close Time">
                                                    -->
                                                    
                                                </div>
                                            </div><!-- .form-group -->
                                            
                                            
                                <div class="form-group ">
                                    <label>Event Location</label>
                                    <input class="form-control" type="text" id="searchText" name="event_location" value="<?php echo $data['event_location'];?>">
                                    <ul id="resultsList" class="result"></ul>
                                </div>
                                
                                <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Event Type</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                        <select name="adstype" class="form-control">
                                                            <option value="">Choose category</option>
                                                            
                                                            <?php
                                                                foreach(getAdstype()->result_array() as $each)
                                                                {
                                                        ?>
                                                        
                                                        <option value="<?php echo $each['type'];?>" <?php if($each['type'] == $data['ads_type'] || ($each['id'] == $data['ads_type'])) { echo 'selected'; };?>><?php echo $each['type'];?></option>
                                                           <?php     
                                                            } ?>
                                                            
                                                        </select>
                                                </div>
                                            </div><!-- .form-group -->
                               
                                    <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Upload Images (Select upto 5 Images)</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                    <input autocomplete="off" type="file" name="event_img[]" multiple class="form-control form-control-lg" id="event_img" placeholder="Enter Event Image">
                                                </div>
                                            </div><!-- .form-group -->
                                            
                                            
                                            
                                            
                                             <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Upload Video (Select upto 5 Videos)</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                    <input autocomplete="off" type="file" name="videos[]" multiple class="form-control form-control-lg"  id="videos" >
                                                </div>
                                            </div><!-- .form-group --> 
                                
                            </div>

                          

                            <div class="col-md-6">
                                
                                     
                                   <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Ads Activity</label>
                                                   
                                                </div>
                                                   <div class="row">
                                                <?php
                                               
                                                                foreach(getadsactivities()->result_array() as $each)
                                                                {
                                                                    
                                                                    $activities = json_decode($data['activities'])
                                                                 
                                                        ?>
                                                 <div class="col-lg-6">
                                                <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" id="mySwitch<?php echo $each['id'];?>" name="activities[]" value="<?php echo $each['activities'];?>"
      <?php  if (in_array($each['activities'],$activities)) { echo 'checked';}?> >
      <label class="form-check-label" for="mySwitch<?php echo $each['id'];?>"><?php echo $each['activities'];?></label>
    </div>
      </div>
    <?php     
                                                            }  ?>
                                                            </div>
    
                                               
                                             
                                                
                                            </div><!-- .form-group -->
                                            
                                          
                                            
                             
                                          <div class="form-group" id ="listEventArea">             
                                            <div class="form-group pull-right text-end">
                                              <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addEventList">Add Event Items</button>
                                          </div>
      <div class="form-group ">
                                     
                                    <?php  $eventChild = json_decode($data['eventchild']);
                                 foreach($eventChild as $items ){?>
                                   <div class="form-group">Event Name: <?php echo $items->event_name;?> Start Time: <?php echo $items->starttime;?> Close Time: <?php echo $items->closetime;?></div>
                                    
                                   <?php } ?>
                                     
                                    
                                </div>
      <?php 
     //$this->db->get('listitemstemp');
     
        
      if(!empty($data['eventchild'])){
      
       $detailsList =  json_decode($data['eventChild']);
       
       ?>
         
     <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                             <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Days</th>
                                    <th>Start Time</th>
                                    <th>Close Time</th>
                                    <th>Delete</th>
                                 

                                </tr>
                            </thead>
     <?php   
     print_r($detailsList);
    $count = 0;
       foreach($detailsList as $item){  
       $count++;
       ?>
           <tr>
               <td><?php echo $item[$count]['event_name']; ?></td>
               <td><?php echo $item[$count]['days']; ?></td>
               <td><?php echo $item[$count]['starttime']; ?></td>
                <td><?php echo $item[$count]['closetime']; ?></td>
             <td><a href="<?php echo adminController();?>removeitemCart/<?php echo $item['id'];?>" onclick="return confirm('You want to remove this item');"><button id="removeItemCart" type="button" data-id="<?php echo $item["id"]; ?>" class="red-symbol" style="background-color:#F00; font-size:12px; color:#fff; border-radius:15px;padding-bottom:5px;padding-top:5px;border:#F00; margin-right:4px;padding-left:12px;padding-right:12px;"><i class="fa fa-trash"></i></button></a></td>
              
              
           
          
          </tr>
    <?php } ?> </table>
  </div>
   <?php }   ?>
</div>
                             
                               <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Contact Name</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                    <input autocomplete="off" type="text" name="contactName"  class="form-control form-control-lg" placeholder="Enter Contact Name" value="<?php echo $data['contactName'];?>">
                                                </div>
                                            </div><!-- .form-group -->
                                            
                                            
                                            <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Contact Phone Number</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                    <input autocomplete="off" type="text" name="contactPhone" class="form-control form-control-lg" id="contactPhone" placeholder="Enter Contact phone" value="<?php echo $data['contactPhone'];?>">
                                                </div>
                                            </div><!-- .form-group -->
                                            
                                            
                                  <?php $getGallleries = json_decode($data['event_img_link']);
                                 foreach($getGallleries as $each ){?>           
                               <div class="form-group d-flex">
                                     
                                   
                                
                                 <img src="<?php echo $each;?>" width="200"><br>
                                 
                                 <a href="<?php echo adminController();?>removeFile?reference=<?php echo $data['reference'];?>&file=<?php echo $each;?>"><button type="button" style="margin-left:2px"  onclick="return confirm('You are about to remove this item');"class="mt-2 btn btn--danger btn--shadow btn-sm bal-btn">Remove</button></a>
                                 
                                     
                                
                                </div>
                                  <?php } ?>
                               
                                
                                
                                
                                <div class="form-group ">
                                    
                                    <?php $getGallleries = $this->db->where('file_type','.mp4')->where('event_id', $data['id'])->get('eventFiles');
                                    if($getGallleries->num_rows() > 0 ) {
                                    foreach($getGallleries->result_array() as $each ){
                                    
                                    ?>
                                                                        <video controls>
                                       <source src="<?php echo $each['fileurl'];?>" type="video/mp4">
                                       <source src="movie.ogg" type="video/ogg">
                                       Your browser does not support the video tag.
                                    </video>
                                                                        
                                    <?php } } ?>
                                </div>
                                
                                
                              <!--
                              <iframe src="https://www.google.com/maps/embed?pb=!..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                              -->
                              
                              
                                
                                          
                                            
                              
                            </div>

                            

                           
                            <div class="col-md-12">
                                <button type="submit" class="btn btn--primary w-100 h-45">Submit                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<div id="declineEvent" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="type"></span> <span>Reason For Decline</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="<?php echo adminController();?>declineEvent"method="POST">
                   <div class="modal-body">
                        <div class="form-group">
                            <label>Reason</label>
                            <div class="input-group">
                               <textarea class="form-control" placeholder="Remark" name="reason" rows="4" required></textarea>
                                <input type="hidden" id="inputName" class="form-control"name="reference" value="<?php echo $data['reference'];?>">
                     
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100" id="submitButton">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 </div>   
  </div>
 <div id="SubtratSubModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="type"></span> <span>Balance</span></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="<?php echo adminController();?>manual_topup" class="balanceAddSub" method="POST">
                   <div class="modal-body">
                        <div class="form-group">
                            <label>Amount</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" placeholder="Please provide positive amount" required>
                                <input type="hidden" id="inputName" class="form-control"name="user_id" value="<?php echo $data['accountid'];?>">
                        <input type="hidden" id="inputName" class="form-control"name="actiontype" value="debit">
                                <div class="input-group-text"><?php echo currency();?> <?php echo $data['wallet_balance'];?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remark</label>
                            <textarea class="form-control" placeholder="Remark" name="remark" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div> 
    
    <div id="userStatusModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                                                    Ban User                                            </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                 <div class="modal-body">
                <form action="#" method="POST">
                             <h6 class="mb-2">If you ban this user, he/she won't able to access his/her account.</h6>
                            <label>Reason</label>
                            <div class="form-group">
                               
                                <textarea class="form-control" name="reason" rows="4" required></textarea>
                            </div>
                   
                    <div class="modal-footer">
                                                    <button type="submit" class="btn btn--primary h-45 w-100">Submit</button>
                                            </div>
                </form>
                </div>
            </div>
        </div>
     </div>
     
     
     <div id="chooseOrganizer" class="modal fade" tabindex="-1" role="dialog">
     
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="chooseOrganizerModalLabel">Organizer of this event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <form  method="POST" class="form-validate is-alter" autocomplete="off" action="<?php echo adminController();?>chooseOrganizer">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email-address">Enter Organizer Register Email</label>
                                            
                                        </div>
                                        <div class="form-control-wrap">
                                            <input autocomplete="off" type="email" name="emailAddress" class="form-control form-control-lg" required id="full-name" placeholder="">
                                            
                                             <input autocomplete="off" type="hidden" name="reference" value="<?php echo $data['reference'];?>">
                                             
                                        </div>
                                        
                                        
                                    </div><!-- .form-group -->
                                     <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
                                    </div>
                                        
                                </form><!-- form -->
            
      </div>
      
    </div>
  </div>
</div>	



 <div class="modal fade" id="addEventList" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">List Event</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <form  method="POST" class="form-validate is-alter" autocomplete="off" id="addEventlist">
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email-address">Event Name</label>
                                            
                                        </div>
                                        <div class="form-control-wrap">
                                            <input autocomplete="off" type="text" name="event_name" class="form-control form-control-lg" required id="full-name" placeholder="">
                                        </div>
                                        
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email-address">Days</label>
                                            
                                        </div>
                                        <div class="row">
                                        <?php
                                        $getAllDays = $this->db->get('days');
                                        foreach($getAllDays->result_array() as $each){
                                        ?>
                                        <div class="col-lg-6">
                                                <div class="btn btn-outline btn-sm">
      <input class="checkboxinput" type="checkbox" id="daysCheck<?php echo $each['id'];?>" name="days[]" value="<?php echo $each['day'];?>">
      <label class="" for="daysCheck<?php echo $each['id'];?>"><?php echo $each['day'];?></label>
    </div>
      </div>
     
      
      <?php } ?>
                                        
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email-address">Start Time</label>
                                            
                                        </div>
                                        <div class="form-control-wrap">
                                            <input autocomplete="off" type="time" id="startime" name="starttime" class="form-control form-control-lg">
                                        </div>
                                    </div><!-- .form-group -->
                                    
                                    
                                     <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email-address">Close Time</label>
                                            
                                        </div>
                                        <div class="form-control-wrap">
                                            <input autocomplete="off" type="time" id="closetime" name="closetime" class="form-control form-control-lg">
                                        </div>
                                    </div><!-- .form-group -->
                                    

                                    
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit" id="addEventbutton">Add item</button>
                                    </div>
                                </form><!-- form -->
            </div>
      </div>
      
    </div>
  </div>
</div>					
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    document.querySelectorAll('.result').forEach((result) => {
				result.addEventListener('click', () => {
				    $('#searchText').val(result.innerText);
				     $('#resultsList').hide(); 
				//	input.value = result.innerText;
					// Clear results
				///	foundCities.splice(0, foundCities.length);
				//	ul.innerHTML = '';
				});
			});
</script>
<script>
$(document).ready(function() {
    $('#searchText').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        
        // Make an API call (replace with your actual API endpoint and parameters)
        $.ajax({
            url: '<?php echo adminController();?>liveLocationSearch', // Replace with your API endpoint
            method: 'GET',
            data: { searchTerm: searchTerm }, // Pass the search term to the API
            success: function(data) {
                 $('#resultsList').show(); 
                $('#resultsList').empty(); // Clear previous results
               // data = JSON.parse(data);
                // Process and display API results
               if (data && data.length > 0) { // Assuming data is an array of objects
               
                  //  data.forEach(item => {
                        // Customize how each item is displayed
                        $('#resultsList').append(`<li>${data}</li>`); 
                  //  });
                } else {
                     //alert(searchTerm);
                    $('#resultsList').append('<li>No results found.</li>');
                }
            },
            error: function(error) {
                console.error('Error fetching data:', error);
                $('#resultsList').empty().append('<li>Error fetching data.</li>');
            }
        });
    });
});
</script>


<script>
$(document).ready(function(){
    $('#addEventlist').on('submit', function(e){
        e.preventDefault(); // Prevent default form submission
        $('#addEventbutton').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Processing ...');
        $.ajax({
            url: "<?php echo adminController();?>addlistingCart", // Controller method
            type: "POST",
            data: $(this).serialize(), // Serialize form data
            dataType: "json", // Expect JSON response
            success: function(response){
                $('#addEventList').modal('hide');
               $( "#listEventArea" ).load(window.location.href + " #listEventArea" );
            },
            error: function(xhr, status, error){
                $('#addEventList').modal('hide');
               $( "#listEventArea" ).load(window.location.href + " #listEventArea" );
            }
        });
    });
});
</script>


<script>
function updateActivationStatus(element) {
     var userid = $('#accountid').val();
   $('#submitButton').html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Processing ...');
    $.ajax({
    url:'<?php echo adminController();?>updateActivationStatus',
    method:"POST",
    data:{userid:userid},
    success:function(data)
    {
        location.reload();
    }
   });
}
</script>

<script>
function updatekyc_approve() {
     var userid = $('#accountid').val();
    $.ajax({
    url:'<?php echo adminController();?>updatekyc_approve',
    method:"POST",
    data:{userid:userid},
    success:function(data)
    {
        location.reload();
    }
   });
}
</script>
<script>
function updateApiaccessStatus() {
     var userid = $('#accountid').val();
    $.ajax({
    url:'<?php echo adminController();?>updateApiaccess',
    method:"POST",
    data:{userid:userid},
    success:function(data)
    {
        location.reload();
    }
   });
}
</script>