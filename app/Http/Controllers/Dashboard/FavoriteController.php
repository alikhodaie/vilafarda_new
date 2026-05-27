<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Error;
use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Home;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Check if user is authenticated
        if (!$user) {
            // Log the issue for debugging
            \Log::error('User not authenticated in FavoriteController::index', [
                'session_id' => session()->getId(),
                'auth_guard' => auth()->getDefaultDriver(),
                'user_id' => auth()->id(),
                'request_url' => $request->fullUrl(),
                'request_headers' => $request->headers->all()
            ]);
            
            return redirect()->route('main.login.form')->with('error', 'Please log in to view your favorites.');
        }
        
        $favoritesQuery = $user->favorites()
            ->where('favoritable_type', Home::class)
            ->with(['favoritable' => function ($query) {
                $query->with(['province', 'city']);
            }])
            ->latest();

        $search = trim((string) $request->get('q', ''));
        if ($search !== '') {
            $favoritesQuery->whereHasMorph('favoritable', [Home::class], function ($query) use ($search) {
                $like = '%' . $search . '%';
                $query->where(function ($query) use ($like) {
                    $query->where('name', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhereHas('city', fn ($city) => $city->where('name', 'like', $like))
                        ->orWhereHas('province', fn ($province) => $province->where('name', 'like', $like));
                });
            });
        }

        $favorites = $favoritesQuery->paginate(10)->appends($request->only('q'));

        if ($request->is_mobile ?? false) {
            return view('dashboard.favorites.index-mobile', compact('favorites'));
        }

        return view('dashboard.favorites.index', compact('favorites'));
    }

    public function destroy(Favorite $favorite)
    {
        $user = auth()->user();
        
        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('main.login.form')->with('error', 'Please log in to perform this action.');
        }
        
        if ($favorite->user_id !== $user->id){
            abort(401);
        }

        try {
            DB::beginTransaction();

            $favorite->delete();

            DB::commit();
            return redirect()->back()->with('success', __('text.success.delete_item'));
        }
        catch(Exception $e) {
            DB::rollBack();
            Error::catch($e, __CLASS__, __FUNCTION__);
            return redirect()->back()->with('danger', __('text.whoops'));
        }
    }
}
