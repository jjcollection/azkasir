<form class="form-horizontal az-form" id="form" name="form" method="post">
    <input type="hidden" id="tr_idtransaction" name="tr_idtransaction"/>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Barcode');?></label>
        <div class="col-sm-7">
            <input class="form-control" type="text" name="tr_barcode" id="tr_barcode" readonly/>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Product Name');?></label>
        <div class="col-sm-7">
            <input class="form-control" type="text" name="tr_name" id="tr_name" readonly/>
        </div>
    </div>
    <div class="form-group"> 
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Price');?></label>
        <div class="col-sm-7">
            <input class="form-control format-number txt-right calculate" type="text" name="tr_sell_price" id="tr_sell_price" maxlength="15" />
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Qty');?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control format-number txt-right calculate" name="tr_qty" id="tr_qty" maxlength="5" />
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Discount');?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control format-number txt-right calculate" name="tr_discount" id="tr_discount" maxlength="15" />
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Total');?></label>
        <div class="col-sm-7">
            <input type="text" class="form-control format-number txt-right" name="tr_final_price" id="tr_final_price" readonly/>
        </div>
    </div>
</form>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('.az-modal').on('shown.bs.modal', function () {
            jQuery('#tr_sell_price').focus();
            calculate_total();
        });  

        jQuery(".calculate").on('keyup', function() {
            calculate_total();
        });
 
        function calculate_total() {
            var sell_price = remove_separator(jQuery("#tr_sell_price").val());
            var discount = remove_separator(jQuery("#tr_discount").val());
            var qty = remove_separator(jQuery("#tr_qty").val());

            var total = (sell_price * qty) - discount;
            jQuery("#tr_final_price").val(thousand_separator(total));
        }
    });
</script>