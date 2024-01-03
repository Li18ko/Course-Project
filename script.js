document.addEventListener('DOMContentLoaded', function() {
    var closeBtns = document.querySelectorAll('.close');
  
    function openModal(modalId) {
      var modal = document.getElementById(modalId);
      modal.style.display = 'block';
    }
  
    function closeModal() {
      var modal = this.closest('.modal');
      modal.style.display = 'none';
    }
  
    // Добавляем обработчик события для каждой строки таблицы
    var tableRows = document.querySelectorAll('.line_table');
    tableRows.forEach(function(row) {
      row.addEventListener('click', function() {
        var modalId = this.getAttribute('data-modal-id');
        openModal(modalId);
      });
    });
  
    // Добавляем обработчик события для каждой кнопки закрытия
    closeBtns.forEach(function(btn) {
      btn.addEventListener('click', closeModal);
    });
  
    // Добавляем обработчик события для закрытия модального окна при клике вне его области
    window.addEventListener('click', function(event) {
      if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
      }
    });

});
  
  