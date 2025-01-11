<?php

// 导入 CSV 数据
function lunaverse_import_csv($file_path) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'lunaverse_quotes';

    if (($handle = fopen($file_path, 'r')) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $wpdb->insert($table_name, [
                'product' => sanitize_text_field($data[0]),
                'price' => floatval($data[1]),
                'unit' => sanitize_text_field($data[2]),
                'stock_location' => sanitize_text_field($data[3]),
                'notes' => sanitize_textarea_field($data[4]),
                'min_order_quantity' => intval($data[5]),
                'display_order' => intval($data[6]),
            ]);
        }
        fclose($handle);
    }
}

// 导出 CSV 数据
function lunaverse_export_csv() {
    $quotes = lunaverse_get_quotes();

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="quotes.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Product', 'Price', 'Unit', 'Stock Location', 'Notes', 'Min Order Quantity', 'Display Order']);

    foreach ($quotes as $quote) {
        fputcsv($output, [
            $quote->product,
            $quote->price,
            $quote->unit,
            $quote->stock_location,
            $quote->notes,
            $quote->min_order_quantity,
            $quote->display_order,
        ]);
    }
    fclose($output);
    exit;
}
    