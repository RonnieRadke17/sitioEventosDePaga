<div>
    <label for="name" class="block text-gray-700">Nombre</label>
    <input type="text" name="name" id="name" value="{{ old('name', $activity->name ?? '') }}" 
        class="w-full px-4 py-2 border rounded-lg @error('name') border-red-500 @enderror">
    @error('name')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="mix" class="block text-gray-700">Mixto</label>
    <select name="mix" id="mix" class="w-full px-4 py-2 border rounded-lg @error('mix') border-red-500 @enderror">
        <option value="0" {{ old('mix', $activity->mix ?? '') == 0 ? 'selected' : '' }}>No</option>
        <option value="1" {{ old('mix', $activity->mix ?? '') == 1 ? 'selected' : '' }}>Sí</option>
    </select>
    @error('mix')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <input type="submit" value="{{ $mode }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg">
</div>