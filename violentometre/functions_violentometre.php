<?php

/***************/
/* Chargement de jquery */
/***************/

function enqueue_sortable_admin_script($hook) {
    $screen = get_current_screen();
    if ($screen->post_type === 'quiz_question') {
        wp_enqueue_script('jquery-ui-sortable');
    }
}
add_action('admin_enqueue_scripts', 'enqueue_sortable_admin_script');

/***************/
/* CSS admin */
/***************/

function quiz_question_admin_styles() {
    $screen = get_current_screen();
    if ($screen->post_type === 'quiz_question') {
        echo '<style>
            .column-order { width: 2em; text-align: center; font-weight: bold; }
            .column-example { width: 25%; }
            .column-answers { width: 35%; }
            .column-answers ul { margin: 0; padding-left: 1em; list-style: disc; }
            .column-answers li { margin: 2px 0; }
        </style>';
    }
}
add_action('admin_head', 'quiz_question_admin_styles');


/***************/
/* Chargement des assets */
/***************/

function violentometre_register_enqueue() {
    add_action('wp_enqueue_scripts', 'violentometre_enqueue_assets');
}

function violentometre_enqueue_assets() {
    wp_enqueue_style('violentometre-css', get_stylesheet_directory_uri() . '/violentometre/css/violentometre.css', [], '1.0');
    wp_enqueue_script('violentometre-js', get_stylesheet_directory_uri() . '/violentometre/js/violentometre.js', ['jquery'], '1.0', true);

    // Charger les barèmes depuis les options
    $baremes = array_map(function($b) {
        return [
            'zone' => $b['zone'],
            'min' => intval($b['min']),
            'max' => intval($b['max']),
            'message' => $b['message'],
        ];
    }, get_option('quiz_result_scales', []));

    wp_localize_script('violentometre-js', 'violentometreData', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'baremes' => array_map(function($item) {
    $item['message'] = stripslashes($item['message']);
    return $item;
}, $baremes),
    ]);
}
add_action('wp_enqueue_scripts', 'violentometre_enqueue_assets');


/***************/
/* Shortcode */
/***************/

function render_violentometre_shortcode() {
    ob_start();
    include get_template_directory() . '/violentometre/templates/quiz-template.php';
    return ob_get_clean();
}
add_shortcode('violentometre', 'render_violentometre_shortcode');

/***************/
/* Custom post */
/***************/

function create_quiz_question_cpt() {
    register_post_type('quiz_question', [
        'label' => 'Questions du quiz',
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-feedback',
        'supports' => ['title', 'page-attributes'],
        'hierarchical' => false,
        'labels' => [
            'name' => 'Questions du quiz',
            'singular_name' => 'Question du quiz',
            'add_new_item' => 'Ajouter une question',
            'edit_item' => 'Modifier la question',
        ],
    ]);
}
add_action('init', 'create_quiz_question_cpt');

/***************/
/* Metabox de question */
/***************/

