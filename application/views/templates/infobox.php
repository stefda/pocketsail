
<style>
    .cardContent .description { line-height: 1.3em; }
    .cardContent a { color: #4089fd; text-decoration: none; }
    .cardContent a:hover { text-decoration: underline; }
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

<div class="cardContent" style="padding: 20px;">
    <div>
        <?= include_card_template($poi->cat(), $poi->sub()) ?>
    </div>
</div>
