<?php
    $ci =& get_instance();
    $ci->load->helper("az_security");
    $tgc = az_encode_url($transaction_group_code);
?>
<form class="form-horizontal az-form" id="form_payment" name="form" method="post">
    <div class="form-group">    
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Date');?></label>
        <div class="col-sm-4">
            <?php
                echo $transaction_date;
            ?>
        </div>
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Customer');?></label>
        <div class="col-sm-4">
            <?php
                echo $select_customer;
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Nota');?></label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="transaction_group_code" id="transaction_group_code" disabled/>
        </div>
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Total Transaction');?></label>
        <div class="col-sm-4">
            <input type="text" class="form-control format-number txt-right" id="transaction_total" disabled value="0"/>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Total Cash');?></label>
        <div class="col-sm-4">
            <input type="text" class="form-control format-number txt-right" id="total_cash" maxlength="20" value="<?php echo $total_cash;?>"/>
        </div>
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Discount');?></label>
        <div class="col-sm-4">
            <input type="text" class="form-control format-number txt-right" id="total_discount" maxlength="20" value="<?php echo $total_discount;?>"/>
        </div>
    </div>
    <div class="total-change">
        <span><?php echo azlang('Change');?> Rp. </span> <span class="total-change-price">0</span>
    </div>
    <div class="grand-total-payment">
        <span><?php echo azlang('Grand Total');?> Rp. </span> <span class="grand-total-payment-price">0</span>
    </div>
</form>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('.az-modal').on('shown.bs.modal', function () {
            jQuery('#total_cash').focus();
            jQuery("#transaction_total").val(jQuery(".transaction-price").text());
            // jQuery("#total_cash").val("");
            jQuery("#total_cash").val(thousand_separator(jQuery("#total_cash").val()));
            jQuery("#transaction_group_code").val(jQuery("#transaction_group_code_hd").val());
            calculate_payment();
            check_btn();
            // jQuery("#transaction_date").val(jQuery.format.date(Date(), "dd-MM-yyyy HH:mm:ss"));
        });

        jQuery('#total_cash, #total_discount').on("keyup", function() {
            calculate_payment();
            check_btn();
        });

        jQuery(".btn-action-save").click(function() {
            save_payment('cash');
        });

        jQuery(".btn-action-save-print").click(function() {
            save_payment('cash', 'print');
        });

        jQuery(".btn-action-save-charge").click(function() {
            if (jQuery("#idcustomer").val() == "") {
                bootbox.alert({
                    title: "Error",
                    message: "Field Pelanggan harus diisi untuk pembayaran tipe Hutang"
                });
            }
            else {
                save_payment('credit');
            }
        });

        jQuery("#total_cash, #total_discount").on("keyup", function(e) {
            if (e.keyCode == 13) {
                save_payment('cash');
            }
        }); 

        function calculate_payment() {
            var total_transaction = remove_separator(jQuery("#transaction_total").val());
            var total_discount = remove_separator(jQuery("#total_discount").val());
            var total_cash = remove_separator(jQuery("#total_cash").val());
            var total_change = remove_separator(jQuery("#total_change").val());
            var change = remove_separator(jQuery(".total-change-price").text());

            var final_grand_total = total_transaction - total_discount;
            jQuery(".grand-total-payment-price").text(thousand_separator(final_grand_total));

            var grand_total = remove_separator(jQuery(".grand-total-payment-price").text());
            var final_change = total_cash - grand_total;
            jQuery(".total-change-price").text(thousand_separator(final_change));
        }

        function save_payment(payment_type, print_nota) {
            if(!print_nota) {
                print_nota = '';
            }

            show_loading();
            jQuery.ajax({
                url: app_url+'transaction/save_payment/'+payment_type,
                data: {
                    "transaction_date" : jQuery("#transaction_date").val(),
                    "idcustomer" : jQuery("#idcustomer").val(),
                    "total_cash" : jQuery("#total_cash").val(),
                    "transaction_total" : jQuery("#transaction_total").val(), 
                    "idtransaction_group" : jQuery("#idtransaction_group").val(),
                    "total_discount" : jQuery("#total_discount").val(),
                    "transaction_group_code" : jQuery("#transaction_group_code").val(),
                    "total_change_price" : jQuery(".total-change-price").text(),
                    "grand_total" : jQuery(".grand-total-payment-price").text()
                },
                dataType: 'JSON',
                type: 'POST',
                success: function(response){                
                    hide_loading();
                    if (response.sMessage == "") {
                        bootbox.alert({
                            title: "Sukses",
                            message: "<?php echo azlang('Transaction success');?>",
                            callback: function(brespon){
                                location.href = "<?php echo app_url();?>transaction";
                                if (print_nota == 'print') {
                                    window.open("<?php echo app_url();?>transaction_list/print_nota/?code=<?php echo $tgc;?>", '_blank');
                                }
                            }
                        });
                    }
                    else {
                        bootbox.alert({
                            title: "Error",
                            message: response.sMessage
                        });
                    }
                },
                error:function(response){
                    console.log(response);
                }
            });
        }

        function check_btn() {
            if (parseInt(jQuery("#transaction_total").val()) > 0) {
                jQuery(".btn-action-save").prop("disabled", false);
                jQuery(".btn-action-save-print").prop("disabled", false);
                check_btn_charge();
            }
            else {
                jQuery(".btn-action-save").prop("disabled", true);
                jQuery(".btn-action-save-print").prop("disabled", true);
                jQuery(".btn-action-save-charge").prop("disabled", true);
            }
        }

        function check_btn_charge() {
            if (parseInt(jQuery(".total-change-price").text()) >= 0) {
                jQuery(".btn-action-save").prop("disabled", false);
                jQuery(".btn-action-save-print").prop("disabled", false);
                jQuery(".btn-action-save-charge").prop("disabled", true);
            }
            else {
                jQuery(".btn-action-save").prop("disabled", true);
                jQuery(".btn-action-save-print").prop("disabled", true);
                jQuery(".btn-action-save-charge").prop("disabled", false);   
            }
        }

    });
</script>