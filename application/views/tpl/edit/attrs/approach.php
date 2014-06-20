
<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Approach</h1>

        <!-- Approach description -->
        <textarea class="tpl-textarea-large attr" name="attrs[approach][val]"><?= a() ?></textarea>

        <!-- Approach drying -->
        <table class="tpl-table">

            <tr class="tpl-has-details">
                <td class="tpl-table-item-label">
                    Approach drying
                </td>
                <td>
                    <select class="tpl-select-button attr" name="attrs[approach][drying][val]">
                        <option value="na" <?= a('drying') == 'na' ? 'selected' : '' ?>>?</option>
                        <option value="no" <?= a('drying') == 'no' ? 'selected' : '' ?>>No</option>
                        <option value="yes" <?= a('drying') == 'yes' ? 'selected' : '' ?>>Yes</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button <?= a('drying', 'details') ? 'tpl-visible' : '' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>

            <!-- Drying details -->
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-textarea-details" name="attrs[approach][details][val]"><?= a('details') ?></textarea>
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