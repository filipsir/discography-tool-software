<?php
// Reorder people by roles
function orderPeopleByRoles($people) {
    if (empty($people) || gettype($people) !== 'array') return null;
    $people_by_roles = [];
    if (!empty($people)):
            foreach ($people as $person):
                foreach ($person['role'] as $role):
                    unset($person['role']);
                    $role_to_push = [
                        'id' => $role->term_id,
                        'name' => $role->name,
                        'people' => []
                    ];
                    $filtered_array = array_filter($people_by_roles, function ($key) use ($role) {
                        return $key['id'] === $role->term_id;
                    });
                    if (!empty($filtered_array)) {
                        $array_position = array_search(reset($filtered_array), $people_by_roles);
                        array_push($people_by_roles[$array_position]['people'], reset($person));
                    } else {
                        array_push($role_to_push['people'], reset($person));
                        array_push($people_by_roles, $role_to_push);
                    }
                endforeach;
            endforeach;
            usort($people_by_roles, fn($a, $b) => $a['name'] <=> $b['name']);
            return $people_by_roles;
    endif;
}

function getRecordDataFillPercentage($post_id) {
    $data = get_field('record_data', $post_id);
    $data['photo'] = get_field('gallery', $post_id);
    $data['people'] = get_field('record_people', $post_id);
    $data['subtitles'] = get_field('record_subtitle', $post_id);
    $data['genres'] = get_field('record_genre', $post_id);
    $total_data_count = count($data, COUNT_RECURSIVE);
    
    $filled_out_data = array_filter($data, function ($row) {
        if (gettype($row) === 'array') {
            return array_filter($row, function ($value) use ($row) {
                return !empty($value) && $row !== '';
            });
        } else {
            return !empty($row) && $row !== '';
        }
    });
    $filled_out_data_count = count($filled_out_data, COUNT_RECURSIVE);

    return round(($filled_out_data_count / $total_data_count) * 100);
}

function getGlossaryTermDescription($term_title) {
    if (empty($term_title)) return;
    $query = [  
        'post_type' => 'slovnik-pojmu',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'no_found_rows' => true,
        'title' => $term_title,
    ];
    $loop = new WP_Query( $query );
    
    if ($loop->have_posts()):
        $term = $loop->posts[0];
        $decription = get_field('glossary_description', $term->ID);
        return $decription;
    else:
        return null;
    endif;

}

function getTooltipAttributes($message) {
    if (empty($message)) return;
    $formatted_message = trim(esc_attr($message));
    return "data-tooltip data-tippy-content='{$formatted_message}' data-tippy-placement='bottom-start'";
}


function getPastDateRecordsFromQuery(WP_Query $query) {
    foreach($query->posts as $post) : setup_postdata($post);
        if (isset($post->ID)) {
            $date = get_field('record_data_date', $post->ID);
        } else { $date = null; }
        if (empty($date)) continue;
        if (intval($date['month']) === intval(date('m')) && intval($date['day']) > intval(date('d'))) {
            $array_position = array_search($post, $query->posts);
            unset($query->posts[$array_position]);
            $query->posts = array_values($query->posts);
            $query->found_posts--;
        }

    endforeach;
    return $query;
    wp_reset_postdata();
}
