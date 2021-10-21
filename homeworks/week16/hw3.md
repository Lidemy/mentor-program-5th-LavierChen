# hw3：Hoisting

請說明以下程式碼會輸出什麼，以及盡可能詳細地解釋原因。

``` js
var a = 1
function fn(){
  console.log(a)  // undefined
  var a = 5
  console.log(a)  // 5
  a++
  var a
  fn2()
  console.log(a)  // 20
  function fn2(){
    console.log(a)  // 6
    a = 20
    b = 100
  }
}
fn()
console.log(a)  // 1
a = 10
console.log(a)  // 10
console.log(b)  // 100
```

## 輸出結果

``` javascript
undefined
5
6
20
1
10
100
```

## 預備知識

### 執行環境（execution context）

執行環境（execution context）是指 JavaScript 引擎模組化直譯程式碼時的區塊環境。簡單來說，就像是在整個 JavaScript 執行時，引擎將程式碼拆解成許多區塊，將這些區塊一塊一塊疊起來，運算完結果就移除當前的區塊，然後繼續運算下一部分的區塊。

這些區塊內存放著執行環境階段（execution context phase）、變數物件（variable object）、作用域鍊（scope chain）等內容，使 JavaScript 引擎可以更方便的管理。

執行環境可以分為兩種：全域執行環境（global execution context）、函式執行環境（function execution context）。global execution context 指的是**一開始**執行 JavaScript 程式時所創立的執行環境，function execution context 指的是**執行函式時**所創立執行環境。

### 作用域鍊（scope chain）

作用域鍊（scope chain）主要的目的在於當我們在執行環境中的變數物件（variable object）找不到變數時，就會透過 scope chain 的機制來尋找。

一般 scope chain 初始化的內容會是自己本身的 variable object 加上 [[scope]]，我們可以理解成程式剛開始執行時，對於變數的處理，會先去找當前的 execution context 中的 variable object，找不到再來看有沒有在 [[scope]] 當中。

**[[scope]] 與 scopeChain 的差別**

[[scope]] 產生的時機為 function 被**宣告**的時候，每一個新的 function 被宣告的同時，就會建立一個屬於自己的 [[scope]] 屬性。初始值為 function 所處的 execution context 底下的 scopeChain。

scopeChain 產生的時機為 function 被**呼叫**的時候。初始值為 function 本身的 activation object 再加上本身的 [[scope]] 屬性。

## 執行流程

1-1. 建立 Global Execution Context

``` javascript
globalEC: {
  VO: {
   
  }, 
  scopeChain: [globalEC.VO]
}
```

scopeChain 的初始值為 function 本身的 variable object 再加上本身的 [[scope]] 屬性，但因為 global execution context 不是 function，所以它並沒有 [[scope]] 屬性，它的 scopeChain 裡面只有 globalEC.VO。

1-2. 初始化變數 a 和 函式 fn

``` javascript
globalEC: {
  VO: {
    a: undefined, 
    fn: function
  }, 
  scopeChain: [globalEC.VO]
}
```

1-3. 設定 fn 的 [[scope]] 屬性

``` javascript
globalEC: {
  VO: {
    a: undefined, 
    fn: function
  }, 
  scopeChain: [globalEC.VO]
}

fn.[[scope]] = globalEC.scopeChain 
```

---

Global Execution Context 建立完之後，開始執行 Global 程式碼。

``` javascript
var a = 1
fn()
console.log(a)
a = 10
console.log(a)
console.log(b)
```

2-1. `var a = 1`

``` javascript
globalEC: {
  VO: {
    a: 1, 
    fn: function
  }, 
  scopeChain: [globalEC.VO]
}

fn.[[scope]] = globalEC.scopeChain
```

2-2. `fn()`

呼叫函式 fn，globalEC 會為它建立一個全新的執行環境，提供 fn 裡面的程式碼執行，這個新的環境會被堆疊在原本的 globalEC 上面，這個堆疊的過程被稱為「執行堆疊（execution stack）」。

---

