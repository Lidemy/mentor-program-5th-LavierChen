## 什麼是 DNS？Google 有提供的公開的 DNS，對 Google 的好處以及對一般大眾的好處是什麼？

DNS（Domain Name System）是一套系統，可以做到在 Domain Name 與 IP address 之間的轉換，例如在瀏覽器輸入 `google.com` 的時候，透過 DNS 可以對應到該網域所指向的 IP address 是 `172.217.160.99:443`。

當開啟 Web 瀏覽器進入網站時，不需要記住這些冗長的數字進行輸入，而是輸入像 `google.com` 這樣的網域名稱就可以連接到正確的位置。

Google Public DNS 官網列出了三個使用 Google Public DNS 的理由：

1. Speed up your browsing experience

    Google Public DNS 的快取機制（Caching），會在 TTL 到期之前預抓（Prefetch），所以不用等待 DNS 查詢的時間，這項差異在開啟一個需要多次 DNS 查詢的網頁（參考資料眾多，如：wiki），會有明顯的差異。

2. Improve your security

    DNS 容易受到欺騙攻擊，這些攻擊會毒害 DNS 的 Cache，並將其用戶路由到惡意網站。 在 DNSSEC 等新協議被廣泛採用之前，DNS 解析器需要採取額外措施來確保其 Cache 的安全。 Google Public DNS 通過隨機化查詢名稱的大小寫，並在其 DNS 消息中包含其他數據，使攻擊者更難欺騙有效響應。

3. Get the results you expect with absolutely no redirection

    Google Public DNS 符合 DNS 標準，可為用戶提供他期望的準確響應，而不會執行任何可能妨礙用戶瀏覽體驗的阻止、過濾或重導向。

使用者透過 Google 提供的 DNS 服務，能夠擁有更安全更快的上網體驗。但同時 Google 可以收集到大量使用者在網路上的軌跡資料，藉以分析使用者行為，以應用於商業目的（如：投放廣告）。

## 什麼是資料庫的 lock？為什麼我們需要 lock？

讓多個交易可以在同一時間存取同一筆資料，稱之為「並行」。並行控制即是讓交易在並行的狀況下運作，而不會互相干擾，確保交易間的孤立性，以及提高交易的效率。

鎖定（Locking）是並行控制的技術之一，基本概念為：一旦某交易欲存取特定資料項目時，必須將此資料項目鎖定（lock），直到存取結束才能解除鎖定（unlock）。若其他交易欲存取被鎖定（lock）的資料項目，必須等待其解除鎖定（unlock）才可以執行。

並行控制的鎖定技術主要有以下三種：二元鎖定、共享互斥鎖定、兩階段鎖定

1. 二元鎖定（Binary locking）
  
    二元鎖定有兩種狀態：

      1. 鎖定（lock）：當資料項目 x 被鎖定時，即 `lock(x) = 1`，表示資料項目 x 不能被其他交易存取。

      2. 解除鎖定（unlock）：當資料項目 x 被解除鎖定時，即 `lock(x) = 0`，表示資料項目 x 可以被其他交易存取。

      3. 交易欲存取資料項目 x 時，必須將 x 鎖定（lock），直到存取結束才能解除 x 的鎖定（unlock）。

2. 共享互斥鎖定（Shared and Exclusive locking）

    由於二元鎖定技術過於嚴格，並行程度不佳。因此提出共享互斥鎖定（Shared and Exclusive locking），又稱為多元鎖定（Multi-mode locking），將鎖定分為共享鎖定（Shared lock）和互斥鎖定（Exclusive lock），較二元鎖定有彈性。

    共享互斥鎖定有兩種狀態：

      1. 共享鎖定（Shared lock）／讀取鎖定（Read-lock）：其他交易可以**讀取**被共享鎖定的資料項目。也就是說資料並不會被**讀壞**，所以可以一起讀取資料。

      2. 互斥鎖定（Exclusive lock）／寫入鎖定（Write-lock）：只有交易本身可以獨佔使用此資料項目，不允許其他交易讀取或寫入被互斥鎖定的資料項目。也就是說當有人在寫入資料時，若其他人要讀取資料，可能會讀到內容不正確的資料，稱為遺失更新（Lost Update）；或者有人也想寫入資料的話，就會產生競爭危害（Race Condition），產生系統錯誤。

      3. 解除鎖定（unlock）：表示資料項目可以被其他交易所存取。

