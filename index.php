<?php
/**
 * Theme Index
 *
 * テーマのインデックス
 *
 * @category Templates
 * @package  WordPress
 * @since    1.0.0
 */

if (is_preview()) {
    get_template_part('templates/content');
} else {
    getNuxtHtml(dirname(__FILE__)."/dist/index.html");
}
