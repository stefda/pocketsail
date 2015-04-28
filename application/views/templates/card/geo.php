<div style="color: #343536;">

    <img src="/application/images/card/island-small.png" style="float: left; margin-right: 8px;" />

    <div style="font-size: 15px; margin-bottom: 2px; font-weight: bold;">
        <?= $poi->name() ?> <span style="">(<?= $poi->subName() ?>)</span>
    </div>

    <div style="font-size: 12px;">
        <?
        $latLng = $poi->latLng();
        echo $latLng->latFormatted() . ", " . $latLng->lngFormatted();
        ?>
    </div>
    
    <div class="description" style="font-size: 13px; padding-top: 10px;">
        <?
        $desc = @$poi->attrs()->description->details;
        $descTrimmed = substr($desc, 0, 300);
        if (strlen($desc) !== strlen($descTrimmed)) {
            $desc = rtrim($descTrimmed, '.') . '...';
        }
        echo html($desc);
        ?>
    </div>

</div>