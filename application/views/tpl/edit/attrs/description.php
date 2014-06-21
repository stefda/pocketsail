
<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Description</h1>

        <textarea class="tpl-textarea-large attr" name="attrs[description][val]"><?= a() ?></textarea>

    </div>
</div>

<script type="text/javascript">

    $(function() {
        validator.add(function() {
            return true;
        });
    });

</script>