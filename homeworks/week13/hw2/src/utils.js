/* eslint-disable */
export function appendDiscussionToDOM(container, discussion, isPrepend) {
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