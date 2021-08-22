## 請簡單解釋什麼是 Single Page Application

![Single Page Application Work](https://www.monocubed.com/wp-content/uploads/2020/10/How-does-Single-Page-Application-Works.jpg)

單頁應用程式（Single Page Application，SPA），有別於以往 Multi-page（多頁式）的網站設計，點選一個按鈕後，需要重新載入另外一個頁面。透過 Ajax 的技術，Client 端發出 Request 後，從 Server 端拿取回傳的資料，不需要重新轉載整個網頁，而是抽換頁面的部分資料，就可以即時更新頁面。

在 SPA 架構下，除了第一次需要全畫面渲染，之後使用者在頁面上的操作，例如：新增、修改、刪除、讀取等，都「不需要換頁」就可以在單一頁面上完成執行。

## SPA 的優缺點為何

### 優點

1. 增進使用者體驗：不用換頁，使用者體驗較佳。

2. 資訊傳遞快速：在第一次連結網站時，網站內容就已經被下載完成，之後的操作都不需要重新載入頁面。

3. 前後端分離：後端只需負責制定 API 文件，提供前端資料。前端則利用 Ajax 從後端拿取資料，並以 JavaScript 在 html 動態產生內容，各司其職。

### 缺點

1. 搜尋引擎最佳化（SEO）較差：因為將所有資料放在同一頁面中，SEO 問題需克服。

2. 初次載入頁面費時：初次瀏覽頁面時會需要下載 JavaScript 或是其他頁面的 template，如果從伺服器抓取的資料比較多的話，第一次載入頁面的速度可能會比較慢。

## 這週這種後端負責提供只輸出資料的 API，前端一律都用 Ajax 串接的寫法，跟之前透過 PHP 直接輸出內容的留言板有什麼不同？

### PHP 直接輸出內容

Server 端接收到 Request，會將資料與 UI 經處理後回傳整份 html，也就是說頁面是由 Server 端產生，Client 端每次發出 Request 都需要重載頁面，使用者體驗不佳。

由於頁面是由 Server 端產生，所以檢視頁面原始碼會包含資料，屬於靜態頁面。

### 後端負責提供只輸出資料的 API

透過 Ajax 技術，Server 端接收到 Request，會回傳 JSON 或 XML 等其他特定格式的資料，瀏覽器再將資料局部抽換，動態更新至頁面。

由於 Server 端只有回傳資料，之後透過 JavaScript 操作 DOM 元素動態產生頁面，因此檢視頁面原始碼並不包含資料，屬於靜態頁面。