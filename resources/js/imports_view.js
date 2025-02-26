

let input_packets_number = document.querySelector("#input_packets_number");
let input_product_category = document.querySelector("#input_product_category");
let input_number_of_pieces = document.querySelector("#input_number_of_pieces");
let input_original_price = document.querySelector("#input_original_price");
let input_packet_selling_price = document.querySelector("#input_packet_selling_price");
let input_piece_selling_price = document.querySelector("#input_piece_selling_price");
let input_number_of_exciting_packets = document.querySelector("#input_number_of_exciting_packets");
let input_existing_number_of_pieces = document.querySelector("#existing_number_of_pieces");

let exit_icon = document.querySelector(".product i");
let whole_form = document.querySelector(".add_quantity_main");
let add_quantity = document.querySelector(".add_quantity_button");
let form_of_data = document.querySelector("#form_of_data");
let submit_button = document.querySelector("#submit_button");


function showForm() {
    whole_form.style.display = "flex";
    whole_form.style.height = document.documentElement.scrollHeight + "px";
}

function clearForm() {
    input_packets_number.value = "";
    input_product_category.value = "";
    input_number_of_pieces.value = "";
    input_original_price.value = "";
    input_packet_selling_price.value = "";
    input_piece_selling_price.value = "";
    input_number_of_exciting_packets.value = "";
    input_existing_number_of_pieces.value = "";
}

exit_icon.addEventListener("click", function () {
    whole_form.style.display = "none";
});

$(function(){
    let form_of_data =$("#form_of_data");
    $("body").on("click",".add_quantity_button",function(){
        let current_product_id=$(this).parent().prev().data("id");
        form_of_data.attr("action", `add_quantity/${current_product_id}`,);
        showForm();
    });
})















/////////////////////////////////////////////search
    function filterProducts(searchValue) {
        return products.filter(product => {
            return product.name.toLowerCase().includes(searchValue.toLowerCase()); 
        });
    }

    function generateTableRows(filteredProducts) {
        const tbody = document.getElementById('products-table-body');
        tbody.innerHTML = ''; 
    
    
        filteredProducts.forEach(product => {
            const row = document.createElement('tr');
    
            row.innerHTML = `
                <td data-id="${product.id}" class="editable name" style="width:200px;">${product.name}</td>
                <td>
                    <button class="add_quantity_button">إضافة كمية </button>
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








$(function(){
    let error_messages = $(".error-message");
if(error_messages.text())
    error_messages.each(function(index) {
        $(this).fadeIn().delay(2000).fadeOut();
        return false;  
    });

});







