{{-- falta el que si es de un form de create y edit tenga el valor anterior --}}
<div class="{{ $attributes->get('class') ?? 'mb-4' }}">
    <div class="relative">
        <input type="{{$attributes->get('type') ?? 'text'}}" name="{{ $attributes->get('name')}}" id="{{ $attributes->get('id')}}" value="{{ old($attributes->get('name')) }}" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-sm text-gray-900 bg-gray-50 dark:bg-gray-700 border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
        <label for="email" class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] start-2.5 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto">{{$description}}</label>
    </div>
    @error($attributes->get('name'))
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror
</div>

