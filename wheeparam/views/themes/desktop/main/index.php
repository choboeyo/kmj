
<div class="page-header">
    <h4 class="page-title">최신 게시글 위젯 사용예제</h4>
</div>
<?php
/**
 * 최신게시글 위젯
 * @param string $skin_name     스킨 이름
 * @param string $brd_key       게시판 고유 키
 * @param int $rows             가져올 게시물 수 (default: 5)
 * @param bool $get_thumb_img   게시글의 대표이미지를 썸네일 처리할 것인지 여부 (default: FALSE)
 * @param bool $file_list       게시글에 첨부된 파일 목록도 로드할지 여부 (속도 느려짐) (default: FALSE)
 */
?>
<?=latest('basic', 'notice', 5,  FALSE, FALSE)?>



<!-- S: 연혁 위젯 사용 예제 -->
<div class="page-header">
    <h4 class="page-title">연혁 위젯 사용예제</h4>
</div>
<?php
/**
 * 연혁 위젯
 * @param string $skin_name     스킨 이름 (default: basic)
 * @param string $order_year    년도 정렬방식 (DESC, ASC) (default : DESC)
 * @param string $order_month   월 정렬방식 (DESC, ASC) (default: DESC)
 */
?>
<?=history('basic','DESC','DESC');?>
<!-- E: 연혁 위젯 사용 예제 -->

<!-- S: 문의하기 위젯 사용 예제 -->
<div class="page-header">
    <h4 class="page-title">문의하기 위젯 사용예제</h4>
</div>
<?php
/**
 * 문의하기 위젯
 * @param string $skin_name     스킨 이름 (default: basic)
 * @param string $complete_msg  문의 작성완료후 메시지 (default: 문의 작성이 완료되었습니다)
 */
?>
<?=contact_form('basic','문의 작성이 완료되었습니다.')?>
<!-- E: 문의하기 위젯 사용 예제 -->


<!-- S: 일반 위젯 사용 예제 -->
<div class="page-header">
    <h4 class="page-title">일반 위젯 사용예제</h4>
    <?php
    /**
     * 일반 위젯
     * @param string $widget_name 위젯 이름
     * @param array|object|null $widget_vars 위젯스킨에 넘겨줄 데이타 (default: [])
     */
    ?>
    <?=widget('hello-world')?>
</div>
<!-- E: 일반 위젯 사용 예제 -->


<!-- S: 상품 진열장 위젯 사용 예제 -->
<div class="page-header">
    <h4 class="page-title">상품 진열장 사용예제</h4>
    <?php
    /**
     * 상품진열장 위젯
     * @param string $skin_name 스킨 이름
     * @param string $dsp_key 진열장 고유 KEY
     */
    ?>
    <?=shop_display('basic','best')?>
</div>
<!-- E: 상품 진열장 위제 사용 예제 -->



<?=$asides_popup?>