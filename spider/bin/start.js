const CronJob = require('cron').CronJob;
const mysql = require('../common/mysql');
const main = require('../main');
const http=require('http');
const m_url = require('url');
let route = {};

function start_cron(){
    new CronJob('12 1 2 * * 1', async function() {
        //每周一更新top
        await cronTop();
        await cronContent()
    }, null, true, 'Asia/Shanghai');
    
    new CronJob('0 30 */2 * * *', async function() {
        //每两个小时更新一次小说章节
        await cronBook();
        await cronContent();
    }, null, true, 'Asia/Shanghai');

    new CronJob('0 10 */4 * * *', async function() {
        //每4个小时下载一次图片
        await cronImg();
    }, null, true, 'Asia/Shanghai');

}

 
async function cronTop(){
    await main.init();
    await main.top();
    
};

async function cronImg(){
    let limit = 50;
    let rs = {};
    let page = 1;
    let loop = false;
    do{
       let rs = await mysql.getBookImages(0,limit,page);
       if(!rs.success){
           break;
       }
       page ++;
       books=rs.data;
       let pms=[];
       for(let i in books){
           let book = books[i];
           pms.push(main.downImage(book.img,book.id));
       }
       await Promise.all(pms);
       console.log(rs.data.length);
       loop = rs.data.length == limit;
    }while(loop);
}

async function cronBook(){
    await main.init();
    let limit = 50;
    let rs = {};
    let page = 1;
    let loop = false;
    do{
       let rs = await mysql.getBooksByStatus(0,limit,'update',page);
       if(!rs.success){
           break;
       }
       page ++;
       books=rs.data;
       let pms=[];
       for(let i in books){
           let book = books[i];
           pms.push(main.getBookInfo(book.file,book.url,book.cat_url,book));
       }
       await Promise.all(pms);
       console.log(rs.data.length);
       loop = rs.data.length == limit;
    }while(loop);
    
}

async function cronContent(){
    await main.init();
    let limit = 100;
    let rs = {};
    let page=1;
    let loop=false;
    do{
        let rs = await mysql.getCatalogsByStatus(0,limit);
        if(!rs.success){
            break;
        }
        cats = rs.data;
        let pms=[];
        for(let i in cats){
            let cat = cats[i];
            pms.push(main.getContent(cat.file,cat.url,cat.id));
        }
        await Promise.all(pms);
        console.log(rs.data.length);
        loop = rs.data.length == limit;
        
    }while(loop);
}

route['/book']=async function(req,res,GET){
    await main.init();
    let book = await mysql.getBook(GET.book_id);
    if(book.success == 0){
        return book;
    }
    book = book.data[0];
    if(!book){
        return {"success":0,"data":"book not found:"+GET.book_id}
    }
    let catnum = await main.getBookInfo(book.file,book.url,book.cat_url,book);
    book.new_cat_num=catnum
    return {"success":1,data:book}
}



async function start(){
	console.log('正尝试获取top');
    await cronTop();
    console.log('top 抓取完成');
    await cronBook();
    console.log('开始抓取章节内容')
    await cronContent();
    console.log('章节内容更新完成')
    console.log('开始下载图片')
    await cronImg();
    console.log('图片下载完成')
    start_cron();
}

start();



//创建一个服务器并指定请求处理函数
http.createServer(async function(req,res){
    try{
        let reqObj = m_url.parse(req.url, true);
        let path = reqObj.pathname;
        let GET = reqObj.query;
        if(route[path]){
            let body = await route[path](req,res,GET);
            res.writeHead(200,{
                'content-Type':'application/json',
            });
            res.end(JSON.stringify(body));
        }else{
            res.writeHead(200,{
                'content-Type':'application/json',
            });
            res.end('{"status":"404","data":"404 Not Found"}');
        }
    }catch(e){
        res.end(e.message);
    }
})
.listen(3000);