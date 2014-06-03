
<style type="text/css">
    table.openingTimes { border-collapse: collapse; font-family: Arial; font-size: 12px; }
    input.everydayTimes, .somedaysTimes { font-size: 12px; padding-left: 3px; width: 34px; border: solid 1px #aaa; }
</style>

<div class="par">

    <h1>
        Open
    </h1>

    <div class="hasDetail">

        <select id="opening" class="attr" name="attrs[opening][value]">
            <option value="na">Don't know</option>
            <option value="everyday">Every day</option>
            <option value="somedays">Some days</option>
        </select>

        <a class="detailsButton" href="">details</a>

    </div>
    
    <div class="details" name="attrs[opening][details]" style="padding-top: 8px; display: none;">
        <textarea class="attr detailsText" placeholder="Provide any details..."></textarea>
    </div>

    <div id="openingEverydayForm" style="display: none; margin-top: 10px; border-top: solid 1px #d0d1d2; padding-bottom: 5px;">
        <div style="border-top: solid 1px #fff; padding-top: 10px;"></div>

        <h2>Specify times</h2>

        <select id="everyday" class="attr" name="attrs[opening][everyday][value]">
            <option value="na">Not sure</option>
            <option value="24h">24h</option>
            <option value="attimes">Fixed times</option>
        </select>

        <span id="everydayAttimes" style="display: none; margin-left: 10px;">
            <input class="attr everydayTimes inputSmall" name="attrs[opening][everyday][from]" placeholder="From" />
            - <input class="attr everydayTimes inputSmall" name="attrs[opening][everyday][to]" placeholder="To" />
        </span>

    </div>

    <div id="openingSomedaysForm" style="display: none; margin-top: 10px; border-top: solid 1px #d0d1d2; padding-bottom: 5px;">
        <div style="border-top: solid 1px #fff; padding-top: 10px;"></div>

        <table class="openingTimes">
            <? foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] AS $day): ?>
                <tr>
                    <td style="text-align: right; padding: 0 10px;"><?= ucfirst($day) ?></td>
                    <td><select class="attr somedays" name="attrs[opening][somedays][<?= $day ?>][value]"><option value="na">Not sure</option><option value="closed">Closed</option><option value="24h">24h</option><option value="attimes">Fixed times</option></select></td>
                    <td style="padding-left: 10px; display: none;">
                        <input class="attr somedaysTimes inputSmall" name="attrs[opening][somedays][<?= $day ?>][from]" placeholder="From" />
                        - <input class="attr somedaysTimes inputSmall" name="attrs[opening][somedays][<?= $day ?>][to]" placeholder="To" />
                    </td>
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
                    $('#everydayAttimes').hide();
                } else {
                    $('#everydayAttimes').show();
                }
            }
        });

        $('.somedays').multiButton({
            select: function(e, ui) {
                if (ui.item.value !== 'attimes') {
                    $(this).closest('td').next().hide();
                } else {
                    $(this).closest('td').next().show();
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
//            function n(name) {
//                name = name.replace(/\]/g, '\\]');
//                name = name.replace(/\[/g, '\\[');
//                var selector = "[name=" + name + "]";
//                return $(selector);
//            }
//            var days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
//            for (var i = 0; i < days.length; i++) {
//                var day = days[i];
//                if (n('attrs[opening][somedays][' + day + '][value]').val() === 'attimes'
//                        && (n('attrs[opening][somedays][' + day + '][from]').val() === '' || n('attrs[opening][somedays][' + day + '][to]').val() === '')) {
//                    //console.log('error');
//                    return false;
//                }
//            }
            return true;
        });
    });

</script>