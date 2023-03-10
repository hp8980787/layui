<!doctype html>
<html lang="zh_CN">
@include('admin.layouts.__header',['title'=>'新增用户'])
<body>
<form action="" class="layui-form">
    @include('admin.layouts.__errors')
    <div class="mainBox">
        <div class="main-container">

            <div class="layui-form-item">
                <label class="layui-form-label">用户名:</label>
                <div class="layui-input-block">
                    <input type="text" name="name" lay-verify="required|name" placeholder="请填写用户名" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">昵称:</label>
                <div class="layui-input-block">
                    <input type="text" name="nickname" lay-verify="required|name" placeholder="请填写name" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">email:</label>
                <div class="layui-input-block">
                    <input type="email" name="email" lay-verify="required|email" placeholder="email" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">password</label>
                <div class="layui-input-block">
                    <input type="password" name="password" lay-verify="required|password" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">确认密码</label>
                <div class="layui-input-block">
                    <input type="password" name="password_confirm" lay-verify="required" class="layui-input">
                </div>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="button-container">
            <button type="submit" class="pear-btn pear-btn-primary pear-btn-sm" lay-submit
                    lay-filter="userCreate">
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
        let form = layui.form;
        let $ = layui.jquery;
        let upload = layui.upload;
        let CREATE_PATH = '{{ route('admin.users.store') }}'
        form.verify({
            name: function (value, item) {
                if (value.length < 2 || value.length > 12) {
                    return '长度不符合';
                }
            },
            password: function (value, item) {
                let confirm = $('input[name="password_confirm"]').val();
                if (value != confirm) {
                    return '两次密码不一致';
                }

            }
        });
        form.on('submit(userCreate)', function (data) {
            $.ajax({
                url: CREATE_PATH,
                type: 'post',
                data: JSON.stringify(data.field),
                dataType: 'json',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (result) {
                    if (result.success) {
                        layer.msg(result.msg, {
                            icon: 1,
                            time: 1000
                        }, function () {
                            parent.layer.close(parent.layer.getFrameIndex(window.name)); //关闭当前页
                            table.reload('data-table');
                        });
                    } else {
                        layer.msg(result.msg, {
                            icon: 2,
                            time: 1000
                        });
                    }
                }, error: function (error) {
                    layer.msg(error.responseJSON.message, {
                        icon: 2,
                        time: 10000,
                    })
                    console.log(error);
                },
            });
            return false;
        })
    });
</script>
</body>
</html>
