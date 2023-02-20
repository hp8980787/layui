<!doctype html>
<html lang="zh_CN">
@include('admin.layouts.__header',['title'=>'新增用户'])
<body>
<form action="" class="layui-form">
    @include('admin.layouts.__errors')
    <div class="mainBox">
        <div class="main-container">
            <div class="layui-form-item">
                <label  class="layui-form-label">username:</label>
                <div class="layui-input-block">
                    <input type="text" name="username" lay-verify="username" placeholder="请填写用户名" class="layui-input">
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

</body>
</html>
