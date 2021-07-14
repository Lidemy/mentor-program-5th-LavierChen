## 什麼是 Ajax？

 Ajax ( Asynchronous JavaScript and XML ) 非同步的 JavaScript 與 XML 技術，是 JavaScript 以「非同步」方式與伺服器交換資料的統稱，網頁不須換頁，就能即時更新渲染畫面。

 Ajax 早期都是以 XML 為資料格式，現在多用 JSON 格式。

## 用 Ajax 與我們用表單送出資料的差別在哪？

用 Ajax 和表單傳送資料的差異，在於如何處理「回傳結果」。

* 表單傳送：

1. 以 html 來傳送資料，當瀏覽器接收到 response 後，頁面會重新刷新。

2. 表單傳送資料會被瀏覽器視為安全的方式，不受同源政策的限制。

3. 表單為瀏覽器支援的傳送方式，無關乎 JavaScript 都可以提交表單。

* Ajax

1. 瀏覽器接收 response，會轉傳資料至 JavaScript，進行局部內容的資料抽換，不須換頁就能即時更新頁面。

2. Ajax 傳送資料會受同源政策所限制。

3. 由於 Ajax 是以 JavaScript 實現技術，會有瀏覽器兼容的問題，若瀏覽器不支援 JavaScript，將無法使用 Ajax。

## JSONP 是什麼？

瀏覽器基於安全性的考量，定義了「同源政策」這項規範，也就是非同源就無法拿到 response。

![Origin determination rules](https://i.imgur.com/GcNEzVX.png)

JSON（JSON with Padding）利用 html 標籤不受同源政策影響的特性，例如 `<script>` 或 `<img>`，藉由 `<script src="https://api.twitch.tv/kraken/games/top"></script>` 讀取網頁的 JS 資訊，再透過指定的 function 進行輸出，就能夠拿到想要的資料。

## 要如何存取跨網域的 API？

利用「跨來源資源共用（Cross-origin Resource Sharing，CORS）」這項規則，和 JSONP 同樣能讓網頁從別的網域要資料。

只要 Server 端在 **response header** 加上 `access-control-allow-origin: *`，即使是非同源的網域，也可以從 Server 存取資料。

## 為什麼我們在第四週時沒碰到跨網域的問題，這週卻碰到了？

1. Node.js

Node.js 程式直接發 request 給 server 後，可以直接拿到 server 回傳的 response，中間沒有經由瀏覽器，所以回傳的資料不會被更改，也沒有任何的限制。

2. 瀏覽器

瀏覽器上的 JS 會先透過瀏覽器，經由瀏覽器發 request 給 server，之後 server 再發 response 給瀏覽器，瀏覽器在把資料給 JS。

在資料傳輸的過程中有經由瀏覽器，必須按照瀏覽器的規則獲取資料，也就是必須遵守「同源政策」。
