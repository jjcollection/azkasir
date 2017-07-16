<form class="form-horizontal" id="form" name="form" method="post">
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Old password');?> *</label>
        <div class="col-sm-3">
            <input class="form-control" type="password" name="password_lama" id="password_lama"/>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('New password');?> *</label>
        <div class="col-sm-3">
            <input class="form-control" type="password" name="password_baru" id="password_baru"/>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Confirm Password');?> *</label>
        <div class="col-sm-3">
            <input class="form-control" type="password" name="password_ulang" id="password_ulang"/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-5 txt-right">
            <button type="button" class="btn btn-primary" id="btn_save_password"><?php echo azlang('Save');?></button>
        </div>
    </div>
</form>


<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#btn_save_password").on("click", function() {
            show_loading();
            jQuery.ajax({
                url: app_url+"user/proses_ubah_password",
                type: "POST",
                data: jQuery("#form").serialize(),
                dataType: "JSON",
                success: function(response){
                    hide_loading();
                    if (response.sMessage == "") {
                        jQuery("#password_lama").val("");
                        jQuery("#password_baru").val("");
                        jQuery("#password_ulang").val("");
                        bootbox.alert({
                            title: "Error",
                            message: "<?php echo azlang('Success save data');?>"
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
    });
</script>