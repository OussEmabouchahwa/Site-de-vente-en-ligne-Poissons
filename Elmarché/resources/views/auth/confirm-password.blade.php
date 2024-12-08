<x-guest-layout>
    <div class="mb-4 text-sm text-blue-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-blue-600" />

            <x-text-input id="password" class="block mt-1 w-full border-black"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
