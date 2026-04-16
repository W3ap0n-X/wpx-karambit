<?php
namespace WPX\Karambit\Core\Options;
/**
 * Options is an interface to access user-defined options.
 */
interface Options {
    /**
     * Return the option value based on the given option name.
     *
     * @param string $name Option name.
     * @return mixed
     */
    public function get( $name );

    /**
     * Store the given value to an option with the given name.
     *
     * @param string $name       Option name.
     * @param mixed  $setValue      Option value.
     * @return bool              Whether the option was added.
     */
    public function set( $name, $setValue );

    /**
     * Remove the option with the given name.
     *
     * @param string $name       Option name.
     */
    public function remove( $name );
}