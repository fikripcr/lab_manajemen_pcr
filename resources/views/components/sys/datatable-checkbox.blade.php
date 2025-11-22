@props(['name' => 'item', 'value' => null, 'id' => null, 'checked' => false, 'disabled' => false])

<input
    type="checkbox"
    name="{{ $name }}[]"
    value="{{ $value }}"
    id="{{ $id }}"
    @if($checked) checked @endif
    @if($disabled) disabled @endif
    class="form-check-input dt-checkboxes select-row">
