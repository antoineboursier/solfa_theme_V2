<?php
$questions = get_posts([
    'post_type' => 'quiz_question',
    'numberposts' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);

$total = count($questions);
?>

<div id="violentometre-container">
    <!-- Écran d'intro -->
    <div id="violentometre-start">
        <button id="start-quiz">Lancer le test du violentomètre</button>
    </div>

    <!-- Étapes du quiz -->
    <div id="violentometre-quiz" style="display:none;">
        <div id="step-counter"></div>

        <?php foreach ($questions as $index => $q) :
            $example = get_post_meta($q->ID, '_quiz_example', true);
            $answers = get_post_meta($q->ID, '_quiz_answers', true);
        ?>
        <div class="quiz-question" data-question-index="<?= $index ?>" style="display:none;">
            <h2><?= esc_html(get_the_title($q)) ?></h2>
            <?php if ($example): ?>
            <p><em><?= esc_html($example) ?></em></p>
            <?php endif; ?>

            <div class="quiz-answers">
                <?php foreach ($answers as $answer): ?>
                <div class="quiz-answer-option" data-score="<?= intval($answer['score']) ?>">
                    <?= esc_html($answer['text']) ?>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($index > 0): ?>
            <button class="prev-question-btn">Question précédente</button>
            <?php endif; ?>

            <?php if ($index === $total - 1): ?>
            <button class="show-result-btn">Découvrir mon résultat</button>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Résultat -->
    <div id="quiz-result" style="display:none; text-align:center;">
        <h2>Votre résultat</h2>
        <p id="quiz-score-summary">Chargement...</p>
        <a href="<?php echo get_permalink(); ?>" class="button">↩ Retour au quiz</a>
    </div>

</div>