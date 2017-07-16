<form class="form-horizontal" id="form" name="form" method="post">
    <input type="hidden" id="idcustomer" name="idcustomer"/>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Name');?> *</label>
        <div class="col-sm-7">
            <input class="form-control" type="text" name="name" id="name" maxlength="30" />
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Gender');?></label>
        <div class="col-sm-7">
            <select class='form-control' name='gender' id='gender'>
                <option value="laki-laki"><?php echo azlang('Male');?></option>
                <option value="perempuan"><?php echo azlang('Female');?></option>
            </select>
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
</form>
