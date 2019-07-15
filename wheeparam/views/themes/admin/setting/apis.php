<div class="page-header">
    <h1 class="page-title">소셜/API 설정</h1>
</div>

<?=form_open_multipart("admin/setting/update", array("class"=>"form-flex"))?>
<input type="hidden" name="reurl" value="<?=base_url('admin/setting/apis')?>">
<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>페이스북 로그인</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="setting[social_facebook_use]">
                    <option value="Y" <?=$this->site->config('social_facebook_use')=='Y'?'selected':''?>>사용</option>
                    <option value="N" <?=$this->site->config('social_facebook_use')=='N'?'selected':''?>>미사용</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-wrap>
                <button type="button" class="btn btn-default btn-sm" data-toggle="popup" data-url="/admin/help/document/facebook_login"><i class="fal fa-question-circle"></i> 등록방법 자세히보기</button>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>APP ID</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[social_facebook_appid]" value="<?=$this->site->config('social_facebook_appid')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>APP Secret</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[social_facebook_appsecret]" value="<?=$this->site->config('social_facebook_appsecret')?>">
            </div>
        </div>
    </div>
</div>

<div class="H10"></div>

<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>구글 로그인</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="setting[social_google_use]">
                    <option value="Y" <?=$this->site->config('social_google_use')=='Y'?'selected':''?>>사용</option>
                    <option value="N" <?=$this->site->config('social_google_use')=='N'?'selected':''?>>미사용</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-wrap>
                <button type="button" class="btn btn-default btn-sm" data-toggle="popup" data-url="/admin/help/document/google_login"><i class="fal fa-question-circle"></i> 등록방법 자세히보기</button>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>클라이언트 ID</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[social_google_clientid]" value="<?=$this->site->config('social_google_clientid')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>클라이언트 보안비밀</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[social_google_clientsecret]" value="<?=$this->site->config('social_google_clientsecret')?>">
            </div>
        </div>
    </div>
</div>

<div class="H10"></div>


<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>네이버 로그인</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="setting[social_naver_use]">
                    <option value="Y" <?=$this->site->config('social_naver_use')=='Y'?'selected':''?>>사용</option>
                    <option value="N" <?=$this->site->config('social_naver_use')=='N'?'selected':''?>>미사용</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-wrap>
                <button type="button" class="btn btn-default btn-sm" data-toggle="popup" data-url="/admin/help/document/naver_login"><i class="fal fa-question-circle"></i> 등록방법 자세히보기</button>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>Client ID</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[social_naver_clientid]" value="<?=$this->site->config('social_naver_clientid')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>Client Secret</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[social_naver_clientsecret]" value="<?=$this->site->config('social_naver_clientsecret')?>">
            </div>
        </div>
    </div>
</div>

<div class="H10"></div>

<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>카카오 로그인</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="setting[social_kakao_use]">
                    <option value="Y" <?=$this->site->config('social_kakao_use')=='Y'?'selected':''?>>사용</option>
                    <option value="N" <?=$this->site->config('social_kakao_use')=='N'?'selected':''?>>미사용</option>
                </select>
            </div>
        </div>
        <div data-ax-td>
            <div data-ax-td-wrap>
                <button type="button" class="btn btn-default btn-sm" data-toggle="popup" data-url="/admin/help/document/kakao_login"><i class="fal fa-question-circle"></i> 등록방법 자세히보기</button>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>REST API Key</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[social_kakao_clientid]" value="<?=$this->site->config('social_kakao_clientid')?>">
            </div>
        </div>
    </div>
</div>

<div class="H10"></div>


<div data-ax-tbl>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>구글 reCaptcha</div>
            <div data-ax-td-wrap>
                <p class="form-control-static"><i class="fal fa-info-circle"></i> 구글 reCaptcha는 구글에서 제공하는 스팸방지 기능입니다.<br><i class="fal fa-info-circle"></i> 비 회원 글쓰기시 적용되며, 등록을 추천합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>Site KEY</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[google_recaptcha_site_key]" value="<?=$this->site->config('google_recaptcha_site_key')?>">
            </div>
        </div>
    </div>

    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>Secret KEY</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[google_recaptcha_secret_key]" value="<?=$this->site->config('google_recaptcha_secret_key')?>">
            </div>
        </div>
    </div>
