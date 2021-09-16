## Webpack 是做什麼用的？可以不用它嗎？

> 它是一個「打包工具」。將眾多模組與資源打包成一包檔案，並編譯我們需要預先處理的內容，變成瀏覽器看得懂的東西，讓我們可以上傳到伺服器。

在 ES6 規範的標準化模組出現前，JavaScript 並沒有一個官方的模組規範，node.js 使用的 CommonJS（`require` 和 `module.exports`）模組規範在瀏覽器上也不支援，所以透過 Webpack 把寫好的 JavaScript 模組打包成一包檔案，讓 JavaScript 可以在瀏覽器上運行。

即使後來有了 ES6 規範的標準化模組（`import` 和 `export`），在大部分的瀏覽器都可以支援，但還是需要考量版本較舊的瀏覽器，舉凡 IE 是不支援 ES6 規範的。

就算不考慮瀏覽器的支援度，在瀏覽器上使用 npm 模組還有不易維護的問題。在 Node.js 上可以直接把 `node_modules` 裡下載好的第三方套件 `require` 進來。但在瀏覽器上，如果不使用 Webpack 打包，雖然可以把整個 `node_modules` 資料夾上傳上去，但資料夾的內容可能非常龐大；此外，`import` 的路徑就要是完整的路徑，這樣一來就不容易維護，一旦入口點有變更，所有的 import 也要一起改寫。

使用 Webpack 的好處：

* 模組化的管理程式碼
* 透過不同的 loader 將資源轉換並載入
  * 不論是 CSS、SASS、圖片都可以被視為一個資源模組，透過loader 把各種資源載入到 Webpack 打包成一個 JavaScript 檔案。
* 產生 source maps 檔案，方便 debug
  * source map 是一個儲存了原始碼與編譯後程式碼的對應關係之檔案，在開啟 Devtool 時，能讓瀏覽器透過載入 source map 的方式，協助定位原始碼位置，方便下中斷點除錯。

## gulp 跟 webpack 有什麼不一樣？

gulp

* 是一套任務管理工具（task manager）
* 目的：為了將工作流程自動化，也就是整合前端開發環境。藉由簡化工作量，可讓開發者將重點放在功能的開發上。
* 功能：提供自訂任務流程，例如：
    1. 讓網頁自動重新整理
    2. 編譯 SASS、JS
    3. 壓縮 CSS、JS、圖檔等

Webpack

* 是一套模組整合工具（module bundler）
* 目的：為了要讓瀏覽器能夠支援 module
* 功能：將各種資源模組化
  * Webpack 把 modules 的概念延伸至更廣的範圍，各種資源都可以視為一個 module，所以我們也可以利用 Webpack 打包圖片或 CSS。

簡單來說就是，gulp 可以做各種 tasks 但做不到打包，Webpack 能做把各種資源打包，但做不到很多 gulp 才能做到的 tasks。

## CSS Selector 權重的計算方式為何？

### 為什麼需要了解選擇器的權重（Specificity）？

因為它決定了「對於同一個元素來說，哪條 CSS 規則會生效」這件事，基本上只有在多條規則都對同一個元素聲明相應樣式時，才會遇到權重計算的問題，它決定了元素最終會被什麼樣式渲染。

當選擇器作用在**同一元素**上時：

* 兩個權重不同：權重值高的規則生效
* 兩個權重相同：後面覆蓋前面

權重由高到低如下：

> !important > inline style > id > class > tag > *

各類選擇器：

* !important：權重最高，但在實際開發過程，幾乎不會使用 !important 來覆蓋其他規則

* inline style 行內樣式：直接在 HTML 寫的行內樣式
  * 權重為 1-0-0-0

* id 選擇器（`#idName`）
  * 權重為 0-1-0-0

* class 選擇器（`.className`）、attribute 選擇器（`input[type="text"]`）、pseudo class 選擇器（`:hover`）
  * 權重為 0-0-1-0

* tag 選擇器（`h1`）、pseudo elements 選擇器（`::before`）
  * 權重為 0-0-0-1

* 萬用選擇器（*）：選擇所有元素
  * 預設為 0-0-0-0
