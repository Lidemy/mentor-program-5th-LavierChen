/* eslint-disable */
const APIUrl = 'https://api.twitch.tv/kraken';
const clientId = '8fe9i00c0swhagvo69nqqz3uzensca';
const accept = 'application/vnd.twitchtv.v5+json';
const template = `
  <li class="stream">
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
  </li>
`;

// 透過 fetch 取得 top games 資料
function getGames() {
  // 回傳執行結果 Promise 給 await
  return fetch(`${APIUrl}/games/top?limit=5`, {
    method: 'GET', 
    headers: new Headers({
      'Client-ID': clientId, 
      'Accept': accept
    })
  })
    .then((result) => {
      return result.json();
    })
    .then((topGames) => {
      return topGames.top;
    })
    .catch((error) => console.log('error', error));
}

// 顯示 top games 清單於 navbar
function renderGames(topGames) {
  for (let channel of topGames) {
    const element = document.createElement('li');
    element.innerText = channel.game.name;
    document.querySelector('.nav_list').appendChild(element);
  }
  // 回傳第一個 top game 的遊戲名稱
  return topGames[0].game.name;
}

// 透過 fetch 取得 streams 資料
function getStreams(channel) {
  // 回傳執行結果 Promise 給 await
  return fetch(`${APIUrl}/streams?game=${encodeURIComponent(channel)}&limit=20`, {
    method: 'GET', 
    headers: new Headers({
      'Client-ID': clientId, 
      'Accept': accept
    })
  })
    .then((result) => {
      return result.json();
    })
    .then((topStreams) => {
      return topStreams.streams;
    })
    .catch((error) => console.log('error', error));
}

// 顯示 top games 的 title 與 streams
function renderStreams(topStreams) {
  document.querySelector('.channel_title h1').innerText = topStreams[0].game;
  document.querySelector('.channel_container').innerHTML = '';
  for (let stream of topStreams) {
    appendStreams(stream);
  }
  addEmptyBlock();
  addEmptyBlock();
}

// 將對應 title 的 streams 逐一顯示
function appendStreams(stream) {
  const element = document.createElement('li');
  const {
    preview, 
    channel: { logo, status, display_name }
  } = stream;
  const content = template
    .replace('$preview', preview.large)
    .replace('$logo', logo)
    .replace('$title', status)
    .replace('$owner', display_name);
  document.querySelector('.channel_container').appendChild(element);
  element.outerHTML = content;
}

// 只有寬度沒有內容的區塊，調整頁面排版
function addEmptyBlock() {
  const block = document.createElement('li');
  block.classList.add('stream-empty');
  document.querySelector('.channel_container').appendChild(block);
}

// 初始化
async function init() {
  const topGames = await getGames();
  const firstGame = renderGames(topGames);
  const firstGameStreams = await getStreams(firstGame);
  renderStreams(firstGameStreams);
}

// 切換頻道
async function changeChannel(channel) {
  const Streams = await getStreams(channel);
  renderStreams(Streams);
}

document.addEventListener('DOMContentLoaded', () => {
  init();
  // 監聽 navbar - click 事件：切換頻道
  document.querySelector('.nav_list').addEventListener('click', (e) => {
    if (e.target.tagName.toLowerCase() === 'li') {
      const channel = e.target.innerText;
      changeChannel(channel);
    }
  });
});