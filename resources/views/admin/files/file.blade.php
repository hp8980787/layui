<!DOCTYPE html>
<html>
@include('admin.layouts.__header',['title'=>'文件夹'])
<body style='padding:10px'>
<button type="button" class="layui-hide" id="test1"></button>
<div class="layui-fluid">
    <div id="fileManager" lay-filter="test"></div>
</div>
@include('admin.layouts.__footer')
<script>

    layui.use(['fileManager', 'layer', 'upload', 'jquery'], function () {
        var fileManager = layui.fileManager
            , $ = layui.$
            , upload = layui.upload
            , layer = layui.layer;
        $('title').html($('title').html() + ' version:' + fileManager.v);
        var upIns = upload.render({
            elem: '#test1' //绑定元素
            , url: '{{ route('admin.files.upload') }}' //上传接口
            , field: 'file[]'
        })
        fileManager.render({
            elem: '#fileManager'
            , method: 'post'
            , id: 'fmTest'
            , btn_upload: true
            , btn_create: true
            , url: '{{ route('admin.files.index') }}'
            , headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            , thumb: {'nopic': '/filemanage/upload/null-100x100.jpg', 'width': 100, 'height': 100}
            , parseData: function (res) {
                console.log(res);
                // data:[{
                //     thumb: 'path'
                //     , type: 'extension'
                //     , path: 'path'
                // }]

                let _res = [];
                _res.code = 0;
                _res.data = res.data;
                _res.count = res.count
                return _res;
            }
            , done: function (res, curr, count) {
                // console.log(res,curr,count)
            }
            , page: {limit: 12}
            , where: {action: 'get_file_data'}
        });
        //监听图片选择事件
        fileManager.on('pic(test)', function (obj) {
            //obj.obj 当前对象
            //obj.data 当前图片数据
            let data = obj.data;
            let user = obj.data.user;
            layer.open({
                title: '图片管理',
                content: `属于 ${user.name} ${user.nickname}`,
                icon: 2,
                btn: ['删除', '取消'],
                yes: function (index, layero) {
                    $.ajax({
                        url: '{{ route('admin.files.destroy') }}',
                        method: 'delete',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        data: {
                            id: obj.data.id,
                        },
                        success: function (res) {
                            if (res.success) {

                                layer.msg(res.msg, {
                                    icon: 1,
                                    time: 1000
                                })
                            } else {

                            }
                        }
                    });
                },
                btn2: function (index, layero) {

                }
            })

        });
        //监听图片上传事件
        fileManager.on('uploadfile(test)', function (obj) {
            //obj.obj 当前对象
            //obj.path 路径
            //更改上传组件参数
            upIns.config.data = {'path': obj.path};
            upIns.config.done = function (res) {
                fileManager.reload('fmTest');
            }
            var e = document.createEvent("MouseEvents");
            e.initEvent("click", true, true);
            document.getElementById("test1").dispatchEvent(e)
        });
        //监听新建文件夹事件
        fileManager.on('new_dir(test)', function (obj) {
            //obj.obj 当前对象
            //obj.folder 文件夹名称
            //obj.path 路径
            e = JSON.parse(e);
            $.post('data.php?action=folder', {'folder': obj.folder, 'path': obj.path}, function (e) {
                layer.msg(e.msg);
                if (e.code == 1) {
                    fileManager.reload('fmTest');
                }
            })
        });
    });
</script>
</body>

</html>
