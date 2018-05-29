<div class="container">
    <div class="row">
        <div class="col-sm-4">
            <?=latest('basic', 'notice', 5,  FALSE, FALSE)?>
        </div>
        <div class="col-sm-4">
            <?=latest('basic', 'freeboard', 5,  FALSE, FALSE)?>
        </div>
    </div>
</div>

<?=$asides_popup?>