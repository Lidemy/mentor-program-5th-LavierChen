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

//判斷質數
function solve(lines) {
    for(let i = 1; i < lines.length; i++) {
        console.log(isPrime(Number(lines[i])) ? 'Prime' : 'Composite')
    }
}

function isPrime(n) {
    if (n === 1) return false;  //corner case, 1 is not prime
    const square = Math.sqrt(n);    //Every composite number has a proper factor less than or equal to its square root.
    for (let i = 2; i <= square; i++) {
        if (n % i === 0) {
            return false;
        }
    }
    return true;
}