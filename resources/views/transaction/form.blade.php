<div class="card-body">
    {{-- <x-form.input class="w-50" disabled label="number" name="number"/>
    <x-form.input class="w-50" value="{{ now()->format('Y-m-d') }}" disabled label="date" name="date"/> --}}

    <div class="d-flex justify-content-between mb-3 align-items-center">
        <p><b>Waste Items</b></p>
        <button class="btn btn-sm btn-primary btn-add-item">Add Item</button>

    </div>

    <table id="items-table" class="table table-bordered table-md">
        <tbody>
            <tr>
                <th>Waste</th>
                {{-- <th>Stock</th> --}}
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                {{-- <th>Inventory</th>
                <th>Stock</th> --}}
                <th colspan="1"></th>
                <th colspan="2"><h1>Total Qty : <span class="txt-total-qty"></span></h1></th>
                <th><h1>Subtotal : <span class="txt-subtotal"></span></h1></th>
            </tr>
        </tfoot>
    </table>
</div>
