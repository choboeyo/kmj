<div class="page-header">
    <h1 class="page-title">상품 정보 입력</h1>
</div>
<div class="container" id="product-form">
    <form @submit.prevent="onSubmit">
    <div data-ax-tbl>
        <div class="caption">상품 기본 정보</div>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>상품코드</div>
                <div data-ax-td-wrap>
                    <input class="form-control" :value="product_id" readonly>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="W600">
                <div data-ax-td-label>상품 분류 <span class="required">(필수입력)</span></div>
                <div data-ax-td-wrap>
                    <select ref="form_cat_id" class="form-control" v-model.number="formData.cat_id">
                        <option :value="0">상품 분류 선택</option>
                        <template v-for="depth1 in categoryList">
                            <option :value="depth1.cat_id" :key="`category-${depth1.cat_id}`">{{depth1.cat_title}}</option>
                            <template v-for="depth2 in depth1.children">
                                <option :value="depth2.cat_id" :key="`category-${depth2.cat_id}`">{{depth1.cat_title}} ▶ {{depth2.cat_title}}</option>
                                <template v-for="depth3 in depth2.children">
                                    <option :value="depth3.cat_id" :key="`category-${depth3.cat_id}`">{{depth1.cat_title}} ▶ {{depth2.cat_title}} ▶ {{depth3.cat_title}}</option>
                                </template>
                            </template>
                        </template>
                    </select>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>상품명 <span class="required">(필수입력)</span></div>
                <div data-ax-td-wrap>
                    <input class="form-control" ref="form_prd_name" v-model.trim="formData.prd_name" required maxlength="255">
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>기본 설명</div>
                <div data-ax-td-wrap>
                    <input class="form-control" name="prd_summary" v-model.trim="formData.prd_summary" maxlength="255">
                    <p class="help-block">상품에 대한 간략한 설명을 입력하세요.</p>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>표시상태</div>
                <div data-ax-td-wrap>
                    <div>
                        <label class="toggle-box">
                            <input type="radio" name="prd_status" value="Y" v-model="formData.prd_status">
                            <span class="checkbox-label">표시중</span>
                        </label>
                        <label class="toggle-box">
                            <input type="radio" name="prd_status" value="H" v-model="formData.prd_status">
                            <span class="checkbox-label">감춤</span>
                        </label>
                    </div>
                    <p class="help-block">상품의 표시상태를 설정합니다. [감춤]으로 설정할 경우 사용자의 페이지에서는 노출되지 않습니다.</p>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>판매상태</div>
                <div data-ax-td-wrap>
                    <div>
                        <label class="toggle-box">
                            <input type="radio" name="prd_sell_status" value="Y" v-model="formData.prd_sell_status">
                            <span class="checkbox-label">판매중</span>
                        </label>
                        <label class="toggle-box">
                            <input type="radio" name="prd_sell_status" value="O" v-model="formData.prd_sell_status">
                            <span class="checkbox-label">품절</span>
                        </label>
                        <label class="toggle-box">
                            <input type="radio" name="prd_sell_status" value="D" v-model="formData.prd_sell_status">
                            <span class="checkbox-label">일시판매중단</span>
                        </label>
                    </div>
                    <p class="help-block">상품의 판매상태를 설정합니다.판매를 통하여 재고가 0이되는 순간 품절로 변경됩니다.</p>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>라벨 표시</div>
                <div data-ax-td-wrap>
                    <label class="w-check">
                        <input type="checkbox" true-value="Y" false-value="N" v-model="formData.prd_is_best">
                        <span>BEST</span>
                    </label>

                    <label class="w-check">
                        <input type="checkbox" true-value="Y" false-value="N" v-model="formData.prd_is_hit">
                        <span>HIT</span>
                    </label>

                    <label class="w-check">
                        <input type="checkbox" true-value="Y" false-value="N" v-model="formData.prd_is_new">
                        <span>NEW</span>
                    </label>

                    <label class="w-check">
                        <input type="checkbox" true-value="Y" false-value="N" v-model="formData.prd_is_recommend">
                        <span>MD추천</span>
                    </label>

                    <label class="w-check">
                        <input type="checkbox" true-value="Y" false-value="N" v-model="formData.prd_is_sale">
                        <span>할인</span>
                    </label>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="W600">
                <div data-ax-td-label>제조원</div>
                <div data-ax-td-wrap>
                    <input class="form-control" v-model.trim="formData.prd_maker" maxlength="255">
                </div>
            </div>
            <div data-ax-td class="W600">
                <div data-ax-td-label>원산지</div>
                <div data-ax-td-wrap>
                    <input class="form-control" v-model.trim="formData.prd_origin" maxlength="255">
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="W600">
                <div data-ax-td-label>브랜드</div>
                <div data-ax-td-wrap>
                    <input class="form-control" v-model.trim="formData.prd_brand" maxlength="255">
                </div>
            </div>
            <div data-ax-td class="W600">
                <div data-ax-td-label>모델명</div>
                <div data-ax-td-wrap>
                    <input class="form-control" v-model.trim="formData.prd_model" maxlength="255">
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td>
                <div data-ax-td-label>상품 판매가</div>
                <div data-ax-td-wrap>
                    <div>
                        <input type="text" class="form-control text-right" ref="form_prd_price" name="prd_price" data-number-only v-model.number="formData.prd_price">
                    </div>
                </div>
            </div>
            <div data-ax-td>
                <div data-ax-td-label>상품 시중가</div>
                <div data-ax-td-wrap>
                    <div>
                        <input type="text" class="form-control text-right" name="prd_cust_price" data-number-only v-model.number="formData.prd_cust_price">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div data-ax-tbl class="MT10">
        <div class="caption">재고 관련 설정</div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>현재 재고</div>
                <div data-ax-td-wrap>
                    <input type="text" class="form-control text-right W100" name="prd_stock_qty" data-number-only v-model.number="formData.prd_stock_qty">
                    <p class="help-block">주문관리에서 상품별 상태 변경에 따라 자동으로 재고를 가감합니다. 재고가 가감되어 0이되면 자동으로 품절로 변경됩니다.</p>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>적정재고수량</div>
                <div data-ax-td-wrap>
                    <input type="text" class="form-control text-right W100" name="prd_noti_qty" data-number-only v-model.number="formData.prd_noti_qty">
                    <p class="help-block">상품의 재고가 적정재고수량보다 작을 때 재고현황에 재고부족 상품으로 표시됩니다.</p>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>최소구매수량</div>
                <div data-ax-td-wrap>
                    <input type="text" class="form-control text-right W100" name="prd_buy_min_qty" data-number-only v-model.number="formData.prd_buy_min_qty">
                    <p class="help-block">상품 구매시 최소 구매 수량을 설정합니다. 0으로 설정시 최소구매 수량을 적용하지 않습니다.</p>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>최대구매수량</div>
                <div data-ax-td-wrap>
                    <input type="text" class="form-control text-right W100" name="prd_buy_max_qty" data-number-only v-model.number="formData.prd_buy_max_qty">
                    <p class="help-block">상품 구매시 최대 구매 수량을 설정합니다. 0으로 설정시 최대구매 수량을 적용하지 않습니다.</p>
                </div>
            </div>
        </div>
    </div>

    <div data-ax-tbl class="MT10">
        <div class="caption">판매 옵션 설정</div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>필수선택옵션</div>
                <div data-ax-td-wrap>
                    <label class="toggle-box">
                        <input type="radio" name="prd_use_options" value="Y" v-model="formData.prd_use_options">
                        <span>필수 선택옵션 사용</span>
                    </label>
                    <label class="toggle-box" style="margin-left:0;">
                        <input type="radio" name="prd_use_options" value="N" v-model="formData.prd_use_options">
                        <span>필수 선택옵션 사용 안함</span>
                    </label>
                </div>
            </div>
        </div>
        <div data-ax-tr v-if="formData.prd_use_options==='Y'">
            <div data-ax-td class="width-100">
                <div data-ax-td-label>필수선택옵션</div>
                <div data-ax-td-wrap>
                    <p class="help-block">필수옵션 항목들을 입력하고 옵션 반영하기를 입력하면, 자동으로 옵션 조합이 세팅됩니다.</p>
                    <div class="H10"></div>
                    <div class="grid">
                        <table>
                            <thead>
                            <tr>
                                <th class="W150">옵션명</th>
                                <th>옵션상세</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="i in [0,1,2]">
                                <tr>
                                    <td>
                                        <input type="text" v-model="formData.prd_item_options[i].title" class="form-control">
                                    </td>
                                    <td>
                                        <ul class="options-list">
                                            <template v-for="(item,index) in formData.prd_item_options[i].items">
                                                <li class="options-row" :key="`option-0-${index}`">
                                                    <input type="text" class="form-control" v-model="formData.prd_item_options[i].items[index]">
                                                    <button type="button" class="btn btn-danger btn-sm" @click="formData.prd_item_options[i].items.splice(index,1)"><i class="fas fa-trash"></i></button>
                                                </li>
                                            </template>
                                        </ul>
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-default" @click="formData.prd_item_options[i].items.push('')"><i class="fas fa-plus"></i> 옵션 항목 추가</button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>

                    </div>
                    <div class="H10"></div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" @click="getOptionsGenerate">옵션 반영하기</button>
                    </div>
                    <div class="H10"></div>
                    <div class="grid">
                        <table>
                            <thead>
                            <tr>
                                <th>옵션</th>
                                <th>추가금액</th>
                                <th>재고수량</th>
                                <th>통보수량</th>
                                <th>사용여부</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(item, index) in formData.options">
                                <tr :key="`item-options-detail-${index}`">
                                    <td>{{item.opt_name.join(' / ')}}</td>
                                    <td><input type="number" class="form-control text-right" v-model.number="item.opt_add_price" :readonly="item.opt_staus==='N'"></td>
                                    <td><input type="number" class="form-control text-right" v-model.number="item.opt_stock_qty" :readonly="item.opt_staus==='N'"></td>
                                    <td><input type="number" class="form-control text-right" v-model.number="item.opt_noti_qty" :readonly="item.opt_staus==='N'"></td>
                                    <td>
                                        <select class="form-control" v-model="item.opt_status">
                                            <option value="Y">사용</option>
                                            <option value="N">미사용</option>
                                        </select>
                                    </td>
                                </tr>
                            </template>
                            <template v-if="formData.options.length === 0">
                                <tr>
                                    <td colspan="5" class="empty">생성된 필수선택 옵션이 없습니다.</td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>추가 옵션</div>
                <div data-ax-td-wrap>
                    <p class="help-block">사용여부를 [미사용]으로 설정하고 저장하면 삭제처리 됩니다.</p>
                    <div class="H10"></div>
                    <div class="grid">
                        <table>
                            <thead>
                            <tr>
                                <th>옵션</th>
                                <th>추가금액</th>
                                <th>재고수량</th>
                                <th>통보수량</th>
                                <th>사용여부</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="(item, index) in formData.options2">
                                <tr :key="`item-options-detail-${index}`">
                                    <td><input type="text" class="form-control" v-model.trim="item.opt_code"></td>
                                    <td><input type="number" class="form-control text-right" v-model.number="item.opt_add_price" :readonly="item.opt_staus==='N'"></td>
                                    <td><input type="number" class="form-control text-right" v-model.number="item.opt_stock_qty" :readonly="item.opt_staus==='N'"></td>
                                    <td><input type="number" class="form-control text-right" v-model.number="item.opt_noti_qty" :readonly="item.opt_staus==='N'"></td>
                                    <td>
                                        <select class="form-control" v-model="item.opt_status">
                                            <option value="Y">사용</option>
                                            <option value="N">미사용</option>
                                        </select>
                                    </td>
                                </tr>
                            </template>
                            <template v-if="formData.options2.length === 0">
                                <tr>
                                    <td colspan="5" class="empty">생성된 추가옵션 없습니다.</td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="H10"></div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" @click="addExtraOption">추가옵션 추가하기</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div data-ax-tbl class="MT10">
        <div class="caption">
            상품요약정보
            <p class="help-block">전자상거래 등에서의 상품 등의 정보제공 관한 고시에 따라 총 35개 상품군에 대해 상품 특성 등을 양식에 따라 입력할 수 있습니다.</p>
        </div>
        <div data-ax-tr>
            <div data-ax-td class="W500">
                <div data-ax-td-label>상품군</div>
                <div data-ax-td-wrap>
                    <select class="form-control" name="prd_item_group" v-model="formData.prd_item_group">
                        <template v-for="(row,key) in itemGroupList">
                            <option :value="key">{{row.title}}</option>
                        </template>

                    </select>
                </div>
            </div>
        </div>
        <template v-for="(row,key) in itemGroupList">
            <template v-if="key===formData.prd_item_group">
                <template v-for="(row2, key2) in row.items">
                    <div data-ax-tr>
                        <div data-ax-td class="width-100">
                            <div data-ax-td-label>{{row2.key}}</div>
                            <div data-ax-td-wrap>
                                <input v-model.trim="formData.prd_extra_info[key2]" class="form-control">
                            </div>
                        </div>
                    </div>
                </template>
            </template>
        </template>

    </div>

    <div data-ax-tbl class="MT10">
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>상품 이미지 등록</div>
                <div data-ax-td-wrap>
                    <label class="upload-zone" for="product-image-input" :class="{'drag-over':fileDrop.isDragged}" @dragenter="onDragEnter" @dragover="onDragOver" @dragLeave="onDragLeave" @drop="onDrop">
                        여기를 클릭하거나, 이곳에 파일을 끌어다 이미지파일을 업로드 하세요
                    </label>
                    <input type="file" id="product-image-input" ref="fileInput" name="userfile" style="display:none" accept="image/*" @change="onFileChange" multiple>
                </div>
            </div>
        </div>
        <div data-ax-tr v-if="formData.images.length>0">
            <div data-ax-td class="width-100">
                <div data-ax-td-label>업로드된<br>이미지</div>
                <div data-ax-td-wrap>
                    <draggable v-model="formData.images" class="image-list" @change="onImageSortChanged" handle=".handle">
                        <li v-for="(item,index) in formData.images" :key="`product-image-${index}`">
                            <div class="image-item">
                                <figure class="figure">
                                    <img :src="`<?=base_url()?>${item.att_filepath}`">
                                </figure>
                                <label class="w-check">
                                    <input type="radio" name="prd_thumbnail" :value="item.att_idx" v-model.number="formData.prd_thumbnail">
                                    <span>대표썸네일로 지정</span>
                                </label>
                                <div class="actions">
                                    <button type="button" class="handle btn btn-default btn-xs">
                                        <i class="fas fa-up-down-left-right"></i>
                                    </button>
                                    <div class="spacer"></div>
                                    <button type="button" class="btn btn-danger btn-xs" @click="deleteImage(item.att_idx)"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>

                        </li>
                    </draggable>
                </div>
            </div>
        </div>
    </div>

    <div data-ax-tbl class="MT10">
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>배송비 유형</div>
                <div data-ax-td-wrap>
                    <select class="form-control W200" v-model="formData.prd_sc_type">
                        <option value="" selected="selected">쇼핑몰 기본설정 사용</option>
                        <option value="무료">무료배송</option>
                        <option value="조건부무료">조건부 무료배송</option>
                        <option value="유료">유료배송</option>
                        <option value="수량별">수량별 부과</option>
                    </select>
                    <p class="help-block">환경설정 > 쇼핑몰 배송 설정 > 배송비유형 설정보다 개별상품 배송비설정이 우선 적용됩니다.</p>
                </div>
            </div>
        </div>
        <div data-ax-tr v-if="formData.prd_sc_type==='조건부무료'||formData.prd_sc_type==='유료'||formData.prd_sc_type==='수량별'">
            <div data-ax-td class="width-100">
                <div data-ax-td-label>배송비 결제</div>
                <div data-ax-td-wrap>
                    <select class="form-control W200" v-model="formData.prd_sc_method">
                        <option value="선불">선불</option>
                        <option value="착불">착불</option>
                        <option value="사용자선택">사용자 선택</option>
                    </select>
                </div>
            </div>
        </div>
        <div data-ax-tr v-if="formData.prd_sc_type==='조건부무료'||formData.prd_sc_type==='유료'||formData.prd_sc_type==='수량별'">
            <div data-ax-td class="width-100">
                <div data-ax-td-label>기본 배송비</div>
                <div data-ax-td-wrap>
                    <input class="form-control W200 text-right" v-model.number="formData.prd_sc_price" data-number-only>
                </div>
            </div>
        </div>
        <div data-ax-tr v-if="formData.prd_sc_type==='조건부무료'">
            <div data-ax-td class="width-100">
                <div data-ax-td-label>상세조건</div>
                <div data-ax-td-wrap>
                    <div style="display:flex; align-items: center;">
                        주문금액
                        <input class="form-control W100 text-right ML5 MR5" v-model.number="formData.prd_sc_minimum" data-number-only>
                        이상 무료 배송
                    </div>
                </div>
            </div>
        </div>
        <div data-ax-tr v-if="formData.prd_sc_type==='수량별'">
            <div data-ax-td class="width-100">
                <div data-ax-td-label>상세조건</div>
                <div data-ax-td-wrap>
                    <div style="display:flex; align-items: center;">
                        주문수량
                        <input class="form-control W100 text-right ML5 MR5" v-model.number="formData.prd_sc_qty" data-number-only>
                        마다 배송비 부과
                    </div>
                    <p class="help-block">상품의 주문 수량에 따라 배송비가 부과됩니다.<br>예를 들어 기본배송비가 3,000원 수량을 3으로 설정했을 경우 상품의 주문수량이 5개이면 6,000원 배송비가 부과됩니다.</p>
                </div>
            </div>
        </div>
    </div>

    <div data-ax-tbl class="MT10">
        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>상세 설명</div>
                <div data-ax-td-wrap>
                    <?=get_editor('prd_content','', '',true,'ckeditor', 'v-model.trim="formData.prd_content"')?>
                </div>
            </div>
        </div>

        <div data-ax-tr>
            <div data-ax-td class="width-100">
                <div data-ax-td-label>모바일</div>
                <div data-ax-td-wrap>
                    <?=get_editor('prd_mobile_content','', '',true,'ckeditor', 'v-model.trim="formData.prd_mobile_content"')?>
                </div>
            </div>
        </div>
    </div>

    <div data-ax-tbl class="MT10">
        <div data-ax-tr v-for="index in [1,2,3,4,5,6,7,8,9,10]" :key="`prd-extra-row-${index}`">
            <div data-ax-td class="width-100">
                <div data-ax-td-label>추가입력필드 {{index}}</div>
                <div data-ax-td-wrap>
                    <input class="form-control" v-model="formData['prd_extra_' + index]">
                </div>
            </div>
        </div>
    </div>

    <div class="text-center MT10">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> 저장하기</button>
    </div>

    </form>
