<?php
/**
 * Wordpress Theme Functions
 *
 * @category Wordpress関数
 * @package  WordPress
 * @since    1.0.0
 */

/**
 * 不要コードを<head>から不要な出力を削除
 *
 * @return void
 */
function removeHeadMeta()
{
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);

    // 絵文字関連削除
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');

    // Embed関連削除
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('template_redirect', 'rest_output_link_header', 11);

    if (!is_admin()) {
        wp_deregister_script('jquery'); // 非ログイン時はWPの既存jQueryを無効化
    }
}
add_action('wp_enqueue_scripts', 'removeHeadMeta', 102);

/**
 * Nuxt.jsのindex.htmlを取得して出力
 *
 * @param String $index_html nuxtでビルドしたindex.htmlのパス
 *
 * @return void なし
 */
function getNuxtHtml($index_html)
{
    if (file_exists($index_html)) {
        echo replaceNuxtHtml($index_html);
    } else {
        echo 'index not found';
    }
}

/**
 * 取得したHTMLを加工
 *
 * @param string $target_html 加工するHTML文字列
 *
 * @return void
 */
function replaceNuxtHtml($target_html)
{
    $html = file_get_contents($target_html);
    $html = str_replace('<!-- [wp-head] -->', getOb('wp_head'), $html);
    $html = str_replace('<!-- [wp-footer] -->', getOb('wp_footer'), $html);
    $html = str_replace('<!-- [wp-cf7-data] -->', $script_val, $html);

    // jsにわたす wpdf7 ID取得
    $cf7Form    = getWPCF7();
    $cf7FormID  = $cf7Form[0]->ID ?: '';
    $script_val = "<script> window.NUXT_WP = { id: {$cf7FormID} }; </script>";

    // dom
    $dom = new DOMDocument();
    $dom->formatOutput = true; // 整形
    libxml_use_internal_errors(true);
    $dom->loadHTML($html);
    libxml_clear_errors();

    // タイトルを現在のページに書き換え
    $xpath = new DOMXpath($dom);
    $html_title = $xpath->query('//title');
    if (!is_null($html_title)) {
        foreach ($html_title as $title) {
            $title->nodeValue = getWPPageTitle();
        }
    }

    // utf-8
    $output = mb_convert_encoding($dom->saveHTML(), 'utf-8', 'HTML-ENTITIES');
    // コメント・改行削除で圧縮
    $output = preg_replace('/<!--[\s\S]*?-->/s', '', $output);
    // $output = preg_replace('/(\t|\r\n|\r|\n)/s', '', $output);

    return $output;
}

/**
 * 関数の出力をバッファして値で返す
 *
 * @param Function $callback コールバック関数
 *
 * @return String 取得データ
 */
function getOb($callback)
{
    if (!function_exists($callback)) {
        return null;
    }
    ob_start();
    $callback();
    $ob_data = ob_get_contents();
    ob_end_clean();

    return $ob_data;
}

/**
 * ページのタイトルを取得して返す
 *
 * @return void
 */
function getWPPageTitle()
{
    return is_single() || is_page()
    ? strip_tags(get_the_title()) . ' | ' . get_bloginfo('name')
    : get_bloginfo('name');
}

/**
 * ContactForm 7
 *
 * @param string $cf7Title 取得する ContactForm 7のタイトル
 *
 * @return array
 */
function getWPCF7($cf7Title = 'Contact form 1')
{
    $args = [
      'post_type'   => 'wpcf7_contact_form',
      'post_status' => 'publish',
      'title'       => $wpCf7Title
    ];
    $form = get_posts($args);

    return $form;
}
