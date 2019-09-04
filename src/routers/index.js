var path = require('path');
var userRouter = require('./user');
var commonRouter = require('./common');

function main(app)
{
  //user/xxxxx
  app.use('/',commonRouter);
  //用户路由
  app.use('/user',userRouter);

}

module.exports = main;