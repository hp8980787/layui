<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'新增角色'])
<body>
<form action="" class="layui-form">
    <input type="hidden" name="id" value="{{ $role->id }}">
    <div class="mainBox">
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">角色名称</label>
                <div class="layui-input-block">
                    <input type="text" name="name" value="{{ $role->name }}" lay-verify="required" class="layui-input">
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

        let UPDATE_PATH = '{{ route('admin.roles.update') }}';
        let headers = {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        };
        form.on('submit(role-store)', function (data) {
            $.ajax({
                url: UPDATE_PATH,
                type: 'put',
                headers: headers,
                data: data.field,
                success: function (res) {
                    if (res.success) {
                        layer.msg(res.msg, {
                            icon: 1,
                            time: 1000
                        },function () {
                            parent.layer.close(parent.layer.getFrameIndex(window.name)); //关闭当前页
                        });

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
