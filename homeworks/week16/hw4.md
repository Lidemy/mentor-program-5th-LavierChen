# hw4：What is this?

請說明以下程式碼會輸出什麼，以及盡可能詳細地解釋原因。

``` js
const obj = {
  value: 1,
  hello: function() {
    console.log(this.value)
  },
  inner: {
    value: 2,
    hello: function() {
      console.log(this.value)
    }
  }
}
  
const obj2 = obj.inner
const hello = obj.inner.hello
obj.inner.hello() // ??
obj2.hello() // ??
hello() // ??
```

## 輸出結果

``` javascript
2
2
undefined
```

## 預備知識

### this 的意義

this 的主要用途在於**物件導向**，用來指哪個實體物件（instance）在呼叫這個屬性／方法，透過 this 我們能夠進一步操作。

``` javascript
class Dog {
  setName(name) {
    this.name = name
  }
}

var myDog = new Dog()
myDog.setName('Woody')
```

在非物件導向地方呼叫 this，預設值會是什麼？

如果在全域環境下呼叫 this，根據不同執行環境（瀏覽器或 node.js），預設值可能會是 global 或 window：

* 在 node.js 環境下執行

```javascript
function test() {
  console.log(this)  // Object [global] { ...}
  console.log(this === global)  // true
}

test()
```

![this](https://i.imgur.com/LmToJBT.jpg)

* 在瀏覽器環境下執行

``` javascript
function test() {
  console.log(this)  // Window { ...}
  console.log(this === window)  // true
}
test()
```

![this](https://i.imgur.com/ZIqSOZl.png)

* use strict 嚴格模式

在沒意義的地方呼叫 this，還是會有預設值 global 或 window。這時候，只要設定 **'use strict'**（嚴格模式）就能避免這種期況發生，this 的預設值會是 **undefined**：

``` javascript
'use strict';
function test() {
  console.log(this)
}
test()  // undefined
```

* DOM 元素中的 this

此外，對 DOM 元素進行事件監聽時，this 就代表當下操作的元素，其實非常直覺。例如監聽按鈕的點擊事件，那 this 就會是那個按鈕：

``` javascript
document.querySelector('.btn').addEventListener('click', function() {
  console.log(this)   // this: 觸發這個 funciont 的物件
})
```

因此，除了在物件導向跟 DOM 之外，this 是沒有意義的。

### 用另一個角度看 this 的值

因為 this 是針對物件導向設計的，從以下範例，可知道 this 就是 obj 物件本身：

``` javascript
'use strict'

const obj = {
  a: 10, 
  test: function() {
    console.log(this)
  }
}

obj.test()  // { a: 10, test: [Function: test] }
```

重點就是，this 的值和在什麼時候被定義無關，而是和怎麼呼叫有關：

``` javascript
'use strict'

const obj = {
  a: 10, 
  test: function() {
    console.log(this)
  }
}

obj.test()          // { a: 10, test: [Function: test] }
obj.test.call(obj)  // { a: 10, test: [Function: test] }

var func = obj.test
func()                // undefined
func.call(undefined)  // undefined
```

會得到不同結果，這是因為 obj 呼叫 function，所以 this 會指向 obj；另一種函式宣告，沒有 instance 去呼叫 function，所以會得到 undefined。

可以透過 `.call()` 來看看 this 指的是什麼：

``` javascript
'use strict'

const obj = {
  a: 10, 
  inner: {
    test: function() {
      console.log(this)
    }
  }
}

obj.inner.test()  
// { test: [Function: test] }

obj.inner.test.call(obj.inner) 
// { test: [Function: test] }

obj.inner.test.call(obj)
// { a: 10, inner: { test: [Function: test] } }

obj.inner.test.call()
// undefined
```

**this，就是函式前面的 context**

JavaScript 開始執行一個函數，它的運行環境從原來的 Global Code 變為 Function Code，會創建一個 execution context 對象，this 就是你呼叫一個函式時傳的 context。

而用 `.call()` 方法傳的第一個參數，可以動態改變 this 的值。

## 執行流程

``` javaScript
const obj2 = obj.inner
const hello = obj.inner.hello

obj.inner.hello()  // => obj.inner.hello.call(obj.inner)

obj2.hello()  // => obj2.hello.call(obj2) => obj.inner.hello.call(obj.inner)

hello()  // hello.call()
```

1. `obj.inner.hello()`

可以看成 `obj.inner.hello.call(obj.inner)`，`.call()` 方法傳的第一個參數，可以動態改變 this 的值，因此 `obj.inner.value` 得到的結果是 2。

2. `obj2.hello()`

可以看成 `obj2.hello.call(obj2)`，也就是 `obj.inner.hello.call(obj.inner)`，`.call()` 方法傳的第一個參數，可以動態改變 this 的值，因此 `obj.inner.value` 得到的結果是 2。

3. `hello()`

可以看成 `hello.call()`，`.call()` 沒有傳入任何 context，因此 this 會被指定為全域物件。依照執行環境不同，其值也會改變，例如：在瀏覽器執行會是 window，在 node.js 執行則是會是 global；若是在 **'use strict'**（嚴格模式）下執行，this 的值會是 undefined。
