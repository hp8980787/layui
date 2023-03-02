<!DOCTYPE html>
<html lang="en">
@include('admin.layouts.__header',['title'=>'新增'])
<body>
<form class="layui-form" action="" lay-filter="update-form">
    <input type="hidden" name="id">
    @include('admin.layouts.__errors')
    <div class="mainBox">
        <div class="main-container">
            <div class="layui-form-item">
                <label class="layui-form-label">ip</label>
                <div class="layui-input-block">
                    <input type="text" name="ip" lay-verify="required|ip" autocomplete="off" placeholder="请输入ip"
                           value="{{ $server->ip }}" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">服务器名称</label>
                <div class="layui-input-block">
                    <input type="text" name="name" value="{{ $server->name }}" lay-verify="required" autocomplete="off"
                           placeholder="请输入服务器名称"
                           class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">服务器登录用户</label>
                <div class="layui-input-block">
                    <input type="text" name="user" lay-verify="required" value="{{ $server->user }}" autocomplete="off"
                           placeholder="请输入服务器用户"
                           class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">服务器国家</label>
                <div class="layui-input-block">
                    <select name="country_id" lay-search lay-verify="required" class="layui-select" id="">
                        <option value="">请选择</option>
                        @foreach($countries as $country)
                            <option
                                value="{{ $country->id }}">{{ $country->name }} {{ json_decode($country->translations,true)['cn'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="button-container">
            <button type="submit" class="pear-btn pear-btn-primary pear-btn-sm" lay-submit=""
                    lay-filter="server-update">
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
        let headers = {'X-CSRF-TOKEN': '{{ csrf_token() }}'};
        let UPDATE_PATH = '{{ route('admin.servers.update') }}';

        $('input[name="ip"]').on('blur', function () {
            let value = $('input[name="ip"]').val();
            const reg = new RegExp(/^(?:25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d)){3}(?:\/(?:3[0-2]|[1-2]?\d)\.(?:3[0-2]|[1-2]?\d))?(?:,(?:25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d)(?:\.(?:25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d)){3}(?:\/(?:3[0-2]|[1-2]?\d)\.(?:3[0-2]|[1-2]?\d))?)*$/)
            if (!value || value === '' || value === undefined) {
                return false;
            }
            let loading = layer.load();
            if (reg.test(value)) {
                $.ajax({
                    url: '/api/get-ip-info',
                    method: 'get',
                    dataType: 'json',
                    data: {
                        ip: value,
                    }, success: function (res) {

                        let countryId = res.country.country_id;
                        form.val('server-form', {
                            country_id: countryId
                        })
                        layer.close(loading);
                    }
                })
            } else {
                layer.msg('ip格式不正确', {
                    icon: 2,
                    time: 1000
                }, function () {
                    layer.close(loading);
                });

            }

        });

        form.val('update-form', {
            id: '{{ $server->id }}',
            country_id: '{{ $server->country_id }}'
        })
        form.on('submit(server-update)', function (data) {
            console.log(data);
            $.ajax({
                url: UPDATE_PATH,
                data: JSON.stringify(data.field),
                dataType: 'json',
                contentType: 'application/json',
                headers: headers,
                type: 'put',
                success: function (result) {
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

</body>
</html>
