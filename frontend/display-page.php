<?php
// 定义并注册短代码以显示价格列表
function display_price_list_shortcode() {
    $column_titles = lunaverse_get_column_titles();
    global $wpdb;
    $table_name = $wpdb->prefix . 'lunaverse_quotes';
    $quotes = $wpdb->get_results("SELECT * FROM $table_name ORDER BY display_order ASC");

    ob_start();
    ?>
    <table class="price-list">
        <thead>
            <tr>
                <?php foreach ($column_titles as $title) : ?>
                    <th><?php echo esc_html($title); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($quotes)) : ?>
                <?php foreach ($quotes as $quote) : ?>
                    <tr>
                        <td><?php echo esc_html($quote->display_order); ?></td>
                        <td><?php echo esc_html($quote->product); ?></td>
                        <td><?php echo esc_html(number_format($quote->price, 2)); ?></td>
                        <td><?php echo esc_html($quote->unit); ?></td>
                        <td><?php echo esc_html($quote->stock_location); ?></td>
                        <td><?php echo esc_html($quote->notes); ?></td>
                        <td><?php echo esc_html($quote->min_order_quantity); ?></td>
                        <td><?php echo esc_html($quote->created_at); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr><td colspan="<?php echo count($column_titles); ?>">No data available</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php
    return ob_get_clean();
}
add_shortcode('display_quotes', 'display_price_list_shortcode');
