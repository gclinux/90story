const path = require('path')
const env = process.env.env||"dev";
const dir = path.resolve(__dirname,env)
module.exports.main = require(path.resolve(dir,'main'))
module.exports.redis = require(path.resolve(dir,'redis'))
module.exports.mysql = require(path.resolve(dir,'mysql'))