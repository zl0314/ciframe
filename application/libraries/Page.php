<?php
/**
 * 分页
 * @author Aaron Zhang
 * @QQ 815818648
 */
class Page
{
	private $total; //数据表中总记录数
	private $listRows; //每页显示行数
	private $limit;
	private $uri;
	private $pageNum; //页数
	private $config = array('header' => "个记录", "prev" => "上一页", "next" => "下一页", "first" => "首 页", "last" => "尾 页");
	private $listNum = 12;


	public function __construct() { }

	public function init($total = 0, $listRows = 10, $pa = "") {
		$this->total = $total;
		$this->listRows = $listRows;
		$this->uri = $this->getUri($pa);
		//如果page不为空，则page=$_GET[page]，否则page=1
		$this->page = !empty($_GET["page"]) ? $_GET["page"] : 1;
		// 对于每页显示的行数进行整除  取整数
		$this->pageNum = ceil($this->total / $this->listRows);
		//设置一个limit函数   设置以每页显示的行数为准，每页取都是10行，如果第一页取的是0-10的，那么第二页就是从11-20  第三页就是从21-30  以此类推
		$this->limit = $this->setLimit();
	}

	private function setLimit() {
		return " Limit " . ($this->page - 1) * $this->listRows . ", {$this->listRows}";
	}

	//定义一个获得当前url的函数  并取出最后一个？出现的地方，如果当前url没有？ 那么当前url就行于当前的url，否则就加上一个？  如果已有，有？的地方，还是只有一个？
	private function getUri($pa) {
		$url = $_SERVER["REQUEST_URI"] . (strpos($_SERVER["REQUEST_URI"], '?') ? '' : "?") . $pa;

		$parse = parse_url($url);

		if (isset($parse["query"])) {
			parse_str($parse['query'], $params);
			unset($params["page"]);
			//对当前的url进行组合
			$url = $parse['path'] . '?' . http_build_query($params);
		}
		return $url;
	}

    //获取LIMIT语句
	public function __get($args = '') {
		if ($args == "limit"){
            return $this->limit;
        }else{
            return null;
        }
	}

    //首页
	private function start() {
		if ($this->total == 0){
            return 0;
        } else{
            return ($this->page - 1) * $this->listRows + 1;
        }
	}
    //最后一页
	private function end() {
		return min($this->page * $this->listRows, $this->total);
	}
    //第一页
	private function first() {
		$html = '';
		if ($this->page == 1) {
			$html .= '';
		} else {
			$html .= "<a href='{$this->uri}&page=1'>{$this->config["first"]}</a>";
			return $html;
		}
	}
    //上一页
	private function prev() {
		$html = '';
		if ($this->page == 1) {
			$html .= '';
		} else {
			$html .= "<a href='{$this->uri}&page=" . ($this->page - 1) . "'>{$this->config["prev"]}</a>";
		}
		return $html;
	}
    //分页列表
	private function pageList() {
		$linkPage = "";

		$inum = floor($this->listNum / 2);

		for ($i = $inum; $i >= 1; $i--) {
			$page = $this->page - $i;

			if ($page < 1) {
				continue;
			}

			$linkPage .= "<a href='{$this->uri}&page={$page}'>{$page}</a>";

		}
		$linkPage .= "{$this->page}";


		for ($i = 1; $i <= $inum; $i++) {
			$page = $this->page + $i;
			if ($page <= $this->pageNum) {
				$linkPage .= "<a href='{$this->uri}&page={$page}'>{$page}</a>";
			} else {
				break;
			}
		}

		return $linkPage;
	}

	//下一页
	private function next() {
		$html = '';
		if ($this->page >= $this->pageNum) {
			$html .= '';
		} else {
			$html .= "<a href='{$this->uri}&page=" . ($this->page + 1) . "'>{$this->config["next"]}</a>";
		}
		return $html;
	}

    //最后一页
	private function last() {
		$html = '';
		if ($this->page == $this->pageNum) {
			$html .= '';
		} else {
			$html .= "<a href='{$this->uri}&page=" . ($this->pageNum) . "'>{$this->config["last"]}</a>";
		}
		return $html;
	}
    //跳转到指定面面
	private function goPage() {
		return '<input type="text" onkeydown="javascript:if(event.keyCode==13){var page=(this.value' . $this->pageNum . ')?' . $this->pageNum . ':this.value;location=\'' . $this->uri . '&page=\'+page+\'\'}" value="' . $this->page . '" style="width:25px"><input type="button" value="GO" onclick="javascript:var page=(this.previousSibling.value' . $this->pageNum . ')?' . $this->pageNum . ':this.previousSibling.value;location=\'' . $this->uri . '&page=\'+page+\'\'">';
	}

	//执行分页操作
	function fpage($display = array(0, 1, 2, 3, 4, 5, 6, 7, 8)) {
		$html[0] = "共有<b>{$this->total}</b>{$this->config["header"]}";
		$html[1] = "每页显示<b>" . ($this->end() - $this->start() + 1) . "</b>条，本页<b>{$this->start()}-{$this->end()}</b>条";
		$html[2] = "<b>{$this->page}/{$this->pageNum}</b>页";

		$html[3] = $this->first();
		$html[4] = $this->prev();
		$html[5] = $this->pageList();
		$html[6] = $this->next();
		$html[7] = $this->last();
		$html[8] = $this->goPage();
		$fpage = '';
		foreach ($display as $index) {
			$fpage .= !empty($html[$index]) ? $html[$index] : '';
		}
		return $fpage;
	}
}