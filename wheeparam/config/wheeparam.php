<?php
/**
 * 휘파람 보드 환경 설정 파일
 * ---------------------------------------------------------------------------------------------
 */
// 기본 타임존 세팅
date_default_timezone_set('Asia/Seoul');

// 프로젝트 이름
define('PROJECT', 'wheeparamboard');

// 개발용 설정
if( IS_TEST )
{
    define("BASE_URL", "http://localhost:8088");    // 기본 설정 URL
    define("COOKIE_DOMAIN", "localhost:8088");  // 쿠키도메인
    define("SSL_VERFIY", FALSE);    // 보안인증서 사용 여부

    // 데이타베이스 설정
    define("DB_HOST", "115.68.120.149");     // DB 호스트
    define("DB_USER", "wboard");                // DB 아이디
    define("DB_PASS", "!@wboard12");            // DB 비밀번호
    define("DB_NAME", "wboard");                // DB 네임
}
// 실제 서버용 설정
else
{
    define("BASE_URL",  "http://www.wboard.com");
    define("COOKIE_DOMAIN", ".wboard.com");  // 쿠키도메인
    define("SSL_VERFIY", FALSE);    // 보안인증서 사용 여부

    // 데이타베이스 설정
    define("DB_HOST", "127.0.0.1");     // DB 호스트
    define("DB_USER", "wboard");                // DB 아이디
    define("DB_PASS", "!@wboard12");            // DB 비밀번호
    define("DB_NAME", "wboard");                // DB 네임
}

// 테마설정
define("THEME_DESKTOP", "desktop");   // PC일경우 사용할 테마
define("THEME_MOBILE", "desktop");   // 모바일일경우 사용할 테마

// 기능 사용여부 설정
define("USE_EMAIL_ID", TRUE);   // 아이디를 이메일 형식으로 사용합니다.
define("USE_EMAIL_VERFY", TRUE); // 이메일 인증 기능을 사용합니다
define("USE_BOARD", TRUE);  // 게시판 기능 사용여부
define("USE_SHOP", TRUE);   // 쇼핑물 기능 사용여부

// 이메일 발송 설정
define('SEND_EMAIL', 'noreply@wheeparam.com');  // 문의메일을 발송하는 이메일주소, 가급적 수정금지
define('SEND_EMAIL_SMTP_USE', FALSE);
define("SEND_EMAIL_SMTP_HOST", "");
define("SEND_EMAIL_SMTP_USER", "");
define("SEND_EMAIL_SMTP_PASS", "");
define("SEND_EMAIL_SMTP_PORT", 465);
define("SEND_EMAIL_SMTP_CRYP", "ssl");

// 파일 업로드 허용 확장자
define("FILE_UPLOAD_ALLOW","csv|psd|pdf|ai|eps|ps|smi|xls|ppt|pptx|gz|gzip|tar|tgz|zip|rar|bmp|gif|jpg|jpe|jpeg|png|tiff|tif|txt|text|rtl|xml|xsl|docx|doc|dot|dotx|xlsx|word|srt");