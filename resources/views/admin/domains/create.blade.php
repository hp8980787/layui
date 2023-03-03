<!doctype html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'修改'])
<body>
<form action="" class="layui-form" lay-filter="form-domain">
    <div class="mainBox">
        <div class="main-container">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">选择国家:</label>
                <div class="layui-input-block">
                    <select name="country_id" lay-search id="" lay-virify="required" class="layui-select">
                        <option value="">请选择</option>
                        @foreach($countries as $country)
                            <option
                                value="{{ $country->id }}">{{ $country->name }} {{ $country->iso2 }} {{ json_decode($country->translations,true)['cn'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">选择服务器:</label>
                <div class="layui-input-block">
                    <select name="server_id" id="" lay-search lay-virify="required" class="layui-select">
                        <option value="">请选择</option>
                        @foreach($servers as $val)
                            <option value="{{ $val->id }}">{{ $val->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">name</label>
                <div class="layui-input-block">
                    <input type="text" name="name" lay-verify="required" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">网址</label>
                <div class="layui-input-block">
                    <input type="text" name="url" lay-verify="required" class="layui-input">
                </div>
            </div>

        </div>

    </div>
    <div class="bottom">
        <div class="button-container">
            <button type="submit" class="pear-btn pear-btn-primary pear-btn-sm" lay-submit=""
                    lay-filter="domain-update">
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

        form.on('submit(domain-update)', function (data) {
            $.ajax({
                url: '{{ route('admin.domains.store') }}',
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

                    }

                }

            });
            return false;
        })
    });
</script>
</body>
</html>
