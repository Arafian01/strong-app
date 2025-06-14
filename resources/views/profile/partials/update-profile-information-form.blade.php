<section>
    <header>
        <h2 class="text-lg font-medium text-[var(--primary-dark)]">
            Informasi Profil
        </h2>
        <p class="mt-1 text-sm text-[var(--primary-dark)]/70">
            Informasi akun Anda.
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" type="text" class="mt-1 block w-full bg-[var(--light-gray)] text-[var(--primary-dark)]" :value="$user->name" readonly />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" class="mt-1 block w-full bg-[var(--light-gray)] text-[var(--primary-dark)]" :value="$user->email" readonly />
        </div>
    </div>
</section>