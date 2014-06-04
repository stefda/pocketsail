
<style>
    table#contactTable { border-collapse: collapse; }
    table#contactTable td { padding-bottom: 3px; }
    table#contactTable td.valueCell { padding-left: 3px; }
</style>

<div class="par">

    <h1>Contact</h1>

    <table id="contactTable" style="border-collapse: collapse;">
        <? for ($i = 0; $i < count(@$attrs->contact->type); $i++): ?>
            <tr>
                <td style="width: 55px; text-align: right;">
                    <select class="attr contactType contactTypeInput" name="attrs[contact][types][]">
                        <option value="tel"<?= @$attrs->contact->types[$i] === 'tel' ? ' selected' : '' ?>>Tel</option>
                        <option value="mob"<?= @$attrs->contact->types[$i] === 'mob' ? ' selected' : '' ?>>Mob</option>
                        <option value="email"<?= @$attrs->contact->types[$i] === 'email' ? ' selected' : '' ?>>Email</option>
                        <option value="www"<?= @$attrs->contact->types[$i] === 'www' ? ' selected' : '' ?>>www</option>
                        <option value="vhf"<?= @$attrs->contact->types[$i] === 'vhf' ? ' selected' : '' ?>>VHF</option>
                    </select>
                </td>
                <td class="valueCell">
                    <input class="attr inputSmall contactValueInput" type="text" name="attrs[contact][values][]" value="<?= @$attrs->contact->values[$i] ?>" style="width: 200px;" />
                </td>
            </tr>
        <? endfor; ?>
        <tr>
            <td style="width: 55px; text-align: right;">
                <select class="contactType contactTypeInput" name="attrs[contact][types][]">
                    <option value="tel"<?= @$attrs->contact->types[$i] === 'tel' ? ' selected' : '' ?>>Tel</option>
                    <option value="mob"<?= @$attrs->contact->types[$i] === 'mob' ? ' selected' : '' ?>>Mob</option>
                    <option value="email"<?= @$attrs->contact->types[$i] === 'email' ? ' selected' : '' ?>>Email</option>
                    <option value="www"<?= @$attrs->contact->types[$i] === 'www' ? ' selected' : '' ?>>www</option>
                    <option value="vhf"<?= @$attrs->contact->types[$i] === 'vhf' ? ' selected' : '' ?>>VHF</option>
                </select>
            </td>
            <td class="valueCell">
                <input class="inputSmall contactValueInput" type="text" name="attrs[contact][values][]" value="<?= @$attrs->contact->values[$i] ?>" style="width: 200px;" />
            </td>
        </tr>
    </table>

</div>

<div id="contactTemplate" style="display: none;">
    <table>
        <tr>
            <td style="width: 55px; text-align: right;">
                <select class="contactType contactTypeInput" name="attrs[contact][types][]">
                    <option value="tel" selected>Tel</option>
                    <option value="mob">Mob</option>
                    <option value="email">Email</option>
                    <option value="www">www</option>
                    <option value="vhf">VHF</option>
                </select>
            </td>
            <td class="valueCell">
                <input class="inputSmall contactValueInput" type="text" name="attrs[contact][values][]" value="<?= @$attrs->contact->values[$i] ?>" style="width: 200px;" />
            </td>
        </tr>
    </table>
</div>

<script type="text/javascript">

    $(function() {
        $('.contactType:visible').select();

        $('#contactTable').on('keyup', '.contactValueInput:last', function() {
            var value = $(this).val();
            if (value !== "") {
                var newRow = $('#contactTemplate tbody').html();
                $('#contactTable tr:last').after($(newRow));
                $('#contactTable .contactType:last').select();
            }
        });

        $('#contactTable').on('keyup', '.contactValueInput', function() {
            if ($(this).val() === "") {
                $(this).removeClass('attr');
                $(this).closest('tr').find('.contactTypeInput').removeClass('attr');
                if ($(this).closest('tr').next().is('#contactTable tr:last')) {
                    $(this).closest('tr').next().remove();
                }
            } else {
                $(this).addClass('attr');
                $(this).closest('tr').find('.contactTypeInput').addClass('attr');
            }
        });

//        function n(name) {
//            name = name.replace(/\]/g, '\\]');
//            name = name.replace(/\[/g, '\\[');
//            var selector = "[name=" + name + "]:visible";
//            return $(selector);
//        }

        validator.add(function() {

//            n('attrs[contact][values][]').each(function() {
//                console.log($(this).val());
//            });
        });
    });

</script>