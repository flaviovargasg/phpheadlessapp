<?php
// Register Custom Post Type - movie
function custom_post_type() {

	$labels = array(
		'name'                  => _x( 'Movies', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Movie', 'Post Type Singular Name', 'text_domain' ),

    //puede haber más campos con más etiquetas
	);
	$args = array(
		'label'                 => __( 'Movie', 'text_domain' ),
		'description'           => __( 'Post Type Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array('title', 'thumbnail'),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'show_in_rest' => true,  //--> importante
	);
	register_post_type( 'movie', $args ); //esto es el slug que aparece en el rest


}
add_action( 'init', 'custom_post_type', 0 );


// Register Custom Taxonomy
function genero() {

	$labels = array(
		'name'                       => _x( 'Géneros', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Género', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Taxonomy', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Items list', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
         'show_in_rest' => true,  //--> importante
	);
	register_taxonomy( 'genero', array( 'movie' ), $args );

}
add_action( 'init', 'genero', 0 );


/** registro de campos*/

add_action( 'cmb2_init', /*'cmb2_admin_init',*/ 'movies_register_demo_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function movies_register_demo_metabox() {
	$prefix = 'movies_';

	/*
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Campos asociados', 'cmb2' ),
		'object_types'  => array( 'movie', ), // Post type
  'show_in_rest' => WP_REST_Server::READABLE,  //pero para que aparezca realmente en REST el hook debe ser cmb2_init no cmb2_admin_init
	) );


/** campo título*/
	$cmb->add_field( array(
		'name'      	 => esc_html__( 'Título', 'cmb2' ),
		'id'         	 => $prefix . 'titulo',
		'type'      	 => 'text_medium',


	) );

/** campo resumen */
	$cmb->add_field( array(
		'name'			   => esc_html__('Resumen', 'cmb2'),
		'id'					 => $prefix . 'resumen',
		'type'			 	 => 'textarea_small'

	));

/** campo director */
	$cmb->add_field( array(
		'name'       => esc_html__( 'Director', 'cmb2' ),
		'id'         => $prefix . 'director',
		'type'       => 'text',

	) );

/** campo duración */
$cmb->add_field( array(
	'name' 				=> esc_html__('Duración', 'cmb2'),
	'id' 					=> $prefix . 'duracion',
	'type' 				=> 'text_small',
	'attributes' 	=> array(
			 'type' 	=> 'number',
			 'format' => 'MM',
			 'min' 		=> '1'
	),
) );

/** campo año */
$cmb->add_field( array(
	'name' 				=> esc_html__('Año', 'cmb2'),
	'id' 					=> $prefix . 'year',
	'type' 				=> 'text_small',
	'attributes' 	=> array(
			 'type' 	=> 'number',
			 'min' 		=> '1'

	),
) );

/** campo trailer */
$cmb->add_field( array(
	'name'       => esc_html__( 'Trailer', 'cmb2' ),
	'id'         => $prefix . 'trailer',
	'type'			 => 'text_small'
	/*'format' 		 => 'url',*/
));

}


/** registrar campos en la salida REST */

add_action( 'rest_api_init', 'register_fields' );

function register_fields() {
  //por ejemplo añadimos el campo director
    register_rest_field( 'movie',
        'director',
        array(
            'get_callback'    => 'get_director',
            'update_callback' => null,
            'schema'          => null,
        )
    );

  //añadimos el nombre de la taxonomia género
	register_rest_field( 'movie',
      'genero_nombre',
      array(
          'get_callback'    => 'get_genero_nombre',
          'update_callback' => null,
          'schema'          => null,
      )
  );

	// título en REST
  register_rest_field( 'movie',
      'titulo',
      array(
          'get_callback'    => 'get_titulo',
          'update_callback' => null,
          'schema'          => null,
					'show_in_rest' => WP_REST_Server::READABLE,
      )
  );

//resumen en REST
	register_rest_field( 'movie',
      'resumen',
      array(
          'get_callback'    => 'get_resumen',
          'update_callback' => null,
          'schema'          => null,
      )
  );
/*
//duración
	register_rest_field( 'movie',
      'duracion',
      array(
          'get_callback'    => 'get_duracion',
          'update_callback' => null,
          'schema'          => null,
      )
	); */

  //de hecho podriamos añadir cualquier otro campo
}

function get_director( $object, $field_name, $request ) {
  //object['id'] es el id del contenido
    return get_post_meta( $object[ 'id' ], "movies_director", true );
}

function get_titulo ($object, $field_name, $request) {
	return get_post_meta( $object[ 'id' ], "movies_titulo", true );
}

function get_resumen ($object, $field_name, $request) {
	return get_post_meta( $object[ 'id' ], "resumen", true );
}

function get_genero_nombre( $object, $field_name, $request ) {
  //en el codex se explica como obtener un término de la taxonomia
  $terms=get_the_terms( $object[ 'id' ],"genero");
  //suponemos que sólo hay uno
  if(count($terms)>0) return $terms[0]->name;
}








/*** código original del archivo */
/**
 * Reviewer functions and definitions.
 *
 * @link https://codex.wordpress.org/Functions_File_Explained
 *
 * @package Reviewer
 */

if ( ! function_exists( 'reviewer_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function reviewer_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Reviewer, use a find and replace
	 * to change 'reviewer' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'reviewer', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/*/

	 add_theme_support( 'post-thumbnails', array ('post', 'movie'));
	 set_post_thumbnail_size( 200, 300, true );

	// Featured Post Main Thumbnail on the front page & single page template
	add_image_size( 'reviewer-large-thumbnail', 340, 500, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary'	=> esc_html__( 'Primary Menu', 'reviewer' )
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'gallery',
		'caption',
	) );

    add_theme_support( 'custom-logo', array(
	   'height'      => 50,
	   'width'       => 300,
	   'flex-width'  => true,
	   'flex-height' => true,
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', reviewer_fonts_url() ) );

}
endif; // reviewer_setup
add_action( 'after_setup_theme', 'reviewer_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function reviewer_content_width() {

	$GLOBALS['content_width'] = apply_filters( 'reviewer_content_width', 720 );

}
add_action( 'after_setup_theme', 'reviewer_content_width', 0 );

/* Custom Excerpt Length
==================================== */

function reviewer_new_excerpt_length($length) {
	return 40;
}
add_filter('excerpt_length', 'reviewer_new_excerpt_length');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function reviewer_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Main Sidebar', 'reviewer' ),
		'id'            => 'sidebar-main',
		'description'   => esc_html__( 'This is the main sidebar area that appears on all pages', 'reviewer' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<p class="widget-title">',
		'after_title'   => '</p>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer: Column 1', 'reviewer' ),
		'id'            => 'sidebar-footer-1',
		'description'   => esc_html__( 'This is displayed in the footer of the website. By default has a width of 275px.', 'reviewer' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<p class="widget-title">',
		'after_title'   => '</p>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer: Column 2', 'reviewer' ),
		'id'            => 'sidebar-footer-2',
		'description'   => esc_html__( 'This is displayed in the footer of the website. By default has a width of 275px.', 'reviewer' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<p class="widget-title">',
		'after_title'   => '</p>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer: Column 3', 'reviewer' ),
		'id'            => 'sidebar-footer-3',
		'description'   => esc_html__( 'This is displayed in the footer of the website. By default has a width of 275px.', 'reviewer' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<p class="widget-title">',
		'after_title'   => '</p>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer: Column 4', 'reviewer' ),
		'id'            => 'sidebar-footer-4',
		'description'   => esc_html__( 'This is displayed in the footer of the website. By default has a width of 275px.', 'reviewer' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<p class="widget-title">',
		'after_title'   => '</p>',
	) );

}
add_action( 'widgets_init', 'reviewer_widgets_init' );


if ( ! function_exists( 'reviewer_fonts_url' ) ) :
/****************************** los campos personalizados *************/


/**
 * Register Google fonts for Reviewer.
 *
 * Create your own reviewer_fonts_url() function to override in a child theme.
 *
 * @since Reviewer 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function reviewer_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Roboto, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'reviewer' ) ) {
		$fonts[] = 'Roboto:300,400,500,700';
	}

	/* translators: If there are characters in your language that are not supported by Lato, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'reviewer' ) ) {
		$fonts[] = 'Lato:300,300italic,400,400italic,700';
	}

	/* translators: If there are characters in your language that are not supported by Open Sans, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'reviewer' ) ) {
		$fonts[] = 'Open+Sans:400,400italic,600,600italic,700';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), '//fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Enqueue scripts and styles.
 */
function reviewer_scripts() {

	wp_enqueue_style( 'reviewer-style', get_stylesheet_uri() );

	// Add Genericons font.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.3.1' );

	wp_enqueue_script(
		'jquery-mmenu',
		get_template_directory_uri() . '/js/jquery.mmenu.all.min.js',
		array('jquery'),
		null
	);

	wp_enqueue_script(
		'jquery-superfish',
		get_template_directory_uri() . '/js/superfish.min.js',
		array('jquery'),
		null
	);

	wp_enqueue_script( 'reviewer-script', get_template_directory_uri() . '/js/reviewer.js', array( 'jquery' ), '20150825', true );

	// Loads our default Google Webfont
	wp_enqueue_style( 'reviewer-webfonts', reviewer_fonts_url(), array(), null, null );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'reviewer_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load plugin enhancement file to display admin notices.
 */
require get_template_directory() . '/inc/plugin-enhancements.php';

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Reviewer 1.0
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function reviewer_widget_tag_cloud_args( $args ) {
	$args['largest'] = 1;
	$args['smallest'] = 1;
	$args['unit'] = 'em';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'reviewer_widget_tag_cloud_args' );