3-1. 建立 fn Execution Context

``` javascript
// second layer
fnEC: {
  AO: {

  },
  scopeChain: [fnEC.AO, fn.[[scope]]]  // [fnEC.AO, globalEC.VO]
}

// first layer
globalEC: {
  VO: {
    a: 1, 
    fn: function
  }, 
  scopeChain: [globalEC.VO]
}

fn.[[scope]] = globalEC.scopeChain
```

3-2. 初始化變數 a 和 函式 fn2

``` javascript
// second layer
fnEC: {
  AO: {
    a: undefined, 
    fn2: function
  },
  scopeChain: [fnEC.AO, fn.[[scope]]]  // [fnEC.AO, globalEC.VO]
}

// first layer
globalEC: {
  VO: {
    a: 1, 
    fn: function
  }, 
  scopeChain: [globalEC.VO]
}

fn.[[scope]] = globalEC.scopeChain
```

3-3. 設定 fn2 的 [[scope]] 屬性

``` javascript
// second layer
fnEC: {
  AO: {
    a: undefined, 
    fn2: function
  },
  scopeChain: [fnEC.AO, fn.[[scope]]]  // [fnEC.AO, globalEC.VO]
}

fn2.[[scope]] = fnEC.scopeChain

// first layer
globalEC: {
  VO: {
    a: 1, 
    fn: function
  }, 
  scopeChain: [globalEC.VO]
}

fn.[[scope]] = globalEC.scopeChain
```

---

fn Execution Context 建立完之後，開始執行 fn 程式碼。

``` javascript
function fn(){
  console.log(a)
  var a = 5
  console.log(a)
  a++
  var a
  fn2()
  console.log(a)
}
```

4-1. `console.log(a)`

對於變數的處理，會先去找當前的 execution context 中的 activation object，找不到再來看有沒有在 [[scope]] 當中。

``` javascript
fnEC: {
  AO: {
    a: undefined, 
    fn2: function
  },
  scopeChain: [fnEC.AO, fn.[[scope]]]  // [fnEC.AO, globalEC.VO]
}
```

因此，印出 a 的值為 undefined。

4-2. `var a = 5`

``` javascript
fnEC: {
  AO: {
    a: 5, 
    fn2: function
  },
  scopeChain: [fnEC.AO, fn.[[scope]]]  // [fnEC.AO, globalEC.VO]
}
```

4-3. `console.log(a)`

找到 fnEC.AO 的 a，印出 a 的值為 5。

4-4. `a++`

``` javascript
fnEC: {
  AO: {
    a: 6, 
    fn2: function
  },
  scopeChain: [fnEC.AO, fn.[[scope]]]  // [fnEC.AO, globalEC.VO]
}
```

4-5. `var a`

不影響變數 a，因為已經宣告過變數 a 了

4-6. `fn2()`

呼叫函式 fn2，fnEC 會為它建立一個全新的執行環境，提供 fn2 裡面的程式碼執行，這個新的環境會被堆疊在原本的 fnEC 上面。

---

5-1. 建立 fn2 Execution Context

``` javascript
// third layer
fn2EC: {
  AO: {

  }, 
  scopeChain: [fn2EC.AO, fn2.[[scope]]]  // [fn2EC.AO, fnEC.AO, globalEC.VO]
}

// second layer
fnEC: {
  AO: {
    a: 6, 
    fn2: function
  },
  scopeChain: [fnEC.AO, fn.[[scope]]]  // [fnEC.AO, globalEC.VO]
}

fn2.[[scope]] = fnEC.scopeChain

// first layer
globalEC: {
  VO: {
    a: 1, 
    fn: function
  }, 
  scopeChain: [globalEC.VO]
}

fn.[[scope]] = globalEC.scopeChain
```

---

由於 fn2 內部沒有宣告變數與函式，fn2 Execution Context 建立完畢，開始執行 fn2 程式碼。

``` javascript
function fn2() {
  console.log(a)
  a = 20
  b = 100
}
```

6-1. `console.log(a)`

