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

//好多星星
function solve(lines) {
    var count = Number(lines[0])
    for(let i = 1; i <= count; i++) {
        let result = ''
        for(let j = 1; j <= i; j++) {
            result = result + '*'
        }
        console.log(result)
    }
}

/*
內建函式 repeat 解法
function solve(lines) {
    let n = Number(lines[0])
    for (let i = 1; i <= n; i++){
        console.log('*'.repeat(i))
    }
}
*/