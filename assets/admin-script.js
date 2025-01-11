document.addEventListener('DOMContentLoaded', function () {
    const table = document.querySelector('.wp-list-table');

    table.addEventListener('click', function (e) {
        if (e.target.classList.contains('save-quote')) {
            const row = e.target.closest('tr');
            const id = row.getAttribute('data-id');
            const data = {};

            row.querySelectorAll('.editable').forEach(cell => {
                const field = cell.getAttribute('data-field');
                const value = cell.textContent.trim();
                data[field] = value;
            });

            data['id'] = id;

            fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'lunaverse_save_quote',
                    data: data,
                }),
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    alert('保存成功！');
                } else {
                    alert('保存失败，请重试！');
                }
            });
        }
    });
});
