<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>主页</title>
    <!-- 依 赖 样 式 -->
    <link rel="stylesheet" href="/component/pear/css/pear.css"/>
    <!-- 加 载 样 式 -->
    <link rel="stylesheet" href="/admin/css/loader.css"/>
    <!-- 布 局 样 式 -->
    <link rel="stylesheet" href="/admin/css/admin.css"/>
</head>
<!-- 结 构 代 码 -->
<body class="layui-layout-body pear-admin">
<!-- 布 局 框 架 -->
<div class="layui-layout layui-layout-admin">
    <!-- 顶 部 样 式 -->
    <div class="layui-header">
        <!-- 菜 单 顶 部 -->
        <div class="layui-logo">
            <!-- 图 标 -->
            <img class="logo">
            <!-- 标 题 -->
            <span class="title"></span>
        </div>
        <!-- 顶 部 左 侧 功 能 -->
        <ul class="layui-nav layui-layout-left">
            <li class="collapse layui-nav-item"><a href="#" class="layui-icon layui-icon-shrink-right"></a></li>
            <li class="refresh layui-nav-item"><a href="#" class="layui-icon layui-icon-refresh-1" loading=600></a></li>
        </ul>
        <!-- 多 系 统 菜 单 -->
        <div id="control" class="layui-layout-control"></div>
        <!-- 顶 部 右 侧 菜 单 -->
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item layui-hide-xs"><a href="#" class="menuSearch layui-icon layui-icon-search"></a></li>
            <li class="layui-nav-item layui-hide-xs"><a href="#" class="fullScreen layui-icon layui-icon-screen-full"></a></li>
            <li class="layui-nav-item layui-hide-xs"><a href="javascript:;" id="clear-cache" class="layui-icon layui-icon-refresh"></a></li>
            <li class="layui-nav-item layui-hide-xs message"></li>
            <li class="layui-nav-item user">
                <!-- 头 像 -->

                @if(auth()->user()->image)
                    <a href="javascript:;"><img width="20px" src="{{ auth()->user()->avatar }}" alt=""></a>
                @else
                    <a class="layui-icon layui-icon-username" href="javascript:;"></a>
                @endif

                <!-- 功 能 菜 单 -->
                <dl class="layui-nav-child">

                    <dd><a class="userInfo" user-menu-title="基本资料">基本资料</a></dd>
                    <dd><a href="{{ route('admin.users.logout') }}" class="logout">注销登录</a></dd>
                </dl>
            </li>
            <!-- 主 题 配 置 -->
            <li class="layui-nav-item setting"><a href="#" class="layui-icon layui-icon-more-vertical"></a></li>
        </ul>
    </div>
    <!-- 侧 边 区 域 -->
    <div class="layui-side layui-bg-black">
        <!-- 菜 单 顶 部 -->
        <div class="layui-logo">
            <!-- 图 标 -->
            <img class="logo">
            <!-- 标 题 -->
            <span class="title"></span>
        </div>
        <!-- 菜 单 内 容 -->
        <div class="layui-side-scroll">
            <div id="sideMenu"></div>
        </div>
    </div>
    <!-- 视 图 页 面 -->
    <div class="layui-body">
        <!-- 内 容 页 面 -->
        <div id="content"></div>
    </div>
    <!-- 页脚 -->
    <div class="layui-footer layui-text">
				<span class="left">
					Released under the MIT license.
				</span>
        <span class="center"></span>
        <span class="right">
					Copyright © 2021-2022 pearadmin.com
				</span>
    </div>
    <!-- 遮 盖 层 -->
    <div class="pear-cover"></div>
    <!-- 加 载 动 画 -->
    <div class="loader-main">
        <!-- 动 画 对 象 -->
        <div class="loader"></div>
    </div>
</div>
<!-- 移 动 端 便 捷 操 作 -->
<div class="pear-collapsed-pe collapse">
    <a href="#" class="layui-icon layui-icon-shrink-right"></a>
</div>
<!-- 依 赖 脚 本 -->
<script src="/component/layui/layui.js"></script>
<script src="/component/pear/pear.js"></script>
<!-- 框 架 初 始 化 -->
<script>
    function clear() {
        let loading = layer.load();
        $.ajax({
            url: '{{ route('admin.clear-cache') }}',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            method: 'post',
            beforeSend :function(xmlHttp){
                xmlHttp.setRequestHeader("If-Modified-Since","0");
                xmlHttp.setRequestHeader("Cache-Control","no-cache");
            },
            success: function (res) {
                layer.close(loading);
                $('html[manifest=saveappoffline.appcache]').attr('content', '');

                if (res.success) {
                    layer.msg(res.msg, {
                        icon: 1,
                        time: 1000
                    });
                }
            }
        })
    }

    layui.use(['admin', 'jquery', 'popup', 'drawer', 'common'], function () {

        var $ = layui.jquery;
        var admin = layui.admin;
        var popup = layui.popup;
        var common = layui.common;
        admin.setConfigType("yml");
        admin.setConfigPath("/config/pear.config.yml");
        // parent.layui.admin.refreshThis()
        admin.render();

        // 登出逻辑
        admin.logout(function () {
            popup.success("注销成功", function () {
                location.href = "login.html";
            })
            // 注销逻辑 返回 true / false
            return true;
        })

        // 消息点击回调
        admin.message(function (id, title, context, form) {});
        $('.userInfo').on('click', function () {
            layer.open({
                type: 2,
                title: '新增',
                shade: 0.1,
                area: [common.isModile() ? '100%' : '600px', common.isModile() ? '100%' : '500px'],
                content: '{{ route('admin.users.edit',['id'=>auth()->user()->id]) }}'
            });
        });

        $('#clear-cache').on('click', function () {
            let loading = layer.load();
            $.ajax({
                url: '{{ route('admin.clear-cache') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                method: 'post',
                success: function (res) {
                    var $body = $('body'),
                        $iframe = $('#ef');
                    $body.on('click', '[e-href]', function() {
                        $('.e-header .e-nav-item.e-this').removeClass('e-this');
                        $(this).parent().addClass('e-this');
                        $iframe.attr('src', $(this).attr('e-href'));
                    });

                    localStorage.clear();

                    localStorage.setItem('doc_theme', $body.hasClass('ew-dark') ? 'ew-dark' : '');
                    $iframe.attr('src', $iframe.attr('src'));
                    layer.close(loading);
                    if (res.success) {
                        layer.msg(res.msg, {
                            icon: 1,
                            time: 1000
                        });
                    }
                }
            })
        });


    })
</script>
</body>
</html>
