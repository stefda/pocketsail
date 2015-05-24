
<style>
    .card-content-wrapper {
        background-color: #fff;
        border-radius: 3px;
        box-shadow: 1px 2px 10px rgba(0, 0, 0, 0.15);
        background-color: #fff;
        border-radius: 2px;
        padding: 20px;
    }

    .card-content .description {
        font-size: 13px;
        line-height: 1.3em;
    }

    .card-content .photos {
        margin-bottom: 13px;
    }

    .card-content .photos img {
        display: block;
        float: left;
    }

    .card-content .photos img:not(:first-child) {
        margin-left: 4px;
    }

    .card-content a {
        color: #4089fd;
        text-decoration: none;
    }

    .cardContent a:hover {
        text-decoration: underline;
    }

    .facility { display: block; float: left; width: 25px; height: 25px; background-image: url('/application/images/facilities.png'); }
    .facility.water { background-position-x: 0px; }
    .facility.electricity { background-position-x: -25px; }
    .facility.showers { background-position-x: -50px; }
    .facility.toilets { background-position-x: -75px; }
    .facility.waste { background-position-x: -100px; }
    .facility.customs { background-position-x: -125px; }
    .facility.enquiries { background-position-x: -150px; }
    .facility.laundry { background-position-x: -175px; }
    .facility.wifi { background-position-x: -200px; }
    .facility.disability { background-position-x: -225px; }
    .facility.pets { background-position-x: -250px; }

</style>

<script>

    $(function () {
//        $('.cardContent:not(.link)').on('click', 'a', function() {
//            var url = $(this).attr('href');
//            window.location.hash = url.substring(1, url.length);
//            return false;
//        });
    });

</script>

<?php
require_model('POITypeModel');
$catsMap = POITypeModel::cats_name_map();
$subsMap = POITypeModel::subs_name_map();
CL_Output::get_instance()->assign('catsMap', $catsMap);
CL_Output::get_instance()->assign('subsMap', $subsMap);
?>

<div class="card-content-wrapper">
    <div class="card-content">
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

        <?php
        $attrs = $poi->attrs();
        ?>

        <? if (isset($attrs->facilities)): ?>

            <div style="">
                <?php
                $facilities = [
                    'water' => 'Water',
                    'electricity' => 'Electricity',
                    'showers' => 'Showers',
                    'toilets' => 'Toilets',
                    'waste' => 'Waste Disposal',
                    'customs' => 'Customs',
                    'enquiries' => 'Tourist Info',
                    'laundry' => 'Laundry',
                    'wifi' => 'WiFi',
                    'disability' => 'Disability Access',
                    'pets' => 'Pets'
                ];
                ?>

                <? foreach ($facilities AS $facility => $name): ?>
                    <? if (isset($attrs->facilities->{$facility}) && $attrs->facilities->{$facility}->value == 'yes'): ?>
                        <span class="facility <?= $facility ?>" title="<?= $name ?>" style="margin-top: 10px;"></span>
                    <? endif; ?>
                <? endforeach; ?>

                <div style="clear: both;"></div>

            <? endif; ?>

        </div>
    </div>
</div>

<?php
require_model('PhotoModel');
$mainPhotoId = PhotoModel::get_main_id($poi->id());
$photoIds = PhotoModel::get_ids($poi->id());
$desc = @$poi->attrs()->description->details;
$descTrimmed = substr($desc, 0, 300);
if (strlen($desc) !== strlen($descTrimmed)) {
    $desc = rtrim($descTrimmed, '.') . '...';
}
?>

<? if ($mainPhotoId || strlen($desc) > 0): ?>
    <div class="card-content-wrapper" style="margin-top: 10px;">
        <div class="card-content">

            <? if (count($photoIds) > 0): ?>
                <div class="photos">
                    <? if (count($photoIds) === 1): ?>
                        <img src="/data/photos/thumb_wide/<?= $mainPhotoId ?>.jpg" />
                    <? else: ?>
                        <img src="/data/photos/thumb/<?= $mainPhotoId ?>.jpg" />
                        <img src="/data/photos/thumb/<?= $photoIds[0] != $mainPhotoId ? $photoIds[0] : $photoIds[1] ?>.jpg" />
                    <? endif; ?>
                    <div style="clear: both;"></div>
                </div>
            <? endif; ?>

            <div class="description">
                <?= $desc ?>
            </div>

        </div>
    </div>
<? endif; ?>
