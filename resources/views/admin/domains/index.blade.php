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
                    <label class="layui-form-label">国家</label>
                    <div class="layui-input-inline">
                        <select name="country_id" lay-search id="" class="layui-select">
                            <option value="">请选择</option>
                            @foreach($countries as $country)
                                <option
                                    value="{{ $country->id }}">{{ $country->name }} {{ json_decode($country->translations,true)['cn'] }}</option>
                            @endforeach

                        </select>
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
<div id="selectCountryDiv"></div>
@include('admin.layouts.__footer')
{{--table头部工具栏--}}
<script id="domain-toolbar" type="text/html">
    <x-lay-button type="add"></x-lay-button>
    <x-lay-button type="" color="primary" text="检查网站" event="check" icon="layui-icon-find-fill" id="btn-check"></x-lay-button>
</script>

{{--表格内操作--}}
<script id="domain-bar" type="text/html">
    <x-lay-button type="edit"></x-lay-button>
    <x-lay-button type="remove"></x-lay-button>
</script>

{{--服务器选择--}}
<script id="server-select" type="text/html">
    @{{# if(d.server_id){  }}
       <span class="layui-badge layui-bg-green">@{{ d.server.name }}</span>
    @{{# }else{  }}
    <button class="pear-btn pear-btn-primary pear-btn-sm" lay-submit id="country-button" data-server-id="@{{ d.server_id }}" data-id="@{{ d.id }}" lay-filter="country-button">
        <i class="layui-icon layui-icon-search"></i>
    </button>
    @{{# }  }}
</script>

{{--国家选择html--}}
<script id="selectCountry" type="text/html">
    <form id="select-country-form" lay-filter="select-country-form">
        <input type="hidden" name="id" value="@{{ d.id }}">
        <div class="layui-form-item">
            <label for="" class="layui-form-label">选择服务器</label>
            <div class="layui-input-block">
                <select name="server_id" class="layui-select" lay-search>
                    @foreach($servers as $server)
                        <option value="{{ $server->id }}">{{ $server->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
</script>
<script>
    layui.use(['jquery', 'table', 'form', 'common', 'button', 'popup', 'dropdown', 'laytpl'], function () {
        let $ = layui.jquery;
        let table = layui.table;
        let form = layui.form;
        let common = layui.common;
        let button = layui.button;
        let popup = layui.popup;
        let dropdown = layui.dropdown;
        let laytpl = layui.laytpl;

        let INDEX_URL = '{{ route('admin.domains.index') }}';
        let CHECK_URL = '{{ route('admin.domains.check') }}';
        let UPDATE_URL = '{{ route('admin.domains.update') }}';
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
                title: '过期天数',
                templet: function (d) {
                    return d.expired_days <= 30 ? `<span class="layui-badge layui-bg-organe">${d.expired_days}</span>` :
                        `<span class="layui-badge layui-bg-blue">${d.expired_days}</span>`;
                },
                align: 'center',
                width: 120,
                field: 'expired_time',
                sort: true,
            }, {
                title: '所属服务器',
                field: 'server',
                templet: '#server-select'
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
            }, {
                title: '过期时间',
                field: 'expired_time',
            }, {
                title: '域名检查状态',
                align: 'center',
                templet: function (d) {
                    return d.expired_status === '成功' ? `<span class="layui-badge layui-bg-blue">成功</span>` : `<span class="layui-badge layui-bg-danger">失败</span>`;
                }
            }, {
                title: '操作',
                toolbar: '#domain-bar',
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
            defaultToolbar: ['filter', 'print', 'exports']
        });

        dropdown.render({
            elem: '#btn-check',
            data: [
                {
                    title: `<button class="pear-btn pear-btn-sm pear-btn-warming">过期时间</button>`,
                    id: 'expired',
                }, {
                    title: `<button class="pear-btn pear-btn-sm pear-btn-warming">错误页面</button>`,
                    id: 'check'
                }
            ], click: function (data, othis) {
                check(data.id);
            }
        });

        form.on('submit(domain-query)', function (data) {
            window.where.filter = data.field;
            table.reload('domain-table', {
                where: window.where
            })
            return false;
        })
        //头部工具栏
        table.on('toolbar(domain-table)', function (obj) {
            switch (obj.event) {
                case 'add':
                    add();
                    break;
            }
        });

        //表格排序
        table.on('sort(domain-table)', function (obj) {
            let type = '';
            if (obj.type === 'desc') {
                type = '-' + obj.field;
            } else {
                type = obj.field;
            }
            window.where['sort'] = type;
            table.reload('domain-table', {
                where: window.where,
            })
        })

        //新增域名
        let add = function () {

        }
        //修改ajax
        let updateAjax = function (data) {
            $.ajax({
                url: UPDATE_URL,
                headers: headers,
                method: 'put',
                data: data,
                success: function (res) {
                    if (res.success) {
                        layer.msg(res.msg, {
                            icon: 1,
                            time: 1000,
                        })
                    }
                }
            });
            return false;
        }

        //检查域名过期时间
        let check = function (type) {
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
                        type: type,
                    },
                    success: function (res) {
                        if (res.success) {

                            layer.msg(res.msg, {
                                icon: 1,
                                time: 2000
                            }, function () {
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
            // console.log(obj); //当前行的一些常用操作集合
            // console.log(obj.checked); //当前是否选中状态
            // console.log(obj.data); //选中行的相关数据
            // console.log(obj.type); //如果触发的是全选，则为：all，如果触发的是单选，则为：one
        });
        //行内选择国家事件
        form.on('submit(country-button)', function (obj) {
            let data = {id: this.getAttribute('data-id')};
            let htmlDom = $('#select-country');
            let getTpl = selectCountry.innerHTML, selectCountryDiv = document.getElementById('selectCountryDiv');
            let parseHtml = '';
            laytpl(getTpl).render(data, function (html) {
                parseHtml = html;
            });

            layer.open({
                type: 1,
                title: '选择国家',
                skin: 'layui-layer-demo',
                shade: 0.1,
                area: ['400px', '200px'],
                content: parseHtml,
                btn: ['确认', '取消'],
                yes: function (index, layero) {
                    let data = {};
                    // let formData = ($("#select-country-form").serializeArray().map(function (v) {return [v.name, v.value];}));
                    let formData = $("#select-country-form").serializeArray().map(v => {
                        data[v.name] = v.value;
                        return data;
                    });
                    updateAjax(data);
                    layer.close(index);
                }
            });
        });

        //提交选择国家事件

    })
</script>
</body>
</html>
