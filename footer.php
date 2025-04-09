    </section>
    </main>

    <?php
$site_url = site_url();
$template_uri = get_template_directory_uri();
?>

    <div id="exit_button">
        <a href="https://www.caf.fr" class="para_lit">Quitter le site <img
                src="<?php echo $template_uri; ?>/img/icons/cross.svg" alt=""></a>
    </div>

    <footer>

        <div class="container principal gap24">
            <div class="container">
                <a href="<?php echo $site_url; ?>/contact" class="button secondary green_button"
                    title="Aller à la page Contact">
                    <img src="<?php echo $template_uri; ?>/img/icons/message_green.svg" alt="">Contacter Solfa
                </a>

                <a href="<?php echo $site_url; ?>/newsletter" class="button secondary white_button"
                    title="S'inscrire à la newsletter">
                    <img src="<?php echo $template_uri; ?>/img/icons/newsletter.svg" alt="">Newsletter
                </a>
            </div>
            <div class="container gap8">
                <a href="https://www.facebook.com/p/SOLFA-Solidarit%25C3%25A9-Femmes-Accueil-100066279232681/?locale=fr_FR"
                    class="logo_button" target="_blank" rel="noopener noreferrer"
                    title="Aller sur notre profil Facebook">
                    <img src="<?php echo $template_uri; ?>/img/icons/logo_facebook.svg" alt="Page Facebook de Solfa">
                </a>
                <a href="https://www.linkedin.com/company/solfa-solidarit%C3%A9-femmes-accueil/posts/?feedView=all"
                    class="logo_button" target="_blank" rel="noopener noreferrer"
                    title="Aller sur notre profil LinkedIn">
                    <img src="<?php echo $template_uri; ?>/img/icons/logo_linkedin.svg" alt="Page LinkedIn de Solfa">
                </a>
                <a href="https://www.instagram.com/solfa.association/" class="logo_button" target="_blank"
                    rel="noopener noreferrer" title="Aller sur notre profil Instagram">
                    <img src="<?php echo $template_uri; ?>/img/icons/logo_instagram.svg" alt="Page Instagram de Solfa">
                </a>
            </div>
        </div>

        <?php
		wp_nav_menu(array(
			'theme_location' => 'footer_link',
			'container' => 'ul',
			'menu_class' => 'para_lit',
			'fallback_cb' => false
		));
	?>
    </footer>

    <?php wp_footer(); ?>

    </body>

    </html>