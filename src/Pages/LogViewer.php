<?php
namespace WPX\Karambit\Pages;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Hooks\Actions;
use WPX\Karambit\Core\Pages\SubPage;
use WPX\Karambit\Core\Pages\Components\SettingBuilder;
use WPX\Karambit\Core\Diagnostics\Logging\Logger;



class LogViewer extends SubPage implements Actions {

    

    public function __construct(  $parent_slug, $hooks) {
        parent::__construct(  $parent_slug, $hooks );
    }

    public function get_actions(): array {
        return array(
            'admin_menu'            => array( 'add_page' ),
            'admin_init'            => array( 'register_sections' ),
            'admin_notices'         => array( 'display_admin_notices' ),
            'admin_enqueue_scripts' => array( 'maybe_enqueue_stylesheets'),
        );
    }

    public function maybe_enqueue_stylesheets($hook_suffix ) {
        // \WPX\Karambit\Core\Debug::logDump( 'enqueue media', __METHOD__);
        parent::maybe_enqueue_stylesheets($hook_suffix );
        wp_enqueue_media();
    }


    /**
     * Return the menu title.
     *
     * @return string
     */
    protected function get_menu_title() {
        return __( "Log Viewer", Manifest::SLUG );
    }

    /**
     * Return the page title.
     *
     * @return string
     */
    protected function get_page_title() {
        return __( Manifest::NAME . ' Logs', Manifest::SLUG );
    }

    /**
     * Return the menu icon as a dashicon.
     *
     * @link https://developer.wordpress.org/resource/dashicons/
     *
     * @return string
     */
    // protected function get_icon_url() {
    //     return 'dashicons-shield-alt';
    // }

    /**
     * Return page slug.
     *
     * @return string
     */
    public function get_slug() {
        return Manifest::PREFIX . '_logs';
    }

    /**
     * Register sections.
     */
    public function register_sections() {
        // $this->add_section( new \WPX\Karambit\Options\GeneralOptions() );
        

        

        foreach ($this->sections as  $section) {
            SettingBuilder::build_ui_from_section($this->get_slug(), $section);
        }
    }

    /**
     * Render Custom HTML for plugin wp-admin page above options sections
     */
    public function content_top() {
        $logs = esc_textarea(Logger::get_contents());
        $path = json_encode(Manifest::PREFIX . "/v1/logs/clear");
        $get_path = json_encode(Manifest::PREFIX . "/v1/logs");
        $html = <<<HTML
            <div class="krmbt-log-viewer">
                <h3>System Logs</h3>
                <textarea id="krmbt-log-content" readonly style="width:100%; height:300px; font-family:monospace;">{$logs}</textarea>
                <div style="margin-top: 10px;">
                    <button type="button" id="krmbt-refresh-logs" class="button button-link">Refresh Log File</button>
                    <button type="button" id="krmbt-clear-logs" class="button button-link-delete">Clear Log File</button>
                </div>
            </div>

            <script>
                jQuery('#krmbt-refresh-logs').on('click', function() {
                    wp.apiFetch({
                        path: {$get_path},
                        method: 'GET'
                    }).then( (response) => {
                        console.log(response);
                        if(response.success) {
                            jQuery('#krmbt_notices').html( response.message ).hide().fadeIn();
                            jQuery('#krmbt-log-content').text(response.logs);
                            jQuery(document).trigger('wp-updates-notice-added');
                        }
                        
                    } );
                });
                jQuery('#krmbt-clear-logs').on('click', function() {
                    if ( ! confirm('Are you sure you want to wipe the logs?') ) return;
                    
                    
                    wp.apiFetch({
                        path: {$path},
                        method: 'POST'
                    }).then((res) => {
                        if(res.success) {
                            wp.apiFetch({
                                path: {$get_path},
                                method: 'GET'
                            }).then( (response) => {
                                console.log(response);
                                if(response.success) {
                                    jQuery('#krmbt_notices').html( res.message ).hide().fadeIn();
                                    jQuery('#krmbt-log-content').text(response.logs);
                                    jQuery(document).trigger('wp-updates-notice-added');
                                }
                                
                            } );
                        } else {
                            jQuery('#krmbt_notices').html( res.message ).hide().fadeIn();
                            jQuery(document).trigger('wp-updates-notice-added');
                        }
                        
                    });
                });
            </script>
        HTML;
        return $html;
    
    }

}