</div>

<div class="H10"></div>

<div data-ax-tbl>
    <div class="caption">사이트 채널 연동</div>
    <div data-ax-tr>
        <div data-ax-td>
            <div data-ax-td-label>사이트 채널</div>
            <div data-ax-td-wrap>
                <select class="form-control form-control-inline" name="setting[channel_type]">
                    <option value="Person" <?=$this->site->config('channel_type')=='Person'?'selected':''?>>개인</option>
                    <option value="Organization" <?=$this->site->config('channel_type')=='Organization'?'selected':''?>>조직/회사</option>
                </select>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>네이버 블로그</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[channel_naver_blog]" value="<?=$this->site->config('channel_naver_blog')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>네이버 카페</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[channel_naver_cafe]" value="<?=$this->site->config('channel_naver_cafe')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>네이버 폴라</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[channel_naver_pholar]" value="<?=$this->site->config('channel_naver_pholar')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>네이버 포스트</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[channel_naver_post]" value="<?=$this->site->config('channel_naver_post')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>네이버 스토어 팜</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[channel_naver_storefarm]" value="<?=$this->site->config('channel_naver_storefarm')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>구글 플레이스토어</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[channel_playstore]" value="<?=$this->site->config('channel_playstore')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>페이스북</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[channel_facebook]" value="<?=$this->site->config('channel_facebook')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>인스타그램</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[channel_instagram]" value="<?=$this->site->config('channel_instagram')?>">
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>아이튠즈</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[channel_itunes]" value="<?=$this->site->config('channel_itunes')?>">
            </div>
        </div>
    </div>
</div>
<div class="text-center MT15">
    <button class="btn btn-primary"><i class="fal fa-save"></i> 저장하기</button>
</div>
<div class="H10"></div>
<?=form_close()?>

<script>
    $(function(){
        $('select[name="setting[social_facebook_use]"]').change(function(){
            if( $(this).find('option:selected').val() == 'Y' ) {
                $('input[name="setting[social_facebook_appid]"], input[name="setting[social_facebook_appsecret]"]').removeAttr('readonly');
            }
            else {
                $('input[name="setting[social_facebook_appid]"], input[name="setting[social_facebook_appsecret]"]').attr('readonly', 'readonly');
            }
        }).change();


        $('select[name="setting[social_google_use]"]').change(function(){
            if( $(this).find('option:selected').val() == 'Y' ) {
                $('input[name="setting[social_google_clientid]"], input[name="setting[social_google_clientsecret]"]').removeAttr('readonly');
            }
            else {
                $('input[name="setting[social_google_clientid]"], input[name="setting[social_google_clientsecret]"]').attr('readonly', 'readonly');
            }
        }).change();


        $('select[name="setting[social_kakao_use]"]').change(function(){
            if( $(this).find('option:selected').val() == 'Y' ) {
                $('input[name="setting[social_kakao_clientid]"]').removeAttr('readonly');
            }
            else {
                $('input[name="setting[social_kakao_clientid]"]').attr('readonly', 'readonly');
            }
        }).change();


        $('select[name="setting[social_naver_use]"]').change(function(){
            if( $(this).find('option:selected').val() == 'Y' ) {
                $('input[name="setting[social_naver_clientid]"], input[name="setting[social_naver_clientsecret]"]').removeAttr('readonly');
            }
            else {
                $('input[name="setting[social_naver_clientid]"], input[name="setting[social_naver_clientsecret]"]').attr('readonly', 'readonly');
            }
        }).change();

        $('[data-toggle="popup"]').click(function(){
            APP.POPUP({
                url : $(this).data('url'),
                width:800,
                height:600
            });
        });
    });
</script>
