@extends('layouts.app')
@section('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full md:w-2/3 lg:w-1/2">
        <!-- Step indicators -->
        <div class="mb-6 flex justify-between items-center">
            <div class="w-1/3 text-center">
                <span class="block w-8 h-8 bg-blue-500 text-white rounded-full mx-auto">1</span>
                <span class="text-gray-700 text-sm">Event Details</span>
            </div>
            <div class="w-1/3 text-center">
                <span class="block w-8 h-8 bg-gray-300 text-white rounded-full mx-auto">2</span>
                <span class="text-gray-700 text-sm">Activities</span>
            </div>
        </div>

        <!-- Formulario -->
        <form action="{{ route('event.store') }}" method="post" id="multi-step-form">
            @csrf
            @include('event.form',['mode'=>'Registrar'])
        
        </form>
    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr('#event_date', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
        flatpickr('#kit_delivery', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
        flatpickr('#registration_deadline', {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle capacity field visibility based on limited capacity selection
        document.getElementById('is_limited_capacity').addEventListener('change', function() {
            const capacityField = document.getElementById('capacity-field');
            if (this.value == '1') {
                capacityField.classList.remove('hidden');
            } else {
                capacityField.classList.add('hidden');
            }
        });

        // Show/hide steps
        document.getElementById('to-step-2').addEventListener('click', function() {
            document.getElementById('step-1').classList.add('hidden');
            document.getElementById('step-2').classList.remove('hidden');
        });

        document.getElementById('to-step-1').addEventListener('click', function() {
            document.getElementById('step-1').classList.remove('hidden');
            document.getElementById('step-2').classList.add('hidden');
        });

        // Toggle activity details visibility
        document.querySelectorAll('.activity-row').forEach(row => {
            row.addEventListener('click', function() {
                const activityId = this.dataset.activityId;
                const detailsRow = document.getElementById(`activity-${activityId}-details`);
                detailsRow.classList.toggle('hidden');
            });
        });

        // Toggle gender subs visibility
        document.querySelectorAll('.activity-details input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const activityId = this.name.match(/genders\[(\d+)\]/)[1];
                const gender = this.value;
                const subsDiv = document.getElementById(`activity-${activityId}-gender-${gender}-subs`);
                if (this.checked) {
                    subsDiv.classList.remove('hidden');
                } else {
                    subsDiv.classList.add('hidden');
                }
            });
        });

        // Initialize gender subs visibility
        document.querySelectorAll('.activity-details input[type="checkbox"]').forEach(checkbox => {
            const activityId = checkbox.name.match(/genders\[(\d+)\]/)[1];
            const gender = checkbox.value;
            const subsDiv = document.getElementById(`activity-${activityId}-gender-${gender}-subs`);
            if (checkbox.checked) {
                subsDiv.classList.remove('hidden');
            }
        });
    });
</script>
@endsection
