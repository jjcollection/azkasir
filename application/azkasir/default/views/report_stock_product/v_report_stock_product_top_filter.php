<form class="form-horizontal az-form" name="form" method="post">
	<div class="form-group element-top-filter" data-id="<?php echo $stock_date;?>">
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
    </div>
    <div class="form-group">
        <label for="" class="col-sm-1 control-label"><?php echo azlang('Barcode');?></label>
        <div class="col-sm-4">
            <?php echo $data_product;?>
        </div>
        <label class="col-sm-7 control-label txt-left">
            <div id="l_product_name" class="l-product-name"></div>
        </label>
    </div>
</form>

<script type="text/javascript">
    jQuery("#barcode").on("keyup", function(e) {
        if (e.keyCode == 13) {
            get_single_product();
        }
    });

    jQuery("#barcode").focus();
</script>