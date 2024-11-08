<?php
/*
Plugin Name: Lunaverse Quote Plugin
Description: 一个自动报价生成插件，用于管理和展示 Lunaverse 报价信息。
Version: 5.8
Author: Sange-面向Chatgpt编程 2024/11/7
*/

if (!defined('ABSPATH')) exit;

// 插件激活和卸载钩子
register_activation_hook(__FILE__, 'lunaverse_quote_plugin_activate');
register_deactivation_hook(__FILE__, 'lunaverse_quote_plugin_deactivate');

// 引入核心功能和管理页面
include_once plugin_dir_path(__FILE__) . 'includes/lunaverse-quote-core.php';
include_once plugin_dir_path(__FILE__) . 'includes/lunaverse-quote-admin.php';

// 添加菜单项
function lunaverse_quote_plugin_menu() {
    add_menu_page(
        '报价管理',
        'Lunaverse报价',
        'manage_options',
        'lunaverse-quote-plugin',
        'lunaverse_render_admin_page'
    );
}
add_action('admin_menu', 'lunaverse_quote_plugin_menu');

// 加载样式
function lunaverse_enqueue_styles() {
    $css_path = plugin_dir_url(__FILE__) . 'frontend/style.css';
    wp_enqueue_style('lunaverse-frontend-style', $css_path, array(), '1.0', 'all');
}
add_action('wp_enqueue_scripts', 'lunaverse_enqueue_styles', 999);
