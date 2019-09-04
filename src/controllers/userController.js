const xtpl = require('xtpl');
const path = require('path');
const md5 = require('md5');
const moment = require('moment');

const base = require("./baseController.js");

//数据库的连接文件
const db = require(path.join(__dirname,"../config/db"));


//配置邮件发送
var nodemailer = require("nodemailer");
var smtpTransport = require('nodemailer-smtp-transport');
var wellknown = require("nodemailer-wellknown");


var config = wellknown("163");   //所选择的第三方服务

config.auth = {
  user: 'dancefunk@163.com',  //邮件账号
  pass: 'h1902123'   //这里密码不是163密码，是你设置的smtp授权密码
}

//让第三方服务生效
var transporter = nodemailer.createTransport(smtpTransport(config));

//会员首页
module.exports.index = function(req,res)
{
  var fromid = req.cookies.user.userid ? req.cookies.user.userid : 0;
  var sql = `SELECT chat.*,user.avatar,user.username FROM pre_chat AS chat LEFT JOIN pre_user AS user ON chat.toid = user.id WHERE chat.fromid = ${fromid} AND chat.status = 0 GROUP BY chat.toid`;
  db.query(sql).then(function(chatlist){
    
    for(var k in chatlist)
    {
      chatlist[k].createtime = moment(chatlist[k].createtime * 1000).format('YYYY-MM-DD HH:mm')
    }

    var render = {
      res: res,
      req: req,
      file: "index.html",
      data: { chatlist: chatlist}
    };
    base.render(render);

  });
}

//会员信息
module.exports.info = function (req, res) 
{
  var userid = req.cookies.user.userid;

  db.table("user").where({id:userid}).find().then(function(user){
    
    //renderFile(模板路径，数据，回调)
    var render = {
      res: res,
      req: req,
      file: "info.html",
      data:{info:user}
    };
    base.render(render);
  })
  
}

//提交会员信息
module.exports.infoData = function(req,res)
{
  //先获取到用户id
  var userid = req.cookies.user.userid;
  db.table("user").where({id:userid}).find().then(function(user){

    var data = {};

    //当输入的邮箱与原来的邮箱不一样就要重新验证
    if (user.email != req.body.email)
    {
      data.email = req.body.email;
      data.status = 0;
    }

    if (req.body.password != "") 
    {
      //获取密码盐
      var salt = base.randomStr();
      var repass = req.body.password;
      var password = md5(repass + salt);
      data.password = password;
    }


    //判断是否有图片上传
    if (JSON.stringify(req.files) != "{}" && JSON.stringify(req.files.avatar.size) > 0)
    {
      var filename = path.basename(req.files.avatar.path);
      var avatar = path.join("/uploads/",filename);
      data.avatar = avatar;
    }

    db.table("user").where({id:userid}).update(data).then(function(affect){

      if(data.avatar)
      {
        //更新完用户信息之后要更新一下cookie缓存
        res.cookie("user", { userid: userid, username: user.username, avatar: data.avatar }, { maxAge: 3600 * 24 * 1000 });
      }
      //更新成功
      res.redirect("/user/info");

    }).catch(function(err){
      base.alert({res:res,msg:"更新用户资料失败"});
    })

  });
  

  
}

module.exports.email = function(req,res)
{
  var userid = req.cookies.user.userid;
  db.table("user").where({id:userid}).find().then(function(user){
    if(!user.email)
    {
      return false;
    }

    var content = `<a href="http://localhost:3000/user/userCheck?userid=${user.id}">点击链接完成认证</a>`;

    var mailOptions = {
      from: "dancefunk@163.com",   //发送方的邮件地址
      to: user.email,    //收件人的邮件地址
      subject: "问答社区 - 邮箱验证",  //邮件主题
      text: "text plain",    //邮件文档类型
      html: `<div>${content}</div>`   //邮件正文内容
    };

    //发送邮件
    transporter.sendMail(mailOptions, function (error, info) {
      if (error) 
      {
        console.log(error);
        base.alert({res:res,msg:"邮件发送失败，请稍后再试"});
        return false;
      }else{
        console.log('Message sent: ' + info.response);
        base.alert({ res: res, msg: "邮件发送成功，请注意查收" });
      }
    });


  });
}

module.exports.userCheck = function(req,res)
{
  var userid = req.query.userid ? req.query.userid : 0;
  db.table("user").where({id:userid}).find().then(function(user){
    if(JSON.stringify(user) == "{}")
    {
      base.alert({res:res,msg:"该用户不存在"});
    }else{
      var data = {
        status:1
      };

      db.table("user").where({id:userid}).update(data).then(function(affect){
        //更新状态成功
        base.alert({ res: res, msg: "邮箱验证成功",url:"/user/info" });
      }).catch(function(err){
        //更新失败
        base.alert({ res: res, msg: "邮箱验证失败", url: "/user/info"});
      });
    }
  })
}

//注册
module.exports.register = function(req,res)
{
  var render = {
    res: res,
    req: req,
    file: "register.html"
  };
  base.render(render);
}

