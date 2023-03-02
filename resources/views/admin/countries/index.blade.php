<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'国家管理'])
<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">搜索</label>
                    <div class="layui-input-inline">
                        <input type="text" name="name" placeholder="" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">状态</label>
                    <div class="layui-input-inline">
                        <input type="checkbox" name="status" value="1" lay-skin="switch" @{{ window.where.filter.status?'checked':'' }}>
                    </div>
                </div>
                <div class="layui-form-item layui-inline">
                    <button class="pear-btn pear-btn-md pear-btn-primary" lay-submit lay-filter="country-query">
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
        <table class="layui-table" id="country-table" lay-filter="country-table"></table>
    </div>
</div>
@include('admin.layouts.__footer')

<script id="country-toolbar" type="text/html">

</script>
<script id="switch" type="text/html">
    <input type="checkbox" name="status" value="@{{ d.status }}" data-id="@{{ d.id }}" lay-skin="switch" lay-text="启用|禁用"
           lay-filter="country-status"
           @{{
           d.status==
           1 ? 'checked' : '' }} >

</script>
<script>
    layui.use(['table', 'common', 'jquery', 'form'], function () {
        let table = layui.table;
        let $ = layui.jquery;
        let common = layui.common;
        let form = layui.form;

        let headers = {'X-CSRF-TOKEN': '{{ csrf_token() }}'};

        let INDEX_PATH = '{{ route('admin.countries.index') }}';

        window.where = {
            filter: {},
        }
        let cols = [[
            {
                type: 'checkbox'
            }, {
                title: 'id',
                field: 'id'
            }, {
                title: 'name',
                field: 'name',
            }, {
                title: '中文名称',
                field: 'translations',
                templet: function (d) {
                    return d.translations['cn'];
                }
            }, {
                title: 'iso2',
                field: 'iso2',
            }, {
                title: '货币符号',
                field: 'currency_symbol'
            }, {
                title: '状态',
                field: 'status',
                templet: '#switch'
            }
        ]];

        table.render({
            elem: '#country-table',
            url: INDEX_PATH,
            headers: headers,
            method: 'post',
            request: {
                pageName: 'page',
                limitName: 'perPage',
            },
            where: window.where,
            page: true,
            limits: [20, 30, 50],
            cols: cols,
            toolbar: '#country-toolbar',
            skin: 'line',
        })

        form.on('submit(country-query)', function (data) {
            window.where.filter = data.field;
            console.log(window.where);
            table.reload('country-table', {
                where: window.where,
            });
            return false;
        });
        form.on('switch(country-status)', function (obj) {
            let type = obj.elem.checked ? 1 : 0;
            let data = {
                id: obj.elem.getAttribute('data-id'),
                status: type,
            }
            update(data);
            layer.tips(this.value + ' ' + this.name + '：' + obj.elem.checked, obj.othis);
            table.reload('country-table', {
                where: window.where,
            })

        });

        let update = function (data) {
            $.ajax({
                url: '{{ route('admin.countries.update') }}',
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
    })
</script>
</body>
</html>
