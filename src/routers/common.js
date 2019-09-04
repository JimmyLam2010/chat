var express = require('express');
var path = require('path');

var commonRouter = express.Router();

//引入控制器
var commonCtrl = require(path.join(__dirname,"../controllers/commonController"))


//验证码方法
commonRouter.get('/imgcode', commonCtrl.imgcode);

module.exports = commonRouter;