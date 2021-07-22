## 資料庫欄位型態 VARCHAR 跟 TEXT 的差別是什麼

1. CHAR
   - 固定長度
   - 索引速度最快
   - 適用：身分證、員工編號
2. VARCHAR
   - 變動長度，可以設定最大長度
   - 索引速度適中
   - 適用：姓名、住址
3. TEXT
   - 存取較長資料，不可設定最大長度
   - 索引速度最慢
   - 適用：留言板、部落格

**注意**：

- 使用 CHAR(n)，即使內容不足預設長度 n，仍會佔據 n 個字元空間，使用 VARCHAR 彈性較大

- 當 VARCHAR 大於預設值，會自動轉換為 TEXT

- TEXT 不可設定 DEFAULT

## Cookie 是什麼？在 HTTP 這一層要怎麼設定 Cookie，瀏覽器又是怎麼把 Cookie 帶去 Server 的？

1. Cookie 是伺服器（Server）暫存在電腦裡的一個小型純文字檔，裡面存放了使用者（User）瀏覽過的網站之相關資料，當 User 再次瀏覽同個網站時，系統就會去讀取 Cookie 裡存放的資料，使 Server 可以辨識 User 身分。

2. 由於 HTTP 是一個無狀態協議，每一次連線（request、response）都視為獨立的行為，因此 Server 無法辨識 User 的身分，造成使用者體驗上有不小的問題，例如：當要結帳購物車時，跳轉頁面後購物車便會清空，因為每一次的 request 都是獨立行為，無法記錄 User 狀態。

3. Server 能透過 response header 的 Set-Cookie 屬性，將 User 狀態記錄在 Cookie。從此以後，瀏覽器在發送請求時，都會在 request header 帶上 Cookie 資料，Server 在檢視 Cookie 後，便能識別 User。

## 我們本週實作的會員系統，你能夠想到什麼潛在的問題嗎？

1. 在 users table 中明碼儲存 password，若資料庫被入侵，則有敏感資料外洩的疑慮

2. 在 users table 中沒有規定 password 的格式，例如：最少幾個字元、需要大小寫英文加數字，以及帳號與密碼不可一致等規定，若使用者註冊的資料太過簡易，則容易被攻擊手破解

3. 在 comments table 中，content 沒有處理跳脫字元，若遭攻擊者以 `<script>` 插入惡意程式碼，例如：盜取帳號密碼、盜取 cookie 等惡意操作，瀏覽器執行這段 JavaScript 程式碼後，使用者資料將外洩
