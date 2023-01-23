<?php 

    $gallery = get_field('gallery');
    $gallery_limit = 1;

    $data = get_field('record_data');
    if ($data):
        $body = $data['body'];
        $formats = $data['format'];
        $physical_parameters = $data['physical_parameters'];
        $place_of_origin = $data['place_of_origin'];
        $dedication = $data['dedication'];

        $instruments_voices = $data['instruments_voices'];
        $documentation = $data['documentation'];

        $date = $data['date'];
        if ($date):
            $day = $date['day'];
            $month = $date['month'];
            $year = $date['year'];
        endif;

        $notes = $data['notes'];

    endif;

    $people = get_field('record_people');
    if (!empty($people)):
        $people_by_roles = orderPeopleByRoles($people);
    endif;

?>

<div class="container py-6">
    <div class="row gy-8">
        <div class="col-lg-6">
            <div class="position-relative">
                <h2><?= esc_html__('Etikety', 'nddb') ?></h2>
                <div class="record-gallery-wrapper">
                    <div class="record-gallery d-inline-flex">
                        <?php if (!empty($gallery)): ?>
                            <?php foreach ($gallery as $index => $image): ?>
                                <?php if ($index < $gallery_limit): ?>
                                <a class="gallery-image" href="<?= wp_get_attachment_url($image); ?>" data-fancybox="record-gallery" data-caption="<?= wp_get_attachment_caption($image) ?>">
                                    <?= wp_get_attachment_image($image, 'full'); ?>
                                </a>
                                <?php else: ?>
                                <a class="gallery-image d-none" href="<?= wp_get_attachment_url($image); ?>" data-fancybox="record-gallery" data-caption="<?= wp_get_attachment_caption($image) ?>">
                                    <?= wp_get_attachment_image($image, 'full'); ?>
                                </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="placeholder-image">
                                <?= get_template_part( 'templates/components/svg/icon', 'brand-vinyl' ) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($people_by_roles) && !empty($people_by_roles) || !empty($body)): ?>
            <div class="col-lg-6">
                <h2><?= esc_html__('Účinkující', 'nddb') ?></h2>
                <div class="record-roles row gy-3">
                    <?php if (!empty($people_by_roles)): ?>
                        <?php foreach ($people_by_roles as $role):
                            $title = $role['name'];
                            $description = getGlossaryTermDescription($title);                        
                            ?>
                            <span class="record-role col-lg-6">
                                <b class="record-role-title">
                                    <span class="d-inline-flex align-items-center" <?= getTooltipAttributes($description) ?>><?= $title ?></span>
                                </b>
                                <span class="record-role-people" id="<?php echo mb_strtolower($title); ?>_val"><?php
                                    $i = 0; // Formatting trashed due to random spaces appearing where they should not
                                    foreach ($role['people'] as $person) : $i++; ?><a href="<?= get_permalink($person->ID) ?>"><?= $person->post_title; ?></a><?= count($role['people']) > $i ? ', ' : '';
                                    endforeach; ?>
                                </span>
                            </span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if (!empty($body)):
                        $title = __('Těleso', 'nddb');
                        $description = getGlossaryTermDescription($title);                                                
                        ?>
                        <span class="record-role col-lg-6">
                            <b class="record-role-title">
                                <span class="d-inline-flex align-items-center" <?= getTooltipAttributes($description) ?>><?= $title ?></span>
                            </b>
                            <span class="record-role-people" id="teleso_val"><?= $body ?></span>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!empty($notes)): ?>
            <div class="col-lg-6">
                <h2><?= esc_html__('Poznámky', 'nddb') ?></h2>
                <p><?= $notes ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($instruments_voices) || !empty($documentation)): ?>
            <div class="col-lg-6">
                <h2><?= esc_html__('Nástroje a hlasy', 'nddb') ?></h2>
                <?php if (!empty($instruments_voices)): ?>
                    <div class="record-instruments-voices d-flex flex-wrap gap-2" id="nastroje_hlasy_val"><?php foreach ($instruments_voices as $instrument_voice): ?><span class="t4 text-dark bg-light px-3 py-2"><?= $instrument_voice->name ?> </span><?php endforeach; ?></div>
                <?php endif; ?>
                <?php if (!empty($documentation)): ?>
                    <a class="record-documentation block-hover d-inline-flex text-dark mt-4" href="<?= wp_get_attachment_url($documentation) ?>" target="_blank"> 
                        <div class="record-documentation-image media-cover-all">
                            <?= wp_get_attachment_image($documentation, 'thumbnail'); ?>
                        </div>
                        <div class="record-documentation-content block-hover-target d-flex flex-grow-1 align-items-center gap-2 px-4 py-2">
                            <h3 class="t2 flex-1 mb-0"><?= esc_html__('Dokumentace', 'nddb') ?></h3>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-md">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                        </div>
                    </a>
                <?php endif;?>
            </div>
        <?php endif; ?>
        <?php if (!empty($formats) || !empty($physical_parameters) || !empty($place_of_origin) || !empty($dedication)): ?>
            <div class="col-lg-6">
                <h2><?= esc_html__('Další informace', 'nddb') ?></h2>
                <div class="record-information">
                    <?php if (!empty($formats)):
                        $title = esc_html__('Typ nosiče', 'nddb');
                        $description = getGlossaryTermDescription($title);
                        ?>
                        <span <?= getTooltipAttributes($description) ?>>
                            <b><?= $title ?>:&nbsp;</b><span class="t3 text-light-gray mb-0 mt-1" id="typ_nosice_val"><?php foreach($formats as $index => $format): ?><span><?= $format->name ?><?= count($formats) > ($index + 1) ? ', ' : ''; ?></span><?php endforeach; ?></span>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($physical_parameters)):
                        $title = esc_html__('Fyzické parametry', 'nddb');
                        $description = getGlossaryTermDescription($title);
                        ?>
                        <span <?= getTooltipAttributes($description) ?>>
                            <b><?= $title ?>:&nbsp;</b><span id="fyzicke_parametry_val"><?= $physical_parameters ?></span>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($place_of_origin)):
                        $title = esc_html__('Místo původu', 'nddb');
                        $description = getGlossaryTermDescription($title);
                        ?>
                        <span <?= getTooltipAttributes($description) ?>>
                            <b><?= $title ?>:&nbsp;</b><span id="misto_vydani_val"><?= $place_of_origin ?></span>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($dedication)):
                        $title = esc_html__('Dedikace', 'nddb');
                        // $description = getGlossaryTermDescription($title);
                        ?>
                        <span <?= getTooltipAttributes($dedication) ?>>
                            <b><?= $title ?>&nbsp;</b><span style="display: none;" id="dedikace_val"><?= $dedication ?></span>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>