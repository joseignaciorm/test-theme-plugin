<?php
defined( 'ABSPATH' ) or die();

class NarTrans_researcher{

    const POST_TYPE = 'researcher';

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
        $singular_name = __('Investigador', 'nar-trans-data');
        $general_name = __( 'Investigadores', 'nar-trans-data' );

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
                //'custom-fields',
                'title',
                'editor',
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
    $meta_boxes[] = array(
      'id' => 'id_old',
      'title' => __('Datos personalizados investigadores','nar-trans-data'),
      'post_types' => array( self::POST_TYPE ),
      'context' => 'advanced',
      'priority' => 'default',
      'autosave' => false,
      'fields' => array(
          
          [
              'id' => 'email_id',
              'name' => __( 'Email', 'nar-trans-data' ),
              'type' => 'email',
              'columns' => 4,
          ],
          [
              'id' => 'phone_number_id',
              'name' => __( 'Número de teléfono', 'nar-trans-data' ),
              'type' => 'text',
              'columns' => 4,
          ],
          [
              'id' => 'biografy_id',
              'name' => __( 'Biografía', 'nar-trans-data' ),
              'type' => 'textarea',
              'columns' => 4,
          ],
          [
              'id' => 'webpage_id',
              'name' => __( 'Web Page', 'nar-trans-data' ),
              'type' => 'url',
              'columns' => 4,
          ],
          [
            'id' => 'facebook_id',
            'name' => __( 'Facebook', 'nar-trans-data' ),
            'type' => 'url',
            'columns' => 4,
          ],
          [
            'id' => 'twitter_id',
            'name' => __( 'Twitter', 'nar-trans-data' ),
            'type' => 'url',
            'columns' => 4,
          ],
          [
            'id' => 'gplus_id',
            'name' => __( 'Gmail Plus', 'nar-trans-data' ),
            'type' => 'url',
            'columns' => 4,
          ],
          [
            'id' => 'linkedin_id',
            'name' => __( 'LinkedIn', 'nar-trans-data' ),
            'type' => 'url',
            'columns' => 4,
          ],
          [
            'id' => 'academic_position_id',
            'name' => __( 'Posición académica', 'nar-trans-data' ),
            'type' => 'text',
            'columns' => 4,
          ],
          [
            'id' => 'avatar_id',
            'name' => __( 'Avatar', 'nar-trans-data' ),
            'type' => 'image',
            'columns' => 4,
            'max_file_uploads' => 1,
          ],
      ),
  );
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

if(!function_exists('NarTrans_researcher')){
    /**
     * Función para llamar a la instancia de la clase.
     */
  function NarTrans_researcher(){
    return NarTrans_researcher::get_instance();
  }
}

// Iniciar la clase
NarTrans_researcher();


?>