<?php
$theme = wp_get_theme();
if ( is_child_theme() ) {
    $theme = wp_get_theme( $theme->get( 'Template' ) );
}
$theme_name = $theme->get( 'Name' );
$theme_version = $theme->get( 'Version' );
?>

<div class="wrap">
    <h1><?php esc_html_e('Theme Setup', 'orologio'); ?></h1>

    <div class="okthemes-admin-welcome-page">
        <div class="okthemes-welcome-panel">
            <div class="okthemes-welcome-panel-content">
                
            <div class="okthemes-welcome-panel-header">
                <h2>
                    <?php 
                    echo sprintf(
                        '%s <strong>%s</strong>',
                        esc_html__( 'Welcome to', 'orologio' ), 
                        $theme_name . ' ' . $theme_version );
                    ?>
                        
                </h2>
                <p class="about-description"><?php esc_html_e( 'Beautifully crafted WordPress theme ready to take your watches to the next level.', 'orologio' ) ?></p>
                </div>

                <div class="okthemes-welcome-panel-container">
                    <div class="okthemes-welcome-panel-column-wrapper">
                        <!-- Theme Screenshot -->
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/screenshot.png' ); ?>" alt="<?php echo esc_attr( $theme_name ); ?> Screenshot">
                    </div>
                    
                    <div class="okthemes-welcome-panel-column-wrapper theme-setup">
                        <!-- License activation -->
                        <div class="okthemes-welcome-panel-column">
                            <div class="okthemes-welcome-panel-column-content">
                                <span>Step 1</span>
                                <h3><?php esc_html_e( 'Activate theme', 'orologio' ) ?></h3>
                                <p><?php esc_html_e( 'Enter your purchase code and email to activate the theme and unlock premium features.', 'orologio' ); ?></p>

                                <?php if (get_option('orologio_license_status') !== 'active') : ?>

                                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">

                                        <?php
                                        if (isset($_GET['license'])) {
                                            if ($_GET['license'] === 'success') {
                                                echo '<div class="notice notice-success inline"><p>' . esc_html__('License activated successfully.', 'orologio') . '</p></div>';
                                            } elseif ($_GET['license'] === 'deactivated') {
                                                echo '<div class="notice notice-warning inline"><p>' . esc_html__('License was deactivated.', 'orologio') . '</p></div>';
                                            } elseif ($_GET['license'] === 'reset') {
                                                echo '<div class="notice notice-info inline"><p>' . esc_html__('License has been reset. You can now activate it again.', 'orologio') . '</p></div>';
                                            }
                                        }

                                        if (isset($_GET['license_error'])) {
                                            echo '<div class="notice notice-error inline"><p>' . esc_html(urldecode($_GET['license_error'])) . '</p></div>';
                                        }
                                        ?>

                                        <label for="theme_source"><strong><?php esc_html_e('Where did you get this theme?', 'orologio'); ?></strong></label><br>
                                        <select name="theme_source" id="theme_source" required style="margin-bottom: 10px;">
                                            <option value="market"><?php esc_html_e('Envato Market', 'orologio'); ?></option>
                                            <option value="elements"><?php esc_html_e('Envato Elements', 'orologio'); ?></option>
                                        </select>

                                        <div id="purchase_code_field">
                                            <input type="text" name="purchase_code" placeholder="<?php esc_attr_e('Purchase Code', 'orologio'); ?>" class="regular-text" style="margin-bottom: 4px; display: block; width: 100%;">
                                            <p style="margin: 0 0 10px;">
                                                <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank" rel="noopener noreferrer">
                                                    <?php esc_html_e('How to find your purchase code?', 'orologio'); ?>
                                                </a>
                                            </p>
                                        </div>

                                        <input type="email" name="email" placeholder="<?php esc_attr_e('Email Address', 'orologio'); ?>" class="regular-text" required style="margin-bottom: 10px; display: block; width: 100%;">

                                        <?php wp_nonce_field('theme_license_activate'); ?>
                                        <input type="hidden" name="action" value="theme_activate_license">
                                        <?php submit_button(__('Continue Setup', 'orologio'), 'primary', 'submit', false); ?>
                                    </form>
                                <?php else : ?>
                                    <p class="theme-license-active notice notice-success" style="margin-top: 10px;"><strong><?php esc_html_e('License is active.', 'orologio'); ?></strong></p>

                                    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display: inline-block; margin-right: 8px;">
                                        <?php wp_nonce_field('theme_license_deactivate'); ?>
                                        <input type="hidden" name="action" value="theme_deactivate_license">
                                        <?php submit_button(__('Deactivate License on this domain', 'orologio'), 'secondary', 'submit', false); ?>
                                        <p><em>In order to connect the license to a different domain, first click "Disconnect domain" and then re-enter the purchase key on a different WordPress installation.</em></p>
                                    </form>

                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Install plugins -->
                        <div class="okthemes-welcome-panel-column">
                            <span>Step 2</span>
                            <div class="okthemes-welcome-panel-column-content">
                                <h3><?php esc_html_e( 'Install Plugins', 'orologio' ) ?></h3>
                                <p><?php esc_html_e( 'Install the recommended plugins to ensure full functionality and design flexibility.', 'orologio' ); ?></p>

                                <?php if (get_option('orologio_license_status') === 'active') : ?>
                                    <a class="button button-primary" href="<?php echo admin_url('admin.php?page=tgmpa-install-plugins'); ?>">
                                        <?php esc_html_e( 'Install Plugins', 'orologio' ); ?>
                                    </a>
                                <?php else : ?>
                                    <a class="button button-primary disabled" href="javascript:void(0);" title="<?php esc_attr_e('Please activate your theme to enable this step.', 'orologio'); ?>" onclick="return false;">
                                        <?php esc_html_e( 'Install Plugins', 'orologio' ); ?>
                                    </a>
                                    <p><em class="description"><?php esc_html_e('You need to activate the theme before installing plugins.', 'orologio'); ?></em></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Import demo content -->
                        <div class="okthemes-welcome-panel-column">
                            <span>Step 3</span>
                            <div class="okthemes-welcome-panel-column-content">
                                <h3><?php esc_html_e( 'Import demo content', 'orologio' ) ?></h3>
                                <p><?php esc_html_e( 'Import demo pages, settings, and layouts to quickly replicate the theme\'s live preview.', 'orologio' ); ?></p>

                                <?php if (get_option('orologio_license_status') === 'active') : ?>
                                    <a class="button button-primary" href="<?php echo admin_url('admin.php?page=one-click-demo-import'); ?>">
                                        <?php esc_html_e( 'Import Demo Content', 'orologio' ); ?>
                                    </a>
                                <?php else : ?>
                                    <a class="button button-primary disabled" href="javascript:void(0);" title="<?php esc_attr_e('Please activate your theme to enable this step.', 'orologio'); ?>" onclick="return false;">
                                        <?php esc_html_e( 'Import Demo Content', 'orologio' ); ?>
                                    </a>
                                    <p><em class="description"><?php esc_html_e('You need to activate the theme before importing the demo content.', 'orologio'); ?></em></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="okthemes-welcome-panel-column-wrapper">
                        <div class="okthemes-promotion">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/admin/assets/okthemes-logo.png' ); ?>" alt="OKThemes Logo" class="okthemes-logo">
                            <p><strong>OKThemes Giveaway</strong></p>
                            <p>Download Torac — Free Premium Wine & Champagne WordPress Theme. Use it for your site, agency clients, or as inspiration.</p>
                            <a class="button button-primary" target="_blank" href="https://okthemes.com/freebies/" title="Download Torac">
                                <?php esc_html_e( 'Free Theme Download', 'orologio' ); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <ul>
                <?php if (class_exists('Okthemes_Toolkit')) : ?>
                <li>
                    <a href="<?php echo admin_url( 'admin.php?page=orologio_options' ); ?>">
                        <i class="dashicons dashicons-admin-customizer"></i>    
                        <?php esc_html_e( 'Customize Appearance', 'orologio' ) ?>
                    </a>
                </li>
                <?php endif; ?>
            
                <li>
                    <a href="https://orologio.okthemes.com/assets/doc/orologio.pdf" target="_blank">
                        <i class="dashicons dashicons-editor-help"></i>    
                        <?php esc_html_e( 'Online Documentation', 'orologio' ) ?>
                    </a>
                </li>
                <li>
                    <a href="mailto:support@okthemes.com" target="_blank">
                        <i class="dashicons dashicons-sos"></i>    
                        <?php esc_html_e( 'Support', 'orologio' ) ?>
                    </a>
                </li>
            </ul>
        </div>

    </div>
</div>
