<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'修改'])
<body>
<form action="" class="layui-form" lay-filter="edit-form">
    <div class="mainBox">
        <div class="main-container">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">分类英文</label>
                <div class="layui-input-block">
                    <input name="category" type="text" lay-verify="required" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">分类法语</label>
                <div class="layui-input-block">
                    <input name="category_fr" type="text" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">分类德语</label>
                <div class="layui-input-block">
                    <input name="category_de" type="text" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">分类日语</label>
                <div class="layui-input-block">
                    <input name="category_jp" type="text" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">分类西班牙语</label>
                <div class="layui-input-block">
                    <input name="category_es" type="text" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">分类荷兰语</label>
                <div class="layui-input-block">
                    <input name="category_nl" type="text" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">分类葡萄牙语</label>
                <div class="layui-input-block">
                    <input name="category_pt" type="text" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">分类葡波兰语</label>
                <div class="layui-input-block">
                    <input name="category_pl" type="text" class="layui-input">
                </div>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="button-container">
            <button type="submit" class="pear-btn pear-btn-primary pear-btn-sm" lay-submit=""
                    lay-filter="category-submit">
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

        let headers = {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        form.on('submit(category-submit)', function (data) {
            $.ajax({
                url: '{{ route('admin.categories.store') }}',
                headers: headers,
                method: 'post',
                data: data.field,
                success: function (res) {
                    if (res.success) {
                        layer.msg(res.msg, {
                            icon: 1,
                            time: 1000,
                        }, function () {
                            parent.layer.close(parent.layer.getFrameIndex(window.name));
                        });

                    }else {
                        layer.msg(res.msg, {
                            icon: 2,
                            time: 1000,
                        });
                    }

                }

            });
            return false;
        })
    });
</script>
</body>
</html>