3. 兩階段鎖定（Two phase locking, 2PL）

    2PL 限定每個交易中所有鎖定動作（lock），包括 Read-lock 與 Write-lock，必須在所有解除鎖定動作（unlock）之前。

    2PL 有兩種狀態：

      1. 成長階段（Growing）：成長階段允許加入新的資料項目鎖定（lock），但不允許解除任何鎖定（unlock）。

      2. 釋放階段（Releasing）：釋放階段允許解除現存鎖定（unlock），但不允許加入任何新的鎖定（lock）。

    遵守 2PL 且成功執行完畢的排程，保證皆為可序列化（serializable）排程。

## NoSQL 跟 SQL 的差別在哪裡？

NoSQL 是指非關聯式資料庫，也是 Not Only SQL 的縮寫，是對不同於傳統關聯式資料庫的 DBMS 之統稱。NoSQL 通常用於超大規模資料的儲存。

|          |    RDBMS    |    NoSQL    |
|----------|-------------|-------------|
|資料模型   |關聯式資料模型|非關聯式資料模型|
|資料格式   |適用於結構化資料|可以使用非結構化和不可預測的資料|
|資料處理   |高度組織化結構化資料，使用結構化查詢語言（SQL）|代表不僅僅是 SQL，還包含非宣告式查詢語言|
|資料查詢   |使用 SQL 存取資料|通常使用 API 存取資料|
|資料儲存   |資料和關係都儲存在單獨的表中|沒有預定義的模式，可以多種形式儲存資料：鍵 - 值對（Key - Value）、文檔、圖形等資料庫|
|交易       |嚴格的一致性，支援交易的 ACID 特性|不嚴格要求 ACID，很多 NoSQL 採最終一致性方案|
|效能       |取決於硬體架構與規格，可針對索引與查詢進行優化|高性能、高可用性，以及高擴展性

* 最終一致性（Eventual Consistency）是分散式運算系統裡，一種記憶體一致性的模型，它指的是當資料停止改變不再更新，最終都能取得已更新的資料，但不完全保證能立即取得已更新的資料。這種模型通常可以實現較高的可用性。最終一致性，通過樂觀複製，或稱延遲複製（lazy replication）實現。

    最終一致性牽涉到 CAP 定理，CAP 定理是指：對於一個分散式運算系統而言，不可能同時滿足一致性、可用性，和分隔容錯性這三個需求，最多只能同時滿足兩個需求。

  * 一致性（Consistency）：所有節點在**同一時刻**具有相同的資料

  * 可用性（Availability）：保證每個請求不論成功或失敗都有回應

  * 分隔容錯性（Partition tolerance）：系統中任意資料的遺失或失敗，不會影響系統地繼續運作

## 資料庫的 ACID 是什麼？

交易（Transaction）是指一個存取或改變資料庫內容的執行，為一件工作的邏輯單位。

交易有以下四大特性：ACID

* 基元性（Atomicity）

    一筆交易必須全部執行或全部不執行，即交易是一個不可再分割的完整個體。

  * 全部執行：指交易正確且正常完成，並確認將交易結果存入永久性的資料庫中。

  * 全部不執行：交易途中若發生錯誤、毀損等因素，導致交易無法完成時，必須將交易回復到執行前的原點。

* 一致性（Consistency）

    交易是從一個一致的狀態，變更到另一個一致的狀態。即資料庫狀態在交易前後，皆滿足資料庫所設定的限制，以及正確的結果。

* 孤立性（Isolation）

    交易期間對資料更新可讓其他交易看見，直到此交易委任（Commit）。

    交易期間的資料或中間結果不容許其他交易讀取或寫入，孤立性可依需求訂定不同層級的限制。

* 永久性（Durability）

    一旦交易委任（Commit），其對資料庫的更新動作永久有效，即使未來系統當機或損毀。
