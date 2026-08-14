<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\View::composer('layouts.mobile', function ($view) {
            $globalNotifs = collect();
            if (auth()->check() && auth()->user()->role_id == 3) {
                $wali = \App\Models\WaliSantri::where('user_id', auth()->id())->first();
                if ($wali) {
                    $activeSantri = \App\Models\Santri::where('id', $wali->santri_id)->first();
                    if ($activeSantri) {
                        $latestPengumumans = \App\Models\Pengumuman::orderBy('published_at', 'desc')->take(2)->get();
                        
                        $pengumumanNotifs = collect()->merge($latestPengumumans)->map(function($item) {
                            return [
                                'icon' => 'campaign',
                                'title' => $item->judul,
                                'desc' => \Illuminate\Support\Str::limit($item->isi, 40),
                                'time' => \Carbon\Carbon::parse($item->published_at)->diffForHumans(),
                                'timestamp' => \Carbon\Carbon::parse($item->published_at)->timestamp
                            ];
                        });

                        $latestIzin = \App\Models\Perizinan::where('santri_id', $activeSantri->id)
                            ->whereIn('status', ['disetujui', 'ditolak'])
                            ->orderBy('updated_at', 'desc')
                            ->take(2)->get()->map(function($item) {
                                $statusText = $item->status == 'disetujui' ? 'Disetujui' : 'Ditolak';
                                $icon = $item->status == 'disetujui' ? 'check_circle' : 'cancel';
                                return [
                                    'icon' => $icon,
                                    'title' => 'Izin ' . $statusText,
                                    'desc' => 'Pengajuan izin ' . str_replace('_', ' ', $item->jenis) . ' ' . $statusText . ' oleh admin.',
                                    'time' => $item->updated_at->diffForHumans(),
                                    'timestamp' => $item->updated_at->timestamp
                                ];
                            });

                        $globalNotifs = collect()
                            ->merge($pengumumanNotifs)
                            ->merge($latestIzin)
                            ->sortByDesc('timestamp')
                            ->values()
                            ->take(4);
                    }
                }
            }
            $view->with('globalNotifs', $globalNotifs);
        });
    }
}
