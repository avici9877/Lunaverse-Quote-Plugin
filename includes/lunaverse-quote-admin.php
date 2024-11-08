<?php

// 导入 CSV 数据
function lunaverse_import_csv($file) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lunaverse_quotes';

    if (($handle = fopen($file, 'r')) !== FALSE) {
        fgetcsv($handle);
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $wpdb->insert($table_name, [
                'product' => sanitize_text_field($data[0]),
                'price' => floatval($data[1]),
                'unit' => sanitize_text_field($data[2]),
                'stock_location' => sanitize_text_field($data[3]),
                'notes' => sanitize_textarea_field($data[4]),
                'min_order_quantity' => intval($data[5]),
                'display_order' => intval($data[6]),
                'created_at' => current_time('mysql')
            ]);
        }
        fclose($handle);
        echo "<div class='updated'><p>CSV 数据已成功导入！</p></div>";
    }
}

// 导出 CSV 数据
function lunaverse_export_csv() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lunaverse_quotes';
    $quotes = $wpdb->get_results("SELECT display_order, product, price, unit, stock_location, notes, min_order_quantity, created_at FROM $table_name ORDER BY display_order ASC", ARRAY_A);

    if (!empty($quotes)) {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="quotes_export.csv"');

        $output = fopen('php://output', 'w');
        $titles = lunaverse_get_column_titles();
        fputcsv($output, array_values($titles));

        foreach ($quotes as $quote) {
            fputcsv($output, $quote);
        }

        fclose($output);
        exit;
    }
}

// 处理请求
function lunaverse_handle_post_requests() {
    if (isset($_POST['export_csv'])) {
        lunaverse_export_csv();
    }
    if (isset($_POST['import_csv']) && !empty($_FILES['csv_file']['tmp_name'])) {
        lunaverse_import_csv($_FILES['csv_file']['tmp_name']);
    }
}
add_action('admin_init', 'lunaverse_handle_post_requests');

// 渲染管理页面
function lunaverse_render_admin_page() {
    $column_titles = lunaverse_get_column_titles();
    include plugin_dir_path(__FILE__) . '../admin/admin-page.php';
}
