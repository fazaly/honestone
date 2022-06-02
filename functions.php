<?php

//Use this function to check site url -----//
//die(site_url());

if(site_url()== "http://learning-wp.local"){
    define("VERSION",time());
    //wp_get_theme() inter-related to style.css 
}else{
    define("VERSION",wp_get_theme()->get("Version"));
}

//Use this function to check internal CSS & JS Files VERSION -----//
//echo VERSION;
//die();

function honest_bootstrapping(){
    load_theme_textdomain("honest");
    add_theme_support("post-thumbnails");
    add_theme_support("title-tag");
/* ========= Dealing with custom header text colors in Customizer ========= */
    $honest_custom_header_details = array(
        'header-text'             => true,
        'default-text-color'      => '#222',
/* ========= Detailed discussion of cropping in custom header images ========= */
        'width'                   => 1200,
        'height'                  => 600,
        'flex-width'              => true,
        'flex-height'             => true,
    );
/* Change header Image with Customizer & Dealing with custom header text colors in Customizer */
    add_theme_support("custom-header",$honest_custom_header_details);
/* ========= Use of custom logos in theme support ========= */
$honest_custom_logo_defaults = array(
    'width'       => '100',
    'height'      => '100',
    );
    add_theme_support("custom-logo",$honest_custom_logo_defaults);
/* ========= Custom Background ========= */
    add_theme_support("custom-background");

    register_nav_menu("topmenu",__("Top Menu","honest"));
    register_nav_menu("footermenu",__("Footer Menu","honest"));
}
add_action("after_setup_theme", "honest_bootstrapping");

/* ========= CSS & Jquery Section Area ========= */
function honest_assets(){
    wp_enqueue_style("honest",get_stylesheet_uri(),null, VERSION );
    wp_enqueue_style("bootstrap","//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css");

/* ========= Featherlight CSS & JS Files ========= */
    wp_enqueue_style("featherlight-css","//cdn.jsdelivr.net/npm/featherlight@1.7.14/release/featherlight.min.css");

    wp_enqueue_script("featherlight-js","//cdn.jsdelivr.net/npm/featherlight@1.7.14/release/featherlight.min.js", array("jquery", "0.0.1", true));

/* ========= Main JS Files ========= */
    // You can use both of them
    wp_enqueue_script("honest-main",get_template_directory_uri()."/assets/js/main.js",null, VERSION, true);
    //wp_enqueue_script("honest-main",get_theme_file_uri("/assets/js/main.js"),array("jquery","featherlight-js"),"0.0.1",true);
}
add_action("wp_enqueue_scripts", "honest_assets");

/* ========= Widget Area ========= */ 
function honest_sidebar (){
    register_sidebar(
        array(
            'name' => __('Single Post Sidebar','honest'),
            'id' => 'sidebar-1',
            'description' => __('Right Sidebar','honest'),
            'before_widget' =>'<section>',
            'after_widget' =>'</section>',
            'before_title' =>'<h2 class="widget-title">',
            'after_widget' =>'</h2>',
        )
    );
/* ========= Footer Widget Area ========= */ 
    register_sidebar(
        array(
            'name' => __('Footer Left','honest'),
            'id' => 'footer-left',
            'description' => __('Widgetized Area on The Left Side','honest'),
            'before_widget' =>'<section>',
            'after_widget' =>'</section>',
            'before_title' =>'',
            'after_widget' =>'',
        )
    );

    register_sidebar(
        array(
            'name' => __('Footer Right','honest'),
            'id' => 'footer-right',
            'description' => __('Widgetized Area on The Right Side','honest'),
            'before_widget' =>'<section>',
            'after_widget' =>'</section>',
            'before_title' =>'',
            'after_widget' =>'',
        )
    );
}
add_action("widgets_init","honest_sidebar");

/* ========= Password Protected Post Manage ========= */
function honest_the_excerpt($excerpt){
    if(!post_password_required()){
        return $excerpt;
    }else{
        echo get_the_password_form();
    }
}
add_filter("the_excerpt","honest_the_excerpt");

function honest_protected_title_change(){
    return "%s";
}
add_filter("protected_title_format","honest_protected_title_change");

/* ========= Nav Menu/Add Extra CSS  ========= */
function honest_menu_item_class($classes, $item){
    $classes[] = "list-inline-item";
    return $classes;
}
add_filter("nav_menu_css_class","honest_menu_item_class", 10, 2);

/* ========= Page Templates Background Banner ========= */
function honest_about_page_template_banner(){
    if(is_page()){
        $honest_feat_image = get_the_post_thumbnail_url(null,"large");
        ?>
        <style>
            .page-header{
                background-image: url(<?php echo $honest_feat_image;?>);
            }
        </style>
        <?php
    }
/* ========= 3.27 Change header Image with Customizer ========= */
    if ( current_theme_supports( "custom-header" ) ) {
        ?>
        <style>
            .header{
                background-image: url(<?php echo header_image();?>);
                background-size: cover;
                margin-bottom: 50px;
                border-bottom: none;
            }
/* ========= Dealing with custom header text colors in Customizer ========= */
            .header h1.heading a, h3.tagline {
                color: #<?php echo get_header_textcolor();?>;
                <?php
                if(!display_header_text()){
                    echo "display: none;";
                }
                ?>
            }
            h1.heading{
                border-bottom: none;
            }
        </style>
        <?php
    }
}
add_action("wp_head","honest_about_page_template_banner",11);

/* ========= Extra Part that don't discuss ========= */

function alpha_body_class( $classes ) {
    unset( $classes[ array_search( "custom-background", $classes ) ] );
    unset( $classes[ array_search( "single-format-audio", $classes ) ] );
    $classes[] = "newclass";

    return $classes;
}

add_filter( "body_class", "alpha_body_class" );


function alpha_post_class( $classes ) {
    unset( $classes[ array_search( "format-audio", $classes ) ] );

    return $classes;
}

add_filter( "post_class", "alpha_post_class" );