function add_quiz_question_metabox() {
    add_meta_box(
        'quiz_question_fields',
        'Détails de la question',
        'render_quiz_question_metabox',
        'quiz_question',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_quiz_question_metabox');


/***************/
/* Affichage des champs */
/***************/

function render_quiz_question_metabox($post) {
    $example = get_post_meta($post->ID, '_quiz_example', true);
    $answers = get_post_meta($post->ID, '_quiz_answers', true) ?: [];

    wp_nonce_field('save_quiz_question', 'quiz_question_nonce');

    echo '<label for="quiz_example"><strong>Exemple ou contexte :</strong></label>';
    echo '<textarea name="quiz_example" rows="3" style="width:100%;">' . esc_textarea($example) . '</textarea><br><br>';

    echo '<strong>Réponses possibles :</strong>';
    echo '<div id="quiz_answers_container">';
    
    if (!empty($answers)) {
        foreach ($answers as $i => $answer) {
            echo '<div class="quiz-answer">';
            echo '<input type="text" name="quiz_answers[' . $i . '][text]" value="' . esc_attr($answer['text']) . '" placeholder="Texte de la réponse" style="width:60%;" />';
            echo '<input type="number" name="quiz_answers[' . $i . '][score]" value="' . esc_attr($answer['score']) . '" placeholder="Score" style="width:20%; margin-left:10px;" />';
            echo '<button type="button" class="remove-answer">🗑️</button>';
            echo '</div>';
        }
    }

    echo '</div>';
    echo '<button type="button" id="add-answer">➕ Ajouter une réponse</button>';

    // JS inline simple
    echo '<script>
        let container = document.getElementById("quiz_answers_container");
        document.getElementById("add-answer").addEventListener("click", function() {
            const index = container.children.length;
            const div = document.createElement("div");
            div.className = "quiz-answer";
            div.innerHTML = `
                <input type="text" name="quiz_answers[${index}][text]" placeholder="Texte de la réponse" style="width:60%;" />
                <input type="number" name="quiz_answers[${index}][score]" placeholder="Score" style="width:20%; margin-left:10px;" />
                <button type="button" class="remove-answer">🗑️</button>
            `;
            container.appendChild(div);
        });
        container.addEventListener("click", function(e) {
            if (e.target.classList.contains("remove-answer")) {
                e.target.parentElement.remove();
            }
        });
    </script>';
}

/***************/
/* Sauvegarde des champs */
/***************/

function save_quiz_question_meta($post_id) {
    if (!isset($_POST['quiz_question_nonce']) || !wp_verify_nonce($_POST['quiz_question_nonce'], 'save_quiz_question')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['quiz_example'])) {
        update_post_meta($post_id, '_quiz_example', sanitize_textarea_field($_POST['quiz_example']));
    }

    if (isset($_POST['quiz_answers']) && is_array($_POST['quiz_answers'])) {
        $cleaned = [];
        foreach ($_POST['quiz_answers'] as $answer) {
            $cleaned[] = [
                'text' => sanitize_text_field($answer['text']),
                'score' => is_numeric($answer['score']) ? intval($answer['score']) : null
            ];
        }
        update_post_meta($post_id, '_quiz_answers', $cleaned);
    }
}
add_action('save_post_quiz_question', 'save_quiz_question_meta');

/***************/
/* Ordre manuel des questions */
/***************/

function quiz_question_custom_order($query) {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'quiz_question') return;

    $query->set('orderby', 'menu_order');
    $query->set('order', 'ASC');
}
add_action('pre_get_posts', 'quiz_question_custom_order');

/***************/
/* Drag drop admin */
/***************/

function quiz_question_sortable_js() {
    $screen = get_current_screen();
    if ($screen->post_type !== 'quiz_question') return;
    ?>
<style>
.wp-list-table tbody tr {
    cursor: move;
}
</style>
<script>
jQuery(function($) {
    var tbody = $('.wp-list-table tbody');
    tbody.sortable({
        handle: '.column-title',
        update: function() {
            var order = [];
            tbody.find('tr').each(function(index) {
                order.push({
                    id: $(this).attr('id').replace('post-', ''),
                    position: index
                });
            });
            $.post(ajaxurl, {
                action: 'quiz_question_reorder',
                order: order,
                _ajax_nonce: '<?php echo wp_create_nonce('quiz_order_nonce'); ?>'
            });
        }
    });
});
</script>
<?php
}
add_action('admin_footer', 'quiz_question_sortable_js');

function quiz_question_reorder() {
    check_ajax_referer('quiz_order_nonce');

    if (!current_user_can('edit_posts')) wp_die();

    if (isset($_POST['order']) && is_array($_POST['order'])) {
        foreach ($_POST['order'] as $item) {
            wp_update_post([
                'ID' => intval($item['id']),
                'menu_order' => intval($item['position']),
            ]);
        }
    }
    wp_die();
}
add_action('wp_ajax_quiz_question_reorder', 'quiz_question_reorder');

/***************/
/* Affichage en admin des données des questions */
/***************/

function quiz_question_columns($columns) {
    $columns = array_slice($columns, 0, 1, true)
        + ['order' => '#']
        + array_slice($columns, 1, null, true);
    $columns['example'] = 'Exemple / Contexte';
    $columns['answers'] = 'Réponses possibles';
    return $columns;
}
add_filter('manage_quiz_question_posts_columns', 'quiz_question_columns');

