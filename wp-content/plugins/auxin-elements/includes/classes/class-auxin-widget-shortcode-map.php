<?php
/**
 * Adds Theme Widgets (elements), shortcodes and visual elements
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2022 averta
*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;




class Auxin_Widget_Shortcode_Map {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * The Master list of all shortcodes and widgets
     *
     * @var      array
     */
    private $master_array = array();

    /**
     * The Master list of all shortcodes
     *
     * @var      array
     */
    public $master_shortcode_array = array();



    public function __construct(){
        add_action('auxin_loaded', array( $this, 'auxin_framework_loaded' ) );
    }



    public function auxin_framework_loaded(){

        add_action( 'vc_before_init', array( $this, 'vc_mapper'   ) );
        add_action( 'widgets_init'  , array( $this, 'add_widgets' ) );

        // map and add all shortcodes
        $this->add_shortcodes();
    }



    /**
     * Collects and stores elements info
     */
    public function get_master_array(){

        if( empty( $this->master_array ) ){

            $master_array = apply_filters( 'auxin_master_array_shortcodes', array() );

            foreach ( $master_array as $element_id => $element_info ) {
                // determines whether the widget should be generated or not
                $element_info['is_widget']    = isset( $element_info['is_widget'] ) && $element_info['is_widget'] === false ? false : true;
                // determines whether the shortcode should be added or not
                $element_info['is_shortcode'] = isset( $element_info['is_shortcode'] ) && $element_info['is_shortcode'] === false ? false : true;
                // determines whether the an element in vc should be generated or not
                $element_info['is_vc']        = isset( $element_info['is_vc'] ) && $element_info['is_vc'] === false ? false : true;

                // make sure icon is set
                $element_info['icon']         = isset( $element_info['icon'] ) && ! empty( $element_info['icon'] ) ? $element_info['icon'] : 'dashicons dashicons-admin-generic';

                // make sure key in master array is base name of elements, in this case we can find elements in this array faster
                $this->master_array[ $element_info['base'] ] = $element_info;
            }
        }

        return $this->master_array;
    }

    /**
     * Register all allowed widgets
     */
    public function add_widgets() {

        $master_array = $this->get_master_array();

        foreach ( $master_array as $element_id => $element_info ) {

            // Add a widget if it was allowed
            if( $element_info['is_widget'] ) {
                $widget_info = $this->generate_widget_array( $element_info );
                global $wp_widget_factory;
                $wp_widget_factory->widgets[ $element_info['base'] ] = new Auxin_Widget( $widget_info );
            }

        }
    }


    /**
     * Adds all collected shortcodes
     */
    private function add_shortcodes() {

        $shortcode_array_list = $this->get_master_shortcode_array();

        foreach ( $shortcode_array_list as $shortcode_index => $shortcode_array ) {
            add_shortcode( $shortcode_array['base'], $shortcode_array['auxin_output_callback'] );
        }
    }

    protected function remove_empty_nodes_recuresively( $array ){
        $cleared_array = array();

        foreach( $array as $key => $node ) {
            if( is_array( $node ) ){
                $cleared_array[ $key ] = $this->remove_empty_nodes_recuresively( $node );
            } elseif ( ! empty( $node ) || $node === false ){
                $cleared_array[ $key ] = $array[ $key ];
            }
        }

        return $cleared_array;
    }


    /**
     * Get list of allowed shortcodes
     */
    public function get_master_shortcode_array(){
        $this->get_master_array();

        if( empty( $this->master_shortcode_array ) ){
            foreach ( $this->master_array as $element_id => $element_info ) {
                // Collect the shortcode if it was allowed
                if( $element_info['is_shortcode'] ){
                    $this->master_shortcode_array[] = $this->generate_shortcode_array( $element_info );
                }
            }
        }

        return $this->master_shortcode_array;
    }


    /**
     * Sanitize and proper
     *
     * @param  [type] $element_info [description]
     * @return [type]               [description]
     */
    protected function sanitize_element_info( $element_info ){

    }


    /**
     * Generates shortcode info
     */
    protected function generate_shortcode_array( $element_info ){

        $shortcode_array['base']                  = $element_info['base'];
        $shortcode_array['is_shortcode']          = $element_info['is_shortcode'];

        $shortcode_array['auxin_output_callback'] = $element_info['auxin_output_callback'];

        foreach ( $element_info['params'] as $param_id => $param ) {
            if( isset( $param['def_value'] ) ){
                $shortcode_array['params'][ $param['param_name'] ] = $param['def_value'];
            } else {
                $shortcode_array['params'][ $param['param_name'] ] = '';
            }
        }

        return $shortcode_array;
    }


    /**
     * Generates widget info
     */
    protected function generate_widget_array( $element_info ){
        $widget_array = array();

        $widget_array['base_ID']    = $element_info['base'];
        $widget_array['name']       = $element_info['name'];
        $widget_array['is_widget']  = $element_info['is_widget'];

        /**
         * This filter makes the others able to override element output function
         */
        $widget_array['auxin_output_callback']  = apply_filters( 'auxin_element_output_callback', $element_info['auxin_output_callback'], 'widget' );
        $widget_array['args']['description']    = $element_info['description'];
        $widget_array['args']['panels_groups']  = array('auxin');

        if( ! empty( $element_info['icon'] ) ) {
            $widget_array['args']['panels_icon'] = $element_info['icon'];
        }

        foreach ( $element_info['params'] as $param_id => $param ) {
            $widget_params = array();

            $widget_params['name']  = $param['heading'];
            $widget_params['id']    = $param['param_name'];
            $widget_params['type']  = $param['type'];

            if( !empty( $param['std'] ) ) {
                $widget_params['value']  = $param['std'];
            }
            elseif( !empty( $param['def_value'] ) ) {
                $widget_params['value']  = $param['def_value'];
            }
            elseif( 1 ) {
                $widget_params['value']  = isset( $param['value'] ) ? $param['value'] : '';
            }
            if( $widget_params['type'] == 'aux_visual_select' ) {
                $widget_params['choices']  = $param['choices'];
            }
            if( isset( $param['description'] ) ) {
                $widget_params['description']  = $param['description'];
            }
            if( isset( $param['dependency'] ) ) {
                $widget_params['dependency']  = $param['dependency'];
            }
            // special param for aux_taxonomy field type
            if( isset( $param['taxonomy'] ) ) {
                $widget_params['taxonomy']  = $param['taxonomy'];
            }
            // TODO: It shoould convert to array an array when dependency js writes
            // $widget_params['dependency']  = array( $param['dependency'] );

            if( in_array( $param['type'], array( 'select', 'dropdown', 'aux_select2_multiple', 'aux_select2_single' ) ) ) {
                $widget_params['options'] = $param['value'];
            }

            // lets set a key for each param, for improving search in array
            $widget_array['params'][ $widget_params['id'] ] = $widget_params;
        }

        return $widget_array;
    }


    /**
     * Adds all defined elements to vc
     */
    public function vc_mapper(){
        $master_array = $this->get_master_array();

        foreach ( $master_array as $array_node ) {
            if( $array_node['is_vc'] !== false ) {
                $array_node['params'] = $this->exchange_vc_fields( $array_node['params'] );
                vc_map( $this->make_vc_compatible( $array_node ) );
            }
        }
    }

    /**
     * Get the params array and flip the value array of its value key
     */
    public function exchange_vc_fields( $params ){
        $result = array();

        foreach ( $params as $params_cell => $params_value ) {
            if( in_array( $params_value['type'], array( 'aux_visual_select', 'dropdown', 'aux_select2_multiple', 'aux_select2_single' ) ) ){
                $params_value['value'] = ! empty( $params_value['value']     ) ? $params_value['value']     : '';
                $params_value['std'  ] = ! empty( $params_value['def_value'] ) ? $params_value['def_value'] : $params_value['value'];

                if( $params_value['type' ] == 'dropdown' ){
                    $params_value['value'] = array_flip( $params_value['value'] );
                }
            }
            $result[] = $params_value;
        }

        return $result;
    }


    /**
     * Removes all empty array keys and appends some special fields for visual composer
     */
    protected function make_vc_compatible( $element_config ){

        // Removes all empty array keys
        $element_config = $this->remove_empty_nodes_recuresively( $element_config );

        // Appending some special fields for visual composer
        if( isset( $element_config['params'] ) ){
            $element_config['params'][] = array(
                'type'          => 'css_editor',
                'heading'       => __( 'CSS', 'auxin-elements' ),
                'param_name'    => 'vc_css',
                'group'         => __( 'Design options', 'auxin-elements' )
            );
        }

        return $element_config;
    }


    /**
     * Return an instance of this class.
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

}

