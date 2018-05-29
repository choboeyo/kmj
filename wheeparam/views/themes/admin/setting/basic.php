<div class="page-header">
    <h1 class="page-title">사이트 기본 설정</h1>
</div>

<?=form_open_multipart("admin/setting/update", array('class'=>"form-flex"))?>
<input type="hidden" name="reurl" value="<?=base_url('admin/setting/basic')?>">

<div class="panel panel-dark">
    <div class="panel-heading">
        <h4 class="panel-title">정보 설정</h4>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="control-label">사이트 이름</label>
            <div class="controls">
                <input class="form-control" name="setting[site_title]" value="<?=$this->site->config('site_title')?>">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">사이트 부제</label>
            <div class="controls">
                <input class="form-control" name="setting[site_subtitle]" value="<?=$this->site->config('site_subtitle')?>">
                <p class="help-block">페이지 제목이 설정되어 있지 않으면 부제가 붙습니다.</p>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">사이트 키워드</label>
            <div class="controls">
                <input class="form-control" name="setting[site_meta_keywords]" value="<?=$this->site->config('site_meta_keywords')?>">
                <p class="help-block">10~20의 고유한 단어나 문구로 유지하세요. 단어나 구문을 반복하지 마세요. 목록의 처음에 가장 중요한 단어나 구문을 넣으세요.</p>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">사이트 요약</label>
            <div class="controls">
                <textarea class="form-control" name="setting[site_meta_description]" rows="4"><?=$this->site->config('site_meta_description')?></textarea>
                <p class="help-block">주요 키워드를 사용한 알아보기 쉽고, 설득력있는 내용을 입력하세요. 키워드로 채우는것은 피하세요.</p>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">사이트 대표 이미지</label>
            <div class="controls">
                <?php if($this->site->config('site_meta_image')) : ?>
                    <img src="<?=base_url($this->site->config('site_meta_image'))?>" class="img-responsive">
                    <div class="H10"></div>
                    <label class="checkbox-inline"><input type="checkbox" value="Y" name="remove_site_meta_image"> 현재 이미지 삭제</label>
                    <div class="H20"></div>
                <?php endif;?>
                <input type="file" name="site_meta_image" class="form-control">
                <p class="help-block">1200x600의 이미지를 사용하세요.</p>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <button class="btn btn-primary">저장하기</button>
    </div>
</div>

<div class="H10"></div>

<div class="panel panel-dark">
    <div class="panel-heading">
        <h4 class="panel-title">관리자 설정</h4>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="control-label">관리자 이메일</label>
            <div class="controls">
                <input class="form-control" name="setting[email_send_address]" value="<?=$this->site->config('email_send_address')?>">
                <p class="help-block">메일을 발송할때 사용할 이메일주소, 문의를 받을 이메일 주소입니다.</p>
            </div>
        </div>

    </div>
    <div class="panel-footer">
        <button class="btn btn-primary">저장하기</button>
    </div>
</div>

<div class="H10"></div>

<div class="panel panel-dark">
    <div class="panel-heading">
        <h4 class="panel-title">보안 설정</h4>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="control-label">허용 호스트</label>
            <div class="controls">
                <textarea class="form-control" name="setting[allow_host]" rows="10"><?=$this->site->config('allow_host')?></textarea>
                <p class="help-block">iframe으로 담을수 있는 허용 호스트 입니다. 한줄에 하나씩 입력하세요</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">접근금지 IP</label>
            <div class="controls">
                <textarea class="form-control" name="setting[deny_ip]" rows="5"><?=$this->site->config('deny_ip')?></textarea>
                <ul class="help-block">
                    <li>접근 금지시킬 IP를 설정합니다.한줄에 하나씩 입력합니다.</li>
                    <li>192.168.2.10 : 4자리의 정확한 ip주소</li>
                    <li>192.168.*.* : 와일드카드(*)가 사용된 4자리의 ip주소, a클래스에는 와일드카드 사용불가,</li>
                    <li>192.168.1.1-192.168.1.10 : 하이픈(-)으로 구분된 정확한 4자리의 ip주소 2개</li>
                </ul>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">사용금지 ID</label>
            <div class="controls">
                <textarea class="form-control" name="setting[deny_id]" rows="4"><?=$this->site->config('deny_id')?></textarea>
                <p class="help-block">가입시 금지시킬 단어를 설정합니다.공백없이 콤마(,)로 구분하여 입력합니다.</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">금지 닉네임</label>
            <div class="controls">
                <textarea class="form-control" name="setting[deny_nickname]" data-role="tagsinput" rows="4"><?=$this->site->config('deny_nickname')?></textarea>
                <p class="help-block">가입시 금지시킬 단어를 설정합니다.공백없이 콤마(,)로 구분하여 입력합니다.</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label">금지 단어</label>
            <div class="controls">
                <textarea class="form-control" name="setting[deny_word]" data-role="tagsinput" rows="4"><?=$this->site->config('deny_word')?></textarea>
                <p class="help-block">사용 금지시킬 단어를 설정합니다.공백없이 콤마(,)로 구분하여 입력합니다.</p>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <button class="btn btn-primary">저장하기</button>
    </div>
</div>

<div class="H10"></div>


<div class="panel panel-dark">
    <div class="panel-heading">
        <h4 class="panel-title">기타 설정</h4>
    </div>
    <div class="panel-body">
        <div class="form-group">
            <label class="control-label">구글 소유확인 코드</label>
            <div class="controls">
                <input class="form-control" name="setting[verification_google]" value="<?=htmlspecialchars($this->site->config('verification_google'))?>">
                <div class="H10"></div>
                <button type="button" class="btn btn-default" data-toggle="popup" data-url="/admin/help/document/google_ownership"><i class="far fa-question-circle"></i> 도움말</button>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">네이버 소유확인 코드</label>
            <div class="controls">
                <input class="form-control" name="setting[verification_naver]" value="<?=htmlspecialchars($this->site->config('verification_naver'))?>">
                <div class="H10"></div>
                <button type="button" class="btn btn-default" data-toggle="popup" data-url="/admin/help/document/naver_ownership"><i class="far fa-question-circle"></i> 도움말</button>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">구글 애널리틱스 코드</label>
            <div class="controls">
                <textarea class="form-control" name="setting[analytics_google]" rows="5"><?=htmlspecialchars($this->site->config('analytics_google'))?></textarea>
                <div class="H10"></div>
                <button type="button" class="btn btn-default" data-toggle="popup" data-url="/admin/help/document/google_analytics"><i class="far fa-question-circle"></i> 도움말</button>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">네이버 애널리틱스 코드</label>
            <div class="controls">
                <textarea class="form-control" name="setting[analytics_naver]" rows="5"><?=htmlspecialchars($this->site->config('analytics_naver'))?></textarea>
                <div class="H10"></div>
                <button type="button" class="btn btn-default" data-toggle="popup" data-url="/admin/help/document/naver_analytics"><i class="far fa-question-circle"></i> 도움말</button>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label">기타 추가 스크립트</label>
            <div class="controls">
                <textarea class="form-control" name="setting[analytics_etc]" rows="5"><?=htmlspecialchars($this->site->config('analytics_etc'))?></textarea>
                <p class="help-block">다른 추가 스크립트를 입력합니다.</p>
            </div>
        </div>

    </div>
    <div class="panel-footer">
        <button class="btn btn-primary">저장하기</button>
    </div>
</div>
<?=form_close()?>
<script>
    $(function(){
        $('[data-toggle="popup"]').click(function(){
            APP.POPUP({
                url : $(this).data('url'),
                width:800,
                height:600
            });
        });
    });
</script>