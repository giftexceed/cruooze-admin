<style>
       .countdown {
            position: relative;
            height: 100px;
            width: 100px;
            text-align: center;
            margin: 0 auto;
        }

        .coaling-time {
            color: yellow;
            position: absolute;
            z-index: 999999;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 30px;
        }

        .coaling-loader svg {
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            transform: rotateY(-180deg) rotateZ(-90deg);
            position: relative;
            z-index: 1;
        }

        .coaling-loader svg circle {
            stroke-dasharray: 314px;
            stroke-dashoffset: 0px;
            stroke-linecap: round;
            stroke-width: 6px;
            stroke: #4634ff;
            fill: transparent;

        }

        .coaling-loader .svg-count {
            width: 100px;
            height: 100px;
            position: relative;
            z-index: 1;
        }

        .coaling-loader .svg-count::before {
            content: '';
            position: absolute;
            outline: 5px solid #f3f3f9;
            z-index: -1;
            width: calc(100% - 16px);
            height: calc(100% - 16px);
            left: 8px;
            top: 8px;
            z-index: -1;
            border-radius: 100%
        }

        .coaling-time-count {
            color: #4634ff;
        }

        @keyframes countdown {
            from {
                stroke-dashoffset: 0px;
            }

            to {
                stroke-dashoffset: 314px;
            }
        }
   
</style>
<?php $bulkEMail = $this->db->get_where('broadcast',array('id'=>'1'))->row_array();?>
<div class="row ">
        <div class="col-xl-12">
            <div class="card">
                <form class="notify-form" action="<?php echo adminController();?>send_broadcast"method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="row">
                           
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Being Sent To </label>
                                    <select class="form-control select2" name="being_sent_to" id="member_option">
                                                                                    <option value="0">All Users</option>
                                                                                   
                                                                                       
                                                                            </select>
                                    
                                </div>
                                <div class="input-append">
                                </div>
                            </div>
                            <div class="form-group col-md-12 subject-wrapper" id="single_email">
                                <label>Email <span class="text--danger">*</span> </label>
                                <input type="email" class="form-control" placeholder="Email Address" name="single_email"
                                    value="">
                            </div>
                            <div class="form-group col-md-12 subject-wrapper">
                                <label>Subject <span class="text--danger">*</span> </label>
                                <input type="text" class="form-control" value="<?php echo $bulkEMail['title'];?>" placeholder="Subject / Title" name="subject"
                                    value="">
                            </div>
                            <div class="form-group col-md-12 subject-wrapper">
                                <label>Status <span class="text--danger">*</span> </label>
                                <select name="status" class="form-control" required>
                        <?php if($bulkEMail['status'] =='1'){?>
                            <option value="1" selected>On</option>
                         <option value="0">Off</option>
                        <?php }else{ ?>
                            <option value="0" selected>Off</option>
                           <option value="1">On</option>
                        <?php } ?>
                        </select>
                            </div>
                           
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Message <span class="text--danger">*</span> </label>
                                    <textarea class="form-control nicEdit" id="nicEdit" name="message" rows="10"><?php echo $bulkEMail['body'];?></textarea>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn w-100 h-45 btn--primary me-2" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
    $("#single_email").hide();
</script>
<script>
$(document).ready(function(){
 $('#member_option').change(function(){
  var userType = $('#member_option').val();
if(userType =='4')
  {
   $("#single_email").show();
  }else{
       $("#single_email").hide();
  } 
 });
});    
</script>  