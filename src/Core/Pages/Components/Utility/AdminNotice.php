<?php
namespace WPX\Karambit\Core\Pages\Components\Utility;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AdminNotice {

    const SUCCESS = 'success';
    const INFO = 'info';
    const WARNING = 'warning';
    const ERROR = 'error';

    const PINNED = 'pinned';
    const DISMISSIBLE = 'dismissible';

    
    private $message;

    
    private $notice_type;

    
    private $pin_type;

    /**
     * AdminNotice constructor.
     *
     * @param string $message     Message to display.
     * @param string $notice_type Notice type ('success', 'info', 'warning', or 'error').
     * @param string $pin_type    Pin type (either 'pinned' or 'dismissible').
     */
    public function __construct( $message, $notice_type = 'success', $pin_type = 'dismissible' ) {
        $this->message     = $message;
        $this->notice_type = $notice_type;
        $this->pin_type    = $pin_type;
    }

    
    private function is_dismissible() {
        return $this->pin_type === self::DISMISSIBLE;
    }

    
    private function get_css_classes() {
        $css_classes = array(
            'notice',
            sprintf( 'notice-%s', $this->notice_type )
        );

        if ( $this->is_dismissible() ) {
            $css_classes[] = 'is-dismissible';
        }

        return implode( ' ', array_unique( $css_classes ) );
    }

    
    public function render() {
        
        printf(
            '<div class="%s"><p>%s</p></div>',
            esc_attr( $this->get_css_classes() ),
            $this->message
        );
    }

    public function renderHtml() {
        $html = '<div class="' . esc_attr( $this->get_css_classes() ) . '"><p>' . $this->message . '</p></div>';
        return $html;
    }

}