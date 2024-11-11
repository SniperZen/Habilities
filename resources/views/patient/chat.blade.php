<x-patient-layout>

<style>
    .file-download {
    font-size: 13px;
    display: block;
    color: rgb(255, 255, 255);
    font-weight: 600;
    margin-top: 10px;
    text-decoration: none;
    border-width: 1px;
    border-style: solid;
    border-color: rgba(0, 0, 0, 0.08);
    border-image: initial;
    padding: 13px 16px;
    border-radius: 20px;
    transition: transform 0.3s, background 0.3s;
    background: var(--primary-color) !important;
}
</style>

@include('Chatify::pages.app')
</x-patient-layout>