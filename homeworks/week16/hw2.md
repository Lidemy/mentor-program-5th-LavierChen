# hw2：Event Loop + Scope

請說明以下程式碼會輸出什麼，以及盡可能詳細地解釋原因。

``` js
for(var i=0; i<5; i++) {
  console.log('i: ' + i)
  setTimeout(() => {
    console.log(i)
  }, i * 1000)
}
```

## 輸出結果

``` javascript
i: 0
i: 1
i: 2
i: 3
i: 4
5
5
5
5
5
```

## 執行流程

1. 將 for loop push into call stack 並執行，由於 `var i` 不在 for loop 的執行範圍內，因此 Hoisting 成全域變數，在程式碼中的任何地方都能存取到 `var i`。此時 `i = 0`，因為 `i < 5`，進入第一輪迴圈。
  
* 每一輪 for loop 都要執行以下程式碼，共執行五輪。

``` javascript
console.log('i: ' + i)
setTimeout(() => {
  console.log(i)
}, i * 1000)
```

2. 將 `console.log('i: ' + i)` push into call stack 並直接執行，印出 **i: 0**，執行結束後從 call stack pop out。

3. 將 `setTimeout(() => { console.log(i) }, i * 1000)` push into call stack，此時 `setTimeout` 會呼叫瀏覽器設定一個 **0 ms** 後到期的計時器，並將 `() => { console.log(i) }` 轉移到其他執行緒上計時，而  `setTimeout` 這個 function call 就執行完畢了，會從 call stack pop out。當計時器到期時，將 `() => { console.log(i) }` 放到 callback queue 中等候。

4. i++（此時 `i = 1`），因為 `i < 5`，進入第二輪迴圈。

5. 將 `console.log('i: ' + i)` push into call stack 並直接執行，印出 **i: 1**，執行結束後從 call stack pop out。

6. 將 `setTimeout(() => { console.log(i) }, i * 1000)` push into call stack，此時 `setTimeout` 會呼叫瀏覽器設定一個 **1000 ms** 後到期的計時器，並將 `() => { console.log(i) }` 轉移到其他執行緒上計時，而  `setTimeout` 這個 function call 就執行完畢了，會從 call stack pop out。當計時器到期時，將 `() => { console.log(i) }` 放到 callback queue 中等候。

7. i++（此時 `i = 2`），因為 `i < 5`，進入第三輪迴圈。

8. 將 `console.log('i: ' + i)` push into call stack 並直接執行，印出 **i: 2**，執行結束後從 call stack pop out。

9. 將 `setTimeout(() => { console.log(i) }, i * 1000)` push into call stack，此時 `setTimeout` 會呼叫瀏覽器設定一個 **2000 ms** 後到期的計時器，並將 `() => { console.log(i) }` 轉移到其他執行緒上計時，而  `setTimeout` 這個 function call 就執行完畢了，會從 call stack pop out。當計時器到期時，將 `() => { console.log(i) }` 放到 callback queue 中等候。

10. i++（此時 `i = 3`），因為 `i < 5`，進入第四輪迴圈。

11. 將 `console.log('i: ' + i)` push into call stack 並直接執行，印出 **i: 3**，執行結束後從 call stack pop out。

12. 將 `setTimeout(() => { console.log(i) }, i * 1000)` push into call stack，此時 `setTimeout` 會呼叫瀏覽器設定一個 **3000 ms** 後到期的計時器，並將 `() => { console.log(i) }` 轉移到其他執行緒上計時，而  `setTimeout` 這個 function call 就執行完畢了，會從 call stack pop out。當計時器到期時，將 `() => { console.log(i) }` 放到 callback queue 中等候。

13. i++（此時 `i = 4`），因為 `i < 5`，進入第五輪迴圈。

14. 將 `console.log('i: ' + i)` push into call stack 並直接執行，印出 **i: 4**，執行結束後從 call stack pop out。

15. 將 `setTimeout(() => { console.log(i) }, i * 1000)` push into call stack，此時 `setTimeout` 會呼叫瀏覽器設定一個 **4000 ms** 後到期的計時器，並將 `() => { console.log(i) }` 轉移到其他執行緒上計時，而  `setTimeout` 這個 function call 就執行完畢了，會從 call stack pop out。當計時器到期時，將 `() => { console.log(i) }` 放到 callback queue 中等候。

16. i++（此時 `i = 5`），因為 `i >= 5`，不再進入 for loop，call stack 目前為空。

17. 被瀏覽器轉移到其他執行緒上計時的 `() => { console.log(i) }`，當計時器到期後，會依據延遲時間 0 sec、1 sec、2 sec、3 sec、4 sec 被放到 callback queue 中等候。

    event loop 發現 callback queue 還有 callback function 等待中， 將 callback queue 排在最前面的 callback function push into call stack 並直接執行，也就是執行 `() => { console.log(i) }`，此時 `i = 5`，印出 5，執行結束後從 call stack pop out。

18. event loop 發現 callback queue 還有 callback function 等待中， 將 callback queue 排在最前面的 callback function push into call stack 並直接執行，也就是執行 `() => { console.log(i) }`，此時 `i = 5`，印出 5，執行結束後從 call stack pop out。

19. event loop 發現 callback queue 還有 callback function 等待中， 將 callback queue 排在最前面的 callback function push into call stack 並直接執行，也就是執行 `() => { console.log(i) }`，此時 `i = 5`，印出 5，執行結束後從 call stack pop out。

20. event loop 發現 callback queue 還有 callback function 等待中， 將 callback queue 排在最前面的 callback function push into call stack 並直接執行，也就是執行 `() => { console.log(i) }`，此時 `i = 5`，印出 5，執行結束後從 call stack pop out。

21. event loop 發現 callback queue 還有 callback function 等待中， 將 callback queue 排在最前面的 callback function push into call stack 並直接執行，也就是執行 `() => { console.log(i) }`，此時 `i = 5`，印出 5，執行結束後從 call stack pop out。
