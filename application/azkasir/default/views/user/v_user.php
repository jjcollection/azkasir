<form class="form-horizontal" id="form" name="form" method="post">
    <input type="hidden" id="iduser" name="iduser"/>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label">Username *</label>
        <div class="col-sm-7">
            <input class="form-control" type="text" name="username" id="username" maxlength="20" />
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label">Password *</label>
        <div class="col-sm-7">
            <input class="form-control" type="password" name="password" id="password" maxlength="20"/>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('Name');?> *</label>
        <div class="col-sm-7">
            <input class="form-control" type="text" name="name" id="name" maxlength="30"/>
        </div>
    </div>
</form>
