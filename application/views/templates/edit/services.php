
<style>
    #services .tpl-table-item-label { width: 100px; }
    #maxtonnage .tpl-text-small { width: 30px; }
    #maxtonnage h2 { margin-left: 10px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Services</h1>

        <table class="tpl-table" id="services" style="width: 100%;">

            <!-- SLIPWAY -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Slipway</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[services][slipway][value]">
                        <option value="na"<?= @$attrs->services->slipway->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->services->slipway->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->services->slipway->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <!-- DETAILS BUTTON -->
                    <span class="tpl-details-button<?= @$attrs->services->slipway->details === null ? '' : ' tpl-visible' ?>">
                        details
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small<?= @$attrs->services->slipway->details === null ? '' : ' attr-include' ?>"
                              name="attrs[services][slipway][details]"
                              placeholder="Provide any details..."><?= @$attrs->services->slipway->details ?></textarea>
                </td>
            </tr>
            <!-- /SLIPWAY -->

            
            <!-- PUMP OUT -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Pump out</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[services][pumpout][value]">
                        <option value="na"<?= @$attrs->services->pumpout->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->services->pumpout->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->services->pumpout->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <!-- DETAILS BUTTON -->
                    <span class="tpl-details-button<?= @$attrs->services->pumpout->details === null ? '' : ' tpl-visible' ?>">
                        details
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small<?= @$attrs->services->pumpout->details === null ? '' : ' attr-include' ?>"
                              name="attrs[services][pumpout][details]"
                              placeholder="Provide any details..."><?= @$attrs->services->pumpout->details ?></textarea>
                </td>
            </tr>
            <!-- /PUMP OUT -->

            
            <!-- REPAIRS -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Repairs</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[services][repairs][value]">
                        <option value="na"<?= @$attrs->services->repairs->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->services->repairs->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->services->repairs->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <!-- DETAILS BUTTON -->
                    <span class="tpl-details-button<?= @$attrs->services->repairs->details === null ? '' : ' tpl-visible' ?>">
                        details
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small<?= @$attrs->services->pumpout->details === null ? '' : ' attr-include' ?>"
                              name="attrs[services][repairs][details]"
                              placeholder="Provide any details..."><?= @$attrs->services->repairs->details ?></textarea>
                </td>
            </tr>
            <!-- /REPAIRS -->

            
            <!-- TRAVELIFT -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Travelift</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="attr" id="travelift" name="attrs[services][travelift][value]">
                        <option value="na"<?= @$attrs->services->travelift->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->services->travelift->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->services->travelift->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span id="maxtonnage" style="<?= @$attrs->services->travelift->value === 'yes' ? '' : 'display: none;' ?>">
                        <h2>Max tonnage</h2> <input class="tpl-text-small attr" name="attrs[services][travelift][maxtonnage]" type="text" value="<?= @$attrs->services->travelift->maxtonnage ?>" />
                        <span class="tpl-note">(tonnes)</span>
                    </span>
                    <!-- DETAILS BUTTON -->
                    <span class="tpl-details-button<?= @$attrs->services->travelift->details === null ? '' : ' tpl-visible' ?>">
                        details
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small<?= @$attrs->services->travelift->details === null ? '' : ' attr-include' ?>"
                              name="attrs[services][travelift][details]"
                              placeholder="Provide any details..."><?= @$attrs->services->travelift->details ?></textarea>
                </td>
            </tr>
            <!-- /TRAVELIFT -->

            
            <!-- STORAGE -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Storage</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[services][storage][value]">
                        <option value="na"<?= @$attrs->services->storage->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->services->storage->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->services->storage->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <!-- DETAILS BUTTON -->
                    <span class="tpl-details-button<?= @$attrs->services->storage->details === null ? '' : ' tpl-visible' ?>">
                        details
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small<?= @$attrs->services->storage->details === null ? '' : ' attr-include' ?>"
                              name="attrs[services][storage][details]"
                              placeholder="Provide any details..."><?= @$attrs->services->storage->details ?></textarea>
                </td>
            </tr>
            <!-- /STORAGE -->

            
            <!-- DIVERS -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Divers</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[services][divers][value]">
                        <option value="na"<?= @$attrs->services->divers->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->services->divers->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->services->divers->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <!-- DETAILS BUTTON -->
                    <span class="tpl-details-button<?= @$attrs->services->divers->details === null ? '' : ' tpl-visible' ?>">
                        details
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small<?= @$attrs->services->divers->details === null ? '' : ' attr-include' ?>"
                              name="attrs[services][divers][details]"
                              placeholder="Provide any details..."><?= @$attrs->services->divers->details ?></textarea>
                </td>
            </tr>
            <!-- /DIVERS -->

        </table>

    </div>
</div>

<script type="text/javascript">

    $(function() {

        $('#travelift').selectButton({
            select: function(e, ui) {
                if (ui.item.value === 'yes') {
                    $('#maxtonnage').show();
                } else {
                    $('#maxtonnage').hide();
                }
            }
        });

        validator.add(function() {
            return true;
        });
    });

</script>

