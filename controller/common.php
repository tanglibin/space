<?php
use think\captcha\Captcha;

/**
 * 检查请求是否来自ajax
 * @param string $moduleName 模块名称
 * @return boolean
 */
function is_from_ajax() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
