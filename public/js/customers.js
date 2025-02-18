let input_cus_name = document.querySelector("#input_cus_name");
let input_phone_number = document.querySelector("#input_phone_number");
let input_address = document.querySelector("#input_address");

let exit_icon = document.querySelector(".customer i");
let addCustomer = document.querySelector("#addCustomer");
let submit_button = document.querySelector("#submit_button");
let form_of_data = document.querySelector("#form_of_data");




let whole_form = document.querySelector(".add_customer_main");


function showForm() {
    whole_form.style.display = "flex";
    whole_form.style.height = document.documentElement.scrollHeight + "px";
}

function clearForm() {
    input_cus_name.value = "";
    input_phone_number.value = "";
    input_address.value = "";
    
}

exit_icon.addEventListener("click", function () {
    whole_form.style.display = "none";
});

addCustomer.addEventListener("click", function () {
    showForm();
});






//customers search 
function filterCustomers(searchValue) {
    return customers.filter(customer => {
        return (
            customer.cus_name.includes(searchValue) ||
            customer.address.includes(searchValue) ||
            customer.phone_number.includes(searchValue)
        );
    });
}

function generateTableRows(filteredCustomers) {
    const tbody = document.getElementById('customers-table-body');
    tbody.innerHTML = '';
    filteredCustomers.forEach(customer => {
        const row = document.createElement('tr');

        const nameCell = document.createElement('td');
        nameCell.textContent = customer.cus_name;
        row.appendChild(nameCell);

        const addressCell = document.createElement('td');
        addressCell.textContent = customer.address;
        row.appendChild(addressCell);

        const phoneCell = document.createElement('td');
        phoneCell.textContent = customer.phone_number;
        row.appendChild(phoneCell);

        $(function() {
            $(nameCell).addClass("editable").addClass("cus_name").attr('data-id',customer.id);
            $(addressCell).addClass("editable").addClass("address").attr('data-id',customer.id);
            $(phoneCell).addClass("editable").addClass("phone_number").attr('data-id',customer.id);
        });

        const purchasesCell = document.createElement('td');
        const purchasesButton = document.createElement('button');
        purchasesButton.textContent = 'رؤية المشتريات';
        purchasesButton.style.backgroundColor = '#4CAF50';
        purchasesButton.style.color = 'white';
        purchasesButton.style.border = 'none';
        purchasesButton.style.padding = '5px 10px';
        purchasesButton.style.borderRadius = '3px';
        purchasesButton.addEventListener('click', () => {
            window.location.href = `/customer/customer_purchases/${customer.id}`;
        });
        purchasesCell.appendChild(purchasesButton);
        row.appendChild(purchasesCell);

        const actionsCell = document.createElement('td');
        actionsCell.style.display = 'flex';
        actionsCell.style.justifyContent = 'space-evenly';

        const deleteForm = document.createElement('form');
        deleteForm.classList.add('delete-form');
        deleteForm.action = `/customers/delete_customer/${customer.id}`;
        deleteForm.method = 'POST';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';

        const deleteButton = document.createElement('input');
        deleteButton.type = 'submit';
        deleteButton.value = 'حذف';
        deleteButton.style.backgroundColor = 'red';
        deleteButton.style.color = 'white';
        deleteButton.style.border = 'none';
        deleteButton.style.padding = '5px 10px';
        deleteButton.style.borderRadius = '3px';

        deleteForm.appendChild(csrfToken);
        deleteForm.appendChild(methodInput);
        deleteForm.appendChild(deleteButton);



        actionsCell.appendChild(deleteForm);

        row.appendChild(actionsCell);

        tbody.appendChild(row);
    });
}

const search_input = document.querySelector("#search_div input");

search_input.addEventListener("input", function (e) {
    const searchValue = e.target.value.trim();
    const filteredCustomers = filterCustomers(searchValue);
    generateTableRows(filteredCustomers);
});

generateTableRows(customers);



document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault(); 

        Swal.fire({
            title: "هل انت متأكد?",
            text: "مش هتقدر ترجع العميل تاني!",
            icon: "تحذير",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "نعم  احذفه!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "تم الحذف بنجاح",
                    icon: "احذفه"
                }).then(() => {
                    event.target.submit(); 
                });
            }
        });
    });
});






//////////////////////////update customer 

$(document).ready(function() {
    $('.editable').on('click', function(e) {
        var $li = $(this);
        var customer_id = $li.data('id');
        var currentValue = $li.text();
        let type = "";

        if ($li.hasClass("cus_name")) {
            type = "cus_name";
        } else if ($li.hasClass("address")) {
            type = "address";
        } else if ($li.hasClass("phone_number")) {
            type = "phone_number";
        }

        var originalHtml = $li.html();

        $li.html('<input type="text" class="edit-input" value="' + currentValue + '">');
        $('.edit-input').focus();

        $('.edit-input').on('keypress', function(e) {
            if (e.which === 13) { 
                var newValue = $(this).val();
                $.ajax({
                    url: `/customers/update_customer/${customer_id}`,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'PUT',
                        type: type,
                        value: newValue
                    },
                    success: function(response) {
                        if (response.success) {
                            $li.html(`<span class="${type}">${newValue}</span>`);
                            $('#message-container')
                            .text(response.message) 
                            .addClass('alert-success') 
                            .fadeIn() 
                            .delay(3000) 
                            .fadeOut(); 
                        } else {
                            alert(response.message);
                            $li.html(originalHtml);
                        }
                    },
                    error: function(xhr) {

                        let errors = xhr.responseJSON.errors;
                            let errorMessage = "";
                            for (let field in errors) {
                                errorMessage += errors[field].join("\n") + "\n";
                            }
                            $('#message-container')
                            .text(errorMessage) 
                            .addClass('alert-success') 
                            .fadeIn() 
                            .delay(3000) 
                            .fadeOut(); 
                        $li.html(originalHtml);
                    }
                });
            }
        });

        $('.edit-input').on('blur', function() {
            $li.html(originalHtml);
        });
    });



    let error_messages = $(".error-message");
    if(error_messages.text())
        error_messages.each(function(index) {
            $(this).fadeIn().delay(2000).fadeOut();
            return false;  
        });
});


