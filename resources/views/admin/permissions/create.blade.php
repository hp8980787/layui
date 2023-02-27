<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'新增权限'])
<body class="pear-container">
<form action="" class="layui-form">
    <div class="mainBox">
        <div class="main-container">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">name</label>
                <div class="layui-input-block">
                    <input type="text" name="name" lay-verify="required" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">url</label>
                <div class="layui-input-block">
                    <input type="text" name="url"  lay-verify="" class="layui-input">
                </div>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="button-container">
            <button type="submit" class="pear-btn pear-btn-primary pear-btn-sm" lay-submit=""
                    lay-filter="permission-create">
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
        let STORE_PATH = '{{ route('admin.permissions.store') }}';
        let headers = {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        form.on('submit(permission-create)', function (data) {
            let loading = layer.load();
            $.ajax({
                url: STORE_PATH,
                method: 'post',
                headers: headers,
                data: data.field,
                dataType: 'json',
                success: function (res) {
                    layer.close(loading);
                    if (res.success) {
                        layer.msg(res.msg,{
                            icon:1,
                            title:'成功'
                        },function () {
                            parent.layer.close(parent.layer.getFrameIndex(window.name));
                        })
                    } else {
                        layer.msg(res.msg,{
                            icon:2,
                            time:1000
                        })
                    }
                }, error: function (error) {

                }
            });
            return false;
        })
    })
</script>
</body>
</html>
