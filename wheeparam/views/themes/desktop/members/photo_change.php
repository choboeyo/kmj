<?=form_open_multipart()?>
<input type="hidden" name="mem_userid" value="<?=$this->member->info('userid')?>">
<div class="container">
    <input type="file" class="form-control" name="userfile" accept="image/*">
    <div class="text-center MT10">
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> Upload</button>
    </div>
</div>
<?=form_close()?>
