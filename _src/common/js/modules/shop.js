APP.SHOP = {};

/**
 * 특정상품의 빠른 장바구니 화면을 불러옵니다.
 * @param prd_idx
 */
APP.SHOP.viewQuickCart = function(prd_idx) {
    var $container =  $('[data-container="quick-cart-'+prd_idx+'"]');

    // 열려있는 다른 퀵 컨테이너를 모두 닫는다.
    APP.SHOP.closeQuickCart();

    if($container.length === 0) return;

    $.ajax({
        url: base_url + '/products/quick_buy/' + prd_idx,
        type: 'GET',
        dataType: 'html',
        success: function(html) {
            $container.append(html);

            $('[data-product-cart="1"]').chained('[data-product-cart="0"]');
            $('[data-product-cart="2"]').chained('[data-product-cart="1"]');
        }
    })
};

/**
 * 찜 토글
 * @param prd_idx
 */
APP.SHOP.toggleWish = function(prd_idx){

    $.ajax({
        url: base_url + '/ajax/products/wish',
        type: 'POST',
        data: {
            prd_idx: prd_idx
        },
        success: function() {
            location.reload();
        }
    })
}

/**
 * 우편번호 검색
 */
APP.SHOP.searchZonecode = function() {
  new daum.Postcode({
      oncomplete: function(data) {
          var extraAddr = "";
          var zonecode = data.zonecode;
          var address = data.roadAddress;

          if(data.bname !== "" &&  /[동|로|가]$/g.test(data.bname)) {
              extraAddr += data.bname;
          }

          if(data.buildingName !== ''){
              extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
          }

          if(extraAddr !== ''){
              extraAddr = ' (' + extraAddr + ')';
          }

          $('[data-input="zonecode"]').val( zonecode );
          $('[data-input="address"]').val( address );
          $('[data-input="addressDetail"]').val( extraAddr ).focus();
      }
  }).open();
};

/**
 * 열려있는 빠른 장바구니 화면을 닫습니다.
 */
APP.SHOP.closeQuickCart = function() {

    // 열려있는 다른 퀵 컨테이너를 모두 닫는다.
    $('[data-wrap="quick-cart"]').remove();

}

/**
 * 장바구니 업데이트후 콜백이벤트
 */
APP.SHOP.updateCartCallback = function() {

};

APP.SHOP.calcItemPrice = function() {
    var $form = $('[data-form="product-cart"]');
    var prd_price = $('[name="prd_price[]"]', $form).val() * 1;
    var prd_idx = $('[name="prd_idx[]"]').val()

    var total_price = 0;

    $('[data-container="selected-options"] li').each(function() {
       var $li = $(this),
           qty = $('[name="cart_qty['+prd_idx+'][]"]', $li).val() * 1,
           type = $('[name="opt_type['+prd_idx+'][]"]', $li).val(),
           add_price = $('[name="opt_price"]', $li).val() * 1;

       if(type === '' || type === 'detail') {
           total_price += ( prd_price + add_price ) * qty
       } else if(type === 'addition') {
           total_price += ( add_price ) * qty
       }
    });

    $('[data-container="total-price"]').text(total_price.numberFormat());
}

/**
 * 리뷰 작성창 닫기
 */
APP.SHOP.closeReviewWrite = function() {
    $('[data-container="item-review"] [data-container="item-review-write"]').empty();
}

/**
 * 문의 작성창 닫기
 */
APP.SHOP.closeQnaWrite = function() {
    $('[data-container="item-qna"] [data-container="item-qna-write"]').empty();
}

/**
 * 상품 보기 페이지
 */
