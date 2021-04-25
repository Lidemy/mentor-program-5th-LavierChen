function capitalize(str) {
    var result = ''
    if(str[0] >= 'a' && str[0] <= 'z') {
        result = String.fromCharCode(str.charCodeAt(0) - 32)    //若第一個字母為小寫，利用 ASCII 轉換成大寫
        for (var i = 1; i < str.length; i++) {
            result = result + str[i]    //第一個字母以外的原字串
        }
    } 
    else {
        result = str    //第一個字不是英文字母則忽略
    }
    return result
}

console.log(capitalize('hello'));
