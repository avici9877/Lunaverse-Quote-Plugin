<?php
/**
 * Plugin Name: Lunaverse Quote Plugin
 * Description: 专注于报价管理的插件，支持增删改查功能。
 * Version: 1.0
 * Author: Your Name
 */

// 防止直接访问文件
if (!defined('ABSPATH')) {
    exit;
}

// 定义插件路径
define('LUNAVERSE_PLUGIN_DIR', plugin_dir_path(__FILE__));

// 加载必要的文件
require_once LUNAVERSE_PLUGIN_DIR . 'includes/db-functions.php'; // 数据库操作函数
require_once LUNAVERSE_PLUGIN_DIR . 'includes/admin-page.php';  // 后台管理页面
require_once LUNAVERSE_PLUGIN_DIR . 'frontend/display-page.php'; // 前端展示页面

// 激活菜单
add_action('admin_menu', 'lunaverse_register_admin_menu');

// 激活插件时创建数据库表
register_activation_hook(__FILE__, 'lunaverse_create_db');

// 注册前端样式
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'lunaverse-frontend-style',
        plugin_dir_url(__FILE__) . 'frontend/style.css'
    );
});

// 添加 AJAX 数据处理脚本
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_script(
        'lunaverse-frontend-script',
        plugin_dir_url(__FILE__) . 'frontend/script.js',
        ['jquery'],
        '1.0',
        true
    );
    wp_localize_script('lunaverse-frontend-script', 'lunaverseAjax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('lunaverse_ajax_nonce'),
    ]);
});