</div>
<style>
    .upload-zone {display:flex; width:100%; height:80px; justify-content: center; align-items: center; border:1px solid #ccc; }
    .upload-zone.drag-over { background:#f0f0f0;}
    .image-list { list-style:none; padding:0; margin:0 -.5rem; display:flex; flex-wrap:wrap; }
    .image-list li {display:flex; padding:.5rem; justify-content: center; align-items:center;}
    .image-list li .image-item {position:relative; padding:1rem;border:1px solid #ccc;}
    .image-list li .image-item .actions {position:absolute; top:0; left:0;width:100%; z-index:3; display:flex; padding:.25rem;}
    .image-list li .image-item .actions .handle {cursor:move;}
    .image-list li .image-item .actions .spacer {flex:1;}
    .image-list li .figure {}
    .image-list li .figure img {max-width:100%;max-height:100px;display:block;margin-left:auto; margin-right:auto;}
    .sortable-ghost {background-color:#f0f0f0;}
    .options-list {padding:0;margin:0;list-style:none;}
    .options-list .options-row {margin-bottom:10px; display:flex; align-items: center}
    .options-list .options-row .form-control {width:auto; flex:1;}
    .options-list .options-row .btn {flex-shrink: 0; margin-left:10px;}
</style>

<script>
const productItemForm = new Vue({
    el: '#product-form',
    data: function() {
        return {
            product_id: '<?=$product_id?>',
            isLoaded: false,
            formData: {
                cat_id:0,
                prd_name: '',
                prd_summary: '',
                prd_status : 'Y',
                prd_maker:'',
                prd_origin: '',
                prd_brand:'',
                prd_model: '',
                prd_sell_status: 'Y',
                prd_price: 0,
                prd_cust_price: 0,
                prd_use_options: 'N',
                prd_stock_qty: 9999,
                prd_noti_qty: 0,
                prd_buy_min_qty: 0,
                prd_buy_max_qty: 0,
                prd_item_group: 'wear',
                prd_content: '',
                prd_mobile_content: '',
                prd_sc_type: '',
                prd_sc_method:'',
                prd_sc_price:0,
                prd_sc_minimum:0,
                prd_sc_qty:0,
                prd_extra_info: {},
                prd_item_options: [
                    {title: '', items: []},
                    {title: '', items: []},
                    {title: '', items: []}
                ],
                prd_thumbnail: 0,
                prd_extra_1:'',
                prd_extra_2:'',
                prd_extra_3:'',
                prd_extra_4:'',
                prd_extra_5:'',
                prd_extra_6:'',
                prd_extra_7:'',
                prd_extra_8:'',
                prd_extra_9:'',
                prd_extra_10:'',
                prd_is_best: 'N',
                prd_is_hit: 'N',
                prd_is_new: 'N',
                prd_is_sale: 'N',
                prd_is_recommend: 'N',
                images: [],
                options: [],
                options2: []
            },
            categoryList: [],
            itemGroupList: [],
            fileDrop: {
                isDragged: false,
                fileList: [],
                isOpened: false
            }
        }
    },
    watch: {
      'formData.prd_item_group' () {
          if(! this.isLoaded) return;

          this.resetItemGroups();
      }
    },
    mounted () {
        this.$nextTick(() => {
            this.getCategoryList();
            this.getItemGroupList();
            this.getData();
        })
    },
    methods: {
        resetItemGroups () {
            console.log(111);
            this.formData.prd_extra_info = {};

            const t = this.itemGroupList[this.formData.prd_item_group];
            for(let key in t.items) {
                this.formData.prd_extra_info[key] = t.items[key].content
            }
        },
        getItemGroupList () {
            $.ajax({
                url: base_url + '/assets/js/shop_item_group.json',
                type: "GET",
                cache: false,
                async: false,
                success:function(res) {
                    productItemForm.itemGroupList = res;
                }
            });
        },
        getCategoryList () {
            $.ajax({
                url: base_url + '/ajax/products/categories',
                type: "GET",
                cache: false,
                async: false,
                success:function(res) {
                    productItemForm.categoryList = res;
                }
            });
        },
        getData () {
            $.ajax({
                url:base_url + '/ajax/products/items/' + this.product_id,
                type: 'GET',
                cache: false,
                async: false,
                success: function(res) {
                    for(let key in res) {
                        if(typeof productItemForm.formData[key] !== 'undefined') {
                            productItemForm.formData[key] = res[key];
                        }
                    }

                    if(productItemForm.formData.prd_status === 'T') {
                        productItemForm.formData.prd_status = 'Y'
                        productItemForm.formData.prd_item_group = 'wear'
                        productItemForm.formData.prd_stock_qty = 9999;
                        productItemForm.resetItemGroups();
                    }
                    productItemForm.isLoaded = true

                }
            })
        },
        getOptionsGenerate () {
          $.ajax({
              url: base_url + '/admin/ajax/products/options_generate',
              type: "GET",
              data: {
                  prd_idx: this.product_id,
                  prd_item_options: this.formData.prd_item_options
              },
              success: function(res) {
                  productItemForm.formData.options = res;
              }
          })
        },
        addExtraOption () {
          this.formData.options2.push({
              opt_code: '',
              opt_add_price: 0,
              opt_stock_qty: 9999,
              opt_noti_qty: 10,
              opt_status: 'Y'
          })
        },
        removeExtraOption(index) {
            console.log(index);
          this.formData.options2.splice(index, 1);
        },
        onSubmit() {
            // 상세설명 Wisywig 에디터의 내용을 가져오기
            this.formData.prd_content = window.CKEDITOR.instances["prd_content"].getData();
            this.formData.prd_mobile_content = window.CKEDITOR.instances["prd_mobile_content"].getData();

            if(this.formData.cat_id <= 0) {
                alert('상품 분류를 선택해주세요');
                this.$refs.form_cat_id.focus();
                return;
            }

            if(this.formData.prd_name.length === 0) {
                alert('상품명을 입력해주세요');
                this.$refs.form_prd_name.focus();
                return;
            }

            if(this.formData.prd_status !== 'Y' && this.formData.prd_status !== 'H') {
                alert('상품 표시상태를 선택해주세요');
                return;
            }

            if(this.formData.prd_sell_status !== 'Y' && this.formData.prd_sell_status !== 'O' && this.formData.prd_sell_status !== 'D')
            {
                alert('판매상태를 선택해주세요');
                return;
            }

            if(this.formData.prd_price < 0 ) {
                alert('상품 판매가는 음수를 입력할 수 없습니다');
                this.$refs.form_prd_price.focus();
                return;
            }

            if(this.formData.prd_price === 0) {
                if(! confirm('상품 판매가가 0원으로 설정되어 있습니다.\n계속 하시겠습니까?')) return;
            }

            if(this.formData.prd_use_options === 'Y' && this.formData.options.length === 0) {
                alert('상품 필수옵션을 사용할 경우, 상품 필수옵션을 설정해주셔야 합니다.');
                return;
            }

            const formData = this.formData;

            $.ajax({
                url: base_url + '/admin/ajax/products/item/' + this.product_id,
                type: "POST",
                data: formData,
                success: function() {
                    alert('상품 정보 입력이 완료되었습니다.');
                    location.href = base_url + '/admin/products/items';
                }
            });
        },
        onDragEnter () {
            this.fileDrop.isDragged = true;
        },
        onDragLeave () {
            this.fileDrop.isDragged = false;
        },
        onDragOver (e) {
            e.preventDefault()
        },
        async onDrop (e) {
            e.preventDefault()
            this.fileDrop.isDragged = false
            this.fileDrop.fileList = e.dataTransfer.files

            await this.uploadImage()
        },
        async onFileChange() {
            this.fileDrop.fileList = this.$refs.fileInput.files

            await this.uploadImage();
        },
        async uploadImage () {
            this.fileDrop.isOpened = true

            if(this.fileDrop.fileList.length <= 0) {
                return;
            }

            const formData = new FormData();
            let fileCount = 0;
            for(let i in this.fileDrop.fileList) {
                if(typeof this.fileDrop.fileList[i] === 'file' || typeof this.fileDrop.fileList[i] === 'object') {
                    formData.append("userfile[]", this.fileDrop.fileList[i])
                    fileCount++;
                }
            }
            formData.append('prd_idx', this.product_id)

            if(fileCount === 0) {
                return;
            }

            $.ajax({
                url: base_url + '/admin/ajax/products/images',
                type: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function(res) {
                    productItemForm.fileDrop.isDragged = false
                    productItemForm.fileDrop.isOpened = false;
                    productItemForm.fileDrop.fileList = [];

                    for(let i in res) {
                        productItemForm.formData.images.push(res[i]);
                    }

                    if(productItemForm.formData.prd_thumbnail * 1 === 0) {
                        productItemForm.formData.prd_thumbnail = productItemForm.formData.images[0].att_idx * 1
                    }
                }
            })
        },
        onImageSortChanged () {
            let ids = [];
            for(let i=0; i<this.formData.images.length; i++) {
                ids.push(this.formData.images[i].att_idx);
            }

            $.ajax({
                url: base_url + '/admin/ajax/management/sort',
                type: 'POST',
                data: {
                    key: 'att_idx',
                    table: 'attach',
                    sort: 'att_sort',
                    sort_order: ids
                },
                success: function() {
                    window.toastr.success('이미지 순서가 변경되었습니다');
                }
            })
        },
        deleteImage(id) {
            if(! confirm('해당 이미지를 삭제하시겠습니까?')) return;

            $.ajax({
                url: base_url+ '/admin/ajax/products/images',
                type: 'DELETE',
                data: {
                    id: id
                },
                async:false,
                cache:false,
                success: function() {
                    const t = productItemForm.formData.images.find(item => item.att_idx * 1 === id * 1)

                    if(t !== null && t !== undefined) {
                        const index = productItemForm.formData.images.indexOf(t);
                        productItemForm.formData.images.splice(index, 1)
                    }
                }
            })
        }
    }
})
</script>