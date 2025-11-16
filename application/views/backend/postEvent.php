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

 <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo adminController();?>doEventadding" enctype="multipart/form-data">
                             <div class="row">
                            <div class="col-xl-6 col-sm-6">
                                       <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Event Title</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                    <input autocomplete="off" type="text" name="event_title" class="form-control form-control-lg" required id="full-name" placeholder="Enter Even Title">
                                                </div>
                                                
                                            </div><!-- .form-group -->
                                            <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Event Description</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                    <textarea cols="4" rows="3" class="form-control" name="event_description"></textarea>
                                                    
                                                </div>
                                                
                                            </div><!-- .form-group -->
                                            <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Start Date</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                    <input autocomplete="off" type="date" name="event_date" class="form-control form-control-lg" required id="event_date" placeholder="Enter Event Start Date">
                                                </div>
                                            </div><!-- .form-group -->
                                            
                                                <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">End Date</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                    <input autocomplete="off" type="date" name="endDate" class="form-control form-control-lg" required id="endDate" placeholder="Enter Event End Date">
                                                </div>
                                            </div><!-- .form-group -->
                                            
                                            
                                             <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Start Time</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                    <input type="text" name="event_time"  class="time-pickable form-control form-control-lg" readonly>
                                                    
                                                    <!--
                                                    <input autocomplete="off" type="time" name="event_time" class="form-control form-control-lg" required id="event_time" placeholder="Enter Event Time">
                                                    -->
                                                    
                                                </div>
                                            </div><!-- .form-group -->
                                            
                                            <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">End Time</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                      <input type="text" name="close_time"  class="time-pickable form-control form-control-lg" readonly>
                                                    
                                                    <!--
                                                    <input autocomplete="off" type="time" name="close_time" class="form-control form-control-lg" required id="close_time" placeholder="Enter Close Time">
                                                    -->
                                                    
                                                </div>
                                            </div><!-- .form-group -->
                                            
                                            
                                            
                                            
                                            
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
                                                        
                                                        <option value="<?php echo $each['id'];?>"><?php echo $each['type'];?></option>
                                                           <?php     
                                                            } ?>
                                                            
                                                        </select>
                                                </div>
                                            </div><!-- .form-group -->
                                            
                                            <!--
                                            
                                            
                                           <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Event State</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                        <select name="state" class="form-control">
                                                            <option value="">Choose State</option>
                                                            
                                                            <?php
                                                                foreach(get_states()->result_array() as $each)
                                                                {
                                                        ?>
                                                        
                                                        <option value="<?php echo $each['name'];?>"><?php echo $each['name'];?></option>
                                                           <?php     
                                                            } ?>
                                                            
                                                        </select>
                                                </div>
                                            </div>
                                            
                                            <!-- .form-group -->
                                            
                                           
                                            <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="password">Location of Event (Give full address that can be located on google map)</label>
                                                  
                                                </div>
                                                <div class="form-control-wrap">
                                                   
                                                      <input class="form-control" type="text" id="searchText" name="event_location" value="">
                                                   <ul id="resultsList" class="result"></ul>
                                                </div>
                                            </div><!-- .form-group -->
                                            
                                            
                                            
                            </div>

                             <div class="col-xl-6 col-sm-6">
                                 
                                 
                                 
                                   <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Ads Activity</label>
                                                   
                                                </div>
                                                   <div class="row">
                                                <?php
                                                                foreach(getadsactivities()->result_array() as $each)
                                                                {
                                                        ?>
                                                 <div class="col-lg-6">
                                                <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" id="mySwitch<?php echo $each['id'];?>" name="activities[]" value="<?php echo $each['activities'];?>">
      <label class="form-check-label" for="mySwitch<?php echo $each['id'];?>"><?php echo $each['activities'];?></label>
    </div>
      </div>
    <?php     
                                                            } ?>
                                                            </div>
    
                                               
                                             
                                                
                                            </div><!-- .form-group -->
                                            
                                            
                                        
                              <div class="form-group" id ="listEventArea">             
                                            <div class="form-group pull-right text-end">
                                              <button class="btn btn-success btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addEventList">Add Event Items</button>
                                          </div>
      <?php 
      $detailsList = $this->db->get('listitemstemp');
      if($detailsList->num_rows() > 0){  ?>
         
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
       foreach($detailsList->result_array() as $item){    ?>
           <tr>
               <td><?php echo $item["event_name"]; ?></td>
               <td><?php echo $item["days"]; ?></td>
               <td><?php echo $item["starttime"]; ?></td>
                <td><?php echo $item["closetime"]; ?></td>
             <td><a href="<?php echo adminController();?>removeitemCart/<?php echo $item['id'];?>" onclick="return confirm('You want to remove this item');"><button id="removeItemCart" type="button" data-id="<?php echo $item["id"]; ?>" class="red-symbol" style="background-color:#F00; font-size:12px; color:#fff; border-radius:15px;padding-bottom:5px;padding-top:5px;border:#F00; margin-right:4px;padding-left:12px;padding-right:12px;"><i class="fa fa-trash"></i></button></a></td>
              
              
           
          
          </tr>
    <?php } ?> </table>
  </div>
   <?php }   ?>
