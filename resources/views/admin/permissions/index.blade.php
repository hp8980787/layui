<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'权限管理'])
<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <table id="permission-table" lay-filter="permission-table"></table>
    </div>
</div>
@include('admin.layouts.__footer')

{{--表格内操作--}}
<script id="permission-bar" type="text/html">
    <x-lay-button type="edit"></x-lay-button>
    <x-lay-button type="remove"></x-lay-button>
</script>

{{--表格头部操作--}}
<script id="permission-toolbar" type="text/html">
    <x-lay-button type="add"></x-lay-button>
</script>


<script>
    layui.use(['table', 'form', 'jquery', 'common'], function () {
        let table = layui.table;
        let $ = layui.jquery;
        let common = layui.common;

        const INDEX_PATH = '{{ route('admin.permissions.index') }}';
        const CREATE_PATH = '{{ route('admin.permissions.create') }}';
        const DELETE_PATH = '{{  route('admin.permissions.destroy') }}';
        window.where = {};
        let headers = {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
        }
        let request = {
            pageName: 'page',
            limitName: 'PerPage',
        }
        let cols = [[
            {
                type: 'checkout'
            }, {
                title: 'id',
                field: 'id'
            }, {
                title: 'name',
                field: 'name',
            }, {
                title: 'url',
                field: 'url'
            }, {
                title: '创建时间',
                field: 'created_at',
            }, {
                title: '操作',
                toolbar: '#permission-bar',
                text: 'center'
            }
        ]];
        table.render({
            elem: '#permission-table',
            url: INDEX_PATH,
            method: 'post',
            headers: headers,
            where: window.where,
            request: request,
            page: true,
            limits: [20, 30, 50],
            toolbar: '#permission-toolbar',
            cols: cols,
            skin: 'line',
            defaultToolbar: [{
                title: '刷新',
                layEvent: 'refresh',
                icon: 'layui-icon-refresh',
            }, 'filter', 'print', 'exports']
        });
        //add 新增
        table.on('toolbar(permission-table)', function (obj) {
            switch (obj.event) {
                case 'add':
                    add();
                    break;
            }
        });

        table.on('tool(permission-table)', function (obj) {
            switch (obj.event) {
                case 'edit':
                    edit(obj);
                    break;
                case 'remove':
                    remove(obj);
                    break;
            }
        })
        let add = function () {
            layer.open({
                type: 2,
                title: '新增',
                shade: 0.1,
                area: [common.isModile() ? '100%' : '400px', common.isModile() ? '100%' : '500px'],
                content: CREATE_PATH,
                end: function () {
                    window.refresh();
                }
            })
        }
        let remove = function (obj) {
            layer.confirm('确定删除该权限吗',{
                icon:3,
                title:'提示'
            },function (index) {
                layer.close(index);
                destroy(obj.data.id);
            });
        }

        let destroy = function (id) {
            let loading = layer.load();
            $.ajax({
                url: DELETE_PATH,
                method: 'delete',
                headers: headers,
                dataType: 'json',
                data: {
                    id: id,
                }, success: function (res) {
                    if (res.success) {
                        layer.msg(res.msg, {
                            icon: 1,
                            time: 1000
                        }, function () {
                            window.refresh();
                        })
                    } else {

                    }

                }, error: function (error) {

                }
            });
            layer.close(loading);
            return false;

        }

        let edit = function (obj) {
            layer.open({
                type: 2,
                title: '修改',
                shade: 0.1,
                area: [common.isModile() ? '100%' : '400px', common.isModile() ? '100%' : '400px'],
                content: obj.data.editUrl,
                end: function () {
                    window.refresh();
                }
            })
        }
        window.refresh = function () {
            table.reload('permission-table')
        }
    })
</script>
</body>
</html>
