<style>
    img{ max-width:180px; }

    input[type=file] {
        padding:10px;
        /* background:#2d2d2d; */
    }
</style>

<div class="d-flex flex-column">
    <label class="text-capitalize">{{ $label }}</label>
    {{-- <input type='file' onchange="readURL(this);" name="{{ $name }}" id="input-{{ $name }}" /> --}}
    <input {{ $attributes->merge(['class' => 'form-control']) }} type='file' onchange="readURL(this);" name="file" id="input-file">
    <input  type="hidden" name="{{ $name }}" id="input-{{ $name }}">
    <img class="mt-3" id="preview-image" src="http://placehold.it/180" alt="your image" />
</div>
