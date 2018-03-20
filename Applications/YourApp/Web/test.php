<!DOCTYPE html>
<html>
    <head> 
        <meta charset="utf-8">
        <title>websocket实时通讯</title>
        <style type="text/css">
            .personList:hover {
                background-color: #CCF1F2;
            }

            .personList div {
                padding: 3px 0px 0px 5px;
            }

                .personList div img {
                    width: 20%;
                    border-radius: 50%;
                    max-width: 50px;
                }

                .personList div div {
                    width: 80%;
                    float: right;
                    padding-left: 5%;
                }

                    .personList div div .lpName {
                        font-size: 15px;
                    }

                    .personList div div .lpEmail {
                        color: #aaa;
                    }

                .personList div span.toId, span.type, span.contactId {
                    display: none;
                }

            .messageNum {
                background: #FF6C60;
                border-radius: 2px;
                -webkit-border-radius: 2px;
                font-size: 10px;
                font-weight: normal;
                line-height: 13px;
                padding: 2px 5px;
                min-width: 10px;
                color: #fff;
                float: right;
                margin-right: 5px;
            }

            #nowcontact {
                display: none;
            }

            .chatPanel {
                display: none;
            }
            a[rel="lightbox-tour"] img{
                max-height:300px;
                max-width:400px;
            }
            .wangEditor-container{
                width:90%;
                float:left;
            }
            .form-inline{
                height:100px;
            }

        </style>
        <link href="css/banneralert.css" rel="stylesheet" />
        <link href="css/lightbox.css" rel="stylesheet" />
        <link href="css/wangEditor.css" rel="stylesheet" />
    </head>
    <body>
        <div class="row">
            <div class="col-md-3 col-md-offset-1">
            <div class="panel">
                <header class="panel-heading">
                    <span class="input-group">
                        <input type="text" class="form-control" placeholder="查找-姓名/邮箱/身份证" id="searchContent" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default" id="searchButton"><i class="fa fa-search"></i></button>
                        </span>
                    </span>
                </header>
                <div style="height: 520px; overflow: scroll;">
                    <ul class="chats cool-chat" id="listUl" style="margin-right: 0" onselectstart='return false'>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel chatPanel">
                <span id="nowcontact">none</span>
                <header class="panel-heading">
                    与<span id="contactname"></span>聊天中
                </header>
                <div class="panel-body">
                    <div style="height: 365px; overflow: scroll;" id="bannerArea">
                        <div style="text-align: center;"><a href="javascript:SeeHistory();">查看更多消息</a></div>
                        <ul class="chats cool-chat" id="chatContentArea">
                        </ul>
                    </div>
                    <div class="chat-form ">
                        <div role="form" class="form-inline">
                            <div style="width:90%">
                                <textarea style="resize:vertical" placeholder="输入消息内容" class="form-control" id="MessageContent"></textarea>
                            </div>
                            <div style="width: 9%;float:right;text-align:center;line-height:8">
                                <button class="btn btn-primary" id="SendMessageButton" style="width:100%" type="button"><i class="fa fa-location-arrow"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/moment-2.2.1.js"></script>
    <script src="js/jquery.nicescroll.js"></script>
    <script type="text/javascript" src="js/banneralert.min.js"></script>
    <script type="text/javascript" src="js/lightbox.js"></script>
    <script type="text/javascript" src="js/wangEditor.min.js"></script>
    <script type="text/javascript" src="js/wangEditorEx.js"></script>
    <script type="text/javascript">
        $(".chats").parents("div").niceScroll({
            cursorcolor: "rgb(101, 206, 167)",
            cursoropacitymax: 1,
            touchbehavior: false,
            cursorwidth: "5px",
            cursorborder: "0",
            cursorborderradius: "5px"
        });
        
    </script>
</html>