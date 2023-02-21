<div class="skin-members-basic">
    <?=form_open_multipart()?>
    <input type="hidden" name="mem_userid" value="<?=$this->member->info('userid')?>">
    <div class="container P10">
        <input type="file" class="members-input" name="userfile" accept="image/*">
        <button type="submit" class="members-btn primary MT10"><i class="fa fa-upload"></i> Upload</button>
    </div>
    <?=form_close()?>
</div>
