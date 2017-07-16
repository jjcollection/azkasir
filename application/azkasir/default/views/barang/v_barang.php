<form class="form-horizontal az-form" id="form" name="form" method="post">
    <input type="hidden" id="idbarang" name="idbarang"/>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('kodeBarang');?> *</label>
        <div class="col-sm-7">
            <input class="form-control" type="text" name="kodeBarang" id="kodeBarang" maxlength="30" />
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('namaBarang');?></label>
        <div class="col-sm-7">
            <input class="form-control" type="text" name="namaBarang" id="namaBarang" maxlength="100"/>
        </div>
    </div>
    <div class="form-group"> 
        <label for="" class="col-sm-3 control-label"><?php echo azlang('kategori');?></label>
        <div class="col-sm-7">
            <input class="form-control" type="text" name="kategori" id="kategori" maxlength="20"/>
        </div>
    </div>
    <div class="form-group">
        <label for="" class="col-sm-3 control-label"><?php echo azlang('harga');?></label>
        <div class="col-sm-7">
            <textarea class="form-control" name="harga" id="harga" maxlength="300"></textarea>
        </div>
    </div>
</form>