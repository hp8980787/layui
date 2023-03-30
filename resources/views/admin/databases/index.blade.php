<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'数据库管理'])
<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <table id="database-table" layui-filter="database-table"></table>
    </div>
</div>

@include('admin.layouts.__footer')
<script id="table-toolbar" type="text/html">
<x-lay-button type="add"></x-lay-button>
</script>
<script>
    layui.use(['table', 'form', 'common', 'jquery'], function () {
        const $ = layui.jquery;
        const table = layui.table;
        const common = layui.common;
        let headers = {'X-CSRF-TOKEN': '{{ csrf_token() }}'};
        let INDEX_PATH = '{{ route('admin.databases.index') }}';
        window.where = {}

        let cols = [[
            {
                type: 'checkbox'
            }, {
                title: 'id',
                field: 'id',
            }, {
                title: 'domain_id',
                field: 'domain_id',
            }, {
                title: 'host',
                field: 'host',
            }, {
                title: 'port',
                field: 'port',
            }, {
                title: 'database',
                field: 'database',
            }, {
                title: 'username',
                field: 'username',
            }, {
                title: '密码',
                field: 'password'
            }
        ]];

        table.render({
            elem: '#database-table',
            url: INDEX_PATH,
            method: 'post',
            headers: headers,
            request: {
                pageName: 'page',
                limitName: 'perPage',
            },
            page: true,
            limits: [20, 30, 50],
            where: window.where,
            cols: cols,
            skin: 'line',
            toolbar:'#table-toolbar'
        })

    });
</script>
</body>
</html>
