layui.use(['form', 'jquery'], function() {
    var $ = layui.jquery;
    var form = layui.form;
    var layer = layui.layer;
    // 如果服务端不在本机，请把127.0.0.1改成服务端ip
    var port = $("#port").val();
    var uid = $("#uid").val();
    var username = $("#username").val();
    var host = $(location).attr('host');
    var protocol = $(location).attr('protocol');
    var url = protocol+"//"+host+':'+port;
    var socket = io(url);

    // 当连接服务端成功时触发connect默认事件
    socket.on('connect', function(){
        let data = {
            'uid':uid,
            'group':'',
            'username':username,
            'msg':'',
        }
        //向服务器 添加用户
        socket.emit('addUser', data);
        //向服务器发送信息
        socket.emit('onlineUser', data);
        //console.log('connect success');
    });
    socket.on('online',function (msg) {
        //服务端返回数据
        if(msg){
            for(let key in msg){
                let nid = $("#"+key).attr('alt');
                if(key !== uid){
                    if(nid !== key){
                        let html = '<li lay-submit lay-filter="tabEach" alt="'+key+'" class="person" id="'+key+'">';
                        html +='<img src="/imgs/thomas.jpg" alt="">';
                        html +='<span class="name">'+msg[key].username+'</span>';
                        html +='<span class="time"></span>';
                        html +='<span style="display: none;" class="group">'+msg[key].uid+'</span>';
                        html +='<span class="preview">'+msg[key].msg+'</span>';
                        html +='</li>';
                        $("#onlineUser").append(html);

                        let showMessage = '<div class="chat cont" id="sl_'+key+'" style="overflow-y:scroll;" data-chat="'+key+'">';
                        showMessage += '<div class="conversation-start"></div>';
                        showMessage += '';
                        showMessage += '</div>';
                        $("#showChat").append(showMessage);
                    }
                }
            }
        }
    });
    // 服务端通过emit('serverMessage', $msg)触发客户端的serverMessage事件
    socket.on('serverMessage', function(msg){
        let showM = 'per1';
        if(msg.group){
            if(msg.group === uid){
                showM = msg.uid;
            }else{
                showM = msg.group;
            }
        }

        //服务端返回数据
        if(msg.msg){
            let sound = new Howl({
                src: ['/music/wx.mp3'],
                volume: 1.0,
                onend: function () {
                    //alert('We finished with the setup!');
                }
            });
            let html = '<div class="bubble you">'+msg.username+':'+msg.msg+'<div style="font-size:12px;">'+msg.time+'</div></div>';
            if(msg.uid === uid){
                html = '<div class="bubble me" style="text-align:right;">'+msg.msg+'<div style="font-size:12px;">'+msg.time+'</div></div>';
            }else{
                sound.play();
            }
            $("#sl_"+showM).append(html);

            //滚动条置底
            let height = document.querySelector('#sl_'+showM).scrollHeight;
            //document.querySelector("#showMessage").scrollTop = height;
            document.getElementById('sl_'+showM).scrollTop=height;
        }
        //修改标签内容
        $('#'+showM).children("span.preview").html(msg.msg);
    });
    //用户离线
    socket.on('outline',function (uid) {
        //当前uid
        //console.log('用户离开：'+uid);
        //删除在线用户
        $("#"+uid).remove();
        //删除聊天框
        $("#sl_"+uid).remove();
    });
    //聊天切换
    form.on('submit(tabEach)', function(data){
        let id = data.elem.attributes.id.nodeValue;
        //样式切换
        $("#"+id).addClass('active').siblings().removeClass( 'active' );
        let sf = $("#sl_"+id).attr('data-chat');
        $('#sl_'+id).addClass('active-chat').siblings().removeClass( 'active-chat' );
        //聊天分组
        $('#group').val(sf);
        //聊天对象
        let name = $('#'+id).children("span.name").html()
        $("#showName").html(name);
    });
    //提交事件
    form.on('submit(send)', function(data){
        var msg = $("#message").val();
        var group = $("#group").val();
        //layer.msg(msg);
        // 触发服务端的chat message事件
        if(msg){
            let data = {
                'uid':uid,
                'group':group,
                'username':username,
                'msg':msg,
            }
            socket.emit('sendMessage', data);
        }
        $("#message").val('');
        $("#message").focus();
        return false;
    });
    $(window).on('keyup',function(e){
        if(e.keyCode==13){
            // 填敲击键盘enter键后实现的事件
            var msg = $("#message").val();
            var group = $("#group").val();
            // 触发服务端的chat message事件
            if(msg){
                let data = {
                    'uid':uid,
                    'group':group,
                    'username':username,
                    'msg':msg,
                }
                socket.emit('sendMessage', data);
            }
            $("#message").val('');
            $("#message").focus();
        }
    });

    $(document).on('click', '#emoji', function(data){
        $(".photo-emo").toggle();
    });
    $(document).on('click', '.photo-emo', function(data){
        $(".photo-emo").toggle();
    });
    $(document).on('click', '#inputEmoji', function(data){
        let img = data.target.attributes.src.value;
        let msg = '<img src="'+img+'" style="width:45px">';
        let group = $("#group").val();
        // 触发服务端的chat message事件
        let content = {
            'uid':uid,
            'group':group,
            'username':username,
            'msg':msg,
        }
        socket.emit('sendMessage', content);
        //关闭表情
        $(".photo-emo").css("display", "none");
        $(".photo-emo").toggle();

    });

    $(document).on('change', '#inputImage', function(data){
        let pic = this;
        if (!pic.files || !pic.files[0]) {
            return;
        }
        var reader = new FileReader();
        reader.onload = function (evt) {
            var images = evt.target.result;
            let formData =  new FormData($('#DataForm')[0]); //获取整个表单数据
            let wait = 1000;
            //异步发送，把数据提交给php
            $.ajax({
                url: '/index/socket/upload',
                type: "POST",
                data:formData,
                async:true,  //true发送异步请求,false发送同步请求
                processData: false,  // 告诉jQuery不要去处理发送的数据
                contentType: false,   // 告诉jQuery不要去设置Content-Type请求头
                beforeSend: function(){
                    var loading = layer.load(1,
                        {
                            shade:[0.3,'#000'],
                            content:'<span class="loadtip">0%</span>',
                            success: function (layer) {
                                layer.find('.layui-layer-content').css({
                                    'padding-top': '40px',
                                    'width': '100px',
                                });
                                layer.find('.loadtip').css({
                                    'font-size':'20px',
                                    'margin-left':'0px'
                                });
                            }
                        }
                    );
                },
                xhr: function() {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(event) {
                        if (event.lengthComputable) {
                            //通过设置进度条的宽度达到效果
                            var percent = Math.round((event.loaded / event.total) * 100);
                            $('.loadtip').html(percent + '%');//完成进度（文字）
                            //console.log('loading:'+percent);
                        }
                    }, false);
                    return xhr;
                },
                success: function (data) {
                    //权限不足
                    let obj = JSON.parse(data);
                    if(obj.code == 200){
                        //关闭弹出层
                        layer.closeAll();
                        //layer.msg(obj.msg,{icon:1,time:wait,shade:0.3});
                        // 填敲击键盘enter键后实现的事件
                        var msg = '<img id="imgAmplify" src="/'+obj.data.ThumbImg+'" layer-src="/'+obj.data.Img+'">';
                        var group = $("#group").val();
                        // 触发服务端的chat message事件
                        let data = {
                            'uid':uid,
                            'group':group,
                            'username':username,
                            'msg':msg,
                        }
                        socket.emit('sendMessage', data);
                        setTimeout(function(){
                            //重置表单
                            $('#DataForm')[0].reset();
                        },wait);
                    }else{
                        layer.closeAll('loading'); //关闭加载层
                        layer.msg(obj.msg,{icon:2,time:wait,shade:0.3});
                    }
                },
                error: function () {
                    layer.msg("上传失败！",{icon:2,time:wait,shade:0.3});
                    layer.closeAll(); //关闭加载层
                }
            });
        };
        reader.readAsDataURL(pic.files[0]);
    });

    $(document).on('click', '#imgAmplify', function(data){
        var imgAlt=$(this).attr('layer-src');
        var imgSrc=$(this).attr('src');
        var title=$(this).attr('title');
        layer.photos({
            photos: {
                "title": "", //相册标题
                "id": 123, //相册id
                "start": 0, //初始显示的图片序号，默认0
                "data": [   //相册包含的图片，数组格式
                    {
                        "alt": title,
                        "pid": 666, //图片id
                        "src": imgAlt, //原图地址
                        "thumb": imgSrc //缩略图地址
                    }
                ]
            },
            success: function () {
                //以鼠标位置为中心的图片滚动放大缩小
                $(document).on("mousewheel", ".layui-layer-photos", function (ev) {
                    var oImg = this;
                    var ev = event || window.event;//返回WheelEvent
                    //ev.preventDefault();
                    var delta = ev.detail ? ev.detail > 0 : ev.wheelDelta < 0;
                    var ratioL = (ev.clientX - oImg.offsetLeft) / oImg.offsetWidth,
                        ratioT = (ev.clientY - oImg.offsetTop) / oImg.offsetHeight,
                        ratioDelta = !delta ? 1 + 0.05 : 1 - 0.05,
                        w = parseInt(oImg.offsetWidth * ratioDelta),
                        h = parseInt(oImg.offsetHeight * ratioDelta),
                        l = Math.round(ev.clientX - (w * ratioL)),
                        t = Math.round(ev.clientY - (h * ratioT));
                    //设置相册层宽高
                    $(".layui-layer-photos").css({ width: w, height: h, left: l, top: t });
                    //设置图片外div宽高
                    $("#layui-layer-photos").css({ width: w, height: h });
                    //设置图片宽高
                    $("#layui-layer-photos>img").css({ width: w, height: h });
                });
            },
            //销毁回调
            end: function () {},
            anim: 0 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
        });
    });

});