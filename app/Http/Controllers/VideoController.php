<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function list(Request $request): View
    {
        $query = Video::query();

        // Filtreler
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $videos = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('video.list', compact('videos'));
    }

    public function globe(Request $request): View
    {
        // Filtreler
        $query = Video::query()->whereNotNull('lat');

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $videos = $query->get();

        // Videoları şehir + ülkeye göre grupla
        $grouped = $videos->groupBy(function ($video) {
            return $video->city . '_' . $video->country;
        });

        // Yeni formatta dizi oluştur
        $formatted = $grouped->map(function ($group) {
            return [
                'city' => $group->first()->city,
                'country' => $group->first()->country,
                'lat' => $group->first()->lat,
                'lng' => $group->first()->lng,
                'videoCount' => $group->count(),
                'videos' => $group->map(function ($video) {
                    return [
                        'title' => $video->title,
                        'video_url' => asset('storage/' . $video->video_path),
                    ];
                })->values(),
            ];
        })->values();

        return view('video.video-globe', [
            'videoData' => $formatted->toJson(),
        ]);
    }

    public function create(): View
    {
        return view('video.create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title'    => 'required|string|max:255',
                'country'  => 'required|string|max:255',
                'city'     => 'required|string|max:255',
                'owner'    => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'video'    => 'required|file|mimetypes:video/mp4,video/quicktime|max:102400', // 100MB
            ]);

            // 1. Videoyu doğrudan kalıcı dizine kaydet
            $videoFilename = uniqid('video_') . '.' . $request->file('video')->getClientOriginalExtension();
            $videoPath = $request->file('video')->storeAs('videos', $videoFilename, 'public');

            // 2. Veritabanına kaydet
            $saveVideo = new Video([
                'title' => $request->title,
                'owner' => $request->owner,
                'category' => $request->category,
                'country' => $request->country,
                'city' => $request->city,
                'video_path' => 'videos/' . $videoFilename,
            ]);

            $saveVideo->setCoordinates();

            $saveVideo->save();

            // 3. Başarılı dönüş
            return redirect()->route('video.list')->with('success', 'Video başarıyla yüklendi.');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('video.create')->with('error', 'Video kaydedilemedi.');
        }
    }

    public function edit($id)
    {
        $video = Video::findOrFail($id);
        return view('video.edit', compact('video'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title'    => 'required|string|max:255',
                'country'  => 'required|string|max:255',
                'city'     => 'required|string|max:255',
                'owner'    => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'video'    => 'nullable|file|mimetypes:video/mp4,video/quicktime|max:102400', // Opsiyonel
            ]);

            $video = Video::findOrFail($id);

            // Video varsa yeni video kaydet ve eskisini sil
            if ($request->hasFile('video')) {
                // Eski videoyu sil
                Storage::disk('public')->delete($video->video_path);

                $videoFilename = uniqid('video_') . '.' . $request->file('video')->getClientOriginalExtension();
                $videoPath = $request->file('video')->storeAs('videos', $videoFilename, 'public');

                $video->video_path = 'videos/' . $videoFilename;
            }

            $video->title = $request->title;
            $video->owner = $request->owner;
            $video->category = $request->category;
            $video->country = $request->country;
            $video->city = $request->city;

            $video->setCoordinates(); // Eğer ülke/şehir değişmişse güncelle
            $video->save();

            return redirect()->route('video.list')->with('success', 'Video başarıyla güncellendi.');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'Video güncellenemedi.');
        }
    }

    public function delete($id)
    {
        try {
            $video = Video::findOrFail($id);

            // Video dosyası varsa sil
            if ($video->video_path && Storage::disk('public')->exists($video->video_path)) {
                Storage::disk('public')->delete($video->video_path);
            }

            // Veritabanından sil
            $video->delete();

            return redirect()->route('video.list')->with('success', 'Video başarıyla silindi.');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->route('video.list')->with('error', 'Video silinirken bir hata oluştu.');
        }
    }

    public function detail(Request $request, $country, $city): View
    {
        $videos = Video::where('country', $country)
            ->where('city', $city)
            ->paginate(10);
        return view('video.detail', compact('videos', 'country', 'city'));
    }
}
