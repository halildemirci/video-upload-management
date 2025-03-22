<x-app-layout>
    <x-slot name="header">
        <div class="w-full h-auto flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Video Listesi') }}
            </h2>

            <x-primary-button type="button" x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'upload-new-video')">
                Video Yükle
            </x-primary-button>
        </div>
    </x-slot>

    <div class="py-12 px-6 sm:px-0 md:px-0 lg:px-0 xl:px-0 2xl:px-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="post" action="{{ route('video.update', $video->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Video Güncelle
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Eklenmiş olan video üzerinde düzenlemeler yapabilirsiniz.
                </p>

                <div class="flex flex-col gap-6 mt-6">
                    <div class="w-full">
                        <x-input-label for="title" :value="__('Video Başığı')" />
                        <x-text-input id="title" name="title" type="text"
                            class="mt-1 block w-full bg-none placeholder:text-xs placeholder:font-semibold"
                            placeholder="Video Başığı" :value="$video->title" />
                    </div>

                    <div class="flex items-center justify-center gap-4">
                        <div class="w-full">
                            <x-input-label for="countrySelect" :value="__('Ülke Seçimi')" />
                            <select id="countrySelect" name="country"
                                class="w-full text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mt-1">
                                <option value="{{ $video->country }}" selected>{{ $video->country }}</option>
                            </select>
                        </div>

                        <div class="w-full">
                            <x-input-label for="citySelect" :value="__('Şehir Seçimi')" />
                            <select id="citySelect" name="city" placeholder="Önce bir ülke seçin..."
                                class="w-full text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mt-1">
                                <option value="{{ $video->city }}" selected>{{ $video->city }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-4">
                        <div class="w-full">
                            <x-input-label for="owner" :value="__('Video Sahibi')" />
                            <x-text-input id="owner" name="owner" type="text"
                                class="mt-1 block w-full bg-none placeholder:text-xs placeholder:font-semibold"
                                placeholder="Video Sahibi" :value="$video->owner" />
                        </div>

                        <div class="w-full">
                            <x-input-label for="categorySelect" :value="__('Kategori Seçimi')" />
                            <select id="categorySelect" name="category" placeholder="Kategori seçin..."
                                class="w-full text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 mt-1">
                                <option value="yapay-zeka" @selected($video->category == 'yapay-zeka')>Yapay Zeka</option>
                                <option value="doga" @selected($video->category == 'doga')>Doğa</option>
                                <option value="teknoloji" @selected($video->category == 'teknoloji')>Teknoloji</option>
                                <option value="kisa-film" @selected($video->category == 'kisa-film')>Kısa Film</option>
                            </select>
                        </div>
                    </div>

                    <!-- Video yükleme -->
                    <div x-data="videoUploader()" class="w-full">
                        <x-input-label for="video" :value="__('Yeni Video (isteğe bağlı)')" />
                        <input type="file" id="video" name="video" accept="video/*"
                            class="block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4
                                file:rounded file:border-0 file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100
                                dark:file:bg-gray-700 dark:file:text-gray-200 dark:hover:file:bg-gray-600
                                cursor-pointer !outline-none">
                        <p class="text-sm text-gray-500 mt-1">Eğer video yüklemezseniz mevcut video korunur.</p>

                        @if ($video->video_path)
                            <div class="mt-4">
                                <x-input-label :value="__('Mevcut Video')" />
                                <video controls
                                    class="mt-2 rounded-lg shadow border border-gray-300 dark:border-gray-700 w-full max-w-md">
                                    <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
                                    Tarayıcınız video etiketini desteklemiyor.
                                </video>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-success-button class="ms-3">
                        Kaydet
                    </x-success-button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async function() {
            const countrySelect = document.getElementById('countrySelect');
            const citySelect = document.getElementById('citySelect');
            const categorySelect = document.getElementById('categorySelect');

            let countrySelector = null;
            let citySelector = null;
            let categorySelector = null;

            // Tom Select başlat
            categorySelector = new TomSelect("#categorySelect", {
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
                    countrySelect.appendChild(option);
                });

                // Tom Select başlat
                countrySelector = new TomSelect("#countrySelect", {
                    create: false,
                    placeholder: "Ülke seç...",
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });

                citySelector = new TomSelect("#citySelect", {
                    create: false,
                    placeholder: "Şehir seç...",
                    sortField: {
                        field: "text",
                        direction: "asc"
                    }
                });
            } catch (error) {
                console.error("Ülkeler alınamadı:", error);
            }

            // default illeri getir
            try {
                const res = await fetch(
                    'https://countriesnow.space/api/v0.1/countries/cities', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            country: "{{ $video->country }}"
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
                }
            } catch (error) {
                console.error("Şehirler alınamadı:", error);
            }

            // 2. Ülke seçilince şehirleri getir
            countrySelect.addEventListener('change', async function() {
                const selectedCountry = this.value;

                // Önceki şehirleri temizle
                citySelector.clearOptions();

                // if (!selectedCountry) return;

                try {
                    const res = await fetch(
                        'https://countriesnow.space/api/v0.1/countries/cities', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                country: selectedCountry
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
                    }
                } catch (error) {
                    console.error("Şehirler alınamadı:", error);
                }
            });
        });
    </script>

    <script>
        function videoUploader() {
            return {
                videoUrl: null,
                durationText: '',
                error: '',

                handleVideo(event) {
                    this.error = '';
                    const file = event.target.files[0];

                    if (!file || !file.type.startsWith('video/')) {
                        this.error = 'Lütfen geçerli bir video dosyası seçin.';
                        return;
                    }

                    const url = URL.createObjectURL(file);
                    this.videoUrl = url;

                    // Geçici video öğesi oluşturup süreyi kontrol et
                    const tempVideo = document.createElement('video');
                    tempVideo.preload = 'metadata';
                    tempVideo.src = url;

                    tempVideo.onloadedmetadata = () => {
                        const duration = tempVideo.duration;
                        const min = 60;
                        const max = 1800;

                        if (duration < min || duration > max) {
                            this.error = 'Video süresi 1 dakika ile 30 dakika arasında olmalıdır.';
                            this.videoUrl = null;
                        } else {
                            const minutes = Math.floor(duration / 60);
                            const seconds = Math.floor(duration % 60);
                            this.durationText = `${minutes} dakika ${seconds} saniye`;
                        }

                        URL.revokeObjectURL(url); // Hafızayı temizle
                    };
                }
            }
        }
    </script>
</x-app-layout>
