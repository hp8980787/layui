<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>登录</title>
    <!-- 样 式 文 件 -->
    <link rel="stylesheet" href="/component/pear/css/pear.css"/>
    <link rel="stylesheet" href="/admin/css/other/login.css"/>
</head>
<!-- 代 码 结 构 -->
<body background="/admin/images/background.svg" style="background-size: cover;">
<form class="layui-form" >
    <div class="layui-form-item">
        <img class="logo" src="/admin/images/logo.png"/>
        <div class="title">Pear Admin</div>
        <div class="desc">
            {{ config('app.name') }}
        </div>
    </div>
    <div class="layui-form-item">
        <input name="name" placeholder="账 户 : admin " lay-verify="required" hover class="layui-input"/>
    </div>
    <div class="layui-form-item">
        <input type="password" name="password" placeholder="密 码 : admin " lay-verify="required" hover class="layui-input"/>
    </div>
    <div class="layui-form-item">
        <input name="captcha" placeholder="验证码 : " hover lay-verify="required" class="code layui-input layui-input-inline"/>
        <img width="120px" src="{{ captcha_src() }}" onclick="this.src=`{{ captcha_src() }}?s=${Math.random()}`" class="codeImage"/>
    </div>
    <div class="layui-form-item">
        <input type="checkbox" name="" title="记住密码" lay-skin="primary" checked>
    </div>
    <div class="layui-form-item">
        <button type="button" class="pear-btn pear-btn-success login" lay-submit lay-filter="login">
            登 入
        </button>
    </div>
</form>
<!-- 资 源 引 入 -->
<script src="/component/layui/layui.js"></script>
<script src="/component/pear/pear.js"></script>
<script>
    layui.use(['form', 'button', 'popup', 'jquery'], function () {
        var form = layui.form;
        var button = layui.button;
        var popup = layui.popup;
        let $ = layui.jquery;
        let LOGIN_PATH = '{{ route('admin.users.login') }}';
        // 登 录 提 交
        form.on('submit(login)', function (data) {

            /// 验证

            /// 登录
            $.ajax({
                url: LOGIN_PATH,
                type: 'post',
                contentType: 'application/json',
                headers:{
                    'X-CSRF-TOKEN':'{{ csrf_token() }}'
                },
                data: JSON.stringify(data.field),
                dataType: 'json',
                success: function (d) {
                    if (d.success===true){
                        button.load({
                            elem: '.login',
                            time: 1500,
                            done: function () {
                                popup.success("登录成功", function () {
                                    location.href = "{{ route('admin./') }}"
                                });
                            }
                        })
                    }else{
                        layer.msg(d.msg, {
                            icon: 2,
                            time: 1000,
                        })
                        $('.codeImage').attr('src','{{ captcha_src() }}')
                    }

                }, error: function (error) {
                    layer.msg('登陆失败', {
                        icon: 2,
                        time: 1000,
                    })
                }
            });

            /// 动画

            return false;
        });
    })
</script>
</body>
</html>
