function now_change(t){
    if(t<10){
     return "0"+t;
    }else{
     return t;
    }
  }

function now(){
    let d=new Date();
    let year=d.getFullYear();
    let month=now_change(d.getMonth()+1);
    let day=now_change(d.getDate());
    let hour=now_change(d.getHours());
    let minute=now_change(d.getMinutes());
    let second=now_change(d.getSeconds());
    
    return year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
}

function md5(str){
    let crypto = require('crypto');
    return crypto.createHash('md5').update(str).digest('hex');
}
function sleep(time) {
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        resolve();
      }, time);
    })
};



 
module.exports.now=now;
module.exports.md5=md5;