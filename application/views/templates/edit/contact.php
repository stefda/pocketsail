
<style type="text/css">
    #contactTable { width: 100%; }
    #contactTable .tpl-table-item-label { width: 55px; padding-right: 3px; }
    #contactTable .tpl-table-item-delete-button { width: 10px; }
    #contactTable .tpl-delete-button { display: none; }
    #contactTable .contactValueInput { box-sizing: border-box; width: 99%; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Contact</h1>

        <table id="contactTable" class="tpl-table" id="contactTable">
            <? for ($i = 0; $i < count(@$attrs->contact->types); $i++): ?>
                <tr>
                    <td class="tpl-table-item-label">
                        <select class="tpl-select contactTypeInput attr" name="attrs[contact][types][]">
                            <option value="tel"<?= @$attrs->contact->types[$i] === 'tel' ? ' selected' : '' ?>>Tel</option>
                            <option value="tel"<?= @$attrs->contact->types[$i] === 'fax' ? ' selected' : '' ?>>Fax</option>
                            <option value="mob"<?= @$attrs->contact->types[$i] === 'mob' ? ' selected' : '' ?>>Mob</option>
                            <option value="email"<?= @$attrs->contact->types[$i] === 'email' ? ' selected' : '' ?>>Email</option>
                            <option value="www"<?= @$attrs->contact->types[$i] === 'www' ? ' selected' : '' ?>>www</option>
                            <option value="vhf"<?= @$attrs->contact->types[$i] === 'vhf' ? ' selected' : '' ?>>VHF</option>
                        </select>
                    </td>
                    <td  class="tpl-table-item-value">
                        <input class="tpl-text-small contactValueInput attr" type="text" name="attrs[contact][values][]" value="<?= @$attrs->contact->values[$i] ?>" />
                    </td>
                    <td class="tpl-table-item-delete-button">
                        <span class="tpl-delete-button" style="display: inherit;"></span>
                    </td>
                </tr>
            <? endfor; ?>
            <tr>
                <td class="tpl-table-item-label">
                    <select class="tpl-select contactTypeInput" name="attrs[contact][types][]">
                        <option value="tel">Tel</option>
                        <option value="fax">Fax</option>
                        <option value="mob">Mob</option>
                        <option value="email">Email</option>
                        <option value="www">www</option>
                        <option value="vhf">VHF</option>
                    </select>
                </td>
                <td  class="tpl-table-item-value">
                    <input class="tpl-text-small contactValueInput" type="text" name="attrs[contact][values][]" value="" />
                </td>
                <td class="tpl-table-item-delete-button">
                    <span class="tpl-delete-button"></span>
                </td>
            </tr>
        </table>

    </div>
</div>

<div id="contactTemplate" style="display: none;">
    <table>
        <tr>
            <td class="tpl-table-item-label">
                <select class="contactTypeInput" name="attrs[contact][types][]">
                    <option value="tel" selected>Tel</option>
                    <option value="fax">Fax</option>
                    <option value="mob">Mob</option>
                    <option value="email">Email</option>
                    <option value="www">www</option>
                    <option value="vhf">VHF</option>
                </select>
            </td>
            <td  class="tpl-table-item-value">
                <input class="tpl-text-small contactValueInput" type="text" name="attrs[contact][values][]" value="<?= @$attrs->contact->values[$i] ?>" />
            </td>
            <td class="tpl-table-item-delete-button">
                <span class="tpl-delete-button"></span>
            </td>
        </tr>
    </table>
</div>

<script type="text/javascript">

    $(function() {

        $('#contactTable').on('keyup', '.contactValueInput:last', function() {
            var value = $(this).val();
            if (value !== '') {
                var newRow = $('#contactTemplate tr').clone(true);
                $('#contactTable tr:last').after(newRow);
                newRow.find('.contactTypeInput').select();
            }
        });

        $('#contactTable').on('keyup', '.contactValueInput', function() {
            if ($(this).val() === '') {
                $(this).removeClass('attr');
                $(this).closest('tr').find('.contactTypeInput').removeClass('attr');
                if ($(this).closest('tr').next().is('#contactTable tr:last')) {
                    $(this).closest('tr').find('.tpl-delete-button').hide();
                    $(this).closest('tr').next().remove();
                }
            } else {
                $(this).addClass('attr');
                $(this).closest('tr').find('.tpl-delete-button').css('display', 'inline-block');
                $(this).closest('tr').find('.contactTypeInput').addClass('attr');
            }
        });

        validator.add(function() {
            return true;
        });
    });

</script>