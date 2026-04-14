document.querySelectorAll('form.needs-validation').forEach((form) => {
  form.addEventListener('submit', (e) => {
    if (!form.checkValidity()) {
      e.preventDefault();
      e.stopPropagation();
    }
    form.classList.add('was-validated');
  });
});

document.querySelectorAll('.alert').forEach((el) => {
  if (!el.classList.contains('alert-dismissible')) {
    return;
  }
  setTimeout(() => {
    const inst = window.bootstrap?.Alert?.getOrCreateInstance(el);
    inst?.close();
  }, 6000);
});
