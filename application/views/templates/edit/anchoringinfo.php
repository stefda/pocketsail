
<style type="text/css">
    #berthing .tpl-text-small { width: 30px; }
    #berthing .tpl-table-item-label { width: 80px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Anchoring</h1>

        <table id="berthing" class="tpl-table" style="width: 100%;">

            <!-- Anchoring -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Anchoring in
                </td>
                <td class="tpl-table-item-value">
                    <input type="text" class="attr tpl-text-small" name="attrs[anchoring][depth][from]" value="<?= @$attrs->anchoring->depth->from ?>" />
                    -
                    <input type="text" class="attr tpl-text-small" name="attrs[anchoring][depth][to]" value="<?= @$attrs->anchoring->depth->to ?>" />
                    <span class="tpl-note">meters</span>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->anchoring->depth->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->anchoring->depth->details === null ? '' : ' attr-include' ?>"
                              name="attrs[anchoring][depth][details]"
                              placeholder="Provide any details..."><?= @$attrs->anchoring->depth->details ?></textarea>
                </td>
            </tr>

            <!-- Holding -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Holding in
                </td>
                <td class="tpl-table-item-value">
                    <select multiple class="tpl-select-button attr" name="attrs[anchoring][holding][values][]">
                        <option value="sand"<?= @$attrs->anchoring->holding->values !== null && in_array('sand', @$attrs->anchoring->holding->values) ? ' selected' : '' ?>>Sand</option>
                        <option value="mud"<?= @$attrs->anchoring->holding->values !== null && in_array('mud', @$attrs->anchoring->holding->values) ? ' selected' : '' ?>>Mud</option>
                        <option value="gravel"<?= @$attrs->anchoring->holding->values !== null && in_array('gravel', @$attrs->anchoring->holding->values) ? ' selected' : '' ?>>Gravel</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->anchoring->holding->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->anchoring->holding->details === null ? '' : ' attr-include' ?>"
                              name="attrs[anchoring][holding][details]"
                              placeholder="Provide any details..."><?= @$attrs->anchoring->holding->details ?></textarea>
                </td>
            </tr>

            <!-- Price -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Price
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" type="text" name="attrs[anchoring][price][value]" value="<?= @$attrs->anchoring->price->value ?>" />
                    <select class="tpl-select attr" name="attrs[berthing][price][currency]">
                        <option value="gbp"<?= @$attrs->anchoring->price->currency === 'gbp' ? ' selected' : '' ?>>£</option>
                        <option value="eur"<?= @$attrs->anchoring->price->currency === 'eur' ? ' selected' : '' ?>>€</option>
                        <option value="usd"<?= @$attrs->anchoring->price->currency === 'usd' ? ' selected' : '' ?>>$</option>
                    </select>
                    <select class="tpl-select attr" name="attrs[anchoring][price][type]">
                        <option value="m">per meter</option><option value="ft">per foot</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->anchoring->price->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->anchoring->price->details === null ? '' : ' attr-include' ?>" name="attrs[anchoring][price][details]" placeholder="Provide any details..."><?= @$attrs->anchoring->price->details ?></textarea>
                </td>
            </tr>

            <!-- Soujourn tax -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Soujourn tax
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" type="text" name="attrs[berthing][soujourn][value]" value="<?= @$attrs->berthing->soujourn->value ?>" />
                    <select class="tpl-select attr" name="attrs[berthing][soujourn][currency]">
                        <option value="gbp"<?= @$attrs->berthing->soujourn->currency === 'gbp' ? ' selected' : '' ?>>£</option>
                        <option value="eur"<?= @$attrs->berthing->soujourn->currency === 'eur' ? ' selected' : '' ?>>€</option>
                        <option value="usd"<?= @$attrs->berthing->soujourn->currency === 'usd' ? ' selected' : '' ?>>$</option>
                    </select>
                    <select class="tpl-select attr" name="attrs[berthing][soujourn][type]">
                        <option value="person">per person</option><option value="boat">per boat</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->berthing->soujourn->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->berthing->soujourn->details === null ? '' : ' attr-include' ?>"
                              name="attrs[berthing][soujourn][details]"
                              placeholder="Provide any details..."><?= @$attrs->berthing->soujourn->details ?></textarea>
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