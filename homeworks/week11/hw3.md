## 請說明雜湊跟加密的差別在哪裡，為什麼密碼要雜湊過後才存入資料庫

明文密碼：會員註冊時的密碼沒有經過其他處理，以明碼的方式儲存在資料庫中，會有以下風險

>1. 網站管理者隨時能夠查看使用者帳號密碼
>2. 如果有駭客成功入侵資料庫，即可竊取所有使用者資料

不讓密碼輕易被盜取，通常會運用一些方法，將密碼加以變化後再儲存於資料庫，加密與雜湊均可使密碼產生變化，達到保護密碼的功能，最大差別在於：「加密可逆，雜湊不可逆」。

### 加密（Encrypt）

> 加密內容是可逆的，有密鑰（key）就可以解密

#### 對稱式加密

![對稱加密](https://miro.medium.com/max/875/1*LJbBohnZ8OTiCH1f-4KuQA.png)

 對稱加密使用**同一把**鑰匙加解密，傳輸資料前，傳送方與接收方必須協商好密鑰，雙方必須妥善安全地保管好密鑰。

* 優點：AES 有超過 10³⁸ 種 key 值，安全性高、不易破解，加密速度快

* 缺點：不同對象要使用不同組金鑰，管理不易，與 N 個用戶交換資訊，需要保管 N 把鑰匙，且需要建立一個安全機制將金鑰分送給用戶

#### 非對稱式加密

![非對稱加密](https://miro.medium.com/max/875/1*z7TqcmFAb34ZANCS1_4MwQ.png)

加密和解密使用**不同把**鑰匙，一對金鑰由公鑰（可交給任何請求方）和私鑰（由一方保管）組成，可互相解密加密，用公鑰加密的內容只能用私鑰解。

* 優點：公鑰可以公開發送，不管與多少用戶交換資訊，只需保管 1 把鑰匙

* 缺點：加密速度慢，密文長度過大，通常用於少量數據加密

### 雜湊（Hashing）

將一個變動長度的資料，經過雜湊函數處理後，產生一個固定長度的雜湊值。設計妥當的雜湊函數，在輸入不同資料時，幾乎不可能產生相同的雜湊值，由於明碼經過複雜計算，無法從雜湊後的結果回推明碼，可以作為系統中保護用戶密碼的安全機制。

![Hashing vs Encryption](https://aboutssl.org/wp-content/uploads/2020/09/hashing-vs-encryption.svg)

## `include`、`require`、`include_once`、`require_once` 的差別

### include 和 include_once

* 執行到 `include()` 時，每次皆會讀取檔案
* 常用於流程控制，例如：條件判斷、迴圈
* 適合引入動態的程式碼，會在用到時加載
* 執行時，若引入的檔案發生錯誤，會顯示警告（Warning），但不會立刻停止程式

### require 和 require_once

* 執行到 `require()` 時，只會讀取一次檔案
* 常放在程式開頭，檔案引入後 PHP 會將網頁重新編譯
* 適合引入靜態的程式碼，會在一開始就加載
* 執行時，若引入的檔案發生錯誤，會直接報錯（Fatal error）並終止程式

## 請說明 SQL Injection 的攻擊原理以及防範方法

又被稱為駭客的填字遊戲。使用者在可以輸入資料的地方，加入特定功能的指令，使 Server 將此指令一併執行，達到破壞資料或查詢機密資料的目的。

* 略過密碼檢查
  
``` php
SELECT * FROM users WHERE username='' or 1=1# AND password='';
```

`'`：將 username 的 input 關閉

`or`：條件式的「或是」

`#`：代表註解，後面的條件均會被忽略

* 查詢時以 `UNION` 搭配內層 SQL 印出機密資料 

簡言之，SQL Injection 就是透過 SQL 語句，改變查詢的語意，達到竊取資料的目的。

防範方法：

1. 在組合 SQL 字串時，先針對傳入的參數作字元取代

2. 完全使用參數化查詢（Parameterized Query）來設計資料存取功能
  
``` php
$stmt = $conn->prepare('SELECT * FROM employees WHERE name = ? AND id = ?');
$stmt->bind_param('si', $name, $id);
$stmt->execute();

$result = $stmt->get_result();
```

3. 避免腳本語言程式原始碼洩漏

4. 設定 Server，發生錯誤時，避免提供完整的錯誤訊息

##  請說明 XSS 的攻擊原理以及防範方法

利用網站允許使用者輸入資料的欄位插入腳本語言，造成其他正常使用者瀏覽網頁的同時，瀏覽器會主動執行部分惡意的程式碼，將其他使用者目前的 Cookie 或 Session 資料傳送到駭客電腦，或將使用者導向到惡意網站。

* 以 JavaScript 的 `document.cookie` 取得 Cookie 資料
* 以 JavaScript 的 `window.location` 導向惡意網站

防範方法：

XSS 主要歸咎於網站開發時忽略的安全漏洞，在 Client 與 Server 有各自的應對方法

**Client**

1. 定期更新瀏覽器至最新版本

2. 避免點擊來路不明的網址

**Server**

1. 過濾特殊字元：對於使用者所有輸入的資料都需要加以檢查，除了表單的填寫欄位，也要防堵網址列 `GET` 參數

```php
function escape($str) {
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
```

2. 限定輸入內容的長度與型態

## 請說明 CSRF 的攻擊原理以及防範方法

跨網站冒名請求（Cross-Site Request Forgery, CSRF）的攻擊手法與 XSS 相似，都是跨站式的請求攻擊，差別在於：

XSS：透過在網頁輸入惡意程式碼的方式來進行攻擊

>使用者對目標網站的信任

CSRF：攻擊者利用網站使用者的身分發送請求，去執行一些未經授權的操作

>目標網站對該使用者的信任

舉例：

1. 假設使用者 Alice 是網站 `Alice.tw` 的管理員

2. Alice 的瀏覽器有設定導向 `Alice.tw` 時會自動登入，一連上網站就會以管理員身分檢視、執行操作

3. 頁面 `Alice.tw/admin.php` 的功能為將一般使用者改為管理員，需要管理員身分登入才能操作，例如：`Alice.tw/admin.php?id=123` 是將 id 為 123 的一般使用者改為管理員

4. 駭客寄 e-mail 給 Alice，e-mail 裡有超連結 `Alice.tw/admin.php?id=123`

5. Alice 點擊超連結後，就把 id 為 123 的一般使用者改為管理員了

簡言之，CSRF 就是「在不同 domain 下，偽造使用者本人發出的 request」。

防範方式：

CSRF 的核心概念是跨網站請求，需要解決的問題是：如何擋掉從別的 domain 來的請求

1. 加上圖形驗證碼、簡訊驗證碼

在網站加上驗證碼，可多一道檢查程序，常用於金流相關操作

2. 加上 CSRF token

防止 CSRF 攻擊，只要確保有些資訊「只有使用者知道」即可，要求在存取敏感數據請求時，用戶瀏覽器提供不儲存在 Cookie 中，並且攻擊者無法偽造的數據作為校驗，那麼攻擊者就無法再執行 CSRF 攻擊，校驗 token 值為空或者錯誤時，就拒絕這個可疑的請求。

* 產生：Server
* 儲存：Server

在 form 裡面加上一個 `hidden` 欄位為 `csrftoken`，填入的值為 Server 隨機產生的亂碼，並且存在 Server 的 Session 中。

按下 submit 後，Server 會比對表單中的 CSRF token 與存在 Server 端的是否相同。

``` html
<form method="post">
     <!--值由 Server 隨機產生，並且存在 Server 的 Session 中
     CSRF token 必須被保存在 Server 當中，才能驗證正確性-->
    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}"/>
</form>
```

3. Double Submit Cookie

與前一種解法相似，好處是不需要存任何東西在 Server 端。

而是在 Cookie 與 form 設置相同的 CSRF token，利用「Cookie 只會從相同 domain 帶上來」機制，使攻擊者無法從不同 domain 戴上此 Cookie。

4. Client 端生成的 Double Submit Cookie

和 Double Submit Cookie 核心概念相同。不同之處在於「改由 Client 端」生成 CSRF token。

生成之後放到 form 裡面以及寫到 Cookie，其他流程就和之前相同。 此 Cookie 只是確保攻擊者無法取得，不含任何資訊，因此由 Client 或 Server 生成都是一樣的。

5. 瀏覽器端的防禦：SameSite Cookie

其原理就是幫 Cookie 再加上一層驗證，不允許跨站請求。即除了在 B 網站這個 domain 發出的請求，其他 domain（如 A 網站）發出的 request 都不會帶上此 Cookie，防止來自不同 domain 的請求。

只要在設置 Cookie 加上：
```
Set-Cookie: key=value; path=/; domain=example.org; HttpOnly; SameSite=Lax
```

**strict 嚴格**

完全禁止第三方 Cookie，跨網站請求時，任何情况下都不會發送 Cookie，也就是只有和當前網頁的 URL 一致，才會帶上 Cookie。

如果只允許 SameSite 使用，不應該在任何的 cross site request 都加上去，會使 `<a href="">`、`<form>` 等標籤，只要不同 domain 都不會帶上此 Cookie。假如從 Google 搜尋結果或其他連結點進某個網站的時候，由於不會帶 Cookie 的關係，那個網站就會變成是登出狀態，造成不好的使用者體驗。

**Lax**

* `<a href="">`、`<form>` 可以帶上 Cookie

* 除了 `Get` 方法，其他的 `POST`、`DELETE`、`PUT` 都不會帶上 Cookie，意即沒辦法擋掉 `GET` 形式的 CSRF
