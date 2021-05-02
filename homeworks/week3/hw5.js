var readline = require('readline');

var rl = readline.createInterface({
    input: process.stdin
})

var lines = []

rl.on('line', function (line) {
  lines.push(line)
})

rl.on('close', function() {
  solve(lines)
})

//聯誼順序比大小
function solve(lines) {
    let set = Number(lines[0])      //共有幾筆測試資料
    for(let i = 1; i <= set; i++) {
        const [a, b, k] = lines[i].split(' ')
        console.log(compare(a, b, k))
    }
}

function compare(a, b, k) {
    if(a === b) {
        return "DRAW"
    }

    if(Number(k) === -1) {      //若 p 為負數，代表數字「小」獲勝
        let temp = a            //將變數內的資料交換，即可達成數字「小」獲勝的條件
        a = b                   //邏輯較不直覺，須多注意
        b = temp
    }

    const aLength = a.length
    const bLength = b.length

    if (aLength != bLength) {
        return aLength > bLength ? "A" : "B"
    }

    for (let p = 0; p < aLength; p++) {
        if (a[p] == b[p]) {
            continue;
        }
        return a[p] > b[p] ? "A" : "B"
    }
}