<?php

defined( 'ABSPATH' ) or die();

class NarTrans_activity{

    const POST_TYPE = 'activity';

    private static $instance = NULL;
  
  /**
     * Constructor
     */
function __construct(){

    // Acción registrar CPT
    add_action('init', [ $this,'register_post_type' ] );

    // Acción registrar taxonomías
    add_action( 'init', [ $this,'register_taxonomies' ] );

    // Filtro metaboxes
    add_filter( 'rwmb_meta_boxes', [ $this,'register_metaboxes' ], 10, 1 );
        
    }


  /**
   * Registra el CPT
   */
  function register_post_type(){
        $singular_name = __('Actividad', 'nar-trans-data');
        $general_name = __( 'Actividades', 'nar-trans-data' );

        $args = array(
            'labels' => array(
                'name'               => $general_name,
                'singular_name'      => $singular_name,
                'menu_name'          => $general_name,
                'name_admin_bar'     => $singular_name,
                'add_new'            => __( 'Añadir nueva', 'nar-trans-data' ),
                'add_new_item'       => sprintf( __( 'Añadir nueva %s', 'nar-trans-data' ), $singular_name ),
                'new_item'           => sprintf( __( 'Nueva %s', 'nar-trans-data' ), $singular_name ),
                'edit_item'          => sprintf( __( 'Editar %s', 'nar-trans-data' ), $singular_name ),
                'view_item'          => sprintf( __( 'Ver %s', 'nar-trans-data' ), $singular_name ),
                'all_items'          => sprintf( __( 'Todas las %s', 'nar-trans-data' ), $general_name ),
                'search_items'       => sprintf( __( 'Buscar %s', 'nar-trans-data' ), $general_name ),
                'parent_item_colon'  => sprintf( __( '%s superior:', 'nar-trans-data' ), $singular_name ),
                'not_found'          => sprintf( __( 'No se ha encontrado %s.', 'nar-trans-data' ), $general_name ),
                'not_found_in_trash' => sprintf( __( 'No se ha encontrado %s en la papelera.', 'nar-trans-data' ), $general_name ),
            ),
            'supports' => array(
                'custom-fields',
                //'title',
                //'editor',
                //'author',
                //'thumbnail',
            ),
            'public' => true,
            'public_queryable' => false,
            'query_var' => false,
            'rewrite' => false,
            'has_archive' => false,
            'hierarchical' => false,
            'exclude_from_search' => false,
            'capability_type' => 'post',
            'menu_icon' => 'dashicons-id',
            'show_in_rest' => false,
        );
            
    register_post_type( self::POST_TYPE, $args );
  }
  
  /**
   * Registra las taxonomías
   */
  function register_taxonomies(){

    }

    /**
   * Registrar los campos personalizados
   */
  function register_metaboxes($meta_boxes){

        return $meta_boxes;
    
    }

  /**
   * Devuelve el nombre del CPT
   */
  function get_post_type(){
    return self::POST_TYPE;
    }
    

  /**
   * Devuelve una intancia de la clase, si no existe aún, la crea
   */
  static function get_instance(){
    if( ! self::$instance instanceof self )
        self::$instance = new self();

    return self::$instance;
  }

}

if(!function_exists('NarTrans_activity')){
    /**
     * Función para llamar a la instancia de la clase.
     */
  function NarTrans_activity(){
    return NarTrans_activity::get_instance();
  }
}

// Iniciar la clase
NarTrans_activity();




?>