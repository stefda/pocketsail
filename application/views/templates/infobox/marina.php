<div style="color: #343536;">

    <div style="font-size: 16px; margin-bottom: 4px; font-weight: bold;">
        <?= $poi->name() ?> (<?= $subsMap[$poi->sub()] ?>)
        <a class="link" href="/<?= $poi->url() ?>">
            <img src="/application/images/open_in_new_window.png" style="margin-left: 2px; vertical-align: text-bottom;" />
        </a>
    </div>

    <div style="font-size: 13px;">
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
        echo $desc;
        ?>
    </div>

</div>