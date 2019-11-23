<?php defined( 'ABSPATH' ) or die();

class SEMNIM_Solicitud{

    const POST_TYPE = 'solicitud';

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
        
        // Procesar la solicitud (Crear post_title, usuario, etc.)
        add_action( 'save_post', [ $this, 'procesar_solicitud' ], 10, 3 );

        // Registrar los roles que usa esta clase
        add_action( 'plugins_loaded', [ $this, 'register_roles'] );

        add_shortcode( 'si2_formulario_solicitud', [ $this, 'shortcode_formulario_solicitud'] );
        add_shortcode( 'si2_formulario_usuario', [ $this, 'shortcode_formulario_usuario'] );
    }

    function shortcode_formulario_solicitud($atts, $content){
        return do_shortcode(
            '[mb_frontend_form 
                id="'.self::POST_TYPE.'_fields" 
                submit_button="'.__('Enviar solicitud','semnim-data').'"
            ]'
        );
    }

    function shortcode_formulario_usuario($atts, $content){
        return do_shortcode(
            '[mb_user_profile_info 
                id="' . self::POST_TYPE. '_user_fields" 
                form_id="update-profile-form" 
                label_submit="'.__('Guardar','semnim-data').'"
                
                id_email="user_email"
                label_email="'.__('Correo electrónico','semnim-data').'"
                
                id_password="user_password"
                label_password="'.__('Contraseña','semnim-data').'"
                
                id_password2="user_password2"
                label_password2="'.__('Repetir contraseña','semnim-data').'"
                
                confirmation="'.__('Perfil actualizado.','semnim-data').'"
            ]');
    }

    /**
     * Registra los roles de socios
     */
    function register_roles(){
        
        // Socio numerario
        add_role(
            'socio_numerario',
            __('Socio Numerario','semnim-data'),
            array(
                'read' => true,
                'delete_posts' => true,
                'edit_posts' => true,
            )
        );

        // Socio Residente
        add_role(
            'socio_residente',
            __('Socio Residente','semnim-data'),
            array(
                'read' => true,
                'delete_posts' => true,
                'edit_posts' => true,
            )
        );

        // Socio Emérito
        add_role(
            'socio_emerito',
            __('Socio Emérito','semnim-data'),
            array(
                'read' => true,
                'delete_posts' => true,
                'edit_posts' => true,
            )
        );

    }

  /**
   * Registra el CPT
   */
  function register_post_type(){
        $singular_name = __('Solicitud', 'semnim-data');
        $general_name = __( 'Solicitudes', 'semnim-data' );

        $args = array(
            'labels' => array(
                'name'               => $general_name,
                'singular_name'      => $singular_name,
                'menu_name'          => $general_name,
                'name_admin_bar'     => $singular_name,
                'add_new'            => __( 'Añadir nueva', 'semnim-data' ),
                'add_new_item'       => sprintf( __( 'Añadir nueva %s', 'semnim-data' ), $singular_name ),
                'new_item'           => sprintf( __( 'Nueva %s', 'semnim-data' ), $singular_name ),
                'edit_item'          => sprintf( __( 'Editar %s', 'semnim-data' ), $singular_name ),
                'view_item'          => sprintf( __( 'Ver %s', 'semnim-data' ), $singular_name ),
                'all_items'          => sprintf( __( 'Todas las %s', 'semnim-data' ), $general_name ),
                'search_items'       => sprintf( __( 'Buscar %s', 'semnim-data' ), $general_name ),
                'parent_item_colon'  => sprintf( __( '%s superior:', 'semnim-data' ), $singular_name ),
                'not_found'          => sprintf( __( 'No se ha encontrado %s.', 'semnim-data' ), $general_name ),
                'not_found_in_trash' => sprintf( __( 'No se ha encontrado %s en la papelera.', 'semnim-data' ), $general_name ),
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

        $singular_name = __('Estado','semnim-data');
        $plural_name = __('Estados','semnim-data');
        register_taxonomy( 'estado_solicitud', self::POST_TYPE, array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'              => $plural_name,
                'singular_name'     => $singular_name,
                'search_items'      => sprintf( __( 'Buscar %s', 'semnim-data' ), $singular_name),
                'all_items'         => sprintf( __( 'Todos los %s', 'semnim-data' ), $plural_name),
                'parent_item'       => sprintf( __( '%s superior', 'semnim-data' ), $singular_name),
                'parent_item_colon' => sprintf( __( '%s superior:', 'semnim-data' ), $singular_name),
                'edit_item'         => sprintf( __( 'Editar %s', 'semnim-data' ), $singular_name),
                'update_item'       => sprintf( __( 'Actualizar %s', 'semnim-data' ), $singular_name),
                'add_new_item'      => sprintf( _x( 'Nuevo %s', 'femenino', 'semnim-data' ), $singular_name),
                'new_item_name'     => sprintf( _x( 'Nuevo nombre de %s', 'semnim-data' ), $singular_name),
                'menu_name'         => $plural_name,
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
    ) );

    $singular_name = __('Situación laboral','semnim-data');
        $plural_name = __('Situaciones laborales','semnim-data');
        register_taxonomy( 'situacion_laboral', self::POST_TYPE, array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'              => $plural_name,
                'singular_name'     => $singular_name,
                'search_items'      => sprintf( __( 'Buscar %s', 'semnim-data' ), $singular_name),
                'all_items'         => sprintf( __( 'Todos los %s', 'semnim-data' ), $plural_name),
                'parent_item'       => sprintf( __( '%s superior', 'semnim-data' ), $singular_name),
                'parent_item_colon' => sprintf( __( '%s superior:', 'semnim-data' ), $singular_name),
                'edit_item'         => sprintf( __( 'Editar %s', 'semnim-data' ), $singular_name),
                'update_item'       => sprintf( __( 'Actualizar %s', 'semnim-data' ), $singular_name),
                'add_new_item'      => sprintf( _x( 'Nuevo %s', 'femenino', 'semnim-data' ), $singular_name),
                'new_item_name'     => sprintf( _x( 'Nuevo nombre de %s', 'semnim-data' ), $singular_name),
                'menu_name'         => $plural_name,
            ),
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
    ) );
    
    $singular_name = __('Ámbito de trabajo','semnim-data');
        $plural_name = __('Ámbito de trabajo','semnim-data');
        register_taxonomy( 'ambito_trabajo', self::POST_TYPE, array(
            'hierarchical'      => true,
            'labels'            => array(
                'name'              => $plural_name,
                'singular_name'     => $singular_name,
                'search_items'      => sprintf( __( 'Buscar %s', 'semnim-data' ), $singular_name),
                'all_items'         => sprintf( __( 'Todos los %s', 'semnim-data' ), $plural_name),
                'parent_item'       => sprintf( __( '%s superior', 'semnim-data' ), $singular_name),
                'parent_item_colon' => sprintf( __( '%s superior:', 'semnim-data' ), $singular_name),
                'edit_item'         => sprintf( __( 'Editar %s', 'semnim-data' ), $singular_name),
                'update_item'       => sprintf( __( 'Actualizar %s', 'semnim-data' ), $singular_name),
                'add_new_item'      => sprintf( _x( 'Nuevo %s', 'femenino', 'semnim-data' ), $singular_name),
                'new_item_name'     => sprintf( _x( 'Nuevo nombre de %s', 'semnim-data' ), $singular_name),
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

        $tratamiento_options = [
            //'Pr.' => __( 'Prof.', 'semnim-data' ),
            //'Pra.' => __( 'Profe.', 'semnim-data' ),
            //'Dr.' => __( 'Dr.', 'semnim-data' ),
            //'Dra.' => __( 'Dra.', 'semnim-data' ),
            'D.' => __( 'D.', 'semnim-data' ),
            'Dña.' => __( 'Dña.', 'semnim-data' ),
        ];

        $grado_academico_options =[
            'grado' => __( 'Grado', 'semnim-data' ),
            'master' => __( 'Máster', 'semnim-data' ),
            'doctorado' => __( 'Doctorado', 'semnim-data' ),
        ];

        // CPT
        // Campos públicos
        $meta_boxes[] = array(
            'id' => self::POST_TYPE . '_fields',
            'title' => __('Campos de la solicitud','ugrcat'),
            'post_types' => array( self::POST_TYPE ),
            'context' => 'advanced',
            'priority' => 'default',
            'autosave' => false,
            'submit_button' => __( 'Enviar solicitud', 'semnim-data' ),
            'confirmation' => __( 'Solicitud registrada. Pronto recibirá un email con el resultado de su solicitud. Gracias.', 'semnim-data' ),
            'fields' => array(
                [
                    'id' => 'tratamiento',
                    'name' => __( 'Tratamiento', 'semnim-data' ),
                    'type' => 'select_advanced',
                    'options' => $tratamiento_options,
                    'columns' => 3,
                ],
                [
                    'id' => 'nombre',
                    'name' => __( 'Nombre', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 3,
                ],
                [
                    'id' => 'apellido1',
                    'name' => __( 'Primer Apellido', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 3,
                ],
                [
                    'id' => 'apellido2',
                    'name' => __( 'Segundo apellido', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 3,
                ],
                [
                    'id' => 'dni',
                    'name' => __( 'DNI', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 3,
                ],
                [
                    'id' => 'telefono',
                    'name' => __( 'Teléfono de contacto', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 3,
                ],
                'email' => [
                    'id' => 'email',
                    'name' => __( 'Correo electrónico', 'semnim-data' ),
                    'type' => 'email',
                    'columns' => 6,
                ],
                [
                    'id' => 'domicilio_postal',
                    'name' => __( 'Domicilio postal', 'semnim-data' ),
                    'type' => 'group',
                    'columns' => 12,
                    'after' => '<hr /><br />',
                    'fields' => [
                        [
                            'id' => 'calle',
                            'name' => __( 'Calle', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 9,
                        ],
                        [
                            'id' => 'numero',
                            'name' => __( 'Número', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'codigo_postal',
                            'name' => __( 'Código postal', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'localidad',
                            'name' => __( 'Localidad', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'provincia',
                            'name' => __( 'Provincia', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'pais',
                            'name' => __( 'País', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                    ],
                    'columns' => 12,
                ],
                [
                    'id' => 'grado_academico',
                    'name' => __( 'Grado académico', 'semnim-data' ),
                    'type' => 'select_advanced',
                    'options' => $grado_academico_options,
                    'columns' => 3,
                ],
                [
                    'id' => 'titulacion',
                    'name' => __( 'Titulación', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 9,
                ],
                [
                    'id' => 'situacion_laboral',
                    'name' => __( 'Situación laboral', 'semnim-data' ),
                    'type' => 'taxonomy',
                    'taxonomy' => 'situacion_laboral',
                    'multiple' => true,
                    'columns' => 6,
                ],
                [
                    'id' => 'ambito_trabajo',
                    'name' => __( 'Ámbito de trabajo', 'semnim-data' ),
                    'type' => 'taxonomy',
                    'taxonomy' => 'ambito_trabajo',
                    'multiple' => true,
                    'columns' => 6,
                ],
                [
                    'id' => 'centro_trabajo',
                    'name' => __( 'Centro de trabajo', 'semnim-data' ),
                    'type' => 'group',
                    'clone' => true,
                    'clone_as_multiple' => true,
                    'columns' => 12,
                    'after' => '<hr /><br />',
                    'add_button' => __( '+ Añadir centro', 'semnim-data' ),
                    'fields' => [
                        [
                            'id' => 'servicio',
                            'name' => __( 'Servicio / Unidad', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 4,
                        ],
                        [
                            'id' => 'centro',
                            'name' => __( 'Centro', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 4,
                        ],
                        [
                            'id' => 'localidad',
                            'name' => __( 'Localidad', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 4,
                        ],
                    ],
                    'columns' => 12,
                ],
                [
                    'id' => 'formacion',
                    'name' => __( 'Formación', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 6,
                ],
                [
                    'id' => 'residencia_ini',
                    'name' => __( 'Año inicio Residencia', 'semnim-data' ),
                    'type' => 'number',
                    'columns' => 3,
                ],
                [
                    'id' => 'residencia_fin',
                    'name' => __( 'Año fin Residencia', 'semnim-data' ),
                    'type' => 'number',
                    'columns' => 3,
                ],
                [
                    'id' => 'avalista1',
                    'name' => __( 'Avalista 1', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 6,
                ],
                [
                    'id' => 'avalista2',
                    'name' => __( 'Avalista 2', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 6,
                ],
                [
                    'id' => 'iban',
                    'name' => __( 'IBAN', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 6,
                ],
                [
                    'id' => 'modalidad_revista',
                    'name' => __( 'Modalidad revista', 'semnim-data' ),
                    'type' => 'select_advanced',
                    'options' => [
                        'papel' => __( 'Papel', 'semnim-data' ),
                        'electronica' => __( 'Electrónica', 'semnim-data' ),
                    ],
                    'columns' => 3,
                ],
                [
                    'id' => 'grupo_trabajo',
                    'name' => __( 'Grupos de Trabajo', 'semnim-data' ),
                    'type' => 'group',
                    'clone' => true,
                    'clone_as_multiple' => true,
                    'columns' => 12,
                    'add_button' => __( '+ Añadir grupo', 'semnim-data' ),
                    'after' => '<hr /><br />',
                    'fields' => [
                        [
                            'id' => 'nombre',
                            'name' => __( 'Nombre', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'cargo',
                            'name' => __( 'Cargo', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'inicio',
                            'name' => __( 'Año de inicio', 'semnim-data' ),
                            'type' => 'number',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'fin',
                            'name' => __( 'Año de finalización', 'semnim-data' ),
                            'desc' => __( 'En blanco para «actualmente».', 'semnim-data' ),
                            'type' => 'number',
                            'columns' => 3,
                        ],
                    ],
                ],
    
                [
                    'id' => 'junta_directiva',
                    'name' => __( 'Juntas Directivas', 'semnim-data' ),
                    'type' => 'group',
                    'clone' => true,
                    'clone_as_multiple' => true,
                    'add_button' => __( '+ Añadir junta', 'semnim-data' ),
                    'columns' => 12,
                    'after' => '<hr /><br />',
                    'fields' => [
                        [
                            'id' => 'nombre',
                            'name' => __( 'Nombre', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'cargo',
                            'name' => __( 'Cargo', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'inicio',
                            'name' => __( 'Año de inicio', 'semnim-data' ),
                            'type' => 'number',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'fin',
                            'name' => __( 'Año de finalización', 'semnim-data' ),
                            'desc' => __( 'En blanco para «actualmente».', 'semnim-data' ),
                            'type' => 'number',
                            'columns' => 3,
                        ],
                    ],
                ],
                
            ),
        );
        // Campos privados
        $meta_boxes[] = array(
            'id' => self::POST_TYPE . '_private_fields',
            'title' => __('Información interna','ugrcat'),
            'post_types' => array( self::POST_TYPE ),
            'context' => 'advanced',
            'priority' => 'default',
            'autosave' => false,
            'fields' => array(
                [
                    'id' => 'estado_solicitud',
                    'name' => __( 'Estado de la solicitud', 'semnim-data' ),
                    'type' => 'taxonomy',
                    'taxonomy' => 'estado_solicitud',
                    'columns' => 4,
                ],
                [
                    'id' => 'saludo',
                    'name' => __( 'Saludo', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 4,
                ],
                [
                    'id' => 'num_socio',
                    'name' => __( 'Número de socio', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 4,
                ],
                [
                    'id' => 'tipo_socio',
                    'name' => __( 'Tipo de socio', 'semnim-data' ),
                    'type' => 'select_advanced',
                    'options' => [
                        'socio_numerario' => __( 'Numerario', 'semnim-data' ),
                        'socio_residente' => __( 'Residente', 'semnim-data' ),
                        'socio_emerito' => __( 'Emérito', 'semnim-data' ),
                    ],
                    'columns' => 4,
                ],
                [
                    'id' => 'fecha_actualizacion_datos',
                    'name' => __( 'Fecha de actualización de datos', 'semnim-data' ),
                    'type' => 'date',
                    'columns' => 4,
                ],
            ),
        );

        $situacion_laboral_options = [];
        $terms = get_terms([ 'taxonomy' => 'situacion_laboral', 'hide_empty' => false ]);
        if(count($terms)) foreach( $terms as $term ){
            $situacion_laboral_options[$term->slug] = $term->name;
        }

        $terms = get_terms([ 'taxonomy' => 'ambito_trabajo', 'hide_empty' => false ]);
        $ambito_trabajo_options = [];
        if(count($terms)) foreach( $terms as $term ){
            $ambito_trabajo_options[$term->slug] = $term->name;
        }

        // Usuario
        // Campos públicos
        unset($solicitud_fields['email']);
        $meta_boxes[] = array(
            'id' => self::POST_TYPE . '_user_fields',
            'title' => __('Datos socio','ugrcat'),
            'type'  => 'user',
            'context' => 'advanced',
            'priority' => 'default',
            'autosave' => false,
            'submit_button' => __( 'Enviar solicitud', 'semnim-data' ),
            'confirmation' => __( 'Solicitud registrada. Pronto recibirá un email con el resultado de su solicitud. Gracias.', 'semnim-data' ),
            'fields' => array(
                [
                    'id' => 'tratamiento',
                    'name' => __( 'Tratamiento', 'semnim-data' ),
                    'type' => 'select_advanced',
                    'options' => $tratamiento_options,
                    'columns' => 3,
                ],
                [
                    'id' => 'nombre',
                    'name' => __( 'Nombre', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 3,
                ],
                [
                    'id' => 'apellido1',
                    'name' => __( 'Primer Apellido', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 3,
                ],
                [
                    'id' => 'apellido2',
                    'name' => __( 'Segundo apellido', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 3,
                ],
                [
                    'id' => 'dni',
                    'name' => __( 'DNI', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 3,
                ],
                [
                    'id' => 'telefono',
                    'name' => __( 'Teléfono de contacto', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 3,
                ],
                [
                    'id' => 'domicilio_postal',
                    'name' => __( 'Domicilio postal', 'semnim-data' ),
                    'type' => 'group',
                    'columns' => 12,
                    'after' => '<hr /><br />',
                    'fields' => [
                        [
                            'id' => 'calle',
                            'name' => __( 'Calle', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 9,
                        ],
                        [
                            'id' => 'numero',
                            'name' => __( 'Número', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'codigo_postal',
                            'name' => __( 'Código postal', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'localidad',
                            'name' => __( 'Localidad', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'provincia',
                            'name' => __( 'Provincia', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'pais',
                            'name' => __( 'País', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                    ],
                    'columns' => 12,
                ],
                [
                    'id' => 'grado_academico',
                    'name' => __( 'Grado académico', 'semnim-data' ),
                    'type' => 'select_advanced',
                    'options' => $grado_academico_options,
                    'columns' => 3,
                ],
                [
                    'id' => 'titulacion',
                    'name' => __( 'Titulación', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 9,
                ],
                [
                    'id' => 'situacion_laboral',
                    'name' => __( 'Situación laboral', 'semnim-data' ),
                    'type' => 'select_advanced',
                    'options' => $situacion_laboral_options,
                    'multiple' => true,
                    'columns' => 3,
                ],
                [
                    'id' => 'ambito_trabajo',
                    'name' => __( 'Ámbito de trabajo', 'semnim-data' ),
                    'type' => 'select_advanced',
                    'options' => $ambito_trabajo_options,
                    'multiple' => true,
                    'columns' => 3,
                ],
                [
                    'id' => 'centro_trabajo',
                    'name' => __( 'Centro de trabajo', 'semnim-data' ),
                    'type' => 'group',
                    'clone' => true,
                    'clone_as_multiple' => true,
                    'columns' => 12,
                    'after' => '<hr /><br />',
                    'add_button' => __( '+ Añadir centro', 'semnim-data' ),
                    'fields' => [
                        [
                            'id' => 'servicio',
                            'name' => __( 'Servicio / Unidad', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'centro',
                            'name' => __( 'Centro', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'localidad',
                            'name' => __( 'Localidad', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                    ],
                ],
                [
                    'id' => 'formacion',
                    'name' => __( 'Formación', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 6,
                ],
                [
                    'id' => 'residencia_ini',
                    'name' => __( 'Año inicio Residencia', 'semnim-data' ),
                    'type' => 'number',
                    'columns' => 3,
                ],
                [
                    'id' => 'residencia_fin',
                    'name' => __( 'Año fin Residencia', 'semnim-data' ),
                    'type' => 'number',
                    'columns' => 3,
                ],
                [
                    'id' => 'avalista1',
                    'name' => __( 'Avalista 1', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 6,
                ],
                [
                    'id' => 'avalista2',
                    'name' => __( 'Avalista 2', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 6,
                ],
                [
                    'id' => 'iban',
                    'name' => __( 'IBAN', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 6,
                ],
                [
                    'id' => 'modalidad_revista',
                    'name' => __( 'Modalidad revista', 'semnim-data' ),
                    'type' => 'select_advanced',
                    'options' => [
                        'papel' => __( 'Papel', 'semnim-data' ),
                        'electronica' => __( 'Electrónica', 'semnim-data' ),
                    ],
                    'columns' => 3,
                ],
                [
                    'id' => 'grupo_trabajo',
                    'name' => __( 'Grupos de Trabajo', 'semnim-data' ),
                    'type' => 'group',
                    'clone' => true,
                    'clone_as_multiple' => true,
                    'columns' => 12,
                    'add_button' => __( '+ Añadir grupo', 'semnim-data' ),
                    'after' => '<hr /><br />',
                    'fields' => [
                        [
                            'id' => 'nombre',
                            'name' => __( 'Nombre', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'cargo',
                            'name' => __( 'Cargo', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'inicio',
                            'name' => __( 'Año de inicio', 'semnim-data' ),
                            'type' => 'number',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'fin',
                            'name' => __( 'Año de finalización', 'semnim-data' ),
                            'desc' => __( 'En blanco para «actualmente».', 'semnim-data' ),
                            'type' => 'number',
                            'columns' => 3,
                        ],
                    ],
                ],
    
                [
                    'id' => 'junta_directiva',
                    'name' => __( 'Juntas Directivas', 'semnim-data' ),
                    'type' => 'group',
                    'clone' => true,
                    'clone_as_multiple' => true,
                    'add_button' => __( '+ Añadir junta', 'semnim-data' ),
                    'columns' => 12,
                    'after' => '<hr /><br />',
                    'fields' => [
                        [
                            'id' => 'nombre',
                            'name' => __( 'Nombre', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'cargo',
                            'name' => __( 'Cargo', 'semnim-data' ),
                            'type' => 'text',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'inicio',
                            'name' => __( 'Año de inicio', 'semnim-data' ),
                            'type' => 'number',
                            'columns' => 3,
                        ],
                        [
                            'id' => 'fin',
                            'name' => __( 'Año de finalización', 'semnim-data' ),
                            'desc' => __( 'En blanco para «actualmente».', 'semnim-data' ),
                            'type' => 'number',
                            'columns' => 3,
                        ],
                    ],
                ],
            ),
        );
        
        // Campos privados
        $meta_boxes[] = array(
            'id' => self::POST_TYPE . '_private_fields',
            'title' => __('Información interna','ugrcat'),
            'context' => 'advanced',
            'priority' => 'default',
            'autosave' => false,
            'fields' => array(
                [
                    'id' => 'saludo',
                    'name' => __( 'Saludo', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 4,
                ],
                [
                    'id' => 'num_socio',
                    'name' => __( 'Número de socio', 'semnim-data' ),
                    'type' => 'text',
                    'columns' => 4,
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
     * Al guardar una solicitud, 
     * la procesa para ver si hay que crear el usuario o no
     */
    function procesar_solicitud( $post_id, $post, $update ){
        if( $post->post_type != self::POST_TYPE )
            return;
        
        // Establecer post title
        $this->generate_post_title( $post_id );

        $estados = wp_get_post_terms( $post_id, 'estado_solicitud' );
        if( count($estados) ){
            $estado = $estados[0];

            if( 'aprobada' == $estado->slug ){
                $this->aprobar_solicitud( $post_id );
            }else{
                $this->rechazar_solicitud( $post_id );
            }

        }

    }

    /**
     * Genera y actualiza el post_title 
     * a partir del nombre y apellidos del solicitante
     */
    function generate_post_title( $post_ID ){
        
        $nombre = get_post_meta( $post_ID, 'nombre', true );
        $apellido1 = get_post_meta( $post_ID, 'apellido1', true );
        $apellido2 = get_post_meta( $post_ID, 'apellido2', true );
        $post_title = trim("{$nombre} {$apellido1} {$apellido2}");

        // Elimina el action
        remove_action( 'save_post', [ $this, 'procesar_solicitud' ]);
        
        // Acualizar el post
        wp_update_post([
            'ID' => $post_ID,
            'post_title' => $post_title,
        ]);

        // Vuelve a añadir el action
        add_action( 'save_post', [ $this, 'procesar_solicitud' ], 10, 3 );
    }

    /**
     * Aprueba una solicitud:
     * - Crea un usuario
     * - Lo configura con los campos de la solicitud
     * - Notifica via email al usuario
     */
    private function aprobar_solicitud( $post_id ){

        $user_created = get_post_meta( $post_id, 'user_created', true );
        if( !empty($user_created) )
            return false;

        $post_meta = get_post_meta( $post_id );

        $password = wp_generate_password( 8, true, false );
        $email = !empty($post_meta['email'][0]) ? sanitize_email($post_meta['email'][0]) : "";
        $nombre = !empty($post_meta['nombre'][0]) ? sanitize_text_field($post_meta['nombre'][0]) : "";
        $apellido1 = !empty($post_meta['apellido1'][0]) ? sanitize_text_field($post_meta['apellido1'][0]) : "";
        $apellido2 = !empty($post_meta['apellido2'][0]) ? sanitize_text_field($post_meta['apellido2'][0]) : "";
        $tratamiento = !empty($post_meta['tratamiento'][0]) ? sanitize_text_field($post_meta['tratamiento'][0]) : "";
        $saludo = !empty($post_meta['saludo'][0]) ? sanitize_text_field($post_meta['saludo'][0]) : "";
        $tipo_socio = !empty($post_meta['tipo_socio'][0]) ? sanitize_text_field($post_meta['tipo_socio'][0]) : 'socio_numerario';

        // Crear usuario
        $user_id = wp_insert_user([
            'user_pass' => $password,
            'user_email' => $email,
            'user_login' => $email,
            'first_name' => $nombre,
            'last_name' => trim("{$apellido1} {$apellido2}"),
            'display_name' => trim("{$nombre} {$apellido1} {$apellido2}"),
            'role' => $tipo_socio,
        ]);

        if( is_wp_error($user_id) ){
            if( function_exists('SI2Notices') ){
                SI2Notices()->add('user-not-created', __('No se ha podido crear el usuario','semnim-data'), 'error' );
            }
            return;
        }else{
            if( function_exists('SI2Notices') ){
                SI2Notices()->add('user-created', __('Usuario creado','semnim-data') );
            }
        }
        
        // Guardar post_meta
        $meta_keys = [
            // Públicos
            'tratamiento',
            'nombre',
            'apellido1',
            'apellido2',
            'dni',
            'telefono',
            'email',
            'grado_academico',
            'titulacion',
            'formacion',
            'residencia_ini',
            'residencia_fin',
            'avalista1',
            'avalista2',
            'iban',
            'modalidad_revista',
            // Privados
            'saludo',
            'num_socio',
        ];
        if(count($meta_keys)) foreach( $meta_keys as $meta_key ){
            if( !empty($post_meta[$meta_key]) ) foreach( $post_meta[$meta_key] as $meta_value ){
                add_user_meta( $user_id, $meta_key, $meta_value );
            }
        }

        // Campos serializados (deserializar antes de guardar)
        $meta_keys_serialized = [
            'domicilio_postal',
            'centro_trabajo',
            'grupo_trabajo',
            'junta_directiva',
        ];
        if(count($meta_keys_serialized)) foreach( $meta_keys_serialized as $meta_key ){
            if( !empty($post_meta[$meta_key]) ) foreach( $post_meta[$meta_key] as $meta_value ){
                $meta_value = unserialize($meta_value);
                add_user_meta( $user_id, $meta_key, $meta_value );
            }
        }

        // Guardar las taxonomías como user_meta
        $taxonomies = [
            'situacion_laboral',
            'ambito_trabajo',
        ];
        if( count($taxonomies) ) foreach( $taxonomies as $taxonomy ){
            $terms = wp_get_post_terms( $post_id, $taxonomy );
            if( count($terms) ) foreach( $terms as $term ){
                add_user_meta( $user_id, $taxonomy, $term->slug );
            }
        }

        // Notificar al usuario via email
        if( function_exists('SI2Email') ){
            $login_url = wp_login_url();
            $subject = __('Solicitud de alta de socio aprobada','semnim-data');
            $content = '<p>'. sprintf(
                __('%s, su solicitud de alta como socio/a ha sido aprobada','semnim-data'),
                "{$saludo} {$tratamiento} {$nombre} {$apellido1} {$apellido2}"
            ) . '</p>';
            $content .= '<p>' . __('A continuación le mostramos los datos de acceso a la plataforma:','semnim-data') . '</p>';
            $content .= '<ul>';
                $content .= '<li><strong>'.__('Usuario:','semnim-data').'</strong> ' . $email . '</li>';
                $content .= '<li><strong>'.__('Contraseña:','semnim-data').'</strong> ' . $password . '</li>';
                $content .= '<li><strong>'.__('URL','semnim-data').'</strong> <a href="'.$login_url.'">' . $login_url . '</a></li>';
            $content .= '</ul>';
            SI2Email()->send( $email, $subject, $content );

            if( function_exists('SI2Notices') ){
                SI2Notices()->add('request-rejected', __('Solicitud rechazada','semnim-data') );
            }

        }

        // Crear el post meta que evita que se vuelva a ejecutar
        add_post_meta( $post_id, 'user_created', $user_id );

    }

    /**
     * Rechaza una solicitud
     * - Notifica via email al usuario de que ha sido rechazada
     */
    private function rechazar_solicitud( $post_id ){

        $is_rejected = get_post_meta( $post_id, 'is_rejected', true );
        if( !empty($is_rejected) )
            return false;

        $post_meta = get_post_meta( $post_id );

        if( function_exists('SI2Email') ){
            $email = !empty($post_meta['email'][0]) ? sanitize_email($post_meta['email'][0]) : "";
            $nombre = !empty($post_meta['nombre'][0]) ? sanitize_text_field($post_meta['nombre'][0]) : "";
            $apellido1 = !empty($post_meta['apellido1'][0]) ? sanitize_text_field($post_meta['apellido1'][0]) : "";
            $apellido2 = !empty($post_meta['apellido2'][0]) ? sanitize_text_field($post_meta['apellido2'][0]) : "";
            $tratamiento = !empty($post_meta['tratamiento'][0]) ? sanitize_text_field($post_meta['tratamiento'][0]) : "";
            $saludo = !empty($post_meta['saludo'][0]) ? sanitize_text_field($post_meta['saludo'][0]) : "";
    
            // Notificar al usuario via email
            $login_url = wp_login_url();
            $subject = __('Solicitud de alta de socio rechazada','semnim-data');
            $content = '<p>'. sprintf(
                __('%s, lamentamos comunicarle que su solicitud de alta como socio/a ha sido rechazada.','semnim-data'),
                "{$saludo} {$tratamiento} {$nombre} {$apellido1} {$apellido2}"
            ) . '</p>';
            SI2Email()->send( $email, $subject, $content );
        }
        
        add_post_meta( $post_id, 'is_rejected', 1 );

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

if(!function_exists('SEMNIM_Solicitud')){
    /**
     * Función para llamar a la instancia de la clase.
     */
  function SEMNIM_Solicitud(){
    return SEMNIM_Solicitud::get_instance();
  }
}

// Iniciar la clase
SEMNIM_Solicitud();


