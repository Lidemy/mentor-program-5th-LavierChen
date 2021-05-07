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

//判斷迴文
function solve(lines) {
    let reverseString = ''
    let front = 0
    let rear = lines[0].length-1

    for(let i = rear; i >= front; i--) {
        reverseString = reverseString + lines[0][i]
    }

    console.log(lines[0] === reverseString ? 'True' : 'False')
}