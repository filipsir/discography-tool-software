<?php

// Base settings
$current_post_id =  get_the_ID();
$slug_base =        'record_data_';
$paged =            !empty($_GET['page-number']) ? absint($_GET['page-number']) : 1;

// Argument defaults
$defaults = array(
    'title' => esc_html__('Katalog', 'nddb'),
    'sort' => true,
    'display_items_to_show' => true,
    'query' => [
        'post_type' => 'nahravky',
        'post_status' => 'publish',
        'no_found_rows' => false,
        'meta_query'	=> [
            'relation' => 'AND',
            array(
                'key' => $slug_base . 'label',
                'value' => $current_post_id
            ),
        ],
        'fields' => 'ids',
    ]
);

$args = wp_parse_args( $args, $defaults );

// Count posts that should exist in main query
$count_query = $args['query'];
$count_query['posts_per_page'] = -1;
$count_query['no_rows_found'] = true;
$count_query['fields'] = 'ids';
$count_loop = new WP_Query( $count_query );
$found_posts = $count_loop->found_posts;

// Set min and max items
$min_items_to_show =                10;
$default_items_to_show_default =    8;
$default_items_to_show =            $min_items_to_show;
$items_to_show =                    !empty($_GET['items-to-show']) ? (absint($_GET['items-to-show'])) : $default_items_to_show;


// If current paged variable isn't realistic, we correct the paged variable.
if ($items_to_show > 0) {
    while ($found_posts/$items_to_show <= ($paged - 1)) $paged--;
}

// Set query variables accordingly
$args['query']['posts_per_page'] = isset($args['query']['posts_per_page']) ? $args['query']['posts_per_page'] : $items_to_show;
$args['query']['paged'] = $paged;

// Set posts_per_page to default if not set in query yet.
if (!isset($args['query']['posts_per_page'])) {
    $args['query']['posts_per_page'] = $items_to_show;
}

// Sort settings
$sort_default_key = $slug_base . 'date_year';
$sort_default_direction = 'DESC';
$sort_query = !empty($_GET['sort-by']) ? $_GET['sort-by'] : $sort_default_key;
$sort_direction = !empty($_GET['sort-direction']) ? $_GET['sort-direction'] : $sort_default_direction;
$sort_key = $sort_query === 'title' ? null : $sort_query;
$orderby = $sort_query === 'title' ? 'title' : 'meta_value';

// If sort is enabled, add sort query variables to the query
if ($args['sort'] === true) {
    $args['query']['meta_key'] = $sort_key;
    $args['query']['orderby'] = $orderby;
    $args['query']['order'] = $sort_direction;
}

$year_head = [
    'title' => esc_html__('Rok', 'nddb'),
    'sortable' => true,
    'slug' =>  $slug_base . 'date_year',
    'direction' => 'ASC'
];

if (!empty($args['show_years_passed']) && $args['show_years_passed'] === true) {
    $year_head = [
        'title' => esc_html__('Událo se před', 'nddb'),
        'sortable' => true,
        'slug' =>  $slug_base . 'date_year',
        'direction' => 'ASC'
    ];    
}

$table_heads = [
    [
        'title' => esc_html__('Objednací číslo', 'nddb'),
        'sortable' => true,
        'slug' =>  $slug_base . 'order_number',
        'direction' => 'ASC'
    ],
    [
        'title' => esc_html__('Matriční číslo', 'nddb'),
        'sortable' => true,
        'slug' =>  $slug_base . 'registration_number',
        'direction' => 'ASC'
    ],
    $year_head,
    [
        'title' => esc_html__('Osoby', 'nddb'),
        'sortable' => false,
        'class' => 'w-25'
    ],
    [
        'title' => esc_html__('Název', 'nddb'),
        'sortable' => true,
        'slug' =>  'title',
        'direction' => 'ASC'
    ],
];

foreach ($table_heads as &$head):
    if ($head['sortable'] === true)
        $head['direction'] = $sort_query === $head['slug'] ? ($sort_direction === 'ASC' ? 'DESC' : 'ASC') : 'ASC';
endforeach;

$loop = new WP_Query( $args['query'] );

$past_date_only_max_posts = 3;
if (!empty($args['past_date_only']) && $args['past_date_only'] === true):
    $loop = getPastDateRecordsFromQuery($loop);
