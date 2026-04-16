<?php
namespace WPX\Karambit\Core\Pages;
use WPX\Karambit\Manifest;
use WPX\Karambit\Core\Hooks\Actions;

use WPX\Karambit\Core\Pages\Components\Utility\AdminNotice;
use WPX\Karambit\Core\Pages\Components\Utility\SubmitButton;
use WPX\Karambit\Core\Pages\Components\Sections\SettingsSection;
use WPX\Karambit\Core\Pages\Components\Sections\Section;
use WPX\Karambit\Core\Pages\Components\Sections\Fields\Elements\Element;
use WPX\Karambit\Core\Options\Options;
use WPX\Karambit\Core\Options\OptionSection;
// use WPX\Karambit\Plugin;
use WPX\Karambit\Core\Debug;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

abstract class Admin implements Actions {

    
    
    protected $sections = array();

    
    protected $options;
    protected $hooks;

    
    public function __construct( $hooks ) {
        // \WPX\Karambit\Core\Debug::logDump($options, __METHOD__);
        // $this->options = $options;
        $this->hooks = $hooks;
    }

    
    public function get_actions(): array {
        return array(
            'admin_menu'            => array( 'add_page' ),
            'admin_init'            => array( 'register_sections' ),
            'admin_notices'         => array( 'display_admin_notices' ),
            'admin_enqueue_scripts' => array( 'maybe_enqueue_stylesheets' ),
        );
    }

    
    public function render() {
        $prefix = esc_attr(Manifest::PREFIX);
        $title = esc_html( $this->get_page_title() );

        
        ob_start();
        if(count($this->sections) > 0) {
            ?>
                <form id="<?php echo $this->get_slug(); ?>_form" class="<?php echo Manifest::PREFIX; ?>_admin_form" method="post">
                    <?php
                        settings_fields( $this->get_slug() );
                        do_settings_sections( $this->get_slug() );
                        $submit = new SubmitButton( $this->get_slug() );
                    ?>
                </form>
            <?php
        }
        $content = ob_get_clean();
        $top = $this->content_top();
        $bottom = $this->content_bottom();

        $html = <<<HTML
            <div class="wrap data-wrap" data-prefix="{$prefix}">
                <div id="{$prefix}_notices"></div>
                <div class="{$prefix}-admin-content-top">
                    {$top}
                </div>
                {$content}
                <div class="{$prefix}-admin-content-bottom">
                    {$bottom}
                </div>
            </div>
        HTML;
        echo $html;

        
    }

    public function render_old() {


        ?>
        
        <div class="wrap" data-prefix="<?php echo Manifest::PREFIX; ?>">
            <h1><?php echo esc_html( $this->get_page_title() ); ?></h1>

            <div id="<?php echo Manifest::PREFIX; ?>_notices"></div>

            <div class="<?php echo Manifest::PREFIX; ?>-admin-content-top">
                <?php echo $this->content_top(); ?>
            </div>
            <?php if(count($this->sections) > 0) { ?>
            <form id="<?php echo $this->get_slug(); ?>_form" class="<?php echo Manifest::PREFIX; ?>_admin_form" method="post">
                <?php

                settings_fields( $this->get_slug() );
                do_settings_sections( $this->get_slug() );
                $submit = new SubmitButton( $this->get_slug() );
                ?>
            </form>
            <?php } ?>

            <div class="<?php echo Manifest::PREFIX; ?>-admin-content-bottom">
                <?php echo $this->content_bottom(); ?>
            </div>
        </div>

        <?php


        
    }

    
    public function content_top() {
        return '';
    }

    
    public function content_bottom() {
        return '';
    }

    
    protected function render_admin_notice( $message, $type ) {
        $notice = new AdminNotice( $message, $type );
        $notice->render();
    }

    
    public function display_admin_notices() {

        if($_GET['page'] == $this->get_slug() ) {
            // settings_errors();
            if ( isset( $_GET['action_result'] ) ) {
                if ( $_GET['action_result'] === 'success' ) {
                    $this->render_admin_notice(
                        esc_html( __( 'Action was performed successfully.', Manifest::SLUG ) ),
                        AdminNotice::SUCCESS
                    );
                } else {
                    
                    $this->render_admin_notice(
                        esc_html( __( 'An error occurred. Couldn\'t perform action.', Manifest::SLUG ) ),
                        AdminNotice::ERROR
                    );
                }
            }
        }
    }

    
    public function maybe_enqueue_stylesheets( $hook_suffix ) {
        // \WPX\Karambit\Core\Debug::logDump('hook_suffix: ' . $hook_suffix, __METHOD__);
        // \WPX\Karambit\Core\Debug::logDump('$this->get_page_prefix() . $this->get_slug(): ' . $this->get_page_prefix() . $this->get_slug(), __METHOD__);
        // $screen = get_current_screen();
        // \WPX\Karambit\Core\Debug::logDump( $screen, __METHOD__);
        // if ( str_contains( $hook_suffix, $this->get_page_prefix() .  $this->get_slug() ) ) {
        //     \WPX\Karambit\Core\Debug::logDump( 'SUCCESS: ' . $this->get_page_prefix() .  $this->get_slug() , __METHOD__);
        // }

        if ( str_contains( $hook_suffix, $this->get_page_prefix() .  $this->get_slug() ) ) {
            $this->enqueue_stylesheets();
        } else {
            return;
        }
    }