$(function() {
    if($('[data-form="product-cart"]').length> 0) {
        $(document).on('click','[data-button="btn-remove-qty"]',function(e) {
            e.preventDefault();
            $(this).parents('[data-item="option"]').remove();
        });

        $(document).on('click','[data-button="qty-minus"]', function(e) {
            e.preventDefault();
            var qty = $(this).parent().find('[data-input="cart-qty"]').val() * 1
            if(qty > 1) {
                qty--;
            }
            $(this).parent().find('[data-input="cart-qty"]').val(qty);

            APP.SHOP.calcItemPrice();
        });

        $(document).on('click','[data-button="qty-plus"]', function(e) {
            e.preventDefault();
            var qty = $(this).parent().find('[data-input="cart-qty"]').val() * 1
            qty++;
            $(this).parent().find('[data-input="cart-qty"]').val(qty);

            APP.SHOP.calcItemPrice();
        });

        $(document).on('click','[data-button="buy-item"]', function(e) {
            var is_direct = $(this).attr('data-direct') === 'Y' ? 'Y' : 'N';

            var $frm = $('[data-form="product-cart"]');
            $('[name="is_direct"]', $frm).val( is_direct );

            var $sel = $frm.find('[data-container="selected-options"]');
            if($sel.find('li').length === 0) {
                alert('상품 필수옵션을 선택하셔야 합니다.');
                return;
            }
            var $total_price = $('[data-container="total-price"]').text().replace(/,/g,'') * 1
            if($total_price <= 0) {
                alert('구매금액이 음수이거나, 0원입니다.');
                return;
            }
            $.ajax({
                url: base_url + '/ajax/shop/cart',
                type: "POST",
                data: $frm.serialize(),
                async: true,
                cache: false,
                success: function() {
                    APP.SHOP.updateCartCallback();

                    if(is_direct === 'Y') {
                        location.href= base_url + '/shop/order/direct';
                    } else {
                        if(confirm('장바구니에 상품이 담겼습니다.\n장바구니로 이동하시겠습니까?')) {
                            location.href= base_url + '/shop/cart';
                        }
                    }
                }
            })
        });

        $('[data-product-additional]').on('change', function(e) {
            var opt = $(this).find('option:selected');

            var isExist = false;
            var prd_idx = $('[data-form="product-cart"] [name="prd_idx[]"]').val()

            $('[name="opt_code['+prd_idx+'][]"]').each(function() {
                if($(this).val() == opt.val()) {
                    isExist = true;
                    return;
                }
            })

            if(! isExist) {

                $('[data-template="option"]').tmpl({
                    opt_code: opt.val(),
                    opt_type: 'addition',
                    opt_value: opt.val(),
                    opt_price: opt.attr('data-price') * 1,
                    opt_stock : opt.attr('data-stock') * 1,
                    opt_name: opt.val()
                }).appendTo('[data-container="selected-options"]');
            }

            $('[data-product-additional]').val('');

            APP.SHOP.calcItemPrice();
        });

        // 만약 필수옵션이 하나도 없다면?
        if($('[data-product-cart]').length <= 0) {
            $('[data-template="option"]').tmpl({
                opt_code: '',
                opt_type: '',
                opt_value: '',
                opt_price: 0,
                opt_stock :'',
                opt_name: $('[name="prd_name[]"]').val()
            }).appendTo('[data-container="selected-options"]');

            APP.SHOP.calcItemPrice();
        } else {
            var last_select = 0;
            if($('[data-product-cart="1"]').length > 0) {
                $('[data-product-cart="1"]').chained('[data-product-cart="0"]');
                last_select = 1;
            }
            if($('[data-product-cart="2"]').length > 0) {
                $('[data-product-cart="2"]').chained('[data-product-cart="1"]');
                last_select = 2;
            }

            $('[data-product-cart="'+last_select+'"]').on('change', function(e) {
                var opt = $(this).find('option:selected');
                var opt_value = [];

                $('[data-product-cart]').each(function() {
                    var label = $(this).attr('data-label');
                    var name = $(this).find('option:selected').attr('data-name')
                    if(typeof name !== 'undefined' &&  name.length > 0) {
                        opt_value.push(label+':'+ name);
                    }
                })

                if(opt_value.length === $('[data-product-cart]').length)
                {
                    var isExist = false;
                    var prd_idx = $('[data-form="product-cart"] [name="prd_idx[]"]').val()

                    $('[name="opt_code['+prd_idx+'][]"]').each(function() {
                        if($(this).val() == opt.val()) {
                            isExist = true;
                            return;
                        }
                    })

                    if(! isExist) {

                        $('[data-template="option"]').tmpl({
                            opt_code: opt.val(),
                            opt_type: 'detail',
                            opt_value: opt_value.join(" / "),
                            opt_price: opt.attr('data-price') * 1,
                            opt_stock : opt.attr('data-stock') * 1,
                            opt_name: opt_value.join(" / ")
                        }).appendTo('[data-container="selected-options"]');
                    }

                    $('[data-product-cart]').val('');

                    APP.SHOP.calcItemPrice();
                }
            })
        }

        // 옵션선택폼의 마지막을 선택하면
    }

    /**
     * 상품문의 가져오기
     */
    if($('[data-container="item-qna"]').length > 0) {

        var prd_idx = $('[data-container="item-review"]').attr('data-prd-idx');

        APP.SHOP.getQnaList(prd_idx, 1);

        $(document).on('click.pagination','[data-container="qna-pagination"] li a', function(e) {
            e.preventDefault();
            var page = $(this).attr('data-page');
            APP.SHOP.getQnaList(prd_idx, page);
        })

        $(document).on('click.qna_write', '[data-button="qna-write"]', function(e) {
            $('[data-container="item-qna"] [data-container="item-qna-write"]').empty();

            $.ajax({
                url: base_url + '/products/qna_write/' + prd_idx,
                type: "GET",
                success: function(res) {
                    $('[data-container="item-qna"] [data-container="item-qna-write"]').html(res);
                }
            })
        });

        $(document).on('submit', '[data-form="item-qna-write"]', function(e) {
            e.preventDefault();

            var $form = $(this);

            if($('[name="qa_content"]', $form).val().trim() === '' ) {
                alert('문의 내용을 작성해주세요');
                $('[name="qa_content"]', $form).focus();
                return false;
            }

            $.ajax({
                url: base_url + '/ajax/products/qna',
                type: 'POST',
                data: $form.serialize(),
                success: function() {
                    APP.SHOP.closeQnaWrite();
                    APP.SHOP.getQnaList(prd_idx, 1);
                }
            })
        })
    }

    /**
     * 상품리뷰 수정하기
     */
    $(document).on('click.review_edit', '[data-button="review-edit"]', function(e) {
        e.preventDefault();

        var prd_idx = $(this).attr('data-prd'),
            rev_idx = $(this).attr('data-idx');

        prd_idx = typeof prd_idx !== 'undefined' && prd_idx ? prd_idx : null;
        rev_idx = typeof rev_idx !== 'undefined' && rev_idx ? rev_idx : null;

        if(! prd_idx || !rev_idx) {
            return;
        }

        $.ajax({
            url: `${base_url}/products/reviews_write/${prd_idx}/${rev_idx}`,
            type: "GET",
            success: function(res) {
                $('[data-container="item-review"] [data-container="item-review-write"]').html(res);
            }
        })
    })

    /**
     * 상품리뷰 삭제하기
     */
    $(document).on('click.review_delete', '[data-button="review-delete"]', function(e) {
        e.preventDefault();

        var prd_idx = $(this).attr('data-prd'),
            rev_idx = $(this).attr('data-idx');

        prd_idx = typeof prd_idx !== 'undefined' && prd_idx ? prd_idx : null;
        rev_idx = typeof rev_idx !== 'undefined' && rev_idx ? rev_idx : null;

        if(! prd_idx || !rev_idx) {
            return;
        }

        if(! confirm('작성하신 리뷰를 삭제하시겠습니까?')) return;

        $.ajax({
            url: `${base_url}/ajax/products/reviews/${prd_idx}/${rev_idx}`,
            type: "DELETE",
            success: function(res) {
                location.reload();
            }
        })
    })

    /**
     * 상품리뷰 가져오기
     */
    if($('[data-container="item-review"]').length >0) {

        var prd_idx = $('[data-container="item-review"]').attr('data-prd-idx');

        if(typeof prd_idx !== 'undefined' && prd_idx) {
            APP.SHOP.getReviewList(prd_idx, 1);

            $(document).on('click.pagination','[data-container="review-pagination"] li a', function(e) {
                e.preventDefault();
                var page = $(this).attr('data-page');
                APP.SHOP.getReviewList(prd_idx, page);
            })
        }

        $(document).on('click.review_write', '[data-button="review-write"]', function(e) {
            $('[data-container="item-review"] [data-container="item-review-write"]').empty();

            $.ajax({
                url: base_url + '/products/reviews_write/' + prd_idx,
                type: "GET",
                success: function(res) {
                    $('[data-container="item-review"] [data-container="item-review-write"]').html(res);
                }
            })
        });

        $(document).on('submit', '[data-form="item-review-write"]', function(e) {
            e.preventDefault();

            var $form = $(this);
            if($('[name="od_id"]', $form).find('option:selected').val() === '' ) {
                alert('리뷰를 남길 주문번호를 선택해주세요');
                $('[name="od_id"]', $form).focus();
                return false;
            }

            if($('[name="rev_score"]', $form).find('option:selected').val() === '' ) {
                alert('평점을 선택해주세요');
                $('[name="rev_score"]', $form).focus();
                return false;
            }

            if($('[name="rev_content"]', $form).val().trim() === '' ) {
                alert('리뷰 내용을 작성해주세요');
                $('[name="rev_content"]', $form).focus();
                return false;
            }

            $.ajax({
                url: base_url + '/ajax/products/reviews',
                type: 'POST',
                data: $form.serialize(),
                success: function() {
                    APP.SHOP.closeReviewWrite();

                    if($('[data-container="item-review"]').attr('data-no-list')  * 1 === 1) {
                        location.reload();
                    }
                    APP.SHOP.getReviewList(prd_idx, 1);
                }
            })
        })
    }

    /**
     * 상품문의 삭제하기
     */
    $(document).on('click.qa_delete', '[data-button="delete-qna"]', function(e) {
        e.preventDefault();

        var idx = $(this).attr('data-idx');
        idx = typeof idx !== 'undefined' && idx ? idx: null;

        if(! idx) return;

        if(! confirm('해당 상품문의를 삭제하시겠습니까?')) return

        $.ajax({
            url: `${base_url}ajax/products/qna/${idx}`,
            type:'DELETE',
            success: function() {
                location.reload();
            }
        })
    })
})

