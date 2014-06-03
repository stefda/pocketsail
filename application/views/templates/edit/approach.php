
<div class="par">

    <h1>
        Approach & Pilotage
    </h1>

    <textarea class="attr text" name="attrs[approach][details]"><?= @$attrs->appraoch->description ?></textarea>

    <div class="hasDetail" style="margin-top: 5px;">

        <h2>Drying</h2>

        <select id="approachDrying" class="attr" type="checkbox" name="attrs[approach][drying][value]">
            <option value="na" <?= @$attrs->approach->drying->value === 'na' ? 'selected' : '' ?>>Don't know</option>
            <option value="no" <?= @$attrs->approach->drying->value === 'no' ? 'selected' : '' ?>>No</option>
            <option value="yes" <?= @$attrs->approach->drying->value === 'yes' ? 'selected' : '' ?>>Yes</option>
        </select>

        <div id="approachDryingDetails" style="display: none; padding-top: 8px;">
            <textarea class="attr detailsText" name="attrs[approach][drying][details]" placeholder="Please provide details..."><?= @$attrs->approach->drying->details ?></textarea>
        </div>

        <a class="detailsButton" href="">details</a>

    </div>

    <div class="details" style="padding-top: 8px; display: none;">
        <textarea class="attr detailsText" name="attrs[approach][drying][details]" placeholder="Provide any details..."></textarea>
    </div>

</div>

<script type="text/javascript">

    $(function() {

        $('#approachDrying').multiButton();
    });

</script>