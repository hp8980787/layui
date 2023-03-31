<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'创建数据库'])
<body>
<form action="" class="layui-form">
    <div class="main-box">
        <div class="main-container">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">选择域名</label>
                <div class="layui-input-block">
                    <select id="unit" name="domain_id" id="" lay-search="">

                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">ip</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="host" lay-verify="required|ip">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">端口号</label>
                <div class="layui-input-block">
                    <input type="text" name="port" class="layui-input" lay-verify="required|number">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">数据库</label>
                <div class="layui-input-block">
                    <input type="text" name="database" class="layui-input" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">用户名</label>
                <div class="layui-input-block">
                    <input type="text" name="username" class="layui-input" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">密码</label>
                <div class="layui-input-block">
                    <input type="password" name="password" class="layui-input" lay-verify="required">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">购物表名称</label>
                <div class="layui-input-block">
                    <select name="cart_name" class="layui-select" id="">
                        <option value="">请选择</option>
                        <option value="cart">cart</option>
                        <option value="carts">carts</option>
                        <option value="gouwuche">gouwuche</option>
                        <option value="gwc">gwc</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">产品表名称</label>
                <div class="layui-input-block">
                    <select name="product_name" class="layui-select" id="">
                        <option value="">请选择</option>
                        <option value="product">product</option>
                        <option value="products">products</option>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">购物车表主键</label>
                <div class="layui-input-block">
                    <select name="cart_primary_key" class="layui-select" id="">
                        <option value="">请选择</option>
                        <option value="id">id</option>
                        <option value="gwc_id">gwc_id</option>
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label for="" class="layui-form-label">产品表主键</label>
                <div class="layui-input-block">
                    <select name="product_primary_key" class="layui-select" id="">
                        <option value="">请选择</option>
                        <option value="id">id</option>
                        <option value="pid">pid</option>
                        <option value="gwc_id">gwc_id</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="button-container">
            <button type="submit" class="pear-btn pear-btn-primary pear-btn-sm" lay-submit
                    lay-filter="database-create">
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
    layui.use(['form', 'jquery', 'layselect'], function () {
        const select = layui.layselect;
        const form = layui.form;
        const $ = layui.jquery;

        const headers = {'X-CSRF-TOKEN': '{{ csrf_token() }}'};
        const CREATE_PATH = '{{ route('admin.databases.store') }}';

        select.render({
            elem: "#unit",
            url: '{{ route('admin.databases.domains') }}',//归属类型
            select: 1,//默认选中索引
            success: function (data) {
                console.log(data);
                //初始化完毕，data为绑定到组件上的数组集合
            },
            fail: function (e) {
                //失败时回调
            },
            format: function (data) {
                return {
                    code: data.id,
                    status: data.status,
                    codeName: data.url,
                };
                //对数据进行映射处理，需映射成：code,codeName,status,select,groupName,groupChildren
            },
            onselect: function (data) {
                //点击选中时触发，data为选中的value
            }
        });

        form.on('submit(database-create)', function (data) {
            $.ajax({
                url: CREATE_PATH,
                method: 'post',
                headers: headers,
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify(data.field),
                success: function (res) {
                    if (res.success) {
                        layer.msg(res.msg, {
                            icon: 1,
                            time: 1000,
                        }, function () {
                            parent.layer.close(parent.layer.getFrameIndex(window.name))
                        })
                    } else {
                        layer.msg(res.msg, {
                            icon: 2,
                            time: 1000,
                        });
                    }
                }
            });
            return true;
        })
    });
</script>
</body>
</html>
