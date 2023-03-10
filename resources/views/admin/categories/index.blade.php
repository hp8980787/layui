<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'产品分类管理'])
<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <table id="category-table" lay-filter="category-table"></table>
    </div>
</div>
@include('admin.layouts.__footer');

<script id="table-toolbar" type="text/html">
    <x-lay-button type="add"></x-lay-button>
    <x-lay-button type="remove"></x-lay-button>
</script>
<script id="table-bar" type="text/html">
    <x-lay-button text="false" type="edit"></x-lay-button>
    <x-lay-button text="false" type="remove"></x-lay-button>
</script>
<script>
    layui.use(['table', 'form', 'common', 'jquery'], function () {
        let table = layui.table;
        let $ = layui.jquery;
        let common = layui.common;
        let headers = {'X-CSRF-TOKEN': '{{ csrf_token() }}'}

        let INDEX_PATH = '{{ route('admin.categories.index') }}';
        let CREATE_PATH = '{{ route('admin.categories.create') }}';
        let DELETE_PATH = '{{ route('admin.categories.destroy') }}';
        window.where = {};
        let cols = [[
            {
                type: 'checkbox'
            }, {
                title: 'id',
                field: 'id'
            }, {
                title: '英语',
                field: 'category',
            }, {
                title: '法语',
                field: 'category_fr',
            }, {
                title: '德语',
                field: 'category_de'
            }, {
                title: '日语',
                field: 'category_jp'
            }, {
                title: '意大利语',
                field: 'category_it',
            }, {
                title: '西班牙语',
                field: 'category_es'
            }, {
                title: '波兰语',
                field: 'category_pl',
            }, {
                title: '葡萄牙语',
                field: 'category_pt'
            }, {
                title: '荷兰语',
                field: 'category_nl'
            }, {
                title: '操作',
                toolbar: '#table-bar'
            }
        ]];

        table.render({
            elem: '#category-table',
            url: INDEX_PATH,
            method: 'post',
            headers: headers,
            request: {
                pageName: 'page',
                limitName: 'perPage'
            },
            where: window.where,
            page: true,
            limits: [20, 30, 50],
            cols: cols,
            skin: 'line',
            toolbar: '#table-toolbar'
        });
        table.on('toolbar(category-table)', function (obj) {
            switch (obj.event) {
                case 'add':
                    add();
                    break;
            }
        });
        table.on('tool(category-table)', function (obj) {
            switch (obj.event) {
                case 'edit':
                    edit(obj);
                    break;
                case 'remove':
                    remove(obj);
                    break;
            }
        });


        //编辑
        let edit = function (obj) {
            layer.open({
                type: 2,
                title: '编辑',
                shade: 0.1,
                area: [common.isModile() ? '100%' : '500px', common.isModile() ? '100%' : '500px'],
                content: obj.data.editUrl,
                end: function () {
                    window.refresh();
                }
            })
        };

        let add = function (obj) {
            layer.open({
                type: 2,
                title: '编辑',
                shade: 0.1,
                area: [common.isModile() ? '100%' : '500px', common.isModile() ? '100%' : '500px'],
                content: CREATE_PATH,
                end: function () {
                    window.refresh();
                }
            })
        };

        let remove = function (obj) {
            layer.confirm('是否删除?',{
                yes:function () {
                    $.ajax({
                        url: DELETE_PATH,
                        method: 'delete',
                        headers: headers,
                        data: {
                            id: obj.data.id
                        }, success: function (res) {
                            if (res.success) {
                                layer.msg(res.msg, {
                                    icon: 1,
                                }, function () {
                                    window.refresh()
                                })
                            } else {
                                layer.msg(res.msg, {
                                    icon: 2,
                                });
                            }

                        }

                    });
                    return false;
                }
            })

        }
        window.refresh = function () {
            table.reload('category-table', {
                where: window.where
            })
        }


    })
</script>
</body>
</html>
