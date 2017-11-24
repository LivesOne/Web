<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Widget;
use Think\Action;

/**
 * 分类widget
 * 用于动态调用分类信息
 */

class HelpWidget extends Action{
	
	/* 显示左侧导航 */
	public function lists($cate, $child = false){
		// 获取help标题
		$category = D('Category')->info('help');
		$category = get_lang_data($category, $_COOKIE['lives_lvt_language'], 'title');

		/* 获取help分类信息 */
		$category_arr = D('Category')->infoPid($category['id']);

		/* 获取当前分类内容列表 */
		$Document       = D('Document');
		foreach ($category_arr as $_key => $_val_lists) {
			$categoryArr[$_key] = get_lang_data($_val_lists, $_COOKIE['lives_lvt_language'], 'title');
			$lists = $Document->page(1, $_val_lists['list_row'])->lists($_val_lists['id']);
			foreach ($lists as $_l => $_list) {
				$categoryArr[$_key]['lists'][$_l] = get_lang_data($_list, $_COOKIE['lives_lvt_language'], 'title');
			}
		}
		
		$path_url = __ROOT__;

		//获取分类下文章信息
		/* 模板赋值并渲染模板 */
		$this->assign('path_url', $path_url);
		$this->assign('category', $category);
		$this->assign('categoryArr', $categoryArr);
		$this->display('Help/left-lists');
	}
	
}