function quiz_question_custom_column_content($column, $post_id) {
    if ($column === 'example') {
        $example = get_post_meta($post_id, '_quiz_example', true);
        echo esc_html(wp_trim_words($example, 20));
    }

    if ($column === 'answers') {
        $answers = get_post_meta($post_id, '_quiz_answers', true);
        if (is_array($answers)) {
            echo '<ul style="margin:0;padding-left:1.2em;">';
            foreach ($answers as $answer) {
                echo '<li>' . esc_html($answer['text']) . ' <span style="opacity:0.6;">(' . esc_html($answer['score']) . ' pts)</span></li>';
            }
            echo '</ul>';
        } else {
            echo '<em>Aucune réponse définie</em>';
        }
    }
    if ($column === 'order') {
        $order = get_post_field('menu_order', $post_id);
        echo intval($order) + 1;
    }
}
add_action('manage_quiz_question_posts_custom_column', 'quiz_question_custom_column_content', 10, 2);

/***************/
/* Sous-menus */
/***************/

function add_quiz_result_scale_submenu() {
    add_submenu_page(
        'edit.php?post_type=quiz_question',
        'Barèmes de résultat',
        'Barèmes de résultat',
        'manage_options',
        'quiz_result_scale',
        'render_quiz_result_scale_page'
    );
}
add_action('admin_menu', 'add_quiz_result_scale_submenu');

add_action('admin_menu', function() {
    add_submenu_page(
        'edit.php?post_type=quiz_question',
        'Statistiques du quiz',
        '📊 Statistiques',
        'manage_options',
        'quiz_stats',
        'render_quiz_stats_page'
    );
});

/***************/
/* Affichage des résultats */
/***************/

