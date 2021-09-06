/* eslint-disable */
import $ from 'jquery';
import { getDiscussions, addDiscussion } from './api';
import { alert, getForm } from './templates';
import { appendDiscussionToDOM } from './utils';

export function init(options) {
  // get informations from options
  const { APIUrl, siteKey, containerSelector } = options;
  // set elements classname with siteKey to identify message board
  const alertClassName = `${siteKey}-alert`;
  const alertSelector = `.${alertClassName}`;
  const formClassName = `${siteKey}-add-discussion-from`;
  const formSelector = `.${formClassName}`;
  const discussionsClassName = `${siteKey}-discussions`;
  const discussionsSelector = `.${discussionsClassName}`;
  const btnClassName = `${siteKey}-button-container`;
  const btnSelector = `.${btnClassName}`

  // dynamically add DOM elements in containerSelector
  const containerElement = $(containerSelector);
  containerElement.append(getForm(formClassName, discussionsClassName, btnClassName));

  // dynamically add discussions in discussionsDOM
  const discussionsDOM = $(discussionsSelector);
  let cursor = null;
  getImmediateDiscussions(discussionsDOM);

  // add discussion and store in database
  $(formSelector).submit((e) => {
    e.preventDefault();
    const nickname = $(`${formSelector} input[name=nickname]`).val();
    const content = $(`${formSelector} textarea[name=content]`).val();

    if (nickname === '' || content === '') {
      $(alertSelector).remove();
      containerElement.prepend(alert(alertClassName));
      return;
    }

    const createDiscussion = {
      site_key: siteKey,
      nickname,
      content,
    };

    addDiscussion(APIUrl, createDiscussion, (data) => {
      createDiscussion.create_at = data.create_at;
      appendDiscussionToDOM(discussionsDOM, createDiscussion, true);
      $(`${formSelector} .form-control`).val('');
      $(alertSelector).remove();
    });
  })
  
  // click button to load more discussions
  $(`${btnSelector} .load_more`).on('click', () => {
    getImmediateDiscussions(discussionsDOM);
  });

  // close alert
  containerElement.on('click', '.btn-close', (e) => {
    const alert = $(e.target).closest('.alert');
    alert.fadeOut();
  }); 

  /* functions */
  function getImmediateDiscussions(discussionsDOM) {
    getDiscussions(APIUrl, siteKey, cursor, (data) => {
      if (!data.ok) {
        return;
      } else {
        const discussions = data.discussions;
        if (discussions.length < 6) {
          for (let discussion of discussions) {
            appendDiscussionToDOM(discussionsDOM, discussion);
          }
          $(`${btnSelector} .load_more`).hide();
        } else {
          for (let i = 0; i < discussions.length - 1 ; i++) {
            appendDiscussionToDOM(discussionsDOM, discussions[i]);
          }
          cursor = discussions[(discussions.length - 1) - 1].id;
        }
      }
    });
  }
}