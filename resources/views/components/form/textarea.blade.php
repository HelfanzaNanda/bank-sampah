<div class="form-group w-100">
    <label class="text-capitalize">{{ $label }}</label>
    <textarea {{ $attributes->merge(['class' => 'form-control']) }} name="{{ $name }}" id="input-{{ $name }}" cols="30" rows="10" style="height: 159px;"></textarea>
</div>
