
<style type="text/css">
    table.openingTimes { border-collapse: collapse; font-family: Arial; font-size: 12px; }
    input.everydayTimes, .somedaysTimes { font-size: 12px; padding-left: 3px; width: 34px; border: solid 1px #aaa; }
</style>

<div class="par">

    <h1>
        Opening Times
    </h1>

    <select id="opening" class="attr" name="attr[opening][value]">
        <option value="na">?</option>
        <option value="everyday">Every day</option>
        <option value="somedays">Some days</option>
    </select>

    <div id="openingEverydayForm" style="display: none;">

        <select id="everyday" class="attr" name="attr[opening][everyday][value]">
            <option value="na">?</option>
            <option value="24h">24h</option>
            <option value="attimes"></option>
        </select>

        <input class="attr everydayTimes" name="attr[opening][everyday][from]" placeholder="From" />
        - <input class="attr everydayTimes" name="attr[opening][everyday][to]" placeholder="To" />

    </div>

    <div id="openingSomedaysForm" style="display: none;">

        <table class="openingTimes">
            <? foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] AS $day): ?>
                <tr>
                    <td style="text-align: right; padding: 0 3px;"><?= ucfirst($day) ?></td>
                    <td><select class="attr somedays" name="attr[opening][somedays][<?= $day ?>][value]"><option value="na">?</option><option value="closed">Closed</option><option value="24h">24h</option><option value="attimes"></option></select></td>
                    <td style="padding-left: 5px;"><input class="attr somedaysTimes" name="attr[opening][somedays][<?= $day ?>][from]" placeholder="From" /></td>
                    <td style="padding: 0 3px 0 2px;">- <input class="attr somedaysTimes" name="attr[opening][somedays][<?= $day ?>][to]" placeholder="To" /></td>
                </tr>
            <? endforeach; ?>
        </table>

    </div>

</div>

<script type="text/javascript">

    $(function() {

        $('#opening').multiButton({
            select: function(e, ui) {
                $('#openingEverydayForm, #openingSomedaysForm').hide();
                if (ui.item.value === 'everyday') {
                    $('#openingEverydayForm').show();
                }
                if (ui.item.value === 'somedays') {
                    $('#openingSomedaysForm').show();
                }
            }
        });

        $('#everyday').multiButton({
            select: function(e, ui) {
                if (ui.item.value !== 'attimes') {
                    $(this).siblings('.everydayTimes').val('');
                }
            }
        });

        $('.somedays').multiButton({
            select: function(e, ui) {
                if (ui.item.value !== 'attimes') {
                    $(this).closest('tr').find('.somedaysTimes').val('');
                }
            }
        });

        $('.everydayTimes').change(function() {
            var select = $(this).siblings('select');
            $(select).multiButtonSelect('attimes');
        });

        $('.somedaysTimes').change(function() {
            var select = $(this).closest('tr').find('select');
            $(select).multiButtonSelect('attimes');
        });

        validator.add(function() {
            function n(name) {
                name = name.replace(/\]/g, '\\]');
                name = name.replace(/\[/g, '\\[');
                var selector = "[name=" + name + "]";
                return $(selector);
            }
            var days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
            for (var i = 0; i < days.length; i++) {
                var day = days[i];
                if (n('attr[opening][somedays][' + day + '][value]').val() === 'attimes'
                        && (n('attr[opening][somedays][' + day + '][from]').val() === '' || n('attr[opening][somedays][' + day + '][to]').val() === '')) {
                    //console.log('error');
                    return false;
                }
            }
            return true;
        });
    });

</script>