
<style type="text/css">
    #berthing .tpl-text-small { width: 30px; }
    #berthing .tpl-table-item-label { width: 100px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Mooring</h1>

        <table id="berthing" class="tpl-table" style="width: 100%;">

            <!-- Number of buoys -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Number of buoys
                </td>
                <td class="tpl-table-item-value">
                    <input type="text" class="attr tpl-text-small" name="attrs[mooring][number][value]" value="<?= @$attrs->mooring->number->value ?>" />
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->mooring->number->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->mooring->number->details === null ? '' : ' attr-include' ?>"
                              name="attrs[mooring][number][details]"
                              placeholder="Provide any details..."><?= @$attrs->mooring->number->details ?></textarea>
                </td>
            </tr>

            <!-- Max draught -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Max draught
                </td>
                <td class="tpl-table-item-value">
                    <input type="text" class="tpl-text-small attr" name="attrs[mooring][maxdraught][value]" value="<?= @$attrs->mooring->maxdraught->value ?>"/>
                    <select class="tpl-select attr" name="attrs[mooring][maxdraught][type]">
                        <option value="m"<?= @$attrs->mooring->maxdraught->type === 'm' ? ' selected' : '' ?>>meters</option>
                        <option value="ft"<?= @$attrs->mooring->maxdraught->type === 'ft' ? ' selected' : '' ?>>feet</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->mooring->maxdraught->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->mooring->maxdraught->details === null ? '' : ' attr-include' ?>"
                              name="attrs[mooring][maxdraught][details]"
                              placeholder="Provide any details..."><?= @$attrs->mooring->maxdraught->details ?></textarea>
                </td>
            </tr>

            <!-- Max length -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Max length
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" type="text" name="attrs[mooring][maxlength][value]" value="<?= @$attrs->mooring->maxlength->value ?>"/>
                    <select class="tpl-select attr" name="attrs[mooring][maxlength][type]">
                        <option value="m"<?= @$attrs->mooring->maxlength->type === 'm' ? ' selected' : '' ?>>meters</option>
                        <option value="ft"<?= @$attrs->mooring->maxlength->type === 'ft' ? ' selected' : '' ?>>feet</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->mooring->maxlength->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->mooring->maxlength->details === null ? '' : ' attr-include' ?>"
                              name="attrs[mooring][maxlength][details]"
                              placeholder="Provide any details..."><?= @$attrs->mooring->maxlength->details ?></textarea>
                </td>
            </tr>

            <!-- Price -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Price
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" type="text" name="attrs[mooring][price][value]" value="<?= @$attrs->mooring->price->value ?>" />
                    <select class="tpl-select attr" name="attrs[mooring][price][currency]">
                        <option value="gbp"<?= @$attrs->mooring->price->currency === 'gbp' ? ' selected' : '' ?>>£</option>
                        <option value="eur"<?= @$attrs->mooring->price->currency === 'eur' ? ' selected' : '' ?>>€</option>
                        <option value="usd"<?= @$attrs->mooring->price->currency === 'usd' ? ' selected' : '' ?>>$</option>
                    </select>
                    <select class="tpl-select attr" name="attrs[mooring][price][type]">
                        <option value="m">per meter</option><option value="ft">per foot</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->mooring->price->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->mooring->price->details === null ? '' : ' attr-include' ?>"
                              name="attrs[mooring][price][details]"
                              placeholder="Provide any details..."><?= @$attrs->mooring->price->details ?></textarea>
                </td>
            </tr>

            <!-- Soujourn tax -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Soujourn tax
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" type="text" name="attrs[mooring][soujourn][value]" value="<?= @$attrs->mooring->soujourn->value ?>" />
                    <select class="tpl-select attr" name="attrs[mooring][soujourn][currency]">
                        <option value="gbp"<?= @$attrs->mooring->soujourn->currency === 'gbp' ? ' selected' : '' ?>>£</option>
                        <option value="eur"<?= @$attrs->mooring->soujourn->currency === 'eur' ? ' selected' : '' ?>>€</option>
                        <option value="usd"<?= @$attrs->mooring->soujourn->currency === 'usd' ? ' selected' : '' ?>>$</option>
                    </select>
                    <select class="tpl-select attr" name="attrs[mooring][soujourn][type]">
                        <option value="person">per person</option><option value="boat">per boat</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->mooring->soujourn->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->mooring->soujourn->details === null ? '' : ' attr-include' ?>"
                              name="attrs[mooring][soujourn][details]"
                              placeholder="Provide any details..."><?= @$attrs->mooring->soujourn->details ?></textarea>
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