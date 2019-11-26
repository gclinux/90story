const mysql = require('./common/mysql');
const bookUrl = require('./common/bookUrl');
const jq = require('cheerio');
const nt = require('./common/network');
const request = require('request');
const fs = require('fs');
const src_path = './story_src/';
const img_path = './static/books/';
const img_show_path = '/images/books/';
const date=require('locutus/php/datetime/date');
const time=require('locutus/php/datetime/time');
const mkdirp = require('mkdirp-promise');
let srcs = {};
let app_url = require('./config').main.app_url || 'https://www.90text.com/';
let last_init_time=0;
let path=require('path');  /*nodejs自带的模块*/


async function sourceGet() {
    let timestamp = new Date().getTime();
    let source = await mysql.getSource();
    //console.log(source);
    if(!source.success){
        console.log('获取来源失败')
        return false;
    }
    for(let k in source.data){
        let src_info =  source.data[k];
        try{
            srcs[src_info.file_name]={"id":src_info.id,"file":src_info.file_name,"obj":require(src_path+src_info.file_name)};
        }catch(e){
            console.log('---------------------------');
            console.log('include file error:');
            console.log(e);
            console.log('---------------------------');
        }
       // console.log(srcs);
        
    }
    return true;
}
async function init(){
    let year = date('Y');
    await mkdirp(img_path+year);
    let now = time();
    if(now - last_init_time > 300){
        last_init_time = now;
        let st = await sourceGet();
    }
}
async function top(){
    let promise_list = [];
    let store=[];
    for(let i in srcs){
        let src = srcs[i];
        let topUrl = src.obj.top.url;////
        let topIsPhone = src.obj.top.isPhone||false;////
        let topNeedProxy = src.obj.top.proxy||false;////
        let topReferer = src.obj.top.referer||false;////
        let html = await nt.get(topUrl,topNeedProxy,60000,topReferer,false,true,topIsPhone);
        if(!html){
            continue;
        }
        let booksUrls = src.obj.top.exec(html);
        
        //console.log(booksUrls.length);
        for(let j in booksUrls){
            let bookExist = await mysql.isBookUrlExist(booksUrls[j].url)
           // console.log(booksUrls[j].url+'->'+bookExist);
            if(!bookExist){
                store.push({file:src.file,"url":booksUrls[j].url,"catUrl":booksUrls[j].catUrl});
            }
        }
    }
    
    console.log('storebook num:'+store.length);
    for(let k in store){
        let info = store[k];
        //console.log(info);
        promise_list.push(getBookInfo(info.file,info.url,info.catUrl));
        if(promise_list.length == 20){
            await Promise.all(promise_list);
            promise_list = [];
            continue;
        }
    }
    await Promise.all(promise_list);
    store = undefined;
    console.log('finish');
    return true;
}

async function search(){

}

async function getBookInfo(src_file_name,url,catUrl,book_info){
    let src = srcs[src_file_name];
    if(!src){return false;}
    let proxy = src.obj.bookInfo.proxy||false;////
    let isPhone = src.obj.bookInfo.isPhone||false;////
    let ref = src.obj.bookInfo.referer||false;////
    let html = await nt.get(url,proxy,60000,ref,null,true,isPhone);
    if(!html){return false}
    let book = src.obj.bookInfo.exec(html);
    let book_id;
    if(!book_info){//有book_info传入的则为更新
        book.url=url;
        book.cat_url=catUrl||'';
        book.char_num = book.char_num||0;
        book.created_at = book.updated_at = date('Y-m-d H:i:s');
        let book_rs=await mysql.storeBook(book);
        if(!book_rs.success){
            console.log('[book]->存入失败:')
            console.log(book_rs.data);
            return false
        }else{
            book_id = book_rs.data;
            console.log('[book]->存入新书:'+book.name+'-'+book.author+' ID:'+book_id);
        }
    }else{
        book_id = book_info.id;
        if((book_info.status != book.status) || (book.char_num != book_info.char_num)){
            mysql.updateBook({"status":book.status,char_num:book.char_num,"id":book_id});
            //更新就不等了,管他成不成功
            console.log('[book]->更新旧书:'+book.name+'-'+book.author+' ID:'+book_id);
        }
    }
 
    let cats_num;
    if(url == catUrl||catUrl == ''||catUrl==null){
        let cats = src.obj.cat.exec(html);
        cats_num = await mysql.storeCatalogs(cats,book_id);
    }else{
        cats_num = await updateCat(src_file_name,catUrl,book_id);
    }
    if((!book_info) && cats_num.data>0){
        mysql.updateBook({"spider_status":1,"id":book_id});
    }
    console.log('[cats]->'+book.name+'-存入新章节共:'+cats_num.data+'个');
    return cats_num.data;
}

