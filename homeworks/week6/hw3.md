## 請找出三個課程裡面沒提到的 HTML 標籤並一一說明作用。

1. `<code>`

   `<code>` 用來顯示程式碼的內容，瀏覽器預設會以 monospace 等寬字型來顯示 `<code>` 中的內容。

    ```html
    <p> Regular text. <code> This is code. </code> Regular text. </p>
    ```

2. `<sup>` 、 `<sub>`

   `<sup>` 定義上標文本，常用來表示日期字尾、次方數。  
   `<sub>` 定義下標文本，常用在註解或化學式。

    ```html
    <p>Today is May 27<sup>th</sup>.</p>
    <p>H<sub>2</sub>O</p>
    ```

3. `<audio>`

   `<audio>` 用於在文檔中嵌入音樂串流。`<audio>` 可以包含一個或多個音樂串流資源，使用 `src` 屬性連接音源的位址 (URL)。

   ```html
   <audio src="example.mp3" autoplay controls></audio>
   ```

## 請問什麼是盒模型（box model）

當網頁架構寫好以後，可以使用 CSS 將網頁架構加上樣式和排版，包含大小、寬高、顏色、邊框、間距、排列方式……等。

使用 Chrome DevTool，選取網頁中任何一個 HTML 元素，會發現它們在瀏覽器中都被視為一個盒模型（box model）， 從內到外包含內容 **content**、內邊距 **padding**、框線 **border**、外邊距 **margin**。

HTML 內容（文字、圖片等）會放置在 content 中，border 以內包含 padding、content，是盒模型的內部。margin 則是盒模型的外部距離。padding 是 border 與 content 間的距離，margin 則是 border 與其他元素或網頁邊界的距離。

## 請問 display: inline, block 跟 inline-block 的差別是什麼？

![display 屬性概要](https://saruwakakun.com/wp-content/uploads/2017/01/bdr44405-O4HRWW-07-min.png)

display 是 CSS 中用於**控制排版**的屬性。每個 HTML 元素都有預設的 display，大部分的元素可分為 block（區塊元素）和 inline（行內元素）兩類：

1. block

    * 橫向佔滿整列
    * 可以設定 height（會對其它元素造成影響）
    * 可以設定 padding 與 margin
    * 無法設定內部文字橫、縱向對齊

2. inline

    * 橫向並排
    * 無法設定 height
    * 可以設定**左右** padding 與 margin  
      無法設定**上下** margin，設定**上下** padding 不會有任何改變
    * 可以設定內部文字橫向、縱向對齊

3. inline-block

    * 以 inline 方式呈現：可以水平排列
    * 擁有 block 的屬性：可以設定元素的height／margin／padding

## 請問 position: static, relative, absolute 跟 fixed 的差別是什麼？

position 屬性可以用來指定元素定位方式，以進行版面配置。

![position 屬性概要](https://user-images.githubusercontent.com/77997842/119253262-01607100-bbe3-11eb-8eb7-f7016faa8193.jpg)

1. static:

    * 為所有元素的 position 預設值
    * 自動排版在頁面中
    * 無法設定 top、bottom、left、right
    * 無法做為 relative、absolute 元素的基準點

2. relative

    * 元素「相對」於它原本顯示的位置擺放
    * 元素原本所佔的空間仍會保留（表示偏離）
    * 以四個邊為基準點，設定 top、bottom、left、right

3. absolute

    * 跳脫排版流，不會影響頁面其他元素
    * 會往上找第一個 position 不是 static 的元素，以它為定位基準。如果完全沒有上一層可定位元素，就會以 body 為定位基準（整個視窗左上角為起點）
    * 以四個邊為基準點，設定 top、bottom、left、right

4. fixed

    * 跳脫排版流，不會影響頁面其他元素
    * 以瀏覽器視窗（左上角）做為定位基準
    * 將元素固定在瀏覽器視窗的相對位置，捲動頁面時仍會在固定位置
    * 以四個邊為基準點設定 top、bottom、left、right
