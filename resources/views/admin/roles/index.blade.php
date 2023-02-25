<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'角色管理'])
<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <table id="role-table" lay-filter="role-table"></table>
    </div>
</div>
@include('admin.layouts.__footer')

<script type="text/html" id="role-toolbar">
    <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">
        <i class="layui-icon layui-icon-add-1"></i>
    </button>
</script>

<script>
    layui.use(['table', 'jquery', 'common', 'form'], function () {
        let $ = layui.jquery;
        let table = layui.table;
        let common = layui.common;

        let INDEX_PATH = '{{ route('admin.roles.index') }}';
        let CREATE_PATH = '{{ route('admin.roles.create') }}'

        const csrfToken = '{{ csrf_token() }}';
        let headers = {'X-CSRF-TOKEN': csrfToken};

        window.where = {};
        let cols = [
            [{
                type: 'checkbox'
            },

                {
                    title: '操作',
                    toolbar: '#user-bar',
                    align: 'center',
                    width: 130
                }
            ]
        ]
        table.render({
            elem: '#role-table',
            url: INDEX_PATH,
            method: 'post',
            headers: headers,
            where: window.where,
            page: true,
            cols: cols,
            skin: 'line',
            toolbar: '#role-toolbar',
            defaultToolbar: [{
                title: '刷新',
                layEvent: 'refresh',
                icon: 'layui-icon-refresh',
            }, 'filter', 'print', 'exports']

        })

        table.on('toolbar(role-table)', function (obj) {

            switch (obj.event) {
                case 'add':
                    add();
                    break;
            }
        });

        let add = function () {
            layer.open({
                type: 2,
                title: '新增',
                shade: 0.1,
                area: [common.isModile() ? '100%' : '500px', common.isModile() ? '100%' : '500px'],
                content:CREATE_PATH
            })
        }
    });

</script>
</body>
</html>
