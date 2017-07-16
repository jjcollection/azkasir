<?php
    $ci =& get_instance();
    $ci->load->library("encrypt");
?>
<form class="form-horizontal az-form" name="form" method="post">
	<div class="form-group element-top-filter" data-id="<?= $ci->encrypt->encode("transaction_date");?>">
        <label for="" class="col-sm-1 control-label"><?php echo azlang('Date');?></label>
        <div class="col-sm-4">
            <div class="row">
                <div class="col-sm-5">
                    <?php echo $datetime1;?>
                </div>
                <div class="col-sm-2 div-between-col">
                	s/d
                </div>
                <div class="col-sm-5">
                	<?php echo $datetime2;?>
                </div>
            </div>
        </div>
        <label for="" class="col-sm-1 control-label"><?php echo azlang('Customer');?></label>
        <div class="col-sm-3">
            <?php echo $customer;?>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-1 control-label"><?php echo azlang('Nota');?></label>
        <div class="col-sm-4">
            <input type="text" class="form-control element-top-filter" name="transaction_group_code" id="transaction_group_code" data-id="<?= $ci->encrypt->encode('code');?>"/>
        </div>
        <?php
            if ($this->session->userdata("user_type") == "administrator") {
        ?>
                <label for="" class="col-sm-1 control-label"><?php echo azlang('Cashier');?></label>
                <div class="col-sm-3">
                    <?php echo $kasir;?>
                </div>
        <?php
            }
        ?>
    </div>
</form>