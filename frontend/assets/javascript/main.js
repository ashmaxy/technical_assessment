var defaults = [];
var modal = document.getElementById("newClientModal");
var span = document.getElementsByClassName("close")[0];
var dropDown = document.getElementById("wrapper");
const form = document.querySelector("#addClientForm");
const formInvoice = document.querySelector("#addInvoiceForm");
var counter = 1;

window.onload = function () {
    span.onclick = function () {
        modal.style.display = "none";
    }

    form.addEventListener("submit", (event) => {
        event.preventDefault();
        if (!isValidUSZip(document.getElementById('newZipCode').value)) {
            alert('Please enter a valid zip code');
            return false;
        }

        if (!isValidPhoneNumber(document.getElementById('newPhoneNumber').value)) {
            alert('Please enter a valid phone number');
            return false;
        }

        sendData();
    });

    formInvoice.addEventListener("submit", (event) => {
        event.preventDefault();
        if (document.getElementById('clientId').value == 0) {
            alert('Please select a client');
            return false;
        }
        if (document.getElementsByName("name[]").length == 0) {
            alert('Please add at least 1 invoice row');
            return false;
        }
        saveInvoice();
    });
};

function toggleDropdown () {
    document.getElementById("dropdownContent").classList.toggle("show");
    document.getElementById("dropdownImage").classList.toggle("open");
    document.getElementById("clientDropdown").classList.toggle("selected");
}

function filterClients() {
    var input, filter;
    input = document.getElementById("searchClients");
    filter = input.value.toUpperCase();
    searchClients(filter);
}

function populateDropdown(data) {
    const dropdown = document.getElementById('clientDropdownInner');
    dropdown.innerHTML = '';

    data.forEach(item => {
        const option = document.createElement('div');
        option.classList.add('item');
        option.innerHTML = "<div class='avatar'>" + item.name.charAt(0) + "</div><div class='name-wrapper'><div class='client-name'>" + item.name + "</div><div class='company-name'>" + item.company_name + "</div></div>";

        option.onclick = function () {
            divClickHandler(item.client_id);
        };
        dropdown.appendChild(option);
    });
}

function divClickHandler(id) {
    toggleDropdown();
    document.getElementById("searchClients").value = '';
    getClientData(id);
}

function getClientData(id) {
    fetch(config.baseUrl+'getClient?client_id=' + id)
        .then(response => response.json())
        .then(data => {
            document.getElementById('selectedClientName').innerText = data.name;
            document.getElementById('selectedClientName').classList.toggle('selected-client-name');
            document.getElementById('clientId').value = data.client_id;
        })
        .catch(error => console.error('Error fetching data:', error));
}

function searchClients(filter) {
    if (filter.length > 0) {
        fetch(config.baseUrl+'searchClients?search_term=' + filter)
        .then(response => response.json())
        .then(data => {
            populateDropdown(data);
        })
        .catch(error => console.error('Error fetching data:', error));
    }
}

function getDefaultData() {

    fetch(config.baseUrl+'populateDefaults')
        .then(response => response.json())
        .then(data => {
            populateDropdown(data.clients);
            defaults = data.items;
        })
        .catch(error => console.error('Error fetching data:', error));
}

function newClient() {
    toggleDropdown();
    modal.style.display = "block";
}

async function sendData() {
    
    const formData = new FormData(form);

    try {
        const response = await fetch(config.baseUrl+'addClient', {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 'error') {
                alert(data.message);
            } else {
                modal.style.display = "none";
                form.reset();
                document.getElementById('selectedClientName').innerText = data.name;
                document.getElementById('selectedClientName').classList.toggle('selected-client-name');
                document.getElementById('clientId').value = data.client_id;
            }
        })
        .catch(error => console.error('Error fetching data:', error));
    } catch (e) {
        console.error(e);
    }
}

async function saveInvoice() {
    var formData = new FormData(formInvoice);

    try {
        const response = await fetch(config.baseUrl+'saveInvoice', {
            method: "POST",
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            if (data.status == 'error') {
                alert(data.message);
            } else {
                alert(data.message);
                formInvoice.reset();
            }
        })
        .catch(error => console.error('Error fetching data:', error));
    } catch (e) {
        console.error(e);
    }
}

getDefaultData();

