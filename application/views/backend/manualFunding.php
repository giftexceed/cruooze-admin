<div class="row ">
        <div class="col-xl-12">
            <div class="card">
                <form class="notify-form" action="<?php echo adminController();?>manual_topup" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="row">
                           <div class="form-group col-md-12 subject-wrapper" id="single_email">
                                <label>Select Type <span class="text--danger">*</span> </label>
                                 <select class="form-control select2" required name="actiontype" id="member_option">
                                     <option value=""></option>
                                    <option value="credit">Credit</option>
                                    <option value="debit">Debit</option>
                                </select>
                             </div>
                                  <div class="col-md-12">
                                <div class="form-group">
                                    <label>Enter Customer Phone number or Email address </label>
                                    <input type="text" class="form-control" placeholder="" name="user_id"
                                    value="">
                                  </div>
                                <div class="input-append">
                                </div>
                            </div>
                            <div class="form-group col-md-12 subject-wrapper">
                                <label>Amount <span class="text--danger">*</span> </label>
                                <input type="number" step="any" min="1" name="amount"class="form-control" placeholder="" value="">
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