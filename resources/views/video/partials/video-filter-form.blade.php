<section class="w-full">
    <form method="GET" action="{{ route($action) }}" class="grid grid-cols-1 2xl:grid-cols-4 xl:grid-cols-4 lg:grid-cols-4 md:grid-cols-4 sm:grid-cols-4 place-items-end gap-4">
        <div class="w-full">
            <x-input-label for="title" :value="__('Başlık')" />
            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" placeholder="Başlık"
                value="{{ request('title') }}" />
        </div>

        <div class="w-full">
            <x-input-label for="countrySelect" :value="__('Ülke')" />
            <select id="countrySelect" name="country"
                class="w-full text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mt-1">
                <option value="" disabled selected hidden>Ülke seçin...</option>
            </select>
        </div>

        <div class="w-full">
            <x-input-label for="categorySelect" :value="__('Kategori')" />
            <select id="categorySelect" name="category"
                class="mt-1 block w-full text-sm rounded border-gray-300 dark:bg-gray-800 dark:text-gray-100">
                <option value="">Kategori Seç...</option>
                <option value="yapay-zeka" {{ request('category') == 'yapay-zeka' ? 'selected' : '' }}>Yapay Zeka
                </option>
                <option value="doga" {{ request('category') == 'doga' ? 'selected' : '' }}>Doğa</option>
                <option value="teknoloji" {{ request('category') == 'teknoloji' ? 'selected' : '' }}>Teknoloji</option>
                <option value="kisa-film" {{ request('category') == 'kisa-film' ? 'selected' : '' }}>Kısa Film</option>
            </select>
        </div>

        <div class="w-full flex items-center justify-center gap-1">
            <x-success-button class="w-full h-[42px] flex items-center justify-center px-0">Filtrele</x-success-button>
            <x-danger-button type="reset"
                onclick="window.location.href='{{ route($action) }}';"
                class="w-full h-[42px] flex items-center justify-center px-0">Sıfırla</x-danger-button>
        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', async function() {
        const countrySelect = document.getElementById('countrySelect');
        const citySelect = document.getElementById('citySelect');

        const selectedCountry = @json(request('country'));
        const selectedCity = @json(request('city'));

        let countrySelector = null;
        let citySelector = null;

        // Kategoriye Tom Select uygula (sabit seçenekler için)
        new TomSelect("#categorySelect", {
            create: false,
            placeholder: "Kategori seç...",
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        // 1. Ülkeleri getir
        try {
            const response = await fetch('https://countriesnow.space/api/v0.1/countries/');
            const result = await response.json();
            const countries = result.data;

            countries.forEach(country => {
                const option = document.createElement('option');
                option.value = country.country;
                option.textContent = country.country;
                if (country.country === selectedCountry) {
                    option.selected = true;
                }
                countrySelect.appendChild(option);
            });

            countrySelector = new TomSelect("#countrySelect", {
                create: false,
                placeholder: "Ülke seç...",
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                onInitialize() {
                    if (selectedCountry) {
                        this.setValue(selectedCountry);
                    }
                }
            });

            citySelector = new TomSelect("#citySelect", {
                create: false,
                placeholder: "Şehir seç...",
                sortField: {
                    field: "text",
                    direction: "asc"
                },
            });

            // Eğer önceden ülke seçilmişse şehirleri de getir
            if (selectedCountry) {
                await loadCities(selectedCountry, selectedCity);
            }
        } catch (error) {
            console.error("Ülkeler alınamadı:", error);
        }

        // Ülke değiştiğinde şehirleri getir
        countrySelect.addEventListener('change', async function() {
            const selected = this.value;
            citySelector.clearOptions();
            if (selected) {
                await loadCities(selected, null);
            }
        });

        async function loadCities(country, selectedCity = null) {
            try {
                const res = await fetch('https://countriesnow.space/api/v0.1/countries/cities', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        country: country
                    })
                });

                const data = await res.json();

                if (data.data && Array.isArray(data.data)) {
                    data.data.forEach(city => {
                        citySelector.addOption({
                            value: city,
                            text: city
                        });
                    });

                    citySelector.refreshOptions(false);

                    if (selectedCity) {
                        citySelector.setValue(selectedCity);
                    }
                }
            } catch (error) {
                console.error("Şehirler alınamadı:", error);
            }
        }
    });
</script>
