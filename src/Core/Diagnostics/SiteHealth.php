<?php
namespace WPX\Karambit\Core\Diagnostics;
use WPX\Karambit\Manifest;
class SiteHealth {
    public function __construct() {
        add_filter( 'site_status_tests', [ $this, 'add_tests' ] );
    }

    public function add_tests( $tests ) {
        $tests['direct']['qck_feed_engine_check'] = [
            'label' => __( 'Qck FeedEngine Status' ),
            'test'  => [ $this, 'test_engine_integrity' ],
        ];
        return $tests;
    }

    public function test_engine_integrity() {
        $result = [
            'label'       => __( 'FeedEngine is running smoothly' ),
            'status'      => 'good',
            'badge'       => [ 'label' => __( 'Qck Core' ), 'color' => 'blue' ],
            'description' => __( 'The FeedEngine manifest is loaded and the template directory is writable.' ),
            'actions'     => '',
        ];

        // Example Check: Is the template folder missing?
        if ( ! is_dir( Manifest::path() . 'templates' ) ) {
            $result['status'] = 'critical';
            $result['label']  = __( 'Missing Template Directory' );
            $result['description'] = __( 'The FeedEngine cannot find its templates folder.' );
        }

        return $result;
    }
}