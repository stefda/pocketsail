
<style>
    .cardContent .description { line-height: 1.3em; }
    .cardContent a { color: #4089fd; text-decoration: none; }
    .cardContent a:hover { text-decoration: underline; }
</style>

<div class="cardContent" style="padding: 20px;">
    <div>
        <?= include_card_template($poi->cat(), $poi->sub()) ?>
    </div>
</div>
