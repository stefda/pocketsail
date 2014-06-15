
<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Description</h1>

        <textarea class="tpl-details-large attr" name="attrs[description][details]"><?= @$attrs->description->details ?></textarea>

    </div>
</div>

<script type="text/javascript">

    $(function() {

        $(function() {
            validator.add(function() {
                return true;
            });
        });
    });

</script>