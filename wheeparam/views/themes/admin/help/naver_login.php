<div class="col-xs-12">
    <div class="page-header">
        <h2 class="page-title"><i class="far fa-question-circle" style="display:inline"></i> 네이버 로그인 등록방법</h2>
    </div>
    <div class="admin-help-wrap">
        <ul>
            <li>
                <p class="MT20 MB20">
                    1. NAVER Developers 페이지로 이동합니다. <a href="https://developers.naver.com/main/" target="_blank" class="point-color">NAVER Developers 페이지 바로가기</a><br>
                    이동후 <span class="point-color">Application에 계정설정</span>을 클릭합니다.<br>
                    처음방문하시는 환영인사와 함께 API이용약관에 동의페이지가 나옵니다.<br>
                    <span class="point-color">"이용약관에 동의합니다."에 체크하시고 확인</span>버튼을 누릅니다.
                </p>
                <img src="/assets/images/admin/help/naver_login01.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">
                    2. <span class="point-color">휴대폰 번호를 인증</span>하고 <span class="point-color">회사이름</span> 입력합니다.<br>
                    <span class="point-color">"소속 회사 이름은 제휴 접수 및 검토 등을 위해 사용되며 서비스 이용기간 동안 보관됩니다."</span>에 체크하고 <br>확인 버튼을 클릭합니다.
                </p>
                <img src="/assets/images/admin/help/naver_login02.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">3.<span class="point-color">애플리케이션 이름 입력</span>, 사용 API에서 <span class="point-color">네아로(네이버 아이디로 로그인)를 선택</span>하고, <span class="point-color">기본정보, 이메일, 회원이름을 체크</span>합니다.
                    로그인 오픈 API 서비스 환경에서 환경추가를 클릭하여 <span class="point-color">PC 웹</span>을 선택합니다.<br>
                    서비스 URL에 <span class="point-color">www를 제외한 사이트 주소</span>를 입력합니다.  ex)https://wheeparam.com <br>
                    다음으로 <span class="point-color">네이버아이디로 로그인 Callback URL</span> (최대 5개) 주소 입력란에 휘파람 보드의 Callback 주소를 입력합니다.<br>
                    <span class="point-color">도메인/members/social-login/naver</span> -> ex)https://www.wheeparam.com/members/social-login/naver 을 입력합니다.<br>
                    모든 입력이 완료되었으면 <span class="point-color">등록하기</span> 버튼을 클릭합니다.
                </p>
                <img src="/assets/images/admin/help/naver_login03.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">4.왼쪽의 메뉴에서 내 애플리케이션에 생성한 어플리케이션이 추가 되었는지 확인합니다.<br>
                    생성된 어플리케이션을 더블클릭하고 <span class="point-color">API 설정에서 애플리케이션 개발 상태를 서비스 적용을 선택하고 수정</span> 버튼을 클릭합니다.
                </p>
                <img src="/assets/images/admin/help/naver_login04.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">5.개요부분을 클릭하여 네이버 아이디로 로그인 부분의 개발상태가 서비스 적용으로 적용되었는지 확인하고, <span class="point-color">애플리케이션 정보 Client ID, Client Secret 코드를 복사</span>합니다.
                </p>
                <img src="/assets/images/admin/help/naver_login05.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">
                    6. 네이버 로그인 등록이 완료되었습니다.<br>
                    휘파람 관리자 페이지로 돌아와 네이버 로그인 사용을 사용으로 선택합니다.<br>
                    <span class="point-color">[Client ID]</span>코드를 휘파람 보드 관리자 -> <span class="point-color">Client ID</span>에 붙여 넣습니다.<br>
                    <span class="point-color">[Client Secret]</span>코드를 보기를 눌러 휘파람 보드 관리자 -> <span class="point-color">Client Secret</span>에 붙여 넣기합니다.
                </p>
                <img src="/assets/images/admin/help/naver_login06.png" alt="">
            </li>
            <li>
                <p class="MT20 MB20">7.네이버 로그인 설정이 완료되었습니다.</p>
            </li>
        </ul>
    </div>
</div>