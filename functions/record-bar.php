<?php

	$is_record = is_singular('nahravky');
    $data = get_field('record_data');
    if ($data):
        $label = $data['label'];
        $registration_number = $data['registration_number'];
        $order_number = $data['order_number'];
        $reissue = $data['reissue'];
        $instruments_voices = $data['instruments_voices'];
        $body = $data['body'];
        $format = $data['format'];
        $physical_parameters = $data['physical_parameters'];
        $place_of_origin = $data['place_of_origin'];
        $owner = $data['owner'];
        $documentation = $data['documentation'];

        $date = $data['date'];
        if ($date):
            $day = $date['day'];
            $month = $date['month'];
            $year = $date['year'];
        endif;

        $record = $data['record'];

        $notes = $data['notes'];

        $subtitles = get_field('record_subtitle');
        $genres = get_field('record_genre');
    endif;

    $show_player = !empty($record['file']) || !empty($record['link']);

?>

<div class="record-bar bar bg-light z-0">
    <div class="container">
        <div class="row">
            <?php if ($show_player): ?>
                <div class="col-lg-4 d-flex align-items-center border-end">
                    <div class="record-player flex-1" data-record-player>
                        <audio class="record-player" crossorigin playsinline>
                            <source src="<?= $record['file'] ? wp_get_attachment_url($record['file']) : $record['link'] ?>" type="audio/mp3">
                        </audio>
                    </div>
                </div>
            <?php endif; ?>
            <div class="<?= $show_player ? 'col-lg-6 data-grid-3' : 'col-lg-10 data-grid-6' ?> data-grid py-3">
                <?php if (!empty($registration_number)):
                    $title = esc_html__('Matriční číslo', 'nddb');
                    $description = getGlossaryTermDescription($title);
                    ?>
                    <span <?= getTooltipAttributes($description) ?>>
                        <b><?= $title ?>:&nbsp;</b><span id="matricni_cislo_val"><?= $registration_number ?></span>
                    </span>
                <?php endif; ?>
                <?php if (!empty($order_number)):
                    $title = esc_html__('Objednací číslo', 'nddb');
                    $description = getGlossaryTermDescription($title);
                    ?>
                    <span <?= getTooltipAttributes($description) ?>>
                        <b><?= $title ?>:&nbsp;</b><span id="objednaci_cislo_val"><?= $order_number ?></span>
                    </span>
                <?php endif; ?>
                <?php if (!empty($reissue)):
                    $title = esc_html__('Reedice', 'nddb');
                    $description = getGlossaryTermDescription($title);
                    ?>
                    <span <?= getTooltipAttributes($description) ?>>
                        <b><?= $title ?>:&nbsp;</b><span id="reedice_val"><?= $reissue ?></span>
                    </span>
                <?php endif; ?>
                <?php if (isset($date) && !empty($date['year'])):
                    $title = esc_html__('Datum nahrání', 'nddb');
                    $description = getGlossaryTermDescription($title);
                    ?>
                    <span <?= getTooltipAttributes($description) ?>>
                        <b><?= $title ?>:&nbsp;</b><span id="datum_vydani_val"><?php echo ($date['day'] != null ? $date['day'] . '. ' : '') . ($date['month'] != null ? $date['month'] . '. ' : ''); ?><?= $date['year'] ?></span>
                    </span>
                <?php endif; ?>
                <?php if (!empty($genres)):
                    $title = esc_html__('Žánr', 'nddb');
                    $description = getGlossaryTermDescription($title);                    
                    ?>
                    <span <?= getTooltipAttributes($description) ?>>
                        <b><?= $title ?>:&nbsp;</b><span class="t3 text-light-gray mb-0 mt-1"><?php foreach($genres as $index => $genre): ?><span id="zanr_val"><?= $genre->name ?><?= count($genres) > ($index + 1) ? ', ' : ''; ?></span><?php endforeach; ?></span>
                    </span>
                <?php endif; ?>
                <?php if (!empty($label)):
                    $title = esc_html__('Label', 'nddb');
                    $description = getGlossaryTermDescription($title);                    
                    ?>
                    <span <?= getTooltipAttributes($description) ?>>
                        <b><?= $title ?>:&nbsp;</b><span id="label_val"><a href="<?= get_permalink($label->ID) ?>"><?= $label->post_title ?></a></span>
                    </span>
                <?php endif; ?>
                <?php if (!empty($owner)):
                    $title = esc_html__('GG', 'nddb');
                    $description = getGlossaryTermDescription($title);
                    ?>
                    <span class="d-block d-lg-none" <?= getTooltipAttributes($description) ?>>
                        <b><?= $title ?>:&nbsp;</b><span id="vlastnik_nahravky_val"><?= $owner ?></span>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>