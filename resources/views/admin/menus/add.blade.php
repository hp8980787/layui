<!DOCTYPE html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'新增'])
<body>
<form class="layui-form" action="">
    @include('admin.layouts.__errors')
    <div class="mainBox">
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">上级菜单</label>
                <div class="layui-input-block">
                    <div id="menus-tree"></div>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">title</label>
                <div class="layui-input-block">
                    <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入标题"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label"> icon</label>

                <div class="layui-input-block">
                    <input type="text" id="iconPicker" lay-filter="iconPicker" style="display:none;">
                    <input type="hidden" name="icon" id="iconPicker2">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">type(类型是否有子集)</label>
                <div class="layui-input-block">
                    <select class="layui-form-select" name="type" id="">
                        <option value="0">没有</option>
                        <option value="1">有</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">打开菜单跳转类型</label>
                <div class="layui-input-block">
                    <select class="layui-form-select" name="open_type" id="">
                        <option value="0">普通</option>
                        <option selected value="_iframe">弹出层</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">路由地址</label>
                <div class="layui-input-block">
                    <input type="text" name="href" lay-verify="href" autocomplete="off" placeholder="请输入路由地址"
                           class="layui-input">
                </div>
            </div>

        </div>
    </div>
    <div class="bottom">
        <div class="button-container">
            <button type="submit" class="pear-btn pear-btn-primary pear-btn-sm" lay-submit=""
                    lay-filter="user-save">
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
    layui.use(['form', 'jquery', 'iconPicker', 'tree'], function () {
        let form = layui.form;
        let $ = layui.jquery;
        let tree = layui.tree;
        let SAVE_PATH = '{{ route('admin.menus.store') }}';
        var iconPicker = layui.iconPicker;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 树形菜单
        var treeJson = "{!! $menusTree !!}"
        console.log(treeJson)
        // var tree1 = tree.render({
        //     elem:'#menus-tree',
        //     data:JSON.parse(treeJson)
        //
        // })
        /**
         *icon选择器
         **/
        iconPicker.render({
            // 选择器，推荐使用input
            elem: '#iconPicker',
            // 数据类型：fontClass/unicode，推荐使用fontClass
            type: 'fontClass',
            // 是否开启搜索：true/false，默认true
            search: true,
            // 是否开启分页：true/false，默认true
            page: true,
            // 每页显示数量，默认12
            limit: 12,
            // 每个图标格子的宽度：'43px'或'20%'
            cellWidth: '43px',
            // 点击回调
            click: function (data) {
                $('#iconPicker2').val(data.icon)
            },
            // 渲染成功后的回调
            success: function (d) {
            }
        });

        form.on('submit(user-save)', function (data) {
            $.ajax({
                url: SAVE_PATH,
                data: JSON.stringify(data.field),
                dataType: 'json',
                contentType: 'application/json',
                type: 'post',
                success: function (result) {
                    console.log(result);
                    if (result.success) {
                        layer.msg(result.msg, {
                            icon: 1,
                            time: 1000
                        }, function () {
                            parent.layer.close(parent.layer.getFrameIndex(window.name)); //关闭当前页
                        });
                    } else {
                        layer.msg(result.msg, {
                            icon: 2,
                            time: 1000
                        });
                    }
                },
                error: function (e) {
                    layer.msg(JSON.parse(e.responseText).message)
                }
            })
            return false;
        });
    })
</script>
<script>
</script>
</body>
</html>