function addRow() {
    counter++;
    var lineItems = document.getElementById('lineItems');
    const option = document.createElement('div');
    option.classList.add('line-item-row');
    option.id = 'line' + counter;
    option.innerHTML =
        "<input type='hidden' name='itemId[]" + counter + "' id='itemId" + counter + "' value='0'>" +
        "<input type='hidden' name='taxed[]' id='taxed" + counter + "' value='0'></input>" +
        "<div class='line-item-column line-input'>" +
        "<input type='text' id='name" + counter + "' name='name[]' autocomplete='off' onclick='openItemList(" + counter + ")' onkeyup='searchItemList(" + counter + ")' onblur='hideItemList(" + counter + ")' required>" +
        "<div id='itemList" + counter + "' class='items-inv'>" +
        "</div>" +
        "</div>" +
        "<div class='line-item-column line-input'>" +
        "<input id='checked" + counter + "' class='line-input' onchange='updateCheckbox(" + counter + ")' type='checkbox' name='checked[]'>" +
        "</div>" +
        "<div class='line-item-column line-input'>" +
        "<input id='amount" + counter + "' class='line-input' type='number' name='amount[]' step='.01' onchange='doCalculations()' required min='0' max='1000000'>" +
        "</div>" +
        "<div class='line-item-column delete-column'>" +
        "<img class='delete-icon' src='assets/images/delete.svg' onclick='deleteRow(" + counter + ")'>" +
        "</div>";

    lineItems.appendChild(option);

}

function deleteRow(rowId) {
    const element = document.getElementById("line" + rowId);
    element.remove();
    doCalculations();
}

function openItemList(id) {
    populateDiv(defaults, id);
}

function populateDiv(data, id) {
    const dropdown = document.getElementById('itemList' + id);
    if (data.length > 0) {
        if (!dropdown.classList.contains('show')) {
            dropdown.classList.add("show");
        }
    } else {
        dropdown.classList.remove("show");
    }
    dropdown.innerHTML = '';

    data.forEach(item => {
        const option = document.createElement('div');
        option.classList.add('item');
        option.innerHTML = "<div class='item-name'>" + item.name + "</div><div>$" + item.amount + "</div>";

        option.onmousedown = function () {
            document.getElementById('itemId' + id).value = item.invoice_item_default_id;
            document.getElementById('name' + id).value = item.name;
            if (item.taxed == 1) {
                document.getElementById('checked' + id).checked = true;
            } else {
                document.getElementById('checked' + id).checked = false;
            }
            updateCheckbox(id);
            document.getElementById('amount' + id).value = item.amount;
            doCalculations();
            dropdown.classList.remove("show");
        };
        dropdown.appendChild(option);
    });
}

function searchItemList(id) {
    const input = document.getElementById('name' + id);
    var searchTerm = input.value;
    var condition = '';
    if (searchTerm.length > 0) {
        condition = '?search_term=' + searchTerm;
        fetch(config.baseUrl+'searchItems' + condition)
            .then(response => response.json())
            .then(data => {
                populateDiv(data, id);
                console.log(data);
            })
            .catch(error => console.error('Error fetching data:', error));
    } else {
        populateDiv(defaults, id);
    }
}

function hideItemList(id) {
    document.getElementById('itemList' + id).classList.remove("show");
}

function updateCheckbox(id) {
    if (document.getElementById('checked' + id).checked) {
        document.getElementById('taxed' + id).value = 1;
    } else {
        document.getElementById('taxed' + id).value = 0;
    }

    doCalculations();
}

function doCalculations() {
    var subTotal = 0;
    var taxable = 0;
    var taxRate = parseFloat(document.getElementById('taxRate').value);
    var tax = 0;
    var other = parseFloat(document.getElementById('otherAmount').value);
    var total = 0;

    var fields = document.getElementsByName("name[]");

    for (var i = 0; i < fields.length; i++) {
        var id = fields[i].id.replace("name", "");
        var val = document.getElementById("amount" + id).value;
        subTotal += parseFloat(val) > 0 ? parseFloat(val) : 0;
        if (document.getElementById("taxed" + id).value == 1) {

            taxable += parseFloat(val) > 0 ? parseFloat(val) : 0;
        }
    }

    if (taxRate > 0) {
        tax = parseFloat((taxRate / 100) * taxable);
    }

    total = subTotal + tax + other;

    document.getElementById('subtotal').value = subTotal.toFixed(2);
    document.getElementById('taxable').value = taxable.toFixed(2);
    document.getElementById('taxDue').value = tax.toFixed(2);
    document.getElementById('total').value = total.toFixed(2);
}

function isValidUSZip(zipCode) {
    return /^\d{5}(-\d{4})?$/.test(zipCode);
}

function isValidPhoneNumber(zipCode) {
    return /^[+]*[(]{0,1}[0-9]{1,3}[)]{0,1}[-\s\./0-9]*$/g.test(zipCode);
}