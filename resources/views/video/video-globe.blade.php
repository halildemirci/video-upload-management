<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Video Haritası') }}
        </h2>
    </x-slot>

    <div class="py-12 px-6 sm:px-0 md:px-0 lg:px-0 xl:px-0 2xl:px-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 flex flex-col items-center justify-center">
            @include('video.partials.video-filter-form', ['action' => 'video.globe'])

            <div class="rounded-full overflow-hidden" id="globe-container" style="width: 900px; height: 900px;"></div>
        </div>
    </div>

    <script src="//cdn.jsdelivr.net/npm/globe.gl"></script>

    <script type="module">
        import * as THREE from 'https://esm.sh/three';

        const videoData = {!! $videoData !!};

        function getMarkerSvg(videoCount) {
            return `
                    <div style="
                        width: 24px;
                        height: 24px;
                        border-radius: 50%;
                        background-color: rgba(0, 0, 0, 0.7);
                        color: white;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 12px;
                        font-weight: bold;
                        border: 2px solid white;
                    ">
                        ${videoCount}
                    </div>
                    `;
        }

        function urlEncoded(str) {
            return encodeURIComponent(str);
        }

        const world = new Globe(document.getElementById('globe-container'))
            .globeImageUrl('//cdn.jsdelivr.net/npm/three-globe/example/img/earth-blue-marble.jpg')
            .bumpImageUrl('//cdn.jsdelivr.net/npm/three-globe/example/img/earth-topology.png')
            .backgroundImageUrl('//cdn.jsdelivr.net/npm/three-globe/example/img/night-sky.png')
            .width(900)
            .height(900)
            .htmlElementsData(videoData)
            .htmlElement(data => {
                const el = document.createElement('div');
                el.innerHTML = getMarkerSvg(data.videoCount);
                el.style.color = 'white';
                el.style.width = `24px`;
                // el.style.transition = 'opacity 250ms';

                el.style['pointer-events'] = 'auto';
                el.style.cursor = 'pointer';
                el.onclick = () => window.location.href = `/video-detay/${urlEncoded(data.country)}/${urlEncoded(data.city)}`;
                return el;
            })
            .htmlElementVisibilityModifier((el, isVisible) => el.style.opacity = isVisible ? 1 : 0);

        // custom globe material
        const globeMaterial = world.globeMaterial();
        globeMaterial.bumpScale = 10;
        new THREE.TextureLoader().load('//cdn.jsdelivr.net/npm/three-globe/example/img/earth-water.png', texture => {
            globeMaterial.specularMap = texture;
            globeMaterial.specular = new THREE.Color('grey');
            globeMaterial.shininess = 15;
        });

        const directionalLight = world.lights().find(light => light.type === 'DirectionalLight');
        directionalLight && directionalLight.position.set(1, 1, 1); // change light position to see the specularMap's effect
    </script>
</x-app-layout>
