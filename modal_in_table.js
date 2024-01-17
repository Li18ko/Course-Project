document.addEventListener('DOMContentLoaded', function () {

    var tableRows = document.querySelectorAll('.line_table');
    tableRows.forEach(function (row) {
        row.addEventListener('click', function () {
            var modalId = this.getAttribute('data-modal-id');
            displayDataInModal(modalId, "u", "one");
        });
    });

    var tableRows_index = document.querySelectorAll('.line_table_index');
    tableRows_index.forEach(function (row) {
        row.addEventListener('click', function () {
            var modalId = this.getAttribute('data-modal-id');
            displayDataInModal(modalId, "g", "one");
        });
    });
});

function displayDataInModal(modalId, place, flag) {
    var xhr = new XMLHttpRequest();
    console.log(xhr);
    console.log(modalId);
    console.log(place);
    console.log(flag);
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.responseText);
            var responseData = JSON.parse(this.responseText);
            console.log(responseData);
            var modal = document.getElementById('myModal');
            modal.style.display = 'block';


            var modalContent = document.getElementById('modalContent');
            var contentHTML = '';

            responseData.modalContentArray.forEach(function (content) {
                contentHTML += content;
            });

            modalContent.innerHTML = contentHTML;

            var closeBtn = document.getElementsByClassName('close')[0];
            closeBtn.onclick = function () {
                modalContent.innerHTML = '';
                modal.style.display = 'none';
            };

            window.onclick = function (event) {
                if (event.target == modal) {
                    modalContent.innerHTML = '';
                    modal.style.display = 'none';
                }
            };
        }
    };

    xhr.open("GET", "get_modal_content.php?modalId=" + modalId + "&place=" + place + "&flag=" + flag, true);
    xhr.send();
}
