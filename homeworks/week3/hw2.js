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

//水仙花數
function solve(lines) {
    const [minNum, maxNum] = lines[0].split(' ').map(Number)
    for (let i = minNum; i <= maxNum; i++) {
        if (isNarcissistic(i)) {
            console.log(i)
        }
    }
}
  
function digitsCount(n) {
    if (n === 0) return 1   //corner case
    let digits = 0
    while (n !== 0) {       //count how many digits
        n = Math.floor(n / 10)
        digits += 1
    }
    return digits
}
  
function isNarcissistic(n) {
    let number = n
    const digits = digitsCount(number)
    let sum = 0
    while (number != 0) {
        sum += Math.pow(number % 10, digits)    //least significant digit to the power of digits
        number = Math.floor(number / 10)        //next least significant digit
    }
    return sum === n
}