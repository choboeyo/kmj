/**********************************************************************************************************************
 * 숫자에 컴마를 붙여서 리턴한다
 * @returns {*}
 *********************************************************************************************************************/
Number.prototype.numberFormat = function(){
    if(this==0) return 0;

    var reg = /(^[+-]?\d+)(\d{3})/;
    var n = (this + '');

    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');

    return n;
};
String.prototype.numberFormat = function() { return isNaN( parseFloat(this) ) ? "0" :  (parseFloat(this)).numberFormat(); };

/**********************************************************************************************************************
 * 컴마가 붙어있는 숫자에서 콤마를 삭제하고 숫자로 반환한다.
 * @returns {*}
 *********************************************************************************************************************/
String.prototype.unNumberFormat = function() {
    var str = this;
    if(typeof str == 'number') return str;
    str = ("" + str).replace(/,/gi,''); // 콤마 제거
    str = str.replace(/(^\s*)|(\s*$)/g, ""); // trim

    var returnStr = new Number(str);
    return isNaN(returnStr) ? str : returnStr;
};
Number.prototype.unNumberFormat = function() {
    return this;
};

/**********************************************************************************************************************
 * 날짜를 원하는 포맷 형식으로 출력
 * @param f
 * @returns {*}
 *********************************************************************************************************************/
Date.prototype.dateFormat = function(f) {
    if (!this.valueOf()) return " ";
    if (!f) return this;

    var weekName = ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"],
        shortWeekName = ["일", "월", "화", "수", "목", "금", "토"],
        d = this;

    return f.replace(/(yyyy|yy|MM|dd|E|hh|mm|ss|a\/p)/gi, function($1) {
        switch ($1) {
            case "yyyy": return d.getFullYear();
            case "yy": return (d.getFullYear() % 1000).zf(2);
            case "MM": return (d.getMonth() + 1).zf(2);
            case "dd": return d.getDate().zf(2);
            case "E": return weekName[d.getDay()];
            case "e": return shortWeekName[d.getDay()];
            case "HH": return d.getHours().zf(2);
            case "hh": return ((h = d.getHours() % 12) ? h : 12).zf(2);
            case "mm": return d.getMinutes().zf(2);
            case "ss": return d.getSeconds().zf(2);
            case "a/p": return d.getHours() < 12 ? "오전" : "오후";
            default: return $1;
        }
    });
};
String.prototype.string = function(len){var s = '', i = 0; while (i++ < len) { s += this; } return s;};
String.prototype.zf = function(len){return "0".string(len - this.length) + this;};
Number.prototype.zf = function(len){return this.toString().zf(len);};
String.prototype.dateFormat = function(f) {
    var d = new Date(this);
    return ( d == 'Invalid Date') ? '' : d.dateFormat(f);
}

/**********************************************************************************************************************
 * 숫자를 한글명으로 바꿔서 보여줍니다.
 *********************************************************************************************************************/
Number.prototype.toKorean = function() {
    var hanA = new Array("","일","이","삼","사","오","육","칠","팔","구","십"),
        danA = new Array("","십","백","천","","십","백","천","","십","백","천","","십","백","천"),
        num = new String(this),
        result = '';

    for(var i=0; i<num.length; i++) {
        var str = "",
            han = hanA[num.charAt(num.length-(i+1))];

        if(han != "") str += han+danA[i];

        if(i == 4) str += "만";
        if(i == 8) str += "억";
        if(i == 12) str += "조";

        result = str + result;
    }

    return result;
}
String.prototype.toKorean = function() {
    return (this.unNumberFormat()).toKorean();
}