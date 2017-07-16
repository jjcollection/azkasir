<form class="form-horizontal az-form" id="form" name="form" method="post">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="" class="col-sm-3 control-label"><?php echo azlang('Store name');?> *</label>
                <div class="col-sm-9">
                    <input class="form-control" type="text" name="store_name" id="store_name" value="<?php echo $store_name;?>" maxlength="20"/>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-3 control-label"><?php echo azlang('Description');?> *</label>
                <div class="col-sm-9">
                    <input class="form-control" type="text" name="store_description" id="store_description" value="<?php echo $store_description;?>" maxlength="70"/>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-3 control-label"><?php echo azlang('Phone');?> *</label>
                <div class="col-sm-9">
                    <input class="form-control" type="text" name="store_phone" id="store_phone" value="<?php echo $store_phone;?>" maxlength="50"/>
                </div>
            </div>
            <div class="form-group"> 
                <label for="" class="col-sm-3 control-label"><?php echo azlang('Prefix Nota');?> *</label>
                <div class="col-sm-9">
                    <input class="form-control" type="text" name="prefix_nota" id="prefix_nota" value="<?php echo $prefix_nota;?>" maxlength="4"/>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-3 control-label"><?php echo azlang('Prefix Barcode');?> *</label>
                <div class="col-sm-9">
                    <input class="form-control" type="text" name="prefix_barcode" id="prefix_barcode" value="<?php echo $prefix_barcode;?>" maxlength="3"/>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="" class="col-sm-1 control-label"><?php echo azlang('Logo');?></label>
                <div class="col-sm-11">
                    <?php echo $image;?>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <button type='button' class='btn btn-primary' id="btn_save_setting"><?php echo azlang('Save');?></button>
        </div>
    </div>
</form>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#btn_save_setting").on("click", function() {
            var formdata = new FormData();
            var file = jQuery('#image_logo')[0].files[0];
            formdata.append('logo', file);  
            jQuery.each($('#form').serializeArray(), function (a, b) {
                formdata.append(b.name, b.value);
            });

            show_loading();
            jQuery.ajax({
                url: app_url+"setting/save",
                type: "POST",
                data: formdata,
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(response){
                    hide_loading();
                    if (response.sMessage == "") {
                        bootbox.alert({
                            title: "Sukses",
                            message: "Simpan Data Berhasil",
                            callback: function() {
                                location.href = app_url+'setting';
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
                error: function(response) {
                    console.log(response);
                }
            });
        });


        jQuery(document).on("hidden.bs.modal", ".bootbox.modal", function (e) {
            location.href = app_url+'setting';
        });
    });
</script>