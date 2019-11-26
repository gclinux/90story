<?php
namespace App\Service;
use App\Model\Proxy;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Promise;
class IpSpiderService{
	protected $cli;
	protected $check_cli;
	protected $db;
	public function __construct(){
		$this->db = new Proxy();
		$this->cli = new Client(['verify' => false,'headers' => ['User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36']]);
		$this->check_cli = new Client(['verify'=>false,'headers' => ['User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36'],
			'base_uri' => 'https://www.baidu.com','allow_redirects'=>false,'timeout' => 10]);
		$this->check_promises = [];
	}
    public function spiderIpHttp($maxPage=3){
    	ignore_user_abort();
		set_time_limit(0);
    	$this->spiderIp($maxPage);
    }
    public function spiderIp($maxPage=3){
		$this->db->clean();
	//	$this->xichi($maxPage);
		//$this->kuai($maxPage);
		$this->jianxili($maxPage);
    }

 
    public function xichi($maxPage=3){
    	//https://www.xicidaili.com/nn/2 西刺代理
		
    	$requests = function ($total) {
			$uri = 'https://www.xicidaili.com/nn/';
			for ($i = 0; $i < $total; $i++) {
				yield new Request('GET', $uri.($i+1));
			}
		};
		
		$pool = new Pool($this->cli, $requests($maxPage), [
			'concurrency' => 5,
			'fulfilled' => function ($response, $index) {
				$body = $response->getBody();
				if(!empty($body)){
					$html_dom = new \HtmlParser\ParserDom($body);
					$tr_array = $html_dom->find('#ip_list tr');
					foreach($tr_array as $k=>$tr){
						if($k!=0){
							$td = $tr->find('td');
							
							$data=[
								'from' =>'西刺代理',
								'ip'=>$td[1]->getPlainText().':'.$td[2]->getPlainText(),
								'anony'=>$td[4]->getPlainText() == '高匿'?1:0,
								'type'=>strtolower($td[5]->getPlainText()),
								'ping'=>intval(floatval($td[6]->find('div',0)->getAttr('title'))*1000),
							];
					
							if($addr = $td[3]->find('a',0)){
								$data['addr'] = $addr->getPlainText();
							}else{
								$data['addr'] = 'CN';
							}
							if($data['ping']>3000){
								//抛弃大于3秒的
								continue;
							}
							//$this->check_cli->request('GET', '/', ['proxy' => $data['type'].'://'.$data['ip']]);
							$this->checkProxy($data);
						}

						//echo $tr->innerHtml();
					}
				}

			},
			'rejected' => function ($reason, $index) {
				dump($reason);
				//echo $reason->message.'<br>'."\n";
				// this is delivered each failed request
			},
		]);
		
		$promise = $pool->promise();
		
		// Force the pool of requests to complete.
		$promise->wait();

	}
	
	public function kuai($maxPage=3){
    	//https://www.kuaidaili.com/free/inha/1/ 快代理
		
    	$requests = function ($total) {
			$uri = 'https://www.kuaidaili.com/free/inha/';
			for ($i = 0; $i < $total; $i++) {
				yield new Request('GET', $uri.($i+1).'/');
			}
		};
		
		$pool = new Pool($this->cli, $requests($maxPage), [
			'concurrency' => 5,
			'fulfilled' => function ($response, $index) {
				$body = $response->getBody();
				if(!empty($body)){
					$html_dom = new \HtmlParser\ParserDom($body);
					$tr_array = $html_dom->find('.table-striped tbody');
					foreach($tr_array as $k=>$tr){
						if($k!=0){
							$td = $tr->find('td');
							
							$data=[
								'from' =>'快代理',
								'ip'=>$td[0]->getPlainText().':'.$td[1]->getPlainText(),
								'anony'=>$td[2]->getPlainText() == '高匿名'?1:0,
								'type'=>strtolower($td[3]->getPlainText()),
								'ping'=>intval(floatval($td[5]->getPlainText())*1000),
								'addr'=>$td[4]->getPlainText()
							];
					
							
							if($data['ping']>3000){
								//抛弃大于3秒的
								continue;
							}
							//$this->check_cli->request('GET', '/', ['proxy' => $data['type'].'://'.$data['ip']]);
							$this->checkProxy($data);
						}

						//echo $tr->innerHtml();
					}
				}

			},
			'rejected' => function ($reason, $index) {
				dump($reason);
				//echo $reason->message.'<br>'."\n";
				// this is delivered each failed request
			},
		]);
		
		$promise = $pool->promise();
		
		// Force the pool of requests to complete.
		$promise->wait();

	}
	

	public function jianxili($maxPage=3){
    	//	http://ip.jiangxianli.com/?page=1 
		
    	$requests = function ($total) {
			$uri = 'http://ip.jiangxianli.com/?page=';
			for ($i = 0; $i < $total; $i++) {
				yield new Request('GET', $uri.($i+1).'');
			}
		};
		
		$pool = new Pool($this->cli, $requests($maxPage), [
			'concurrency' => 5,
			'fulfilled' => function ($response, $index) {
				$body = $response->getBody();
				//dump((string)$body);
				if(!empty($body)){
					$html_dom = new \HtmlParser\ParserDom($body);
					$tr_array = $html_dom->find('.table-striped',0)->find('tbody tr');
					foreach($tr_array as $k=>$tr){
						//continue;
						$td = $tr->find('td');
						if(empty($td)){
							continue;
						}
						$data=[
							'from' =>'.jiangxianli',
							'ip'=>$td[1]->getPlainText().':'.$td[2]->getPlainText(),
							'anony'=>$td[3]->getPlainText() == '高匿'?1:0,
							'type'=>strtolower($td[4]->getPlainText()),
							'ping'=>intval(floatval($td[5]->getPlainText())*1000),
							'addr'=>$td[7]->getPlainText()
						];
				
						
						if($data['ping']>3000){
							//抛弃大于3秒的
							continue;
						}
						//$this->check_cli->request('GET', '/', ['proxy' => $data['type'].'://'.$data['ip']]);
						$db->RedisInsert($data);
						
						//echo $tr->innerHtml();
					}
				}

			},
			'rejected' => function ($reason, $index) {
				dump($reason);
				//echo $reason->message.'<br>'."\n";
				// this is delivered each failed request
			},
		]);
		
		$promise = $pool->promise();
		
		// Force the pool of requests to complete.
		$promise->wait();

	}
}