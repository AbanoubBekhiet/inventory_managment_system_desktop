   
    $(function () {
        
        let $exitIcon = $(".product i");
        let exitIconCus = $(".customer i");
        let $wholeForm = $(".add_product_main");
        let $wholeFormCustomer = $(".add_customer_main");
        let $addProduct = $("#add_product");
        let choose_customer=$("#choose_customer");
        let submit_button=$(".submit_button");


    
        function showForm() {
            $wholeForm.css({
                "display": "flex",
            });
        }
    
        $exitIcon.on("click", function () {
            $wholeForm.hide();
        });
    
        $addProduct.on("click", function () {
            $(".add_product_main").css("height", $(document).height() + "px");
            showForm();
        });


        function showFormCust() {
            $(".add_customer_main").css("height", $(document).height() + "px");
            $wholeFormCustomer.css({
                "display": "flex",
            });
        }
        choose_customer.on("click", function () {
            showFormCust();
        });

        exitIconCus.on("click", function () {
            $wholeFormCustomer.hide();
        });

        function exitCurtomerFun(){
            $wholeFormCustomer.hide();
        }
    
    
    
    
    
    //////////////////////////////////////////////////products search
    
    
    function filterProducts(searchValue) {
        return products.filter(product => {
            return product.name.toLowerCase().includes(searchValue.toLowerCase());
        });
    }
    

    
    $("#search_div input").on("input", function () {
        let searchValue = $(this).val().trim();
        let filteredProducts = filterProducts(searchValue);
        let $tbody = $("#products-table-body");
        $tbody.empty(); 
    
        filteredProducts.forEach(product => {
            let $row1 = $("<tr>").attr("data-id", product.id)
                
                $row1.html(`
                <td class=" name" style="width:200px;">${product.name} -- ${product.n_pieces_in_packet} ق</td>
                <td class=" original_packet_price">${product.original_packet_price}</td>
                <td class=" selling_packet_price">${product.selling_packet_price}</td>
                <td class=" piece_price">${product.piece_price}</td>
                <td class=" exicting_number_of_pieces">${product.exicting_number_of_pieces}</td>
            `);
            
            $tbody.append($row1);
        });
    
        $(".add_product_main").css("height", $(document).height() + "px");    });
    
    let filteredProducts = [];
    let products_ids = [];
    $("body").on("click", ".add_product_main tbody tr", function() {
        let id = $(this).data("id");
        products_ids.push(id);
        $(this).remove();
        $(".add_product_main").css("height", $(document).height() + "px");
    
        filteredProducts = products.filter(product => products_ids.includes(product.id));
    
        let uniqueProducts = Array.from(new Map(filteredProducts.map(p => [p.id, p])).values());
    
    
        let $bill_products=$("#customer_table tbody");
        let $bill_products_for_user=$("#user_table tbody");
    
        $bill_products.empty();
        $bill_products_for_user.empty();


        function listProducts(){
            uniqueProducts.forEach(product => {

                let $row1 = $("<tr>").attr("data-id", product.id)
                    
                    $row1.html(`
                    <td class=" name" style="width:200px;">${product.name} -- ${product.n_pieces_in_packet} ق</td>
                    <td class=" selling_packet_price parent-span-of-plus-minus" >
                        <span style="margin-left:40px;" class="edit">${product.selling_packet_price}</span>
                        <span data-id="${product.id}" class="span-of-plus-minus">
                            <button class="plus" style="border:0px;background-color: #00c489;color: white;padding-inline: 4px;border-radius: 50%;">+</button>
                            <button class="minus" style="border:0px;background-color: #fcdc4c;color: white;padding-inline: 6px;border-radius: 50%;">-</button>
                        </span>
                    </td>
                    
                    <td class=" number_of_packets parent-span-of-plus-minus">
                        <span style="margin-left:40px;">0</span>
                        <span  data-id="${product.id}"  class="span-of-plus-minus">
                            <button class="plus" style="border:0px;background-color: #00c489;color: white;padding-inline: 4px;border-radius: 50%;">+</button>
                            <button class="minus" style="border:0px;background-color: #fcdc4c;color: white;padding-inline: 6px;border-radius: 50%;">-</button>
                        </span>
                    </td>
                    <td class=" selling_piece_price parent-span-of-plus-minus">
                        <span  class="edit" style="visibility:${product.accept_pieces==0?'hidden':'visible'};margin-left:40px;">${product.piece_price}</span>
                        <span  data-id="${product.id}"  class="span-of-plus-minus">
                            <button class="plus" style="border:0px;background-color: #00c489;color: white;padding-inline: 4px;border-radius: 50%;visibility:${product.accept_pieces==0?'hidden':'visible'}">+</button>
                            <button class="minus" style="border:0px;background-color: #fcdc4c;color: white;padding-inline: 6px;border-radius: 50%;visibility:${product.accept_pieces==0?'hidden':'visible'}">-</button>
                        </span>    
                    </td>
                    <td class=" number_of_pieces parent-span-of-plus-minus" >
                        <span style="visibility:${product.accept_pieces==0?'hidden':'visible'};margin-left:40px;">0</span>
                        <span  data-id="${product.id}"  class="span-of-plus-minus" >
                            <button class="plus" style="border:0px;background-color: #00c489;color: white;padding-inline: 4px;border-radius: 50%;visibility:${product.accept_pieces==0?'hidden':'visible'}">+</button>
                            <button class="minus" style="border:0px;background-color: #fcdc4c;color: white;padding-inline: 6px;border-radius: 50%;visibility:${product.accept_pieces==0?'hidden':'visible'}">-</button>
                        </span>    
                    </td>
        
                    <td class=" total price">0</td>
        `);
                
                $bill_products.append($row1);
        
        
        
        
                let $row2 = $("<tr>").attr("data-id", product.id)
                    $row2.html(`
                    <td data-id=${product.id}  class=" selling_packet_price" >${product.original_packet_price}</td>
                    <td data-id=${product.id}  class=" number_of_packets" >${product.exicting_number_of_pieces}</td>
                    <td data-id=${product.id}  class=" selling_peice_price" style="visibility:${product.accept_pieces==0?'hidden':'visible'}">${product.piece_price}</td>
                    <td data-id=${product.id}  class=" number_of_pieces " style="visibility:${product.accept_pieces==0?'hidden':'visible'}" >${product.existing_number_of_pieces ?? 0} </td>
                `);
                
                $bill_products_for_user.append($row2);
            });
        }

        listProducts()
    
    });
    
    
    
    //////////////////////////////////////////////////////// altering values 
    
    
        let error_p=$(".error-message");
    
    ///////////////////////////////////////////////////////////////////////////////plus button
    
        $(".content .bill table").on("click","button.plus",function(e){
            let button=$(e.target);
            let id_of_product=button.parent().data("id");
            let span=button.parent().prev();
    

            let current_value=parseFloat(button.parent().prev().text());
            let increased_value=current_value+1;
    
    

            if(span.parent().hasClass("number_of_packets")){
                let td_of_number_of_packets = $("#user_table tbody td.number_of_packets[data-id='" + id_of_product + "']");
    
                let td_of_number_of_packets_current_value=parseFloat(td_of_number_of_packets.text());
                if(parseFloat(td_of_number_of_packets_current_value)==0){
                    error_p.text("عدد الكراتين المتوفرة 0").
                    fadeIn(1000).
                    fadeOut();
                    return ;
                } 
                else{
                    let td_of_number_of_packets_updated_value=td_of_number_of_packets_current_value-1;
                    td_of_number_of_packets.text(td_of_number_of_packets_updated_value);
                    span.text(increased_value);
                    total_product_price();
                    total_price();
                }
            }
    
            else if(span.parent().hasClass("number_of_pieces")){
                let td_of_number_of_pieces = $("#user_table tbody td.number_of_pieces[data-id='" + id_of_product + "']");
                let td_of_number_of_packets = $("#user_table tbody td.number_of_packets[data-id='" + id_of_product + "']");
    
                let td_of_number_of_pieces_current_value=parseFloat(td_of_number_of_pieces.text());
                let td_of_number_of_packets_current_value=parseFloat(td_of_number_of_packets.text());
                if(parseFloat(td_of_number_of_pieces_current_value)==0){
                    if(td_of_number_of_packets_current_value>0){


                        td_of_number_of_packets.text(parseFloat(td_of_number_of_packets.text()) - 1);

                        let product=products.find(p => p.id ===id_of_product )
                        td_of_number_of_pieces.text(parseFloat(td_of_number_of_pieces_current_value+product.n_pieces_in_packet-1));
                        span.text(increased_value);
                    }
                    else{
                        error_p.text("عدد القطع المتوفرة 0").
                        fadeIn(1000).
                        fadeOut();
                        return ;
                    }
                } 
                else{
                    let td_of_number_of_pieces_updated_value=td_of_number_of_pieces_current_value-1;
                    td_of_number_of_pieces.text(td_of_number_of_pieces_updated_value);
                    span.text(increased_value);

                    total_product_price();
                    total_price();
                    }
            }
            else if(span.parent().hasClass("selling_piece_price")||span.parent().hasClass("selling_packet_price")){
                    span.text(increased_value);
                    total_product_price();
                    total_price();
            }
    
    
            total_product_price();
            total_price();
        });
    
    
    
    
    
    
    ///////////////////////////////////////////////////////////////////////////////minus button
        $(".content .bill table").on("click","button.minus",function(e){
            let button=$(e.target);
            let id_of_product=button.parent().data("id");
            let span=button.parent().prev();
            let current_value=parseFloat(button.parent().prev().text());
            let increased_value=current_value-1;
    
    
            if(span.parent().hasClass("number_of_packets")){
                let td_of_number_of_packets = $("#user_table tbody td.number_of_packets[data-id='" + id_of_product + "']");
    
                let td_of_number_of_packets_current_value=parseFloat(td_of_number_of_packets.text());
                if(parseFloat(current_value)==0){
                    error_p.text("عدد الكراتين المباعة  0").
                    fadeIn(1000).
                        fadeOut();
                    return ;
                } 
                else{
                    let td_of_number_of_packets_updated_value=td_of_number_of_packets_current_value+1;
                    td_of_number_of_packets.text(td_of_number_of_packets_updated_value);
                    span.text(increased_value);
                }
                total_product_price();
                total_price();
            }
    
            else if(span.parent().hasClass("number_of_pieces")){
                let td_of_number_of_pieces = $("#user_table tbody td.number_of_pieces[data-id='" + id_of_product + "']");
                let td_of_number_of_pieces_current_value=parseFloat(td_of_number_of_pieces.text());
                    if(parseFloat(current_value)==0){
                        error_p.text("عدد القطع المباعة  0").
                        fadeIn(1000).
                        fadeOut();
                        return ;
                    } 
                    else{
                        let td_of_number_of_pieces_updated_value=td_of_number_of_pieces_current_value+1;
                        td_of_number_of_pieces.text(td_of_number_of_pieces_updated_value);
                        span.text(increased_value);
                    }
                    total_product_price();
                    total_price();
            }
    
    
            else if(span.parent().hasClass("selling_piece_price")||span.parent().hasClass("selling_packet_price")){
                if(current_value==0){
                    error_p.text("لا يمكن ان يكون السعر اقل من 0 ").
                        fadeIn(1000).
                        fadeOut();
                        return ;
                }
                else{
                    span.text(increased_value);
                }
            }
    
            total_product_price();
            total_price();
        });
    
    
    
    function total_product_price(){
        let $bill_table_rows=$(".content .bill #customer_table tbody tr");
        $bill_table_rows.each(function(){
            let total_product_price=0;
            let tr=$(this).find("td");
            total_product_price+=parseFloat(tr.eq(1).children().eq(0).text())*parseFloat(tr.eq(2).children().eq(0).text());
            total_product_price+=parseFloat(tr.eq(3).children().eq(0).text())*parseFloat(tr.eq(4).children().eq(0).text());
            tr.last().text(parseFloat(total_product_price));
        });
    }
    
    
    let total_bill_price=$(".content .total_price span").eq(1);
        function total_price(){
            let total=0;
            let $bill_table_rows=$(".content .bill #customer_table tbody tr");
            $bill_table_rows.each(function(){
                total+=parseFloat($(this).children().last().text());
                total_bill_price.text(total);
            });
        }
    
    
    







////////////////////////////////////////////////////////////////choose customer




 //////////////////////////////////////////////////customer search
    
    
function filterCustomers(searchValue) {
    return customers.filter(customer => {
        return (
            customer.cus_name.includes(searchValue) ||
            customer.address.includes(searchValue) ||
            customer.phone_number.includes(searchValue)
        );
    });
}

function generateTablerow1s(filteredcustomers) {
    let $tbody = $("#customer-table-body");
    $tbody.empty(); 

    filteredcustomers.forEach(customer => {
        let $row1 = $("<tr>").attr("data-id", customer.id)
            
            $row1.html(`
            <td class=" cus_name" style="width:200px;">${customer.cus_name}  </td>
            <td class=" phone">${customer.phone_number}</td>
            <td class=" address">${customer.address}</td>
        `);
        
        $tbody.append($row1);
    });

}

$("#search_div_customer input").on("input", function () {
    let searchValue = $(this).val().trim();
    let filteredcustomers = filterCustomers(searchValue);
    generateTablerow1s(filteredcustomers);
});

let customer_choosen=$("#customer_choosen")
let customer_id;
let customer_name;
let customer_phone_number;
let customer_address;
$("body").on("click", ".add_customer_main tbody tr", function() {
    customer_id = $(this).data("id");
    customer_name=$(this).children().eq(0).text();
    customer_phone_number=$(this).children().eq(1).text();
    customer_address=$(this).children().eq(2).text();
    customer_choosen.children().eq(1).text(customer_name);
    exitCurtomerFun()


});











///////////////////////////////////////////////send data to backend
submit_button.on("click", function(e) {
    let rows_of_products = $(".bill #customer_table tbody tr");

    if (customer_choosen.children().eq(1).text() == "لم يتم الاختيار") {
        error_p.text("لم يتم اخيار اسم العميل").fadeIn(1000).fadeOut();
        return;
    }

    if(rows_of_products.length === 0) {
        error_p.text("لا يوجد منتجات مضافة").fadeIn(1000).fadeOut();
        return;
    }

    let products = [];
    let validProducts = true;
    try {
        rows_of_products.each(function() {
            const $tr = $(this);
            
            const packetQty = parseFloat($tr.find("td").eq(2).find("span").eq(0).text()) || 0;
            const pieceQty = parseFloat($tr.find("td").eq(4).find("span").eq(0).text()) || 0;

            if (packetQty === 0 && pieceQty === 0) {
                error_p.text(`${$tr.find("td").eq(0).text()} - لم يتم اختيار الكمية`).fadeIn(1000).fadeOut();
                validProducts = false;
                return false;
            }

            products.push({
                id: parseFloat($tr.data("id")) ,
                name: $tr.find("td").eq(0).text(),
                selling_packet_price: parseFloat($tr.find("td").eq(1).find("span").eq(0).text()) ,
                number_of_packets: packetQty,
                selling_piece_price: parseFloat($tr.find("td").eq(3).find("span").eq(0).text()) ,
                number_of_pieces: pieceQty,
                total: parseFloat($tr.find("td").eq(5).text()) 
            });

        });
    } catch (e) {
        error_p.text("خطأ في بيانات المنتجات").fadeIn(1000).fadeOut();
        return;
    }

    if(validProducts && products.length > 0) {
        const total_bill_price = parseFloat($(".total_price span").eq(1).text());
        
        const requestData = {
            _token: $('meta[name="csrf-token"]').attr("content"),
            products: JSON.parse(JSON.stringify(products)), 
            total_bill_price: total_bill_price,
            customer_id:customer_id,
            customer_name:customer_name,
            customer_address:customer_address,
            customer_phone_number:customer_phone_number,
        };
        $.ajax({
            url: "/bills/bill_back",
            method: "POST",
            contentType: "application/json",
            data: JSON.stringify(requestData),
            success: function(response) {
                window.location.reload();
            },
            error: function(xhr) {
            }
        });
        $(e.target).prop('disabled', true);
    } else {
        error_p.text("الرجاء مراجعة كميات المنتجات").fadeIn(1000).fadeOut();
    }
});




$(document).on("dblclick", ".bill #customer_table tbody td.name", function(){
    let row=$(this).parent();
    row.detach();
    let pro_id=row.data("id");
    $("#user_table tbody tr[data-id='" + pro_id + "']").detach();
    products_ids=products_ids.filter(id=>id!==pro_id);
    total_product_price();
    total_price();
});





















//////////////////////////////////////////////////////altering products data by clicking on it
$("#customer_table tbody").on("click", "span.edit", function(e) {
    e.target.innerHTML = "<input type='number' min='0' value='" + e.target.innerText + "'>";
    
    let input = e.target.querySelector("input"); 
    input.focus(); 
    input.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            let newValue = input.value;
            if (newValue < 0 || newValue === "") {
                error_p.text("يوجد خطأ في ادخال السعر راجع المبلغ").fadeIn(1000).fadeOut();
                return false;
            }
            e.target.innerHTML = newValue;
            total_product_price();
            total_price();        
        }

    });
    


});






});
