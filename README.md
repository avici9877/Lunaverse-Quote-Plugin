需求文档：Lunaverse Quote Plugin 报价管理插件
项目概述
Lunaverse Quote Plugin 是一个 WordPress 插件，用于管理和展示报价信息。它支持后台配置、报价导入/导出、列标题自定义以及前端显示等功能。本需求文档定义了插件的需求，以确保前端和后台数据的一致性和灵活性。

1. 功能需求
1.1 后台功能
1.1.1 插件激活与卸载
激活功能：插件激活时自动创建必要的数据库表，用于存储报价数据。
卸载功能：插件卸载时清理数据库，删除相关的表或数据（可选）。
1.1.2 报价管理页面
在 WordPress 管理后台添加一个菜单项 “Lunaverse报价”，点击进入 报价管理页面，包含以下功能：

报价数据表：

显示报价信息，包括序号、产品名称、价格、单位、库存位置、备注、最小订单数量、更新时间等列。
支持编辑、删除和批量操作。
自定义列标题：

提供输入框，允许管理员自定义每一列的标题。
保存自定义列标题到数据库，以便前端展示时同步更新。
导入导出功能：

导入 CSV：支持从 CSV 文件导入报价数据，将数据写入数据库中。
导出 CSV：支持将数据库中的报价数据导出到 CSV 文件。
1.1.3 数据库交互
创建数据库表：在插件激活时创建用于存储报价数据的表，包含必要字段，如 display_order, product, price, unit, stock_location, notes, min_order_quantity, created_at。
保存和获取自定义列标题：保存自定义列标题到 WordPress options 表，获取列标题时，优先使用用户自定义标题。
1.2 前端功能
1.2.1 报价列表显示
短代码：提供 [display_quotes] 短代码，在 WordPress 页面中使用，前端显示报价列表。
动态列标题：前端表格的列标题动态获取后台自定义的列标题，保持一致。
表格内容：显示后台管理的报价信息，包括序号、产品名称、价格、单位、库存位置、备注、最小订单数量、更新时间等列。
样式：表格样式美观，支持响应式设计，适配不同屏幕尺寸。
2. 技术实现
2.1 后台实现
2.1.1 数据库创建和删除
函数：lunaverse_create_db_table()，在插件激活时调用，用于创建数据库表。
结构：数据库表包含报价的各个字段，每一列字段类型根据存储的数据确定（如 int, varchar, decimal 等）。
2.1.2 插件文件结构
插件按照模块化结构划分为不同文件，以提升代码可读性和维护性。具体结构如下：

/lunaverse-quote-plugin
├── lunaverse-quote-plugin.php         // 插件主文件，负责加载核心模块和功能
├── includes
│   ├── lunaverse-quote-core.php       // 核心功能模块（数据库操作、获取列标题等）
│   └── lunaverse-quote-admin.php      // 后台管理功能（页面渲染、自定义列标题、导入导出功能）
├── frontend
│   ├── display-page.php               // 前端显示报价列表的逻辑
│   └── style.css                      // 前端表格样式

2.1.3 自定义列标题逻辑
保存列标题：在 lunaverse_save_column_titles() 中，读取管理员输入的自定义列标题，并将其保存到数据库。
获取列标题：在 lunaverse_get_column_titles() 中，优先从数据库获取自定义标题；若无自定义标题，则使用默认值。
2.1.4 导入导出功能
导入 CSV：lunaverse_import_csv()，读取 CSV 文件内容，并将其保存到数据库。
导出 CSV：lunaverse_export_csv()，从数据库读取报价数据，并生成 CSV 文件供下载。
2.2 前端实现
2.2.1 短代码显示报价列表
短代码函数：display_price_list_shortcode()，用于生成报价表格的 HTML 代码。
获取数据：从数据库获取报价数据，并按照后台的列标题顺序进行显示。
动态列标题：通过 lunaverse_get_column_titles() 获取列标题，确保前端与后台一致。
2.2.2 表格样式设计
CSS 样式：在 frontend/style.css 文件中定义表格样式，包括表头样式、行间隔色等。
响应式设计：表格样式适配不同设备，保证在移动设备和桌面设备上的显示效果。
3. 详细实现步骤
3.1 开发后台功能
创建数据库表：

在 lunaverse_create_db_table() 函数中定义数据库表结构，在插件激活时执行。
实现列标题的自定义和保存：

创建管理页面的表单，包含列标题输入框。
在 lunaverse_save_column_titles() 函数中保存自定义标题到数据库。
实现报价导入导出：

在 lunaverse_import_csv() 函数中处理 CSV 文件上传，解析并保存到数据库。
在 lunaverse_export_csv() 函数中从数据库获取数据并生成 CSV 文件。
生成后台菜单：

在 lunaverse_quote_plugin_menu() 中添加菜单项，调用页面渲染函数生成页面内容。
3.2 开发前端功能
定义短代码函数：

创建 display_price_list_shortcode() 短代码函数，在 WordPress 页面中使用 [display_quotes] 调用。
动态获取列标题：

在短代码函数中，使用 lunaverse_get_column_titles() 获取列标题，确保前端和后台一致。
显示报价数据：

在 HTML 表格中循环显示报价数据，按照列标题的顺序显示。
前端样式设计：

定义表格样式，优化显示效果并添加响应式支持。
4. 示例代码
插件主文件：lunaverse-quote-plugin.php
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

5. 测试方案
列标题自定义测试：

在后台自定义列标题，保存后检查数据库是否更新。
前端刷新页面，确认列标题与后台一致。
数据导入导出测试：

使用不同的 CSV 文件测试导入功能，检查数据库中的数据是否正确。
测试导出功能，确保生成的 CSV 文件数据和格式正确。
前端显示测试：

确认短代码 [display_quotes] 在页面中显示数据，并检查样式是否正确。
在不同屏幕尺寸上查看表格显示效果。
6. 项目总结
通过 Lunaverse Quote Plugin 插件，用户可以在 WordPress 后台管理报价数据，并在前端展示报价列表。插件具备灵活性，支持用户自定义列标题和批量操作，且确保了前后端数据一致性。
