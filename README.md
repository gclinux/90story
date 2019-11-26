# 90story 
一个基于laravel的小说网站

#### 协议

本程序在遵循下列条件下,可以任意使用在任意场合:

第一:凡使用本站程序或本站程序的衍生品所搭建的网站,必须保留指向https://www.90text.com的友情链接

第二:本程序开源目的只提供学习和研究,任何因为使用本程序商用化而可能导致的版权问题需要使用者自行解决和承担

第三:使用本程序衍生其他项目不得故意增加恶意程序.

第四:不得使用"90文字网"或者"90小说网",或者"90电子书"为网站名字



#### 程序截图

![login](https://github.com/gclinux/90story/raw/master/screenshots/admin1.jpg) 



#### 介绍

使用laravel5.5搭建的小说站,爬虫使用nodejs编写.

演示网站:https://www.90text.com


#### 功能:
1. 自动根据目标网站的top进行爬取,自动排重书本,大部分情况无需人工干涉
2. 自动向百度搜索引擎进行提交最新链接(需要添加计划任务和配置提交地址,详细查看下面的"百度自动提交")
3. 书本管理 章节管理 带搜索功能
4. 搜索引擎访问记录 目前能识别百度 必应 360 谷歌 soso 微软必应 
5. 自定义菜单及权限
6. 书籍推荐(人工推荐)
7. 小说描述伪原创化(需要启动计划任务)

#### 计划:
1. PC页面进行适配(目前只做了移动端的模版,主要是自己没时间,如果start到2000我会把PC端模版做出来,如果没人用做了也白费心思)
2. 更多的适配网站爬取文件(目前开源只放出一个,你们跟着改就行,会jquery的都能写出来,我自己有4个主要怕人用多了会被封)
3. 搜索引擎优化
5. 站群化(根据域名呈现不同表现)

#### 软件环境
环境需求:

PHP7.0或以上,安装composer  
MySQL5.6或以上

redis 任意版本

以上环境推荐使用  lnmp 一键搭建

nodejs8 或以上

[lnmp]: https://lnmp.org/install.html


#### 安装教程

1. 通过git或者下载源码,并放置好

2. $cd admin

4. $cp .env.example .env

5. 修改.env里的内容,正确指向mysql和redis

6-1. $sh install.sh

6-2. 导入menu.sql文件到mysql

7. 修改nginx 让nginx指向admin/public目录

8. 访问网站

9. 建议:$ln -s app/Front/Views/phone app/Front/Views/pc //因为目前还没提供PC的模板,建议暂时用phone的顶替



10. $cd ../spider

11. $npm install

12. $cp config/dev config/product

13. $sudo npm -g install forever

14. npm start //启动爬虫

    

#### 使用说明

1. 查看爬虫日志:

   `$forever list`

2. 后台地址,在网址基础上增加 /admin

3. 更新分类:

   `$cd admin`

   `$php artisan book:class`



#### 百度自动提交
请先查看 https://ziyuan.baidu.com/linksubmit/index 获得提交API

cd admin

vim .env

增加 BAIDU_API=http://data.zz.baidu.com/urls?site=xxxxx&token=xxxxxx

在计划任务中增加:

`* * * * * php /path/to/project/admin/artisan schedule:run >> /dev/null 2>&1`



#### 默认帐号信息
默认后台: http://你网站地址/admin
默认帐号密码:admin,admin (登录后请自行修改)



#### 开发:

目录结构:(请查看源文件 github web下排版有问题)

\admin #存放php 前台及后台
|--app
    |--Admin #后台实现
    |--Console #计划任务及命令行实现
    |--Front     #前端实现
          |--View  #前端模版
                 |--phone #移动端模版
                 |--pc   #PC端模版

|--config #配置文件都在这里
|--public #nginx指向这个目录
|-- .env  #环境的配置(建议修改这个文件而不修改config)

\spider #存放nodejs爬虫
   |--story_src   #存放爬虫目标文件



#### 捐赠

如果您觉得这个程序对您有帮助,请给予我鼓励,捐赠点钱我给娃买奶粉.您的慷慨会让我跟积极更新

微信捐赠:

 ![login](https://github.com/gclinux/90story/raw/master/screenshots/wechat.png) 







