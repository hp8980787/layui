<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>菜单管理</title>
    <link rel="stylesheet" href="/component/pear/css/pear.css"/>
</head>
<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">搜索</label>
                    <div class="layui-input-inline">
                        <input type="text" name="search" placeholder="" class="layui-input">
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
    @{{# if(window.where.filter.trashed===false) { }}
    <button class="pear-btn pear-btn-danger pear-btn-md" lay-event="batchRemove">
        <i class="layui-icon layui-icon-delete"></i>
        删除
    </button>
    @{{# }else{  }}
    <button class="pear-btn pear-btn-danger pear-btn-md" lay-event="batchRemove">
        <i class="layui-icon layui-icon-delete"></i>
        彻底删除
    </button>
    @{{#  } }}

    @if($trashCount>0)
        @{{# if(window.where.filter.trashed===false) { }}
        <button class="pear-btn-success pear-btn pear-btn-md" lay-event="trash">
            <i class="layui-icon layui-icon-delete"></i>回收站
        </button>
        @{{# }else{  }}
        <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="restoreAll"><i class="layui-icon layui-icon-refresh"></i>
            全部恢复
        </button>
        <button class="pear-btn-success pear-btn pear-btn-md" onclick="window.location.href='{{ route('admin.menus.index') }}'">
            <i class="layui-icon layui-icon-home"></i>首页
        </button>
        @{{#  } }}
    @endif

</script>

<script type="text/html" id="user-bar">
    @{{# if(window.where.filter.trashed===false) { }}
    <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="edit"><i class="layui-icon layui-icon-edit"></i></button>
    <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
    @{{# }else{  }}
    <button class="pear-btn pear-btn-primary pear-btn-sm" lay-event="restore"><i class="layui-icon layui-icon-refresh"></i></button>
    <button class="pear-btn pear-btn-danger pear-btn-sm" lay-event="remove"><i class="layui-icon layui-icon-delete"></i></button>
    @{{# }  }}
</script>

<script type="text/html" id="user-enable">
    <input type="checkbox" name="enable" value="@{{d.status}}" lay-skin="switch" lay-text="启用|禁用" lay-filter="user-enable" @{{
           d.status==
           1 ? 'checked' : '' }} />
</script>


<script src="/component/layui/layui.js"></script>
<script src="/component/pear/pear.js"></script>
<script>
    layui.use(['table', 'form', 'jquery', 'common'], function () {

        let table = layui.table;
        let form = layui.form;
        let $ = layui.jquery;
        let common = layui.common;

        window.where = {
            filter: {
                trashed: false
            },
            restore: false,
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let DELETE_PATH = "{{ route('admin.menus.destroy') }}";
        let CREATED_PATH = '{{ route('admin.menus.create') }}'
        let API_PATH = '{{ route('admin.menus.api') }}'
        let EDIT_PATH = '{{ route('admin.menus.edit') }}'
        let cols = [
            [{
                type: 'checkbox'
            },
                {
                    title: 'id',
                    field: 'id',
                    align: 'center',
                    width: 100,
                    sort: true,
                },
                {
                    title: '名称',
                    field: 'title',
                    align: 'center'
                },
                {
                    title: '图标',
                    field: 'icon',
                    align: 'center',
                    width: 80,
                    templet: function (d) {
                        return `<i class="layui-icon ${d.icon}" ></i>`
                    }
                },
                {
                    title: '是否有子集',
                    field: 'type',
                    align: 'center'
                },
                {
                    title: '状态',
                    field: 'status',
                    align: 'center',
                    templet: '#user-enable'
                },

                {
                    title: '创建时间',
                    field: 'created_at',
                    align: 'center',
                    templet: '#user-createTime'
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
            url: API_PATH,
            method: 'post',
            dataType: 'json',
            where: window.where,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            page: true,
            limits: [2, 10, 20, 30, 50],
            request: {
                pageName: 'page',
                limitName: 'perPage'
            },
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
            } else if (obj.event === 'restore') {
                window.restore(obj.data.id);
            }
        });

        table.on('toolbar(user-table)', function (obj) {
            if (obj.event === 'add') {
                window.add();
            } else if (obj.event === 'refresh') {
                window.refresh();
            } else if (obj.event === 'batchRemove') {
                window.batchRemove(obj);
            } else if (obj.event === 'trash') {
                window.where.filter['trashed'] = 'only';
                table.reload('user-table', {
                    where: window.where
                })
            } else if (obj.event === 'restoreAll') {
                let data = table.checkStatus('user-table').data;
                let ids = [];
                if (data.length < 1) {
                    layer.msg('所选不能为空', {
                        icon: 2
                    })
                    return false;
                }
                for (let i in data) {
                    ids.push(data[i].id);
                }
                window.restore(ids);
            }
        });

        form.on('submit(user-query)', function (data) {
            window.where.filter.title = data.field.search;
            table.reload('user-table', {
                where: window.where
            })
            return false;
        });

        form.on('switch(user-enable)', function (obj) {
            layer.tips(this.value + ' ' + this.name + '：' + obj.elem.checked, obj.othis);
        });

        //排序
        table.on('sort(user-table)', function (obj) {
            let name = obj.field;
            window.where.filter[name] = obj.type === 'desc' ? '-' + name : name;
            table.reload('user-table',{
                where:window.where
            })
        })

        window.add = function () {
            layer.open({
                type: 2,
                title: '新增',
                shade: 0.1,
                area: [common.isModile() ? '100%' : '500px', common.isModile() ? '100%' : '400px'],
                content: CREATED_PATH,
            });
        }

        window.edit = function (obj) {

            layer.open({
                type: 2,
                title: '修改',
                shade: 0.1,
                area: ['500px', '400px'],
                content: `${EDIT_PATH}?id=${obj.data.id}`,
            });
        }

        window.remove = function (obj) {
            layer.confirm('确定要删除该菜单', {
                icon: 3,
                title: '提示'
            }, function (index) {
                layer.close(index);
                let loading = layer.load();
                ajaxDelete(obj.data.id, obj);
            });
        }

        window.batchRemove = function (obj) {
            let data = table.checkStatus('user-table').data;
            let ids = [];
            for (let i in data) {
                ids.push(data[i].id);
            }
            if (ids.length === 0) {
                layer.msg("未选中数据", {
                    icon: 3,
                    time: 1000
                });
                return false;
            }

            layer.confirm('确定要删除这些菜单', {
                icon: 3,
                title: '提示'
            }, function (index) {
                layer.close(index);
                ajaxDelete(ids, obj)
            });
        }

        let ajaxDelete = function (id, obj) {
            let loading = layer.load();
            $.ajax({
                url: DELETE_PATH,
                dataType: 'json',
                type: 'delete',
                data: {
                    id: id
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (result) {
                    layer.close(loading);
                    if (result.success) {
                        layer.msg(result.msg, {
                            icon: 1,
                            time: 1000
                        }, function () {
                            table.reload('user-table', {
                                toolbar: '#user-toolbar'
                            });
                        });
                    } else {
                        layer.msg(result.msg, {
                            icon: 2,
                            time: 1000
                        });
                    }
                }
            })

        }
        window.restore = function (id) {
            window.where.id = id;
            window.where.restore = true;
            table.reload('user-table', {
                where: window.where,
            })
        }
        window.refresh = function (param) {
            table.reload('user-table');
        }
    })
</script>
</body>
</html>
