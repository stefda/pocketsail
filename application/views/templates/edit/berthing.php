
<? include_view('templates/edit/description'); ?>
<? include_view('templates/edit/approach'); ?>
<? include_view('templates/edit/attractions'); ?>
<? include_view('templates/edit/hazards'); ?>
<?= include_view('templates/edit/season'); ?>
<?= include_view('templates/edit/opening'); ?>
<?= include_view('templates/edit/sources'); ?>

<div class="par">
    <input type="button" id="saveButton" value="Save" />
</div>

<script>

    $(function() {
        var id = <?= $poi->id() ?>;
        var latLng = LatLng.fromWKT('<?= $poi->latLng() === NULL ? 'NULL' : $poi->latLng()->toWKT() ?>');
        var border = Polygon.fromWKT('<?= $poi->border() === NULL ? 'NULL' : $poi->border()->toWKT() ?>');
    });

</script>