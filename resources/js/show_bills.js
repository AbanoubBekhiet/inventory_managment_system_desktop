$(function(){
    /////////////////////////////////////////////////////////filter based on search


    function filterBills(searchValue) {
        return bills_no_limit.filter(customer => {
            return customer.cus_name.toLowerCase().includes(searchValue.toLowerCase()); 
        });
    }

    function generateTableRows(filteredBills) {
        const tbody = document.getElementById('bill-table-body');
        tbody.innerHTML = ''; 

        filteredBills.forEach((bill,index )=> {
            const row = document.createElement('tr');
            const showBillUrl = `show_bills/show_specific_bill/${bill.id}`;
            const bill_benifit = `show_bills/bill_binefits/${bill.id}`;
            row.innerHTML = `
                <td >${index+1}</td>
                <td class="name" style="width:200px;">${bill.cus_name}</td>
                <td class="n_pieces_in_packet">${bill.phone_number}</td>
                <td class="original_packet_price">${bill.total_price}</td>
                <td class="selling_packet_price">${bill.created_at}</td>
                <td class="bill_benifit">
                    <a href="${bill_benifit}">عرض مكسب الفاتورة </a>
                </td>
                <td class="discount_button" data-id=${bill.id}><button>خصم</button></td>
                <td id="discount_value"> ${bill.discount==null? 0:bill.discount}</td>
                <td>
                    <a href="${showBillUrl}">عرض الفاتورة</a>
                </td>
            `;

            tbody.appendChild(row);
        });
    }

    const search_input = document.querySelector("#search_div input");

    search_input.addEventListener("input", function (e) {
        const searchValue = e.target.value.trim();
        const filteredBills = filterBills(searchValue);
        generateTableRows(filteredBills);
    });

    /////////////////////////////////////////////////////////filter based on check box
    const today = new Date().getDate();
    const this_month = new Date().getMonth();
    const this_year = new Date().getFullYear();
    
    let curent_day_bills=  bills_no_limit.filter(customer => {
        const date = new Date(customer.created_at);  
        if(date.getDate()==today&&date.getMonth()==this_month&&date.getFullYear()==this_year)
            return customer;
        });
    

        let checkbox = $("#day");

    if(checkbox.is(":checked"))
        generateTableRows(curent_day_bills)
        else{
            generateTableRows(bills_with_limit);
        }        
    checkbox.on("change",function(){
        if(checkbox.is(":checked"))
        generateTableRows(curent_day_bills)
        else{
            generateTableRows(bills_with_limit);
        }
    })
    
///////////////////////////////////////////////////////////discount


    let exit_icon = document.querySelector(".discount i");
    let whole_form = document.querySelector(".discount_main");
    let form_of_data = document.querySelector("#form_of_data");
    $("body").on("click", ".discount_button button", function() {
        whole_form.style.display = "flex";
        whole_form.style.height = document.documentElement.scrollHeight + "px";
        let discountId = $(this).parent().data("id");
        form_of_data.action = "/bills/show_bills/discount/" + discountId;
    });
    

    

    
    exit_icon.addEventListener("click", function () {
        $("#discount_input").val("");
        whole_form.style.display = "none";
    });
    

    

});