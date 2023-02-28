<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'文件管理'])
<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <table class="layui-table" id="file-table" lay-filter="file-table"></table>
    </div>
</div>

@include('admin.layouts.__footer')


<script id="file-bar" type="text/html">
<x-lay-button type="remove"></x-lay-button>
</script>

<script>
    layui.use(['table', 'form', 'common', 'jquery'], function () {
        let $ = layui.jquery;
        let table = layui.table;
        let common = layui.common;

        let INDEX_PATH = '{{ route('admin.files.index') }}';
        window.where = {};
        let headers = {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        }

        let cols = [[
            {
                type: 'checkbox',
            }, {
                title: 'id',
                field: 'id',
            }, {
                title: '文件名',
                field: 'name',
            }, {
                title: '原始名',
                field: 'original_name'
            }, {
                title: '文件后缀名',
                field: 'extension',
            }, {
                title: '网址',
                field: 'path'
            }, {
                title: '创建时间',
                field: 'created_at',
            }, {
                title: '操作',
                toolbar: '#file-bar',
            }
        ]];
        table.render({
            elem: '#file-table',
            url: INDEX_PATH,
            method: 'post',
            headers: headers,
            request: {
                limitName: 'perPage',
                pageName: 'page',
            },
            where: window.where,
            page: true,
            limits: [20, 30, 50, 60],
            cols: cols,
            skin: 'line',
            defaultToolbar: [{
                title: '刷新',
                layEvent: 'refresh',
                icon: 'layui-icon-refresh',
            }, 'filter', 'print', 'exports']
        })
    })
</script>
</body>
</html>
