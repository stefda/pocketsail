
<style type="text/css">
    input.seasonMonths { font-size: 13px; padding-left: 3px; width: 70px; border: solid 1px #aaa; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>
            Opening Season
        </h1>

        <div class="tpl-has-details-button">

            <select id="season" class="attr" name="attrs[season][value]">
                <option value="na" <?= @$attrs->season->value === 'na' ? 'selected' : '' ?>>Don't know</option>
                <option value="allyear" <?= @$attrs->season->value === 'allyear' ? 'selected' : '' ?>>All year</option>
                <option value="seasonal" <?= @$attrs->season->value === 'seasonal' ? 'selected' : '' ?>>Seasonal</option>
            </select>

            <span id="seasonal" style="margin-left: 10px;<?= @$attrs->season->value === 'seasonal' ? '' : ' display: none;' ?>">
                <select class="attr seasonMonths" name="attrs[season][from]">
                    <option value="">From</option><option value="jan">January</option><option value="feb">February</option><option value="mar">March</option><option value="apr">April</option><option value="may">May</option><option value="jun">June</option><option value="jul">July</option><option value="aug">August</option><option value="sep">September</option><option value="oct">October</option><option value="nov">November</option><option value="dec">December</option>
                </select>
                - <select class="attr seasonMonths" name="attrs[season][to]">
                    <option value="">To</option><option value="jan">January</option><option value="feb">February</option><option value="mar">March</option><option value="apr">April</option><option value="may">May</option><option value="jun">June</option><option value="jul">July</option><option value="aug">August</option><option value="sep">September</option><option value="oct">October</option><option value="nov">November</option><option value="dec">December</option>
                </select>
            </span>

            <span class="tpl-details-button">details</span>

        </div>

        <div class="tpl-details" name="attrs[season][details]">
            <textarea class="tpl-details-small attr" placeholder="Provide any details..."></textarea>
        </div>

    </div>
</div>

<script type="text/javascript">

    $(function() {

        $('#season').selectButton({
            select: function(e, ui) {
                if (ui.item.value === 'seasonal') {
                    $('#seasonal').show();
                } else {
                    $('#seasonal').hide();
                }
            }
        });

        $('.seasonMonths').select();
    });

</script>