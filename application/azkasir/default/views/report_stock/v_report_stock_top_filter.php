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
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Barcode');?></label>
        <div class="col-sm-4">
            <input type="text" name='barcode' class="form-control element-top-filter" data-id="<?php echo $product_barcode;?>">
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-1 control-label"><?php echo azlang('Description');?></label>
        <div class="col-sm-4">
            <select id="product_name_filter" name='detail' class='form-control element-top-filter select' data-id="<?php echo $product_detail;?>">
            </select>
        </div>
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Product Name');?></label>
        <div class="col-sm-4">
            <input type="text" name='product_name' class="form-control element-top-filter" data-id="<?php echo $product_name;?>">
        </div>
    </div>
    <?php 
        if ($stock_type != "out") {
    ?>
    <div class="form-group">
        <label class="col-sm-1 control-label"><?php echo azlang('Supplier');?></label>
        <div class="col-sm-4">
            <?php echo $supplier;?>
        </div>
    </div>
    <?php
        }
    ?>
</form>
<?php
    $this->load->helper("az_stock");
    $stock_in = json_encode(az_get_stock_description('stok_masuk'));
    $stock_out = json_encode(az_get_stock_description('stok_keluar'));
?>

<script type="text/javascript">
    var option_detail_in = <?php echo $stock_in;?>;
    var option_detail_out = <?php echo $stock_out;?>;
    var option_in = '<option value=""><?php echo azlang('All');?></option>';
    var option_out = '<option value=""><?php echo azlang('All');?></option><option value="penjualan"><?php echo azlang('Sale');?></option>';
    var stock_type = "<?php echo $stock_type;?>";
    jQuery.each(option_detail_in, function(key, value){
        option_in += "<option value="+key+">"+value+"</option>";
    });
    jQuery.each(option_detail_out, function(key, value){
        option_out += "<option value="+key+">"+value+"</option>";
    });

    if (stock_type == 'out') {
        jQuery("#product_name_filter").html(option_out);
    }
    else {
        jQuery("#product_name_filter").html(option_in);
    }

</script>
