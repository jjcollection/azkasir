
<script>
<?php
    $this->load->helper("az_stock");
    $stock_in = json_encode(az_get_stock_description('stok_masuk'));
    $stock_out = json_encode(az_get_stock_description('stok_keluar'));
?>

    var option_detail_in = <?php echo $stock_in;?>;
    var option_detail_out = <?php echo $stock_out;?>;
    var option_in = '';
    var option_out = '';
    var stock_type = "<?php echo $stock_type;?>";
    jQuery.each(option_detail_in, function(key, value){
        option_in += "<option value="+key+">"+value+"</option>";
    });
    jQuery.each(option_detail_out, function(key, value){
        option_out += "<option value="+key+">"+value+"</option>";
    });

    jQuery(document).ready(function() {
        jQuery("#detail2_box").hide();

        jQuery(".select#type").change(function() {
            set_select();
        });

        set_select();
        function set_select() {
            jQuery(".select#detail").select2('val', '');
            if (stock_type != 'out') {
                jQuery(".select#detail").html(option_in);
            }
            else {
                jQuery(".select#detail").html(option_out);
            }
        }

        jQuery(".select#detail").change(function() {
            hide_supplier();
        });

        hide_supplier();
        function hide_supplier() {
            if (jQuery(".select#detail").val() == "penambahan_stok") {
                jQuery("#div_supplier").show();
            }
            else {
                jQuery("#div_supplier").hide();
                jQuery("#idsupplier").select2("val", "");
            }
        }

        jQuery("#detail").change(function() {
            if (jQuery(this).val() == 'lain') {
                jQuery("#detail2_box").show();
            }
            else {
                jQuery("#detail2_box").hide();
            }
        });

        jQuery('.az-modal').on('shown.bs.modal', function () {
            jQuery("#barcode").focus();
            if (jQuery("#idproduct_stock").val() == "") {
                jQuery("#stock_date").val(jQuery.format.date(Date(), "dd-MM-yyyy HH:mm:ss"));
                jQuery("#product_name").val("-");
                jQuery("#l_product_name").text("-");
            }
        });  

        jQuery("#product_name").on("change", function() {
            jQuery("#l_product_name").text(jQuery("#product_name").val());
        });

        jQuery("#barcode, #qty").on("keyup", function(e) {
            jQuery("#l_product_name").text("-");
            if (e.keyCode == 13) {
                get_single_product();
            }
        });
    });