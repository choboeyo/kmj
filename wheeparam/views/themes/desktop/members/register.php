<div class="container">
    <?=$form_open?>
    <article class="panel panel-default">
        <header class="panel-heading">
            <h1 class="panel-title"><?=langs('회원/register')?></h1>
        </header>

        <div class="panel-body">

            <h4>사이트 이용약관</h4>
            <div class="form-group">
                <textarea class="form-control" rows="5" readonly><?=html_symbol(get_summary($this->site->config('agreement_site'), FALSE))?></textarea>
                <div class="checkbox">
                    <label><input type="checkbox" value="Y" data-agree> 사이트 이용약관에 동의합니다.</label>
                </div>
            </div>


            <h4>개인정보 취급방침</h4>
            <div class="form-group">
                <textarea class="form-control" rows="5" readonly><?=html_symbol(get_summary($this->site->config('agreement_privacy'), FALSE))?></textarea>
                <div class="checkbox">
                    <label><input type="checkbox" value="Y" data-agree> 개인정보 취급방침에 동의합니다.</label>
                </div>
            </div>

            <?php // 위의 두 체크박스의 체크여부와 상관없이 아래의 체크박스 여부만 PHP단에서 체크함.?>
            <div class="checkbox">
                <label><input type="checkbox" name="agree" value="Y"> 이용약관에 모두 동의합니다.</label>
            </div>
        </div>

        <div class="panel-body">
            <!-- START :: 필수입력값 -->
            <fieldset>
                <legend>필수 입력정보</legend>
                <div class="form-group">
                    <label for="userid">아이디 <span class="text-danger">*</span><span class="sr-only">필수 입력</span></label>
                    <input class="form-control" name="userid" required>
                </div>
                <div class="form-group">
                    <label for="userpass">비밀번호 <span class="text-danger">*</span><span class="sr-only">필수 입력</span></label>
                    <input type="password" class="form-control" name="userpass" required>
                </div>
                <div class="form-group">
                    <label for="userpass">비밀번호 확인 <span class="text-danger">*</span><span class="sr-only">필수 입력</span></label>
                    <input type="password" class="form-control" name="userpass_confirm" required>
                </div>
                <div class="form-group">
                    <label for="userpass">닉네임 <span class="text-danger">*</span><span class="sr-only">필수 입력</span></label>
                    <input class="form-control" name="usernick" required>
                </div>

                <?php if( ! USE_EMAIL_ID ) : ?>
                <!-- 이메일 아이디 미사용시 이메일 입력폼 -->
                <div class="form-group">
                    <label for="userpass">E-mail</label>
                    <input class="form-control" name="useremail" required>
                </div>
                <?php endif;?>
            </fieldset>
            <!-- END :: 필수입력값 -->

            <!-- START :: 추가입력정보 -->
            <fieldset>
                <legend>추가입력정보</legend>
                <div class="form-group">
                    <label>연락처</label>
                    <input name="userphone" class="form-control">
                </div>

                <div class="form-group">
                    <label>성별</label>
                    <div class="radiobox">
                        <div class="radio-inline">
                            <label><input type="radio" name="usergender" value="M" checked> 남성</label>
                        </div>
                        <div class="radio-inline">
                            <label><input type="radio" name="usergender" value="F"> 여성</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="checkbox">
                        <label><input type="checkbox" name="recv_email" value="Y"> E-mail을 수신합니다.</label>
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" name="recv_sms" value="Y"> SMS를 수신합니다.</label>
                    </div>
                </div>
            </fieldset>
            <!-- END :: 추가입력정보 -->
            
            <div class="text-center">
                <button class="btn btn-primary"><?=langs('회원/register')?></button>
            </div>
        </div>

    </article>
    <?=$form_close?>
</div>

<script>
    $(function(){
        // 각각의 체크박스 상태에 따라 모두 동의 체크 박스 변경
        $('[data-agree]').change(function(){
            $('input[name="agree"]').prop('checked', ($('[data-agree]').length == $('[data-agree]:checked').length ));
        });

        // 모두 동의 체크박스 상태에 따라 각각의 체크박스 상태 변경
        $('input[name="agree"]').change(function(){
            $('[data-agree]').prop('checked', $(this).prop('checked'));
        });
    });
</script>