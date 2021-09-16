/* eslint-disable */
import $ from 'jquery';

export function getDiscussions(APIUrl, siteKey, cursor, cb) {
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

export function addDiscussion(APIUrl, data, cb){
  $.ajax({
    method: 'POST',
    url: `${APIUrl}/api_add_discussion.php`,
    data: data
  })
  .done((data) => cb(data))
  .fail(err => console.log(err));
}