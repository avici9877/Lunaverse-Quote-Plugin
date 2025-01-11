<?php

function lunaverse_register_admin_menu() {
    add_menu_page(
        '报价管理',
        'Lunaverse Quote',
        'manage_options',
        'lunaverse-quote',
        'lunaverse_admin_page',
        'dashicons-list-view',
        20
    );
}

function lunaverse_admin_page() {
    global $wpdb;

    // 调试：打印 POST 数据到日志
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log('POST Data: ' . print_r($_POST, true));
    }

    // 处理表单提交逻辑
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 批量保存
        if (!empty($_POST['bulk_action']) && $_POST['bulk_action'] === 'save') {
            foreach ($_POST['display_order'] as $id => $order) {
                $id = intval($id);
                $data = [
                    'product' => sanitize_text_field($_POST['product'][$id] ?? ''),
                    'price' => floatval($_POST['price'][$id] ?? 0),
                    'unit' => sanitize_text_field($_POST['unit'][$id] ?? ''),
                    'stock_location' => sanitize_text_field($_POST['stock_location'][$id] ?? ''),
                    'display_order' => intval($order),
                    'power' => floatval($_POST['power'][$id] ?? 0),
                    'hashrate' => floatval($_POST['hashrate'][$id] ?? 0),
                    'per_t_price' => floatval($_POST['per_t_price'][$id] ?? 0),
                    'stock_quantity' => intval($_POST['stock_quantity'][$id] ?? 0),
                ];
                $result = $wpdb->update("{$wpdb->prefix}lunaverse_quotes", $data, ['id' => $id]);

                // 打印更新 SQL 查询日志
                if ($result === false) {
                    error_log("Failed to update ID $id. Query: " . $wpdb->last_query);
                }
            }
            echo '<div class="updated"><p>所有行已保存。</p></div>';
        }

        // 批量删除
        if (!empty($_POST['bulk_action']) && $_POST['bulk_action'] === 'delete') {
            $ids_to_delete = array_map('intval', $_POST['selected_ids'] ?? []);
            foreach ($ids_to_delete as $id) {
                $wpdb->delete("{$wpdb->prefix}lunaverse_quotes", ['id' => $id]);
            }
            echo '<div class="updated"><p>选中行已删除。</p></div>';
        }

        // 新增行逻辑
        if (!empty($_POST['new_product']) && !empty($_POST['new_price'])) {
            $new_data = [
                'product' => sanitize_text_field($_POST['new_product']),
                'price' => floatval($_POST['new_price']),
                'unit' => sanitize_text_field($_POST['new_unit'] ?? ''),
                'stock_location' => sanitize_text_field($_POST['new_stock_location'] ?? ''),
                'display_order' => intval($_POST['new_display_order'] ?? 0),
                'power' => floatval($_POST['new_power'] ?? 0),
                'hashrate' => floatval($_POST['new_hashrate'] ?? 0),
                'per_t_price' => floatval($_POST['new_per_t_price'] ?? 0),
                'stock_quantity' => intval($_POST['new_stock_quantity'] ?? 0),
                'created_at' => current_time('mysql'),
            ];
            $result = $wpdb->insert("{$wpdb->prefix}lunaverse_quotes", $new_data);

            // 打印插入 SQL 查询日志
            if ($result === false) {
                error_log("Failed to insert new record. Query: " . $wpdb->last_query);
            } else {
                echo '<div class="updated"><p>新行已添加。</p></div>';
            }
        }

        // 防止重复提交
        wp_redirect(admin_url('admin.php?page=lunaverse-quote'));
        exit;
    }

    // 获取报价数据
    $quotes = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lunaverse_quotes ORDER BY display_order ASC");

    // 输出 HTML 表单
    echo '<div class="wrap">';
    echo '<h1>报价管理</h1>';
    echo '<form method="post">';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr>';
    echo '<th><input type="checkbox" id="select_all" /></th>';
    echo '<th>排序</th><th>产品名称</th><th>功耗 (W)</th><th>算力</th><th>单 T 价格</th><th>价格</th><th>库存数量</th><th>单位</th><th>库存位置</th><th>操作</th>';
    echo '</tr></thead><tbody>';

    // 动态生成行数据
    foreach ($quotes as $quote) {
        echo "<tr>
                <td><input type='checkbox' name='selected_ids[]' value='{$quote->id}' class='row_checkbox' /></td>
                <td><input type='number' name='display_order[{$quote->id}]' value='{$quote->display_order}' /></td>
                <td><input type='text' name='product[{$quote->id}]' value='{$quote->product}' /></td>
                <td><input type='number' name='power[{$quote->id}]' value='{$quote->power}' step='0.01' /></td>
                <td><input type='number' name='hashrate[{$quote->id}]' value='{$quote->hashrate}' step='0.01' /></td>
                <td><input type='number' name='per_t_price[{$quote->id}]' value='{$quote->per_t_price}' step='0.01' /></td>
                <td><input type='number' name='price[{$quote->id}]' value='{$quote->price}' step='0.01' /></td>
                <td><input type='number' name='stock_quantity[{$quote->id}]' value='{$quote->stock_quantity}' /></td>
                <td><input type='text' name='unit[{$quote->id}]' value='{$quote->unit}' /></td>
                <td><input type='text' name='stock_location[{$quote->id}]' value='{$quote->stock_location}' /></td>
                <td>
                    <button type='submit' name='quote_id' value='{$quote->id}' class='button button-primary'>保存</button>
                    <a href='?page=lunaverse-quote&delete={$quote->id}' class='button button-secondary'>删除</a>
                </td>
              </tr>";
    }

    // 新增行
    echo "<tr>
            <td></td>
            <td><input type='number' name='new_display_order' /></td>
            <td><input type='text' name='new_product' /></td>
            <td><input type='number' name='new_power' step='0.01' /></td>
            <td><input type='number' name='new_hashrate' step='0.01' /></td>
            <td><input type='number' name='new_per_t_price' step='0.01' /></td>
            <td><input type='number' name='new_price' step='0.01' /></td>
            <td><input type='number' name='new_stock_quantity' /></td>
            <td><input type='text' name='new_unit' /></td>
            <td><input type='text' name='new_stock_location' /></td>
            <td><button type='submit' class='button button-primary'>新增</button></td>
          </tr>";

    echo '</tbody></table>';

    // 批量操作按钮
    echo '<div style="margin-top: 10px;">';
    echo '<select name="bulk_action">';
    echo '<option value="">批量操作</option>';
    echo '<option value="save">全部保存</option>';
    echo '<option value="delete">全部删除</option>';
    echo '</select>';
    echo '<button type="submit" class="button button-primary">应用</button>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
}
