# hw5：這週學了一大堆以前搞不懂的東西，你有變得更懂了嗎？請寫下你的心得

這一週的學習內容很多，除了課程本身的內容很紮實以外，參考文章跟自己找的資料也很豐富，因此花了蠻多時間在整理筆記。

## Variable

從複習 JavaScript 的變數、資料型態，到等號賦值、記憶體位址、call by value or call  by reference 等，從底層架構去了解程式運作原理。

## Hoisting、Execution Context

這部分蠻好理解的，從 ECMAScript 理解 Hoisting 原理，了解為什麼需要 Hoisting，尤其是函式在宣告前可以呼叫這一點，讓程式設計有更大的彈性。

Execution Context 的建立牽扯到很多面向，Event Loop、Scope Chain、Closure、this 等都脫離不了關係，參考了 [JavaScript 深入淺出 Execution Content 執行環境](https://shawnlin0201.github.io/JavaScript/JavaScript-Execution-Context/)，完全理解 Execution Context 存在的意義。

## Scope、Closure

理解 Execution Context 後，對於 Scope 以及 Scope Chain 的觀念建立就容易了許多，JavaScript 是如何處理變數的，就是透過 Scope Chain 這個屬性。

一開始並不是很了解 Closure 的意思，一直聽課到 [Closure 可以應用在哪裡？](https://lidemy.com/courses/390599/lectures/8624019)才有點概念。

通常 Closure 的使用時機是想要**隱藏住某些資訊**。例如下面的例子中，就算不是經過 deduct 函式，還是可以隨便在外部改到 myWallet：

``` javascript
var myWallet = 99

function add(num) {
  myWallet += num
}

function deduct(num) {
  myWallet -= (num >= 10 ? 10 : num)
}

add(1)
deduct(100)
console.log(myWallet)  // 90

myWallet = -1
console.log(myWallet)  // -1
```

為了保證 myWallet 不會隨便被 function 外面的程式碼重新賦值，可以用 **Closure** 的方式達到**封裝**的效果，這樣就只有透過 getWallet 裡面的 function 才能修改到 myWallet 的值：

``` javascript
function getWallet(initMoney) {
  var money = initMoney

  return {
    add: function(num) {
      money += num
    },
    deduct: function(num) {
      money -= (num >= 10 ? 10 : num)
    },
    view: function() {
      return money
    }
  }
}

var myWallet = getWallet(99)
myWallet.add(1)
myWallet.deduct(100)
console.log(myWallet.view())  // 90

myWallet.money = -1
console.log(myWallet.view())  // 90
```

## Object Oriented、Prototype

JavaScript 雖然也是一種物件導向程式語言（Object-oriented programming, OOP），但跟其他 OOP 不一樣的是，大部分 OOP 都定義類別（Class），然後建立類別的實例──物件（Object），然而 JavaScript 則是基於原型（Prototype）而非類別的語言。

### 從 ES6 以前的 Class 延伸到 Prototype

先看下方程式碼：

``` javascript
function Dog(name) {
  var dogName = name

  return {
    getName: function() {
      return dogName
    },
    sayHello: function() {
      console.log('Hello, my name is', dogName)
    }
  }
}

var dogWhite = Dog('White')
dogWhite.sayHello()  // Hello, my name is White

var dogBlack = Dog('Black')
dogBlack.sayHello()  // Hello, my name is Black

console.log(dogWhite.sayHello === dogBlack.sayHello)  // false
```

會發現 `dogWhite.sayHello` 和 `dogBlack.sayHello` 兩個功能相同的 function 不是同一個東西，代表程式在背後其實產生了兩個功能相同的 function。原因在於 class 產生了兩個不同的 instance，造成 **this 指向不同**。

但這其實會產生一個問題，如果今天有一萬隻狗，就會產生一萬個功能相同的 function，明明都是在處理同樣的事情，應該可以使用同一個 function，去對應不同的 instance 即可。

### 以 `.prototype` 連結 function

在 JavaScript 機制中，有個 `.prototype` 屬性能夠連結 function，如下方程式碼：

* 使用 `Dog.prototype.sayHello` 將建構一個屬性為 sayHello 的 function

* new 出來的物件皆可存取 sayHello 這個屬性

``` javascript
function Dog(name) {
  this.name = name
}

Dog.prototype.getName = function() {
  return this.name
}

Dog.prototype.sayHello = function() {
  console.log('Hello, my name is', this.name)
}

var dogWhite = new Dog('White')
dogWhite.sayHello()

var dogBlack = new Dog('Black')
dogBlack.sayHello()

console.log(dogWhite.sayHello === dogBlack.sayHello)  // true
```

這麼一來兩個 function 就會是同一個東西，因為 dogWhite 和 dogBlack 都是使用 prototype 上面的 function。如此一來，我們就可以使用同一個 function 去跑不同的 instance，透過這個方式實作 JavaScript 的物件導向。

## this

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

除了在物件導向使用 this 以外，對 DOM 元素進行事件監聽時，this 就代表當下操作的元素，例如監聽按鈕的點擊事件，那 this 就會是那個按鈕。因此，除了在物件導向跟 DOM 之外，this 是沒有意義的。

關於 this 的重點，就是記得 this 的值和程式碼在哪無關，而是和怎麼呼叫有關係。

## Event Loop

[所以說 event loop 到底是什麼玩意兒？](https://www.youtube.com/watch?v=8aGhZQkoFbQ)講解得十分清晰，把一個看似複雜的觀念用動畫一步一步的說明，讓我在看完影片的同時對 Event Loop 也了解了七成以上，額外閱讀了[理解 JavaScript 中的事件循環、堆疊、佇列和併發模式](https://pjchender.blogspot.com/2017/08/javascript-learn-event-loop-stack-queue.html)又融會貫通了一次。

從第七週開始就一直有在使用 callback function 的觀念，但總是覺得還是似懂非懂，看完影片後再次閱讀 [JavaScript 中的同步與非同步（上）：先成為 callback 大師吧！](https://blog.huli.tw/2019/10/04/javascript-async-sync-and-callback/)就釐清更多觀念了，對於同步與非同步也有了新的一層認識。

## 總結

在學習這個階段的課程，由於外務繁多，一開始是階段性的學習，這個 part 的內容搞懂了才進行下一個 part，但因為學習的過程中有間隔時間，導致記憶遺忘，又要回去溫習上一個 part 的內容。

直到最近外務處理完畢，一鼓作氣的把所有的課程內容上完，第一次整理自己的筆記後，再去閱讀老師的補充文章以及網路上的參考文章，把吸收到的知識再補到筆記裡面，做第二次的筆記整理。

在寫作業的過程中重新回顧自己的筆記，藉由作業去審視自己的筆記還有沒有觀念不清楚的地方，因此這個部分算是完整的學習了三次。

在這個過程中我感受到，當你第一次遇到不懂的觀念，急著搞懂他未必是好事，也無法確定是真正搞懂了還是暫時性的記憶而已，就像學習線性代數一樣，每一個章節的觀念都不難，但為什麼會念不好？因為沒有連貫性，這個章節的觀念可能會在下個章節才解釋完整，等念到下一個章節的時後，再回頭看上一個章節的內容就茅塞頓開了，JavaScript 的觀念就像線性代數一樣，要反覆的前後對照，建立一個有連貫性的學習曲線，才是真的搞懂了他背後的意義。
