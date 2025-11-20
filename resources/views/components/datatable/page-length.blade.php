@props([
    'id' => 'pageLength',
    'selected' => 10,
    'options' => [10, 25, 50, 100]
])

<select id="{{ $id }}" class="form-select form-select-sm">
    @foreach($options as $option)
        <option value="{{ $option }}" {{ $option == $selected ? 'selected' : '' }}>
            {{ $option }}
        </option>
    @endforeach
    <option value="-1" {{ $selected == -1 ? 'selected' : '' }}>
        All
    </option>
</select>