@props(['name', 'label', 'value' => ''])

<div class="form-group">
    <label>{{ $label }}</label>
    <input type="date" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}" class="form-control report-filter">
</div>
