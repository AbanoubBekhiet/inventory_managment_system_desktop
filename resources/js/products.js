

let input_product_name = document.querySelector("#input_product_name");
let input_product_category = document.querySelector("#input_product_category");
let input_number_of_pieces = document.querySelector("#input_number_of_pieces");
let input_original_price = document.querySelector("#input_original_price");
let input_packet_selling_price = document.querySelector("#input_packet_selling_price");
let input_piece_selling_price = document.querySelector("#input_piece_selling_price");
let input_number_of_exciting_packets = document.querySelector("#input_number_of_exciting_packets");
let input_existing_number_of_pieces = document.querySelector("#existing_number_of_pieces");
let selling_customer_piece_price = document.querySelector("#selling_customer_piece_price");

let exit_icon = document.querySelector(".product i");
let whole_form = document.querySelector(".add_product_main");
let addProduct = document.querySelector("#addProduct");
let form_of_data = document.querySelector("#form_of_data");
let submit_button = document.querySelector("#submit_button");


function showForm() {
    whole_form.style.display = "flex";
    whole_form.style.height = document.documentElement.scrollHeight + "px";
    clearForm(); 
}

function clearForm() {
    input_product_name.value = "";
    input_product_category.value = "";
    input_number_of_pieces.value = "";
    input_original_price.value = "";
    input_packet_selling_price.value = "";
    input_piece_selling_price.value = "";
    input_number_of_exciting_packets.value = "";
    input_existing_number_of_pieces.value = "";
    selling_customer_piece_price.value = "";
}

exit_icon.addEventListener("click", function () {
    clearForm(); 
    whole_form.style.display = "none";
});

addProduct.addEventListener("click", function () {
    clearForm(); 
    showForm();
});















/////////////////////////////////////////////search
    function filterProducts(searchValue) {
        return products.filter(product => {
            return product.name.toLowerCase().includes(searchValue.toLowerCase()); 
        });
    }

    function generateTableRows(filteredProducts) {
        const tbody = document.getElementById('products-table-body');
        tbody.innerHTML = ''; 
    
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Get CSRF token
    
        filteredProducts.forEach((product,index) => {
            const row = document.createElement('tr');
    
            row.innerHTML = `
                <td >${index+1}</td>
                <td data-id="${product.id}" class="editable name" style="width:200px;">${product.name}</td>
                <td data-id="${product.id}" class="editable n_pieces_in_packet">${product.n_pieces_in_packet ?? "غير متوفر"}</td>
                <td data-id="${product.id}" class="editable original_packet_price">${product.original_packet_price}</td>
                <td data-id="${product.id}" class="editable selling_packet_price">${product.selling_packet_price}</td>
                <td data-id="${product.id}" class="editable piece_price">${product.piece_price}</td>
                <td data-id="${product.id}" class="editable exicting_number_of_pieces">${product.exicting_number_of_pieces ?? 0}</td>
                <td data-id="${product.id}" class="editable existing_number_of_pieces">${product.existing_number_of_pieces ?? 0}</td>
                <td data-id="${product.id}" class="editable selling_customer_piece_price">${product.selling_customer_piece_price ?? ""}</td>
                <td data-id="${product.id}" class="accept_pieces">${product.accept_pieces == 0 ? "لا" : "نعم"}</td>
                <td>
                    <form class="delete-form" action="/products/delete_product/${product.id}" method="POST">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="حذف" style="background-color: red; color: white; border: none; padding: 5px 10px; border-radius: 3px;">
                    </form>
                </td>
            `;
    
            tbody.appendChild(row);
        });
    }

    const search_input = document.querySelector("#search_div input");

    search_input.addEventListener("input", function (e) {
        const searchValue = e.target.value.trim();
        const filteredProducts = filterProducts(searchValue);
        generateTableRows(filteredProducts);
    });

    generateTableRows(products);


////////////////////////////////////////////////////////sweet alert


document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault(); 

        Swal.fire({
            title: "هل انت متأكد?",
            text: "مش هتقدر ترجع المنتج تاني!",
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

$(function() {
    $("#products").on('click','.editable', function(e) {
        var $li = $(this);
        var product_id = $li.data('id');
        var currentValue = $li.text();
        let type = "";
        if ($li.hasClass("name")) {
            type = "name";
        } else if ($li.hasClass("n_pieces_in_packet")) {
            type = "n_pieces_in_packet";
        } else if ($li.hasClass("original_packet_price")) {
            type = "original_packet_price";
        } else if ($li.hasClass("selling_packet_price")) {
            type = "selling_packet_price";
        } else if ($li.hasClass("piece_price")) {
            type = "piece_price";
        } else if ($li.hasClass("exicting_number_of_pieces")) {
            type = "exicting_number_of_pieces";
        } 
        else if ($li.hasClass("existing_number_of_pieces")) {
            type = "existing_number_of_pieces";
        }
        else if ($li.hasClass("selling_customer_piece_price")) {
            type = "selling_customer_piece_price";
        }

        var originalHtml = $li.html();
        $li.html('<input type="text" class="edit-input" value="' + currentValue + '">');
        $('.edit-input').focus();

        $('.edit-input').on('keypress', function(e) {
            if (e.which === 13) { 
                var newValue = $(this).val();
                if (type !== "name") {
                    newValue = parseFloat(newValue);
                    if (isNaN(newValue)) {
                        $('#message-container')
                            .text('من فضلك ادخل قيمة رقمية')
                            .addClass('alert-danger')
                            .fadeIn()
                            .delay(3000)
                            .fadeOut();
                        $li.html(originalHtml);
                        return;
                    }
                }

                $.ajax({
                    url: `/products/update_product/${product_id}`,
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
                            $li.html(originalHtml);
                        }
                    },
                    error: function(xhr) {
                        $('#message-container')
                            .text(xhr.responseJSON.errors.value[0])
                            .addClass('alert-danger')
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






