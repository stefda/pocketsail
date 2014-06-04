
<style type="text/css">
    #everydayTimes .tpl-text-small, #somedaysTimes .tpl-text-small { font-size: 12px; padding-left: 3px; width: 34px; border: solid 1px #aaa; }
    #somedaysTimes .tpl-table-item-label { width: 37px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Open</h1>

        <div class="tpl-has-details-button">
            <select id="opening" class="attr" name="attrs[opening][value]">
                <option value="na">Don't know</option>
                <option value="everyday">Every day</option>
                <option value="somedays">Some days</option>
            </select>
            <span class="tpl-details-button">details</span>
        </div>

        <div class="tpl-details" name="attrs[opening][details]">
            <textarea class="tpl-details-small attr" placeholder="Provide any details..."></textarea>
        </div>

        <div class="tpl-subsection" id="everydayDetails">

            <h2>Specify times</h2>

            <select id="everyday" class="attr" name="attrs[opening][everyday][value]">
                <option value="na">Not sure</option>
                <option value="24h">24h</option>
                <option value="attimes">Fixed times</option>
            </select>

            <span id="everydayTimes" style="display: none; margin-left: 10px;">
                <input class="tpl-text-small attr" name="attrs[opening][everyday][from]" placeholder="From" />
                -
                <input class="tpl-text-small attr" name="attrs[opening][everyday][to]" placeholder="To" />
                <span class="tpl-note">(24h format)</span>
            </span>

        </div>

        <div class="tpl-subsection" id="somedaysDetails">

            <table class="tpl-table" id="somedaysTimes">
                <? foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] AS $day): ?>
                    <tr>
                        <td class="tpl-table-item-label"><?= ucfirst($day) ?></td>
                        <td class="tpl-table-item-value">
                            <select class="somedays attr" name="attrs[opening][somedays][<?= $day ?>][value]">
                                <option value="na">Not sure</option>
                                <option value="closed">Closed</option>
                                <option value="24h">24h</option>
                                <option value="attimes">Fixed times</option>
                            </select>
                        </td>
                        <td style="padding-left: 10px; display: none;">
                            <input class="tpl-text-small attr" name="attrs[opening][somedays][<?= $day ?>][from]" placeholder="From" />
                            -
                            <input class="tpl-text-small attr" name="attrs[opening][somedays][<?= $day ?>][to]" placeholder="To" />
                            <span class="tpl-note">(24h format)</span>
                        </td>
                    </tr>
                <? endforeach; ?>
            </table>

        </div>

    </div>
</div>

<script type="text/javascript">

    $(function() {

        $('#opening').selectButton({
            select: function(e, ui) {
                $('#everydayDetails, #somedaysDetails').hide();
                switch (ui.item.value) {
                    case 'everyday':
                        $('#everydayDetails').show();
                        break;
                    case 'somedays':
                        $('#somedaysDetails').show();
                        break;
                }
            }
        });

        $('#everyday').selectButton({
            select: function(e, ui) {
                if (ui.item.value !== 'attimes') {
                    $('#everydayTimes').hide();
                } else {
                    $('#everydayTimes').show();
                }
            }
        });

        $('.somedays').selectButton({
            select: function(e, ui) {
                if (ui.item.value !== 'attimes') {
                    $(this).closest('td').next().hide();
                } else {
                    $(this).closest('td').next().show();
                }
            }
        });

        validator.add(function() {
            return true;
        });
    });

</script>