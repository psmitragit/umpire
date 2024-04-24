// flag
function toggleDropdown() {
    var dropdown = document.getElementById('dropdown');
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
  }

  function changeLanguage(language, flagUrl) {
    document.getElementById('selected-language').textContent = language;
    document.getElementById('selected-flag').src = flagUrl;
    toggleDropdown();
  }
//   end 

