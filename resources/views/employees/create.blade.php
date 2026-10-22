<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Name Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <!-- Contact Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="+91 9876543210" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <!-- Radio Buttons: Gender -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                        <div class="flex items-center space-x-6 text-sm">
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="male" {{ old('gender') === 'male' ? 'checked' : '' }} required class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-gray-700">Male</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="female" {{ old('gender') === 'female' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-gray-700">Female</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="gender" value="other" {{ old('gender') === 'other' ? 'checked' : '' }} class="text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-gray-700">Other</span>
                            </label>
                        </div>
                    </div>

                    <!-- Checkboxes: Skills -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Technical Skills <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                            @foreach (\App\Models\Employee::SKILLS as $skill)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="skills[]" value="{{ $skill }}" {{ in_array($skill, (array) old('skills', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-gray-700">{{ $skill }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Date Pickers -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date of Birth <span class="text-red-500">*</span></label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Joining Date <span class="text-red-500">*</span></label>
                            <input type="date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <!-- Dependent Dropdowns: Country -> State -> City -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Country <span class="text-red-500">*</span></label>
                            <select id="country_id" name="country_id" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Select Country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">State <span class="text-red-500">*</span></label>
                            <select id="state_id" name="state_id" required disabled class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm disabled:bg-gray-100">
                                <option value="">Select State</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">City <span class="text-red-500">*</span></label>
                            <select id="city_id" name="city_id" required disabled class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm disabled:bg-gray-100">
                                <option value="">Select City</option>
                            </select>
                        </div>
                    </div>

                    <!-- Avatar Upload & Status -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
                            <input type="file" name="avatar" accept="image/png,image/jpeg,image/webp" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <span class="text-xs text-gray-500">Max size 2MB (JPG, PNG, WebP)</span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                            <select name="status" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t">
                        <a href="{{ route('employees.index') }}" class="px-4 py-2 border rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm font-semibold hover:bg-indigo-700">Save Employee</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="{{ asset('js/common.js') }}"></script>

    <!-- AJAX Cascading Dropdown Script -->
    <!-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const countrySelect = document.getElementById('country_id');
            const stateSelect = document.getElementById('state_id');
            const citySelect = document.getElementById('city_id');

            countrySelect.addEventListener('change', function () {
                const countryId = this.value;
                stateSelect.innerHTML = '<option value="">Select State</option>';
                citySelect.innerHTML = '<option value="">Select City</option>';
                stateSelect.disabled = true;
                citySelect.disabled = true;

                if (!countryId) return;

                fetch(`{{ url('/api/countries') }}/${countryId}/states`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(state => {
                            const opt = document.createElement('option');
                            opt.value = state.id;
                            opt.textContent = state.name;
                            stateSelect.appendChild(opt);
                        });
                        stateSelect.disabled = false;
                    });
            });

            stateSelect.addEventListener('change', function () {
                const stateId = this.value;
                citySelect.innerHTML = '<option value="">Select City</option>';
                citySelect.disabled = true;

                if (!stateId) return;

                fetch(`{{ url('/api/states') }}/${stateId}/cities`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(city => {
                            const opt = document.createElement('option');
                            opt.value = city.id;
                            opt.textContent = city.name;
                            citySelect.appendChild(opt);
                        });
                        citySelect.disabled = false;
                    });
            });
        });
    </script> -->
</x-app-layout>