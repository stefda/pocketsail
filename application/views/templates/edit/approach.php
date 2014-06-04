
<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Approach & Pilotage</h1>

        <textarea class="tpl-details-large attr" name="attrs[approach][details]"><?= @$attrs->approach->details ?></textarea>

        <div class="tpl-has-details-button" style="margin-top: 8px;">

            <h2>Approach drying</h2>

            <select class="tpl-select-button attr" name="attrs[approach][drying][value]">
                <option value="na" <?= @$attrs->approach->drying->value === 'na' ? 'selected' : '' ?>>Don't know</option>
                <option value="no" <?= @$attrs->approach->drying->value === 'no' ? 'selected' : '' ?>>No</option>
                <option value="yes" <?= @$attrs->approach->drying->value === 'yes' ? 'selected' : '' ?>>Yes</option>
            </select>

            <span class="tpl-details-button">details</span>

        </div>

        <div class="tpl-details">
            <textarea class="tpl-details-large attr" name="attrs[approach][drying][details]" placeholder="Please provide details..."><?= @$attrs->approach->drying->details ?></textarea>
        </div>

    </div>
</div>

<script type="text/javascript">

    $(function() {
        validator.add(function() {
            return true;
        });
    });

</script>