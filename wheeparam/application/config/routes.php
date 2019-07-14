<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'main';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

// FAQ 라우팅 설정
$route['customer/faq/(:num)'] = 'customer/faq/view/$1';
$route['customer/faq/(:any)'] = 'customer/faq/index/$1';
$route['customer/faq/(:any)/(:num)'] = 'customer/faq/view/$2/$1';

// Q&A 라우팅 설정
$route['customer/qna/write'] = 'customer/qna/write';
$route['customer/qna/(:num)'] = 'customer/qna/view/$1';
$route['customer/qna/(:any)'] = 'customer/qna/index/$1';
$route['customer/qna/(:any)/(:num)'] = 'customer/qna/view/$2/$1';

// 게시판 라우팅 설정
$route['board/comment/modify/(:num)'] = "board/comment_modify/$1";
$route['board/comment/reply/(:num)/(:num)'] = "board/comment_reply/$1/$2";
$route['board/(:any)'] = "board/lists/$1";
$route['board/(:any)/write'] = "board/write/$1";
$route['board/(:any)/write/(:num)'] = "board/write/$1/$2";
$route['board/(:any)/(:num)'] = "board/view/$1/$2";
$route['board/(:any)/reply/(:num)'] = "board/reply/$1/$2";
$route['board/(:any)/password/(:num)'] = "board/password/$1/$2";
$route['board/(:any)/delete/(:num)'] = "board/delete/$1/$2";
$route['board/(:any)/download/(:num)/(:num)'] = "board/download/$1/$2/$3";
$route['board/(:any)/comment/(:num)'] = "board/comment/$1/$2";
$route['board/(:any)/comment/(:num)/(:num)'] = "board/comment/$1/$2/$3";
$route['board/(:any)/comment/(:num)/(:num)/(:num)'] = "board/comment/$1/$2/$3/$4";
$route['board/(:any)/comment/(:num)/(:num)/delete'] = "board/comment_delete/$1/$2/$3";
$route['board/(:any)/comment/(:num)/(:num)/blind'] = "board/comment_blind/$1/$2/$3";

$route['rss/(:any)'] = "rss/index/$1";
$route['sitemap\.xml'] = "sitemap";
$route['sitemap_1.xml'] = "sitemap/pages";
$route['sitemap_([a-zA-Z0-9_-]+)\.xml'] = "sitemap/board/$1";

$route['content/(:any)'] = "content/index/$1";