<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'新增角色'])
<body>
<form action="" class="layui-form">
    <div class="mainBox">
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">角色名称</label>
                <div class="layui-input-block">
                    <input type="text" name="name" lay-verify="required" class="layui-input">
                </div>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="button-container">
            <button type="submit" class="pear-btn pear-btn-primary pear-btn-sm" lay-submit=""
                    lay-filter="role-store">
                <i class="layui-icon layui-icon-ok"></i>
                提交
            </button>
            <button type="reset" class="pear-btn pear-btn-sm">
                <i class="layui-icon layui-icon-refresh"></i>
                重置
            </button>
        </div>
    </div>
</form>
@include('admin.layouts.__footer')
<script>
    layui.use(['form', 'jquery'], function () {
        let $ = layui.jquery;
        let form = layui.form;

        let STORE_PATH = '{{ route('admin.roles.store') }}';
        let headers = {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        };
        form.on('submit(role-store)', function (data) {
            $.ajax({
                url: STORE_PATH,
                type: 'post',
                headers: headers,
                data: data.field,
                success: function (res) {
                    if (res.success) {

                    } else {
                        layer.msg(res.msg, {
                            icon: 2,
                            time: 1000
                        })
                    }
                },
                error: function (error) {

                }

            })
            return false;
        })
    })
</script>
</body>
</html>
