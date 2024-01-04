document.addEventListener('DOMContentLoaded', function () {
  var closeBtns = document.querySelectorAll('.close');

  function openModal(modalId) {
    var modal = document.getElementById(modalId);
    modal.style.display = 'block';
  }

  function closeModal() {
    var modal = this.closest('.modal');
    modal.style.display = 'none';
  }

  var tableRows = document.querySelectorAll('.line_table');
  tableRows.forEach(function (row) {
    row.addEventListener('click', function () {
      var modalId = this.getAttribute('data-modal-id');
      openModal(modalId);
    });
  });

  closeBtns.forEach(function (btn) {
    btn.addEventListener('click', closeModal);
  });

  window.addEventListener('click', function (event) {
    if (event.target.classList.contains('modal')) {
      event.target.style.display = 'none';
    }
  });

});

