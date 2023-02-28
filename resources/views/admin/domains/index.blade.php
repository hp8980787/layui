<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'域名管理'])
<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">搜索</label>
                    <div class="layui-input-inline">
                        <input type="text" name="url" placeholder="" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <button class="pear-btn pear-btn-md pear-btn-primary" lay-submit lay-filter="domain-query">
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
        <table id="domain-table" lay-filter="domain-table"></table>
    </div>
</div>
@include('admin.layouts.__footer')
{{--table头部工具栏--}}
<script id="domain-toolbar" type="text/html">
    <x-lay-button type="add"></x-lay-button>
    <x-lay-button type="" color="primary" text="检查网站" event="check" icon="layui-icon-find-fill"
                  id="btn-check"></x-lay-button>
</script>

<script>
    layui.use(['jquery', 'table', 'form', 'common', 'button', 'popup'], function () {
        let $ = layui.jquery;
        let table = layui.table;
        let form = layui.form;
        let common = layui.common;
        let button = layui.button;
        let popup = layui.popup;

        let INDEX_URL = '{{ route('admin.domains.index') }}';
        let CHECK_URL = '{{ route('admin.domains.check') }}';

        let headers = {'X-CSRF-TOKEN': '{{ csrf_token() }}'}

        window.where = {
            select: 'one',
        };

        let cols = [[
            {
                type: 'checkbox'
            }, {
                title: 'id',
                field: 'id',
                width: 40
            }, {
                title: '国家',
                templet: function (d) {
                    return d.country.name;
                },
                width: 120,
            },
            {
                title: '国家中文',
                templet: function (d) {
                    return d.country.translations.cn;
                },
                width: 140,
            }, {
                title: '货币',
                width: 40,
                templet: function (d) {
                    return d.country.currency;
                }
            }, {
                title: '货币符号',
                templet: function (d) {
                    return d.country.currency_symbol;
                },
                align: 'center',
                width: 100
            }, {
                title: 'name',
                field: 'name',
            }, {
                title: '网址',
                field: 'url',
                templet: function (d) {
                    return `<a href='${d.url}' target="_blank">${d.url}</a>`;
                }
            }
        ]];

        table.render({
            elem: '#domain-table',
            url: INDEX_URL,
            dataType: 'json',
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            where: window.where,
            request: {
                pageName: 'page',
                limitName: 'perPage',
            },
            page: true,
            limits: [20, 30, 50],
            cols: cols,
            toolbar: '#domain-toolbar',
            skin: 'line',
            defaultToolbar: [{
                title: '刷新',
                layEvent: 'refresh',
                icon: 'layui-icon-refresh',
            }, 'filter', 'print', 'exports']
        });

        form.on('submit(domain-query)', function (data) {
            console.log(data.field.search);
            window.where.filter = data.field;
            table.reload('domain-table', {
                where: window.where
            })
            return false;
        })

        table.on('toolbar(domain-table)', function (obj) {
            switch (obj.event) {
                case 'check':
                    check(obj);
                    break;
            }
        });
        let check = function (obj) {
            let data = table.checkStatus('domain-table').data;
            if (data.length < 1) {
                layer.msg('必须选择一个域名', {
                    icon: 2,
                    time: 1000,
                });
                return false;
            } else {
                let id = [];
                for (let i in data) {
                    id.push(data[i].id);
                }
                $.ajax({
                    url: CHECK_URL,
                    method: 'post',
                    headers: headers,
                    data: {
                        id: id,
                        select: window.where.select,
                    },
                    success: function (res) {
                        if (res.success) {

                            layer.msg(res.msg, {
                                icon: 1,
                                time: 2000
                            }, function () {
                                console.log(1111);
                                button.load({
                                    elem: '#btn-check',
                                    time: 5000,
                                    done: function () {
                                        popup.success("加载完成");
                                    }
                                });
                            })
                        }
                    }, error: function (error) {

                    }
                });
                return false;
            }
        }

        table.on('checkbox(domain-table)', function (obj) {
            let type = obj.type;
            if (type === 'all' && obj.checked) {
                layer.confirm('是否选择全部页面域名', {
                    icon: 3,
                    buttons: ['是', '否'],
                    yes: function (index, layger) {
                        window.where.select = 'all';
                        layer.msg('已选择所有域名');
                        layer.close(index);
                    },
                    btn2: function (index) {
                        layer.close(index);
                    }
                })
            }
            console.log(obj); //当前行的一些常用操作集合
            console.log(obj.checked); //当前是否选中状态
            console.log(obj.data); //选中行的相关数据
            console.log(obj.type); //如果触发的是全选，则为：all，如果触发的是单选，则为：one
        });
    })
</script>
</body>
</html>
