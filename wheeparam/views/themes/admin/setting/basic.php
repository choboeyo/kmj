<div class="page-header">
    <h1 class="page-title">사이트 기본 설정</h1>
</div>

<?=form_open_multipart("admin/setting/update", array('class'=>"form-flex"))?>
<input type="hidden" name="reurl" value="<?=base_url('admin/setting/basic')?>">

<div data-ax-tbl>
    <div class="caption">정보 설정</div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>사이트 이름</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[site_title]" value="<?=$this->site->config('site_title')?>" required>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>사이트 부제</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[site_subtitle]" value="<?=$this->site->config('site_subtitle')?>">
                <p class="help-block">페이지 제목이 설정되어 있지 않으면 부제가 붙습니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>사이트 키워드</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[site_meta_keywords]" value="<?=$this->site->config('site_meta_keywords')?>">
                <p class="help-block">10~20의 고유한 단어나 문구로 유지하세요. 단어와 단어 사이는 콤마(,)로 구분합니다. 단어나 구문을 반복하지 마세요. 목록의 처음에 가장 중요한 단어나 구문을 넣으세요.</p>
            </div>
        </div>
    </div>

    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>사이트 요약</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[site_meta_description]" data-autosize><?=$this->site->config('site_meta_description')?></textarea>
                <p class="help-block">주요 키워드를 사용한 알아보기 쉽고, 설득력있는 내용을 입력하세요. 키워드로 채우는것은 피하세요.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>사이트<br>대표 이미지</div>
            <div data-ax-td-wrap>
                <?php if($this->site->config('site_meta_image')) : ?>
                    <img src="<?=base_url($this->site->config('site_meta_image'))?>" class="img-responsive">
                    <div class="H10"></div>
                    <label class="w-check"><input type="checkbox" value="Y" name="remove_site_meta_image"><span>등록된 이미지 삭제</span></label>
                    <div class="H20"></div>
                <?php endif;?>
                <input type="file" name="site_meta_image" class="form-control">
                <p class="help-block">1200x600의 이미지를 사용하세요.</p>
            </div>
        </div>
    </div>
</div>

<div class="text-center MT10">
    <button class="btn btn-primary"><i class="fal fa-save"></i> 저장하기</button>
</div>

<div class="H30"></div>

<div data-ax-tbl>
    <div class="caption">관리자 설정</div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>관리자 이메일</div>
            <div data-ax-td-wrap>
                <input class="form-control" name="setting[email_send_address]" data-regex="email-address" value="<?=$this->site->config('email_send_address')?>">
                <p class="help-block">메일을 발송할때 사용할 이메일주소, 문의를 받을 이메일 주소입니다.</p>
            </div>
        </div>
    </div>
</div>

<div class="text-center MT10">
    <button class="btn btn-primary"><i class="fal fa-save"></i> 저장하기</button>
</div>

<div class="H30"></div>

<div data-ax-tbl>
    <div class="caption">보안 설정</div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>허용 호스트</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[allow_host]" data-autosize><?=$this->site->config('allow_host')?></textarea>
                <p class="help-block">iframe으로 담을수 있는 허용 호스트 입니다. 한줄에 하나씩 입력하세요</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>접근금지 IP</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[deny_ip]" data-autosize><?=$this->site->config('deny_ip')?></textarea>
                <ul class="help-block">
                    <li>접근 금지시킬 IP를 설정합니다.한줄에 하나씩 입력합니다.</li>
                    <li>192.168.2.10 : 4자리의 정확한 ip주소</li>
                    <li>192.168.*.* : 와일드카드(*)가 사용된 4자리의 ip주소, a클래스에는 와일드카드 사용불가,</li>
                    <li>192.168.1.1-192.168.1.10 : 하이픈(-)으로 구분된 정확한 4자리의 ip주소 2개</li>
                </ul>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>사용금지 ID</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[deny_id]" data-autosize><?=$this->site->config('deny_id')?></textarea>
                <p class="help-block">가입시 금지시킬 단어를 설정합니다.공백없이 콤마(,)로 구분하여 입력합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>금지 닉네임</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[deny_nickname]" data-autosize><?=$this->site->config('deny_nickname')?></textarea>
                <p class="help-block">가입시 금지시킬 단어를 설정합니다.공백없이 콤마(,)로 구분하여 입력합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>금지 단어</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[deny_word]" data-autosize><?=$this->site->config('deny_word')?></textarea>
                <p class="help-block">사용 금지시킬 단어를 설정합니다.공백없이 콤마(,)로 구분하여 입력합니다.</p>
            </div>
        </div>
    </div>
</div>

<div class="text-center MT10">
    <button class="btn btn-primary"><i class="fal fa-save"></i> 저장하기</button>
</div>

<div class="H30"></div>

<div data-ax-tbl>
    <div class="caption">기타 설정</div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>추가 메타태그</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[extra_tag_meta]" data-autosize><?=$this->site->config('extra_tag_meta')?></textarea>
                <p class="help-block">&lt;head&gt;와&lt;/head&gt;사이에 들어갈 추가 메타 태그를 입력합니다.</p>
            </div>
        </div>
    </div>
    <div data-ax-tr>
        <div data-ax-td class="width-100">
            <div data-ax-td-label>추가 스크립트</div>
            <div data-ax-td-wrap>
                <textarea class="form-control" name="setting[extra_tag_script]" data-autosize><?=$this->site->config('extra_tag_script')?></textarea>
                <p class="help-block">추가 스크립트 태그를 입력합니다. 여닫는 태그 (&lt;script&gt; 와 &lt;/script&gt;)를 포함하여 넣어야 합니다.</p>
            </div>
        </div>
    </div>
</div>
<div class="text-center MT10">
    <button class="btn btn-primary"><i class="fal fa-save"></i> 저장하기</button>
</div>
<?=form_close()?>