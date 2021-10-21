# hw1：Event Loop

在 JavaScript 裡面，一個很重要的概念就是 Event Loop，是 JavaScript 底層在執行程式碼時的運作方式。請你說明以下程式碼會輸出什麼，以及盡可能詳細地解釋原因。

``` js
console.log(1)
setTimeout(() => {
  console.log(2)
}, 0)
console.log(3)
setTimeout(() => {
  console.log(4)
}, 0)
console.log(5)
```

## 輸出結果

``` javascript
1
3
5
2
4
```

## 預備知識

### 單線程（single thread）

JavaScript 是單線程（single thread runtime）的程式語言，所有程式碼片段都會在呼叫堆疊（call stack）中執行，而且一次只會執行一個程式碼片段（Do one thing at one time）。

### 呼叫堆疊（call stack）

JavaScript 中的呼叫堆疊（call stack）會記錄目前執行到整個程式碼的哪個片段，如果進入了一個函式（function），就會將這個函式推入（push into）call stack 的最上方；如果函式執行到 `return`，代表該函式執行完畢，函式會從 call stack 的最上方被推出（pop out）。

![event loop](https://miro.medium.com/max/1400/0*12q--TIw0eA7XYEK.png)

### 事件循環（event loop）

我們之所以可以在瀏覽器中同時（concurrently）處理多件事情，是因為瀏覽器並非只有一個 JavaScript 的執行環境（runtime）。雖然 JavaScript 一次只能執行一件事情，但是**瀏覽器**提供了很多不同的 API（例如：DOM、AJAX、Timeout），並由不同的執行緒去處理各自負責的事情，因此可以透過事件循環（event loop）搭配非同步（asynchronous）的方式，同時處理多件事情。

### setTimeout

`setTimeout` 是瀏覽器提供的 API，並不是 JavaScript 引擎本身的功能。瀏覽器提供了一個計時器的服務，當程式執行到 `setTimeout(function cb(){}, delay)` 的片段時，`setTimeout(function cb(){}, delay)` 會被 push 到 call stack 中，`setTimeout` 中的第一個參數 callback function（簡稱 cb）會被轉移到其他執行緒上計時，不會影響到 JavaScript 的執行緒。而 `setTimeout` 這個 function call 就執行完畢了，因此會從 call stack pop 出來。

當計時器到期時，會把 callback function 放到一個叫回調佇列
（callback queue）的地方等候。

事件循環（event loop）的工作就是，如果 call stack 是空的，它就會把 callback queue 中的第一個項目放到 call stack 中執行。

## 執行流程

1. 將 `console.log(1)` push into call stack 並直接執行，印出 1，執行結束後從 call stack pop out。

2. 將 `setTimeout(() => { console.log(2) }, 0 )` push into call stack，此時 `setTimeout` 會呼叫瀏覽器設定一個 0 ms 後到期的計時器，並將 `() => { console.log(2) }` 轉移到其他執行緒上計時，而  `setTimeout` 這個 function call 就執行完畢了，會從 call stack pop out。當計時器到期時，將 `() => { console.log(2) }` 放到 callback queue 中等候。

3. 將 `console.log(3)` push into call stack 並直接執行，印出 3，執行結束後從 call stack pop out。

4. 將 `setTimeout(() => { console.log(4) }, 0 )` push into call stack，此時 `setTimeout` 會呼叫瀏覽器設定一個 0 ms 後到期的計時器，並將 `() => { console.log(4) }` 轉移到其他執行緒上計時，而  `setTimeout` 這個 function call 就執行完畢了，會從 call stack pop out。當計時器到期時，將 `() => { console.log(4) }` 放到 callback queue 中等候。

5. 將 `console.log(5)` push into call stack 並直接執行，印出 5，執行結束後從 call stack pop out。

6. call stack 為空，event loop 發現 callback queue 還有 callback function 等待中， 將 callback queue 排在最前面的 callback function push into call stack 並直接執行，也就是執行 `() => { console.log(2) }`，印出 2，執行結束後從 call stack pop out。

7. call stack 為空，event loop 發現 callback queue 還有 callback function 等待中， 將 callback queue 排在最前面的 callback function push into call stack 並直接執行，也就是執行 `() => { console.log(4) }`，印出 4，執行結束後從 call stack pop out。
