<?php
/*
Plugin Name: Post Link Previewer Shortcode
Description: Show preview and a link to a post using shortcode
Version:     0.0.2
Author:      Chaiyapat Tantiworachot
Author URI:  https://pix7.me/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( '' );

function post_link_previewer_styles() {
    wp_register_style( 'post-link-previewer', plugins_url( 'post-link-previewer-shortcode/style.css' ) );
    wp_enqueue_style( 'post-link-previewer' );
}

function post_link_previewer_do( $atts ){

    $info_texts = array(
        "en" => "This post is also available in English",
        "th" => "อ่านโพสต์นี้ในภาษาไทย"
    );

    $a = shortcode_atts( array(
        'id' => 0,
        'to' => 'en',
        'blank' => false,
        'info' => 'PRESET'
    ), $atts );
    if( $a['id'] == 0 || get_post_status( $a['id'] ) === FALSE ) return;
    $post = get_post( $a[ 'id' ] );
    if( $post->post_status != 'publish' ) return;
    $res = array(
        'title' => apply_filters( 'the_title', $post->post_title ),
        'content' => substr( wp_strip_all_tags( strip_shortcodes ( $post->post_content ) ), 0, 300 ),
        'hasImage' => has_post_thumbnail( $post->ID ),
        'url' => get_permalink( $post->ID ),
        'thumb' => ''
    );
    if($res['hasImage']) $res['thumb'] = wp_get_attachment_url(get_post_thumbnail_id($post->ID));

    ?>
        <div class="post-link-previewer-wrapper">
            <a href='<?php echo $res['url']; ?>' class="post-link-previewer-anchor">
                <div class="post-link-previewer">
                    <?php if( ! $a['blank'] ) { ?>
                    <div class="infos">
                        <p class="info-text"><?php echo ($a['info'] == 'PRESET')? $info_texts[ $a['to'] ] . " &rarr;": $a['info']; ?></p>
                    </div>
                    <?php } ?>
                    <div class="link-preview">
                        <?php if($res['hasImage']){ ?><div class="thumb" style="background-image: url('<?php echo $res['thumb']; ?>')"></div><?php } ?>
                        <div class="content-wrapper">
                            <div class="content">
                                <p class="title"><?php echo $res['title'] ?></p>
                                <p class="post-content"><?php echo $res['content'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    <?php
}

add_action( 'wp_enqueue_scripts', 'post_link_previewer_styles' );

add_shortcode( 'post-link-previewer', 'post_link_previewer_do' );

?>
