
<!-- FULLVIEW -->
<? if ($type === 'fullview'): ?>

    <div class="info-block">
        <div class="inner-wrapper">
            <a id="berthing"></a>
            <h1>Berthing</h1>
            <div class="content-wrapper">
                <?= @$info['text'] ?>
                <? if ($info['details']): ?>
                    <table class="details-list">
                        <? if (@$info['details']['maxLength']): ?>
                            <tr>
                                <td class="label">Max.length</td>
                                <td><?= $info['details']['maxLength']['value']; ?> m</td>
                            </tr>
                        <? endif; ?>
                        <? if (@$info['details']['maxDraught']): ?>
                            <tr>
                                <td class="label">Max.draught</td>
                                <td><?= $info['details']['maxDraught']['value']; ?> m</td>
                            </tr>
                        <? endif; ?>
                        <? if (@$info['details']['water']): ?>
                            <tr>
                                <td class="label">Water</td>
                                <td><?= $info['details']['water']['value']; ?></td>
                            </tr>
                        <? endif; ?>
                    </table>
                <? endif; ?>
            </div>
        </div>
    </div>

<? endif; ?>
<!-- /FULLVIEW -->


<!-- EDIT -->
<? if ($type === 'edit'): ?>

    <div class="info-block">
        <div class="inner-wrapper">
            <a id="berthing"></a>
            <h1>Berthing</h1>
            <textarea placeholder="Describe berthing conditions..."><?= @$info['text'] ?></textarea>
        </div>
    </div>

<? endif; ?>
<!-- /EDIT -->
