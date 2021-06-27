## 什麼是 DOM？

DOM（Document Object Model，文件物件模型）是「瀏覽器」提供用來和「程式語言」溝通的橋樑，將 HTML 文件中的每個元素，以樹狀的結構來表示。透過 DOM 提供的 API，利用 JavaScript 即可選取 HTML 中的任何元素，並進行改變結構、樣式、內容等操作。

![DOM tree](https://ithelp.ithome.com.tw/upload/images/20171214/20065504rULoAa69HV.png)

## 事件傳遞機制的順序是什麼；什麼是冒泡，什麼又是捕獲？

DOM 事件傳遞機制分為 3 個階段：捕獲階段（capture phase）、目標（target）、冒泡階段（bubbling phase）。

![event flow](https://www.w3.org/TR/DOM-Level-3-Events/images/eventflow.svg)

當觸發事件時，會從最上層的根節點 window 向下傳遞事件到 target，此過程為「捕獲階段」。

當事件傳遞到 target，屬於獨立的狀態，既不屬於捕獲階段，也不屬於冒泡階段。

事件從 target 一層層向上傳回 window，此過程為「冒泡階段」。

## 什麼是 event delegation，為什麼我們需要它？

假設同時有很多個 DOM 元素都有相同的 `eventListener`，與其在每個元素上「個別」加上 `eventListener`，利用 **event bubbling** 的特性，統一在他們的**父元素**（代理人）加上 `eventListener`，再對底下的子元素進行處理。

event delegation 不僅能簡化程式碼、提高效率，也能處理「動態新增」的問題。

``` javascript
// 取得容器
var myList = document.getElementById('myList');

// 讓外層 myList 來監聽 click 事件
myList.addEventListener('click', function(e){

  // 判斷目標元素若是 li 則執行 console.log
  if( e.target.tagName.toLowerCase() === 'li' ){
    console.log(e.target.textContent);
  }

}, false);


// 建立新的 <li> 元素
var newList = document.createElement('li');

// 建立 textNode 文字節點
var textNode = document.createTextNode("Hello world!");

// 透過 appendChild 將 textNode 加入至 newList
newList.appendChild(textNode);

// 透過 appendChild 將 newList 加入至 myList
myList.appendChild(newList);

```

後續加上的 `newList` 也會有 `click` 的效果，無需另外再去綁定 `click` 事件。

## event.preventDefault() 跟 event.stopPropagation() 差在哪裡，可以舉個範例嗎？

### `event.preventDefault()`：阻止事件的預設行為

`event.preventDefault()` 阻止 DOM 元素使用此方法（包含其子元素）的預設行為，但是**不影響** DOM 事件的傳遞。

舉例：

1. 送出 form 表單：預設行為是**送出表單**，若在事件監聽加上 `preventDefault()`，就會阻止表單送出。

2. 點擊 `<a>` 連結：預設行為是**跳轉網址**，若在事件監聽加上 `preventDefault()`，就會阻止轉址。

### `event.stopPropagation()`：阻止事件傳遞

`event.stopPropagation()` 阻止 DOM 元素在使用此方法後繼續傳遞，但是不會影響已接收到事件的元素預設行為。

舉例：

``` javascript
window.addEventListener('click', function(e) {
  e.stopPropagation()
}, true)
```

在 `window` 的捕獲階段設置 `event.stopPropagation()`，會阻止後續事件傳遞，造成所有 `click` 事件均失效。
