
<!-- FULLVIEW/SUMMARY -->
<? if ($type === 'fullview' || $type === 'summary'): ?>

    <div class="info-block" style="width: 100%; margin-top: 10px;">
        <div class="inner-wrapper">
            <h1>Contact</h1>
            <table class="details-list">
                <? foreach ($info AS $contact): ?>
                    <tr>
                        <td class="label"><?= $contact['type'] ?></td>
                        <td>
                            <? if ($contact['type'] === 'www'): ?> 
                                <a href="<?= full_url($contact['value']) ?>" target="_blank"><?= short_url($contact['value']) ?></a>
                            <? else: ?>
                                <?= $contact['value'] ?>
                            <? endif; ?>
                        </td>
                    </tr>
                <? endforeach; ?>
            </table>
        </div>
    </div>

<? endif; ?>
<!-- /FULLVIEW/SUMMARY -->

<!-- EDIT-->
<? if ($type === 'edit'): ?>

    <div class="info-block" style="width: 100%; margin-top: 10px;">
        <div class="inner-wrapper">
            <h1>Contact</h1>
            <table class="details-list">
                <tr>
                    <td class="label">tel</td>
                    <td>
                        placeholder
                    </td>
                </tr>
            </table>
        </div>
    </div>

<? endif; ?>
<!-- /EDIT -->