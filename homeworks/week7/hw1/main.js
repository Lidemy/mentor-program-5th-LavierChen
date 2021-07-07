const form = document.querySelector('.form')
form.addEventListener('submit', (e) => {
  e.preventDefault()
  const requireItems = document.querySelectorAll('.require')
  const info = {}
  let hasError = false

  for (const item of requireItems) {
    const text = item.querySelector('input[type="text"]')
    const radios = item.querySelectorAll('input[type="radio"]')
    let isValid = true

    if (text) {
      if (text.value) {
        info[text.name] = text.value
      } else {
        isValid = false
      }
    } else if (radios.length) {
      isValid = [...radios].some((radio) => radio.checked)
      if (isValid) {
        const choice = item.querySelector('input[type="radio"]:checked')
        info[choice.name] = choice.value
      }
    } else {
      continue
    }

    if (isValid) {
      item.classList.add('hide_error')
    } else {
      item.classList.remove('hide_error')
      hasError = true
    }
  }

  const suggestion = document.querySelector('.suggestion')
  const suggestionText = suggestion.querySelector('input[type="text"]')
  if (suggestionText.value) {
    info[suggestionText.name] = suggestionText.value
  }

  if (!hasError) {
    alert(JSON.stringify(info))
  }
})
