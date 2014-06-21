
<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Contact</h1>

        <table class="tpl-table" id="contactTable">

            <? foreach ((array) a() AS $i => $value): ?>
                <tr>
                    <td class="tpl-table-item-label">
                        <select class="tpl-select contactType attr" name="attrs[contact][type][]">
                            <option value="tel" <?= a('type')[$i] == 'tel' ? 'selected' : '' ?>>Tel</option>
                            <option value="fax" <?= a('type')[$i] == 'fax' ? 'selected' : '' ?>>Fax</option>
                            <option value="mob" <?= a('type')[$i] == 'mob' ? 'selected' : '' ?>>Mob</option>
                            <option value="email" <?= a('type')[$i] == 'email' ? 'selected' : '' ?>>Email</option>
                            <option value="www" <?= a('type')[$i] == 'www' ? 'selected' : '' ?>>www</option>
                            <option value="vhf" <?= a('type')[$i] == 'vhf' ? 'selected' : '' ?>>VHF</option>
                        </select>
                    </td>
                    <td>
                        <input class="tpl-text-small contactValue attr" type="text" name="attrs[contact][val][]" value="<?= $value ?>" />
                    </td>
                    <td>
                        <span class="tpl-delete-button"></span>
                    </td>
                </tr>
            <? endforeach; ?>

            <tr>
                <td class="tpl-table-item-label">
                    <select class="tpl-select contactType" name="attrs[contact][type][]">
                        <option value="tel">Tel</option>
                        <option value="fax">Fax</option>
                        <option value="mob">Mob</option>
                        <option value="email">Email</option>
                        <option value="www">www</option>
                        <option value="vhf">VHF</option>
                    </select>
                </td>
                <td>
                    <input class="tpl-text-small contactValue" type="text" name="attrs[contact][val][]" />
                </td>
                <td>
                    <span class="ps-ui-delete-button" style="display: none;"></span>
                </td>
            </tr>

        </table>

    </div>
</div>

<script type="text/javascript">

    $(function() {

        $('#contactTable').on('keyup', '.contactValue:last', function() {

            if ($(this).val() !== '') {
                
                // Get the row of the input and clone into new contact row
                var lastContactRow = $('#contactTable tr:last');
                var newContactRow = lastContactRow.clone(true);
                
                // Reset new row's inputs and reinitialise its UI
                newContactRow.find('.contactValue').val('');
                newContactRow.find('.contactType option:selected').prop('selected', false);
                newContactRow.find('.contactType').select();
                
                // Insert new contact row and add it to POST
                $('#contactTable tr:last').after(newContactRow);
                lastContactRow.find('.contactValue').addClass('attr');
                lastContactRow.find('.contactType').addClass('attr');
                lastContactRow.find('.ps-ui-delete-button').css('display', 'inline-block');
            }
        });

        $('#contactTable').on('keyup', '.contactValue', function() {

            var contactRow = $(this).closest('tr');

            if ($(this).val() === '') {
                
                // Hide from POST
                contactRow.find('.contactValue').removeClass('attr');
                contactRow.find('.contactType').removeClass('attr');
                
                // Remove next contact row if it's the last row
                if (contactRow.next().is('#contactTable tr:last')) {
                    contactRow.find('.tpl-delete-button').hide();
                    contactRow.next().remove();
                }
            } else {
                contactRow.find('.contactValue').addClass('attr');
                contactRow.find('.contactType').addClass('attr');
                contactRow.find('.ps-ui-delete-button').css('display', 'inline-block');
            }
        });
        
        $('#contactTable').on('click', '.ps-ui-delete-button', function() {
            $(this).closest('tr').remove();
        });

        validator.add(function() {
            return true;
        });
    });

</script>