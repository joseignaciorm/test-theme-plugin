<?php
defined( 'ABSPATH' ) or die();

class NarTrans_publications{

const POST_TYPE = 'publication';

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
    $singular_name = __('Publicación', 'nar-trans-data');
    $general_name = __( 'Publicaciones', 'nar-trans-data' );

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

    $singular_name = __('Tipo de publicación','nar-trans-data');
    $plural_name = __('Tipos de publicaciones','nar-trans-data');
    register_taxonomy( 'publication_type', self::POST_TYPE, array(
        'hierarchical'      => true,
        'labels'            => array(
            'name'              => $plural_name,
            'singular_name'     => $singular_name,
            'search_items'      => sprintf( __( 'Buscar %s', 'nar-trans-data' ), $singular_name),
            'all_items'         => sprintf( __( 'Todos los %s', 'nar-trans-data' ), $plural_name),
            'parent_item'       => sprintf( __( '%s superior', 'nar-trans-data' ), $singular_name),
            'parent_item_colon' => sprintf( __( '%s superior:', 'nar-trans-data' ), $singular_name),
            'edit_item'         => sprintf( __( 'Editar %s', 'nar-trans-data' ), $singular_name),
            'update_item'       => sprintf( __( 'Actualizar %s', 'nar-trans-data' ), $singular_name),
            'add_new_item'      => sprintf( _x( 'Nuevo %s', 'femenino', 'nar-trans-data' ), $singular_name),
            'new_item_name'     => sprintf( _x( 'Nuevo nombre de %s', 'nar-trans-data' ), $singular_name),
            'menu_name'         => $plural_name,
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
) );

}

/**
* Registrar los campos personalizados
*/

function register_metaboxes($meta_boxes){
  
    $meta_boxes[] = array(
        'id' => 'id_old',
        'title' => __('Campos personalizados de la publicación','nar-trans-data'),
        'post_types' => array( self::POST_TYPE ),
        'context' => 'advanced',
        'priority' => 'default',
        'autosave' => false,
        'fields' => array(
            
            [
                'id' => 'year_id',
                'name' => __( 'Año', 'nar-trans-data' ),
                'type' => 'number',
                'columns' => 4,
                'default_hidden' => false
            ],
            [
                'id' => 'image_id',
                'name' => __( 'Imágen', 'nar-trans-data' ),
                'type' => 'image',
                'max_file_uploads' => 1,
                'columns' => 4,
            ],
            [
                'id' => 'link_id',
                'name' => __( 'URL', 'nar-trans-data' ),
                'type' => 'url',
                'columns' => 4,
            ],
            [
                'id' => 'publication_date',
                'name' => __( 'Fecha de actualización de datos', 'nar-trans-data' ),
                'type' => 'date',
                'columns' => 4,
            ],
            
        ),
    );
return $meta_boxes;

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

if(!function_exists('NarTrans_publications')){
/**
 * Función para llamar a la instancia de la clase.
 */
function NarTrans_publications(){
return NarTrans_publications::get_instance();
}
}

// Iniciar la clase
NarTrans_publications();

?>