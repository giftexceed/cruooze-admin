 <div class="row gy-4" style="margin-bottom:15px">
        <div class="col-lg-12">
            <div class="card">
                	
               <div class="show-filter mb-3 text-end">
            <button type="button" class="btn btn-outline--primary showFilterBtn btn-sm"><i class="las la-filter"></i> Filter</button>
        </div>
            <div class="card responsive-filter-card mb-4">
            <div class="card-body">
                <form method="post" action="<?php echo adminController();?>salesAnalysis">
                    <div class="d-flex flex-wrap gap-4">
                     
                        <div class="flex-grow-1">
                            <label>Select Date</label>
                            <input type="date" name="fromDate" value="" class="form-control">
                        </div>
                     
                        <div class="flex-grow-1 align-self-end">
                            <button class="btn btn--primary w-100 h-45" name="search" type="submit"><i class="fa fa-search"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
     </div>
        </div>  
</div>  

 <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5  bg--white">

                <i class="fa fa-hammer overlay-icon text--info"></i>
    
    <div class="widget-two__icon b-radius--5   bg--info  ">
        <i class="fa fa-hammer"></i>
    </div>

    <div class="widget-two__content">
        <h3><?php echo currency();?> <?php echo $this->Admin_model->getTotalProfit($date);?></h3>
        <p>Profit Today</p>
    </div>

            <a href="<?php echo adminController();?>transactions" class="widget-two__btn btn btn-outline--info">View All</a>
    </div>

        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5  bg--white">

                <i class="la la-list overlay-icon text--primary"></i>
    
    <div class="widget-two__icon b-radius--5   bg--primary  ">
        <i class="fa fa-list"></i>
    </div>

    <div class="widget-two__content">
        <h3><?php echo currency();?> <?php echo $this->Admin_model->getTotalFund($date);?></h3>
        <p>Total Wallet funds today</p>
    </div>

            <a href="<?php echo adminController();?>deposits/1" class="widget-two__btn btn btn-outline--primary">View All</a>
    </div>

        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5  bg--white">

                <i class="fa fa-list-alt overlay-icon text--success"></i>
    
    <div class="widget-two__icon b-radius--5   bg--success  ">
        <i class="fa fa-list-alt"></i>
    </div>

    <div class="widget-two__content">
        <h3><?php echo currency();?> <?php echo $this->Admin_model->getTotalSales($date);?></h3>
        <p>Today Sales</p>
    </div>

            <a href="<?php echo adminController();?>transactions" class="widget-two__btn btn btn-outline--success">View All</a>
    </div>

        </div>

        <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5  bg--white">

                <i class="fa fa-money-bill overlay-icon text--dark"></i>
    
    <div class="widget-two__icon b-radius--5   bg--dark  ">
        <i class="fa fa-money-bill"></i>
    </div>

    <div class="widget-two__content">
        <h3><?php echo $this->db->get_where('transactions', array('date_added' => $date))->num_rows();?></h3>
        <p>Order Count Today</p>
    </div>

            <a href="<?php echo adminController();?>transactions" class="widget-two__btn btn btn-outline--dark">View All</a>
    </div>


        </div>
        
        
    </div><!-- row end-->
    
            
        
 <div class="row gy-4" style="margin-top:15px">
        <?php if($services->num_rows() > 0){
            foreach($services->result_array() as $each)
        {
    ?>
     <div class="col-xxl-3 col-sm-6">
            <div class="widget-two box--shadow2 b-radius--5  bg--white">

                <i class="fa fa-money-bill overlay-icon text--dark"></i>
    
    <div class="widget-two__icon b-radius--5   bg--dark  ">
        <i class="fa fa-money-bill"></i>
    </div>

    <div class="widget-two__content">
        <h3><?php echo $this->Admin_model->getTotalServiceTotal($each['id'],$date);?></h3>
        <p><?php echo $each['name'];?></p>
    </div>

            <a href="<?php echo adminController();?>transactions" class="widget-two__btn btn btn-outline--dark">View All</a>
    </div>


        </div>
        
    
    <?php
    }
        }
    ?>    
    </div><!-- row end-->
    
    
    