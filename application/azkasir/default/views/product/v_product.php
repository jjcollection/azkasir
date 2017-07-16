<form class="form-horizontal az-form" id="form" name="form" method="post">
    <input type="hidden" id="idproduct" name="idproduct"/>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Barcode');?> *</label>
        <div class="col-sm-3">
            <input class="form-control" type="text" name="barcode" id="barcode" maxlength="20"/>
        </div>
        <div class="col-sm-1">
            <button class="btn btn-default" type="button" id="btn_reload_code"><span class="glyphicon glyphicon-refresh"></span></button>
        </div>
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Category');?></label>
        <div class="col-sm-4">
            <select class='form-control select' name="idproduct_category" id="idproduct_category">
                <option value="">-</option>
                <?php
                    foreach($data_category->result() as $value) {
                        echo "<option value='".$value->idproduct_category."'>".$value->name."</option>";
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Product Name');?> *</label>
        <div class="col-sm-4">
            <input class="form-control" type="text" name="name" id="name" maxlength="30"/>
        </div>
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Unit');?></label>
        <div class="col-sm-4">
            <select class='form-control select' name="idproduct_unit" id="idproduct_unit">
                <option value="">-</option>
                <?php
                    foreach($data_unit->result() as $value) {
                        echo "<option value='".$value->idproduct_unit."'>".$value->name."</option>";
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('Price');?> *</label>
        <div class="col-sm-4">
            <input type='text' class='form-control txt-right format-number' name='price' id='price' maxlength="15"/>
        </div>
        <label for="" class="col-sm-2 control-label"><abbr title="<?php echo azlang('Change stock only can in menu PRODUCT -> STOCK PRODUCT');?>">Stok</abbr></label>
        <div class="col-sm-4">
            <abbr title="<?php echo azlang('Change stock only can in menu PRODUCT -> STOCK PRODUCT');?>"><input type='text' class='form-control txt-right' name='stock' id='stock' readonly value='0'/></abbr>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-2 control-label"><?php echo azlang('description');?></label>
        <div class="col-sm-10">
            <textarea class="form-control" name="description" id="description" maxlength="100"></textarea>
        </div>
    </div>
</form>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#btn_reload_code").click(function() {
            jQuery.ajax({
                url: app_url+'product/generate_barcode',
                dataType: 'json',
                success: function(response){
                    jQuery("#barcode").val(response.return);
                },
                error: function(response){
                    console.log(response);
                }
            });
        });
    });
</script>