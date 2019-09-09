var express = require('express');
var path = require('path');

var userRouter = express.Router();


//引入数据库
var db = require(path.join(__dirname,"../config/db"));

//引入控制器
var userCtrl = require(path.join(__dirname,"../controllers/userController"))

//req请求 res相应 next下一层
var checkUser = function(req,res,next)
{
  var user = req.cookies.user;
  if(!user || JSON.stringify(user) == "{}")
  {
    res.redirect("/user/login");
  }
  var userid = user.userid ? user.userid : 0;

  db.table("user").where({id:userid}).find().then(function(user){
    
    //如果为空就不存在
    if(JSON.stringify(user) == "{}")
    {
      //时间为0自动过期
      res.cookie('user', null, { maxAge:0});
      res.redirect("/user/login");
    }else{
      //如果有登录就直接往下执行
      next();  
    }
  });
}


//设置路由   /user/index
userRouter.get('/index', checkUser,userCtrl.index);

//user/info 个人中心
userRouter.get('/info', checkUser,userCtrl.info);

userRouter.post('/info', checkUser,userCtrl.infoData);

//发送邮箱验证的邮件
userRouter.get('/email',checkUser,userCtrl.email);

userRouter.get('/userCheck', userCtrl.userCheck);



//用户分组
userRouter.get('/groupAdd', checkUser,userCtrl.groupAdd);

userRouter.post('/groupAdd', checkUser,userCtrl.groupAddData);

//显示分组
userRouter.get('/groupList',checkUser,userCtrl.groupList);

userRouter.get('/groupChat', checkUser, userCtrl.groupChat);



//用户分组结束


//登录
userRouter.get('/login',userCtrl.login);

//登录提交
userRouter.post('/login',userCtrl.loginData);

//找回密码
userRouter.get('/password',userCtrl.password);

//注册
userRouter.get('/register', userCtrl.register);

userRouter.get('/logout', userCtrl.logout);

//注册的post方法
userRouter.post("/register", userCtrl.registerData);

userRouter.post("/friendAdd", userCtrl.friendAddData);

userRouter.post("/friendSearch", userCtrl.friendSearchData);


module.exports = userRouter;