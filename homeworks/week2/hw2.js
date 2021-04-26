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

// JavaScript 內建函式的解法：使用函式 toUpperCase()，就不用檢查第一個字母是不是大小寫，因為即使是大寫，轉換之後也還是大寫
function capitalize_2(str) {
    return str[0].toUpperCase() + str.slice(1);     
}

console.log(capitalize('hello'));
console.log(capitalize_2('hello'));
