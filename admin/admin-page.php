<!-- admin/admin-page.php -->

<div class="wrap">
    <h1>报价管理</h1>

    <!-- 自定义列标题表单 -->
    <form method="post">
        <h2>自定义列标题</h2>
        <?php foreach ($column_titles as $key => $label): ?>
            <label><?php echo $label; ?>: 
                <input type="text" name="<?php echo $key; ?>_title" value="<?php echo esc_attr($label); ?>">
            </label><br>
        <?php endforeach; ?>
        <button type="submit" name="save_column_titles" class="button-primary">保存列标题</button>
    </form>

    <!-- 导入导出功能 -->
    <form method="post" enctype="multipart/form-data">
        <h2>报价管理</h2>
        <input type="file" name="csv_file" accept=".csv">
        <button type="submit" name="import_csv" class="button">导入 CSV</button>
        <button type="submit" name="export_csv" class="button-primary">导出 CSV</button>
    </form>
</div>
