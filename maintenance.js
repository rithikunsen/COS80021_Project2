document.addEventListener('DOMContentLoaded', function () {
    const processItemsButton = document.getElementById('processItems');
    const generateReportButton = document.getElementById('generateReport');
    const resultDiv = document.getElementById('result');

    // Function to send an Ajax request
    function sendAjaxRequest(action, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'maintenance.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                callback(xhr.responseText);
            }
        };
        xhr.send('action=' + action);
    }

    processItemsButton.addEventListener('click', function () {
        sendAjaxRequest('process_items', function (response) {
            resultDiv.innerHTML = response;
        });
    });

    generateReportButton.addEventListener('click', function () {
        sendAjaxRequest('generate_report', function (response) {
            resultDiv.innerHTML = response;
        });
    });
});
