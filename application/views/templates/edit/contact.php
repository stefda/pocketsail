
<div class="par">

    <h1>Contact</h1>

    <table>
        <? for ($i = 0; $i < count(@$attrs->contact->type); $i++): ?>
            <tr>
                <td style="width: 55px; text-align: right;">
                    <select class="attr contactType" name="attrs[contact][types][]">
                        <option value="tel"<?= @$attrs->contact->types[$i] === 'tel' ? ' selected' : '' ?>>Tel</option>
                        <option value="mob"<?= @$attrs->contact->types[$i] === 'mob' ? ' selected' : '' ?>>Mob</option>
                        <option value="email"<?= @$attrs->contact->types[$i] === 'email' ? ' selected' : '' ?>>Email</option>
                        <option value="www"<?= @$attrs->contact->types[$i] === 'www' ? ' selected' : '' ?>>www</option>
                        <option value="vhf"<?= @$attrs->contact->types[$i] === 'vhf' ? ' selected' : '' ?>>VHF</option>
                    </select>
                </td>
                <td>
                    <input class="attr inputSmall" type="text" name="attrs[contact][values][]" value="<?= @$attrs->contact->values[$i] ?>" style="width: 200px;" />
                </td>
            </tr>
        <? endfor; ?>
        <tr>
            <td style="width: 55px; text-align: right;">
                <select class="attr contactType" name="attrs[contact][types][]">
                    <option value="tel"<?= @$attrs->contact->types[$i] === 'tel' ? ' selected' : '' ?>>Tel</option>
                    <option value="mob"<?= @$attrs->contact->types[$i] === 'mob' ? ' selected' : '' ?>>Mob</option>
                    <option value="email"<?= @$attrs->contact->types[$i] === 'email' ? ' selected' : '' ?>>Email</option>
                    <option value="www"<?= @$attrs->contact->types[$i] === 'www' ? ' selected' : '' ?>>www</option>
                    <option value="vhf"<?= @$attrs->contact->types[$i] === 'vhf' ? ' selected' : '' ?>>VHF</option>
                </select>
            </td>
            <td>
                <input class="attr inputSmall" type="text" name="attrs[contact][values][]" value="<?= @$attrs->contact->values[$i] ?>" style="width: 200px;" />
            </td>
        </tr>
    </table>

</div>

<script type="text/javascript">

    $(function() {
        $('.contactType').select();
    });

</script>