對於變數的處理，會先去找當前的 execution context 中的 activation object，找不到再來看有沒有在 [[scope]] 當中。

``` javascript
// third layer
fn2EC: {
  AO: {

  }, 
  scopeChain: [fn2EC.AO, fn2.[[scope]]]  // [fn2EC.AO, fnEC.AO, globalEC.VO]
}

// second layer
fnEC: {
  AO: {
    a: 6, 
    fn2: function
  },
  scopeChain: [fnEC.AO, fn.[[scope]]]  // [fnEC.AO, globalEC.VO]
}

fn2.[[scope]] = fnEC.scopeChain
```

因此，印出 a 的值為 6。

6-2. `a = 20`

因為 fn2EC.AO 沒有 a，往上層找到 fnEC.AO 的 a，將其值設為 20

``` javascript
// third layer
fn2EC: {
  AO: {

  }, 
  scopeChain: [fn2EC.AO, fn2.[[scope]]]  // [fn2EC.AO, fnEC.AO, globalEC.VO]
}

// second layer
fnEC: {
  AO: {
    a: 20, 
    fn2: function
  },
  scopeChain: [fnEC.AO, fn.[[scope]]]  // [fnEC.AO, globalEC.VO]
}

fn2.[[scope]] = fnEC.scopeChain
```

6-3. `b = 100`

因為 fn2EC.AO 沒有 b，往上層找到 fnEC.AO 也沒有 b，因此只能在最上層 globalEC.VO 宣告變數 b 並賦值 100。

``` javascript
// third layer
fn2EC: {
  AO: {

  }, 
  scopeChain: [fn2EC.AO, fn2.[[scope]]]  // [fn2EC.AO, fnEC.AO, globalEC.VO]
}

// second layer
fnEC: {
  AO: {
    a: 20, 
    fn2: function
  },
  scopeChain: [fnEC.AO, fn.[[scope]]]  // [fnEC.AO, globalEC.VO]
}

fn2.[[scope]] = fnEC.scopeChain

// first layer
globalEC: {
  VO: {
    a: 1, 
    fn: function, 
    b: 100
  }, 
  scopeChain: [globalEC.VO]
}

fn.[[scope]] = globalEC.scopeChain
```

6-4. `fn2()` 執行完畢

`fn2()` 執行完畢，它的 EC 就會自動從 execution stack 的頂端推出，所以現在 execution stack 最上方的執行環境是 fnEC。

``` javascript
// second layer
fnEC: {
  AO: {
    a: 20, 
    fn2: function
  },
  scopeChain: [fnEC.AO, fn.[[scope]]]  // [fnEC.AO, globalEC.VO]
}

fn2.[[scope]] = fnEC.scopeChain

// first layer
globalEC: {
  VO: {
    a: 1, 
    fn: function, 
    b: 100
  }, 
  scopeChain: [globalEC.VO]
}

fn.[[scope]] = globalEC.scopeChain
```

---

4-7. `console.log(a)`

找到 fnEC.AO 的 a，印出 a 的值為 20。

4-8. `fn()` 執行完畢

`fn()` 執行完畢，它的 EC 就會自動從 execution stack 的頂端推出，所以現在 execution stack 最上方的執行環境是 globalEC。

``` javascript
// first layer
globalEC: {
  VO: {
    a: 1, 
    fn: function, 
    b: 100
  }, 
  scopeChain: [globalEC.VO]
}

fn.[[scope]] = globalEC.scopeChain
```

---

2-3. `console.log(a)`

找到 globalEC.VO 的 a，印出 a 的值為 1。

2-4. `a = 10`

``` javascript
// first layer
globalEC: {
  VO: {
    a: 10, 
    fn: function, 
    b: 100
  }, 
  scopeChain: [globalEC.VO]
}

fn.[[scope]] = globalEC.scopeChain
```

2-5. `console.log(a)`

找到 globalEC.VO 的 a，印出 a 的值為 10。

2-6. `console.log(b)`

找到 globalEC.VO 的 a，印出 b 的值為 100。
