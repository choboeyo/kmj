# WheeparamBoard

휘파람보드

# 연결 설정
1. /wheeparam/config/wheeparam.php 에서 설정을 변경하세요.

# Docker 설정
1. /docker-compose.yml 파일에서 nginx container 와 php container를 타 프로젝트와 겹치지 않도록 변경하세요   
2. nginx container 내에 있는 외부 Port를 8088 에서 타 프로젝트와 겹치지 않도록 변경하세요
3. /nginx/nginx.conf 파일에서 16번째줄에 있는 php:9000 부분을 변경한 php 컨테이너를 변경하세요

# scss 설정
1. /_src/desktop/scss/_variables.scss 에서 색상을 변경하세요.