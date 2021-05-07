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

function compare(num1, num2, BorS) {
    if(num1 === num2) {
        return "DRAW"
    }

    if(Number(BorS) === -1) {      //若 p 為負數，代表數字「小」獲勝
        let temp = num1
        num1 = num2
        num2 = temp
    }

    const num1_length = num1.length
    const num2_length = num2.length

    if (num1_length != num2_length) {
        return num1_length > num2_length ? "A" : "B"
    }

    for (let p = 0; p < num1_length; p++) {
        if (num1[p] == num2[p]) {
            continue;
        }
        return num1[p] > num2[p] ? "A" : "B"
    }
}