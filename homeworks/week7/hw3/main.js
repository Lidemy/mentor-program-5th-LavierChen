/* eslint-disable no-useless-return */
// 點擊新增
document.querySelector('.input_create').addEventListener('click', (e) => {
  createTodo()
})

// 按 Enter 新增
document.querySelector('.todo_input').addEventListener('keydown', (e) => {
  if (e.keyCode === 13) {
    createTodo()
  }
})

document.querySelector('.todo_list').addEventListener('click', (e) => {
  const { target } = e
  // 刪除 todo
  if (target.classList.contains('list_delete')) {
    target.parentNode.remove()
    return
  }
  // 標記／取消標記 todo
  if (target.classList.contains('list_check')) {
    target.parentNode.classList.toggle('list_done')
  }
})

function createTodo() {
  const todo = document.querySelector('.input_block').value
  if (todo.length === 0) return
  const createItem = document.createElement('li')
  createItem.classList.add('list_item')
  createItem.innerHTML = `
    <label class="list_content">
      <input class="list_check" type="checkbox">
      <p>${escapeHTML(todo)}</p>
    </label>
    <button class="list_delete">Delete</button>
  `
  document.querySelector('.todo_list').appendChild(createItem)
  // 清空輸入欄
  document.querySelector('.input_block').value = ''
}

// 處理跳脫字元
function escapeHTML(unsafe) {
  return unsafe
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;')
}
