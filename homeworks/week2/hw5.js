function join(arr, concatStr) {
    if(arr.length === 0){
        return ''            //考慮字串為空時的特別處理，若沒有額外處理，join('', '')會回傳 undefined
    }
    var result = arr[0]     //陣列的每個元素中間插入一個字串，因此最後一個元素後面不能插入 concatStr
    for(var i = 1; i < arr.length; i++) {
        result = result+ concatStr + arr[i]
    }    
    return result
}

function repeat(str, times) {
    var result = ''
    for(var i = 1; i <= times; i++) {
        result = result + str
    }
    return result
}

console.log(join(['a'], '!'));      //陣列裡只有一個元素，故不能插入 concatStr
console.log(repeat('a', 5));
console.log(join('', ''));          //空字串的特別處理