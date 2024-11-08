<?php

// 创建数据库表
function lunaverse_create_db_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lunaverse_quotes';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        display_order int NOT NULL,
        product varchar(255) NOT NULL,
        price decimal(10,2) NOT NULL,
        unit varchar(50),
        stock_location varchar(255),
        notes text,
        min_order_quantity int,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// 删除数据库表（可选）
function lunaverse_quote_plugin_deactivate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lunaverse_quotes';
    $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

// 获取列标题
function lunaverse_get_column_titles() {
    $default_titles = [
        'display_order' => '序号',
        'product' => 'Product Name',
        'price' => 'Price',
        'unit' => 'Unit',
        'stock_location' => 'Stock Location',
        'notes' => 'Notes',
        'min_order_quantity' => 'Minimum Order Quantity',
        'created_at' => 'Last Updated',
    ];
    return get_option('lunaverse_column_titles', $default_titles);
}

// 保存自定义列标题
function lunaverse_save_column_titles() {
    if (isset($_POST['save_column_titles'])) {
        $columns = [
            'display_order' => sanitize_text_field($_POST['display_order_title']),
            'product' => sanitize_text_field($_POST['product_title']),
            'price' => sanitize_text_field($_POST['price_title']),
            'unit' => sanitize_text_field($_POST['unit_title']),
            'stock_location' => sanitize_text_field($_POST['stock_location_title']),
            'notes' => sanitize_text_field($_POST['notes_title']),
            'min_order_quantity' => sanitize_text_field($_POST['moq_title']),
            'created_at' => sanitize_text_field($_POST['created_at_title']),
        ];
        update_option('lunaverse_column_titles', $columns);
        echo "<div class='updated'><p>列标题已保存！</p></div>";
    }
}
add_action('admin_init', 'lunaverse_save_column_titles');
