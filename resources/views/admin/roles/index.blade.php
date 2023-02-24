<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'角色管理'])
<body class="pear-container">
<div class="layui-card">
    <div class="layui-card-body">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <div class="layui-form-item layui-inline">
                    <label class="layui-form-label">搜索关键字</label>
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
        <table id="role-table" lay-filter="role-table"></table>
    </div>
</div>
@include('admin.layouts.__footer')
<script type="text/html" id="role-toolbar">
    <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add">
        <i class="layui-icon layui-icon-add-1"></i>
    </button>
</script>
<script>
    layui.use(['table', 'jquery', 'common', 'form'], function () {
        let $ = layui.jquery;
        let table = layui.table;
        let common = layui.common;

        let INDEX_PATH = '{{ route('admin.roles.index') }}';

        const csrfToken = '{{ csrf_token() }}';
        let headers = {'X-CSRF-TOKEN': csrfToken};

        window.where = {};
        let cols = [
            [{
                type: 'checkbox'
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
            elem: '#role-table',
            url: INDEX_PATH,
            method: 'post',
            headers: headers,
            where: window.where,
            page: true,
            cols: cols,
            skin: 'line',
            toolbar: '#role-toolbar',
            defaultToolbar: [{
                title: '刷新',
                layEvent: 'refresh',
                icon: 'layui-icon-refresh',
            }, 'filter', 'print', 'exports']

        })
    });

</script>
</body>
</html>
