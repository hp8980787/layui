<!DOCTYPE html>
<html>
@include('admin.layouts.__header',['title'=>'用户管理'])
<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-inline">
                        <input type="text" name="realName" placeholder="" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">性别</label>
                    <div class="layui-input-inline">
                        <input type="text" name="realName" placeholder="" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">邮箱</label>
                    <div class="layui-input-inline">
                        <input type="text" name="realName" placeholder="" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <button class="pear-btn pear-btn-md pear-btn-primary" lay-submit lay-filter="user-query">
                        <i class="layui-icon layui-icon-search"></i>
                        查询
                    </button>
                    <button type="reset" class="pear-btn pear-btn-md">
                        <i class="layui-icon layui-icon-refresh"></i>
                        重置
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="layui-card">
    <div class="layui-card-body">
        <table id="user-table" lay-filter="user-table"></table>
    </div>
</div>

<script type="text/html" id="user-toolbar">
    <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">
        <i class="layui-icon layui-icon-add-1"></i>
        新增
    </button>
    <button class="pear-btn pear-btn-danger pear-btn-md" lay-event="batchRemove">
        <i class="layui-icon layui-icon-delete"></i>
        删除
    </button>
</script>

<script type="text/html" id="user-bar">
    <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>
    <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
</script>


@include('admin.layouts.__footer')
<script>
    layui.use(['table', 'form', 'jquery', 'common'], function () {
        let table = layui.table;
        let form = layui.form;
        let $ = layui.jquery;
        let common = layui.common;

        let MODULE_PATH = "operate/";
        let INDEX_PATH = '{{ route('admin.users.index') }}';
        let CREATE_PATH = '{{ route('admin.users.create') }}'
        let cols = [
            [{
                type: 'checkbox'
            },
                {
                    title: '账号',
                    field: 'name',
                    align: 'center',
                    width: 100
                },
                {
                    title: '姓名',
                    field: 'nickname',
                    align: 'center'
                },

                {
                    title: '邮箱',
                    field: 'email'
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
            elem: '#user-table',
            url: INDEX_PATH,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            method: 'post',
            where: window.where,
            page: true,
            cols: cols,
            skin: 'line',
            toolbar: '#user-toolbar',
            defaultToolbar: [{
                title: '刷新',
                layEvent: 'refresh',
                icon: 'layui-icon-refresh',
            }, 'filter', 'print', 'exports']
        });

        table.on('tool(user-table)', function (obj) {
            if (obj.event === 'remove') {
                window.remove(obj);
            } else if (obj.event === 'edit') {
                window.edit(obj);
            }
        });

        table.on('toolbar(user-table)', function (obj) {
            if (obj.event === 'add') {
                window.add();
            } else if (obj.event === 'refresh') {
                window.refresh();
            } else if (obj.event === 'batchRemove') {
                window.batchRemove(obj);
            }
        });

        form.on('submit(user-query)', function (data) {
            table.reload('user-table', {
                where: data.field
            })
            return false;
        });

        form.on('switch(user-enable)', function (obj) {
            layer.tips(this.value + ' ' + this.name + '：' + obj.elem.checked, obj.othis);
        });

        window.add = function () {
            layer.open({
                type: 2,
                title: '新增',
                shade: 0.1,
                area: [common.isModile() ? '100%' : '500px', common.isModile() ? '100%' : '400px'],
                content: CREATE_PATH
            });
        }

        window.edit = function (obj) {
            layer.open({
                type: 2,
                title: '修改',
                shade: 0.1,
                area: ['500px', '400px'],
                content: MODULE_PATH + 'edit.html'
            });
        }

        window.remove = function (obj) {
            layer.confirm('确定要删除该用户', {
                icon: 3,
                title: '提示'
            }, function (index) {
                layer.close(index);
                let loading = layer.load();
                $.ajax({
                    url: MODULE_PATH + "remove/" + obj.data['userId'],
                    dataType: 'json',
                    type: 'delete',
                    success: function (result) {
                        layer.close(loading);
                        if (result.success) {
                            layer.msg(result.msg, {
                                icon: 1,
                                time: 1000
                            }, function () {
                                obj.del();
                            });
                        } else {
                            layer.msg(result.msg, {
                                icon: 2,
                                time: 1000
                            });
                        }
                    }
                })
            });
        }

        window.batchRemove = function (obj) {

            var checkIds = common.checkField(obj, 'userId');

            if (checkIds === "") {
                layer.msg("未选中数据", {
                    icon: 3,
                    time: 1000
                });
                return false;
            }

            layer.confirm('确定要删除这些用户', {
                icon: 3,
                title: '提示'
            }, function (index) {
                layer.close(index);
                let loading = layer.load();
                $.ajax({
                    url: MODULE_PATH + "batchRemove/" + ids,
                    dataType: 'json',
                    type: 'delete',
                    success: function (result) {
                        layer.close(loading);
                        if (result.success) {
                            layer.msg(result.msg, {
                                icon: 1,
                                time: 1000
                            }, function () {
                                table.reload('user-table');
                            });
                        } else {
                            layer.msg(result.msg, {
                                icon: 2,
                                time: 1000
                            });
                        }
                    }
                })
            });
        }

        window.refresh = function (param) {
            table.reload('user-table');
        }
    })
</script>
</body>
</html>
