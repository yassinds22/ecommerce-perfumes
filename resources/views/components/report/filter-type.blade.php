@props(['name', 'label', 'options', 'selected' => ''])

<div class="form-group">
    <label>{{ $label }}</label>
    <select name="{{ $name }}" id="{{ $name }}" class="form-control report-filter">
        @foreach($options as $val => $text)
            <option value="{{ $val }}" {{ $selected == $val ? 'selected' : '' }}>{{ $text }}</option>
        @endforeach
    </select>
</div>
