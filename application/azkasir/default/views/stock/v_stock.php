<form class="form-horizontal az-form" id="form" name="form" method="post">
    <input type="hidden" id="idproduct_stock" name="idproduct_stock"/>
    <input type="hidden" id="idproduct" name="idproduct"/>
    <input type="hidden" id="product_name"/>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Date');?> *</label>
        <div class="col-sm-6">
            <div class='input-group az-datetime'>
                <input type='text' class="form-control" id="stock_date" name="stock_date" value=""/>
                <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Barcode');?> *</label>
        <div class="col-sm-6">
            <?php
                echo $data_product;
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Product Name');?></label>
        <div class="col-sm-6">
            <label class="control-label" id="l_product_name"></label>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Detail');?> *</label>
        <div class="col-sm-6">
            <select class="form-control select" id="detail" name="detail">
            </select>
        </div>
    </div>
    <div id="detail2_box" class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Other Detail');?></label>
        <div class="col-sm-6">
            <input type="text" class="form-control" id="detail2" name="detail2" maxlength="20" />
        </div>
    </div>
    <div class="form-group" id="div_supplier">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Supplier');?></label>
        <div class="col-sm-6">
            <?php echo $supplier;?>
        </div> 
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Total');?> *</label>
        <div class="col-sm-6">
            <input type="text" class="form-control txt-right format-number" id="total" name="total" placeholder="0" maxlength="10"/>
        </div>
    </div>
</form>