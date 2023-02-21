<div class="history-skin-basic">
    <?php if(count($list) == 0) :?>
        <div class="list-empty">등록된 연혁이 없습니다.</div>
    <?php else :?>
        <ol class="history-list">
            <?php foreach($list as $year=>$row) :?>
                <dl>
                    <dt><?=$year?></dt>
                    <dd>
                    <?php foreach($row as $month=>$content):?>
                    <ul>
                        <?php foreach($content as $values):?>
                        <li>
                            <span class="date"><?=sprintf('%02d',$month)?></span>
                            <p class="content"><?=$values?></p>
                        </li>
                        <?php endforeach;?>
                    </ul>
                    <?php endforeach;?>
                    </dd>
                </dl>
            <?php endforeach;?>
        </ol>
    <?php endif;?>
</div>
