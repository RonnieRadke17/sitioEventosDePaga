            <!-- Paso 1: Event Details -->
            <div id="step-1">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Event Name</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea name="description" id="description" class="w-full px-4 py-2 border rounded-lg"></textarea>
                </div>
                <div class="flex mb-4">
                    <div class="w-1/2 pr-2">
                        <label for="event_date" class="block text-gray-700">Event Date</label>
                        <input type="text" name="event_date" id="event_date" class="w-full px-4 py-2 border rounded-lg" placeholder="YYYY-MM-DD HH:MM">
                    </div>
                    <div class="w-1/2 pl-2">
                        <label for="registration_deadline" class="block text-gray-700">Registration Deadline</label>
                        <input type="text" name="registration_deadline" id="registration_deadline" class="w-full px-4 py-2 border rounded-lg" placeholder="YYYY-MM-DD HH:MM">
                    </div>
                </div>

                <div class="flex mb-4">
                    <div class="w-1/2 pr-2">
                        <label for="kit_delivery" class="block text-gray-700">Kit Delivery Date (Optional)</label>
                        <input type="text" name="kit_delivery" id="kit_delivery" class="w-full px-4 py-2 border rounded-lg" placeholder="YYYY-MM-DD HH:MM">
                    </div>
                    <div class="w-1/2 pl-2">
                        <label for="is_limited_capacity" class="block text-gray-700">Limited Capacity</label>
                        <select name="is_limited_capacity" id="is_limited_capacity" class="w-full px-4 py-2 border rounded-lg">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4 hidden" id="capacity-field">
                    <label for="capacity" class="block text-gray-700">Capacity</label>
                    <input type="number" name="capacity" id="capacity" class="w-full px-4 py-2 border rounded-lg" min="1">
                </div>
                
                <div class="mb-4">
                    <label for="price" class="block text-gray-700">Price</label>
                    <input type="number" name="price" id="price" class="w-full px-4 py-2 border rounded-lg" step="0.01" min="0">
                </div>
                @if ($mode == 'Editar')
                <div class="mb-4">
                    <label for="status" class="block text-gray-700">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border rounded-lg">
                        <option value="Activo">Active</option>
                        <option value="Inactivo">Inactive</option>
                        <option value="Cancelado">Cancelled</option>
                    </select>
                </div>
                @endif

                <div class="flex justify-center mt-4">
                    <button type="button" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2" id="to-step-2">Next</button>
                </div>
                
            </div>

            <!-- Paso 2: Activities -->
            <div id="step-2" class="hidden">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="py-2 px-4 text-left">Nombre</th>
                            <th class="py-2 px-4 text-left">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                        <tr class="border-b hover:bg-gray-50 cursor-pointer activity-row" data-activity-id="{{ $activity->id }}">
                            <td class="py-2 px-4">{{ $activity->name }}</td>
                            <td class="py-2 px-4 text-center">
                                <input type="checkbox" name="selected_activities[]" value="{{ $activity->id }}" {{ isset($eventActivities[$activity->id]) ? 'checked' : '' }}>
                            </td>
                        </tr>
                        <tr class="hidden activity-details" id="activity-{{ $activity->id }}-details">
                            <td colspan="2" class="py-2 px-4">
                                @foreach(['M', 'F', 'Mix'] as $gender)
                                <div class="mb-2">
                                    <label class="block font-semibold">
                                        <input type="checkbox" name="genders[{{ $activity->id }}][{{ $gender }}]" value="{{ $gender }}"
                                        @if(isset($eventActivities[$activity->id]) && $eventActivities[$activity->id]->contains('gender', $gender))
                                            checked
                                        @endif> {{ $gender }}
                                    </label>
                                    <div class="pl-4 hidden gender-subs" id="activity-{{ $activity->id }}-gender-{{ $gender }}-subs">
                                        @foreach ($subs as $sub)
                                        <label class="block">
                                            <input type="checkbox" name="subs[{{ $activity->id }}][{{ $gender }}][]" value="{{ $sub->id }}"
                                            @if(isset($eventActivities[$activity->id]))
                                                @foreach($eventActivities[$activity->id] as $eventActivity)
                                                    @if($eventActivity->gender == $gender && $eventActivity->sub_id == $sub->id)
                                                        checked
                                                    @endif
                                                @endforeach
                                            @endif> {{ $sub->name }}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="flex justify-between mt-4">
                    <button type="button" class="w-1/2 px-4 py-2 bg-gray-500 text-white rounded-lg" id="to-step-1">Previous</button>
                    <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{$mode}}</button>
                </div>
            </div>

<!-------aqui van dos mapas el cual uno es para entrega de kits y otro es para lugar del evento--------->

