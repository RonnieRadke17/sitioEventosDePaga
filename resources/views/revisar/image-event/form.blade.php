<!-- Input para múltiples imágenes -->
<label class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:border-blue-500">
    <input type="file" name="images[]" id="imageInput" accept="image/*" multiple class="hidden">
    <span class="text-gray-500">Arrastra tus imágenes aquí o haz clic</span>
</label>

<!-- Vista previa de imágenes -->
<div id="preview" class="grid grid-cols-3 gap-2 mt-4"></div>

<div class="flex justify-center mt-4">
    <button type="submit" class="w-1/2 px-4 py-2 bg-blue-500 text-white rounded-lg ml-2">{{$mode}}</button>
</div>