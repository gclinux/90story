const redis = require('./redis');
const redis_key = 'joffe_book_url_list';

function pop(){
	return new Promise(async (ac,rj)=>{
		let data;
		try{
			data = await redis.rpop(redis_key);
		}catch(e){
			return rj(e);
		}
		try{
			ac(JSON.parse(data));
		}catch(e){
			rj(e);
		}
	})
}
/**用于存储那些不能获取作者的书的地址,如果书作者有了,可以不用,直接存储到mysql就好
 * value格式{"url":章节更新地址,"info_url":资料更新地址,如果没则表示跟章节更新地址一致,file:处理文件}
*/
function store(value){
	value = JSON.stringify(value);
	return redis.lpush(redis_key,value);
}

function storeMany(array){
	let value=[redis_key];
	for(let i in array){
		value.push(JSON.stringify(array[i]))
	}
	redis.lpush(...value);
}
module.exports.pop = pop;
module.exports.store = store;
module.exports.storeMany=storeMany;
