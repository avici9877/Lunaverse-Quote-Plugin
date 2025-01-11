<?php

// 创建数据库表
function lunaverse_create_db() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lunaverse_quotes';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        product VARCHAR(255) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        unit VARCHAR(50) NOT NULL,
        stock_location VARCHAR(255),
        notes TEXT,
        display_order INT UNSIGNED NOT NULL,
        power FLOAT DEFAULT 0,
        hashrate FLOAT DEFAULT 0,
        per_t_price DECIMAL(10, 2) DEFAULT 0.00,
        stock_quantity INT UNSIGNED DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}
