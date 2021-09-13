/* eslint-disable*/

const APIUrl = 'https://mentor-program.co/mtr04group6/lavier/week12/todoList';
const template = `
  <div class="list-group-item list-group-item-action d-flex justify-content-between todo_item $state">
    <div class="todo_item_left d-flex">
      <button type="button" class="btn btn-secondary btn-sm me-3 btn_check">
        <i class="bi bi-check-lg"></i>
      </button>
      <h5 class=" my-auto todo_content">$content</h5>
    </div>
    <div class="todo_item_right">
      <button class="btn btn-primary btn-sm me-3 btn_update" data-toggle="modal" data-target="#update-content">
        <i class="bi bi-pencil-fill"></i>
      </button>
      <button class="btn btn-danger btn-sm btn_delete">
        <i class="bi bi-trash-fill"></i>
      </button>
    </div>
  </div>
`;
const alert = `
  <div class="alert alert-danger alert-dismissible fade show mt-3 mb-0" role="alert">
    <i class="bi bi-exclamation-circle-fill"></i>&nbsp;&nbsp;
    <strong>Error!</strong> Please input something needs to be done.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
`;

$(document).ready(() => {
  const id = getIdFromUrl();

  // if has id then render the todes of its own
  if (id) {
    $.ajax({
      type: 'GET', 
      url: `${APIUrl}/api_read_todos.php?id=${id}`
    })
    .done((data) => {
      const todos = JSON.parse(data.todos);
      for (let todo of todos) {
        $('.todo_list').append(template
          .replace('$state', todo.state)
          .replace('$content', todo.content)
        );
      }
      $('.todo_item').each((i, todoItem) => {
        if ($(todoItem).hasClass('complete')) {
          $(todoItem).find('.btn_check').removeClass('btn-secondary').addClass('btn-success');
          $(todoItem).find('.todo_content').css('text-decoration', 'line-through').addClass('text-muted');
          $(todoItem).find('.btn_update').hide();
        }
      })
    });
  }

  // create todo by click button
  $('.btn_create').on('click', () => {
    createTodo();
  });

  // create todo by press enter
  $('.todo_input').on('keydown', (e) => {
    if (e.key === 'Enter') {
      createTodo();
    }
  });

  // update todo
  $('.todo_list').on('click', '.btn_update', (e) => {
    const todoItem = $(e.target).closest('.todo_item');
    const todoItemContent = todoItem.find('.todo_content');
    $('.update_todo_input').val(todoItemContent.text());
    $('.btn_confirm').on('click', () => {
      const newContent = $('.update_todo_input').val();
      if (newContent === '') {
        return;
      } else {
        todoItemContent.text(newContent);
      }
      $('.btn_confirm').off();
    });
  });

  // delete todo
  $('.todo_list').on('click', '.btn_delete', (e) => {
    $(e.target).closest('.todo_item').remove();
  });

  // check todo
  $('.todo_list').on('click', '.btn_check', (e) => {
    const todoItem = $(e.target).closest('.todo_item');
    const todoItemContent = todoItem.find('.todo_content');
    if (todoItem.hasClass('complete')) {
      todoItem.find('.btn_check').removeClass('btn-success').addClass('btn-secondary');
      todoItemContent.css('text-decoration', 'none').removeClass('text-muted');
      todoItem.find('.btn_update').show();
      todoItem.removeClass('complete');
    } else {
      todoItem.find('.btn_check').removeClass('btn-secondary').addClass('btn-success');
      todoItemContent.css('text-decoration', 'line-through').addClass('text-muted');
      todoItem.find('.btn_update').hide();
      todoItem.addClass('complete');
    }
  });

  // select status of todos
  $('.btn-group').on('click', 'input[value="all"]', () => {
    $('.todo_item').addClass('d-flex').show();
  });

  $('.btn-group').on('click', 'input[value="active"]', () => {
    $('.todo_item').removeClass('d-flex').hide();
    $('.todo_item:not(.complete)').addClass('d-flex').show();
  });

  $('.btn-group').on('click', 'input[value="complete"]', () => {
    $('.todo_item').removeClass('d-flex').hide();
    $('.todo_item.complete').addClass('d-flex').show();
  });

  // close alert message
  $('main').on('click', '.btn-close', (e) => {
    const alert = $(e.target).closest('.alert');
    alert.fadeOut();
  });
  
  //clean all todos
  $('.btn_clean').on('click', () => {
    $('.todo_list').empty();
  });

  // save todos
  $('.btn_save').on('click', () => {
    var beforeTodos = [];
    $('.todo_item').each((i, todoItem) => {
      var todo = {};
      todo.content = $(todoItem).find('.todo_content').text();
      if ($(todoItem).hasClass('complete')) {
        todo.state = 'complete';
      } else {
        todo.state = '';
      }
      beforeTodos.push(todo);
    })
    const afterTodos = JSON.stringify(beforeTodos);
    const todoList = {id: id, todos: afterTodos};

    $.ajax({
      type: 'POST',
      url: `${APIUrl}/api_add_todos.php`,
      data: todoList,
    })
    .done((data) => {
      const id = data.id;
      $('.save_title').text('儲存成功');
      if (data.has_exist) {
        $('.save_information').text(`用戶 ${id}，您的代辦事項清單已儲存成功。`);
      } else {
        $('.save_information').text(`
          新用戶您好，請記下您的識別代碼 ${id}。日後若要讀取代辦事項清單，請在網址列後輸入 id={識別代碼} 即可讀取成功。
        `)
      }
      $('.btn_finish').click(() => {
        window.location = 'index.html?id=' + id;
      });
    });
  });
});

function getIdFromUrl() {
  const currentUrl = new URL(location.href);
  const urlParams = currentUrl.searchParams;
  const id = urlParams.get('id');
  return id;
}

function createTodo() {
  const content = $('.todo_input').val();
  $('.todo_input').val('');

  if(content === '') {
    $('.alert').remove();
    $('main').prepend(alert);
    return;
  } else {
    $('.todo_list').append(template
      .replace('$state', '')
      .replace('$content', content)
    );
    $('.alert').fadeOut();
  }
}

function escapeHtml(unsafe) {
  return unsafe
  .replace(/&/g, '&amp;')
  .replace(/</g, '&lt;')
  .replace(/>/g, '&gt;')
  .replace(/"/g, '&quot;')
  .replace(/'/g, '&#039;');
}
