<?php

/**
 * Plugin Name: Elementor HLS Video Widget
 * Plugin URI: https://fabiomontenegro.com
 * Description: A plugin to add HLS video widget to Elementor.
 * Version: 1.0
 * Author: Fabio Montenegro
 * Author URI: https://fabiomontenegro.com
 */

// Namespace declaration to organize code
namespace WPC;

// Widget_Loader Class
class Widget_Loader
{

    private static $_instance = null;

    // Singleton pattern to ensure only one instance of Widget_Loader
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Include widget files
    private function include_widgets_files()
    {
        require_once(__DIR__ . '/widgets/video.php');
    }

    // Register widgets with Elementor
    public function register_widgets()
    {
        $this->include_widgets_files();
        
        // Register the Video widget with Elementor
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Video());
    }

    // Constructor
    public function __construct()
    {
        // Hook to register widgets after Elementor has initialized
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets'], 99);
    }
}

// Instantiate Plugin Class
Widget_Loader::instance();