function render_quiz_result_scale_page() {
    if (!current_user_can('manage_options')) return;

    // Récupération des questions et scores max
    $questions = get_posts([
        'post_type' => 'quiz_question',
        'numberposts' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    ]);

    $total_questions = count($questions);
    $score_max = 0;
    foreach ($questions as $q) {
        $answers = get_post_meta($q->ID, '_quiz_answers', true);
        if (is_array($answers)) {
            $max = max(array_column($answers, 'score'));
            $score_max += intval($max);
        }
    }

    // Récupération du barème déjà enregistré
    $scales = get_option('quiz_result_scales', []);

    // Formulaire soumis
    if (isset($_POST['quiz_result_scale_nonce']) && wp_verify_nonce($_POST['quiz_result_scale_nonce'], 'save_quiz_result_scale')) {
        $new_scales = [];
        if (!empty($_POST['scale'])) {
            foreach ($_POST['scale'] as $s) {
                $new_scales[] = [
                    'zone' => sanitize_text_field($s['zone']),
                    'min' => intval($s['min']),
                    'max' => intval($s['max']),
                    'message' => wp_kses_post(stripslashes($s['message']))
                ];
            }
        }
        update_option('quiz_result_scales', $new_scales);
        $scales = $new_scales;
        echo '<div class="updated"><p>Barèmes enregistrés.</p></div>';
    }

    ?>

<div class="wrap">
    <h1>Barèmes de résultat du quiz</h1>
    <p><strong><?= $total_questions ?> questions enregistrées</strong></p>
    <p><strong>Score maximum possible :</strong> <?= $score_max ?> points</p>

    <form method="post">
        <?php wp_nonce_field('save_quiz_result_scale', 'quiz_result_scale_nonce'); ?>

        <table class="widefat fixed" style="margin-top: 20px;">
            <thead>
                <tr>
                    <th>Zone</th>
                    <th>Point min.</th>
                    <th>Point max.</th>
                    <th>Message affiché</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody id="quiz-scale-rows">
                <?php foreach ($scales as $i => $scale) : ?>
                <tr>
                    <td>
                        <select name="scale[<?= $i ?>][zone]" style="min-width:120px;">
                            <option value="Vert" <?= selected($scale['zone'], 'Vert') ?>>🟩 Vert</option>
                            <option value="Orange" <?= selected($scale['zone'], 'Orange') ?>>🟧 Orange</option>
                            <option value="Rouge" <?= selected($scale['zone'], 'Rouge') ?>>🟥 Rouge</option>
                        </select>
                    </td>
                    <td><input type="number" name="scale[<?= $i ?>][min]" value="<?= esc_attr($scale['min']) ?>" /></td>
                    <td><input type="number" name="scale[<?= $i ?>][max]" value="<?= esc_attr($scale['max']) ?>" /></td>
                    <td><textarea name="scale[<?= $i ?>][message]" rows="2"
                            style="width:100%;"><?= esc_textarea(stripslashes($scale['message'])) ?></textarea></td>
                    <td><button type="button" class="button remove-scale-row">🗑️</button></td> <!-- ✅ Ajout -->
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


        <p>
            <button type="button" class="button" id="add-scale-row">➕ Ajouter un barème</button>
        </p>

        <p>
            <button type="submit" class="button button-primary">💾 Enregistrer</button>
        </p>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const tbody = document.getElementById('quiz-scale-rows');
    const addButton = document.getElementById('add-scale-row');

    addButton.addEventListener('click', function() {
        const index = tbody.querySelectorAll('tr').length;
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select name="scale[${index}][zone]" style="min-width:120px;">
                    <option value="Vert">🟩 Vert</option>
                    <option value="Orange">🟧 Orange</option>
                    <option value="Rouge">🟥 Rouge</option>
                </select>
            </td>
            <td><input type="number" name="scale[${index}][min]" /></td>
            <td><input type="number" name="scale[${index}][max]" /></td>
            <td><textarea name="scale[${index}][message]" rows="2" style="width:100%;"></textarea></td>
            <td><button type="button" class="button remove-scale-row">🗑️</button></td>
        `;
        tbody.appendChild(row);
    });

    // Supprimer une ligne
    tbody.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-scale-row')) {
            e.target.closest('tr').remove();
        }
    });
});
</script>
<?php
}

add_action('wp_ajax_quiz_track_launch', function () {
    $stats = get_option('quiz_stats_data', [
        'launches' => 0,
        'results' => 0,
        'scores' => [],
    ]);

    $stats['launches'] += 1;

    update_option('quiz_stats_data', $stats);
    wp_die();
});


/***************/
/* Data des résultats */
/***************/

add_action('wp_ajax_quiz_track_result', function () {
    $score = isset($_POST['score']) ? intval($_POST['score']) : 0;
    if ($score === 0) wp_die(); // on ne stocke pas les 0

    $stats = get_option('quiz_stats_data', [
        'launches' => 0,
        'results' => 0,
        'scores' => [],
    ]);

    $stats['results'] += 1;
    if (!isset($stats['scores'][$score])) {
        $stats['scores'][$score] = 1;
    } else {
        $stats['scores'][$score]++;
    }

    update_option('quiz_stats_data', $stats);
    wp_die();
});

function render_quiz_stats_page() {
    if (!current_user_can('manage_options')) return;

    $stats = get_option('quiz_stats_data', [
        'launches' => 0,
        'results' => 0,
        'scores' => [],
    ]);

    echo '<div class="wrap">';
    echo '<h1>📊 Statistiques du quiz</h1>';
    echo '<p><strong>Lancé :</strong> ' . intval($stats['launches']) . ' fois</p>';
    echo '<p><strong>Avec résultat :</strong> ' . intval($stats['results']) . ' fois</p>';

    if (!empty($stats['scores'])) {
        echo '<h2>Répartition des scores</h2>';
        echo '<ul>';
        foreach ($stats['scores'] as $score => $count) {
            echo '<li>' . $count . ' × ' . intval($score) . ' point' . ($score > 1 ? 's' : '') . '</li>';
        }
        echo '</ul>';
        echo '<canvas id="scoreChart" style="width:400px;"></canvas>';
        echo "<script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('scoreChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: " . json_encode(array_keys($stats['scores'])) . ",
                    datasets: [{
                        label: 'Nombre de fois obtenu',
                        data: " . json_encode(array_values($stats['scores'])) . ",
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Répartition des scores'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0,
                            title: {
                                display: true,
                                text: 'Occurrences'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Score'
                            }
                        }
                    }
                }
            });
        });
        </script>";
    } else {
        echo '<p>Aucun score enregistré pour le moment.</p>';
    }

    echo '<form method="post" onsubmit="return confirm(\'Confirmer la réinitialisation des statistiques ?\');">';
    echo '<input type="hidden" name="reset_stats" value="1">';
    submit_button('🗑️ Réinitialiser les statistiques', 'delete');
    echo '</form>';

    echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';

    echo '</div>';

    if (isset($_POST['reset_stats']) && current_user_can('manage_options')) {
        delete_option('quiz_stats_data');
        echo '<script>location.reload();</script>';
    }
}






?>