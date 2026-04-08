document.addEventListener('DOMContentLoaded', function () {
  const forms = document.querySelectorAll('.auth-form');

  forms.forEach(function (form) {
    const feedback = form.querySelector('.form-feedback');

    form.addEventListener('submit', function (event) {
      event.preventDefault();

      if (!form.reportValidity()) {
        if (feedback) {
          feedback.hidden = true;
          feedback.textContent = '';
        }
        return;
      }

      if (feedback) {
        feedback.textContent = form.dataset.success || 'Fields validated successfully.';
        feedback.hidden = false;
      }
    });
  });
});
