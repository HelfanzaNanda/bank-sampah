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
                    @include('waste.form');
                    <div class="card-footer text-right">
                        <a href="{{ route('waste.index') }}" class="btn-back btn btn-secondary mr-2">Back</a>
                        <button class="btn-submit btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
    <script>
        const id = "{{ $id }}";

        $(document).ready( async function () {
            await getData();
        });

        async function getData() {

            $(".btn-submit").hide();

            const url = `${APP_URL}/api/waste/${id}`;
            const method = "GET";
            const response = await request(url, method);

            if (response.status) {
                const params = Object.fromEntries(new FormData(document.getElementById("main-form")).entries());
                Object.keys(params).forEach(field => {
                    $(`#input-${field}`).attr("disabled", true);
                    $(`#input-${field}`).val(response.data[field]);
                })
                $(`#input-file`).attr("disabled", true);
                $('#preview-image').attr('src', response.data.file.fileurl);

            }
        }
    </script>
@endpush
