const express = require("express"),
      path = require('path'),
      opn = require('opn'),
      bodyParser = require('body-parser'),  //接收请求的
      session = require('express-session'), //session
      cookieParser = require('cookie-parser'),  //cookie
      mutipart = require('connect-multiparty'),  //文件上传
      moment = require('moment'),  //时间插件
      Router = require("./src/routers/index"),
      io = require('socket.io')  //socket
      

var app = express();

//设置静态文件加载的路径
app.use(express.static(path.join(__dirname,"/assets/")));

//parse application/x-www-form-urlencoded  设置接收请求的中间层插件
app.use(bodyParser.urlencoded({ extended: false }));

//设置session的中间层 
//rolling 每个请求都重新设置一个cookie，默认为false
//resave: 即使session没有被修改，也保存session值，默认为true
//saveUninitialized：强制未初始化的session保存到数据库
app.use(session({ secret: 'h1902', cookie: { maxAge: 60000 }, rolling: true, resave: false, saveUninitialized: true }));

//不使用签名 设置cookie
app.use(cookieParser());


//文件上传
const mutipartMiddeware = mutipart(); //初始化文件上传对象

//设置为中间层 并且设置文件上传的保存目录
app.use(mutipart({ uploadDir: './assets/uploads'}));


//将应用传递给路由里面
Router(app);

var server = app.listen(3000,function(){
  console.log('服务已启动');
  // opn("http://localhost:3000/user/index", { app: ['google chrome', '--incognito'] });
});

//监听服务
var ws = io.listen(server);

//存放当前连接的socket用户
var socketList = {};

const db = require(path.join(__dirname,"./src/config/db"));

//设置链接方法
ws.on('connection',function(client)
{
  client.on("join", function (username){
    socketList[username] = client;
  });

  //先插入数据库 => 判断这个人在不在线 -> 在线 发送消息  -> 等上线后登录成功后自动查询消息出来
  client.on('message',function(obj){

    var data = {
      fromid:obj.fromid,
      toid: obj.toid,
      content: obj.content,
      status:0,
      createtime: moment().unix()
    }

    if (JSON.stringify(obj) != "{}")
    {
      var from = obj;
      db.table("chat").add(data).then(function (insertId) {
        //成功
        //判断这个人是否有在线，如果有在线然后顺便直接发信息
        if (socketList[from.toUser]) {
          var obj = { content: from.content, fromid: from.toid, fromAvatar: from.fromAvatar, fromUser: from.fromUser, createtime: moment(data.createtime*1000).format('YYYY-MM-DD HH:mm')}
          socketList[from.toUser].emit('notice', obj);
        }
      });
    }
    
  })
})