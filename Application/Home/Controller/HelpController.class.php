<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 文档模型控制器
 * 文档模型列表和详情
 */
class HelpController extends HomeController {

    /* 文档模型频道页 */
	public function index(){
		
		$this->redirect('/help/login-registe/login');
	}

	/* 文档模型详情页 */
	public function detail(){
		$category = I('get.category');
		$name = I('get.name');
		$p = I('get.p');
		/* 标识正确性检测 */
		if(empty($category) || empty($name)){
			$this->redirect('/help');
		}

		/* 页码检测 */
		$p = intval($p);
		$p = empty($p) ? 1 : $p;

		/* 获取详细信息 */
		$Document = D('Document');
		$info = $Document->detail_name($name);
		$info = get_lang_data($info, $_COOKIE['lives_lvt_language']);
		
		if(!$info){
			$this->error($Document->getError());
		}

		/* 更新浏览数 */
		$map = array('id' => $id);
		$Document->where($map)->setInc('view');

		/* 模板赋值并渲染模板 */
		$this->assign('info', $info);
		$this->assign('page', $p); //页码
		$this->display();
	}


	//根据pid获取二级导航
	private function categoryPid($pid = '') {
		if(empty($pid)){
			$this->error('没有指定文档分类！');
		}
		/* 获取分类信息 */
		$categoryArr = D('Category')->infoPid($pid);
		return $categoryArr;
	}

	/* 文档分类检测 */
	private function category($id = 1){
		/* 标识正确性检测 */
		$id = $id ? $id : I('get.category', 0);
		if(empty($id)){
			$this->error('没有指定文档分类！');
		}

		/* 获取分类信息 */
		$category = D('Category')->info($id);
		if($category && 1 == $category['status']){
			switch ($category['display']) {
				case 0:
					$this->error('该分类禁止显示！');
					break;
				//TODO: 更多分类显示状态判断
				default:
					return $category;
			}
		} else {
			$this->error('分类不存在或被禁用！');
		}
	}

}
