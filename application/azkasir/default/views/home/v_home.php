<?php
    $ci =& get_instance();
    $ci->load->helper("az_core");
?>
<div class="row">
	<div class="col-sm-6">
		<div class="box-home">
			<div class="box-home-title">
				<i class='fa fa-money'></i> <?php echo azlang("THIS MONTH'S SALES");?>
			</div>
			<div class="box-home-content">
				<a href="<?php app_url();?>home/graph_transaction_month" target="_blank">
                    <img style="width:100%" src="<?php app_url();?>home/graph_transaction_month"/>
                </a>
			</div>
		</div>
	</div>
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-6">
                <div class="box-home">
                    <div class="box-home-title">
                        <i class='fa fa-money'></i> <?php echo azlang('TODAY SALES');?>
                    </div>
                    <div class="box-home-content box-content-bg-blue">
                        <div class="content-btn">
                            <a href="<?php echo app_url();?>transaction_list"><i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                        <div class="content-transaction">
                            <?php echo $total;?> <?php echo azlang('transaction');?>
                        </div>
                        <div class="content-rp">
                            <div class="content-rp-rp">
                                <div class="content-caret"></div>
                                <span>Rp</span>&nbsp;<?php echo $price;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="box-home">
                    <div class="box-home-title">
                        <i class='fa fa-money'></i> <?php echo azlang('LAST TRANSACTION');?>
                    </div>
                    <div class="box-home-content box-content-bg-blue">
                        <div class="content-btn">
                            <div class="content-text">
                                <?php echo $last_transaction_date;?>
                            </div>
                        </div>
                        <div class="content-transaction">
                            <?php echo $total_last_transaction;?> <?php echo azlang('product');?>
                        </div>
                        <div class="content-rp">
                            <div class="content-rp-rp">
                                <div class="content-caret"></div>
                                <span>Rp</span>&nbsp;<?php echo $last_transaction;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row home-row-2">
            <div class="col-sm-6">
                <div class="box-home">
                    <div class="box-home-title">
                        <i class='fa fa-pie-chart'></i><?php echo azlang('SELLING PRODUCT');?>
                    </div>
                    <div class="box-home-content box-content-2">
                       <a target="blank" href="<?php echo app_url();?>home/graph_product"><img src="<?php echo app_url();?>home/graph_product"/></a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="box-home">
                    <div class="box-home-title">
                        <i class='fa fa-arrow-down'></i><?php echo azlang('PRODUCT STOCK');?>
                    </div>
                    <div class="box-home-content box-content-bg-blue box-content-2 box-scroll-row">
                        <?php
                            foreach ($product_stock->result() as $key => $value) {
                        ?>
                            <div class="box-content-scroll">
                                <div class="box-scroll-product">
                                    <?php echo $value->name;?>
                                </div>
                                <div class="box-scroll-total">
                                    <div class="scroll-caret"></div>
                                    <div class="scroll-total">
                                        <?php echo az_thousand_separator($value->stock);?>
                                    </div>
                                </div>
                            </div>
                        <?php  
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>