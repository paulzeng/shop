<?php


namespace app\http\middleware;


use app\Request;
use think\Response;

/**
 * 
 */
class Check
{
	public function handle(Request $request,\Closure $next){
		$request->Info = 'HELLO WORLD';
		return $next($request);
	}
}