<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="img/favicon.ico">
    <title>ShiYanLou</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body onload="connect()">
    

    <div class="container">
        <div class="row row-offcanvas row-offcanvas-right">
            <div class="col-xs-12 col-sm-9">
                <div class="panel panel-primary">
                  <div class="panel-heading">
                    Room:<span id="roomid"><?php echo isset($_GET['room_id']) ? $_GET['room_id'] : 1 ?></span> || User:<span id="user"></span> <span id="currendtime" style="float: right"></span>
                  </div>
                  <div class="panel-body dialog">
                    <div style="height: 500px;overflow-x:hidden" id="dialog">

                    </div>
                    <hr>
                    <textarea class="form-control" rows="5" id="content" placeholder="chat with your friends!" ></textarea>
                    <br>
                    <button type="button" class="btn btn-warning clear" onclick="clear_dialog()">clear</button>

                    <!-- Split button --> 
                    <div class="btn-group" style="float:right">
                      <button type="button" class="btn btn-info" onclick="onSubmit()">Commit</button>
                      <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                       <!--  <span class="sr-only">Commit</span> -->
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <li class="mth" value="e"><span >✔ &nbsp;</span>Enter to send</li>
                        <li class="mth" value="ec"><span hidden="">✔ &nbsp;</span>Enter+Ctrl to send</li>
                      </ul>
                    </div>

                    <div class="col-md-2" style="float: right">
                        <select class="form-control" id="client_list_select">
                        </select>
                    </div>

                  </div>
                  <div class="panel-footer"> 
                    <p class="cp" style="text-align: center">PHP多进程+Websocket(HTML5/Flash)+PHP Socket实时推送技术&nbsp;&nbsp;&nbsp;&nbsp;Powered by <a href="http://www.workerman.net/workerman-chat" target="_blank">workerman-chat</a>
                    </p>
                  </div>
                </div>
                <a href="/?room_id=1" title="" id="room1"><button type="button" class="btn btn-info roomid">Room1</button></a>
                <a href="/?room_id=2" title="" id="room2"><button type="button" class="btn btn-info roomid">Room2</button></a>
                <a href="/?room_id=3" title="" id="room3"><button type="button" class="btn btn-info roomid">Room3</button></a>
                <a href="/?room_id=4" title="" id="room4"><button type="button" class="btn btn-info roomid">Room4</button></a>
            </div>
            <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
                <div class="panel panel-default">
                  <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        当前房间在线用户：
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body" style="padding: 0">
                      <div class="list-group">
                          <div id="sidebar_client_list">

                          </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div><!--/.sidebar-offcanvas-->
        </div>
    </div>
  </body>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript">
    /*：
      ws:WebSocket对象
      name:用户名
      client_list:在线用户列表
    */
    var ws,name,client_list={};

    //在页面加载时初始化websocket对象
    function connect(){
      //连接服务端
      WEB_SOCKET_DEBUG = true;
      //创建WebSocket
      ws = new WebSocket("ws://127.0.0.1:8282");
      //当socket连接打开时，输入用户名
      ws.onopen = onopen;
      ws.onmessage = onmessage;
      ws.onclose = function(){
        console.log('连接关闭，定时重连');
        setInterval(connect(),10);
      };
      ws.onerror = function(){
        console.log('出现错误');
      };
    }

    function onopen(){
      if(!name){
        show_prompt();
      }
      $('#user').text(name);
      var login_data = '{'+
        '"type":"login",'+
        '"client_name":"'+name.replace(/"/g,'//"')+'",'+
        '"room_id":"<?php echo isset($_GET["room_id"]) ? $_GET["room_id"] : 1;?>"'+
      '}';
      ws.send(login_data);
      //console.log('handshake success,send login_data:'+login_data);
    }

    //显示输入框
    function show_prompt(){
      name = prompt('请输入你的用户名:','');
      if(!name || name == ''){
        name = '游客';
      }
      $('#user').text(name);
    }

    //处理服务器发来的消息
    function onmessage(e){
      var data = JSON.parse(e.data);
      /*//由于后台返回的json数据有json字符串和json数组两种类型，故而要做不同的转换
      if(typeof data != 'object')
      {
        data = eval('('+data+')');
      }*/
      switch(data.type){
        //服务器端ping客户端
        case 'ping':
        ws.send('{"type":"pong"}');
        break;

        //登录 更新用户列表
        case 'login':
          say(data['client_id'],data['client_name'],data['client_name']+'加入了聊天室',data['time'],'login');
          if(data['client_list']){
            client_list = data['client_list'];
          }else{
            client_list[data['client_id']] = data['client_name'];
          }
          flush_client_list();
          console.log(data['client_name']+'登录成功');
          break;

        case 'say':
          say(data['from_client_id'],data['from_client_name'],data['content'],data['time'],'say');
          break;

        case 'logout':
          say(data['from_client_id'],data['from_client_name'],data['from_client_name']+'退出了',data['time'],'logout');
          delete client_list[data['from_client_id']];
          flush_client_list();
      }
    }

    //向对话框中添加消息
    function say(from_client_id,from_client_name,content,time,type){
      if(type == 'say'){
        if(from_client_id == 'mine'){
          var html = '<div class="media">'+
                        '<div class="media-body">'+
                          '<h4 class="media-heading" style="text-align:right">'+
                            '<small>'+time+'</small>&nbsp;&nbsp;&nbsp;'+from_client_name+
                          '</h4>'+
                          '<ul class="list-group">'+
                            '<li class="list-group-item list-group-item-info" style="text-align:right">'+content+'</li>'+
                          '</ul>'+
                        '</div>'+
                        '<a class="media-right" href="#">'+
                          '<img src="img/headimg.png" alt="...">'+
                        '</a>'+
                      '</div>';
          $('#dialog').append(html);
          $('#dialog').scrollTop($('#dialog')[0].scrollHeight);
        }else{
          var html = '<div class="media">'+
                        '<a class="media-left" href="#">'+
                          '<img src="img/headimg.png" alt="...">'+
                        '</a>'+
                        '<div class="media-body">'+
                          '<h4 class="media-heading">'+from_client_name+'  &nbsp;&nbsp;&nbsp;'+
                            '<small> '+time+'</small>'+
                          '</h4>'+
                          '<ul class="list-group">'+
                            '<li class="list-group-item list-group-item-info">'+content+'</li>'+
                          '</ul>'+
                        '</div>'+
                      '</div>';
          $('#dialog').append(html);
          $('#dialog').scrollTop($('#dialog')[0].scrollHeight);
          notify();
        }
      }else if(type == 'logout'){
        $('#dialog').append('<p class="bg-danger" style="text-align: center">'+from_client_name+' logout</p>');
        $('#dialog').scrollTop($('#dialog')[0].scrollHeight);
      }else if(type == 'login'){
        $('#dialog').append('<p class="bg-success" style="text-align: center">'+from_client_name+' login</p>');
        $('#dialog').scrollTop($('#dialog')[0].scrollHeight);
      }
    }

    //刷新在线客户列表
    function flush_client_list(){
      var sidebar_client_list = $('#sidebar_client_list');
      var client_list_select = $('#client_list_select');
      sidebar_client_list.empty();
      client_list_select.empty();
      client_list_select.append('<option value="all">ALL</option>');
      for(var p in client_list){
        if(client_list[p] != $('#user').text()){
          sidebar_client_list.append('<li class="list-group-item chatable" id="'+p+'" onclick="javascript:bindclick(this.id)">'+client_list[p]+'</li>');
          client_list_select.append('<option value="'+p+'">'+client_list[p]+'</option>');
        }else{
          sidebar_client_list.append('<li class="list-group-item disabled" id="'+p+'">'+client_list[p]+'*</li>');
      }
    }
  }

    //点击commit按钮，实现消息发送
    function onSubmit(){
      var to_client_name = $('#client_lsit_sestct option:selected').text();
      var to_client_id = $('#client_list_select option:selected').attr('value');
      var input = $('#content').val();
      var str = '{'+
        '"type":"say",'+
        '"to_client_id":"'+to_client_id+'",'+
        '"to_client_name":"'+to_client_name+'",'+
        '"content":"'+input.replace(/"/g,'\\"').replace(/\n/g,'\\n').replace(/\r/g,'\\r')+'"'+
      '}';
      ws.send(str);
      $('#content').val('');
      //$('#content').foucs();
    }

    //清空消息区
    function clear_dialog(){
      $('#dialog').empty();
      //console.log($('#content'));
      //$('#content').onfoucs();
    }

    //通知新消息
    function notify(){

    }
</script>
</html>