<x-app-layout>
    <x-slot name="header">
        <div class="w-full h-auto flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $city }}, {{ $country }} şehrindeki videolar
            </h2>

            <a href="{{ route('video.create') }}">
                <x-primary-button type="button">
                    Video Yükle
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-12 px-6 sm:px-0 md:px-0 lg:px-0 xl:px-0 2xl:px-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('video.partials.video-filter-form', ['action' => 'video.list'])

            <div class="overflow-x-auto border border-gray-100/10 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Video Başlığı
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Video Sahibi
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kategori
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Lokasyon
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                İşlemler
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @if (count($videos) > 0)
                            @foreach ($videos as $row)
                                <tr>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $row->title }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $row->owner }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $row->category }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $row->country }} - {{ $row->city }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end space-x-1">
                                        <a href="{{ route('video.edit', $row->id) }}">
                                            <x-secondary-button type="button">
                                                Düzenle / Görüntüle
                                            </x-secondary-button>
                                        </a>

                                        <form method="POST" action="{{ route('video.delete', $row->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button
                                                onclick="return confirm('Emin misiniz?')">Sil</x-danger-button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                                    Henüz video eklenmemiş
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $videos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