//注册的post方法
module.exports.registerData = function(req,res)
{
  //验证码
  if(req.body.imgcode != req.session.imgcode)
  {
    base.alert({res:res,msg:"验证码错误，请重新输入"});
    return false;
  }

  //判断用户名是否存在
  var username = req.body.username;
  db.table('user').where({username:username}).find().then(function(check){
    if (JSON.stringify(check) != "{}")
    {
      base.alert({res:res,msg:"该用户已存在"});
      return false;
    }

    //获取密码盐
    var salt = base.randomStr();
    var repass = req.body.password;
    var password = md5(repass+salt);

    //添加到数据库里面
    var data = {
      username:username,
      password:password,
      salt:salt,
      createtime: moment().unix()
    };

    db.table("user").add(data).then(function(userid){
      //注册成功
      res.redirect('/user/login');

    }).catch(function(err){
      base.alert({res:res,msg:err});
      return false;
    });
  });
}

//登录
module.exports.login = function(req, res)
{
  var render = {
    res: res,
    req: req,
    file: "login.html"
  };
  base.render(render);
}

//登录提交
module.exports.loginData = function(req,res)
{
  //验证码
  if (req.body.imgcode != req.session.imgcode) 
  {
    base.alert({ res: res, msg: "验证码错误，请重新输入" });
    return false;
  }

  //先查找用户
  db.table('user').where({username:req.body.username}).find().then(function(user){
    if(JSON.stringify(user) == "{}")
    {
      base.alert({res:res,msg:"该用户不存在"});
    }

    //验证密码
    var salt = user.salt;
    var repass = req.body.password;
    var password = md5(repass+salt);
    if(user.password != password)
    {
      base.alert({res:res,msg:"您输入的密码有误"});
    }

    //设置cookie
    res.cookie("user", { userid: user.id, username: user.username, avatar: user.avatar }, { maxAge:3600*24*1000});

    res.redirect("/user/groupList");
  });
}

//找回密码
module.exports.password = function(req, res)
{
  var render = {
    res: res,
    req: req,
    file: "password.html"
  };
  base.render(render);
}


//分组
module.exports.groupAdd = function(req,res)
{
  var render = {
    res: res,
    req: req,
    file: "groupAdd.html"
  };
  base.render(render);
}

module.exports.groupAddData = function(req,res)
{
  var userid = req.cookies.user.userid;
  var name = req.body.name;
  db.table("user_group").where({id:userid,name:name}).find().then(function(group){

    if(JSON.stringify(group) != "{}")
    {
      base.alert({res:res,msg:"该分组已经存在，请重新填写"});
    }else{

      var data = {
        name:name,
        userid:userid,
      }

      //不存在就追加
      db.table('user_group').add(data).then(function(affect){
        base.alert({ res: res, msg: "添加成功", url:"/user/groupList"});
      }).catch(function(err){
        base.alert({res:res,msg:"添加失败,请重新在试"});
      });
    }
  })
}


//显示分组
module.exports.groupList = function(req,res)
{
  var userid = req.cookies.user.userid;

  db.table("user_group").where({ userid: userid }).order("id ASC").select().then(function(group){
    if(JSON.stringify(group) == "{}")
    {
      group = null;
    }

    var sql = `SELECT friends.*,friend.username,friend.avatar FROM pre_user_friends AS friends LEFT JOIN pre_user AS user ON friends.userid = user.id LEFT JOIN pre_user AS friend ON friends.friend = friend.id WHERE friends.userid = ${userid} AND friends.status = 1 ORDER BY friends.id ASC`;
    db.query(sql).then(function(userlist){
      if (JSON.stringify(userlist) != "{}")
      {
        for(var i=0;i<userlist.length;i++)
        {
          for(var s=0;s<group.length;s++)
          {
            if(userlist[i].groupid == group[s].id)
            {
              if (!Array.isArray(group[s].userlist))
              {
                group[s].userlist = [];
              }

              group[s].userlist.push(userlist[i]);
            }
          }
        }
      }

      //渲染模板
      var render = {
        res: res,
        req: req,
        file: "groupList.html",
        data: { group: group}
      };
      base.render(render);
      
    })


  })

}

//聊天窗口
module.exports.groupChat = function(req,res)
{
  //好友的id
  var friendid = req.query.friend ? req.query.friend : 0;

  //自己的id
  var userid = req.cookies.user.userid ? req.cookies.user.userid : 0;

  //先查询朋友
  db.table("user").where({ id: friendid}).find().then(function(friend){
    if(JSON.stringify(friend) == "{}")
    {
      res.redirect("/user/groupList");
    }

    //既可以作为发送方  又可以作为接受方
    var sql = `SELECT * FROM pre_chat WHERE (toid = ${userid} AND fromid = ${friendid}) OR (fromid = ${userid} AND toid = ${friendid})`;
    db.query(sql).then(function(chatlist){

      if(chatlist)
      {
        for (var k in chatlist) 
        {
          chatlist[k].createtime = moment(chatlist[k].createtime * 1000).format('YYYY-MM-DD HH:mm')
        }
      }

      var render = {
        res: res,
        req: req,
        file: "chat.html",
        data: { friend: friend, chatlist: chatlist }
      };
      base.render(render);
      
    })
    
  })

  
}

//分组结束

module.exports.logout = function(req,res)
{
  res.cookie('user', null, { maxAge: 0 });
  res.redirect("/user/login");
}