endif;

// Do not display the table if we have no rows 
if (!empty($loop->posts)): ?>
    <div id="catalogue" class="mt-6" data-table-wrapper>
        <div class="catalogue-navigation container d-flex align-items-end justify-content-between">
            <div>
                <h2><?= $args['title'] ?></h2>
                <?php get_template_part( 'templates/components/items-to-show', '', array(
                    'display' => $args['display_items_to_show'],
                    'items_to_show' => $items_to_show,
                )); ?>
            </div>
            <div class="pagination">
                <?= nddb_paginate($loop, ['items_to_show' => $items_to_show], 'catalogue'); ?>
            </div>
        </div>
            <div class="bg-light py-6 my-4">
                <div class="container table-responsive">
                    <table class="record-catalogue table">
                        <thead>
                            <tr>
                                <th></th>
                                <?php foreach ($table_heads as &$head): ?>
                                    <?php if ($args['sort'] === true && $head['sortable'] === true): ?>
                                        <th data-sortable <?= $sort_query === $head['slug'] ? "data-is-sorting data-sort-direction='{$sort_direction}'" : '' ?>>
                                            <a class="text-dark" href="<?= add_query_arg([
                                                'sort-by' => $head['slug'],
                                                'sort-direction' => $sort_query === $head['slug'] ? $head['direction'] : $sort_default_direction ]) ?>">
                                                <?= $head['title'] ?>
                                                <?php get_template_part( 'templates/components/svg/icon', 'chevron-down' ); ?>
                                            </a>
                                        </th>
                                    <?php else: ?>
                                        <th <?= !empty($head['class']) ? "class='{$head['class']}'" : '' ?>><?= $head['title'] ?></th>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <?php // Display all records
                        foreach ( $loop->posts as $index => $post ) : setup_postdata($post);
                            if (!empty($args['past_date_only']) && $args['past_date_only'] === true && $index === ($past_date_only_max_posts) ) break;
                            $data = get_field('record_data');
                            // Get basic data
                            $order_number = $data['order_number'];
                            $registration_number = $data['registration_number'];
                            $date = $data['date'];

                            // Get featured image
                            $featured_image = null;
                            $label = get_field('record_data_label');
                            $label_images = get_field('gallery', $label->ID);
                            if (!empty($label_images)):
                                $label_image = reset($label_images);
                                $featured_image = $label_image;
                            endif;                    


                            $people = get_field('record_people');
                            $people_by_roles = orderPeopleByRoles($people);
                            ?>
                            <tr class="align-middle">
                                <td class="w-0 pe-4">
                                    <a class="d-inline-flex" href="<?= get_the_permalink() ?>">
                                        <div class="record-image media-cover-all"><?= wp_get_attachment_image($featured_image, 'full'); ?></div>
                                    </a>
                                </td>
                                <td><?= $order_number ? $order_number : '' ?></td>
                                <td><?= $registration_number ? $registration_number : '' ?></td>
                                <td><?= $date['year'] ? !empty($args['show_years_passed']) && $args['show_years_passed'] === true ? date('Y') - intval($date['year']) . ' ' . esc_html__('lety', 'nddb') : $date['year'] : '' ?></td>
                                <td name="people">
                                    <?php if (!empty($people_by_roles)): ?>
                                        <?php foreach ($people_by_roles as $role): ?>
                                            <span>
                                                <b><?= $role['name'] ?>:&nbsp;</b><?php
                                                    $i = 0; // Formatting trashed due to random spaces appearing where they should not
                                                    foreach ($role['people'] as $person) : $i++; ?><a href="<?= get_permalink($person->ID) ?>"><?= $person->post_title; ?></a><?= count($role['people']) > $i ? ', ' : '';
                                                    endforeach; ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="d-inline-flex" href="<?= get_the_permalink() ?>">
                                        <?= get_the_title() ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </table>
                </div>
            </div>
        <div class="catalogue-navigation container d-flex align-items-end justify-content-between mb-4">
            <?php get_template_part( 'templates/components/items-to-show', '', array(
                'display' => $args['display_items_to_show'],
                'items_to_show' => $items_to_show,
            )); ?>
            <div class="pagination">
                <?= nddb_paginate($loop, ['items_to_show' => $items_to_show], 'catalogue'); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
