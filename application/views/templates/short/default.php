
<div style="padding: 10px 13px;">
    <div style="margin-bottom: 10px;">
        <div style="float: right;"><a class="nolink closeShortInfo" href="">close</a></div>
        <div>
            <div>
                <?= $info->name ?> <span style="font-size: 12px;">(<?= $info->subName ?>)</span>
            </div>
            <? if ($info->nearName !== '' && $info->countryName): ?>
                <div style="font-size: 12px; color: #444;">
                    <?= $info->nearName ?>, <?= $info->countryName ?>
                </div>
            <? endif; ?>
        </div>
    </div>
    <div style="font-size: 12px;"><?= $info->features->description ?></div>
</div>