async function updateCat(src_name,url,book_id){
    let src = srcs[src_name];
    if(!src){return false;}
    let proxy = src.obj.cat.proxy||false;
    let isPhone = src.obj.cat.isPhone||false;
    let ref = src.obj.cat.referer||false;
    let html = await nt.get(url,proxy,60000,ref,null,true,isPhone);
    if(!html){
        mysql.bookRetryAdd(book_id);
        return {"data":0,success:false}
    }
    let cats = src.obj.cat.exec(html);
    let num = await mysql.storeCatalogs(cats,book_id);
    return num;
}

async function updateContent(src_name,url,catId){
    let src = srcs[src_name];
    if(!src){return false;}
    let proxy = src.obj.content.proxy||false;
    let isPhone = src.obj.content.isPhone||false;
    let ref = src.obj.content.referer||false;
    let html = await nt.get(url,proxy,60000,ref,null,true,isPhone);
    if(!html){
        console.log('[cont]->'+url+'->access false');
        mysql.catRetryAdd(catId);
        return false;
    }
    let content = src.obj.content.exec(html);
    content = content.replace('小说','<a href="'+app_url+'">小说</a>')
        .replace('作者','<a href="'+app_url+'">作者</a>')
        .replace(/中国/g,'中原国')
        .replace(/裸体/g,'果体')
        .replace(/月票/g,'<a alt="求月票" href="'+app_url+'">免费小说</a>月票')
    console.log('[cont]->爬取ID为'+catId+'的内容');
    await mysql.storeContents(content,catId);
    return true;
}

function downImage(img_url,book_id){
    return new Promise((ac,rj)=>{
        img_url = encodeURI(img_url);
        let extname=path.extname("img_url")||'.jpg';
        let error_happen = false;
        let year = date('Y');
        let tmp = img_path + year + '/' + book_id + extname;
        console.log(tmp);
        let ws = fs.createWriteStream(tmp);
        request.get(img_url)
        .on('error', function(err) {
            error_happen = true;
            console.error('请求时候发生错误'+err.message);
           // ws.end();
            ac(false);
        })
        .pipe(ws);
        ws.on('error', (err) => {
            
            error_happen=true;
            console.log(img_url);
            console.log(tmp);
            console.log('发生异常:', err);
        });
        
        ws.on('finish', () => {
            
           if(error_happen == false){
                console.log('finish:'+tmp);
                mysql.updateBook({"id":book_id,"img":img_show_path+year+'/'+book_id+extname,"img_down":1})
                ac(true);
           }else{
                console.log('unlink:'+tmp);
                fs.unlink(tmp,(e)=>{});
                ac(false);
           }
        });

        ws.on('close', () => {
            if(error_happen){
                fs.unlink(tmp,(e)=>{});
                ac(false)
            } 
            ;
        });

    });
}

module.exports.getBookInfo = getBookInfo;
module.exports.getCats = updateCat;
module.exports.getContent = updateContent;
module.exports.init = init;
module.exports.top = top;
module.exports.downImage = downImage;
