function theme() {
  var body = document.body;
  body.classList.toggle("light-mode");
  if (body.classList.contains('light-mode') == true) {
    localStorage.setItem('theme', 'light');
  } else {
    localStorage.setItem('theme', 'dark');
  }
}