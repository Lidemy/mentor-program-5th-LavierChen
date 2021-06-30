document.querySelector('.FAQ').addEventListener('click', (e) => {
  const isValid = e.target.closest('.FAQ_block')
  if (isValid) {
    isValid.classList.toggle('hide_content')
  }
})
