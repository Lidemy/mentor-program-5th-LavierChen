const API_URL = 'https://api.twitch.tv/kraken'
const CLIENT_ID = '8fe9i00c0swhagvo69nqqz3uzensca'
const ACCEPT = 'application/vnd.twitchtv.v5+json'
const template = `<li class="stream">
      <img class="stream_preview" src="$preview">
      <div class="stream_info">
        <div class="stream_avatar">
          <img src="$logo">
        </div>
        <div class="stream_description">
          <div class="stream_title">$title</div>
          <div class="stream_owner">$owner</div>
        </div>
      </div>
    </li>`

// 顯示 top games 清單於 navbar
getGames((items) => {
  for (const item of items) {
    const element = document.createElement('li')
    element.innerText = item.game.name
    document.querySelector('.nav_list').appendChild(element)
  }

  // 顯示第一個 top game 的 title 與 streams
  displayChannel(items[0].game.name)
})

// 監聽 navbar - click 事件：切換頻道
document.querySelector('.nav_list').addEventListener('click', (e) => {
  if (e.target.tagName.toLowerCase() === 'li') {
    const channel = e.target.innerText
    displayChannel(channel)
  }
})

/* ----- functions ----- */

// 取得 top games 資料
function getGames(callback) {
  const request = new XMLHttpRequest()
  request.open('GET', `${API_URL}/games/top?limit=5`, true)
  request.setRequestHeader('Client-ID', CLIENT_ID)
  request.setRequestHeader('Accept', ACCEPT)
  request.onload = function() {
    if (request.status >= 200 && request.status < 400) {
      callback(JSON.parse(request.responseText).top)
    }
  }
  request.send()
}

// 取得 streams 資料
function getStreams(channel, callback) {
  const request = new XMLHttpRequest()
  request.open('GET', `${API_URL}/streams?game=${encodeURIComponent(channel)}&limit=20`, true)
  request.setRequestHeader('Client-ID', CLIENT_ID)
  request.setRequestHeader('Accept', ACCEPT)
  request.onload = function() {
    if (request.status >= 200 && request.status < 400) {
      callback(JSON.parse(request.responseText).streams)
    }
  }
  request.send()
}

// 顯示 top game 的 title 與 streams
function displayChannel(channel) {
  document.querySelector('.channel_title h1').innerText = channel
  document.querySelector('.channel_container').innerHTML = ''
  getStreams(channel, (streams) => {
    for (const stream of streams) {
      appendStreams(stream)
    }
    addEmptyBlock()
    addEmptyBlock()
  })
}

// 將對應 title 的 streams 逐一顯示
function appendStreams(stream) {
  const element = document.createElement('li')
  const content = template
    .replace('$preview', stream.preview.large)
    .replace('$logo', stream.channel.logo)
    .replace('$title', stream.channel.status)
    .replace('$owner', stream.channel.display_name)
  document.querySelector('.channel_container').appendChild(element)
  element.outerHTML = content
}

// 只有寬度沒有內容的區塊，調整頁面排版
function addEmptyBlock() {
  const block = document.createElement('li')
  block.classList.add('stream-empty')
  document.querySelector('.channel_container').appendChild(block)
}
