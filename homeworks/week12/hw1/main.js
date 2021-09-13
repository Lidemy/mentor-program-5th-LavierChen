/* eslint-disable*/

const APIUrl = 'https://mentor-program.co/mtr04group6/lavier/week12/messageBoard';
const siteKey = 'lavier';
let cursor = '';

$(document).ready(() => {
  const discussionsDOM = $('.discussions');
  getDiscussions(discussionsDOM);

  const form = $('.add_discussion_form');
  form.submit((e) => {
    e.preventDefault();
    addDiscussion(discussionsDOM);
  });

  $('main').on('click', '.btn-close', (e) => {
    const alert = $(e.target).closest('.alert');
    alert.fadeOut();
  }); 

  $('.load_more').on('click', () => {
    getDiscussions(discussionsDOM);
  });
});

function getDiscussions(discussionsDOM) {
  getDiscussionsAPI(cursor, (data) => {
    if (!data.ok) {
      alert(data.message);
      return;
    } else {
      const discussions = data.discussions;
      if (discussions.length < 6) {
        for (let discussion of discussions) {
          appendDiscussionToDOM(discussionsDOM, discussion);
        }
        $('.load_more').hide();
      } else {
        for (let i = 0; i < discussions.length - 1 ; i++) {
          appendDiscussionToDOM(discussionsDOM, discussions[i]);
        }
        cursor = discussions[(discussions.length - 1) - 1].id;
      }
    }
  });
} 

function getDiscussionsAPI(cursor, cb) {
  $.ajax({
    method: 'GET', 
    url: `${APIUrl}/api_read_discussions.php`, 
    data: {
      site_key: siteKey, 
      cursor: cursor
    }
  })
    .done(data => cb(data))
    .fail(err => console.log(err));
}

function addDiscussion(discussionsDOM) {
  const nickname = $('input[name=nickname]').val();
  const content = $('textarea[name=content]').val();
  const alert = `
    <div class="alert alert-danger alert-dismissible fade show mt-3 mb-0" role="alert">
      <i class="bi bi-exclamation-circle-fill"></i>&nbsp;&nbsp;
      <strong>Submit Error!</strong> Please confirm that the form is filled in completely.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `;

  if (nickname === '' || content === '') {
    $('.alert').remove();
    $('main').prepend(alert);
    return;
  }

  const createDiscussion = {
    site_key: siteKey,
    nickname,
    content,
  };

  $.ajax({
    method: 'POST',
    url: `${APIUrl}/api_add_discussion.php`,
    data: createDiscussion,
  })
  .done((data) => {
    createDiscussion.create_at = data.create_at;
    appendDiscussionToDOM(discussionsDOM, createDiscussion, true);
    $('.form-control').val('');
    $('.alert').remove();
  })
  .fail(err => console.log(err));
}

function appendDiscussionToDOM(container, discussion, isPrepend) {
  const html = `
    <div class="col-md-6 col-lg-4">
      <div class="card border-secondary my-3" style="max-width: 100%;">
        <div class="card-header create_at">${escapeHtml(discussion.create_at)}</div>
        <div class="card-body">
          <h4 class="card-title nickname text-primary fw-bolder">
            <i class="bi bi-person-circle" aria-hidden="true"></i>&nbsp;&nbsp;
            ${escapeHtml(discussion.nickname)}
          </h4>
          <p class="card-text content">${escapeHtml(discussion.content)}</p>
        </div>
      </div>
    </div>
  `;
  if (isPrepend) {
    container.prepend(html);
  } else {
    container.append(html);
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
