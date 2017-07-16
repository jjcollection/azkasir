<form class="form-horizontal az-form" id="form" name="form" method="post">
    <input type="hidden" id="idsupplier" name="idsupplier"/>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Name');?> *</label>
        <div class="col-sm-7">
            <input class="form-control" type="text" name="name" id="name" maxlength="30" />
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Address');?></label>
        <div class="col-sm-7">
            <input class="form-control" type="text" name="address" id="address" maxlength="100"/>
        </div>
    </div>
    <div class="form-group"> 
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Phone');?></label>
        <div class="col-sm-7">
            <input class="form-control" type="text" name="phone" id="phone" maxlength="20"/>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Description');?></label>
        <div class="col-sm-7">
            <textarea class="form-control" name="description" id="description" maxlength="300"></textarea>
        </div>
    </div>
</form>