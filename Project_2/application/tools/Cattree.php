<?php
namespace app\tools;

	class Cattree{
		//成员属性
		private $idName;//对应数据库字段名称
		private $pidName;
		private $catName;
		private $ordName;
		private $child;
		private $level;
		private $pid;
		private $list;
		private $items;
		private $path;
		//成员方法
		//构造方法
		function  __construct($items,$level=0,$path='0,',$pid=0,$child='child',$idName='id',$pidName='pid',$catName='name',$ordName='ord'){
			//初始化赋值
			$this->items = $items;
			$this->idName = $idName;
			$this->pidName = $pidName;
			$this->catName = $catName;
			$this->ordName = $ordName;
			$this->path = $path;
			$this->level = $level;
			$this->pid = $pid;
			$this->child = $child;
			//最后返回的数组
			$this->list = [];


		}
		//获得新数据
		public function getTree(){
			//调用遍历数据方法
			$this->data($this->items,$this->pid,$this->level,$this->path);
			//var_dump($this->list);
			//调用获取子类方法
			return $this->childs($this->list);
			//return $this->list;
		}
		//遍历数据
		private function data($data,$pid,$level,$path){
			//判断
			if(array_key_exists($this->ordName,$data[0])){
				//对数组进行排序
				usort($data,array(__CLASS__,"paixu"));
			}
			//var_dump($data);
			//遍历层级
			foreach($data as $k=>$v){
				if($v[$this->pidName] == $pid){
					$v['level']  = $level;
						//制作当前path路径
						if($v[$this->pidName] != 0){
							$v['path'] = $path.$v[$this->pidName].',';
						}else{
							$v['path'] = $path;
						}

					$this->list[$v[$this->idName]] = $v;
					// var_dump($this->list);
					
					//递归
					$this->data($data,$v[$this->idName],$level+1,$v['path']);

				}
			}
		}
		//获取子类
		private function childs(array $data){
			foreach($data as $k=>&$v){
				$v[$this->child] = '';
				foreach($data as $key=>$val){
					if(in_array($v[$this->idName],explode(',',$val['path']))){
						$v[$this->child] .= $val[$this->idName].',';
					}
				}
				$v[$this->child] = rtrim($v[$this->child],',');
			}
			return $data;
		}
		//排序回调方法
		private function paixu($x,$y){
			if($x[$this->ordName] == $y[$this->ordName]){
				return 0;
			}elseif($x[$this->ordName] < $y[$this->ordName]){
				return -1;
			}else{
				return 1;
			}
		}
	}