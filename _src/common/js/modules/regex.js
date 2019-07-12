/************************************************************************************************************************
 * 해당 문자열의 regex 검사
 * @param regexType
 *************************************************************************************************************************/
String.prototype.regex = function(regexType) {
    var phoneRegex = /^(01[016789]{1}|02|0[3-9]{1}[0-9]{1})([0-9]{3,4})([0-9]{4})$/,
        phoneWithHypenRegex = /^(01[016789]{1}|02|0[3-9]{1}[0-9]{1})-?([0-9]{3,4})-?([0-9]{4})$/,
        telRegex = /(^02.{0}|^01.{1}|[0-9]{3})([0-9]{3,4})([0-9]{4})/,
        telCheckRegex = /^\d{2,3}-\d{3,4}-\d{4}$/,
        uniqueID = /^[a-z][a-z0-9_]{2,19}$/g,
        emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
        str = this;

    switch(regexType) {
        case "phone" :
            var transNum = str.replace(/\s/gi, '').replace(/-/gi,'');
            if(transNum.length == 11 || transNum.length == 10) {
                if( phoneRegex.test(transNum) ) {
                    transNum = transNum.replace(phoneWithHypenRegex, '$1-$2-$3');
                    return transNum;
                }
            }
            return false;
        case "tel":
            var transNum = str.replace(/\s/gi, '').replace(/-/gi,'');
            transNum = transNum.replace(telRegex, '$1-$2-$3');
            if(telCheckRegex.test(transNum)) {
                return transNum;
            }
            return false;
        case "email":
            return emailRegex.test(str);
        case "biznum" :
            var checkID = new Array(1, 3, 7, 1, 3, 7, 1, 3, 5, 1),
                tmpBizID, i, chkSum=0, c2, remander,
                bizID = str.replace(/-/gi,'');

            for (i=0; i<=7; i++) chkSum += checkID[i] * bizID.charAt(i);
            c2 = "0" + (checkID[8] * bizID.charAt(8));
            c2 = c2.substring(c2.length - 2, c2.length);
            chkSum += Math.floor(c2.charAt(0)) + Math.floor(c2.charAt(1));
            remander = (10 - (chkSum % 10)) % 10 ;

            if (Math.floor(bizID.charAt(9)) == remander) return bizID.replace(/(\d{3})(\d{2})(\d{5})/, '$1-$2-$3');
            return false;
        case "uniqid" :
            return uniqueID.test(str);
    }
};