<?php
// Thêm menu
add_action('admin_menu', function () {
    add_menu_page('A/B Testing', 'A/B Testing', 'manage_options', 'ab-tester', 'abt_render_admin_page', 'dashicons-chart-bar', 80);
});

// Giao diện đơn giản
function abt_render_admin_page() {
    global $wpdb;
    $table = $wpdb->prefix . 'abt_data';
    $results = $wpdb->get_results("SELECT variation, metric, COUNT(*) as total FROM $table GROUP BY variation, metric", ARRAY_A);

    // Định dạng dữ liệu cho biểu đồ
    $data = [];
    foreach ($results as $row) {
        $data[$row['variation']][$row['metric']] = (int) $row['total'];
    }

    ?>
<div class="wrap">
    <h1>Kết quả A/B Testing</h1>
    <canvas id="abtChart" width="600" height="300"></canvas>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const data = {
        labels: <?php echo json_encode(array_keys($data)); ?>,
        datasets: [{
                label: 'Lượt click',
                backgroundColor: '#3b82f6',
                data: <?php echo json_encode(array_map(fn($v) => $v['click'] ?? 0, $data)); ?>
            },
            {
                label: 'Thời gian ở lại (giây)',
                backgroundColor: '#22c55e',
                data: <?php echo json_encode(array_map(fn($v) => $v['time'] ?? 0, $data)); ?>
            }
        ]
    };

    new Chart(document.getElementById('abtChart'), {
        type: 'bar',
        data: data,
        options: {
            responsive: true
        }
    });
    </script>
</div>
<?php
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'toplevel_page_ab-tester') {
        wp_enqueue_style('abt-admin', ABT_URL . 'css/admin.css');
    }
});

}