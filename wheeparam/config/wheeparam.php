<?php
/**
 * 휘파람 보드 환경 설정 파일
 * ---------------------------------------------------------------------------------------------
 */
// 휘파람보드 버젼
const WB_VER = '2.0.4';

// 기본 타임존 세팅
date_default_timezone_set('Asia/Seoul');

// 프로젝트 이름
const PROJECT = 'wheeparamboard';

if( IS_TEST )
{
    // 개발용 설정
    define("DB_HOST", "115.68.120.149");     // DB 호스트
    define("DB_USER", "wboard");                // DB 아이디
    define("DB_PASS", "!@wboard12");            // DB 비밀번호
    define("DB_NAME", "wboard");                // DB 네임
}
else
{
    // 실서버용 설정
    define("DB_HOST", "127.0.0.1");     // DB 호스트
    define("DB_USER", "");                // DB 아이디
    define("DB_PASS", "");            // DB 비밀번호
    define("DB_NAME", "");                // DB 네임
}

// 테마설정
const THEME_DESKTOP = "desktop";   // PC일경우 사용할 테마
const THEME_MOBILE = "desktop";   // 모바일일경우 사용할 테마

// 기능 사용여부 설정
const USE_EMAIL_ID = TRUE;   // 아이디를 이메일 형식으로 사용합니다.
const USE_EMAIL_VERFY = TRUE; // 이메일 인증 기능을 사용합니다
const USE_SHOP = TRUE;   // 쇼핑물 기능 사용여부

// 이메일 발송 설정
const SEND_EMAIL = 'noreply@wheeparam.com';  // 문의메일을 발송하는 이메일주소, 가급적 수정금지
const SEND_EMAIL_SMTP_USE = FALSE;
const SEND_EMAIL_SMTP_HOST = "";
const SEND_EMAIL_SMTP_USER = "";
const SEND_EMAIL_SMTP_PASS = "";
const SEND_EMAIL_SMTP_PORT = 465;
const SEND_EMAIL_SMTP_CRYP = "ssl";

// 파일 업로드 허용 확장자
const FILE_UPLOAD_ALLOW = "csv|psd|pdf|ai|eps|ps|smi|xls|ppt|pptx|gz|gzip|tar|tgz|zip|rar|bmp|gif|jpg|jpe|jpeg|png|tiff|tif|txt|text|rtl|xml|xsl|docx|doc|dot|dotx|xlsx|word|srt";