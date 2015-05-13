
<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Cash Withdrawals</h1>

        <table class="tpl-table" style="width: 100%;">
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[withdrawals][value]">
                        <option value="na"<?= @$attrs->withdrawals->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="free"<?= @$attrs->withdrawals->value === 'free' ? ' selected' : '' ?>>Free</option>
                        <option value="paid"<?= @$attrs->withdrawals->value === 'paid' ? ' selected' : '' ?>>Paid</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->withdrawals->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea
                        class="tpl-details-small <?= @$attrs->withdrawals->details === null ? '' : ' attr-include' ?>"
                        name="attrs[withdrawals][details]" placeholder="Provide any details..."
                        ><?= @$attrs->withdrawals->details ?></textarea>
                </td>
            </tr>
        </table>
    </div>

</div>
</div>