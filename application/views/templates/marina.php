
<div>

    <? if ($type === 'summary'): ?>

        <!-- SUMMARY -->

        <div id="content">

            <div class="info-block">

                <div style="width: 100%; height: 225px; background-position-y: -45px; background-image: url('/application/images/kornati.jpg'); border-radius: 2px 2px 0 0;"></div>
                <div class="inner-wrapper">
                    <div class="icon <?= $info['sub'] ?>"></div>
                    <div style="margin-left: 79px;">

                        <div style="font-size: 28px; margin-top: 5px;"><?= $info['name'] ?></div>
                        <div style="font-size: 12px; margin-top: 4px; font-weight: bold;">
                            <? if (@$info['near']): ?>
                                near <?= @$info['near'] ?>,
                            <? endif; ?>
                            <?= @$info['country'] ?>
                        </div>
                        <img src="/application/images/rating.png" style="margin-top: 3px;" />

                    </div>
                </div>

            </div>

            <div class="info-block">
                <div class="inner-wrapper">
                    <div class="content-wrapper">
                        Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril deleni.
                        <div style="font-size: 40px; color: #999; padding-top: 20px;">
                            SUMMARY
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <? if (@$info['contact']): ?>
            <div id="contact">
                <?= t('contact', $info['contact'], $type); ?>
            </div>
        <? endif; ?>

        <!-- SUMMARY -->

    <? endif; ?>


    <? if ($type === 'fullview'): ?>

        <!-- FULLVIEW -->

        <div id="content">

            <div class="info-block">

                <div style="width: 100%; height: 225px; background-position-y: -45px; background-image: url('/application/images/kornati.jpg'); border-radius: 2px 2px 0 0;"></div>
                <div class="inner-wrapper">
                    <div class="icon <?= $info['sub'] ?>"></div>
                    <div style="margin-left: 79px;">

                        <div style="font-size: 28px; margin-top: 5px;"><?= $info['name'] ?></div>
                        <div style="font-size: 12px; margin-top: 4px; font-weight: bold;">
                            <? if (@$info['near']): ?>
                                near <?= @$info['near'] ?>,
                            <? endif; ?>
                            <?= @$info['country'] ?>
                        </div>
                        <img src="/application/images/rating.png" style="margin-top: 3px;" />

                    </div>
                </div>

            </div>

            <? if (@$info['description']): ?>
                <?= t('description', $info['description'], $type) ?>
            <? endif; ?>

            <? if (@$info['navigation']): ?>
                <?= t('navigation', $info['navigation'], $type) ?>
            <? endif; ?>

            <? if (@$info['berthing']): ?>
                <?= t('berthing', $info['berthing'], $type) ?>
            <? endif; ?>

        </div>

        <? if (@$info['contact']): ?>
            <div id="contact">
                <?= t('contact', $info['contact'], $type); ?>
            </div>
        <? endif; ?>

        <div id="menu">
            <div class="wrapper sub">
                <a href="#description">Description</a>
            </div>
            <div class="wrapper sub">
                <a href="#navigation">Navigation</a>
            </div>
            <div class="wrapper sub">
                <a href="#berthing">Berthing</a>
            </div>
            <div class="wrapper sub">
                <span class="digit-background">
                    23
                </span>
                <a href="#comments">Comments</a>
            </div>
            <div class="wrapper sub">
                <span class="digit-background">
                    8
                </span>
                <a href="#questions">Questions</a>
            </div>
        </div>

        <!-- /FULLVIEW -->

    <? endif; ?>


    <? if ($type === 'edit'): ?>

        <!-- EDIT -->

        <div id="content">

            <div class="info-block">

                <div style="width: 100%; height: 225px; background-position-y: -45px; background-image: url('/application/images/kornati.jpg'); border-radius: 2px 2px 0 0;"></div>
                <div class="inner-wrapper">
                    <div class="icon <?= $info['sub'] ?>"></div>
                    <div style="margin-left: 79px;">

                        <div style="font-size: 28px; margin-top: 5px;"><?= $info['name'] ?></div>
                        <div style="font-size: 12px; margin-top: 4px; font-weight: bold;">
                            <? if (@$info['near']): ?>
                                near <?= @$info['near'] ?>,
                            <? endif; ?>
                            <?= @$info['country'] ?>
                        </div>
                        <img src="/application/images/rating.png" style="margin-top: 3px;" />

                    </div>
                </div>
            </div>

            <?= t('description', @$info['description'], $type) ?>
            <?= t('navigation', @$info['navigation'], $type) ?>
            <?= t('berthing', @$info['berthing'], $type) ?>

        </div>

        <div id="contact">
            <?= t('contact', @$info['contact'], $type); ?>
        </div>

        <!-- /EDIT -->

    <? endif; ?>

</div>