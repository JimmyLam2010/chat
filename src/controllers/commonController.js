const xtpl = require('xtpl');
const path = require('path');
//加载验证码
const captchapng = require('captchapng');

const base = require("./baseController.js");

//验证码方法
module.exports.imgcode = function(req,res)
{
  //随机字符串
  var vcode = parseInt(Math.random() * 9000 + 1000);

  //实例化一个验证码对象
  var p = new captchapng(80, 30, vcode);

  p.color(0, 0, 0, 0);
  p.color(80, 80, 80, 255);

  //将数据保存到session当中
  req.session.imgcode = vcode;

  //获取一个base64的图片类型
  var img = p.getBase64();

  //将图片对象处理成一个二进制的buffer对象
  var imgbase64 = new Buffer(img, 'base64');

  //返回给界面显示图片
  res.writeHead(200, {
    'Content-Type': 'image/png'
  });

  res.end(imgbase64);
}