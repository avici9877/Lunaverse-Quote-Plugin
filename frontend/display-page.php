<?php

function lunaverse_display_quotes() {
    global $wpdb;

    // 查询数据库中的报价数据
    $quotes = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lunaverse_quotes ORDER BY display_order ASC");

    if (!$quotes) {
        return '<p>暂无报价数据。</p>';
    }

    // 构建 HTML 表格
    $html = '<table class="lunaverse-quote-table">';
    $html .= '<thead><tr>';
    $html .= '<th>排序</th><th>产品名称</th><th>功耗 (W)</th><th>算力</th><th>单 T 价格</th><th>价格</th><th>库存数量</th><th>单位</th><th>库存位置</th>';
    $html .= '</tr></thead>';
    $html .= '<tbody>';

    foreach ($quotes as $quote) {
        $html .= "<tr>
                    <td>{$quote->display_order}</td>
                    <td>{$quote->product}</td>
                    <td>{$quote->power}</td>
                    <td>{$quote->hashrate}</td>
                    <td>{$quote->per_t_price}</td>
                    <td>{$quote->price}</td>
                    <td>{$quote->stock_quantity}</td>
                    <td>{$quote->unit}</td>
                    <td>{$quote->stock_location}</td>
                  </tr>";
    }

    $html .= '</tbody></table>';
    return $html;
}

// 注册短代码
add_shortcode('lunaverse_quotes', 'lunaverse_display_quotes');