APP.SHOP.getReviewList = function(prd_idx, page) {

    if($('[data-container="item-review"]').length <= 0) return;

    if($('[data-container="item-review"]').attr('data-no-list')  * 1 === 1) {
        return;
    }

    var $form = $('[data-form="item-review-list"]', '[data-container="item-review"]');
    var data = '';
    if($form.length === 0 ) {
        data= 'page=' + page;
    } else {
        data = $form.serialize() + '&page=' + page;
    }

    $.ajax({
        url: '/products/reviews/' + prd_idx,
        type: 'GET',
        data: data,
        success: function(res) {
            $('[data-container="item-review"]').html(res);
        },
        global:false
    })
}

APP.SHOP.getQnaList = function(prd_idx, page) {

    if($('[data-container="item-qna"]').length <= 0) return;

    var $form = $('[data-form="item-qna-list"]', '[data-container="item-qna"]');
    var data = '';
    if($form.length === 0 ) {
        data= 'page=' + page;
    } else {
        data = $form.serialize() + '&page=' + page;
    }

    $.ajax({
        url: '/products/qna/' + prd_idx,
        type: 'GET',
        data: data,
        success: function(res) {
            $('[data-container="item-qna"]').html(res);
        },
        global:false
    })
}

/**
 * 장바구니 페이지
 */