    public function enqueue_stylesheets() {
        // 1. CSS
        wp_enqueue_style(
            Manifest::PREFIX . '_admin_page',
            Manifest::url('src/assets/css/admin.css'), // Using the url() method we built
            [],
            Manifest::VERSION
        );

        // 2. JS - Let's use a consistent handle variable
        $js_handle = Manifest::PREFIX . '_admin_page';

        wp_enqueue_script( 
            $js_handle,
            Manifest::url('src/assets/js/admin.js'), 
            ['jquery'], // Added jquery as a dependency since your script uses it
            Manifest::VERSION, 
            true // Move to footer for better performance
        );

        // 3. Localize - Using the SAME handle
        wp_localize_script($js_handle, Manifest::PREFIX . '_vars', [
            'prefix'     => Manifest::PREFIX,
            'rest_url' => esc_url_raw(rest_url(Manifest::PREFIX . '/v1/')),
            'nonce'    => wp_create_nonce('wp_rest'), 
        ]);
    }

    abstract public function add_page();

    
    abstract protected function get_menu_title();

    
    abstract protected function get_page_title();

    
    protected function get_capability() {
        return 'manage_options';
    }

    
    abstract protected function get_slug();

    
    abstract protected function get_page_prefix();

    
    protected function get_icon_url() {
        return 'dashicons-admin-generic';
    }

    
    protected function get_position() {
        return null;
    }

    
    abstract public function register_sections();

    
    protected function register_section( $section_id, $properties = array() ) {
        $dump_me = ['id'=>$section_id, 'properties'=>$properties];
        // \WPX\Karambit\Core\Debug::logDump($dump_me, __METHOD__);
        $section = new SettingsSection( $section_id, $this->get_slug(), $this->options, $properties );

        $this->sections[] = $section;

        register_setting(
            $this->get_slug(),
            Manifest::PREFIX . '_' . $section_id,
            // 'qckfe_general_options',
            array( 'sanitize_callback' => array( $section, 'sanitize' ) )
        );

        return $section;
    }

    protected function add_section( $option_section ) {
        
        // \WPX\Karambit\Core\Debug::logDump($option_section->define_fields(), __METHOD__);
        // \WPX\Karambit\Core\Debug::logDump($option_section->get_values(), __METHOD__);
        if ( ! ( $option_section instanceof OptionSection ) ) {
            return;
        }
        $section = new SettingsSection( 
            $option_section->get_db_row(), 
            $this->get_slug(), 
            $option_section, 
            ['title' => $option_section->get_title(), 'description' => $option_section->get_description()] 
        );
        $this->sections[] = $section;

        register_setting(
            $this->get_slug(),
            $option_section->get_db_row(),
            // 'qckfe_general_options',
            array( 'sanitize_callback' => array( $section, 'sanitize' ) )
        );
        
        return $section;
    }

    
    protected function register_presentation_section( $section_id, $properties = array() ) {
        $section = new Section( $section_id, $this->get_slug(), $this->options, $properties );
        $this->sections[] = $section;

        return $section;
    }

}