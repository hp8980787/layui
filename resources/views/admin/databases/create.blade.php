<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'创建数据库'])
<body>
<form action="" class="layui-form">
    <div class="main-box">
        <div class="main-container">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">ip</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="host" required>
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

</script>
</body>
</html>
