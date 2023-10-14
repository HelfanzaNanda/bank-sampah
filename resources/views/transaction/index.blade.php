@extends('layouts.app')

@section('content')
<div class="main-content">
    <section class="section">
        <x-breadcrumb :menus="$menus" :title="$title" />

        <div class="section-body">

            <div class="card">
                <form id="main-form">
                    <div class="card-header">
                        <h4>{{ $title }}</h4>
                    </div>
                    @include('transaction.form');
                    <div class="card-footer text-right">
                        {{-- <a href="{{ route('transaction.index') }}" class="btn btn-secondary mr-2">Back</a> --}}
                        <button class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection
@push('scripts')
    <script>



        function selectRefresh() {
            $('.select2').select2({
                placeholder: "Select an Option",
                // allowClear: true,
            });
        }


        $(document).ready( async function () {
            await addItem()
        });


        async function fetchOptions() {
            const url = `${APP_URL}/api/waste/options`;
            const method = "POST";
            const params = {
                prefix : "transaction"
            }
            const response = await request(url, method, params);

            if (response.status) {
                return response.data;
            }
        }


        $(document).on('click', '.btn-add-item', async function (e) {
            e.preventDefault()
            await addItem();
        })
        $(document).on('click', '.btn-remove-item', async function (e) {
            e.preventDefault()
            let id = $(this).data('id');
            const isConfirmed = await showAlertDelete()
            if (isConfirmed) {
                $(`#tr-${id}`).remove();
                calculateSubTotal();
            }
        })

        async function addItem() {
            let dataId = $("#items-table tbody tr:last-child").data("id");
            let id = 0;
            if (Number.isInteger(dataId)) {
                id = dataId + 1;
            }
            console.log('id : ', id);
            const options = await fetchOptions();

            let tr = ``;
                tr += `<tr data-id="${id}" id="tr-${id}">`;
                tr += `    <td>`;
                tr += `        <div class="form-group">`;
                tr += `            <select data-id="${id}" name="waste_id[]" id="input-waste_id-${id}" class="form-control select2 input-waste_id">`
                tr +=   `        <option value="" disabled selected>Select an Option</option> `;

                    options.forEach(item => {
                tr +=   `        <option data-price="${item.price}" value="${item.id}">${item.name}</option> `;
                    });
                tr += `    </select>`;
                tr += `        </div>`;
                tr += `    </td>`;
                // tr += `    <td>`;
                // tr += `         <input data-id="${id}" class="form-control input-stock" disabled type="text" name="stock[]" id="input-stock-${id}"> `;
                // tr += `    </td>`;
                tr += `    <td>`;
                tr += `         <input disabled data-id="${id}" onkeypress="numberOnly(event)" class="form-control input-qty" type="text" name="qty[]" id="input-qty-${id}"> `;
                tr += `    </td>`;
                tr += `    <td>`;
                tr += `         <input disabled data-id="${id}" onkeypress="numberOnly(event)" class="form-control input-price" type="text" name="price[]" id="input-price-${id}"> `;
                tr += `    </td>`;
                tr += `    <td>`;
                tr += `         <input data-id="${id}" class="form-control input-total" disabled type="text" name="total[]" id="input-total-${id}"> `;
                tr += `    </td>`;
                if (id > 0) {
                    tr += `    <td>`;
                    tr += `         <a href="#" data-id="${id}" class="btn btn-remove-item btn-icon btn-danger"><i class="fas fa-times"></i></a>`;
                    tr += `    </td>`;
                }
                tr += `</tr>`;

            $("#items-table").append(tr);
            selectRefresh()
        }

        $(document).on("change", ".input-waste_id", function () {
            const id = $(this).data('id');
            const price = $(this).find(":selected").data("price")
            $(`#input-price-${id}`).val(rupiahFormat(price))
            $(`#input-price-${id}`).data("price", price)
            $(`#input-qty-${id}`).attr("disabled", false)
        });

        $(document).on("input", ".input-qty", function (e) {
            const id = $(this).data('id');
            const oldQty = $(this).data('qty');

            // const stock = $(`#input-stock-${id}`).val();
            // const data = $(`#input-waste_id-${id}`).select2('data')[0];
            // const stock = data.stock;

            // const qty = $(this).val();
            // const remaining_stock = stock - qty;
            // if (remaining_stock < 1) {
            //     e.preventDefault();
            //     $(this).val(oldQty);

            //     showFailedAlert("qty has reached the maximum limit");
            //     return false;
            // }

            // $(`#input-stock-${id}`).val(remaining_stock);

            // $(this).data('qty', qty);
            calculateTotal(id);
        });

        function calculateTotal(key) {


            let qty = $(`#input-qty-${key}`).val();
            // let price = $(`#input-price-${key}`).val();
            let price = $(`#input-price-${key}`).data("price");


            qty = parseInt(qty)
            price = parseFloat(price)

            if (isNaN(qty)) {
                qty = 0;
            }
            if (isNaN(price)) {
                price = 0;
            }
            let total = qty * price;
            $(`#input-total-${key}`).data("price", total)
            $(`#input-total-${key}`).val(rupiahFormat(total))

            calculateSubTotal();
        }


        function calculateSubTotal() {
            let totalQty = 0;
            let total = 0;
            $(".input-qty").each(function (item) {
                totalQty += $(this).val() ? parseInt($(this).val()) : 0;
            })
            $(".input-total").each(function (item) {
                total += $(this).data("price") ? parseFloat($(this).data("price")) : 0;
            })

            if (isNaN(totalQty)) {
                totalQty = 0;
            }
            console.log('total before : ', total);

            if (isNaN(total)) {
                total = 0;
            }
            console.log('total after : ', total);

            $(".txt-total-qty").html(totalQty)
            $(".txt-subtotal").html(rupiahFormat(total))
        }


        $(document).on('submit', '#main-form', async function (e) {
            e.preventDefault()
            const url = `${APP_URL}/api/transaction`;
            const method = "POST";

            const inputWastes = $('[id^=input-waste_id]');
            console.log('inputWastes : ', inputWastes);

            // const qty = $('[id^=input-qty]').val();
            // const inputPrices = $('[id^=input-price]');
            const items = [];
            $(inputWastes).each(function( index ) {
                const key = $(this).attr("id").match(/\d+/);
                const waste_id = $(this).val();
                const qty = $(`#input-qty-${key}`).val();

                if (!qty || qty == 0) {
                    showFailedAlert("Please fill in the quantity");
                    throw new Error("Please fill in the quantity");
                }

                const price = $(`#input-price-${key}`).val();
                const item = { waste_id, qty, price, };
                items.push(item);
            });

            if (items.length < 1) {
                    showFailedAlert("items cannot be empty");
                    throw new Error("items cannot be empty");
            }

            // console.log('waste_id : ', waste_id);
            console.log('items : ', items);
            const params = {
                items : items
            };


            const response = await request(url, method, params);
            if (response.status) {
                showAlertOnSubmit(response, '', '', `${APP_URL}/transaction`);
            }
        })
    </script>
@endpush
