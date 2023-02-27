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

<script type="text/html" id="role-bar">
    <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>
    <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
</script>

<script>
    layui.use(['table', 'jquery', 'common', 'form'], function () {
        let $ = layui.jquery;
        let table = layui.table;
        let common = layui.common;

        let INDEX_PATH = '{{ route('admin.roles.index') }}';
        let CREATE_PATH = '{{ route('admin.roles.create') }}'
        let UPDATE_PATH = '{{ route('admin.roles.update') }}';
        let DESTROY_PATH = '{{ route('admin.roles.destroy') }}';
        const csrfToken = '{{ csrf_token() }}';
        let headers = {'X-CSRF-TOKEN': csrfToken};

        window.where = {};
        let cols = [
            [{
                type: 'checkbox'
            }, {
                title: 'id',
                field: 'id',
            }, {
                title: 'name',
                field: 'name'
            }, {
                title: 'created_at',
                field: 'created_at',
            }, {
                title: '操作',
                toolbar: '#role-bar',
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
            request: {
                limitName: 'perPage',
                pageName: 'page',
            },
            page: true,
            limits: [20, 30, 50],
            cols: cols,
            skin: 'line',
            toolbar: '#role-toolbar',
            defaultToolbar: [{
                title: '刷新',
                layEvent: 'refresh',
                icon: 'layui-icon-refresh',
            }, 'filter', 'print', 'exports']

        })


        //table操作工具栏
        table.on('tool(role-table)', function (obj) {

            switch (obj.event) {
                case 'edit':
                    edit(obj);
                    break;
                case 'remove':
                    remove(obj);
                    break;
            }
        });
        //table 头部工具栏
        table.on('toolbar(role-table)', function (obj) {
            switch (obj.event) {
                case 'add':
                    add();
                    break;
            }
        });


        let edit = function (obj) {
            layer.open({
                type: 2,
                title: '新增',
                shade: 0.1,
                area: [common.isModile() ? '100%' : '500px', common.isModile() ? '100%' : '500px'],
                content: obj.data.editUrl,
                end: function () {
                    window.refresh();
                }
            })
        }

        let remove = function (obj) {
            console.log(obj);
            layer.confirm('是否删除?', {
                icon: 3,
                title: '是否删除?'
            }, function (index) {
                layer.close(index);
                destroy(obj.data.id);
            });
        }

        let destroy = function (id) {
            let loading = layer.load();
            $.ajax({
                url: DESTROY_PATH,
                headers: headers,
                method: 'delete',
                data: {
                    id: id,
                },
                success: function (res) {
                    if (res.success) {
                        layer.msg(res.msg, {
                            icon: 1,
                            time: 1000
                        }, function () {
                            window.refresh()
                        });
                    } else {
                        layer.msg(res.msg, {
                            icon: 2,
                            time: 1000
                        });
                    }
                    layer.close(loading);
                }, error: function (error) {

                }

            });
            return false;
        }

        let add = function () {
            layer.open({
                type: 2,
                title: '新增',
                shade: 0.1,
                area: [common.isModile() ? '100%' : '500px', common.isModile() ? '100%' : '500px'],
                content: CREATE_PATH,
                end: function () {
                    window.refresh();
                }
            })
        }

        window.refresh = function (param) {
            table.reload('role-table', {
                where: window.where
            });
        }
    });

</script>
</body>
</html>
