/* eslint-disable */
export function alert(alertClassName) {
  return `
    <div class="${alertClassName} alert alert-danger alert-dismissible fade show mt-3 mb-0" role="alert">
      <i class="bi bi-exclamation-circle-fill"></i>&nbsp;&nbsp;
      <strong>Submit Error!</strong> Please confirm that the form is filled in completely.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  `
}

export function getForm(formClassName, discussionsClassName, btnClassName) {
  return `
    <div>
      <form class="${formClassName}">
        <div class="form-group">
          <label for="nickname" class="form-label mt-3">Nickname</label>
          <input type="text" class="form-control" id="nickname" name="nickname" aria-describedby="nicknameHelp" placeholder="Please input nickname">
          <small id="nicknameHelp" class="form-text text-muted">Your nickname may appear around Message Board.</small>
        </div>
        <div class="form-group">
          <label for="content" class="form-label mt-3">Discussion</label>
          <textarea class="form-control" id="content" name="content" rows="3" placeholder="Please leave your discussion here"></textarea>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
      </form>
      <div class="${discussionsClassName} row my-2"></div>
      <div class="${btnClassName} d-flex justify-content-center">
        <button type="button" class="load_more btn btn-primary btn-lg mb-3">Load More</button>
      </div>
    </div>  
  `
}