$(function() {
    /**
     * 장바구니 상품 수량 변경
     */
    if($('[data-button="modify-cart-option"]').length > 0) {
        $('[data-button="modify-cart-option"]').on('click',function(e) {
            e.preventDefault();

            var prd_idx = $(this).attr('data-idx');
            var direct = $(this).attr('data-direct');
            var $container = $('[data-container="cart-modify-form"]')

            $.ajax({
                url: base_url+'/shop/cart-modify',
                type: 'GET',
                data: {
                    prd_idx: prd_idx,
                    is_direct : direct
                },
                dataType:'html',
                success: function(res) {
                    $container.html(res);

                    $('[data-product-cart="1"]').chained('[data-product-cart="0"]');
                    $('[data-product-cart="2"]').chained('[data-product-cart="1"]');
                }
            })
        });
    }

    /**
     * 장바구니 선택 삭제
     */
    if($('[data-button="delete-selected-cart"]').length > 0) {
        $('[data-button="delete-selected-cart"]').on('click', function(e) {
           var $form = $('[data-form="shop-cart"]');
           var is_direct = $(this).attr('data-direct');

           if($form.length === 0) return;

           var prd_idx = [];
           $form.find('[name="prd_idx[]"]').each(function() {
               if( $(this).prop('checked') ) {
                   prd_idx.push( $(this).val() );
               }
           });

           if(prd_idx.length === 0) {
               alert('장바구니에서 삭제할 상품을 선택해주세요');
               return;
           }

           if(!confirm(`선택하신 ${prd_idx.length}개의 상품을 장바구니에서 삭제하시겠습니까? `)) return;


            $.ajax({
                url: base_url + '/ajax/shop/cart',
                type: 'DELETE',
                data: {
                    prd_idx : prd_idx,
                    is_direct : is_direct
                },
                success: function() {
                    location.reload();
                }
            })
        });
    }

    /**
     * 장바구니 비우기
     */
    if($('[data-button="delete-all-cart"]').length> 0) {
        $('[data-button="delete-all-cart"]').on('click', function(e) {
            e.preventDefault();

            var $form = $('[data-form="shop-cart"]');
            var is_direct = $(this).attr('data-direct');

            if($form.length === 0) return;

            var prd_idx = [];
            $form.find('[name="prd_idx[]"]').each(function() {
                prd_idx.push( $(this).val() );
            });

            if(prd_idx.length === 0) {
                alert('장바구니에서 삭제할 상품을 선택해주세요');
                return;
            }

            if(!confirm(`장바구니를 완전히 비우시겠습니까?`)) return;


            $.ajax({
                url: base_url + '/ajax/shop/cart_all',
                type: 'DELETE',
                data: {
                    is_direct : is_direct
                },
                success: function() {
                    location.reload();
                }
            })
        })
    }

    /**
     * 장바구니 폼의 구매하기 버튼 클릭시 처리
     */
    if($('[data-form="shop-cart"]').length >0) {
        $('[data-form="shop-cart"]').on('submit', function(e) {
            var $form = $('[data-form="shop-cart"]');
            var is_direct = $(this).attr('data-direct');

            if($form.length === 0) return;

            var prd_idx = [];
            $form.find('[name="prd_idx[]"]').each(function() {
                if( $(this).prop('checked') ) {
                    prd_idx.push( $(this).val() );
                }
            });

            if(prd_idx.length === 0) {
                alert('구매할 상품을 선택하세요');
                e.preventDefault();
                return;
            }

            return true;
        });
    }

    /**
     * 장바구니 수량변경 페이지 닫기
     */
    $(document).on('click', '[data-button="modify-cart-close"]', function(e) {
        e.preventDefault();
        e.stopPropagation();

        $('[data-container="cart-modify-form"]').empty();
    });

    /**
     * 장바구니 수량 +,- 버튼 클릭시
     */
    $(document).on('click', '[data-button="cart-modify-minus"], [data-button="cart-modify-plus"]', function(e) {
        e.preventDefault();

        var target = $(this).attr('data-target');
        if( $(target).length === 0 ) return;
        var currentValue = $(target).val() * 1

        var type = $(this).attr('data-button') === 'cart-modify-minus' ? 'minus' : 'plus';

        if(type === 'minus') {
            if(currentValue > 1) {
                $(target).val( currentValue -1 );
            }
        }
        else if (type === 'plus') {
            $(target).val( currentValue + 1 );
        }
    })

    /**
     * 장바구니 수량변경 확인 버튼 클릭시
     */
    $(document).on('submit', '[data-form="shop-cart-modify"]', function(e) {
        e.preventDefault();

        var $form = $('[data-form="shop-cart-modify"]');

        $.ajax({
            url: base_url + '/ajax/shop/cart',
            type: 'PUT',
            data: $form.serialize(),
            success: function() {
                location.reload();
            }
        })
    })

    /**
     * 장바구니 담기 버튼 이벤트
     */
    $(document).on('submit', '[data-form="product-cart"]', function(e) {
        e.preventDefault();

        var $frm = $(this);
        var $sel = $frm.find('select[data-product-cart]');
        var it_name = $frm.find("input[name^=prd_name]").val();
        var it_price = parseInt($frm.find("input[name^=prd_price]").val());
        var id = "";
        var value, info, sel_opt, item, price, stock, run_error = false;
        var option = sep = "";
        var count = $sel.length;

        if(count >0) {
            $sel.each(function(index) {
                value = $(this).val();

                item = $(this).data('label');
                var _price = $(this).find('option:selected').data('price');
                var _stock = $(this).find('option:selected').data('stock');
                var _name = $(this).find('option:selected').data('name');
                var _code = $(this).find('option:selected').val();

                // 필수선택옵션을 선택안한경우 중단.
                if(! value) {
                    run_error = true;
                    return false;
                }

                id = _code;

                if(option !== "") {
                    option += " / ";
                }
                option += item + ":" + _name;

                price = _price;
                stock = _stock;
            })

            if(run_error) {
                alert(it_name + '의 ' + item + '을(를) 선택해주십시오');
                return false;
            }
        }
        else {
            price = 0;
            stock = $frm.find("input[name^=prd_stock]").val();
            option = it_name
        }

        // 금액 음수체크
        if(it_price + parseInt(price) < 0) {
            alert("구매금액이 음수인 상품은 구매할 수 없습니다.");
            APP.SHOP.closeQuickCart();
            return false;
        }

        // 옵션 선택정보 적용
        $frm.find("input[name^=opt_code]").val(id);
        $frm.find("input[name^=opt_value]").val(option);
        $frm.find("input[name^=opt_price]").val(price);

        $.ajax({
            url: base_url + '/ajax/shop/cart',
            type: "POST",
            data: $frm.serialize(),
            async: true,
            cache: false,
            success: function() {
                APP.SHOP.updateCartCallback();
                APP.SHOP.closeQuickCart();
            }
        })
    })
});

