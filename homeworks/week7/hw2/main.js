document.querySelector('.FAQ').addEventListener('click', (e) => {
  const isValid = e.target.closest('.FAQ_question')
  if (isValid) {
    isValid.parentNode.classList.toggle('hide_content')
  }
})
