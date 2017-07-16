<form class="form-horizontal az-form" id="form_product" name="form" method="post">
    <input type="hidden" name="idtransaction_group" tabindex="1" id="idtransaction_group" value="<?php echo $idtransaction_group;?>"/>
    <div class="form-group">
        <label for="" class="col-sm-1 control-label"><?php echo azlang('Barcode');?></label>
        <div class="col-sm-3">
            <?php
                echo $product;
            ?>
        </div>
        <label class="col-sm-7 control-label txt-left">
            <div id="l_product_name" class="l-product-name"></div>
        </label>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-1 control-label"><?php echo azlang('Qty');?></label>
        <div class="col-sm-1">
            <input class="form-control txt-center format-number" tabindex="2" type="number" min="1" name="qty" id="qty" placeholder="1" maxlength="5" />
        </div>
    </div>
</form>

<div class="transaction-group-code-div">
    Nota &nbsp;
    <input style="width:150px;" type='text' id='transaction_group_code_hd' readonly value='<?php echo $transaction_group_code;?>'/>
</div>

<div class="transaction-price">
    <?php echo $transaction_price;?>
</div>


<div class="transaction-btn">
    <button tabindex="3" class="btn btn-primary" type="button" id="btn_add_transaction"><i class="fa fa-plus"></i> <?php echo azlang('Add');?></button>&nbsp;
    <button tabindex="4" class="btn btn-primary" type="button" id="btn_payment"><i class="fa fa-floppy-o"></i> <?php echo azlang('Payment');?></button>&nbsp;
    <a href="<?php echo app_url().'transaction';?>"><button tabindex="5" class="btn btn-info" type="button"><i class="fa fa-file-o"></i> <?php echo azlang('New Transaction');?></button></a>
</div>
<br>

<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#barcode").focus();

        jQuery("#btn_add_transaction").click(function() {
            add_transaction();
        });

        jQuery("#barcode, #qty").on("keyup", function(e) {
            jQuery("#l_product_name").text("");
            if (e.keyCode == 13) {
                add_transaction();
            }
        });

        jQuery("#btn_hold").click(function() {
            hold_transaction();
        });

        jQuery("#btn_payment").click(function() {
            show_modal("payment");
            jQuery(".az-modal .modal-title").text("<?php echo azlang('Payment');?>");
        });

        jQuery(document).on("hidden.bs.modal", ".modal", function () {
            jQuery("#barcode").focus();
        });

        function add_transaction() {
            show_loading();
            jQuery.ajax({
                url: app_url+'transaction/add_transaction',
                data: {
                    "barcode" : jQuery("#barcode").val(),
                    "qty" : jQuery("#qty").val(),
                    "idcustomer" : jQuery("#idcustomer").val(),
                    "transaction_date" : jQuery("#transaction_date").val(), 
                    "idtransaction_group" : jQuery("#idtransaction_group").val()
                },
                dataType: 'JSON',
                type: 'POST',
                success: function(response){
                    hide_loading();
                    if (response.sMessage != "") {
                        bootbox.alert({
                            title: "Error",
                            message: response.sMessage
                        });
                    }
                    else {
                        jQuery("#idtransaction_group").val(response.idtransaction_group);
                    }

                    var dtable = jQuery('#transaction').dataTable({bRetrieve:true});
                    dtable.fnDraw();

                    jQuery("#barcode").val("");
                    jQuery("#l_product_name").text("");
                    jQuery("#qty").val("");
                    jQuery("#barcode").focus();

                    jQuery(".transaction-price").html(response.final_price);
                    jQuery("#btn_hold").prop("disabled", false);
                },
                error:function(response){
                    console.log(response);
                }
            });
        }

        function hold_transaction() {
            jQuery.ajax({
                url: app_url+'transaction/hold_transaction',
                data: {
                    "idtransaction_group" : jQuery("#idtransaction_group").val()
                },
                dataType: 'JSON',
                type: 'POST',
                success: function(response){
                    if (response.sMessage != "") {
                        bootbox.alert({
                            title: "Error",
                            message: response.sMessage
                        });
                    }
                    else {
                        bootbox.alert({
                            title: "Success",
                            message: "Transaksi Berhasil Ditahan"
                        });
                        location.href = "<?php echo app_url();?>transaction";
                    }
                },
                error:function(response){
                    console.log(response);
                }
            });
        }

    });
</script>