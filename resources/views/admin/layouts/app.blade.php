<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@section('title','用户管理')</title>
    <link rel="stylesheet" href="/component/pear/css/pear.css"/>
</head>
<body class="pear-container">
@yield('content')
<script src="/component/layui/layui.js"></script>
<script src="/component/pear/pear.js"></script>
<script>
    layui.use(['jquery'], function () {
        let $ = layui.jquery;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

</script>
@yield('footerJS')
</body>
</html>
