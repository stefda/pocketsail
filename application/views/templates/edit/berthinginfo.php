
<style type="text/css">
    #berthing .tpl-text-small { width: 30px; }
    #berthing .tpl-table-item-label { width: 80px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Berthing</h1>

        <table id="berthing" class="tpl-table" style="width: 100%;">

            <!-- Assistance -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Assistance
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[berthing][assistance][value]">
                        <option value="na"<?= @$attrs->berthing->assistance->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->berthing->assistance->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->berthing->assistance->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->berthing->assistance->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->berthing->assistance->details === null ? '' : ' attr-include' ?>" name="attrs[berthing][assistance][details]" placeholder="Provide any details..."><?= @$attrs->berthing->assistance->details ?></textarea>
                </td>
            </tr>

            <!-- Type -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Type
                </td>
                <td class="tpl-table-item-value">
                    <select multiple class="tpl-select-button attr" name="attrs[berthing][type][values][]">
                        <option value="alongiside"<?= @$attrs->berthing->type->values !== null && in_array('alongiside', @$attrs->berthing->type->values) ? ' selected' : '' ?>>Alongside</option>
                        <option value="sternto"<?= @$attrs->berthing->type->values !== null && in_array('sternto', @$attrs->berthing->type->values) ? ' selected' : '' ?>>Stern-to</option>
                        <option value="bowto"<?= @$attrs->berthing->type->values !== null && in_array('bowto', @$attrs->berthing->type->values) ? ' selected' : '' ?>>Bow-to</option>
                        <option value="lazyline"<?= @$attrs->berthing->type->values !== null && in_array('lazyline', @$attrs->berthing->type->values) ? ' selected' : '' ?>>Lazyline</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->berthing->type->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->berthing->type->details === null ? '' : ' attr-include' ?>" name="attrs[berthing][type][details]" placeholder="Provide any details..."><?= @$attrs->berthing->type->details ?></textarea>
                </td>
            </tr>

            <!-- Sea berths -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Sea berths
                </td>
                <td class="tpl-table-item-value">
                    <input type="text" class="attr tpl-text-small" name="attrs[berthing][seaberths][total][value]" value="<?= @$attrs->berthing->seaberths->total->value ?>" />
                    /
                    <input type="text" class="attr tpl-text-small" name="attrs[berthing][seaberths][visitor][value]" value="<?= @$attrs->berthing->seaberths->visitor->value ?>" />
                    <span class="tpl-note">total / visitors</span>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->berthing->seaberths->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->berthing->seaberths->details === null ? '' : ' attr-include' ?>" name="attrs[berthing][seaberths][details]" placeholder="Provide any details..."><?= @$attrs->berthing->seaberths->details ?></textarea>
                </td>
            </tr>

            <!-- Dry berths -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Dry berths
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" type="text" name="attrs[berthing][dryberths][value]" value="<?= @$attrs->berthing->dryberths->value ?>" />
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->berthing->dryberths->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->berthing->dryberths->details === null ? '' : ' attr-include' ?>" name="attrs[berthing][dryberths][details]" placeholder="Provide any details..."><?= @$attrs->berthing->dryberths->details ?></textarea>
                </td>
            </tr>

            <!-- Max draught -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Max draught
                </td>
                <td class="tpl-table-item-value">
                    <input type="text" class="tpl-text-small attr" name="attrs[berthing][maxdraught][value]" value="<?= @$attrs->berthing->maxdraught->value ?>"/>
                    <select class="tpl-select attr" name="attrs[berthing][maxdraught][type]">
                        <option value="m"<?= @$attrs->berthing->maxdraught->type === 'm' ? ' selected' : '' ?>>meters</option>
                        <option value="ft"<?= @$attrs->berthing->maxdraught->type === 'ft' ? ' selected' : '' ?>>feet</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->berthing->maxdraught->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->berthing->maxdraught->details === null ? '' : ' attr-include' ?>" name="attrs[berthing][maxdraught][details]" placeholder="Provide any details..."><?= @$attrs->berthing->maxdraught->details ?></textarea>
                </td>
            </tr>
            <!-- /Max draught -->

            <!-- Max length -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Max length
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" type="text" name="attrs[berthing][maxlength][value]" value="<?= @$attrs->berthing->maxlength->value ?>"/>
                    <select class="tpl-select attr" name="attrs[berthing][maxlength][type]">
                        <option value="m"<?= @$attrs->berthing->maxlength->type === 'm' ? ' selected' : '' ?>>meters</option>
                        <option value="ft"<?= @$attrs->berthing->maxlength->type === 'ft' ? ' selected' : '' ?>>feet</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->berthing->maxlength->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->berthing->maxlength->details === null ? '' : ' attr-include' ?>" name="attrs[berthing][maxlength][details]" placeholder="Provide any details..."><?= @$attrs->berthing->maxlength->details ?></textarea>
                </td>
            </tr>

            <!-- Price -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Price
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" type="text" name="attrs[berthing][price][value]" value="<?= @$attrs->berthing->price->value ?>" />
                    <select class="tpl-select attr" name="attrs[berthing][price][currency]">
                        <option value="gbp"<?= @$attrs->berthing->price->currency === 'gbp' ? ' selected' : '' ?>>£</option>
                        <option value="eur"<?= @$attrs->berthing->price->currency === 'eur' ? ' selected' : '' ?>>€</option>
                        <option value="usd"<?= @$attrs->berthing->price->currency === 'usd' ? ' selected' : '' ?>>$</option>
                    </select>
                    <select class="tpl-select attr" name="attrs[berthing][price][type]">
                        <option value="m">per meter</option><option value="ft">per foot</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->berthing->price->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small <?= @$attrs->berthing->price->details === null ? '' : ' attr-include' ?>" name="attrs[berthing][price][details]" placeholder="Provide any details..."><?= @$attrs->berthing->price->details ?></textarea>
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
                    <textarea class="tpl-details-small ?= @$attrs->berthing->soujourn->details === null ? '' : ' attr-include' ?>" name="attrs[berthing][soujourn][details]" placeholder="Provide any details..."><?= @$attrs->berthing->soujourn->details ?></textarea>
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