<?php
/**
 * @package Portfolio Press
 */

/**
 * Helper function to get options set by the Options Framework plugin
 */
if ( !function_exists( 'of_get_option' ) ) :
function of_get_option( $name, $default = false ) {

	$optionsframework_settings = get_option( 'optionsframework' );

	// Gets the unique option id
	$option_name = $optionsframework_settings['id'];

	if ( get_option($option_name) ) {
		$options = get_option($option_name);
	}

	if ( isset($options[$name]) ) {
		return $options[$name];
	} else {
		return $default;
	}
}
endif;

/**
 * Additional content to display after the options panel
 */
function portfoliopress_panel_info() { ?>
    <p style="color: #777;">
    <?php printf(
    	'Theme <a href="%s">documentation</a>.  For additional options, see <a href="%s">Portfolio+</a>.',
    	esc_url( 'http://wptheming.com/portfolio-theme' ),
    	esc_url( 'http://wptheming.com/portfolio-plus' )
    );
    ?>
    </p>
<?php }

add_action( 'optionsframework_after', 'portfoliopress_panel_info', 100 );

/**
 * Adds a body class to indicate sidebar position
 */
function portfoliopress_body_class_options( $classes ) {

	// Layout options
	$classes[] = of_get_option( 'layout','layout-2cr' );

	// Clear the menu if selected
	if ( of_get_option( 'menu_position', false ) == 'clear' ) {
		$classes[] = 'clear-menu';
	}

	return $classes;
}
add_filter( 'body_class', 'portfoliopress_body_class_options' );

/**
 * Favicon Option
 */
function portfolio_favicon() {
	$favicon = of_get_option( 'custom_favicon', false );
	if ( $favicon ) {
        echo '<link rel="shortcut icon" href="'.  $favicon  .'"/>'."\n";
    }
}
add_action( 'wp_head', 'portfolio_favicon' );

/**
 * Menu Position Option
 */
function portfolio_head_css() {

		$output = '';

		if ( of_get_option( 'header_color' ) != "#000000") {
			$output .= "#branding {background:" . of_get_option('header_color') . "}\n";
		}

		// Output styles
		if ($output <> '') {
			$output = "<!-- Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo $output;
		}
}

add_action( 'wp_head', 'portfolio_head_css' );

/**
 * Front End Customizer
 *
 * WordPress 3.4 Required
 */

if ( function_exists( 'optionsframework_init' ) ) {
	add_action( 'customize_register', 'portfoliopress_customize_register' );
}

function portfoliopress_customize_register( $wp_customize ) {

	$options = optionsframework_options();

	/* Title & Tagline */

	$wp_customize->add_setting( 'portfoliopress[logo]', array(
		'type' => 'option'
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'logo', array(
		'label' => $options['logo']['name'],
		'section' => 'title_tagline',
		'settings' => 'portfoliopress[logo]'
	) ) );

	/* Layout */

	$wp_customize->add_section( 'portfoliopress_layout', array(
		'title' => __( 'Layout', 'portfoliopress' ),
		'priority' => 100,
	) );

	$wp_customize->add_setting( 'portfoliopress[layout]', array(
		'default' => 'layout-2cr',
		'type' => 'option'
	) );

	$wp_customize->add_control( 'portfoliopress_layout', array(
		'label' => $options['layout']['name'],
		'section' => 'portfoliopress_layout',
		'settings' => 'portfoliopress[layout]',
		'type' => 'radio',
		'choices' => array(
			'layout-2cr' => 'Sidebar Right',
			'layout-2cl' => 'Sidebar Left',
			'layout-1col' => 'Single Column')
	) );

	$wp_customize->add_setting( 'portfoliopress[menu_position]', array(
		'default' => 'right',
		'type' => 'option'
	) );

	$wp_customize->add_control( 'portfoliopress_menu_position', array(
		'label' => $options['menu_position']['name'],
		'section' => 'nav',
		'settings' => 'portfoliopress[menu_position]',
		'type' => 'radio',
		'choices' => $options['menu_position']['options']
	) );

	/* Header Styles */

	$wp_customize->add_section( 'portfoliopress_header_styles', array(
		'title' => __( 'Header Style', 'portfoliopress' ),
		'priority' => 105,
	) );

	$wp_customize->add_setting( 'portfoliopress[header_color]', array(
		'default' => '#000000',
		'type' => 'option'
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_color', array(
		'label' => __( 'Background Color', 'portfoliopress' ),
		'section' => 'portfoliopress_header_styles',
		'settings' => 'portfoliopress[header_color]'
	) ) );
}