<div style="color: #343536;">

    <img src="/application/images/card/anchorage-small.png" style="float: left; margin-right: 8px;" />

    <div style="font-size: 15px; margin-bottom: 2px; font-weight: bold;">
        Anchorage near <?= $poi->nearName() ?>
    </div>

    <div style="font-size: 12px;">
        <?
        $latLng = $poi->latLng();
        echo $latLng->latFormatted() . ", " . $latLng->lngFormatted();
        ?>
    </div>

</div>