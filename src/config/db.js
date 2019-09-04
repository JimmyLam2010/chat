var Mysql = require('node-mysql-promise');

//创建一个数据库连接
var db = Mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: 'root',
  database:"chat",
  tablePrefix:"pre_",
  logSql:true
})

module.exports = db;