</div>

                                            
                                            
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
                                                        
                                   <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Contact Name</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                    <input autocomplete="off" type="text" name="contactName"  class="form-control form-control-lg" placeholder="Enter Contact Name">
                                                </div>
                                            </div><!-- .form-group -->
                                            
                                            
                                            <div class="form-group">
                                                <div class="form-label-group">
                                                    <label class="form-label" for="email-address">Contact Phone Number</label>
                                                    
                                                </div>
                                                <div class="form-control-wrap">
                                                    <input autocomplete="off" type="text" name="contactPhone" class="form-control form-control-lg"  id="contactPhone" placeholder="Enter Contact phone">
                                                </div>
                                            </div><!-- .form-group -->
                                            
                                           
                                            
                                            
                            </div>
                             
                             
                             
                     
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">Submit</button>
                        </div>
                        </div>
                    </form>
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
    function activate() {
	document.head.insertAdjacentHTML("beforeend", `
		<style>
			.time-picker {
				position: absolute;
				display: inline-block;
				padding: 10px;
				background: #eeeeee;
				border-radius: 6px;
			}

			.time-picker__select {
				-webkit-appearance: none;
				-moz-appearance: none;
				appearance: none;
				outline: none;
				text-align: center;
				border: 1px solid #dddddd;
				border-radius: 6px;
				padding: 6px 10px;
				background: #ffffff;
				cursor: pointer;
				font-family: 'Heebo', sans-serif;
			}
		</style>
	`);

	document.querySelectorAll(".time-pickable").forEach(timePickable => {
		let activePicker = null;

		timePickable.addEventListener("focus", () => {
			if (activePicker) return;

			activePicker = show(timePickable);

			const onClickAway = ({ target }) => {
				if (
					target === activePicker
					|| target === timePickable
					|| activePicker.contains(target)
				) {
					return;
				}

				document.removeEventListener("mousedown", onClickAway);
				document.body.removeChild(activePicker);
				activePicker = null;
			};

			document.addEventListener("mousedown", onClickAway);
		});
	});
}

function show(timePickable) {
	const picker = buildPicker(timePickable);
	const { bottom: top, left } = timePickable.getBoundingClientRect();

	picker.style.top = `${top}px`;
	picker.style.left = `${left}px`;

	document.body.appendChild(picker);

	return picker;
}

function buildPicker(timePickable) {
	const picker = document.createElement("div");
	const hourOptions = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12].map(numberToOption);
	const minuteOptions = [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55].map(numberToOption);

	picker.classList.add("time-picker");
	picker.innerHTML = `
		<select class="time-picker__select">
			${hourOptions.join("")}
		</select>
		:
		<select class="time-picker__select">
			${minuteOptions.join("")}
		</select>
		<select class="time-picker__select">
			<option value="am">am</option>
			<option value="pm">pm</option>
		</select>
	`;

	const selects = getSelectsFromPicker(picker);

	selects.hour.addEventListener("change", () => timePickable.value = getTimeStringFromPicker(picker));
	selects.minute.addEventListener("change", () => timePickable.value = getTimeStringFromPicker(picker));
	selects.meridiem.addEventListener("change", () => timePickable.value = getTimeStringFromPicker(picker));

	if (timePickable.value) {
		const { hour, minute, meridiem } = getTimePartsFromPickable(timePickable);

		selects.hour.value = hour;
		selects.minute.value = minute;
		selects.meridiem.value = meridiem;
	}

	return picker;
}

function getTimePartsFromPickable(timePickable) {
	const pattern = /^(\d+):(\d+) (am|pm)$/;
	const [hour, minute, meridiem] = Array.from(timePickable.value.match(pattern)).splice(1);

	return {
		hour,
		minute,
		meridiem
	};
}

function getSelectsFromPicker(timePicker) {
	const [hour, minute, meridiem] = timePicker.querySelectorAll(".time-picker__select");

	return {
		hour,
		minute,
		meridiem
	};
}

function getTimeStringFromPicker(timePicker) {
	const selects = getSelectsFromPicker(timePicker);

	return `${selects.hour.value}:${selects.minute.value} ${selects.meridiem.value}`;
}

function numberToOption(number) {
	const padded = number.toString().padStart(2, "0");

	return `<option value="${padded}">${padded}</option>`;
}

activate();
</script>