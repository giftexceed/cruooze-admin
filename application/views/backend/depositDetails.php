<div class="d-flex mb-30 flex-wrap gap-3 justify-content-between align-items-center">
    <div class="d-flex flex-wrap justify-content-end gap-2 align-items-center breadcrumb-plugins">
            </div>
</div>

                        <div class="row mb-none-30 justify-content-center">
        <div class="col-xl-6 col-md-6 mb-30">
            <div class="card box--shadow1 overflow-hidden">
                <div class="card-body">
                    <h5 class="text-muted mb-20"><?php echo payment_getways($list['gateway'])['gateway_name'];?></h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Date                            <span class="fw-bold"><?php echo date_convert($list['date_added']);?> - <?php echo $list['time_added'];?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Transaction Number                            <span class="fw-bold"><?php echo $list['reference'];?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Paid by                            <span class="fw-bold">
                                <a href="<?php echo adminController();?>customerDetails/<?php echo $list['user_id'];?>"><?php echo get_user_info($list['user_id'])['fullname'];?></a>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Method                            <span class="fw-bold"><?php echo payment_getways($list['gateway'])['gateway_name'];?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Amount                            <span class="fw-bold"><?php echo currency();?> <?php echo $list['amount'];?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Before                            <span class="fw-bold"><?php echo currency();?> <?php echo $list['initia_bal'];?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            After                            <span class="fw-bold"><?php echo currency();?> <?php echo $list['new_bal'];?></span>
                        </li>
                        
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Status                            <span class="badge badge--warning"><?php if($list['payment_status']=='0'){$get_status = 'Pending';}elseif($list['payment_status']=='1'){$get_status = 'Approved';}else{$get_status = 'Declined';}echo $get_status;?></span></li>
                                            </ul>
                                            
                                            <!--
                                            <div class="col-md-12">
                                    <button class="btn btn-outline--success btn-sm ms-1 confirmationBtn" data-action="#" data-question="Are you sure to approve this transaction?"><i class="las la-check-double"></i>
                                        Approve                                    </button>

                                    <button class="btn btn-outline--danger btn-sm ms-1" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <i class="las la-ban"></i> Reject                                    </button>
                                </div>
                                -->
                </div>
            </div>
        </div>
                    
            </div>
