<div class="col-xs-12">
    <div class="page-header">
        <h2 class="page-title"><i class="far fa-question-circle" style="display:inline"></i> 구글 로그인 등록방법</h2>
    </div>
    <div class="admin-help-wrap">
        <ul>
            <li>
                <p class="MT20 MB20">1. 구글 API 페이지로 이동합니다. <a href="https://console.developers.google.com/apis/library" target="_blank" class="point-color">구글 API 페이지 바로가기</a><br>
                    이동후 <span class="point-color">소셜 API에 Google+ API </span>를 클릭합니다.
                </p>
                <img src="/assets/images/admin/help/google_login01.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">2. 서비스 약관 업데이트 사항에 동의하고, <span class="point-color">프로젝트 만들기</span> 버튼을 클릭합니다. </p>
                <img src="/assets/images/admin/help/google_login02.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">3. <span class="point-color">새 프로젝트</span>를 생성합니다. 프로젝트 이름을 입력하고 <span class="point-color">만들기</span> 버튼을 클릭합니다. </p>
                <img src="/assets/images/admin/help/google_login03.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">4. 왼쪽 메뉴에서 <span class="point-color">사용자 인증 정보</span> 메뉴로 이동하여 사용자 인증 정보 만들기를 클릭 후 <br>두번째 <span class="point-color">OAuth 클라이언트 ID</span>를 클릭합니다.</p>
                <img src="/assets/images/admin/help/google_login04.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">5. <span class="point-color">동의 화면 구성</span> 버튼을 클릭 후 사용자 인증 정보에서 사용자에게 표시되는 제품 이름, <br> 홈페이지 URL 을 입력후 저장합니다.</p>
                <img src="/assets/images/admin/help/google_login05.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">
                    6. 다시 <span class="point-color">사용자 인증 정보</span> 페이지로 돌아와 <span class="point-color">사용자 인증 정보 만들기 클릭 후 OAuth 클라이언트 ID</span>를 선택합니다.
                </p>

                <img src="/assets/images/admin/help/google_login04.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">
                    7. 클라이언트 ID 만들기 페이지로 이동 후 <span class="point-color">애플리케이션 유형</span>은 <span class="point-color">웹 애플리케이션</span>을 선택하고, <br>
                    이름에 <span class="point-color">사이트 이름</span>을 입력합니다.<br>
                    승인된 자바스크립트 원본에는 <span class="point-color">도메인 주소</span>만 입력합니다. ex)https://www.wheeparam.com<br>
                    승인된 리디렉션 URI에는 <span class="point-color">휘파람 보드의 리디렉션 주소</span>를 입력합니다.<br>
                    <span class="point-color">도메인/members/social-login/google</span> -> ex)https://www.wheeparam.com/members/social-login/google<br>
                    정보 입력이 끝났으면 <span class="point-color">생성</span>버튼을 눌러 이동합니다.
                </p>
                <img src="/assets/images/admin/help/google_login06.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">7. OAuth 클라이언트 정보가 팝업에 나타납니다. <span class="point-color">클라이언트 ID</span>와 <span class="point-color">클라이언트 보안 비밀</span> 코드를 복사합니다.</p>
                <img src="/assets/images/admin/help/google_login07.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">
                    9. 구글 로그인 등록이 완료되었습니다.<br>
                    휘파람 관리자 페이지로 돌아와 구글 로그인 사용을 사용으로 선택합니다.<br>
                    복사한 <span class="point-color">[클라이언트 ID]</span>코드를 휘파람 보드 관리자 -> <span class="point-color">클라이언트 ID</span>에 붙여 넣습니다.<br>
                    그리고 <span class="point-color">[클라이언트 보안 비밀]</span>코드를 휘파람 보드 관리자 -> <span class="point-color">클라이언트 보안 비밀</span>에 붙여 넣기합니다.
                </p>
                <img src="/assets/images/admin/help/google_login08.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">9. 구글 로그인 설정이 완료되었습니다.</p>
            </li>
        </ul>
    </div>
</div>
