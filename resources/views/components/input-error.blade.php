@props(['messages'])
<style>
    .error-messages {
    font-size: 0.775rem;
    color: #dc2626;
    margin-top: 0.25rem;
    list-style-type: none;
    padding: 0;
    font-weight: bold;
    text-align: center;
    border-color: #f5c6cb;
}

.error-messages li {
    margin: 0 auto;
    position: relative;
    right: 15px;
    top: 5px;
}


</style>

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'error-messages']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
