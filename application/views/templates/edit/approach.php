
<style type="text/css">
    #drying { margin-top: 10px; }
    #drying .tpl-table-item-label { width: 100px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Approach & Pilotage</h1>

        <textarea class="tpl-details-large attr" name="attrs[approach][details]"><?= @$attrs->approach->details ?></textarea>

        <table id="drying" class="tpl-table" style="width: 100%;">
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Approach drying
                </td>
                <td>
                    <select class="tpl-select-button attr" name="attrs[approach][drying][value]">
                        <option value="na"<?= @$attrs->approach->drying->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="no"<?= @$attrs->approach->drying->value === 'no' ? ' selected' : '' ?>>No</option>
                        <option value="yes"<?= @$attrs->approach->drying->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->approach->drying->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->approach->drying->details === null ? '' : ' attr-include' ?>" name="attrs[berthing][assistance][details]" placeholder="Provide any details..."><?= @$attrs->berthing->assistance->details ?></textarea>
                </td>
            </tr>
        </table>

    </div>
</div>

<script type="text/javascript">

    $(function() {
        validator.add(function() {
            return true;
        });
    });

</script>