/**
 * 주문서 페이지
 */
$(function(){

    if($('[data-button="search-zonecode"]').length > 0 ){
        $('[data-button="search-zonecode"]').on('click', function(e) {
            e.preventDefault();

            APP.SHOP.searchZonecode();
        })
    }

    /**
     * 구매하기 페이지
     */
    if($('[data-form="shop-order"]').length> 0) {

        var imp_code = $(this).find('[name="imp_code"]').val();

        if(imp_code.length === 0) {
            alert('포트원 결제모듈 정보가 입력되지 않았습니다.\\n관리자모드에서 포트원 결제 설정을 완료해주세요');
            history.back(-1);
            return;
        }

        $('[data-form="shop-order"]').on('submit', function(e) {
            e.preventDefault();

            // 필수입력항목 검사
            var od_name = $('[data-form="shop-order"]').find('[name="od_name"]').val(),
                od_phone = $('[data-form="shop-order"]').find('[name="od_hp"]').val(),
                od_zonecode = $('[data-form="shop-order"]').find('[name="od_zonecode"]').val(),
                od_addr1 = $('[data-form="shop-order"]').find('[name="od_addr1"]').val();

            if(od_name.trim().length === 0) {
                alert('주문자 성명을 입력해주세요');
                $(this).find('[name="od_name"]').focus();
                return;
            }

            if(od_phone.trim().length === 0) {
                alert('주문자 핸드폰번호를 입력해주세요');
                $(this).find('[name="od_hp"]').focus();
                return;
            }

            if(od_zonecode.trim().length === 0) {
                alert('우편번호를 입력해주세요');
                $(this).find('[name="od_zonecode"]').focus();
                return;
            }

            if(od_addr1.trim().length === 0) {
                alert('주소를 입력해주세요');
                $(this).find('[name="od_addr1"]').focus();
                return;
            }

            // 결제 시작전 임시주문서를 입력한다.
            var formData = $(this).serialize()

            let payment_data = null;
            $.ajax({
                url: base_url +'/ajax/shop/payment-prepare',
                type: 'POST',
                data: formData,
                cache:false,
                async: false,
                success: function(res) {
                    payment_data = res;
                }
            })

            // 받아온 데이타가 없다면 종료
            if(payment_data === null) {
                return;
            }

            // 만약 결제방식이 무통장입금이라면 바로 결제 완료 처리로 넘어간다.
            if(payment_data.pay_method === 'bank') {
                $.ajax({
                    url: base_url + '/ajax/shop/payment-verify',
                    type: 'GET',
                    data: payment_data,
                    async: false,
                    cache: false,
                    success:function(res) {
                        location.href= base_url + '/shop/order-complete'
                    }
                });
                return;
            }

            // 모바일용 redirect_url 설정
            payment_data.m_redirect_url = base_url + '/ajax/shop/payment-verify/mobile'

            // 결제 처리
            IMP.request_pay(payment_data, function(rsp) {
                if(!rsp.success ) {
                    alert(rsp.error_msg);
                    return;
                }
                payment_data.imp_uid = rsp.imp_uid
                payment_data.is_direct = $('[name="is_direct"]').val() === 'Y' ? 'Y' : 'N';

                $.ajax({
                    url: base_url + '/ajax/shop/payment-verify',
                    type: 'GET',
                    data: payment_data,
                    async: false,
                    cache: false,
                    success:function(res) {
                        location.href= base_url + '/shop/order-complete'
                    }
                })
            });
        })
    }
})