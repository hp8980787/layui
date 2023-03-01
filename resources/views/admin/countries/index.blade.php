<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'国家管理'])
<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <table class="layui-table" id="country-table" lay-filter="country-table"></table>
    </div>
</div>
@include('admin.layouts.__footer')

<script id="country-toolbar" type="text/html">

</script>
<script>
    layui.use(['table', 'common', 'jquery', 'form'], function () {
        let table = layui.table;
        let $ = layui.jquery;
        let common = layui.common;
        let form = layui.form;

        let headers = {'X-CSRF-TOKEN': '{{ csrf_token() }}'};

        let INDEX_PATH = '{{ route('admin.countries.index') }}';

        let cols = [[
            {
                type: 'checkbox'
            }, {
                title: 'id',
                field: 'id'
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
    })
</script>
</body>
</html>
