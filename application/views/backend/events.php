
            <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <a href="<?php echo adminController();?>events"><div class="widget-two box--shadow2 b-radius--5  bg--white">

                <i class="las la-hammer overlay-icon text--info"></i>
    
    <div class="widget-two__icon b-radius--5   bg--info  ">
       <i class="las la-money-bill-wave-alt"></i>
    </div>

    <div class="widget-two__content">
        <h3><?php echo $this->db->get('events')->num_rows();?></h3>
        <p>All Events</p>
    </div>
 </div></a>

        </div>

        <div class="col-xxl-3 col-sm-6">
            <a href="<?php echo adminController();?>events?status=1"><div class="widget-two box--shadow2 b-radius--5  bg--white">
    <i class="la la-list overlay-icon text--primary"></i>
    <div class="widget-two__icon b-radius--5   bg--primary  ">
        <i class="fa fa-list"></i>
    </div>

    <div class="widget-two__content">
        <h3><?php echo $this->db->get_where('events', array('approval_status' => '1'))->num_rows();?></h3>
        <p>Approved Events</p>
    </div>

    </div></a>

        </div>

        <div class="col-xxl-3 col-sm-6">
            <a href="<?php echo adminController();?>events?status=0"><div class="widget-two box--shadow2 b-radius--5  bg--white">

                <i class="fa fa-list-alt overlay-icon text--success"></i>
    
    <div class="widget-two__icon b-radius--5   bg--success  ">
        <i class="fa fa-list-alt"></i>
    </div>

    <div class="widget-two__content">
       <h3><?php echo $this->db->get_where('events', array('approval_status' => '0'))->num_rows();?></h3>
        <p>Pending Events</p>
    </div>

          
    </div></a>

        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5  bg--white">

                <i class="fa fa-money-bill overlay-icon text--dark"></i>
    
    <div class="widget-two__icon b-radius--5   bg--dark  ">
        <i class="fa fa-money-bill"></i>
    </div>

    <div class="widget-two__content">
        <h3><?php echo $this->db->get_where('events', array('approval_status' =>'2'))->num_rows();?></h3>
        <p>Declined</p>
    </div>

           
    </div>


        </div>
    </div>
    
    
      <div class="row gy-4 mt-2">
						<div class="col-12 col-lg-12 col-xxl-12 d-flex">
							<div class="card flex-fill">
							    	<a href="postEvent"><div class="pull-right text-end" style="margin:10px; float:right"><button class="btn btn-success btn-sm">Add New</button></div></a>
                
								<div class="card-header">
								    
                                    <h5 class="card-title mb-0">Latest Events</h5>
                                  
								</div>
								<div class="card-body">
								
								 <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
        <thead style="background-color: gray;">
                <tr>
                    <th style="color: #fff;">SN</th>
                      <th style="color: #fff;">Reference ID</th>
                          <th style="color: #fff;">Added by</th>
                          <th style="color: #fff;">Category</th>
                          <th style="color: #fff;">Event Title</th>
                         <th style="color: #fff;">Event Description</th>
                          <th style="color: #fff;">Event Date / Time</th>
                         <th style="color: #fff;">Event Location</th>
                          <th style="color: #fff;">Status</th>
                          <th style="color: #fff;">Action</th>
                          
                        </tr>
                </thead>
                <tr>
                   <?php 
                         if(!empty($history)) 
                  {
                      $count = 1;
                    foreach ($history->result_array()  as $list){ 
                    if($list['approval_status'] == '1')
                    {$caption = 'Approved';$class = 'success';}else{$caption = 'pending';$class = 'danger';}?>
                          <tr>
                        <td><?php echo $count++;?></td>
                        <td><a href="<?php echo adminController();?>event_details/<?php echo $list['reference']; ?>"><?php echo $list['reference'];?></a></td>
                          <td>
                              <?php if($list['user_id'] =='0'){?>
                              Admin
                              <?php }else{?>
                              <a href="<?php echo adminController();?>customerDetails/<?php echo $list['user_id']; ?>" targe="blank"><?php echo get_user_info($list['user_id'])['fullname'];?></a>
                              <?php }?>
                              </td>
                         <td>
                            <?php if($list['ads_type'] !=''){ $categoryDetails = $this->db->get_where('adstype', array('id' => $list['ads_type']))->row_array(); echo $categoryDetails['type'];}; ?>
                         </td>
                         <td><?php echo $list['event_title'];?></td>
                         
                         <td><?php echo $list['event_description'];?></td>
                          <td><?php echo dateConvert($list['event_date']);?> - <?php echo $list['event_time'];?> </td>
                          <td><?php echo $list['event_location'];?></td>
                          <td><span class="badge bg-<?php echo $class;?>"><?php echo $caption;?></span></td>
                          <td><a href="<?php echo adminController();?>event_details/<?php echo $list['reference']; ?>"><button class="btn btn-success btn-sm">Details</button></a></td>
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
					
   <div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add New</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       <form action="<?php echo adminController();?>addEvent" method="POST" enctype="multipart/form-data" class="form-validate is-alter" autocomplete="off">
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
                                            <label class="form-label" for="email-address">Event Date</label>
                                            
                                        </div>
                                        <div class="form-control-wrap">
                                            <input autocomplete="off" type="date" name="event_date" class="form-control form-control-lg" required id="event_date" placeholder="Enter Event Date">
                                        </div>
                                    </div><!-- .form-group -->
                                    
                                    
                                     <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email-address">Event Time</label>
                                            
                                        </div>
                                        <div class="form-control-wrap">
                                            <input autocomplete="off" type="time" name="event_time" class="form-control form-control-lg" required id="event_time" placeholder="Enter Event Time">
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
                                                
                                                <option value="<?php echo $each['type'];?>"><?php echo $each['type'];?></option>
                                                   <?php     
                                                    } ?>
                                                    
                                                </select>
                                        </div>
                                    </div><!-- .form-group -->
                                    
                                      <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="email-address">Upload Image</label>
                                            
                                        </div>
                                        <div class="form-control-wrap">
                                            <input autocomplete="off" type="file" name="event_img" class="form-control form-control-lg" required id="event_img" placeholder="Enter Event Image">
                                        </div>
                                    </div><!-- .form-group -->
                                    
                                    <div class="form-group">
                                        <div class="form-label-group">
                                            <label class="form-label" for="password">Location of Event (Give full address that can be located on google map)</label>
                                          
                                        </div>
                                        <div class="form-control-wrap">
                                            <textarea  cols="4" rows="3" class="form-control" name="event_location"></textarea>
                                            
                                        </div>
                                    </div><!-- .form-group -->
                                    <div class="form-group">
                                        <button class="btn btn-lg btn-primary btn-block" type="submit">Add Event</button>
                                    </div>
                                </form><!-- form -->
            </div>
      </div>
      
    </div>
  </div>	