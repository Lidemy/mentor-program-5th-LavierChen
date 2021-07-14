const apiUrl = 'https://dvwhnbka7d.execute-api.us-east-1.amazonaws.com/default/lottery'
const errorMessage = '系統不穩定，請再試一次'
const errorParsing = '資料錯誤，請再試一次'

// 監聽抽獎頁面按鈕 click 事件，處理回傳資料
document.querySelector('.lottery_button').addEventListener('click', (e) => {
  getPrize((error, data) => {
    // 資料有誤，跳出提示視窗輸出錯誤並重新整理頁面
    if (error) {
      alert(error)
      window.location.reload()
    }
    // 抽獎獎項內容
    const prizes = {
      FIRST: {
        className: 'first-prize',
        title: '恭喜你中頭獎了！日本東京來回雙人遊！'
      },
      SECOND: {
        className: 'second-prize',
        title: '二獎！90 吋電視一台！'
      },
      THIRD: {
        className: 'third-prize',
        title: '恭喜你抽中三獎：知名 YouTuber 簽名握手會入場券一張，bang！'
      },
      NONE: {
        className: 'none-prize',
        title: '銘謝惠顧'
      }
    }
    const { className, title } = prizes[data.prize]
    document.querySelector('.lottery').classList.add(className)
    document.querySelector('.lottery_result-prize').innerText = title
    document.querySelector('.lottery_info').classList.add('hidden')
    document.querySelector('.lottery_result').classList.remove('hidden')
  })
})

// 監聽結果頁面按鈕 click 事件，重新整理頁面
document.querySelector('.lottery_result-btn').addEventListener('click', (e) => {
  if (e.target) {
    window.location.reload()
  }
})

// 串接抽獎 API
function getPrize(callback) {
  const request = new XMLHttpRequest()
  request.open('GET', apiUrl, true)
  request.onload = function() {
    // 判斷 HTTP 狀態
    if (request.status >= 200 && request.status < 400) {
      const response = request.responseText
      // 判斷回傳資料是否為 JSON 格式
      let data
      try {
        data = JSON.parse(response)
      } catch (error) {
        callback(errorParsing)
        return
      }
      // 判斷回傳資料是否有 prize
      if (!data.prize) {
        callback(errorMessage)
        return
      }
      // 回傳資料的格式與內容皆正確
      callback(null, data)
    } else {
      callback(errorMessage)
    }
  }
  request.onerror = function() {
    callback(errorMessage)
  }
  request.send()
}
