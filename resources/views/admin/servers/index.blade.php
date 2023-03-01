<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'服务器管理'])
<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <table id="server-table" lay-filter="server-table"></table>
    </div>
</div>

@include('admin.layouts.__footer')
{{--table头部工具栏--}}
<script id="server-toolbar" type="text/html">
    <x-lay-button type="add"></x-lay-button>
</script>
{{--table 行工具栏--}}
<script id="server-bar" type="text/html">
<x-lay-button type="edit"></x-lay-button>
<x-lay-button type="remove"></x-lay-button>
</script>
<script>
    layui.use(['table', 'form', 'common', 'jquery'], function () {
        let $ = layui.jquery;
        let table = layui.table;
        let form = layui.form;
        let common = layui.common;

        window.where = {
            filter: {}
        }

        let INDEX_PATH = '{{ route('admin.servers.index') }}';
        let CREATE_PATH = '{{ route('admin.servers.create') }}';
        let headers = {'X-CSRF-TOKEN': '{{ csrf_token() }}'};
        let cols = [[
            {
                type: 'checkbox'
            }, {
                title: 'id',
                field: 'id',
                width: 40
            }, {
                title: '服务器名称',
                field: 'name',
                width: 200,
                align: 'center'
            }, {
                title: '国家',
                field: 'country_id',
                templet:function (d) {
                    return d.country.name +' '+d.country.translations['cn'];
                }
            }, {
                title: 'ip',
                field: 'ip'
            }, {
                title: '服务器用户',
                field: 'user',
            }, {
                title: '操作',
                toolbar: '#server-bar',
            }
        ]];
        table.render({
            elem: '#server-table',
            url: INDEX_PATH,
            dataType: 'json',
            method: 'post',
            headers: headers,
            request: {
                pageName: 'page',
                limitName: 'perPage',
            },
            where: window.where,
            page: true,
            limits: [20, 30, 50],
            toolbar: '#server-toolbar',
            cols: cols,
            skin: 'line',

        });
        //表格头部工具栏事件
        table.on('toolbar(server-table)', function (obj) {
            switch (obj.event) {
                case 'add':
                    add();
                    break;
            }
        });
        //添加
        let add = function (obj) {
            layer.open({
                type: 2,
                title: '新增',
                shade: 0.1,
                area: [common.isModile() ? '100%' : '500px', common.isModile() ? '100%' : '500px'],
                content: CREATE_PATH,
            })
        }
    });
</script>
</body>
</html>
